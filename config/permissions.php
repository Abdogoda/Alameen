<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Static System Permissions Registry
    |--------------------------------------------------------------------------
    |
    | All admin permissions are strictly declared here in code. They are stored
    | as JSON arrays in `roles.permissions` or `admins.permissions`.
    |
    */

    'permissions' => [
        // Platform Full Control
        'all' => [
            'value' => '*',
            'label_ar' => 'كافة الصلاحيات الكاملة للمنصة (المالك)',
            'label_en' => 'Full Super Administrator Authority (Owner)',
            'group_ar' => 'إدارة النظام الشاملة',
            'group_en' => 'System Administration',
        ],

        // Courses & Lectures
        'courses_view' => [
            'value' => 'courses.view',
            'label_ar' => 'عرض قائمة الدورات والمحاضرات',
            'label_en' => 'View courses & lecture catalogue',
            'group_ar' => 'إدارة الدورات والمحاضرات',
            'group_en' => 'Course Management',
        ],
        'courses_manage' => [
            'value' => 'courses.manage',
            'label_ar' => 'إضافة وتعديل الدورات والمستويات',
            'label_en' => 'Manage course levels & video content',
            'group_ar' => 'إدارة الدورات والمحاضرات',
            'group_en' => 'Course Management',
        ],
        'courses_publish' => [
            'value' => 'courses.publish',
            'label_ar' => 'نشر وتفعيل الدورات الجديدة',
            'label_en' => 'Publish and toggle course availability',
            'group_ar' => 'إدارة الدورات والمحاضرات',
            'group_en' => 'Course Management',
        ],

        // Clinical Tasks & Voice Grading
        'tasks_view' => [
            'value' => 'tasks.view',
            'label_ar' => 'استعراض مهام وتكليفات الطلاب',
            'label_en' => 'Browse student clinical assignments',
            'group_ar' => 'المهام السريرية والتقييم',
            'group_en' => 'Clinical Tasks & Grading',
        ],
        'tasks_grade' => [
            'value' => 'tasks.grade',
            'label_ar' => 'تقييم ورصد درجات التسجيلات السريرية',
            'label_en' => 'Evaluate and grade voice recordings',
            'group_ar' => 'المهام السريرية والتقييم',
            'group_en' => 'Clinical Tasks & Grading',
        ],

        // Certificates & Approvals
        'certificates_view' => [
            'value' => 'certificates.view',
            'label_ar' => 'عرض الشهادات الصادرة',
            'label_en' => 'View issued completion certificates',
            'group_ar' => 'الشهادات والاعتمادات',
            'group_en' => 'Certificates',
        ],
        'certificates_approve' => [
            'value' => 'certificates.approve',
            'label_ar' => 'اعتماد وتوقيع الشهادات للطلاب',
            'label_en' => 'Approve and endorse student certificates',
            'group_ar' => 'الشهادات والاعتمادات',
            'group_en' => 'Certificates',
        ],

        // Manual Payment Receipts & Finance
        'receipts_view' => [
            'value' => 'receipts.view',
            'label_ar' => 'استعراض إيصالات التحويل البنكي',
            'label_en' => 'Inspect payment & wire transfer receipts',
            'group_ar' => 'المالية وإيصالات الدفع',
            'group_en' => 'Finance & Receipts',
        ],
        'receipts_verify' => [
            'value' => 'receipts.verify',
            'label_ar' => 'تدقيق بيانات الإيصال البنكي',
            'label_en' => 'Audit bank & wallet transaction records',
            'group_ar' => 'المالية وإيصالات الدفع',
            'group_en' => 'Finance & Receipts',
        ],
        'receipts_approve' => [
            'value' => 'receipts.approve',
            'label_ar' => 'قبول الإيصال وتفعيل وصول الطالب تلقائياً',
            'label_en' => 'Approve payment & auto-grant student access',
            'group_ar' => 'المالية وإيصالات الدفع',
            'group_en' => 'Finance & Receipts',
        ],
        'receipts_reject' => [
            'value' => 'receipts.reject',
            'label_ar' => 'رفض الإيصال مع إرسال سبب الرفض',
            'label_en' => 'Reject payment receipt with note',
            'group_ar' => 'المالية وإيصالات الدفع',
            'group_en' => 'Finance & Receipts',
        ],

        // Notifications
        'notifications_view' => [
            'value' => 'notifications.view',
            'label_ar' => 'استعراض سجل الإشعارات الصادرة',
            'label_en' => 'View notification history logs',
            'group_ar' => 'الإشعارات والتعاميم',
            'group_en' => 'Notifications',
        ],
        'notifications_send' => [
            'value' => 'notifications.send',
            'label_ar' => 'إرسال إشعار جماعي أو فردي',
            'label_en' => 'Broadcast notifications to students',
            'group_ar' => 'الإشعارات والتعاميم',
            'group_en' => 'Notifications',
        ],
        'notifications_manage' => [
            'value' => 'notifications.manage',
            'label_ar' => 'إدارة قوالب وحذف الإشعارات',
            'label_en' => 'Manage templates and delete alerts',
            'group_ar' => 'الإشعارات والتعاميم',
            'group_en' => 'Notifications',
        ],

        // Students Management
        'students_view' => [
            'value' => 'students.view',
            'label_ar' => 'استعراض قائمة الطلاب المسجلين',
            'label_en' => 'View enrolled students roster',
            'group_ar' => 'شؤون الطلاب والمتدربين',
            'group_en' => 'Students Management',
        ],
        'students_manage' => [
            'value' => 'students.manage',
            'label_ar' => 'تعديل وتفعيل وتعليق حسابات الطلاب',
            'label_en' => 'Manage, activate & suspend student accounts',
            'group_ar' => 'شؤون الطلاب والمتدربين',
            'group_en' => 'Students Management',
        ],

        // Admins & Dynamic Roles
        'admins_view' => [
            'value' => 'admins.view',
            'label_ar' => 'استعراض فريق العمل والمشرفين',
            'label_en' => 'View supervisor and staff roster',
            'group_ar' => 'إدارة المشرفين والأدوار',
            'group_en' => 'Admins & Roles',
        ],
        'admins_manage' => [
            'value' => 'admins.manage',
            'label_ar' => 'إضافة وتعديل حسابات المشرفين',
            'label_en' => 'Manage supervisor accounts',
            'group_ar' => 'إدارة المشرفين والأدوار',
            'group_en' => 'Admins & Roles',
        ],
        'roles_manage' => [
            'value' => 'roles.manage',
            'label_ar' => 'إدارة الأدوار الديناميكية وتعيين صلاحياتها',
            'label_en' => 'Manage dynamic roles and permissions',
            'group_ar' => 'إدارة المشرفين والأدوار',
            'group_en' => 'Admins & Roles',
        ],

        // Dynamic CMS & Global Settings
        'cms_manage' => [
            'value' => 'cms.manage',
            'label_ar' => 'إدارة محتوى الموقع والصفحات والمحتوى الديناميكي',
            'label_en' => 'Manage dynamic CMS blocks, pages & FAQs',
            'group_ar' => 'إدارة المحتوى والقوائم',
            'group_en' => 'CMS & Navigation',
        ],
        'menus_manage' => [
            'value' => 'menus.manage',
            'label_ar' => 'إدارة القوائم العلوية والسفلية (Menu Manager)',
            'label_en' => 'Manage website headers and footers navigation',
            'group_ar' => 'إدارة المحتوى والقوائم',
            'group_en' => 'CMS & Navigation',
        ],
        'settings_manage' => [
            'value' => 'settings.manage',
            'label_ar' => 'التحكم في إعدادات المنصة وطرق الدفع والاتصال',
            'label_en' => 'Configure platform wide parameters & payment details',
            'group_ar' => 'الإعدادات والتقارير',
            'group_en' => 'Settings & Reports',
        ],
        'reports_view' => [
            'value' => 'reports.view',
            'label_ar' => 'استعراض التقارير المالية والأكاديمية',
            'label_en' => 'View financial and academic analytics',
            'group_ar' => 'الإعدادات والتقارير',
            'group_en' => 'Settings & Reports',
        ],
        'reports_export' => [
            'value' => 'reports.export',
            'label_ar' => 'تصدير التقارير بصيغة Excel / PDF',
            'label_en' => 'Export analytical data to Excel/PDF',
            'group_ar' => 'الإعدادات والتقارير',
            'group_en' => 'Settings & Reports',
        ],
    ],
];
