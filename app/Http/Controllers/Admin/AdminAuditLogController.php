<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PruneAuditLogsRequest;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Services\Audit\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAuditLogController extends Controller
{
    /**
     * Display a listing of recorded administrative activities.
     */
    public function index(Request $request): View
    {
        $query = AdminActivityLog::with('admin')
            ->latest('id');

        // Apply filters
        $search = $request->input('search');
        $adminId = $request->input('admin_id');
        $module = $request->input('module');
        $action = $request->input('action');
        $status = $request->input('status');
        $method = $request->input('method');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $perPage = (int) $request->input('per_page', 25);

        if (! in_array($perPage, [15, 25, 50, 100], true)) {
            $perPage = 25;
        }

        $query->search($search)
            ->admin($adminId)
            ->module($module)
            ->action($action)
            ->status($status)
            ->method($method)
            ->dateRange($dateFrom, $dateTo);

        $logs = $query->paginate($perPage)->withQueryString();

        // Calculate KPI metrics
        $todayStart = Carbon::today()->startOfDay();
        $weekAgo = Carbon::now()->subDays(7)->startOfDay();

        $stats = [
            'total' => AdminActivityLog::count(),
            'today' => AdminActivityLog::where('created_at', '>=', $todayStart)->count(),
            'todayMutations' => AdminActivityLog::where('created_at', '>=', $todayStart)
                ->where('method', '!=', 'GET')
                ->count(),
            'activeAdmins' => AdminActivityLog::where('created_at', '>=', $weekAgo)
                ->whereNotNull('admin_id')
                ->distinct('admin_id')
                ->count('admin_id'),
            'failedAttempts' => AdminActivityLog::whereIn('status', ['failed', 'warning'])->count(),
        ];

        // Unique modules and actions for filter dropdowns
        $availableModules = [
            'dashboard' => 'لوحة الإحصائيات',
            'courses' => 'الدورات والمستويات',
            'lessons' => 'الدروس والمحاضرات',
            'students' => 'شؤون الطلاب',
            'receipts' => 'إيصالات الدفع والتحويل',
            'tasks' => 'المهام السريرية والتقييم',
            'certificates' => 'الشهادات والاعتمادات',
            'support' => 'تذاكر الدعم والرسائل',
            'notifications' => 'الإشعارات والتعاميم',
            'admins' => 'إدارة المشرفين',
            'roles' => 'الأدوار والصلاحيات',
            'settings' => 'إعدادات المنصة',
            'cms' => 'محتوى الصفحات (CMS)',
            'menus' => 'إدارة القوائم',
            'profile' => 'الملف الشخصي للمشرف',
            'passkeys' => 'مفاتيح المرور (Passkeys)',
            'auth' => 'المصادقة والأمان',
            'audit_logs' => 'سجلات العمليات (Audit Logs)',
        ];

        $availableActions = [
            'view' => 'استعراض / تصفح',
            'create' => 'إنشاء / إضافة',
            'update' => 'تعديل / تحديث',
            'delete' => 'حذف',
            'approve' => 'اعتماد / قبول',
            'reject' => 'رفض',
            'grade' => 'تقييم ورصد درجات',
            'login' => 'تسجيل دخول',
            'logout' => 'تسجيل خروج',
            'failed_login' => 'محاولة دخول فاشلة',
            'lockout' => 'حظر مؤقت لمحاولات مشبوهة',
            'upload' => 'رفع وسائط / ملفات',
            'toggle' => 'تبديل الحالة',
            'enroll' => 'تسجيل طالب بدورة',
            'revoke' => 'إلغاء تسجيل طالب',
            'reply' => 'إرسال رد',
            'export' => 'تصدير بيانات',
            'clean' => 'تنظيف السجلات',
        ];

        $admins = Admin::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.logs.index', [
            'logs' => $logs,
            'stats' => $stats,
            'availableModules' => $availableModules,
            'availableActions' => $availableActions,
            'admins' => $admins,
            'filters' => [
                'search' => $search,
                'admin_id' => $adminId,
                'module' => $module,
                'action' => $action,
                'status' => $status,
                'method' => $method,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Display detailed JSON properties for a specific log entry.
     */
    public function show(AdminActivityLog $log): JsonResponse
    {
        return response()->json([
            'id' => $log->id,
            'admin_name' => $log->admin_name ?? 'غير معروف',
            'admin_email' => $log->admin_email,
            'role_name' => $log->role_name,
            'module' => $log->module,
            'module_label' => $log->getModuleLabel(),
            'action' => $log->action,
            'action_label' => $log->getActionLabel(),
            'description' => $log->description,
            'subject_name' => $log->subject_name,
            'method' => $log->method,
            'url' => $log->url,
            'route_name' => $log->route_name,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'browser' => $log->getBrowserName(),
            'platform' => $log->getPlatformName(),
            'status' => $log->status,
            'properties' => $log->properties ?? [],
            'created_at' => $log->created_at?->format('Y-m-d H:i:s'),
            'time_ago' => $log->getTimeAgo(),
        ]);
    }

    /**
     * Export filtered logs as a UTF-8 CSV stream.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = AdminActivityLog::latest('id')
            ->search($request->input('search'))
            ->admin($request->input('admin_id'))
            ->module($request->input('module'))
            ->action($request->input('action'))
            ->status($request->input('status'))
            ->method($request->input('method'))
            ->dateRange($request->input('date_from'), $request->input('date_to'));

        $filename = 'admin_audit_logs_'.Carbon::now()->format('Y_m_d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            // UTF-8 BOM so Excel opens Arabic characters correctly
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($handle, [
                'ID',
                'التاريخ والوقت',
                'اسم المشرف',
                'البريد الإلكتروني',
                'الدور الوظيفي',
                'القسم / الوحدة',
                'نوع الإجراء',
                'بيان العملية',
                'العنصر المستهدف',
                'طريقة الطلب',
                'الرابط',
                'عنوان IP',
                'الحالة',
                'مدة التنفيذ',
            ]);

            $query->chunk(500, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at?->format('Y-m-d H:i:s') ?? '',
                        $log->admin_name ?? 'غير معروف',
                        $log->admin_email ?? '',
                        $log->role_name ?? '',
                        $log->getModuleLabel(),
                        $log->getActionLabel(),
                        $log->description,
                        $log->subject_name ?? '',
                        $log->method,
                        $log->url,
                        $log->ip_address ?? '',
                        $log->status === 'success' ? 'ناجح' : ($log->status === 'warning' ? 'تحذير' : 'فشل'),
                        $log->getFormattedDuration() ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Prune old logs (restricted to platform owners / super admins).
     */
    public function clear(PruneAuditLogsRequest $request): RedirectResponse
    {
        $admin = auth('admin')->user();

        if (! $admin instanceof Admin || ! $admin->isSuperAdmin()) {
            abort(403, __('غير مصرح لك بتنفيذ هذه العملية. تقتصر على مالك المنصة فقط.'));
        }

        $validated = $request->validated();

        $cutoffDate = Carbon::now()->subDays((int) $validated['retention_days'])->startOfDay();
        $deletedCount = AdminActivityLog::where('created_at', '<', $cutoffDate)->delete();

        // Record the cleanup action itself
        AdminActivityLogger::log([
            'admin' => $admin,
            'module' => 'audit_logs',
            'action' => 'clean',
            'description' => "قام المشرف ({$admin->name}) بتنظيف وأرشفة سجلات النشاط الأقدم من {$validated['retention_days']} يوماً (تم حذف {$deletedCount} سجلاً)",
            'method' => 'POST',
            'url' => route('admin.audit-logs.clear'),
            'route_name' => 'admin.audit-logs.clear',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'properties' => [
                'retention_days' => $validated['retention_days'],
                'deleted_records_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
            ],
        ]);

        return redirect()
            ->route('admin.audit-logs.index')
            ->with('success', __('تم تنظيف السجلات الأقدم من :days يوماً بنجاح (تم حذف :count سجلاً).', [
                'days' => $validated['retention_days'],
                'count' => $deletedCount,
            ]));
    }
}
