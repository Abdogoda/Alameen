<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SeoDataSeeder extends Seeder
{
    /**
     * Run the database seeds for comprehensive SEO & Search Engine Optimization.
     * Seeds 100% of global, per-page, and schema settings using real project data.
     */
    public function run(): void
    {
        $appUrl = rtrim((string) config('app.url', url('/')), '/');
        $sitemapUrl = $appUrl . '/sitemap.xml';

        // -------------------------------------------------------------
        // 1. Global Platform SEO Metadata & Search Tower Settings
        // -------------------------------------------------------------
        $globalSeoSettings = [
            [
                'key' => 'seo_meta_title',
                'value_ar' => 'مركز الأمين للتدريب والتأهيل التخاطبي | كورس التخاطب الشامل مع هنا المكاوي',
                'value_en' => 'Al-Ameen Center for Speech Training & Rehabilitation | Comprehensive Speech Therapy with Hana Mekawy',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_meta_description',
                'value_ar' => 'المنصة الأكاديمية والسريرية الأولى لتدريب وتأهيل أخصائي النطق والتخاطب في الوطن العربي. دبلومات معتمدة، فيديوهات تدريبية سريرية واقعية مع الحالات، وتأهيل كامل لسوق العمل مع أ.د. هناء المكاوي.',
                'value_en' => 'The leading clinical and academic speech therapy platform in the Arab world. Accredited diplomas, authentic clinical video cases with real patients, and certified credentials with Prof. Hana Mekawy.',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_meta_keywords',
                'value_ar' => 'تخاطب, دورات تخاطب, كورس التخاطب الشامل, هنا المكاوي, مركز الأمين, أخصائي نطق, تدريب تخاطب, علاج التلعثم, لدغات الحروف, تأخر نمو اللغة, توحد, تعديل سلوك, حبسة كلامية, شهادات معتمدة تخاطب',
                'value_en' => 'speech therapy, Hana Mekawy, speech rehabilitation, Al-Ameen Center, speech pathology courses, articulation disorders, stuttering therapy, autism rehabilitation, clinical speech certification',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_author',
                'value_ar' => 'مركز الأمين - أ.د. هناء المكاوي',
                'value_en' => 'Al-Ameen Center - Prof. Hana Mekawy',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_robots',
                'value_ar' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
                'value_en' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_canonical_base_url',
                'value_ar' => $appUrl,
                'value_en' => $appUrl,
                'group' => 'seo',
            ],
            [
                'key' => 'seo_og_type',
                'value_ar' => 'website',
                'value_en' => 'website',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_og_image',
                'value_ar' => '/assets/images/alameen-images/6.jpeg',
                'value_en' => '/assets/images/alameen-images/6.jpeg',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_twitter_card',
                'value_ar' => 'summary_large_image',
                'value_en' => 'summary_large_image',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_twitter_handle',
                'value_ar' => '@alameencenter',
                'value_en' => '@alameencenter',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_google_verification',
                'value_ar' => 'google-site-verification=alameen_center_official_google_console_token',
                'value_en' => 'google-site-verification=alameen_center_official_google_console_token',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_bing_verification',
                'value_ar' => 'msvalidate.01=alameen_center_bing_webmaster_token',
                'value_en' => 'msvalidate.01=alameen_center_bing_webmaster_token',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_sitemap_enabled',
                'value_ar' => '1',
                'value_en' => '1',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_schema_org_enabled',
                'value_ar' => '1',
                'value_en' => '1',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_schema_org_name',
                'value_ar' => 'مركز الأمين للتدريب والتأهيل التخاطبي',
                'value_en' => 'Al-Ameen Center for Speech Training & Rehabilitation',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_robots_txt',
                'value_ar' => <<<TXT
# =========================================================================
# Al-Ameen Speech Center - Robots.txt Rules for Search Engine Crawlers
# =========================================================================
User-agent: *
Allow: /
Allow: /assets/
Allow: /build/
Allow: /src/

# Prevent crawlers from accessing private student LMS and admin endpoints
Disallow: /admin/
Disallow: /dashboard
Disallow: /lesson/
Disallow: /assignment/
Disallow: /grades
Disallow: /result
Disallow: /user-profile
Disallow: /storage/videos/lessons/
Disallow: /webhooks/

# Dynamic XML Sitemap Index
Sitemap: {$sitemapUrl}
TXT,
                'value_en' => <<<TXT
# =========================================================================
# Al-Ameen Speech Center - Robots.txt Rules for Search Engine Crawlers
# =========================================================================
User-agent: *
Allow: /
Allow: /assets/
Allow: /build/
Allow: /src/

# Prevent crawlers from accessing private student LMS and admin endpoints
Disallow: /admin/
Disallow: /dashboard
Disallow: /lesson/
Disallow: /assignment/
Disallow: /grades
Disallow: /result
Disallow: /user-profile
Disallow: /storage/videos/lessons/
Disallow: /webhooks/

# Dynamic XML Sitemap Index
Sitemap: {$sitemapUrl}
TXT,
                'group' => 'seo',
            ],
            [
                'key' => 'seo_head_custom_scripts',
                'value_ar' => '',
                'value_en' => '',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_body_custom_scripts',
                'value_ar' => '',
                'value_en' => '',
                'group' => 'seo',
            ],
        ];

        // -------------------------------------------------------------
        // 2. Per-Page Specialized SEO Settings (All 6 Public Pages)
        // -------------------------------------------------------------
        $perPageSeoSettings = [
            // Page 1: Home Page (/)
            [
                'key' => 'seo_page_home_title',
                'value_ar' => 'مركز الأمين | كورس التخاطب الشامل مع هنا المكاوي - دبلوم سريري معتمد',
                'value_en' => 'Al-Ameen Center | Comprehensive Speech Therapy with Hana Mekawy - Accredited Diploma',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_home_description',
                'value_ar' => 'سجل الآن في كورس التخاطب الشامل Round 5 مع هنا المكاوي في مركز الأمين. تدريب سريري واقعي مع الحالات، فيديوهات عملية، وتأهيل كامل لسوق العمل بشهادة موثقة ومعتمدة.',
                'value_en' => 'Enroll now in Round 5 of the Comprehensive Speech Therapy Course with Hana Mekawy at Al-Ameen Center. Practical clinical cases, instructional videos, and verified certification.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_home_keywords',
                'value_ar' => 'كورس هنا المكاوي, كورس التخاطب الشامل, تدريب تخاطب, مركز الأمين, دورات نطق وتخاطب, شهادة معتمدة تخاطب, تدريب سريري تخاطب',
                'value_en' => 'Hana Mekawy course, speech therapy course, speech pathology training, Al-Ameen Center, speech certifications, clinical speech training',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_home_og_image',
                'value_ar' => '/assets/images/alameen-images/6.jpeg',
                'value_en' => '/assets/images/alameen-images/6.jpeg',
                'group' => 'seo_pages',
            ],

            // Page 2: Courses Directory (/courses)
            [
                'key' => 'seo_page_courses_title',
                'value_ar' => 'دليل الدورات والمسارات التدريبية السريرية في التخاطب | مركز الأمين',
                'value_en' => 'Clinical Speech Therapy Courses & Diplomas Directory | Al-Ameen Center',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_courses_description',
                'value_ar' => 'استكشف أحدث الدورات التخصصية والمسارات الأكاديمية في أمراض التخاطب، تعديل السلوك، وعيوب النطق المصممة للارتقاء بمهارات الأخصائيين وتأهيلهم سريرياً بمركز الأمين.',
                'value_en' => 'Explore our specialized courses and academic tracks in speech disorders, behavioral modification, and articulation therapy designed for speech specialists at Al-Ameen Center.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_courses_keywords',
                'value_ar' => 'دورات تخاطب, مسارات تدريبية تخاطب, عيوب النطق, تعديل السلوك, دورات أخصائي تخاطب, دبلوم التخاطب السريري, اعتمادات التخاطب',
                'value_en' => 'speech courses, clinical speech tracks, speech therapy directory, speech disorders courses, speech diploma credentials',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_courses_og_image',
                'value_ar' => '/assets/images/alameen-images/8.jpeg',
                'value_en' => '/assets/images/alameen-images/8.jpeg',
                'group' => 'seo_pages',
            ],

            // Page 3: Student Reviews (/reviews)
            [
                'key' => 'seo_page_reviews_title',
                'value_ar' => 'آراء وتقييمات المتدربين والخرّيجين | مركز الأمين',
                'value_en' => 'Student Reviews & Graduate Testimonials | Al-Ameen Center',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_reviews_description',
                'value_ar' => 'شاهد تجارب وآراء مئات الخريجين والمتدربين في كورس التخاطب الشامل مع هنا المكاوي، وتقييماتهم الواقعية لجودة المحتوى والتطبيق السريري وقصص نجاحهم المهنية.',
                'value_en' => 'Read authentic reviews, ratings, and feedback from hundreds of graduates in the Comprehensive Speech Therapy course with Hana Mekawy at Al-Ameen Center.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_reviews_keywords',
                'value_ar' => 'آراء متدربي مركز الأمين, تقييمات كورس هنا المكاوي, تجارب خريجي التخاطب, شهادات الطلاب, قصص نجاح خريجي التخاطب',
                'value_en' => 'student testimonials, Al-Ameen center reviews, Hana Mekawy course ratings, speech graduate success stories',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_reviews_og_image',
                'value_ar' => '/assets/images/alameen-images/14.jpeg',
                'value_en' => '/assets/images/alameen-images/14.jpeg',
                'group' => 'seo_pages',
            ],

            // Page 4: Contact Us (/contact)
            [
                'key' => 'seo_page_contact_title',
                'value_ar' => 'تواصل معنا والمقر الرئيسي | مركز الأمين للتخاطب',
                'value_en' => 'Contact Us & Headquarters | Al-Ameen Speech Center',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_contact_description',
                'value_ar' => 'تواصل مع فريق الدعم الأكاديمي والاستشاري بمركز الأمين للتخاطب، أو تفضل بزيارة مقرنا الرئيسي. متاحون يومياً للرد على استفساراتكم عبر الهاتف 01005123784 وتطبيق واتساب.',
                'value_en' => 'Contact our academic advising and support team at Al-Ameen Center, or visit our headquarters. We are ready to help via phone (+201005123784) and WhatsApp.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_contact_keywords',
                'value_ar' => 'رقم مركز الأمين للتخاطب, عنوان مركز الأمين, واتساب مركز الأمين, استفسارات دورات التخاطب, 01005123784',
                'value_en' => 'Al-Ameen contact number, speech center address, WhatsApp Al-Ameen, course inquiries, speech advisory contact',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_contact_og_image',
                'value_ar' => '/assets/images/alameen-images/2.jpeg',
                'value_en' => '/assets/images/alameen-images/2.jpeg',
                'group' => 'seo_pages',
            ],

            // Page 5: Clinical Audit Report (/audit-report)
            [
                'key' => 'seo_page_audit_title',
                'value_ar' => 'تقرير التقييم السريري ومعايير الجودة | مركز الأمين',
                'value_en' => 'Clinical Quality & Audit Report | Al-Ameen Center',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_audit_description',
                'value_ar' => 'تقرير تقييم الأداء السريري المعتمد وفق أعلى المعايير الدولية لتأهيل أخصائيي النطق والتخاطب بمركز الأمين وضمان الجودة الأكاديمية والسريرية.',
                'value_en' => 'Certified clinical audit and performance evaluation report adhering to international speech pathology training standards at Al-Ameen Center.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_audit_keywords',
                'value_ar' => 'تقرير الجودة السريرية, تقييم تدريب التخاطب, معايير مركز الأمين, شهادات الجودة, تدقيق التخاطب الأكاديمي',
                'value_en' => 'clinical audit report, speech training quality standards, accredited speech evaluation, academic clinical audit',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_audit_og_image',
                'value_ar' => '/assets/images/alameen-images/10.jpeg',
                'value_en' => '/assets/images/alameen-images/10.jpeg',
                'group' => 'seo_pages',
            ],

            // Page 6: Certificate Verification (/verify-certificate)
            [
                'key' => 'seo_page_verify_certificate_title',
                'value_ar' => 'فحص والتحقق من صحة الشهادات الصادرة | مركز الأمين',
                'value_en' => 'Verify Issued Certificates & Credentials | Al-Ameen Center',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_verify_certificate_description',
                'value_ar' => 'البوابة الرسمية للتحقق الفوري من صحة وموثوقية شهادات المتدربين الصادرة من مركز الأمين باستخدام الرمز المرجعي أو كود QR للتأكد من الاعتماد الرسمي.',
                'value_en' => 'Official portal for instantly verifying credentials and certificates issued by Al-Ameen Center via serial reference code or QR scan.',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_verify_certificate_keywords',
                'value_ar' => 'التحقق من الشهادات, فحص كود الشهادة, شهادات مركز الأمين المعتمدة, استعلام عن شهادة تخاطب, فحص الباركود, توثيق شهادة هنا المكاوي',
                'value_en' => 'certificate verification, verify credential, Al-Ameen certificate checker, QR verification, speech diploma authenticity',
                'group' => 'seo_pages',
            ],
            [
                'key' => 'seo_page_verify_certificate_og_image',
                'value_ar' => '/assets/images/alameen-images/12.jpeg',
                'value_en' => '/assets/images/alameen-images/12.jpeg',
                'group' => 'seo_pages',
            ],
        ];

        // -------------------------------------------------------------
        // 3. Persist All Settings into `settings` Database Table
        // -------------------------------------------------------------
        $allSeoSettings = array_merge($globalSeoSettings, $perPageSeoSettings);

        foreach ($allSeoSettings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // -------------------------------------------------------------
        // 4. Update Course SEO Descriptions in Database
        // -------------------------------------------------------------
        $primaryCourse = Course::query()->first();
        if ($primaryCourse) {
            $primaryCourse->update([
                'title_ar' => 'كورس التخاطب الشامل مع هنا المكاوي (الدفعة الخامسة)',
                'title_en' => 'Comprehensive Speech Therapy Diploma with Hana Mekawy (Round 5)',
                'description_ar' => 'الدبلوم السريري الأقوى لتأهيل أخصائي النطق والتخاطب في الوطن العربي. يتضمن 20 درساً سريرياً مكثفاً يغطي أمراض النطق، اللدغات، التلعثم، الحبسة الكلامية، والتأهيل العملي مع الحالات الحقيقية.',
                'description_en' => 'The premier clinical diploma for speech-language pathologists. Includes 20 intensive clinical lectures covering articulation disorders, stuttering, aphasia, and hands-on therapy cases.',
            ]);
        }

        // -------------------------------------------------------------
        // 5. Synchronize Physical public/robots.txt with Live Sitemap
        // -------------------------------------------------------------
        try {
            $robotsPath = public_path('robots.txt');
            $robotsContent = <<<TXT
# =========================================================================
# Al-Ameen Speech Center - Robots.txt Rules for Search Engine Crawlers
# =========================================================================
User-agent: *
Allow: /
Allow: /assets/
Allow: /build/
Allow: /src/

# Prevent crawlers from accessing private student LMS and admin endpoints
Disallow: /admin/
Disallow: /dashboard
Disallow: /lesson/
Disallow: /assignment/
Disallow: /grades
Disallow: /result
Disallow: /user-profile
Disallow: /storage/videos/lessons/
Disallow: /webhooks/

# Dynamic XML Sitemap Index
Sitemap: {$sitemapUrl}
TXT;
            File::put($robotsPath, $robotsContent);
        } catch (\Throwable $e) {
            // Log or ignore file permissions in certain locked hosting environments
        }
    }
}

