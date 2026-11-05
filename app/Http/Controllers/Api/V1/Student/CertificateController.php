<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CertificateResource;
use App\Services\Certificate\CertificateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService,
    ) {}

    /**
     * GET /api/v1/student/certificates
     * Return all certificates earned by the authenticated student.
     */
    public function index(Request $request): JsonResponse
    {
        $certificates = $this->certificateService->getStudentCertificates($request->user());

        return response()->json([
            'success' => true,
            'data' => [
                'certificates' => CertificateResource::collection($certificates),
            ],
        ]);
    }

    /**
     * GET /api/v1/student/certificates/{code}
     * Return details, verification link, QR SVG, and file download URL for a specific certificate.
     */
    public function show(Request $request, string $code): JsonResponse
    {
        $certificate = $this->certificateService->verify($code);

        if (! $certificate || (int) $certificate->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('الشهادة المطلوبة غير موجودة.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'certificate' => new CertificateResource($certificate),
            ],
        ]);
    }
}
