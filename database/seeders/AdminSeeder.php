<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds for dynamic roles and admins.
     */
    public function run(): void
    {
        // ---------------------------------------------------------
        // 1. Seed Dynamic Roles in `roles` table
        // ---------------------------------------------------------
        $superAdminRole = Role::query()->updateOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'المالك / مدير النظام العام',
                'name_ar' => 'المالك / مدير النظام العام',
                'name_en' => 'Owner / Super Administrator',
                'description' => 'كامل صلاحيات التحكم في المنصة، الإعدادات، المشرفين، والتقارير',
                'description_ar' => 'كامل صلاحيات التحكم في المنصة، الإعدادات، المشرفين، والتقارير',
                'description_en' => 'Full administrative access to platform control, system settings, supervisors, and analytics',
                'permissions' => ['*'],
                'is_system' => true,
            ]
        );

        $supervisorRole = Role::query()->updateOrCreate(
            ['slug' => 'supervisor'],
            [
                'name' => 'المشرف التدريبي والأكاديمي',
                'name_ar' => 'المشرف التدريبي والأكاديمي',
                'name_en' => 'Training & Academic Supervisor',
                'description' => 'إدارة وتعديل الكورسات والمحاضرات، تقييم تسجيلات ومهام الطلاب، واعتماد الشهادات',
                'description_ar' => 'إدارة وتعديل الكورسات والمحاضرات، تقييم تسجيلات ومهام الطلاب، واعتماد الشهادات',
                'description_en' => 'Curriculum management, student practical recording evaluations, and certificate accreditation',
                'permissions' => AdminRole::CLINICAL_SUPERVISOR->defaultPermissions(),
                'is_system' => false,
            ]
        );

        $instructorRole = Role::query()->updateOrCreate(
            ['slug' => 'instructor'],
            [
                'name' => 'محاضر ومدرب معتمد',
                'name_ar' => 'محاضر ومدرب معتمد',
                'name_en' => 'Certified Instructor & Trainer',
                'description' => 'استعراض المناهج وتقييم مهام الطلاب التدريبية والتطبيقية',
                'description_ar' => 'استعراض المناهج وتقييم مهام الطلاب التدريبية والتطبيقية',
                'description_en' => 'Curriculum reviewing and practical voice task grading for enrolled trainees',
                'permissions' => AdminRole::INSTRUCTOR->defaultPermissions(),
                'is_system' => false,
            ]
        );

        $financeRole = Role::query()->updateOrCreate(
            ['slug' => 'finance_admin'],
            [
                'name' => 'المسؤول المالي ومراجعة الإيصالات',
                'name_ar' => 'المسؤول المالي ومراجعة الإيصالات',
                'name_en' => 'Finance & Payment Receipts Auditor',
                'description' => 'مراجعة وتدقيق إيصالات التحويل البنكي، تفعيل وصول الطلاب، والتقارير المالية',
                'description_ar' => 'مراجعة وتدقيق إيصالات التحويل البنكي، تفعيل وصول الطلاب، والتقارير المالية',
                'description_en' => 'Auditing transfer receipts, activating course enrollments, and exporting finance reports',
                'permissions' => AdminRole::FINANCE_ADMIN->defaultPermissions(),
                'is_system' => false,
            ]
        );

        $supportRole = Role::query()->updateOrCreate(
            ['slug' => 'support_admin'],
            [
                'name' => 'مسؤول الدعم الفني والإشعارات',
                'name_ar' => 'مسؤول الدعم الفني والإشعارات',
                'name_en' => 'Technical Support & Communications Officer',
                'description' => 'إرسال الإشعارات والتعاميم الجماعية، ومتابعة شؤون واستفسارات المتدربين',
                'description_ar' => 'إرسال الإشعارات والتعاميم الجماعية، ومتابعة شؤون واستفسارات المتدربين',
                'description_en' => 'Broadcasting announcements, managing push notifications, and supporting trainee inquiries',
                'permissions' => AdminRole::SUPPORT_ADMIN->defaultPermissions(),
                'is_system' => false,
            ]
        );

        // ---------------------------------------------------------
        // 2. Seed Administrators linked to their dynamic roles
        // ---------------------------------------------------------

        // 1. Dr. Hana El-Mekawy - Platform Owner & General Manager
        Admin::query()->updateOrCreate(
            ['email' => 'hm2991479@gmail.com'],
            [
                'name' => 'د. هنا المكاوي',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'permissions' => ['*'],
                'locale' => 'ar',
            ]
        );

        // 2. Abdo Goda - System Administrator with All Permissions
        Admin::query()->updateOrCreate(
            ['email' => 'abdogoda0a@gmail.com'],
            [
                'name' => 'Abdo Goda',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'permissions' => ['*'],
                'locale' => 'ar',
            ]
        );
    }
}
