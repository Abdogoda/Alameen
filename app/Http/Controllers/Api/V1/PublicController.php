<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\SwitchLocaleRequest;
use App\Http\Resources\Api\V1\CertificateResource;
use App\Http\Resources\Api\V1\CourseSummaryResource;
use App\Http\Resources\Api\V1\LevelResource;
use App\Http\Resources\Api\V1\PaymentReceiptResource;
use App\Http\Resources\Api\V1\VideoResource;
use App\Models\Course;
use App\Services\Certificate\CertificateService;
use App\Services\Cms\PublicContentService;
use App\Services\Course\StudentCourseWorkspaceService;
use App\Services\Localization\LocalizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(
        protected PublicContentService $publicContentService,
        protected StudentCourseWorkspaceService $workspaceService,
        protected CertificateService $certificateService,
        protected LocalizationService $localizationService,
    ) {}

    /**
     * GET /api/v1/public/bootstrap
     * Return global platform branding, currencies, feature flags, and supported locales.
     */
    public function bootstrap(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->publicContentService->getBootstrapConfig(),
        ]);
    }

    /**
     * GET /api/v1/public/home
     * Return landing page sections, featured courses, stats, and SEO metadata.
     */
    public function home(): JsonResponse
    {
        $data = $this->publicContentService->getLandingPageData();

        return response()->json([
            'success' => true,
            'data' => [
                'locale' => $data['loc'],
                'hero' => [
                    'title' => $data['heroBlock']?->title,
                    'subtitle' => $data['heroBlock']?->subtitle,
                    'cta_primary_text' => $data['cta1Text'],
                    'cta_primary_url' => $data['cta1Url'],
                    'cta_secondary_text' => $data['cta2Text'],
                    'cta_secondary_url' => $data['cta2Url'],
                    'students_count' => $data['studentsCount'],
                    'images' => $data['heroImages'],
                ],
                'about_pillars' => $data['pillars'],
                'feedback_images' => $data['feedbackImages'],
                'featured_courses' => CourseSummaryResource::collection($data['courses']),
                'reviews' => $data['reviewCards'],
                'faq' => $data['faqItems'],
                'seo' => $this->publicContentService->getSeoData(),
            ],
        ]);
    }

    /**
     * GET /api/v1/public/courses
     * Return all active published courses for the public catalog.
     */
    public function courses(Request $request): JsonResponse
    {
        $courses = $this->workspaceService->getPublicCatalog($request->query('search'));

        return response()->json([
            'success' => true,
            'data' => [
                'courses' => CourseSummaryResource::collection($courses),
            ],
        ]);
    }

    /**
     * GET /api/v1/public/courses/{slug}
     * Return detailed public course preview including levels, lesson previews, and enrollment status if authenticated.
     */
    public function courseShow(Request $request, string $slug): JsonResponse
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => __('المساق المطلوب غير موجود.'),
            ], 404);
        }

        $details = $this->workspaceService->getCourseDetailsData($course, $request->user());

        return response()->json([
            'success' => true,
            'data' => [
                'course' => new CourseSummaryResource($details['course']),
                'has_enrolled' => $details['hasActiveAccess'],
                'pending_receipt' => $details['pendingReceipt'] ? new PaymentReceiptResource($details['pendingReceipt']) : null,
                'completed_video_ids' => $details['completedVideoIds'],
                'accessible_video_ids' => $details['accessibleVideoIds'],
                'total_videos' => $details['totalVideosCount'],
                'completed_count' => $details['completedVideosCount'],
                'progress_percent' => $details['courseProgressPercentage'],
                'resume_video' => $details['resumeVideo'] ? new VideoResource($details['resumeVideo']) : null,
                'levels' => LevelResource::collection($details['levels']),
                'unleveled_videos' => VideoResource::collection($details['standaloneVideos']),
            ],
        ]);
    }

    /**
     * GET /api/v1/public/reviews
     * Return clinical testimonials, success stories, and platform feedback.
     */
    public function reviews(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->publicContentService->getReviewsPageData(),
        ]);
    }

    /**
     * GET /api/v1/public/contact
     * Return contact information, social links, and support channels.
     */
    public function contact(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->publicContentService->getContactPageData(),
        ]);
    }

    /**
     * GET /api/v1/public/seo/{pageKey}
     * Return localized SEO metadata and OpenGraph tags for a given page key.
     */
    public function seo(string $pageKey): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->publicContentService->getSeoData($pageKey),
        ]);
    }

    /**
     * GET /api/v1/public/certificates/verify/{code}
     * Verify a student certificate by its unique verification code.
     */
    public function verifyCertificate(string $code): JsonResponse
    {
        $certificate = $this->certificateService->verify($code);

        if (! $certificate) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => __('رمز الشهادة غير صالح أو غير مسجل في المنصة.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'valid' => true,
            'message' => __('الشهادة موثقة وصالحة.'),
            'data' => [
                'certificate' => new CertificateResource($certificate),
            ],
        ]);
    }

    /**
     * POST /api/v1/public/locale
     * Switch active locale and optionally persist to the authenticated user's profile.
     */
    public function switchLocale(SwitchLocaleRequest $request): JsonResponse
    {
        $locale = $this->localizationService->setLocale(
            $request->validated('locale'),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => $locale === 'ar' ? 'تم تغيير اللغة إلى العربية.' : 'Language switched to English.',
            'data' => [
                'locale' => $locale,
                'direction' => $this->localizationService->getDirection($locale),
                'is_rtl' => $this->localizationService->isRtl($locale),
            ],
        ]);
    }

    /**
     * GET /api/v1/openapi.json
     * Serve the complete OpenAPI 3.0.3 Swagger specification.
     */
    public function openApiSpec(): JsonResponse
    {
        $path = public_path('swagger.json');
        if (! file_exists($path)) {
            $path = base_path('swagger.json');
        }

        if (! file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'OpenAPI specification file not found.',
            ], 404);
        }

        $json = json_decode((string) file_get_contents($path), true);

        return response()->json($json);
    }
}
