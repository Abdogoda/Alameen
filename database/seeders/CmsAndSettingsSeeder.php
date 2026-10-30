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

        ContentBlock::query()->updateOrCreate(
            ['section_key' => 'about'],
            [
                'title_ar' => 'منصة مركز الأمين.. شريكك الأول في تأهيل التخاطب والنطق',
                'title_en' => 'Al-Ameen Platform.. Your Premier Partner in Speech & Pronunciation Rehabilitation',
                'subtitle_ar' => 'منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق السريري لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.',
                'subtitle_en' => 'A specialized educational platform combining academic knowledge and clinical application to empower speech therapy students and practitioners to master diagnostic and therapeutic skills with the highest scientific quality standards.',
                'metadata' => [
                    'pillars' => [
                        [
                            'icon' => '🎯',
                            'title_ar' => 'مناهج تدريبية معتمدة',
                            'title_en' => 'Accredited Training Curricula',
                            'desc_ar' => 'مسارات متسلسلة تبدأ من التشخيص الأولي وتمتد إلى جلسات النطق المتقدمة وتعديل السلوك خطوة بخطوة.',
                            'desc_en' => 'Sequential pathways starting from initial diagnosis and extending to advanced articulation sessions and step-by-step behavior modification.',
                        ],
                        [
                            'icon' => '🎙️',
                            'title_ar' => 'مهام صوتية وتقييم مباشر',
                            'title_en' => 'Voice Assignments & Direct Assessment',
                            'desc_ar' => 'تطبيق عملي من خلال تسجيل المقاطع الصوتية ومخارج الحروف وتلقي ملاحظات وتوجيهات دقيقة من نخبة المحاضرين.',
                            'desc_en' => 'Practical application through recording audio samples and articulation exercises, receiving precise guidance from expert lecturers.',
                        ],
                        [
                            'icon' => '👨‍⚕️',
                            'title_ar' => 'أخصائيون واستشاريون موثوقون',
                            'title_en' => 'Certified Specialists & Consultants',
                            'desc_ar' => 'فريق متكامل من أطباء ومحاضري التخاطب ذوي الخبرات الطويلة في المستشفيات والمراكز التأهيلية الكبرى.',
                            'desc_en' => 'A comprehensive team of speech physicians and lecturers with extensive clinical experience in major hospitals and rehabilitation centers.',
                        ],
                        [
                            'icon' => '📜',
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
                'title_ar' => 'المستوى الرابع: التطبيقات السريرية ودراسة الحالات والتقييم النهائي',
                'title_en' => 'Level 4: Clinical Applications, Case Studies & Final Evaluation',
                'description_ar' => 'دراسة حالات واقعية، كتابة الخطة العلاجية الفردية، وتأهيل شهادة الـ ٩٥٪',
                'description_en' => 'Real-world case analysis, individualized treatment planning, and 95% qualification.',
            ]
        );

        $videos = [
            // Level 1 Lessons
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ١: مدخل إلى عملية التواصل البشري',
                'title_en' => 'Lesson 1: Introduction to Human Communication Process',
                'slug' => 'intro-human-communication',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-01',
                'duration_seconds' => 1200, // 20 mins
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
                'task_title' => 'تحليل تشويش الرسالة السريرية',
            ],
            [
                'level_id' => $level1->id,
                'title_ar' => 'الدرس ٣: مراحل النمو اللغوي عند الأطفال',
                'title_en' => 'Lesson 3: Child Language Development Stages',
                'slug' => 'child-language-development-stages',
                'video_source' => 'link',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'bunny_video_id' => 'sample-bunny-video-03',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 3,
                'is_free_preview' => false,
                'task_title' => 'جدول مقارنة المعالم اللغوية',
            ],
            // Level 2 Lessons
            [
                'level_id' => $level2->id,
                'title_ar' => 'الدرس ٨: مهارات التمييز السمعي للأصوات المتشابهة',
                'title_en' => 'Lesson 8: Auditory Discrimination for Minimal Word Pairs',
                'slug' => 'auditory-discrimination-minimal-pairs',
                'video_source' => 'mux',
                'mux_playback_id' => 'DS00Spx1CV902MCtP7ToneAwBpR00uoJq',
                'video_url' => 'https://stream.mux.com/DS00Spx1CV902MCtP7ToneAwBpR00uoJq.m3u8',
                'bunny_video_id' => 'sample-bunny-video-04',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 80,
                'sort_order' => 4,
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
                'bunny_video_id' => 'sample-bunny-video-05',
                'duration_seconds' => 1200,
                'min_completion_percentage' => 85,
                'sort_order' => 5,
                'is_free_preview' => false,
                'task_title' => 'استراتيجية التقطيع الصوتي للكلمات',
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
                    'title_ar' => "مهمة مرتبطة: {$taskTitle}",
                    'title_en' => "Clinical Task: {$v['title_en']}",
                    'instructions_ar' => 'قم بإعداد وتسجيل التطبيق العملي للدرس وفق المعايير السريرية المحددة.',
                    'instructions_en' => 'Record a clear clinical audio demonstration according to specified criteria.',
                    'max_score' => 100,
                ]
            );
        }
    }
}
