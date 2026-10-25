<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_switch_locale_via_get_route(): void
    {
        $response = $this->get(route('locale.switch', 'ar'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'ar');
        $response->assertCookie('locale', 'ar');
    }

    public function test_switching_locale_updates_authenticated_user_preference(): void
    {
        $user = User::factory()->create(['locale' => 'en']);

        $this->actingAs($user)
            ->post(route('locale.switch', 'ar'))
            ->assertRedirect();

        $this->assertEquals('ar', $user->fresh()->locale);
    }

    public function test_rejects_unsupported_locale(): void
    {
        $response = $this->get(route('locale.switch', 'invalid_locale'));

        $response->assertRedirect();
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_api_locale_is_not_changed_via_url_route_or_query_param(): void
    {
        // 1. The old /api/v1/locale/{locale} route does not exist
        $this->getJson('/api/v1/locale/ar')->assertNotFound();

        // 2. Query param ?lang=ar or ?locale=ar is strictly ignored in API
        $response = $this->postJson('/api/v1/auth/login?lang=ar', [
            'email' => 'nonexistent@example.com',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertStatus(422);
        $response->assertHeader('Content-Language', 'en');
        $this->assertEquals('These credentials do not match our records.', $response->json('message'));
    }

    public function test_api_accept_language_header_sets_locale_and_translates_message(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('/api/v1/auth/login', [
                'email' => 'nonexistent@example.com',
                'password' => 'WrongPassword123!',
            ]);

        $response->assertStatus(422);
        $response->assertHeader('Content-Language', 'ar');
        $this->assertEquals('بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.', $response->json('message'));
    }

    public function test_web_page_renders_with_arabic_dir_rtl_when_locale_is_ar(): void
    {
        $response = $this->withSession(['locale' => 'ar'])->get(route('login'));

        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('lang="ar"', false);
        $response->assertSee('تسجيل الدخول');
    }

    public function test_web_page_renders_with_english_dir_ltr_when_locale_is_en(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get(route('login'));

        $response->assertOk();
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('lang="en"', false);
        $response->assertSee('Log in');
    }

    public function test_translations_are_loaded_from_php_files_without_json(): void
    {
        $this->assertFileDoesNotExist(base_path('lang/ar.json'));
        $this->assertFileDoesNotExist(base_path('lang/en.json'));

        app()->setLocale('ar');

        $this->assertEquals('تسجيل الدخول', __('Log in'));
        $this->assertEquals('لوحة التحكم', __('Dashboard'));
        $this->assertEquals('بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.', __('auth.failed'));
        $this->assertEquals('تمت العملية بنجاح.', __('messages.Operation successful.'));
    }
}
