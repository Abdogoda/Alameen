<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use App\Services\Cms\SettingsService;
use App\Services\Seo\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_renders_all_seo_meta_tags_and_structured_data(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="title"', false);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<meta name="robots"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<link rel="alternate" hreflang="ar"', false);
        $response->assertSee('<link rel="alternate" hreflang="en"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:image"', false);
        $response->assertSee('<meta name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('EducationalOrganization', false);
    }

    public function test_dynamic_sitemap_xml_endpoint_generates_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('<loc>', false);
        $response->assertSee('courses', false);
        $response->assertSee('hreflang="ar"', false);
        $response->assertSee('hreflang="en"', false);
    }

    public function test_dynamic_robots_txt_endpoint_returns_optimized_crawler_directives(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Disallow: /admin/', false);
        $response->assertSee('Sitemap:', false);
    }

    public function test_admin_can_manage_global_and_page_seo_via_dashboard(): void
    {
        /** @var Role $role */
        $role = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'Super Administrator', 'name_ar' => 'مدير النظام الأعلى']
        );

        /** @var Admin $admin */
        $admin = Admin::factory()->create([
            'role_id' => $role->id,
        ]);

        $customTitle = 'أقوى كورس تخاطب سريري في مصر والوطن العربي';
        $customDesc = 'دورة تأهيلية مكثفة لأخصائيي النطق والعيوب الكلامية بشهادة معتمدة دولياً.';
        $customKeywords = 'تخاطب, نطق, لدغات, تدريب سريري';

        $response = $this->actingAs($admin, 'admin')->post(route('admin.settings.update'), [
            'settings' => [
                'seo_meta_title' => [
                    'ar' => $customTitle,
                    'en' => 'Leading Speech Therapy Course',
                    'group' => 'seo',
                ],
                'seo_page_home_title' => [
                    'ar' => $customTitle,
                    'en' => 'Leading Speech Therapy Course',
                    'group' => 'seo_pages',
                ],
                'seo_meta_description' => [
                    'ar' => $customDesc,
                    'en' => 'Intensive speech training accredited program.',
                    'group' => 'seo',
                ],
                'seo_page_home_description' => [
                    'ar' => $customDesc,
                    'en' => 'Intensive speech training accredited program.',
                    'group' => 'seo_pages',
                ],
                'seo_meta_keywords' => [
                    'ar' => $customKeywords,
                    'en' => 'speech, therapy, articulation',
                    'group' => 'seo',
                ],
                'seo_page_courses_title' => [
                    'ar' => 'دليل المسارات الأكاديمية للتخاطب - مركز الأمين',
                    'en' => 'Courses Directory',
                    'group' => 'seo_pages',
                ],
            ],
        ]);

        $response->assertRedirect();

        // Verify update in SettingsService
        $seoService = app(SeoService::class);
        $this->assertEquals($customTitle, $seoService->getMetaTitle());
        $this->assertEquals($customDesc, $seoService->getMetaDescription());
        $this->assertEquals($customKeywords, $seoService->getMetaKeywords());

        // Verify update on public pages
        $publicHome = $this->get('/');
        $publicHome->assertOk();
        $publicHome->assertSee($customTitle, false);
        $publicHome->assertSee($customDesc, false);

        $publicCourses = $this->get('/courses');
        $publicCourses->assertOk();
        $publicCourses->assertSee('دليل المسارات الأكاديمية للتخاطب - مركز الأمين', false);
    }
}
