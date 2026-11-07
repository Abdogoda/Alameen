<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $admin_id
 * @property string|null $admin_name
 * @property string|null $admin_email
 * @property string|null $role_name
 * @property string $module
 * @property string $action
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $subject_name
 * @property string $method
 * @property string $url
 * @property string|null $route_name
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $status
 * @property array<string, mixed>|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Admin|null $admin
 * @property-read Model|null $subject
 */
class AdminActivityLog extends Model
{
    protected $table = 'admin_activity_logs';

    protected $fillable = [
        'admin_id',
        'admin_name',
        'admin_email',
        'role_name',
        'module',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'subject_name',
        'method',
        'url',
        'route_name',
        'ip_address',
        'user_agent',
        'status',
        'properties',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * The admin who performed this action.
     *
     * @return BelongsTo<Admin, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * The subject entity impacted by this action.
     *
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: search by keyword.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('admin_name', 'like', "%{$term}%")
                ->orWhere('admin_email', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhere('subject_name', 'like', "%{$term}%")
                ->orWhere('ip_address', 'like', "%{$term}%")
                ->orWhere('url', 'like', "%{$term}%")
                ->orWhere('route_name', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: filter by module.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeModule(Builder $query, ?string $module): Builder
    {
        if (blank($module) || $module === 'all') {
            return $query;
        }

        return $query->where('module', $module);
    }

    /**
     * Scope: filter by action.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeAction(Builder $query, ?string $action): Builder
    {
        if (blank($action) || $action === 'all') {
            return $query;
        }

        return $query->where('action', $action);
    }

    /**
     * Scope: filter by admin.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeAdmin(Builder $query, int|string|null $adminId): Builder
    {
        if (blank($adminId) || $adminId === 'all') {
            return $query;
        }

        return $query->where('admin_id', (int) $adminId);
    }

    /**
     * Scope: filter by status.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status) || $status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Scope: filter by HTTP method.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeMethod(Builder $query, ?string $method): Builder
    {
        if (blank($method) || $method === 'all') {
            return $query;
        }

        return $query->where('method', strtoupper($method));
    }

    /**
     * Scope: filter by date range.
     *
     * @param  Builder<AdminActivityLog>  $query
     * @return Builder<AdminActivityLog>
     */
    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if (! empty($from)) {
            $query->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay());
        }

        if (! empty($to)) {
            $query->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        return $query;
    }

    /**
     * Get Arabic label for the module.
     */
    public function getModuleLabel(): string
    {
        return match ($this->module) {
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
            default => ucfirst($this->module),
        };
    }

    /**
     * Get Arabic label for the action.
     */
    public function getActionLabel(): string
    {
        return match ($this->action) {
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
            'publish' => 'نشر',
            'unpublish' => 'إلغاء النشر',
            'enroll' => 'تسجيل طالب بدورة',
            'revoke' => 'إلغاء تسجيل طالب',
            'reply' => 'إرسال رد',
            'export' => 'تصدير بيانات',
            'clean', 'prune' => 'تنظيف السجلات',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Badge CSS class for the action.
     */
    public function getActionBadgeClass(): string
    {
        return match ($this->action) {
            'create', 'approve', 'publish', 'enroll' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
            'update', 'grade', 'reply', 'toggle' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800',
            'delete', 'reject', 'revoke', 'clean', 'prune' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
            'login', 'logout' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800',
            'failed_login', 'lockout' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
            'upload', 'export' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800',
            default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    /**
     * Badge CSS class for HTTP method.
     */
    public function getMethodBadgeClass(): string
    {
        return match (strtoupper($this->method)) {
            'GET' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
            'POST' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
            'PUT', 'PATCH' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300',
            'DELETE' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    /**
     * Badge CSS class for execution status.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
            'warning' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
            'failed' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
            default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    /**
     * Parse browser name from User Agent.
     */
    public function getBrowserName(): string
    {
        $agent = $this->user_agent ?? '';

        if (empty($agent)) {
            return 'غير معروف';
        }

        if (str_contains($agent, 'Edg')) {
            return 'Microsoft Edge';
        }

        if (str_contains($agent, 'Chrome')) {
            return 'Google Chrome';
        }

        if (str_contains($agent, 'Firefox')) {
            return 'Mozilla Firefox';
        }

        if (str_contains($agent, 'Safari')) {
            return 'Apple Safari';
        }

        if (str_contains($agent, 'Postman')) {
            return 'Postman API';
        }

        if (str_contains($agent, 'curl')) {
            return 'cURL Client';
        }

        return 'متصفح ويب';
    }

    /**
     * Parse OS platform from User Agent.
     */
    public function getPlatformName(): string
    {
        $agent = $this->user_agent ?? '';

        if (empty($agent)) {
            return '';
        }

        if (str_contains($agent, 'Windows NT 10.0') || str_contains($agent, 'Windows')) {
            return 'Windows';
        }

        if (str_contains($agent, 'Macintosh') || str_contains($agent, 'Mac OS X')) {
            return 'macOS';
        }

        if (str_contains($agent, 'iPhone') || str_contains($agent, 'iPad')) {
            return 'iOS';
        }

        if (str_contains($agent, 'Android')) {
            return 'Android';
        }

        if (str_contains($agent, 'Linux')) {
            return 'Linux';
        }

        return '';
    }

    /**
     * Format execution time string.
     */
    public function getFormattedDuration(): ?string
    {
        $ms = $this->properties['duration_ms'] ?? null;

        if ($ms === null) {
            return null;
        }

        if ($ms < 1000) {
            return round($ms).' ms';
        }

        return round($ms / 1000, 2).' s';
    }

    /**
     * Human-friendly time diff in Arabic.
     */
    public function getTimeAgo(): string
    {
        $createdAt = $this->created_at;

        if (! is_object($createdAt) || ! method_exists($createdAt, 'diffForHumans')) {
            return '';
        }

        return $createdAt->locale('ar')->diffForHumans(syntax: CarbonInterface::DIFF_RELATIVE_TO_NOW);
    }
}
