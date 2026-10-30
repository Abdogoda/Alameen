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
            ['slug' => 'clinical_supervisor'],
            [
                'name' => 'المشرف السريري والأكاديمي',
                'name_ar' => 'المشرف السريري والأكاديمي',
                'name_en' => 'Clinical & Academic Supervisor',
                'description' => 'إدارة وتعديل الكورسات والمحاضرات، تقييم تسجيلات الطلاب السريرية، واعتماد الشهادات',
                'description_ar' => 'إدارة وتعديل الكورسات والمحاضرات، تقييم تسجيلات الطلاب السريرية، واعتماد الشهادات',
                'description_en' => 'Curriculum management, student clinical recording evaluations, and certificate accreditation',
                'permissions' => AdminRole::CLINICAL_SUPERVISOR->defaultPermissions(),
                'is_system' => false,
            ]
        );

        $instructorRole = Role::query()->updateOrCreate(
            ['slug' => 'instructor'],
            [
                'name' => 'محاضر ومدرب سريري',
                'name_ar' => 'محاضر ومدرب سريري',
                'name_en' => 'Clinical Instructor & Trainer',
                'description' => 'استعراض المناهج وتقييم مهام الطلاب الإكلينيكية',
                'description_ar' => 'استعراض المناهج وتقييم مهام الطلاب الإكلينيكية',
                'description_en' => 'Curriculum reviewing and clinical voice task grading for enrolled trainees',
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

        // Platform Owner / Super Administrator (Abdo Goda)
        Admin::query()->updateOrCreate(
            ['email' => 'abdogoda0a@gmail.com'],
            [
                'name' => 'Abdo Goda',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'permissions' => null,
                'locale' => 'ar',
            ]
        );

        // Clinical & Academic Supervisor
        Admin::query()->updateOrCreate(
            ['email' => 'supervisor@alameen.edu.sa'],
            [
                'name' => 'د. سارة خليل (مشرف إكلينيكي)',
                'password' => Hash::make('password'),
                'role_id' => $supervisorRole->id,
                'permissions' => null,
                'locale' => 'ar',
            ]
        );

        // Clinical Instructor & Lecturer
        Admin::query()->updateOrCreate(
            ['email' => 'instructor@alameen.edu.sa'],
            [
                'name' => 'د. حازم القاضي (محاضر تخاطب)',
                'password' => Hash::make('password'),
                'role_id' => $instructorRole->id,
                'permissions' => null,
                'locale' => 'ar',
            ]
        );

        // Finance & Payment Receipts Auditor
        Admin::query()->updateOrCreate(
            ['email' => 'finance@alameen.edu.sa'],
            [
                'name' => 'أ. كريم فهمي (مسؤول مالي)',
                'password' => Hash::make('password'),
                'role_id' => $financeRole->id,
                'permissions' => null,
                'locale' => 'ar',
            ]
        );

        // Support & Notification Coordinator
        Admin::query()->updateOrCreate(
            ['email' => 'support@alameen.edu.sa'],
            [
                'name' => 'أ. مروة العبد (دعم وإشعارات)',
                'password' => Hash::make('password'),
                'role_id' => $supportRole->id,
                'permissions' => null,
                'locale' => 'ar',
            ]
        );
    }
}
