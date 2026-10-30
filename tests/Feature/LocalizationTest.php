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
        $this->assertEquals('ar', app()->getLocale());
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
