<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\Cms\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsAndControlTowerTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
    }

    public function test_admin_can_view_settings_control_tower_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('إعدادات المنصة ولوحة التحكم والموقع');
        $response->assertSee('الهوية والعلامة التجارية');
        $response->assertSee('التواصل والدعم الفني');
        $response->assertSee('الواجهة ومحتوى الموقع');
        $response->assertSee('الأسئلة الشائعة (FAQ)');
        $response->assertSee('السياسات الأكاديمية والمحاضرات');
        $response->assertSee('قنوات الدفع والتحويل');
        $response->assertSee('مدير القوائم والتنقل');
    }

    public function test_admin_can_update_branding_and_contact_settings(): void
    {
        $payload = [
            'settings' => [
                'site_name' => [
                    'ar' => 'منصة الأمين لاختبار التخاطب',
                    'en' => 'Al-Ameen Speech Testing Platform',
                    'group' => 'branding',
                ],
                'contact_email' => [
                    'ar' => 'admin@alameen-speech.com',
                    'group' => 'contact',
                ],
                'contact_phone' => [
                    'ar' => '+201111111111',
                    'group' => 'contact',
                ],
                'social_whatsapp' => [
                    'ar' => 'https://wa.me/201111111111',
                    'group' => 'social',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'site_name',
            'value_ar' => 'منصة الأمين لاختبار التخاطب',
            'value_en' => 'Al-Ameen Speech Testing Platform',
        ]);

        $settingsService = app(SettingsService::class);
        $this->assertSame('منصة الأمين لاختبار التخاطب', $settingsService->get('site_name'));
        $this->assertSame('admin@alameen-speech.com', $settingsService->get('contact_email'));
    }

    public function test_admin_can_update_academic_rules_and_payment_channels(): void
    {
        $payload = [
            'settings' => [
                'certificate_min_percentage' => [
                    'ar' => '90',
                    'group' => 'academic',
                ],
                'sequential_video_lock' => [
                    'ar' => '0',
                    'group' => 'academic',
                ],
                'dynamic_video_watermark' => [
                    'ar' => '1',
                    'group' => 'academic',
                ],
                'vodafone_cash_number' => [
                    'ar' => '01099999999',
                    'group' => 'payment_vodafone',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'certificate_min_percentage',
            'value_ar' => '90',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'sequential_video_lock',
            'value_ar' => '0',
        ]);
    }

    public function test_admin_can_update_hero_content_block_with_stats(): void
    {
        $payload = [
            'title_ar' => 'أكاديمية الأمين للتأهيل الإكلينيكي والنطق',
            'title_en' => 'Al-Ameen Clinical Speech Academy',
            'subtitle_ar' => 'تدريب عملي سريري متكامل مع كبار الأطباء',
            'subtitle_en' => 'Hands-on clinical training with expert pathologists',
            'metadata' => [
                'cta_primary_ar' => 'ابدأ التدريب الآن',
                'cta_primary_url' => '/courses',
                'students_count' => '3500+',
                'courses_count' => '50+',
                'supervisors_count' => '15+',
                'success_rate' => '99%',
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.content.update', 'hero'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('content_blocks', [
            'section_key' => 'hero',
            'title_ar' => 'أكاديمية الأمين للتأهيل الإكلينيكي والنطق',
        ]);

        $block = ContentBlock::where('section_key', 'hero')->first();
        $this->assertNotNull($block);
        $this->assertSame('3500+', $block->metadata['students_count']);
    }

    public function test_admin_can_update_faq_items_dynamically(): void
    {
        $payload = [
            'title_ar' => 'الأسئلة الشائعة والمكررة',
            'subtitle_ar' => 'إجابات وافية حول كافة استفسارات المتدربين',
            'metadata' => [
                'items' => [
                    [
                        'question_ar' => 'هل الشهادة معتمدة دولياً ومحلياً؟',
                        'question_en' => 'Is the certificate internationally accredited?',
                        'answer_ar' => 'نعم، الشهادة معتمدة وتحمل كود تحقق فريد ورمز QR فوري.',
                        'answer_en' => 'Yes, verifiable with unique code and QR code.',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.content.update', 'faq'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $block = ContentBlock::where('section_key', 'faq')->first();
        $this->assertNotNull($block);
        $this->assertCount(1, $block->metadata['items']);
        $this->assertSame('هل الشهادة معتمدة دولياً ومحلياً؟', $block->metadata['items'][0]['question_ar']);
    }

    public function test_admin_can_manage_menu_items_toggle_and_delete(): void
    {
        $menu = Menu::create([
            'name' => 'Header Nav',
            'location' => 'header',
        ]);

        // 1. Add item
        $addResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.menus.item', $menu->id), [
                'title_ar' => 'قسم الدورات الحصري',
                'title_en' => 'Exclusive Courses',
                'url' => '/exclusive-courses',
                'target' => '_self',
                'sort_order' => 1,
            ]);

        $addResponse->assertRedirect();
        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'title_ar' => 'قسم الدورات الحصري',
            'url' => '/exclusive-courses',
            'is_active' => true,
        ]);

        /** @var MenuItem $item */
        $item = MenuItem::where('url', '/exclusive-courses')->first();

        // 2. Toggle item
        $toggleResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settings.menus.item.toggle', $item->id));

        $toggleResponse->assertRedirect();
        $this->assertFalse($item->fresh()->is_active);

        // 3. Delete item
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.settings.menus.item.delete', $item->id));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('menu_items', [
            'id' => $item->id,
        ]);
    }
}
