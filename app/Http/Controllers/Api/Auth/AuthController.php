<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService
    ) {}

    /**
     * Handle user login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->attemptLogin(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            isApi: true,
            deviceName: $request->input('device_name', 'API Token'),
            remember: $request->boolean('remember', false),
            ip: $request->ip()
        );

        if ($result->requiresTwoFactor) {
            return ApiResponse::success(
                data: $result->toArray(),
                message: __('Two-factor authentication code required.'),
                status: 200
            );
        }

        return ApiResponse::success(
            data: $result->toArray(),
            message: __('Authenticated successfully.')
        );
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $registered = $this->authService->register(
            data: $request->validated(),
            isApi: true,
            deviceName: $request->input('device_name', 'API Token')
        );

        return ApiResponse::success(
            data: [
                'user' => [
                    'id' => $registered['user']->id,
                    'name' => $registered['user']->name,
                    'email' => $registered['user']->email,
                    'email_verified_at' => $registered['user']->email_verified_at,
                ],
                'token' => $registered['token'],
                'token_type' => 'Bearer',
            ],
            message: __('Registration successful.'),
            status: 201
        );
    }

    /**
     * Handle user logout (revoking current API token).
     */
    public function logout(Request $request): JsonResponse
    {
        $allDevices = $request->boolean('all_devices', false);

        $this->authService->logout($request->user(), allDevices: $allDevices);

        return ApiResponse::success(
            message: $allDevices
                ? __('Logged out from all devices.')
                : __('Logged out successfully.')
        );
    }

    /**
     * Get authenticated user profile and security summary.
     */
    public function me(Request $request): JsonResponse
    {
        $profile = $this->authService->getSecurityProfile($request->user());

        return ApiResponse::success(data: $profile);
    }
}
