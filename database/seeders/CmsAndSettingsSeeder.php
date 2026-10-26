<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\Video;
use Illuminate\Database\Seeder;

class CmsAndSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds for 100% dynamic CMS, settings, and navigation.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. Dynamic Platform & Payment Settings
        // -------------------------------------------------------------
        $settings = [
            // General & Branding
            [
                'key' => 'site_name',
                'value_ar' => 'منصة الأمين للتخاطب وعلاج النطق',
                'value_en' => 'Al-Ameen Speech & Language Therapy Platform',
                'group' => 'branding',
            ],
            [
                'key' => 'site_tagline',
                'value_ar' => 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائيي النطق والتخاطب في الوطن العربي',
                'value_en' => 'The premier clinical & academic platform for speech and language pathologists in the Arab World',
                'group' => 'branding',
            ],

            // Payment: Vodafone Cash
            [
                'key' => 'vodafone_cash_number',
                'value_ar' => '01012345678',
                'value_en' => '01012345678',
                'group' => 'payment_vodafone',
            ],
            [
                'key' => 'vodafone_cash_instructions',
                'value_ar' => 'يرجى التحويل إلى رقم فودافون كاش أعلاه والاحتفاظ بصورة الإيصال أو رسالة التأكيد لرفعها بالموقع.',
                'value_en' => 'Please transfer to the Vodafone Cash number above and upload the confirmation screenshot.',
                'group' => 'payment_vodafone',
            ],

            // Payment: InstaPay
            [
                'key' => 'instapay_handle',
                'value_ar' => 'alameen@instapay',
                'value_en' => 'alameen@instapay',
                'group' => 'payment_instapay',
            ],
            [
                'key' => 'instapay_instructions',
                'value_ar' => 'يمكنك الدفع الفوري عبر تطبيق إنستاباي باستخدام المعرف أعلاه، ثم رفع إيصال العملية.',
                'value_en' => 'Transfer instantly via InstaPay using the handle above, then upload your transaction receipt.',
                'group' => 'payment_instapay',
            ],

            // Payment: Bank Wire
            [
                'key' => 'bank_name',
                'value_ar' => 'البنك الأهلي المصري',
                'value_en' => 'National Bank of Egypt (NBE)',
                'group' => 'payment_bank',
            ],
            [
                'key' => 'bank_iban',
                'value_ar' => 'EG380002000100000012345678901',
                'value_en' => 'EG380002000100000012345678901',
                'group' => 'payment_bank',
            ],
            [
                'key' => 'bank_account_holder',
                'value_ar' => 'مؤسسة الأمين للاستشارات السريرية والتدريب',
                'value_en' => 'Al-Ameen Clinical Consultation & Training',
                'group' => 'payment_bank',
            ],

            // Contact & Social
            [
                'key' => 'contact_email',
                'value_ar' => 'contact@alameen-speech.com',
                'value_en' => 'contact@alameen-speech.com',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value_ar' => '+20 100 000 0000',
                'value_en' => '+20 100 000 0000',
                'group' => 'contact',
            ],
            [
                'key' => 'social_whatsapp',
                'value_ar' => 'https://wa.me/201000000000',
                'value_en' => 'https://wa.me/201000000000',
                'group' => 'social',
            ],
        ];

        foreach ($settings as $s) {
            Setting::query()->updateOrCreate(['key' => $s['key']], $s);
        }

        // -------------------------------------------------------------
        // 2. Dynamic Content Blocks (Hero, FAQ, About)
        // -------------------------------------------------------------
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'hero'],
            [
                'title_ar' => 'أتقن الممارسة الإكلينيكية في التخاطب والنطق بأعلى المعايير العالمية',
                'title_en' => 'Master Clinical Speech-Language Pathology with Global Standards',
                'subtitle_ar' => 'دورات سريرية متقدمة، فيديوهات تطبيقية عملية، وتقييم صوتي سريري مباشر مع شهادات معتمدة برمز تحقق.',
                'subtitle_en' => 'Advanced clinical courses, practical demonstrations, voice assignment grading, and verifiable certificates.',
                'metadata' => [
                    'cta_primary_ar' => 'استكشف المسارات التدريبية',
                    'cta_primary_en' => 'Explore Clinical Tracks',
                    'cta_primary_url' => '/courses',
                    'cta_secondary_ar' => 'تواصل معنا',
                    'cta_secondary_en' => 'Contact Us',
                    'cta_secondary_url' => '/contact',
                    'students_count' => '2500+',
                    'courses_count' => '45+',
                    'supervisors_count' => '12+',
                ],
                'is_active' => true,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'faq'],
            [
                'title_ar' => 'الأسئلة الشائعة والأكثر تكراراً',
                'title_en' => 'Frequently Asked Questions',
                'subtitle_ar' => 'كل ما تحتاج لمعرفته حول التسجيل، طرق الدفع المتاحة، والشهادات السريرية المعتمدة.',
                'subtitle_en' => 'Everything you need to know about registration, payment methods, and certified clinical credentials.',
                'metadata' => [
                    'items' => [
                        [
                            'question_ar' => 'كيف يتم تفعيل اشتراكي بعد التحويل عبر فودافون كاش أو إنستاباي؟',
                            'question_en' => 'How is my enrollment activated after paying via Vodafone Cash or InstaPay?',
                            'answer_ar' => 'بمجرد تحويل المبلغ، قم برفع صورة الإيصال في صفحة الكورس، وسيقوم المشرف المالي بمراجعتها واعتماد الوصول الفوري لحسابك تلقائياً.',
                            'answer_en' => 'After transferring, simply upload your receipt screenshot on the course page. Our finance team will review and auto-grant immediate access.',
                        ],
                        [
                            'question_ar' => 'هل الفيديوهات والمحاضرات مفتوحة بالكامل أم مقيدة بتسلسل؟',
                            'question_en' => 'Are video lectures unlocked all at once or sequentially locked?',
                            'answer_ar' => 'حرصاً على الفائدة السريرية والتراكم العلمي، تطبق المنصة نظام القفل المتسلسل؛ حيث يفتح كل فيديو بعد إكمال نسبة المشاهدة المطلوبة للمحاضرة السابقة.',
                            'answer_en' => 'To guarantee scientific progression, sequential video locking is enforced: video N+1 unlocks only after reaching the required watch percentage of video N.',
                        ],
                        [
                            'question_ar' => 'ما هي شروط الحصول على الشهادة الرقمية المعتمدة؟',
                            'question_en' => 'What are the requirements to obtain the verified digital certificate?',
                            'answer_ar' => 'يشترط إكمال 95% على الأقل من مجموع فيديوهات الدورة والمهام السريرية. تصدر الشهادة برمز فريد مع رمز استجابة سريعة (QR Code) للفحص والتحقق.',
                            'answer_en' => 'You must complete at least 95% of total course video progress. Certificates are generated with a unique verification code and QR code.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        // -------------------------------------------------------------
        // 3. Dynamic Menu Navigation (Header & Footers)
        // -------------------------------------------------------------
        $headerMenu = Menu::query()->updateOrCreate(
            ['location' => 'header'],
            ['name' => 'القائمة العلوية الرئيسية (Header Navigation)']
        );

        $headerItems = [
            ['title_ar' => 'الرئيسية', 'title_en' => 'Home', 'url' => '/', 'route_name' => 'home', 'sort_order' => 1],
            ['title_ar' => 'الدورات السريرية', 'title_en' => 'Courses', 'url' => '/courses', 'route_name' => 'courses.index', 'sort_order' => 2],
            ['title_ar' => 'آراء المتدربين', 'title_en' => 'Reviews', 'url' => '/reviews', 'route_name' => 'reviews.index', 'sort_order' => 3],
            ['title_ar' => 'اتصل بنا', 'title_en' => 'Contact', 'url' => '/contact', 'route_name' => 'contact', 'sort_order' => 4],
        ];

        foreach ($headerItems as $item) {
            MenuItem::query()->updateOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => $item['url']],
                array_merge($item, ['is_active' => true, 'target' => '_self'])
            );
        }

        $footerMenu = Menu::query()->updateOrCreate(
            ['location' => 'footer_quick_links'],
            ['name' => 'روابط سريعة في التذييل (Footer Quick Links)']
        );

        $footerItems = [
            ['title_ar' => 'جميع الكورسات', 'title_en' => 'All Courses', 'url' => '/courses', 'route_name' => 'courses.index', 'sort_order' => 1],
            ['title_ar' => 'تقييم المهام والدرجات', 'title_en' => 'Clinical Grades', 'url' => '/grades', 'route_name' => 'grades.index', 'sort_order' => 2],
            ['title_ar' => 'الشهادات والاعتمادات', 'title_en' => 'Certificates', 'url' => '/certificates', 'route_name' => 'certificates.index', 'sort_order' => 3],
            ['title_ar' => 'تقرير التدقيق السريري', 'title_en' => 'Audit Report', 'url' => '/audit-report', 'route_name' => 'audit.report', 'sort_order' => 4],
        ];

        foreach ($footerItems as $item) {
            MenuItem::query()->updateOrCreate(
                ['menu_id' => $footerMenu->id, 'url' => $item['url']],
                array_merge($item, ['is_active' => true, 'target' => '_self'])
            );
        }

        // -------------------------------------------------------------
        // 4. Default Clinical Diploma Course & Sequential Videos
        // -------------------------------------------------------------
        $defaultCourse = Course::query()->firstOrCreate(
            ['slug' => 'speech-therapy-diploma'],
            [
                'title_ar' => 'دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة',
                'title_en' => 'Diploma in Fundamentals & Clinical Applications of Speech Therapy',
                'description_ar' => 'البرنامج التدريبي المتكامل لتأهيل أخصائيي التخاطب وأولياء الأمور مع نظام المهام بعد كل درس ونظام القفل المتسلسل.',
                'description_en' => 'The comprehensive training program for qualifying speech therapists with sequential video locking and verified certification.',
                'price' => 1200.00,
                'access_duration_days' => 365,
                'thumbnail' => '/assets/images/course-topic-basics.png',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        $videos = [
            [
                'title_ar' => 'المحاضرة الأولى: مقدمة في تشريح الجهاز الصوتي والنطقي',
                'title_en' => 'Lecture 1: Anatomy of Speech & Vocal Mechanism',
                'slug' => 'anatomy-speech-mechanism',
                'bunny_video_id' => 'sample-bunny-video-01',
                'duration_seconds' => 1800,
                'min_completion_percentage' => 80,
                'sort_order' => 1,
                'is_free_preview' => true,
            ],
            [
                'title_ar' => 'المحاضرة الثانية: اضطرابات النطق الوظيفية والعضوية',
                'title_en' => 'Lecture 2: Functional & Organic Articulation Disorders',
                'slug' => 'articulation-disorders-assessment',
                'bunny_video_id' => 'sample-bunny-video-02',
                'duration_seconds' => 2400,
                'min_completion_percentage' => 80,
                'sort_order' => 2,
                'is_free_preview' => false,
            ],
            [
                'title_ar' => 'المحاضرة الثالثة: بروتوكول التدخل العلاجي للتأتأة والتلعثم',
                'title_en' => 'Lecture 3: Clinical Intervention Protocol for Stuttering',
                'slug' => 'clinical-stuttering-protocol',
                'bunny_video_id' => 'sample-bunny-video-03',
                'duration_seconds' => 3000,
                'min_completion_percentage' => 85,
                'sort_order' => 3,
                'is_free_preview' => false,
            ],
        ];

        foreach ($videos as $v) {
            Video::query()->updateOrCreate(
                ['course_id' => $defaultCourse->id, 'slug' => $v['slug']],
                $v
            );
        }
    }
}
