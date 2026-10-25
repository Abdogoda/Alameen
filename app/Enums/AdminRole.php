<?php

declare(strict_types=1);

namespace App\Enums;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case CLINICAL_SUPERVISOR = 'clinical_supervisor';
    case INSTRUCTOR = 'instructor';
    case FINANCE_ADMIN = 'finance_admin';
    case SUPPORT_ADMIN = 'support_admin';

    /**
     * Get human-readable label in Arabic.
     */
    public function labelAr(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'المالك / مدير النظام العام',
            self::CLINICAL_SUPERVISOR => 'المشرف السريري والأكاديمي',
            self::INSTRUCTOR => 'محاضر ومدرب سريري',
            self::FINANCE_ADMIN => 'المسؤول المالي ومراجعة الإيصالات',
            self::SUPPORT_ADMIN => 'مسؤول الدعم الفني والإشعارات',
        };
    }

    /**
     * Get human-readable label in English.
     */
    public function labelEn(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Owner / Super Administrator',
            self::CLINICAL_SUPERVISOR => 'Clinical & Academic Supervisor',
            self::INSTRUCTOR => 'Instructor & Clinical Trainer',
            self::FINANCE_ADMIN => 'Finance & Receipts Auditor',
            self::SUPPORT_ADMIN => 'Support & Notification Coordinator',
        };
    }

    /**
     * Get localized label based on active locale.
     */
    public function label(): string
    {
        return app()->getLocale() === 'en' ? $this->labelEn() : $this->labelAr();
    }

    /**
     * Default permissions granted to this role.
     *
     * @return list<string>
     */
    public function defaultPermissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => [
                '*',
            ],
            self::CLINICAL_SUPERVISOR => [
                'courses.view',
                'courses.manage',
                'courses.publish',
                'tasks.view',
                'tasks.grade',
                'certificates.view',
                'certificates.approve',
                'students.view',
                'notifications.send',
            ],
            self::INSTRUCTOR => [
                'courses.view',
                'tasks.view',
                'tasks.grade',
                'students.view',
            ],
            self::FINANCE_ADMIN => [
                'receipts.view',
                'receipts.verify',
                'receipts.approve',
                'receipts.reject',
                'reports.view',
                'reports.export',
            ],
            self::SUPPORT_ADMIN => [
                'notifications.view',
                'notifications.send',
                'notifications.manage',
                'students.view',
                'inquiries.manage',
            ],
        };
    }
}
