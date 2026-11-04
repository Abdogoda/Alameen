<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ClinicalTask;
use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\Level;
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
        // 1. Dynamic Platform, Theme & Payment Settings
        // -------------------------------------------------------------
        $settings = [
            // General & Branding (Requirements 1, 19, 20, 21, 22)
            [
                'key' => 'site_name',
                'value_ar' => 'مركز الأمين',
                'value_en' => 'Al-Ameen Center',
                'group' => 'branding',
            ],
            [
                'key' => 'site_tagline',
                'value_ar' => 'منصة مركز الامين , التخاطب الشامل مع هنا المكاوي , دورات تخاطب شاملة , فيديوهات وتطبيقات عملية',
                'value_en' => 'Al-Ameen Center Platform, Comprehensive Speech Therapy with Hana Mekawy, practical video courses and accredited certification',
                'group' => 'branding',
            ],
            [
                'key' => 'footer_badge',
                'value_ar' => 'مركز الامين للتدريب والتاهيل التخاطبي',
                'value_en' => 'Al-Ameen Center for Speech Training & Rehabilitation',
                'group' => 'branding',
            ],
            [
                'key' => 'site_logo',
                'value_ar' => '/assets/images/logo.png',
                'value_en' => '/assets/images/logo.png',
                'group' => 'branding',
            ],
            [
                'key' => 'footer_logo',
                'value_ar' => '/assets/images/logo.png',
                'value_en' => '/assets/images/logo.png',
                'group' => 'branding',
            ],

            // Theme & Color Variables (Dashboard Control for Colors)
            [
                'key' => 'theme_primary_color',
                'value_ar' => '#012383',
                'value_en' => '#012383',
                'group' => 'theme',
            ],
            [
                'key' => 'theme_secondary_color',
                'value_ar' => '#0B2A8F',
                'value_en' => '#0B2A8F',
                'group' => 'theme',
            ],
            [
                'key' => 'theme_accent_color',
                'value_ar' => '#10B77F',
                'value_en' => '#10B77F',
                'group' => 'theme',
            ],
            [
                'key' => 'theme_gold_color',
                'value_ar' => '#C59B27',
                'value_en' => '#C59B27',
                'group' => 'theme',
            ],
            [
                'key' => 'theme_footer_bg',
                'value_ar' => '#031338',
                'value_en' => '#031338',
                'group' => 'theme',
            ],

            // Student Portal Page Visibility Settings (Admin Controlled)
            [
                'key' => 'page_student_dashboard_enabled',
                'value_ar' => '0',
                'value_en' => '0',
                'group' => 'pages',
            ],
            [
                'key' => 'page_courses_enabled',
                'value_ar' => '1',
                'value_en' => '1',
                'group' => 'pages',
            ],
            [
                'key' => 'page_tasks_enabled',
                'value_ar' => '0',
                'value_en' => '0',
                'group' => 'pages',
            ],
            [
                'key' => 'page_grades_enabled',
                'value_ar' => '0',
                'value_en' => '0',
                'group' => 'pages',
            ],
            [
                'key' => 'page_notifications_enabled',
                'value_ar' => '0',
                'value_en' => '0',
                'group' => 'pages',
            ],
            [
                'key' => 'page_settings_enabled',
                'value_ar' => '1',
                'value_en' => '1',
                'group' => 'pages',
            ],

            // Payment: Vodafone Cash (Requirement 17)
            [
                'key' => 'vodafone_cash_number',
                'value_ar' => '01005123784',
                'value_en' => '01005123784',
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
                'value_ar' => '01005123784@instapay',
                'value_en' => '01005123784@instapay',
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
                'value_ar' => 'مركز الأمين للتدريب والتأهيل',
                'value_en' => 'Al-Ameen Center for Training & Rehabilitation',
                'group' => 'payment_bank',
            ],

            // Contact & Social (Requirements 16, 17, 18, 23)
            [
                'key' => 'contact_email',
                'value_ar' => 'hm2991479@gmail.com',
                'value_en' => 'hm2991479@gmail.com',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value_ar' => '01005123784',
                'value_en' => '01005123784',
                'group' => 'contact',
            ],
            [
                'key' => 'social_whatsapp',
                'value_ar' => 'https://wa.me/201005123784',
                'value_en' => 'https://wa.me/201005123784',
                'group' => 'social',
            ],
            [
                'key' => 'social_facebook_personal',
                'value_ar' => 'https://www.facebook.com/share/1BY6bGSNeR/?mibextid=wwXIfr',
                'value_en' => 'https://www.facebook.com/share/1BY6bGSNeR/?mibextid=wwXIfr',
                'group' => 'social',
            ],
            [
                'key' => 'social_facebook_center',
                'value_ar' => 'https://www.facebook.com/share/1CChF5RHCT/?mibextid=wwXIfr',
                'value_en' => 'https://www.facebook.com/share/1CChF5RHCT/?mibextid=wwXIfr',
                'group' => 'social',
            ],
            [
                'key' => 'social_facebook',
                'value_ar' => 'https://www.facebook.com/share/1CChF5RHCT/?mibextid=wwXIfr',
                'value_en' => 'https://www.facebook.com/share/1CChF5RHCT/?mibextid=wwXIfr',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok_personal',
                'value_ar' => 'https://www.tiktok.com/@slt.hanamekawy?_r=1&_t=ZS-97eMA66BpIa',
                'value_en' => 'https://www.tiktok.com/@slt.hanamekawy?_r=1&_t=ZS-97eMA66BpIa',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok_center',
                'value_ar' => 'https://www.tiktok.com/@alameen_center?_r=1&_t=ZS-9AyFeNMOY5N',
                'value_en' => 'https://www.tiktok.com/@alameen_center?_r=1&_t=ZS-9AyFeNMOY5N',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok',
                'value_ar' => 'https://www.tiktok.com/@alameen_center?_r=1&_t=ZS-9AyFeNMOY5N',
                'value_en' => 'https://www.tiktok.com/@alameen_center?_r=1&_t=ZS-9AyFeNMOY5N',
                'group' => 'social',
            ],
            [
                'key' => 'working_hours',
                'value_ar' => 'السبت - الخميس من 12 - 9 م',
                'value_en' => 'Saturday - Thursday from 12 - 9 PM',
                'group' => 'contact',
            ],
            [
                'key' => 'headquarters_address',
                'value_ar' => 'الزقازيق - شارع الغشام',
                'value_en' => 'Zagazig - El-Ghasham Street',
                'group' => 'contact',
            ],

            // Certificate System (Requirements 8, 9)
            [
                'key' => 'certificate_default_template',
                'value_ar' => 'custom',
                'value_en' => 'custom',
                'group' => 'certificates',
            ],
            [
                'key' => 'certificate_custom_bg',
                'value_ar' => '/assets/images/alameen-images/certificate.png',
                'value_en' => '/assets/images/alameen-images/certificate.png',
                'group' => 'certificates',
            ],
            [
                'key' => 'certificate_signatory_name',
                'value_ar' => 'Hana El-Mekawy',
                'value_en' => 'Hana El-Mekawy',
                'group' => 'certificates',
            ],
            [
                'key' => 'certificate_signatory_title',
                'value_ar' => 'General Manager',
                'value_en' => 'General Manager',
                'group' => 'certificates',
            ],
            [
                'key' => 'certificate_min_percentage',
                'value_ar' => '95',
                'value_en' => '95',
                'group' => 'certificates',
            ],

            // Auth Artwork (Requirement 24)
            [
                'key' => 'auth_login_image',
                'value_ar' => '/assets/images/alameen-images/6.jpeg',
                'value_en' => '/assets/images/alameen-images/6.jpeg',
                'group' => 'branding',
            ],
        ];

        foreach ($settings as $s) {
            Setting::query()->updateOrCreate(['key' => $s['key']], $s);
        }

        // -------------------------------------------------------------
        // 2. Dynamic Content Blocks
        // -------------------------------------------------------------

        // Block 1: Hero Slider (Requirements 2, 3, 4)
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'hero'],
            [
                'title_ar' => 'كورس التخاطب الشامل مع هنا المكاوي – Round 5',
                'title_en' => 'Comprehensive Speech Therapy Course with Hana Mekawy – Round 5',
                'subtitle_ar' => 'دورات تخاطب شاملة , فيديوهات وتطبيقات عملية , شهادة معتمدة من مركز الامين',
                'subtitle_en' => 'Comprehensive speech therapy courses, practical video demonstrations, and verified certification from Al-Ameen Center.',
                'metadata' => [
                    'cta_primary_ar' => 'استكشف المسارات التدريبية',
                    'cta_primary_en' => 'Explore Training Tracks',
                    'cta_primary_url' => '#courses',
                    'cta_secondary_ar' => 'تواصل معنا',
                    'cta_secondary_en' => 'Contact Us',
                    'cta_secondary_url' => '#contact',
                    'students_count' => '2500+',
                    'courses_count' => '45+',
                    'supervisors_count' => '12+',
                    'images' => [
                        '/assets/images/alameen-images/6.jpeg',
                        '/assets/images/alameen-images/4.jpeg',
                        '/assets/images/alameen-images/2.jpeg',
                        '/assets/images/alameen-images/1.jpeg',
                    ],
                ],
                'is_active' => true,
            ]
        );

        // Block 2: Feedback Showcase replacing Slide 2 (Requirement 7)
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'feedback'],
            [
                'title_ar' => 'مركز الأمين.. ثقتكم دايمًا مسؤولية بنعتز بيها، ورضاكم هو هدفنا اللي بنسعى ليه كل يوم',
                'title_en' => 'Al-Ameen Center.. Your trust is always a responsibility we cherish, and your satisfaction is our daily goal',
                'subtitle_ar' => 'بنؤمن إن آراءكم وملاحظاتكم هي الدافع الحقيقي لينا علشان نتطور ونقدم الأفضل باستمرار ونكون عند حسن ظنكم وثقتكم فينا.',
                'subtitle_en' => 'We believe your feedback and reviews are the real motivation for us to continuously evolve and deliver the best.',
                'metadata' => [
                    'eyebrow_ar' => 'آراء المتدربين وثقة أولياء الأمور',
                    'eyebrow_en' => 'Student Reviews & Parent Trust',
                    'cta_text_ar' => 'تصفح جميع الآراء والتقييمات',
                    'cta_text_en' => 'Browse All Reviews',
                    'cta_url' => '#reviews',
                    'images' => [
                        '/assets/images/alameen-images/6.jpeg',
                        '/assets/images/alameen-images/1.jpeg',
                        '/assets/images/alameen-images/2.jpeg',
                        '/assets/images/alameen-images/3.jpeg',
                        '/assets/images/alameen-images/6.jpeg',
                        '/assets/images/alameen-images/4.jpeg',
                        '/assets/images/alameen-images/5.jpeg',
                        '/assets/images/alameen-images/2.jpeg',
                        '/assets/images/alameen-images/6.jpeg',
                    ],
                ],
                'is_active' => true,
            ]
        );

        // Block 3: Courses Header (Requirements 9, 10)
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'courses_header'],
            [
                'title_ar' => 'استكشف كورسات التخاطب الشامل مع هنا المكاوي',
                'title_en' => 'Explore Comprehensive Speech Courses with Hana Mekawy',
                'subtitle_ar' => 'اختر مسارك التدريبي الأكاديمي في علوم التخاطب مع مناهج معتمدة وتطبيقات عملية شاملة للطلاب.',
                'subtitle_en' => 'Choose your academic training path in speech sciences with accredited curricula and comprehensive practical applications for students.',
                'metadata' => [
                    'eyebrow_ar' => 'دليل كورسات التخاطب للطلاب',
                    'eyebrow_en' => 'Student Speech Courses Directory',
                    'search_placeholder_ar' => 'ابحث باسم الكورس، المحاضر، أو المهارة...',
                    'search_placeholder_en' => 'Search by course title, instructor, or skill...',
                ],
                'is_active' => true,
            ]
        );

        // Block 4: About & Values (Requirements 11, 12, 13)
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'about'],
            [
                'title_ar' => 'منصة مركز الأمين.. شريكك الأول في تأهيل التخاطب الشامل',
                'title_en' => 'Al-Ameen Center Platform.. Your Premier Partner in Comprehensive Speech Rehabilitation',
                'subtitle_ar' => 'منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.',
                'subtitle_en' => 'A specialized educational platform combining academic knowledge and application to empower speech therapy students and practitioners to master diagnostic and therapeutic skills with the highest scientific quality standards.',
                'metadata' => [
                    'pillars' => [
                        [
                            'icon' => '',
                            'title_ar' => 'مناهج تدريبية معتمدة',
                            'title_en' => 'Accredited Training Curricula',
                            'desc_ar' => 'مسارات متسلسلة تبدأ من التشخيص الأولي وتمتد إلى جلسات النطق المتقدمة وتعديل السلوك خطوة بخطوة.',
                            'desc_en' => 'Sequential pathways starting from initial diagnosis and extending to advanced articulation sessions and step-by-step behavior modification.',
                        ],
                        [
                            'icon' => '',
                            'title_ar' => 'مهام صوتية وتقييم مباشر',
                            'title_en' => 'Voice Assignments & Direct Assessment',
                            'desc_ar' => 'تطبيق عملي من خلال تسجيل المقاطع الصوتية ومخارج الحروف وتلقي ملاحظات وتوجيهات دقيقة من نخبة المحاضرين.',
                            'desc_en' => 'Practical application through recording audio samples and articulation exercises, receiving precise guidance from expert lecturers.',
                        ],
                        [
                            'icon' => '',
                            'title_ar' => 'أخصائيون واستشاريون موثوقون',
                            'title_en' => 'Certified Specialists & Consultants',
                            'desc_ar' => 'فريق متكامل من أطباء ومحاضري التخاطب ذوي الخبرات الطويلة في المستشفيات والمراكز التأهيلية الكبرى.',
                            'desc_en' => 'A comprehensive team of speech physicians and lecturers with extensive clinical experience in major hospitals and rehabilitation centers.',
                        ],
                        [
                            'icon' => '',
                            'title_ar' => 'شهادات إتمام موثقة برمز QR',
                            'title_en' => 'Verified Certificates with QR Codes',
                            'desc_ar' => 'شهادات إنجاز رسمية موثقة برقم مرجعي ورمز استجابة سريعة تثبت كفاءتك وساعاتك التدريبية في التخصص.',
                            'desc_en' => 'Official achievement certificates documented with reference numbers and QR codes verifying your competence and training hours.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        // Block 5: FAQ (Cleaned from السريرية)
        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'faq'],
            [
                'title_ar' => 'الأسئلة الشائعة والأكثر تكراراً',
                'title_en' => 'Frequently Asked Questions',
                'subtitle_ar' => 'كل ما تحتاج لمعرفته حول التسجيل، طرق الدفع المتاحة، والشهادات المعتمدة من مركز الأمين.',
                'subtitle_en' => 'Everything you need to know about registration, available payment methods, and certified credentials.',
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
                            'answer_ar' => 'حرصاً على الفائدة والتراكم العلمي، تطبق المنصة نظام القفل المتسلسل؛ حيث يفتح كل فيديو بعد إكمال نسبة المشاهدة المطلوبة للمحاضرة السابقة.',
                            'answer_en' => 'To guarantee scientific progression, sequential video locking is enforced: video N+1 unlocks only after reaching the required watch percentage of video N.',
                        ],
                        [
                            'question_ar' => 'ما هي شروط الحصول على الشهادة الرقمية المعتمدة؟',
                            'question_en' => 'What are the requirements to obtain the verified digital certificate?',
                            'answer_ar' => 'يشترط إكمال 95% على الأقل من مجموع فيديوهات الدورة والمهام التطبيقية. تصدر الشهادة برمز فريد مع رمز استجابة سريعة (QR Code) للفحص والتحقق.',
                            'answer_en' => 'You must complete at least 95% of total course video progress. Certificates are generated with a unique verification code and QR code.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        // -------------------------------------------------------------
        // 3. Dynamic Menu Navigation (Requirements 5, 23)
        // -------------------------------------------------------------
        $headerMenu = Menu::query()->updateOrCreate(
            ['location' => 'header'],
            ['name' => 'القائمة العلوية الرئيسية (Header Navigation)']
        );

        $headerItems = [
            ['title_ar' => 'الرئيسية', 'title_en' => 'Home', 'url' => '/', 'route_name' => 'home', 'sort_order' => 1],
            ['title_ar' => 'الدورات التدريبية', 'title_en' => 'Courses', 'url' => '/courses', 'route_name' => 'courses.index', 'sort_order' => 2],
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
            ['title_ar' => 'تقييم المهام والدرجات', 'title_en' => 'Grades & Tasks', 'url' => '/grades', 'route_name' => 'grades.index', 'sort_order' => 2],
            ['title_ar' => 'الشهادات والاعتمادات', 'title_en' => 'Certificates', 'url' => '/certificates', 'route_name' => 'certificates.index', 'sort_order' => 3],
            ['title_ar' => 'تقرير التدقيق والاعتماد', 'title_en' => 'Audit Report', 'url' => '/audit-report', 'route_name' => 'audit.report', 'sort_order' => 4],
        ];

        foreach ($footerItems as $item) {
            MenuItem::query()->updateOrCreate(
                ['menu_id' => $footerMenu->id, 'url' => $item['url']],
                array_merge($item, ['is_active' => true, 'target' => '_self'])
            );
        }

        // -------------------------------------------------------------
        // 4. Default Diploma Course & Sequential Videos (Requirements 29, 30)
        // -------------------------------------------------------------
        $defaultCourse = Course::query()->firstOrCreate(
            ['slug' => 'speech-therapy-diploma'],
            [
                'title_ar' => 'دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة',
                'title_en' => 'Diploma in Fundamentals & Applications of Speech Therapy',
                'description_ar' => 'دورات تخاطب شاملة , فيديوهات وتطبيقات عمليه ,شهاده معتمده من مركز الامين',
                'description_en' => 'Comprehensive speech therapy training program with sequential video locking and verified certification from Al-Ameen Center.',
                'price' => 1200.00,
                'original_price' => 1800.00,
                'access_duration_days' => 365,
                'thumbnail' => '/assets/images/course-topic-basics.png',
                'promo_video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'promo_video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        $defaultCourse->update([
            'title_ar' => 'دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة',
            'description_ar' => 'دورات تخاطب شاملة , فيديوهات وتطبيقات عمليه ,شهاده معتمده من مركز الامين',
            'original_price' => 1800.00,
            'promo_video_source' => 'mux',
            'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
            'promo_video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
        ]);

        // Dynamic Levels Hierarchy
        $level1 = Level::query()->updateOrCreate(
            ['course_id' => $defaultCourse->id, 'sort_order' => 1],
            [
                'title_ar' => 'المستوى الأول: مدخل إلى علوم التخاطب والتواصل',
                'title_en' => 'Level 1: Introduction to Speech & Communication Sciences',
                'description_ar' => 'أساسيات عملية التواصل، التشريح الصوتي، وتصنيف اضطرابات النطق',
                'description_en' => 'Fundamentals of communication, vocal anatomy, and articulation classification.',
            ]
        );

        $level2 = Level::query()->updateOrCreate(
            ['course_id' => $defaultCourse->id, 'sort_order' => 2],
            [
                'title_ar' => 'المستوى الثاني: مهارات الصوت والاستماع والتمييز',
                'title_en' => 'Level 2: Vocal Skills, Auditory Listening & Discrimination',
                'description_ar' => 'الوعي الصوتي، التمييز السمعي للأصوات، وبناء الأنشطة المنزلية',
                'description_en' => 'Phonological awareness, auditory discrimination, and home activity design.',
            ]
        );

        $level3 = Level::query()->updateOrCreate(
            ['course_id' => $defaultCourse->id, 'sort_order' => 3],
            [
                'title_ar' => 'المستوى الثالث: مخارج الحروف وتصحيح اللدغات والتشويه',
                'title_en' => 'Level 3: Articulation Points & Lisp Correction Protocols',
                'description_ar' => 'تصحيح نطق الراء والسين والكاف والتمارين العضلية المصاحبة',
                'description_en' => 'Articulatory corrections for /r/, /s/, /k/ sounds and accompanying motor drills.',
            ]
        );

        $level4 = Level::query()->updateOrCreate(
            ['course_id' => $defaultCourse->id, 'sort_order' => 4],
            [
                'title_ar' => 'المستوى الرابع: التطبيقات العملية ودراسة الحالات والتقييم النهائي',
                'title_en' => 'Level 4: Practical Applications, Case Studies & Final Evaluation',
                'description_ar' => 'دراسة حالات واقعية، كتابة الخطة العلاجية الفردية، وتأهيل شهادة الـ ٩٥٪',
                'description_en' => 'Real-world case analysis, individualized treatment planning, and 95% qualification.',
            ]
        );

        $videos = [
            // Level 1 Lessons (1 to 5)
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ١: مدخل إلى عملية التواصل البشري',
                'title_en' => 'Lesson 1: Introduction to Human Communication Process',
                'slug' => 'intro-human-communication',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-01',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 1,
                'is_free_preview' => true,
                'task_title' => 'مخطط أركان التواصل',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٢: مكونات عملية التواصل وعناصر الرسالة',
                'title_en' => 'Lesson 2: Communication Components & Message Channels',
                'slug' => 'communication-components-message',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-02',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 2,
                'is_free_preview' => false,
                'task_title' => 'تحليل تشويش الرسالة',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٣: مراحل النمو اللغوي عند الأطفال',
                'title_en' => 'Lesson 3: Child Language Development Stages',
                'slug' => 'child-language-development-stages',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-03',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 3,
                'is_free_preview' => false,
                'task_title' => 'جدول مقارنة المعالم اللغوية',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٤: بيئة التعلّم الداعمة وتصنيف اضطرابات النطق',
                'title_en' => 'Lesson 4: Supportive Learning Environment & Speech Disorders',
                'slug' => 'supportive-learning-environment',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-04',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 4,
                'is_free_preview' => false,
                'task_title' => 'استمارة تجهيز بيئة الجلسة',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٥: مراجعة المفاهيم الأساسية للتخاطب',
                'title_en' => 'Lesson 5: Review of Core Concepts in Speech Pathology',
                'slug' => 'foundational-concepts-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-05',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 5,
                'is_free_preview' => false,
                'task_title' => 'ملخص مراجعة المستوى الأول',
            ],

            // Level 2 Lessons (6 to 10)
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٦: تشريح الجهاز الصوتي والتنفس ودعم الصوت',
                'title_en' => 'Lesson 6: Vocal Anatomy, Respiration & Breath Support',
                'slug' => 'vocal-anatomy-breath-support',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-06',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 6,
                'is_free_preview' => false,
                'task_title' => 'تمرين التحكم في النفس الصوتي',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٧: آليات إنتاج الصوت والإيقاع وسرعة الكلام',
                'title_en' => 'Lesson 7: Sound Production, Rhythm & Speech Rate Mechanisms',
                'slug' => 'speech-rate-rhythm-mechanisms',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-07',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 7,
                'is_free_preview' => false,
                'task_title' => 'مقياس ضبط إيقاع الكلام',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٨: مهارات التمييز السمعي للأصوات المتشابهة',
                'title_en' => 'Lesson 8: Auditory Discrimination for Minimal Word Pairs',
                'slug' => 'auditory-discrimination-minimal-pairs',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-08',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 8,
                'is_free_preview' => false,
                'task_title' => 'ورقة نشاط التمييز الصوتي (٦ أزواج)',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٩: تدريبات الوعي الصوتي والتقطيع',
                'title_en' => 'Lesson 9: Phonological Awareness & Syllable Segmentation',
                'slug' => 'phonological-awareness-segmentation',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-09',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 85,
                'sort_order' => 9,
                'is_free_preview' => false,
                'task_title' => 'استراتيجية التقطيع الصوتي للكلمات',
            ],
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ١٠: تقنيات تعديل السلوك ومراجعة المهارات الصوتية',
                'title_en' => 'Lesson 10: Behavior Modification & Voice Skills Review',
                'slug' => 'behavior-modification-vocal-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-10',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 10,
                'is_free_preview' => false,
                'task_title' => 'خطة تعزيز وتعديل السلوك بالجلسة',
            ],

            // Level 3 Lessons (11 to 15)
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١١: الانتباه المشترك وتدريبات مخرج حرف الراء',
                'title_en' => 'Lesson 11: Joint Attention & /r/ Sound Articulation Drills',
                'slug' => 'joint-attention-r-sound',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-11',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 11,
                'is_free_preview' => false,
                'task_title' => 'جدول تدرج تصحيح صوت الراء',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٢: التقليد اللفظي وتصحيح لدغة السين',
                'title_en' => 'Lesson 12: Verbal Imitation & /s/ Lisp Correction Protocols',
                'slug' => 'verbal-imitation-s-lisp-correction',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-12',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 12,
                'is_free_preview' => false,
                'task_title' => 'استمارة تقييم اللدغة السينّية',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٣: توسيع المفردات وعلاج إبدال الكاف والتاء',
                'title_en' => 'Lesson 13: Vocabulary Expansion & /k/ to /t/ Substitution Therapy',
                'slug' => 'vocabulary-expansion-k-t-substitution',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-13',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 13,
                'is_free_preview' => false,
                'task_title' => 'بطاقات التمييز بين الكاف والتاء',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٤: بناء الجملة البسيطة والتمارين الحركية لأعضاء النطق',
                'title_en' => 'Lesson 14: Simple Sentence Construction & Oral-Motor Drills (OMEs)',
                'slug' => 'simple-sentence-oral-motor-drills',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-14',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 14,
                'is_free_preview' => false,
                'task_title' => 'بروتوكول التمارين العضلية للفم',
            ],
            [
                'level_id' => $level3->id,
                'title_ar' => 'الدرس ١٥: توظيف الأسئلة وبروتوكول التثبيت في الكلام العفوي',
                'title_en' => 'Lesson 15: Question Usage & Generalization to Spontaneous Speech',
                'slug' => 'question-usage-spontaneous-speech',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-15',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 15,
                'is_free_preview' => false,
                'task_title' => 'مصفوفة قياس الكلام التلقائي',
            ],

            // Level 4 Lessons (16 to 20)
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٦: خطة التقييم واختيار أنشطة التدريب ودراسة الحالة',
                'title_en' => 'Lesson 16: Assessment Plan, Training Activity Selection & Case Study',
                'slug' => 'assessment-plan-case-study',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-16',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 16,
                'is_free_preview' => false,
                'task_title' => 'نموذج دراسة الحالة والتشخيص الأولي',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٧: تصميم الخطة العلاجية الفردية (IEP) وتدرج صعوبة النشاط',
                'title_en' => 'Lesson 17: Individualized Treatment Plan (IEP) & Difficulty Progression',
                'slug' => 'iep-individual-treatment-plan',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-17',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 17,
                'is_free_preview' => false,
                'task_title' => 'صياغة خطة علاجية فردية متكاملة',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٨: استراتيجيات الإرشاد الأسري والمتابعة المنزلية وتسجيل الملاحظات',
                'title_en' => 'Lesson 18: Family Counseling Strategies & Clinical Observation Notes',
                'slug' => 'family-counseling-observation-notes',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-18',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 18,
                'is_free_preview' => false,
                'task_title' => 'دليل الإرشاد والمتابعة المنزلية للأسرة',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ١٩: ورشة المحاكاة العملية ومراجعة التقدم للحالات الواقعية',
                'title_en' => 'Lesson 19: Clinical Simulation Workshop & Progress Review',
                'slug' => 'simulation-workshop-progress-review',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-19',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 19,
                'is_free_preview' => false,
                'task_title' => 'تقرير تحليل جلسة محاكاة سريرية',
            ],
            [
                'level_id' => $level4->id,
                'title_ar' => 'الدرس ٢٠: الاختبار النهائي والتقييم الختامي لتأهيل الاعتماد',
                'title_en' => 'Lesson 20: Final Comprehensive Evaluation & 95% Certification Exam',
                'slug' => 'final-evaluation-certification-exam',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-20',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 20,
                'is_free_preview' => false,
                'task_title' => 'مشروع التخرج والتقييم الختامي الشامل',
            ],
        ];

        foreach ($videos as $v) {
            $taskTitle = $v['task_title'];
            unset($v['task_title']);

            $videoModel = Video::query()->updateOrCreate(
                ['course_id' => $defaultCourse->id, 'slug' => $v['slug']],
                $v
            );

            ClinicalTask::query()->updateOrCreate(
                [
                    'course_id' => $defaultCourse->id,
                    'video_id' => $videoModel->id,
                ],
                [
                    'title_ar' => "مهمة تطبيقية: {$taskTitle}",
                    'title_en' => "Practical Task: {$v['title_en']}",
                    'instructions_ar' => 'قم بإعداد وتسجيل التطبيق العملي للدرس وفق المعايير المحددة.',
                    'instructions_en' => 'Record a clear practical demonstration according to specified criteria.',
                    'max_score' => 100,
                ]
            );
        }
    }
}
