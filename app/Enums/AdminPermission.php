<?php

declare(strict_types=1);

namespace App\Enums;

enum AdminPermission: string
{
    // Super Administrator wildcard
    case ALL = '*';

    // Courses & Lectures
    case COURSES_VIEW = 'courses.view';
    case COURSES_MANAGE = 'courses.manage';
    case COURSES_PUBLISH = 'courses.publish';

    // Tasks & Clinical Grading
    case TASKS_VIEW = 'tasks.view';
    case TASKS_GRADE = 'tasks.grade';

    // Certificates
    case CERTIFICATES_VIEW = 'certificates.view';
    case CERTIFICATES_APPROVE = 'certificates.approve';

    // Finance & Payment Receipts
    case RECEIPTS_VIEW = 'receipts.view';
    case RECEIPTS_VERIFY = 'receipts.verify';
    case RECEIPTS_APPROVE = 'receipts.approve';
    case RECEIPTS_REJECT = 'receipts.reject';

    // Notifications
    case NOTIFICATIONS_VIEW = 'notifications.view';
    case NOTIFICATIONS_SEND = 'notifications.send';
    case NOTIFICATIONS_MANAGE = 'notifications.manage';

    // Students
    case STUDENTS_VIEW = 'students.view';
    case STUDENTS_MANAGE = 'students.manage';

    // Admins & Roles
    case ADMINS_VIEW = 'admins.view';
    case ADMINS_MANAGE = 'admins.manage';
    case ROLES_MANAGE = 'roles.manage';

    // Settings & Reports
    case SETTINGS_MANAGE = 'settings.manage';
    case REPORTS_VIEW = 'reports.view';
    case REPORTS_EXPORT = 'reports.export';

    /**
     * Get human-readable description in Arabic.
     */
    public function labelAr(): string
    {
        return match ($this) {
            self::ALL => 'كافة الصلاحيات الكاملة للمنصة (المالك)',
            self::COURSES_VIEW => 'عرض قائمة الدورات والمحاضرات',
            self::COURSES_MANAGE => 'إضافة وتعديل الدورات والمستويات',
            self::COURSES_PUBLISH => 'نشر وتفعيل الدورات الجديدة',
            self::TASKS_VIEW => 'استعراض مهام وتكليفات الطلاب',
            self::TASKS_GRADE => 'تقييم ورصد درجات التسجيلات السريرية',
            self::CERTIFICATES_VIEW => 'عرض الشهادات الصادرة',
            self::CERTIFICATES_APPROVE => 'اعتماد وتوقيع الشهادات للطلاب',
            self::RECEIPTS_VIEW => 'استعراض إيصالات التحويل البنكي',
            self::RECEIPTS_VERIFY => 'تدقيق بيانات الإيصال البنكي',
            self::RECEIPTS_APPROVE => 'قبول الإيصال وتفعيل وصول الطالب',
            self::RECEIPTS_REJECT => 'رفض الإيصال مع إرسال سبب الرفض',
            self::NOTIFICATIONS_VIEW => 'استعراض سجل الإشعارات الصادرة',
            self::NOTIFICATIONS_SEND => 'إرسال إشعار جماعي أو فردي',
            self::NOTIFICATIONS_MANAGE => 'إدارة قوالب وحذف الإشعارات',
            self::STUDENTS_VIEW => 'استعراض قائمة الطلاب المسجلين',
            self::STUDENTS_MANAGE => 'تعديل وتفعيل حسابات الطلاب',
            self::ADMINS_VIEW => 'استعراض فريق العمل والمشرفين',
            self::ADMINS_MANAGE => 'إضافة وتعديل حسابات المشرفين',
            self::ROLES_MANAGE => 'إدارة الأدوار الديناميكية وتعيين صلاحياتها',
            self::SETTINGS_MANAGE => 'التحكم في إعدادات المنصة العامة',
            self::REPORTS_VIEW => 'استعراض التقارير المالية والأكاديمية',
            self::REPORTS_EXPORT => 'تصدير التقارير بصيغة Excel / PDF',
        };
    }

    /**
     * Get human-readable description in English.
     */
    public function labelEn(): string
    {
        return match ($this) {
            self::ALL => 'Full Super Administrator Authority (Owner)',
            self::COURSES_VIEW => 'View courses & lecture catalogue',
            self::COURSES_MANAGE => 'Manage course levels & video content',
            self::COURSES_PUBLISH => 'Publish and toggle course availability',
            self::TASKS_VIEW => 'Browse student clinical assignments',
            self::TASKS_GRADE => 'Evaluate and grade voice recordings',
            self::CERTIFICATES_VIEW => 'View issued completion certificates',
            self::CERTIFICATES_APPROVE => 'Approve and endorse student certificates',
            self::RECEIPTS_VIEW => 'Inspect wire transfer receipts',
            self::RECEIPTS_VERIFY => 'Audit bank transaction records',
            self::RECEIPTS_APPROVE => 'Approve payment & activate student access',
            self::RECEIPTS_REJECT => 'Reject payment receipt with note',
            self::NOTIFICATIONS_VIEW => 'View notification history logs',
            self::NOTIFICATIONS_SEND => 'Broadcast notifications to students',
            self::NOTIFICATIONS_MANAGE => 'Manage templates and delete alerts',
            self::STUDENTS_VIEW => 'View enrolled students roster',
            self::STUDENTS_MANAGE => 'Manage student account statuses',
            self::ADMINS_VIEW => 'View supervisor and staff roster',
            self::ADMINS_MANAGE => 'Manage supervisor accounts',
            self::ROLES_MANAGE => 'Manage dynamic roles and permissions',
            self::SETTINGS_MANAGE => 'Configure platform wide parameters',
            self::REPORTS_VIEW => 'View financial and academic analytics',
            self::REPORTS_EXPORT => 'Export analytical data to Excel/PDF',
        };
    }

    /**
     * Get category group for permission assignment UI.
     */
    public function groupAr(): string
    {
        return match ($this) {
            self::ALL => 'إدارة النظام الشاملة',
            self::COURSES_VIEW, self::COURSES_MANAGE, self::COURSES_PUBLISH => 'إدارة الدورات والمحاضرات',
            self::TASKS_VIEW, self::TASKS_GRADE => 'المهام السريرية والتقييم',
            self::CERTIFICATES_VIEW, self::CERTIFICATES_APPROVE => 'الشهادات والاعتمادات',
            self::RECEIPTS_VIEW, self::RECEIPTS_VERIFY, self::RECEIPTS_APPROVE, self::RECEIPTS_REJECT => 'المالية وإيصالات الدفع',
            self::NOTIFICATIONS_VIEW, self::NOTIFICATIONS_SEND, self::NOTIFICATIONS_MANAGE => 'الإشعارات والتعاميم',
            self::STUDENTS_VIEW, self::STUDENTS_MANAGE => 'شؤون الطلاب والمتدربين',
            self::ADMINS_VIEW, self::ADMINS_MANAGE, self::ROLES_MANAGE => 'إدارة المشرفين والأدوار',
            self::SETTINGS_MANAGE, self::REPORTS_VIEW, self::REPORTS_EXPORT => 'الإعدادات والتقارير',
        };
    }

    public function label(string $locale = 'ar'): string
    {
        return $locale === 'en' ? $this->labelEn() : $this->labelAr();
    }

    /**
     * List all static permission string values.
     *
     * @return list<string>
     */
    public static function allValues(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get static permissions grouped by functional module.
     *
     * @return array<string, list<array{value: string, label: string}>>
     */
    public static function grouped(string $locale = 'ar'): array
    {
        $grouped = [];

        foreach (self::cases() as $perm) {
            if ($perm === self::ALL) {
                continue;
            }

            $groupName = $perm->groupAr();
            $grouped[$groupName][] = [
                'value' => $perm->value,
                'label' => $perm->label($locale),
            ];
        }

        return $grouped;
    }
}
