<?php

declare(strict_types=1);

namespace App\Services\Audit;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Models\Course;
use App\Models\PaymentReceipt;
use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AdminActivityLogger
{
    /**
     * Route patterns to ignore from general request logging (e.g. high-frequency internal polling).
     *
     * @var list<string>
     */
    protected static array $ignoredRoutes = [
        'admin.support.poll',
        'admin.courses.promo.mux.upload_status',
        'admin.courses.videos.mux.upload_status',
    ];

    /**
     * Sensitive input keys that must be sanitized or redacted.
     *
     * @var list<string>
     */
    protected static array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'passkey',
        'credential',
        'raw_id',
        'client_data_json',
        'authenticator_data',
        'signature',
        'user_handle',
        'token',
        '_token',
        'remember_token',
        'secret',
        'api_key',
        'authorization',
        'credit_card',
        'card_number',
        'cvv',
    ];

    /**
     * Record an audit log entry safely.
     *
     * @param  array<string, mixed>  $data
     */
    public static function log(array $data): ?AdminActivityLog
    {
        try {
            $admin = $data['admin'] ?? auth('admin')->user();

            if (! isset($data['admin_id']) && $admin instanceof Admin) {
                $role = $admin->role;
                $data['admin_id'] = $admin->id;
                $data['admin_name'] = $admin->name;
                $data['admin_email'] = $admin->email;
                $data['role_name'] = $role ? $role->name : ($admin->isSuperAdmin() ? 'مالك المنصة' : 'مشرف');
            }

            unset($data['admin']);

            return AdminActivityLog::create($data);
        } catch (Throwable $e) {
            // Never let logging failure crash the user's primary application request
            report($e);

            return null;
        }
    }

    /**
     * Log an HTTP Request automatically from middleware.
     */
    public static function logRequest(
        Request $request,
        Response $response,
        ?Admin $admin = null,
        ?float $startTime = null
    ): ?AdminActivityLog {
        try {
            $routeName = $request->route()?->getName();

            // Ignore pure polling/heartbeat routes to avoid flooding the audit log
            if ($routeName && in_array($routeName, static::$ignoredRoutes, true)) {
                return null;
            }

            // Also ignore static asset requests
            if ($request->is('*.css', '*.js', '*.ico', '*.png', '*.jpg', '*.svg', '*.woff*')) {
                return null;
            }

            $admin = $admin
                ?? auth('admin')->user()
                ?? ($request->user('admin') instanceof Admin ? $request->user('admin') : null)
                ?? ($request->user() instanceof Admin ? $request->user() : null);

            $method = strtoupper($request->method());
            $statusCode = $response->getStatusCode();

            // Calculate duration in milliseconds if start time was provided
            $durationMs = $startTime !== null ? round((microtime(true) - $startTime) * 1000, 2) : null;

            // Determine status
            $status = match (true) {
                $statusCode >= 500 => 'failed',
                $statusCode === 403 => 'warning',
                $statusCode === 422 => 'warning',
                $statusCode >= 400 => 'failed',
                default => 'success',
            };

            // Map route to functional module, action, description, and target subject
            $meta = static::resolveRouteMetadata($request, $admin);

            // Prepare sanitized properties
            $requestParams = static::sanitizePayload($request->except(['_token', '_method']));
            $queryParams = static::sanitizePayload($request->query());
            $routeParams = static::extractRouteParameters($request);

            $properties = [
                'response_status' => $statusCode,
                'duration_ms' => $durationMs,
            ];

            if (! empty($requestParams)) {
                $properties['request_params'] = $requestParams;
            }

            if (! empty($queryParams)) {
                $properties['query_params'] = $queryParams;
            }

            if (! empty($routeParams)) {
                $properties['route_params'] = $routeParams;
            }

            $modelChanges = (array) $request->attributes->get('_admin_model_audit_changes', []);
            if (! empty($modelChanges)) {
                $properties['model_changes'] = $modelChanges;

                // If subject was not explicitly discovered by route, take it from first model change
                if (empty($meta['subject_type']) && isset($modelChanges[0])) {
                    $meta['subject_name'] = $modelChanges[0]['name'] ?? null;
                }
            }

            $adminRole = $admin?->role;
            $adminRoleName = $adminRole ? $adminRole->name : ($admin?->isSuperAdmin() ? 'مالك المنصة' : ($admin ? 'مشرف' : null));

            return AdminActivityLog::create([
                'admin_id' => $admin?->id,
                'admin_name' => $admin?->name,
                'admin_email' => $admin?->email,
                'role_name' => $adminRoleName,
                'module' => $meta['module'],
                'action' => $meta['action'],
                'description' => $meta['description'],
                'subject_type' => $meta['subject_type'] ?? null,
                'subject_id' => $meta['subject_id'] ?? null,
                'subject_name' => $meta['subject_name'] ?? null,
                'method' => $method,
                'url' => $request->fullUrl(),
                'route_name' => $routeName,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
                'properties' => $properties,
            ]);
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Log authentication events (login, logout, failed attempt, lockout).
     *
     * @param  array<string, mixed>  $details
     */
    public static function logAuthentication(
        string $event,
        Admin|string|null $adminOrEmail = null,
        string $status = 'success',
        ?string $reason = null,
        array $details = []
    ): ?AdminActivityLog {
        try {
            $admin = $adminOrEmail instanceof Admin ? $adminOrEmail : null;
            $email = $admin ? $admin->email : (is_string($adminOrEmail) ? $adminOrEmail : null);

            $adminName = $admin !== null ? $admin->name : ($email ? "مشرف ({$email})" : 'زائر / غير مسجل');
            $adminRole = $admin?->role;
            $roleName = $adminRole ? $adminRole->name : ($admin?->isSuperAdmin() ? 'مالك المنصة' : null);

            $description = match ($event) {
                'login' => "تسجيل دخول المشرف ({$adminName}) بنجاح إلى لوحة الإدارة المركزية",
                'logout' => "تسجيل خروج المشرف ({$adminName}) من لوحة الإدارة",
                'failed_login' => "محاولة تسجيل دخول فاشلة للمشرف باستخدام البريد: ({$email})".($reason ? " - {$reason}" : ''),
                'lockout' => "تم تقييد محاولات الدخول مؤقتاً لحساب: ({$email}) بسبب تجاوز عدد المحاولات المسموح بها",
                default => "حدث أمان ومصادقة: {$event}",
            };

            $properties = array_merge([
                'event' => $event,
                'attempted_email' => $email,
                'reason' => $reason,
            ], $details);

            return AdminActivityLog::create([
                'admin_id' => $admin?->id,
                'admin_name' => $admin?->name,
                'admin_email' => $email,
                'role_name' => $roleName,
                'module' => 'auth',
                'action' => $event,
                'description' => $description,
                'subject_type' => $admin ? Admin::class : null,
                'subject_id' => $admin?->id,
                'subject_name' => $admin?->name,
                'method' => request()->method() ?: 'POST',
                'url' => request()->fullUrl() ?: url('/admin/login'),
                'route_name' => request()->route()?->getName() ?: 'admin.login',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status' => $status,
                'properties' => $properties,
            ]);
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Map request and route to module, action, description, and subject entity.
     *
     * @return array{module: string, action: string, description: string, subject_type?: string|null, subject_id?: int|string|null, subject_name?: string|null}
     */
    protected static function resolveRouteMetadata(Request $request, ?Admin $admin): array
    {
        $routeName = $request->route()?->getName() ?? '';
        $method = strtoupper($request->method());
        $path = $request->path();

        // 1. Dashboard
        if ($routeName === 'admin.dashboard' || $path === 'admin/dashboard') {
            return [
                'module' => 'dashboard',
                'action' => 'view',
                'description' => 'استعراض لوحة الإحصائيات المركزية',
            ];
        }

        // 2. Courses, Levels & Lessons
        if (str_starts_with($routeName, 'admin.courses.')) {
            $course = $request->route('course');
            $courseModel = $course instanceof Course ? $course : (is_numeric($course) ? Course::find($course) : null);
            $courseTitle = $courseModel?->title_ar ?: ($courseModel?->title ?: ($course ? "كورس #{$course}" : ''));

            return match ($routeName) {
                'admin.courses.index' => [
                    'module' => 'courses',
                    'action' => 'view',
                    'description' => 'استعراض قائمة الدورات التدريبية والمحاضرات',
                ],
                'admin.courses.show' => [
                    'module' => 'courses',
                    'action' => 'view',
                    'description' => "عرض تفاصيل الدورة التدريبية: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.store' => [
                    'module' => 'courses',
                    'action' => 'create',
                    'description' => 'إنشاء دورة تدريبية جديدة: '.($request->input('title_ar') ?: $request->input('title', 'دورة جديدة')),
                    'subject_type' => Course::class,
                    'subject_name' => $request->input('title_ar') ?: $request->input('title'),
                ],
                'admin.courses.edit' => [
                    'module' => 'courses',
                    'action' => 'view',
                    'description' => "فتح صفحة تعديل الدورة التدريبية: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.update' => [
                    'module' => 'courses',
                    'action' => 'update',
                    'description' => "تحديث بيانات الدورة التدريبية: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.destroy' => [
                    'module' => 'courses',
                    'action' => 'delete',
                    'description' => "حذف الدورة التدريبية نهائياً: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.price.update' => [
                    'module' => 'courses',
                    'action' => 'update',
                    'description' => "تعديل سعر وتكلفة الدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.toggle-auto-enroll' => [
                    'module' => 'courses',
                    'action' => 'toggle',
                    'description' => "تبديل حالة التسجيل التلقائي للدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.levels.store' => [
                    'module' => 'courses',
                    'action' => 'create',
                    'description' => "إضافة مستوى تعليمي جديد للدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.levels.update' => [
                    'module' => 'courses',
                    'action' => 'update',
                    'description' => "تعديل بيانات المستوى التعليمي في الدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.levels.destroy' => [
                    'module' => 'courses',
                    'action' => 'delete',
                    'description' => "حذف مستوى تعليمي من الدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.levels.toggle-publish' => [
                    'module' => 'courses',
                    'action' => 'toggle',
                    'description' => "تبديل حالة نشر المستوى في الدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.create' => [
                    'module' => 'lessons',
                    'action' => 'view',
                    'description' => "فتح نموذج إضافة محاضرة/درس جديد في: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.store' => [
                    'module' => 'lessons',
                    'action' => 'create',
                    'description' => 'إضافة ونشر درس ومحاضرة فيديو جديدة: '.($request->input('title_ar') ?: $request->input('title', ''))." في دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.edit' => [
                    'module' => 'lessons',
                    'action' => 'view',
                    'description' => "فتح صفحة تعديل الدرس في دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.update' => [
                    'module' => 'lessons',
                    'action' => 'update',
                    'description' => "تحديث بيانات درس ومحاضرة في دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.destroy' => [
                    'module' => 'lessons',
                    'action' => 'delete',
                    'description' => "حذف درس ومحاضرة فيديو من دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.toggle-publish' => [
                    'module' => 'lessons',
                    'action' => 'toggle',
                    'description' => "تبديل حالة نشر الفيديو والمحاضرة في دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.videos.mux.sync' => [
                    'module' => 'lessons',
                    'action' => 'sync',
                    'description' => "مزامنة حالة معالجة الفيديو مع منصة Mux Streaming لدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.assign-users' => [
                    'module' => 'courses',
                    'action' => 'view',
                    'description' => "استعراض شاشة تعيين الطلاب المسجلين بالدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.assign-users.update' => [
                    'module' => 'courses',
                    'action' => 'update',
                    'description' => "تحديث وتزامن الطلاب المسجلين بالدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.assign-users.toggle' => [
                    'module' => 'courses',
                    'action' => 'toggle',
                    'description' => "تبديل تسجيل طالب في دورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                'admin.courses.promo.chunk.upload', 'admin.courses.videos.chunk.upload' => [
                    'module' => 'courses',
                    'action' => 'upload',
                    'description' => "رفع أجزاء فيديو (Chunk Upload) لدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
                default => [
                    'module' => 'courses',
                    'action' => strtolower($method === 'GET' ? 'view' : ($method === 'DELETE' ? 'delete' : 'update')),
                    'description' => "إجراء في إدارة الدورات ({$routeName}) لدورة: {$courseTitle}",
                    'subject_type' => Course::class,
                    'subject_id' => $courseModel?->id,
                    'subject_name' => $courseTitle,
                ],
            };
        }

        // 3. Students Management
        if (str_starts_with($routeName, 'admin.students.')) {
            $user = $request->route('user');
            $userModel = $user instanceof User ? $user : (is_numeric($user) ? User::find($user) : null);
            $userName = $userModel?->name ?: ($user ? "طالب #{$user}" : '');

            return match ($routeName) {
                'admin.students.index' => [
                    'module' => 'students',
                    'action' => 'view',
                    'description' => 'استعراض قائمة الطلاب والمتدربين المسجلين بالمنصة',
                ],
                'admin.students.show' => [
                    'module' => 'students',
                    'action' => 'view',
                    'description' => "استعراض الملف الأكاديمي الشامل للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.update' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "تعديل البيانات الشخصية والأكاديمية للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.password.update' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "تغيير وتعيين كلمة مرور جديدة لحساب الطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.approve' => [
                    'module' => 'students',
                    'action' => 'approve',
                    'description' => "اعتماد وتفعيل حساب الطالب رسمياً: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.toggle' => [
                    'module' => 'students',
                    'action' => 'toggle',
                    'description' => "تبديل حالة نشاط حساب الطالب (نشط / معلق): {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.verify' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "تأكيد وتوثيق البريد الإلكتروني يدوياً للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.unverify' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "إلغاء توثيق البريد الإلكتروني للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.resend-verification' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "إعادة إرسال رابط تأكيد الحساب إلى بريد الطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.enroll' => [
                    'module' => 'students',
                    'action' => 'enroll',
                    'description' => "تسجيل ومنح وصول الطالب: {$userName} إلى دورة تدريبية",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.enrollments.update' => [
                    'module' => 'students',
                    'action' => 'update',
                    'description' => "تحديث صلاحية وتاريخ انتهاء تسجيل الطالب: {$userName} في دورة",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.enrollments.destroy' => [
                    'module' => 'students',
                    'action' => 'revoke',
                    'description' => "إلغاء وسحب تسجيل الطالب: {$userName} من الدورة التدريبية",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.certificates.store' => [
                    'module' => 'certificates',
                    'action' => 'create',
                    'description' => "إصدار واعتماد شهادة إتمام رسمية للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.certificates.destroy' => [
                    'module' => 'certificates',
                    'action' => 'delete',
                    'description' => "إلغاء وحذف شهادة معتمدة للطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                'admin.students.destroy' => [
                    'module' => 'students',
                    'action' => 'delete',
                    'description' => "حذف حساب الطالب نهائياً من النظام: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
                default => [
                    'module' => 'students',
                    'action' => strtolower($method === 'GET' ? 'view' : ($method === 'DELETE' ? 'delete' : 'update')),
                    'description' => "إجراء على حساب الطالب: {$userName}",
                    'subject_type' => User::class,
                    'subject_id' => $userModel?->id,
                    'subject_name' => $userName,
                ],
            };
        }

        // 4. Payment Receipts & Approvals
        if (str_starts_with($routeName, 'admin.receipts.')) {
            $receipt = $request->route('receipt');
            $receiptModel = $receipt instanceof PaymentReceipt ? $receipt : (is_numeric($receipt) ? PaymentReceipt::find($receipt) : null);
            $receiptNum = (string) ($receiptModel !== null ? $receiptModel->id : (is_scalar($receipt) ? $receipt : ''));
            $receiptModelId = $receiptModel !== null ? $receiptModel->id : (is_numeric($receipt) ? (int) $receipt : null);

            return match ($routeName) {
                'admin.receipts.index' => [
                    'module' => 'receipts',
                    'action' => 'view',
                    'description' => 'استعراض إيصالات الدفع والتحويلات البنكية المرفوعة من الطلاب',
                ],
                'admin.receipts.approve' => [
                    'module' => 'receipts',
                    'action' => 'approve',
                    'description' => "قبول واعتماد إيصال التحويل البنكي #{$receiptNum} وتفعيل الدورة للطالب",
                    'subject_type' => PaymentReceipt::class,
                    'subject_id' => $receiptModelId,
                    'subject_name' => "إيصال #{$receiptNum}",
                ],
                'admin.receipts.reject' => [
                    'module' => 'receipts',
                    'action' => 'reject',
                    'description' => "رفض إيصال التحويل البنكي #{$receiptNum} مع إرسال سبب الرفض للطالب: ".((string) $request->input('rejection_reason', '')),
                    'subject_type' => PaymentReceipt::class,
                    'subject_id' => $receiptModelId,
                    'subject_name' => "إيصال #{$receiptNum}",
                ],
                default => [
                    'module' => 'receipts',
                    'action' => 'view',
                    'description' => 'إجراء في قسم إيصالات الدفع',
                ],
            };
        }

        // 5. Clinical Tasks & Grading
        if (str_starts_with($routeName, 'admin.tasks.')) {
            $submission = $request->route('submission');
            $submissionModel = $submission instanceof TaskSubmission ? $submission : (is_numeric($submission) ? TaskSubmission::find($submission) : null);
            $submissionId = (string) ($submissionModel !== null ? $submissionModel->id : (is_scalar($submission) ? $submission : ''));
            $submissionModelId = $submissionModel !== null ? $submissionModel->id : (is_numeric($submission) ? (int) $submission : null);
            $submissionUser = $submissionModel?->user;
            $studentName = ($submissionUser instanceof User) ? $submissionUser->name : '';

            return match ($routeName) {
                'admin.tasks.index' => [
                    'module' => 'tasks',
                    'action' => 'view',
                    'description' => 'استعراض مهام وتكليفات الطلاب السريرية والتسجيلات الصوتية',
                ],
                'admin.tasks.grade' => [
                    'module' => 'tasks',
                    'action' => 'grade',
                    'description' => "تقييم ورصد درجات المهمة السريرية #{$submissionId} للطالب: {$studentName} بنتيجة (".((string) $request->input('score', '')).'%)',
                    'subject_type' => TaskSubmission::class,
                    'subject_id' => $submissionModelId,
                    'subject_name' => "تسليم مهمة #{$submissionId}",
                ],
                default => [
                    'module' => 'tasks',
                    'action' => 'view',
                    'description' => 'إجراء في قسم المهام السريرية',
                ],
            };
        }

        // 6. Support Tickets
        if (str_starts_with($routeName, 'admin.support.')) {
            $ticket = $request->route('ticket');
            $ticketModel = $ticket instanceof SupportTicket ? $ticket : (is_numeric($ticket) ? SupportTicket::find($ticket) : null);
            $ticketId = (string) ($ticketModel !== null ? $ticketModel->id : (is_scalar($ticket) ? $ticket : ''));
            $ticketModelId = $ticketModel !== null ? $ticketModel->id : (is_numeric($ticket) ? (int) $ticket : null);
            $ticketSubject = $ticketModel !== null && $ticketModel->subject ? " ({$ticketModel->subject})" : '';

            return match ($routeName) {
                'admin.support.index' => [
                    'module' => 'support',
                    'action' => 'view',
                    'description' => 'استعراض تذاكر الدعم الفني والرسائل المباشرة من الطلاب',
                ],
                'admin.support.show' => [
                    'module' => 'support',
                    'action' => 'view',
                    'description' => "فتح وقراءة محادثة تذكرة الدعم #{$ticketId}{$ticketSubject}",
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.reply' => [
                    'module' => 'support',
                    'action' => 'reply',
                    'description' => "إرسال رد رسمي على تذكرة الدعم #{$ticketId}{$ticketSubject}",
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.status.update' => [
                    'module' => 'support',
                    'action' => 'update',
                    'description' => "تحديث حالة تذكرة الدعم #{$ticketId} إلى: ".((string) $request->input('status', '')),
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.priority.update' => [
                    'module' => 'support',
                    'action' => 'update',
                    'description' => "تعديل أولوية تذكرة الدعم #{$ticketId} إلى: ".((string) $request->input('priority', '')),
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.assign' => [
                    'module' => 'support',
                    'action' => 'update',
                    'description' => "إسناد تذكرة الدعم #{$ticketId} إلى مشرف للمتابعة",
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.close' => [
                    'module' => 'support',
                    'action' => 'update',
                    'description' => "إغلاق وإنهاء تذكرة الدعم الفني #{$ticketId}",
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                'admin.support.reopen' => [
                    'module' => 'support',
                    'action' => 'update',
                    'description' => "إعادة فتح تذكرة الدعم الفني #{$ticketId}",
                    'subject_type' => SupportTicket::class,
                    'subject_id' => $ticketModelId,
                    'subject_name' => "تذكرة #{$ticketId}",
                ],
                default => [
                    'module' => 'support',
                    'action' => 'view',
                    'description' => 'إجراء في تذاكر الدعم الفني',
                ],
            };
        }

        // 7. Administrators Team Management
        if (str_starts_with($routeName, 'admin.admins.')) {
            $targetAdmin = $request->route('admin');
            $targetAdminModel = $targetAdmin instanceof Admin ? $targetAdmin : (is_numeric($targetAdmin) ? Admin::find($targetAdmin) : null);
            $targetName = $targetAdminModel?->name ?: ($targetAdmin ? "مشرف #{$targetAdmin}" : '');

            return match ($routeName) {
                'admin.admins.index' => [
                    'module' => 'admins',
                    'action' => 'view',
                    'description' => 'استعراض قائمة المشرفين وفريق عمل لوحة التحكم',
                ],
                'admin.admins.create' => [
                    'module' => 'admins',
                    'action' => 'view',
                    'description' => 'فتح نموذج إضافة مشرف جديد لفريق العمل',
                ],
                'admin.admins.store' => [
                    'module' => 'admins',
                    'action' => 'create',
                    'description' => 'إضافة مشرف جديد لفريق العمل: '.($request->input('name', ''))." ({$request->input('email', '')})",
                    'subject_type' => Admin::class,
                    'subject_name' => $request->input('name'),
                ],
                'admin.admins.edit' => [
                    'module' => 'admins',
                    'action' => 'view',
                    'description' => "فتح صفحة تعديل بيانات المشرف: {$targetName}",
                    'subject_type' => Admin::class,
                    'subject_id' => $targetAdminModel?->id,
                    'subject_name' => $targetName,
                ],
                'admin.admins.update' => [
                    'module' => 'admins',
                    'action' => 'update',
                    'description' => "تحديث صلاحيات وبيانات المشرف: {$targetName}",
                    'subject_type' => Admin::class,
                    'subject_id' => $targetAdminModel?->id,
                    'subject_name' => $targetName,
                ],
                'admin.admins.destroy' => [
                    'module' => 'admins',
                    'action' => 'delete',
                    'description' => "حذف حساب المشرف نهائياً: {$targetName}",
                    'subject_type' => Admin::class,
                    'subject_id' => $targetAdminModel?->id,
                    'subject_name' => $targetName,
                ],
                default => [
                    'module' => 'admins',
                    'action' => 'view',
                    'description' => "إجراء في إدارة المشرفين ({$targetName})",
                ],
            };
        }

        // 8. Dynamic Roles & Permissions
        if (str_starts_with($routeName, 'admin.roles.')) {
            $role = $request->route('role');
            $roleModel = $role instanceof Role ? $role : (is_numeric($role) ? Role::find($role) : null);
            $roleName = $roleModel?->name ?: ($role ? "دور #{$role}" : '');

            return match ($routeName) {
                'admin.roles.index' => [
                    'module' => 'roles',
                    'action' => 'view',
                    'description' => 'استعراض الأدوار الديناميكية وصلاحيات النظام',
                ],
                'admin.roles.create' => [
                    'module' => 'roles',
                    'action' => 'view',
                    'description' => 'فتح نموذج إنشاء دور وظيفي جديد',
                ],
                'admin.roles.store' => [
                    'module' => 'roles',
                    'action' => 'create',
                    'description' => 'إنشاء دور وظيفي جديد: '.($request->input('name', '')),
                    'subject_type' => Role::class,
                    'subject_name' => $request->input('name'),
                ],
                'admin.roles.edit' => [
                    'module' => 'roles',
                    'action' => 'view',
                    'description' => "فتح صفحة تعديل صلاحيات الدور: {$roleName}",
                    'subject_type' => Role::class,
                    'subject_id' => $roleModel?->id,
                    'subject_name' => $roleName,
                ],
                'admin.roles.update' => [
                    'module' => 'roles',
                    'action' => 'update',
                    'description' => "تحديث صلاحيات ومسمى الدور الوظيفي: {$roleName}",
                    'subject_type' => Role::class,
                    'subject_id' => $roleModel?->id,
                    'subject_name' => $roleName,
                ],
                'admin.roles.destroy' => [
                    'module' => 'roles',
                    'action' => 'delete',
                    'description' => "حذف الدور الوظيفي: {$roleName}",
                    'subject_type' => Role::class,
                    'subject_id' => $roleModel?->id,
                    'subject_name' => $roleName,
                ],
                default => [
                    'module' => 'roles',
                    'action' => 'view',
                    'description' => 'إجراء في إدارة الأدوار والصلاحيات',
                ],
            };
        }

        // 9. Platform Settings, CMS & Menus
        if (str_starts_with($routeName, 'admin.settings.')) {
            return match ($routeName) {
                'admin.settings.index' => [
                    'module' => 'settings',
                    'action' => 'view',
                    'description' => 'استعراض إعدادات المنصة العامة والدفع وتكامل الوسائط',
                ],
                'admin.settings.update' => [
                    'module' => 'settings',
                    'action' => 'update',
                    'description' => 'تحديث إعدادات المنصة وبيانات التواصل وطرق الدفع',
                ],
                'admin.settings.mux.test' => [
                    'module' => 'settings',
                    'action' => 'sync',
                    'description' => 'اختبار الاتصال المباشر بمفتاح API لمنصة Mux Video',
                ],
                'admin.settings.content.update' => [
                    'module' => 'cms',
                    'action' => 'update',
                    'description' => 'تحديث محتوى كتلة الموقع الديناميكية: '.($request->route('sectionKey') ?? 'CMS Block'),
                ],
                'admin.settings.menus.item' => [
                    'module' => 'menus',
                    'action' => 'create',
                    'description' => 'إضافة أو تعديل عنصر في قوائم التنقل (Menu Manager)',
                ],
                'admin.settings.menus.item.toggle' => [
                    'module' => 'menus',
                    'action' => 'toggle',
                    'description' => 'تبديل حالة ظهور عنصر في القائمة العلوية/السفلية',
                ],
                'admin.settings.menus.item.delete' => [
                    'module' => 'menus',
                    'action' => 'delete',
                    'description' => 'حذف عنصر من قوائم الموقع',
                ],
                default => [
                    'module' => 'settings',
                    'action' => 'update',
                    'description' => 'إجراء في إعدادات المنصة أو المحتوى',
                ],
            };
        }

        // 10. Admin Notifications Broadcasting
        if (str_starts_with($routeName, 'admin.notifications.')) {
            return [
                'module' => 'notifications',
                'action' => $method === 'GET' ? 'view' : 'create',
                'description' => $method === 'GET' ? 'استعراض لوحة إدارة وبث الإشعارات للطلاب' : 'بث أو إدارة إشعارات الطلاب',
            ];
        }

        // 11. Profile & Passkeys
        if (str_starts_with($routeName, 'admin.profile.') || str_starts_with($routeName, 'admin.passkey.')) {
            return match ($routeName) {
                'admin.profile.edit' => [
                    'module' => 'profile',
                    'action' => 'view',
                    'description' => 'فتح شاشة تعديل الملف الشخصي للمشرف',
                ],
                'admin.profile.update' => [
                    'module' => 'profile',
                    'action' => 'update',
                    'description' => 'تحديث بيانات الملف الشخصي للمشرف',
                ],
                'admin.profile.password' => [
                    'module' => 'profile',
                    'action' => 'update',
                    'description' => 'تغيير كلمة المرور الخاصة بحساب المشرف',
                ],
                'admin.passkey.index' => [
                    'module' => 'passkeys',
                    'action' => 'view',
                    'description' => 'استعراض مفاتيح المرور الحيوية المسجلة (Passkeys)',
                ],
                'admin.passkey.store' => [
                    'module' => 'passkeys',
                    'action' => 'create',
                    'description' => 'تسجيل مفتاح مرور حيوي جديد (Passkey / WebAuthn)',
                ],
                'admin.passkey.destroy' => [
                    'module' => 'passkeys',
                    'action' => 'delete',
                    'description' => 'حذف مفتاح مرور أمان مسجل (Passkey)',
                ],
                default => [
                    'module' => 'profile',
                    'action' => 'view',
                    'description' => 'إجراء في الملف الشخصي أو مفاتيح المرور',
                ],
            };
        }

        // 12. Audit Logs viewer
        if (str_starts_with($routeName, 'admin.audit-logs.')) {
            return match ($routeName) {
                'admin.audit-logs.index' => [
                    'module' => 'audit_logs',
                    'action' => 'view',
                    'description' => 'استعراض وتصفح سجل نشاط الإدارة المركزي (Audit Logs)',
                ],
                'admin.audit-logs.show' => [
                    'module' => 'audit_logs',
                    'action' => 'view',
                    'description' => 'فحص التفاصيل الفنية لعملية مسجلة في سجل النشاط',
                ],
                'admin.audit-logs.export' => [
                    'module' => 'audit_logs',
                    'action' => 'export',
                    'description' => 'تصدير سجل العمليات والنشاط كملف بيانات (CSV / JSON)',
                ],
                'admin.audit-logs.clear' => [
                    'module' => 'audit_logs',
                    'action' => 'clean',
                    'description' => 'أرشفة وتنظيف السجلات القديمة بواسطة الإدارة العليا',
                ],
                default => [
                    'module' => 'audit_logs',
                    'action' => 'view',
                    'description' => 'إجراء في سجلات النشاط',
                ],
            };
        }

        // 13. Generic Fallback for Any Admin Route or Page
        $module = Str::contains($path, '/') ? explode('/', $path)[1] ?? 'admin' : 'admin';
        $action = match ($method) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => strtolower($method),
        };

        return [
            'module' => $module,
            'action' => $action,
            'description' => "تنفيذ إجراء ({$method}) على مسار: /{$path}",
        ];
    }

    /**
     * Recursively sanitize input array to prevent logging passwords and secrets.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function sanitizePayload(array $payload): array
    {
        $sanitized = [];

        foreach ($payload as $key => $value) {
            $lowerKey = strtolower((string) $key);

            // Redact known sensitive keys
            if (in_array($lowerKey, static::$sensitiveKeys, true) || str_contains($lowerKey, 'password') || str_contains($lowerKey, 'token') || str_contains($lowerKey, 'secret')) {
                $sanitized[$key] = '••••••••';

                continue;
            }

            // Convert UploadedFile instances to metadata arrays
            if ($value instanceof UploadedFile) {
                $sanitized[$key] = [
                    'file_name' => $value->getClientOriginalName(),
                    'file_size' => $value->getSize(),
                    'mime_type' => $value->getClientMimeType(),
                ];

                continue;
            }

            // Handle nested arrays recursively
            if (is_array($value)) {
                $sanitized[$key] = static::sanitizePayload($value);

                continue;
            }

            // Truncate excessively long strings (e.g., base64 or chunks) to keep DB rows compact
            if (is_string($value) && strlen($value) > 2000) {
                $sanitized[$key] = substr($value, 0, 500).'... [truncated: '.strlen($value).' bytes]';

                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    /**
     * Extract route parameters with readable names and IDs.
     *
     * @return array<string, mixed>
     */
    protected static function extractRouteParameters(Request $request): array
    {
        $route = $request->route();

        if (! $route) {
            return [];
        }

        $params = $route->parameters();
        $extracted = [];

        foreach ($params as $name => $param) {
            if ($param instanceof Model) {
                $extracted[$name] = [
                    'id' => $param->getKey(),
                    'class' => class_basename($param),
                ];
            } elseif (is_scalar($param)) {
                $extracted[$name] = $param;
            }
        }

        return $extracted;
    }
}
