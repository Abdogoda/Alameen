<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(
        protected EmailVerificationService $verificationService
    ) {}

    /**
     * Resend verification notification.
     */
    public function resend(Request $request): JsonResponse
    {
        $sent = $this->verificationService->sendVerificationNotification($request->user());

        if (! $sent) {
            return ApiResponse::success(
                message: __('Email is already verified.')
            );
        }

        return ApiResponse::success(
            message: __('Verification email sent.')
        );
    }

    /**
     * Verify email via ID and hash.
     */
    public function verify(Request $request, int $id, string $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        $this->verificationService->verify($user, $hash);

        return ApiResponse::success(
            message: __('Email verified successfully.')
        );
    }
}
