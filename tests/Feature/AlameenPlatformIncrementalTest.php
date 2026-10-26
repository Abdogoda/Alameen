<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\ClinicalTask;
use App\Models\Course;
use App\Models\PaymentReceipt;
use App\Models\Role;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Video;
use App\Services\Auth\StudentProfileService;
use App\Services\Certificate\CertificateService;
use App\Services\Clinical\ClinicalTaskService;
use App\Services\Cms\MenuService;
use App\Services\Cms\SettingsService;
use App\Services\Course\CourseEnrollmentService;
use App\Services\Payment\PaymentReceiptService;
use App\Services\Video\BunnyStreamService;
use App\Services\Video\VideoProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AlameenPlatformIncrementalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Test student profile extension: phone, country, city, address, and status.
     */
    public function test_student_profile_fields_and_status(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $profileService = app(StudentProfileService::class);
        $profileService->updateProfile($user, [
            'phone' => '+201099887766',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'address' => 'Nasr City, Block 12',
            'locale' => 'ar',
        ]);

        $this->assertEquals('+201099887766', $user->fresh()->phone);
        $this->assertEquals('Egypt', $user->fresh()->country);
        $this->assertEquals('Cairo', $user->fresh()->city);
        $this->assertTrue($user->fresh()->isActive());

        $profileService->suspend($user);
        $this->assertTrue($user->fresh()->isSuspended());
        $this->assertFalse($user->fresh()->isActive());
    }

    /**
     * 2. Test central permissions config and dynamic role checking.
     */
    public function test_permissions_configuration_and_dynamic_roles(): void
    {
        $configPermissions = config('permissions.permissions');
        $this->assertIsArray($configPermissions);
        $this->assertArrayHasKey('courses_view', $configPermissions);
        $this->assertArrayHasKey('receipts_approve', $configPermissions);

        $role = Role::create([
            'name' => 'مسؤول المالية',
            'slug' => 'test_finance',
            'permissions' => ['receipts.view', 'receipts.approve'],
        ]);

        $admin = Admin::create([
            'name' => 'Finance Admin',
            'email' => 'fin@test.com',
            'password' => 'secret',
            'role_id' => $role->id,
        ]);

        $this->assertTrue($admin->hasPermission('receipts.approve'));
        $this->assertTrue($admin->hasPermission('receipts.view'));
        $this->assertFalse($admin->hasPermission('courses.manage'));
    }

    /**
     * 3. Test sequential video locking.
     * Video (N+1) cannot be accessed until Video (N) reaches min_completion_percentage.
     */
    public function test_sequential_video_locking(): void
    {
        $user = User::factory()->create();
        $course = Course::create([
            'title_ar' => 'دورة تأهيل التلعثم الإكلينيكية',
            'slug' => 'stuttering-rehab',
            'price' => 500,
            'access_duration_days' => 90,
        ]);

        // Enroll user in the course
        $enrollmentService = app(CourseEnrollmentService::class);
        $enrollmentService->enroll($user, $course);

        // Create 2 sequential videos
        $video1 = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'المحاضرة 1: تشخيص اضطرابات الطلاقة',
            'slug' => 'lec-1',
            'duration_seconds' => 100,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);

        $video2 = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'المحاضرة 2: التدخل العلاجي للتلعثم',
            'slug' => 'lec-2',
            'duration_seconds' => 100,
            'min_completion_percentage' => 80,
            'sort_order' => 2,
        ]);

        $progressService = app(VideoProgressService::class);

        // Video 1 is first -> always unlocked
        $this->assertTrue($progressService->canAccessVideo($user, $video1));

        // Video 2 is locked before watching video 1
        $this->assertFalse($progressService->canAccessVideo($user, $video2));

        // Watch 50% of video 1 (below 80% threshold)
        $progressService->recordProgress($user, $video1, 50);
        $this->assertFalse($progressService->canAccessVideo($user, $video2));

        // Watch 85% of video 1 (reaches min_completion_percentage)
        $progressService->recordProgress($user, $video1, 85);
        $this->assertTrue($progressService->canAccessVideo($user, $video2), 'Video 2 must unlock after Video 1 reaches 80%');
    }

    /**
     * 4. Test time-bound course access and expiration.
     */
    public function test_time_bound_course_access_and_expiration(): void
    {
        $user = User::factory()->create();
        $course = Course::create([
            'title_ar' => 'دورة الاضطرابات النمائية',
            'slug' => 'developmental-speech',
            'price' => 300,
            'access_duration_days' => 30,
        ]);

        $enrollmentService = app(CourseEnrollmentService::class);
        $enrollmentService->enroll($user, $course, durationDays: 30);

        $this->assertTrue($enrollmentService->hasActiveAccess($user, $course));
        $this->assertGreaterThan(0, $enrollmentService->getRemainingDays($user, $course));

        // Fast-forward 31 days into future
        Carbon::setTestNow(Carbon::now()->addDays(31));

        $this->assertFalse($enrollmentService->hasActiveAccess($user, $course), 'Access must expire after 30 days');
        $this->assertEquals(0, $enrollmentService->getRemainingDays($user, $course));

        Carbon::setTestNow(); // reset time
    }

    /**
     * 5. Test manual payment receipt submission and admin auto-grant workflow.
     */
    public function test_payment_receipt_auto_grant_access(): void
    {
        $user = User::factory()->create();
        $admin = Admin::create([
            'name' => 'Auditor',
            'email' => 'auditor@alameen.com',
            'password' => 'secret',
        ]);

        $course = Course::create([
            'title_ar' => 'دورة الحبسة الكلامية (الأفازيا)',
            'slug' => 'aphasia-course',
            'price' => 450,
            'access_duration_days' => 180,
        ]);

        $receiptService = app(PaymentReceiptService::class);
        $enrollmentService = app(CourseEnrollmentService::class);

        // User does not have access initially
        $this->assertFalse($enrollmentService->hasActiveAccess($user, $course));

        // Submit manual Vodafone Cash receipt
        $receipt = $receiptService->submitReceipt($user, $course, [
            'payment_method' => 'vodafone_cash',
            'amount' => 450.00,
            'sender_account' => '01011223344',
            'transaction_reference' => 'TXN-998877',
            'receipt_image' => 'receipts/mock-receipt.jpg',
        ]);

        $this->assertTrue($receipt->isPending());

        // Admin approves receipt -> AUTO-GRANT
        $receiptService->approveReceipt($receipt, $admin);

        $this->assertTrue($receipt->fresh()->isApproved());
        $this->assertEquals($admin->id, $receipt->fresh()->reviewed_by);
        $this->assertTrue($enrollmentService->hasActiveAccess($user, $course), 'Student must be auto-granted access upon approval');
    }

    /**
     * 6. Test certificate generation upon >= 95% completion.
     */
    public function test_certificate_issuance_at_95_percent_threshold(): void
    {
        $user = User::factory()->create();
        $course = Course::create([
            'title_ar' => 'دورة التقييم السريري الشامل',
            'slug' => 'clinical-eval',
            'access_duration_days' => 365,
        ]);

        $enrollmentService = app(CourseEnrollmentService::class);
        $enrollmentService->enroll($user, $course);

        // Create single lecture
        $video = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'المحاضرة الشاملة',
            'slug' => 'full-lec',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);

        $progressService = app(VideoProgressService::class);
        $certService = app(CertificateService::class);

        // Progress = 80% (not eligible yet for cert)
        $progressService->recordProgress($user, $video, 800);
        $this->assertFalse($certService->isEligible($user, $course));

        // Complete video (100% progress >= 95%)
        $progressService->recordProgress($user, $video, 1000);
        $this->assertTrue($certService->isEligible($user, $course));

        // Issue certificate
        $cert = $certService->issueCertificate($user, $course);
        $this->assertNotNull($cert);
        $this->assertStringStartsWith('ALM-', $cert->certificate_code);
        $this->assertNotNull($cert->verification_url);
        $this->assertNotNull($cert->qr_code_svg);

        // Public verification lookup
        $verified = $certService->verify($cert->certificate_code);
        $this->assertNotNull($verified);
        $this->assertEquals($user->id, $verified->user_id);
    }

    /**
     * 7. Test dynamic CMS settings & Menu manager with bilingual support.
     */
    public function test_dynamic_cms_settings_and_menu_manager(): void
    {
        $settingsService = app(SettingsService::class);
        $settingsService->set('vodafone_cash_number', '01011112222', '01011112222', 'payment_vodafone');
        $settingsService->set('hero_badge', 'خصم خاص للمتدربين', 'Special Interns Discount', 'branding');

        app()->setLocale('ar');
        $this->assertEquals('خصم خاص للمتدربين', $settingsService->get('hero_badge'));

        app()->setLocale('en');
        $this->assertEquals('Special Interns Discount', $settingsService->get('hero_badge'));

        // Menu manager test
        $menuService = app(MenuService::class);
        $headerItems = $menuService->getMenu('header');
        $this->assertNotNull($headerItems);
    }

    /**
     * 8. Test BunnyStreamService payload and signed URL structure.
     */
    public function test_bunny_stream_service_watermark_payload(): void
    {
        $user = User::factory()->create([
            'name' => 'د. أحمد السعدني',
            'phone' => '01012349999',
        ]);

        $video = Video::create([
            'course_id' => Course::create(['title_ar' => 'Test', 'slug' => 'test'])->id,
            'title_ar' => 'فيديو تجريبي',
            'slug' => 'demo',
            'bunny_video_id' => '12345678-abcd',
            'duration_seconds' => 120,
        ]);

        $bunnyService = app(BunnyStreamService::class);
        $embedUrl = $bunnyService->getIframeEmbedUrl($video);
        $this->assertStringContainsString('12345678-abcd', $embedUrl);

        $watermark = $bunnyService->getWatermarkPayload($user, '192.168.1.100');
        $this->assertEquals('د. أحمد السعدني', $watermark['name']);
        $this->assertEquals('01012349999', $watermark['phone']);
        $this->assertEquals('192.168.1.100', $watermark['ip']);
    }

    /**
     * 9. Test Admin receipts controller approve & reject workflow.
     */
    public function test_admin_receipts_controller_approve_and_reject(): void
    {
        $admin = Admin::create([
            'name' => 'Finance Chief',
            'email' => 'fc@test.com',
            'password' => 'secret',
        ]);

        $user = User::factory()->create();
        $course = Course::create(['title_ar' => 'Course 1', 'slug' => 'course-1', 'price' => 200]);

        $receipt = PaymentReceipt::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_method' => 'vodafone_cash',
            'amount' => 200,
            'receipt_image_path' => 'receipts/test.png',
            'status' => 'pending',
        ]);

        // Access index as admin
        $response = $this->actingAs($admin, 'admin')->get(route('admin.receipts.index'));
        $response->assertStatus(200);

        // Approve
        $approveResponse = $this->actingAs($admin, 'admin')->post(route('admin.receipts.approve', $receipt->id));
        $approveResponse->assertRedirect();
        $this->assertTrue($receipt->fresh()->isApproved());
        $this->assertTrue(app(CourseEnrollmentService::class)->hasActiveAccess($user, $course));

        // Reject another receipt
        $receipt2 = PaymentReceipt::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_method' => 'instapay',
            'amount' => 100,
            'receipt_image_path' => 'receipts/test2.png',
            'status' => 'pending',
        ]);

        $rejectResponse = $this->actingAs($admin, 'admin')->post(route('admin.receipts.reject', $receipt2->id), [
            'rejection_reason' => 'مبلغ غير مكتمل',
        ]);
        $rejectResponse->assertRedirect();
        $this->assertTrue($receipt2->fresh()->isRejected());
        $this->assertEquals('مبلغ غير مكتمل', $receipt2->fresh()->rejection_reason);
    }

    /**
     * 10. Test public certificate verification route.
     */
    public function test_public_certificate_verification_page(): void
    {
        $user = User::factory()->create(['name' => 'طالب متميز']);
        $course = Course::create(['title_ar' => 'دورة النطق', 'slug' => 'speech-cert-test']);

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_code' => 'ALM-2026-TESTCODE',
            'verification_url' => url('/verify-certificate/ALM-2026-TESTCODE'),
            'qr_code_svg' => '<svg>QR</svg>',
            'completion_percentage' => 98.5,
        ]);

        $response = $this->get(route('certificates.verify', 'ALM-2026-TESTCODE'));
        $response->assertStatus(200);
        $response->assertSee('طالب متميز');
        $response->assertSee('ALM-2026-TESTCODE');
    }

    /**
     * 11. Test clinical audio voice exercise submission and supervisor grading.
     */
    public function test_clinical_voice_task_submission_and_grading(): void
    {
        $user = User::factory()->create();
        $admin = Admin::create([
            'name' => 'Supervisor Voice',
            'email' => 'voice.sup@test.com',
            'password' => 'secret',
        ]);

        $course = Course::create(['title_ar' => 'علاج اللدغات', 'slug' => 'lisping-rehab']);
        $task = ClinicalTask::create([
            'course_id' => $course->id,
            'title_ar' => 'تسجيل صوتي لحرف الراء (R)',
            'max_score' => 100,
        ]);

        $taskService = app(ClinicalTaskService::class);

        // Submit audio recording
        $submission = $taskService->submitRecording($user, $task, 'clinical_audio/rec-01.wav', 'تسجيل بعد ٣ جلسات تدريب');
        $this->assertTrue($submission->isPending());

        // Grade submission
        $taskService->gradeSubmission($submission, $admin, 92, 'مخارج الحروف دقيقة وممتازة');
        $this->assertTrue($submission->fresh()->isGraded());
        $this->assertEquals(92, $submission->fresh()->score);
        $this->assertEquals('مخارج الحروف دقيقة وممتازة', $submission->fresh()->clinical_feedback);
    }

    /**
     * 12. Test student manager toggle active/suspended status.
     */
    public function test_student_manager_toggle_status(): void
    {
        $admin = Admin::create(['name' => 'Manager', 'email' => 'mgr@test.com', 'password' => 'secret']);
        $student = User::factory()->create(['status' => 'active']);

        // Toggle to suspended
        $this->actingAs($admin, 'admin')->post(route('admin.students.toggle', $student->id));
        $this->assertTrue($student->fresh()->isSuspended());

        // Toggle back to active
        $this->actingAs($admin, 'admin')->post(route('admin.students.toggle', $student->id));
        $this->assertTrue($student->fresh()->isActive());
    }

    /**
     * 13. Test courses:expire-subscriptions console command.
     */
    public function test_courses_expire_subscriptions_command(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['title_ar' => 'Test Course', 'slug' => 'test-exp-cmd']);

        // Enroll with past expiration date
        DB::table('course_user')->insert([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'access_starts_at' => Carbon::now()->subDays(40),
            'access_expires_at' => Carbon::now()->subDays(5),
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->artisan('courses:expire-subscriptions')
            ->expectsOutput('Completed. Marked 1 course subscription(s) as expired.')
            ->assertExitCode(0);

        $subscription = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        $this->assertEquals('expired', $subscription->status);
    }

    /**
     * 14. Test student submits clinical voice recording and supervisor grades.
     */
    public function test_student_submits_clinical_voice_recording_and_supervisor_grades(): void
    {
        $user = User::factory()->create();
        $admin = Admin::create(['name' => 'Supervisor', 'email' => 'sup@test.com', 'password' => 'secret']);
        $course = Course::create(['title_ar' => 'كورس تدريب النطق', 'slug' => 'voice-training']);
        $task = ClinicalTask::create([
            'course_id' => $course->id,
            'title_ar' => 'تمرين مخارج الحروف اللثوية',
            'instructions_ar' => 'سجل دقيقة لنطق حرف الثاء والذال والظاء',
        ]);

        $audioFile = UploadedFile::fake()->create('voice.mp3', 500, 'audio/mpeg');

        // Student submits assignment
        $response = $this->actingAs($user)->post(route('assignments.submit', $task->id), [
            'audio_file' => $audioFile,
            'student_notes' => 'واجهت صعوبة طفيفة في حرف الظاء',
        ]);

        $response->assertSessionHas('success');
        $submission = TaskSubmission::where('task_id', $task->id)->where('user_id', $user->id)->first();
        $this->assertNotNull($submission);
        $this->assertEquals('pending', $submission->status);
        $this->assertEquals('واجهت صعوبة طفيفة في حرف الظاء', $submission->student_notes);

        // Admin grades the task submission
        $gradeResponse = $this->actingAs($admin, 'admin')->post(route('admin.tasks.grade', $submission->id), [
            'score' => 92,
            'clinical_feedback' => 'أداء ممتاز، مخارج الحروف ممتازة',
        ]);

        $gradeResponse->assertSessionHas('success');
        $fresh = $submission->fresh();
        $this->assertEquals('graded', $fresh->status);
        $this->assertEquals(92, $fresh->score);
        $this->assertEquals('أداء ممتاز، مخارج الحروف ممتازة', $fresh->clinical_feedback);
        $this->assertEquals($admin->id, $fresh->graded_by);
    }

    /**
     * 15. Test certificate list, presentation page, and admin index.
     */
    public function test_certificate_routes_and_presentation(): void
    {
        $user = User::factory()->create();
        $admin = Admin::create(['name' => 'Admin Cert', 'email' => 'admin-cert@test.com', 'password' => 'secret']);
        $course = Course::create(['title_ar' => 'كورس التخاطب المعتمد', 'slug' => 'certified-speech']);

        $enrollmentService = app(CourseEnrollmentService::class);
        $enrollmentService->enroll($user, $course);

        $video = Video::create([
            'course_id' => $course->id,
            'title_ar' => 'محاضرة التخاطب الشاملة',
            'slug' => 'speech-lec',
            'duration_seconds' => 1000,
            'min_completion_percentage' => 80,
            'sort_order' => 1,
        ]);

        $progressService = app(VideoProgressService::class);
        $progressService->recordProgress($user, $video, 1000);

        $certService = app(CertificateService::class);
        $certificate = $certService->issueCertificate($user, $course);

        // Student visits certificates index
        $indexResponse = $this->actingAs($user)->get(route('certificates.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee($course->title);

        // Student visits certificate presentation view
        $certResponse = $this->actingAs($user)->get(route('certificates.show', $certificate->id));
        $certResponse->assertOk();
        $certResponse->assertSee($certificate->certificate_code);
        $certResponse->assertSee($user->name);

        // Admin visits certificates manager
        $adminResponse = $this->actingAs($admin, 'admin')->get(route('admin.certificates.index'));
        $adminResponse->assertOk();
        $adminResponse->assertSee($certificate->certificate_code);
    }
}
