<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Video;
use App\Notifications\EmailVerificationOtpNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StudentAndPublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_openapi_specification_endpoint_and_apidog_sync_command_work(): void
    {
        // OpenAPI 3.0.3 (Apidog Ready) Spec
        $this->getJson('/api/v1/openapi.json')
            ->assertStatus(200)
            ->assertJsonPath('openapi', '3.0.3')
            ->assertJsonStructure([
                'openapi',
                'info' => ['title', 'version'],
                'paths',
                'components' => ['securitySchemes', 'schemas'],
            ]);

        // Interactive OpenAPI Docs Page
        $this->get('/api/v1/docs')
            ->assertStatus(200);

        // Automated Apidog OpenAPI Sync Command (php artisan apidog:sync)
        Http::fake([
            'https://api.apidog.com/v1/projects/*/import-openapi*' => Http::response([
                'data' => [
                    'counters' => [
                        'endpointCreated' => 66,
                        'endpointUpdated' => 0,
                        'schemaCreated' => 14,
                    ],
                ],
            ], 200),
        ]);

        $this->artisan('apidog:sync', [
            '--project-id' => '123456',
            '--token' => 'aps-test-token',
        ])->assertExitCode(0);
    }

    public function test_public_endpoints_return_structured_json_responses(): void
    {
        $course = Course::create([
            'title_ar' => 'دورة زراعة الأسنان المتقدمة',
            'title_en' => 'Advanced Dental Implantology',
            'slug' => 'advanced-dental-implantology',
            'description_ar' => 'وصف الدورة التدريبية السريرية',
            'description_en' => 'Clinical course description',
            'price' => 250.00,
            'access_duration_days' => 365,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Bootstrap
        $this->getJson('/api/v1/public/bootstrap')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'branding',
                    'currency',
                    'features',
                    'localization',
                ],
            ]);

        // Home
        $this->getJson('/api/v1/public/home')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'hero',
                    'featured_courses',
                    'seo',
                ],
            ]);

        // Courses Catalog
        $this->getJson('/api/v1/public/courses')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // Single Course Detail
        $this->getJson("/api/v1/public/courses/{$course->slug}")
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.course.slug', $course->slug);

        // Reviews
        $this->getJson('/api/v1/public/reviews')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // Contact Info
        $this->getJson('/api/v1/public/contact')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // SEO & Locale Switch
        $this->getJson('/api/v1/public/seo/home')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->postJson('/api/v1/public/locale', ['locale' => 'en'])
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.locale', 'en');
    }

    public function test_student_registration_and_sanctum_authentication_flow(): void
    {
        // 1. Register a new student (defaults to pending approval)
        $registerResponse = $this->postJson('/api/v1/auth/register', [
            'name' => 'Dr. Khaled Alameen',
            'email' => 'khaled@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '+966512345678',
            'country' => 'SA',
            'city' => 'Riyadh',
            'locale' => 'ar',
        ]);

        $registerResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('data.user.email', 'khaled@example.com');

        // 2. Pending student cannot log in before admin approval
        $this->postJson('/api/v1/auth/login', [
            'email' => 'khaled@example.com',
            'password' => 'SecurePass123!',
        ])->assertStatus(422);

        // 3. Approve student and log in to receive a Sanctum Bearer token
        $user = User::where('email', 'khaled@example.com')->firstOrFail();
        $user->forceFill([
            'status' => 'active',
            'email_verified_at' => now(),
        ])->save();

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'khaled@example.com',
            'password' => 'SecurePass123!',
            'device_name' => 'phpunit-test',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'authenticated')
            ->assertJsonStructure([
                'success',
                'status',
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'status'],
                ],
            ]);

        $token = $loginResponse->json('data.token');
        $this->assertNotEmpty($token);

        // 4. Fetch current authenticated student profile (/api/v1/auth/me)
        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'khaled@example.com');

        // 5. Logout revokes token
        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_email_verification_otp_api_flow(): void
    {
        Notification::fake();

        $student = User::factory()->create([
            'email' => 'student-verify@example.com',
            'status' => 'active',
            'email_verified_at' => null,
        ]);

        $token = $student->createToken('test-device')->plainTextToken;

        // 1. Request OTP via API
        $this->withToken($token)
            ->postJson('/api/v1/auth/email/verification-notification')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('already_verified', false);

        $sentOtp = null;
        Notification::assertSentTo($student, EmailVerificationOtpNotification::class, function ($notification) use (&$sentOtp) {
            $sentOtp = $notification->otp;

            return true;
        });

        $this->assertNotNull($sentOtp);

        // 2. Submit invalid OTP
        $this->withToken($token)
            ->postJson('/api/v1/auth/email/verify-otp', ['otp' => '000000'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['otp']);

        $this->assertFalse($student->fresh()->hasVerifiedEmail());

        // 3. Submit valid OTP
        $this->withToken($token)
            ->postJson('/api/v1/auth/email/verify-otp', ['otp' => $sentOtp])
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertTrue($student->fresh()->hasVerifiedEmail());
    }

    public function test_authenticated_student_workspace_endpoints_share_services_and_return_json(): void
    {
        $student = User::factory()->create([
            'name' => 'Dr. Nour Student',
            'email' => 'nour@example.com',
            'password' => Hash::make('Password123!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $course = Course::create([
            'title_ar' => 'تجميل الأسنان السريري',
            'title_en' => 'Clinical Esthetic Dentistry',
            'slug' => 'clinical-esthetic-dentistry',
            'description_ar' => 'دورة متكاملة في تجميل الأسنان',
            'description_en' => 'Comprehensive course in esthetic dentistry',
            'price' => 300.00,
            'access_duration_days' => 180,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $level = Level::create([
            'course_id' => $course->id,
            'title_ar' => 'المستوى الأول: الأساسيات',
            'title_en' => 'Level 1: Fundamentals',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $video = Video::create([
            'course_id' => $course->id,
            'level_id' => $level->id,
            'slug' => 'intro-to-veneers',
            'title_ar' => 'مقدمة في العدسات التجميلية',
            'title_en' => 'Introduction to Veneers',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration_seconds' => 600,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
            'is_free_preview' => true,
            'is_published' => true,
        ]);

        // Enroll student in course
        $student->courses()->attach($course->id, [
            'status' => 'active',
            'access_starts_at' => now(),
            'access_expires_at' => now()->addDays(180),
        ]);

        $token = $student->createToken('test-suite')->plainTextToken;

        // 1. Student Dashboard
        $this->withToken($token)
            ->getJson('/api/v1/student/dashboard')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'stats',
                    'enrolled_courses',
                ],
            ]);

        // 2. Student Courses List & Single Course Workspace
        $this->withToken($token)
            ->getJson('/api/v1/student/courses')
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'enrolled_courses',
                    'all_courses',
                ],
            ]);

        $this->withToken($token)
            ->getJson("/api/v1/student/courses/{$course->slug}")
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.course.slug', $course->slug);

        // 3. Lesson Workspace & Heartbeat Progress
        $this->withToken($token)
            ->getJson("/api/v1/student/courses/{$course->slug}/lessons/{$video->slug}")
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.current_video.slug', $video->slug);

        $this->withToken($token)
            ->postJson("/api/v1/student/courses/{$course->slug}/lessons/{$video->slug}/progress", [
                'watched_seconds' => 550,
            ])
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_completed', true);

        // 4. Assignments & Grades
        $this->withToken($token)
            ->getJson('/api/v1/student/assignments')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->withToken($token)
            ->getJson('/api/v1/student/grades')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // 5. Certificates
        $this->withToken($token)
            ->getJson('/api/v1/student/certificates')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // 6. Notifications
        $this->withToken($token)
            ->getJson('/api/v1/student/notifications')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->withToken($token)
            ->postJson('/api/v1/student/notifications/read-all')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // 7. Support Tickets (List, Create, Show, Close)
        $ticketResponse = $this->withToken($token)
            ->postJson('/api/v1/student/support-tickets', [
                'subject' => 'مشكلة في تشغيل الفيديو السريري',
                'category' => 'technical',
                'priority' => 'medium',
                'message' => 'يرجى المساعدة في تشغيل الفيديو بجودة عالية.',
            ]);

        $ticketResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $ticketId = $ticketResponse->json('data.ticket.id');

        $this->withToken($token)
            ->getJson("/api/v1/student/support-tickets/{$ticketId}")
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->withToken($token)
            ->postJson("/api/v1/student/support-tickets/{$ticketId}/close")
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        // 8. Student Settings & Profile Update
        $this->withToken($token)
            ->getJson('/api/v1/student/settings/profile')
            ->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->withToken($token)
            ->postJson('/api/v1/student/settings/profile', [
                'name' => 'Dr. Nour Updated',
                'email' => 'nour@example.com',
                'phone' => '+966599999999',
                'country' => 'SA',
                'city' => 'Jeddah',
                'locale' => 'ar',
            ])
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.name', 'Dr. Nour Updated');
    }
}
