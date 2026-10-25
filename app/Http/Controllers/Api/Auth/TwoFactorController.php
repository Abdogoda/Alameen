<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TwoFactorChallengeRequest;
use App\Http\Requests\Api\TwoFactorConfirmRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\AuthenticationService;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService,
        protected AuthenticationService $authService
    ) {}

    /**
     * Complete a two-factor authentication challenge during login.
     */
    public function challenge(TwoFactorChallengeRequest $request): JsonResponse
    {
        $result = $this->authService->completeTwoFactorLogin(
            challengeToken: $request->string('challenge_token')->toString(),
            code: $request->input('code'),
            recoveryCode: $request->input('recovery_code'),
            isApi: true,
            deviceName: $request->input('device_name', 'API Token')
        );

        return ApiResponse::success(
            data: $result->toArray(),
            message: __('Two-factor challenge verified successfully.')
        );
    }

    /**
     * Initiate two-factor authentication setup for authenticated user.
     */
    public function enable(Request $request): JsonResponse
    {
        $setup = $this->twoFactorService->enable(
            user: $request->user(),
            force: $request->boolean('force', false)
        );

        return ApiResponse::success(
            data: $setup,
            message: __('Two-factor authentication initiated. Scan the QR code or enter the secret in your authenticator app.')
        );
    }

    /**
     * Confirm two-factor authentication with the 6-digit TOTP code.
     */
    public function confirm(TwoFactorConfirmRequest $request): JsonResponse
    {
        $result = $this->twoFactorService->confirm(
            user: $request->user(),
            code: $request->string('code')->toString()
        );

        return ApiResponse::success(
            data: $result,
            message: __('Two-factor authentication confirmed and enabled successfully.')
        );
    }

    /**
     * Disable two-factor authentication for authenticated user.
     */
    public function disable(Request $request): JsonResponse
    {
        $this->twoFactorService->disable($request->user());

        return ApiResponse::success(
            message: __('Two-factor authentication disabled successfully.')
        );
    }

    /**
     * Retrieve recovery codes for authenticated user.
     */
    public function recoveryCodes(Request $request): JsonResponse
    {
        $codes = $this->twoFactorService->getRecoveryCodes($request->user());

        return ApiResponse::success(
            data: ['recovery_codes' => $codes],
            message: __('Recovery codes retrieved.')
        );
    }

    /**
     * Regenerate new recovery codes for authenticated user.
     */
    public function regenerateRecoveryCodes(Request $request): JsonResponse
    {
        $codes = $this->twoFactorService->regenerateRecoveryCodes($request->user());

        return ApiResponse::success(
            data: ['recovery_codes' => $codes],
            message: __('New recovery codes generated successfully.')
        );
    }
}
