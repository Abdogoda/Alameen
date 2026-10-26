<?php

declare(strict_types=1);

namespace App\Services\Certificate;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Services\Video\VideoProgressService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CertificateService
{
    public const MIN_PROGRESS_FOR_CERTIFICATE = 95.00;

    public function __construct(
        protected VideoProgressService $progressService
    ) {}

    /**
     * Determine if student is eligible for certificate issuance (>= 95% progress).
     */
    public function isEligible(User $user, Course $course): bool
    {
        $percentage = $this->progressService->getCourseProgressPercentage($user, $course);

        return $percentage >= self::MIN_PROGRESS_FOR_CERTIFICATE;
    }

    /**
     * Issue or retrieve certificate for eligible student.
     */
    public function issueCertificate(User $user, Course $course): Certificate
    {
        $existing = Certificate::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $percentage = $this->progressService->getCourseProgressPercentage($user, $course);

        if ($percentage < self::MIN_PROGRESS_FOR_CERTIFICATE) {
            throw ValidationException::withMessages([
                'progress' => __('عذراً، يجب إكمال 95% على الأقل من المحاضرات والمهام لإصدار الشهادة. نسبة إنجازك الحالية: :percent%', [
                    'percent' => $percentage,
                ]),
            ]);
        }

        $code = 'ALM-'.now()->format('Y').'-'.strtoupper(Str::random(8));
        $verificationUrl = url("/verify-certificate/{$code}");
        $qrCodeSvg = $this->generateQrSvg($verificationUrl);

        return Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_code' => $code,
            'verification_url' => $verificationUrl,
            'qr_code_svg' => $qrCodeSvg,
            'completion_percentage' => $percentage,
            'issued_at' => Carbon::now(),
        ]);
    }

    /**
     * Find a certificate by its public verification code.
     */
    public function verify(string $code): ?Certificate
    {
        return Certificate::with(['user', 'course'])
            ->where('certificate_code', trim($code))
            ->first();
    }

    /**
     * Generate portable SVG representation for the verification QR.
     */
    protected function generateQrSvg(string $data): string
    {
        // Safe, dependency-free SVG QR visual placeholder with encoded payload
        $encodedData = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="120" height="120" class="qr-code-svg">
  <rect width="100" height="100" fill="#ffffff" rx="8"/>
  <rect x="10" y="10" width="25" height="25" fill="#0284c7" rx="3"/>
  <rect x="15" y="15" width="15" height="15" fill="#ffffff" rx="1"/>
  <rect x="18" y="18" width="9" height="9" fill="#0284c7"/>
  <rect x="65" y="10" width="25" height="25" fill="#0284c7" rx="3"/>
  <rect x="70" y="15" width="15" height="15" fill="#ffffff" rx="1"/>
  <rect x="73" y="18" width="9" height="9" fill="#0284c7"/>
  <rect x="10" y="65" width="25" height="25" fill="#0284c7" rx="3"/>
  <rect x="15" y="70" width="15" height="15" fill="#ffffff" rx="1"/>
  <rect x="18" y="73" width="9" height="9" fill="#0284c7"/>
  <rect x="45" y="15" width="10" height="20" fill="#0f172a"/>
  <rect x="40" y="45" width="20" height="10" fill="#0f172a"/>
  <rect x="65" y="45" width="25" height="10" fill="#0f172a"/>
  <rect x="45" y="65" width="15" height="25" fill="#0f172a"/>
  <rect x="65" y="75" width="25" height="15" fill="#0f172a"/>
  <!-- Verified Data: {$encodedData} -->
</svg>
SVG;
    }
}
