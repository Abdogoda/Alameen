<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PasskeyLoginRequest;
use App\Http\Requests\Api\PasskeyRegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\PasskeyService;
use App\Services\Auth\TokenManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasskeyController extends Controller
{
    public function __construct(
        protected PasskeyService $passkeyService,
        protected TokenManagementService $tokenManagementService
    ) {}

    /**
     * Get passkey login assertion options.
     */
    public function loginOptions(Request $request): JsonResponse
    {
        $options = $this->passkeyService->generateLoginOptions();

        return ApiResponse::success(
            data: $options,
            message: __('Passkey assertion options generated.')
        );
    }

    /**
     * Verify passkey and return Sanctum API token.
     */
    public function login(PasskeyLoginRequest $request): JsonResponse
    {
        $result = $this->passkeyService->verifyLogin(
            credential: $request->input('credential'),
            challengeId: $request->string('challenge_id')->toString()
        );

        $user = $result['user'];
        $deviceName = $request->input('device_name', 'Passkey API Token');
        $tokenInfo = $this->tokenManagementService->createToken($user, $deviceName);

        return ApiResponse::success(
            data: [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'passkey' => [
                    'id' => $result['passkey']->id,
                    'name' => $result['passkey']->name,
                ],
                'token' => $tokenInfo['plain_text_token'],
                'token_type' => 'Bearer',
            ],
            message: __('Authenticated successfully with passkey.')
        );
    }

    /**
     * Get passkey creation options for authenticated user.
     */
    public function registerOptions(Request $request): JsonResponse
    {
        $options = $this->passkeyService->generateRegistrationOptions($request->user());

        return ApiResponse::success(
            data: $options,
            message: __('Passkey creation options generated.')
        );
    }

    /**
     * Store new passkey for authenticated user.
     */
    public function register(PasskeyRegisterRequest $request): JsonResponse
    {
        $passkey = $this->passkeyService->registerPasskey(
            user: $request->user(),
            name: $request->string('name')->toString(),
            credential: $request->input('credential'),
            challengeId: $request->string('challenge_id')->toString()
        );

        return ApiResponse::success(
            data: [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'created_at' => $passkey->created_at->toISOString(),
            ],
            message: __('Passkey registered successfully.'),
            status: 201
        );
    }

    /**
     * List passkeys for authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $passkeys = $this->passkeyService->listPasskeys($request->user());

        return ApiResponse::success(
            data: $passkeys,
            message: __('Passkeys retrieved.')
        );
    }

    /**
     * Delete passkey for authenticated user.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->passkeyService->deletePasskey($request->user(), $id);

        return ApiResponse::success(
            message: __('Passkey removed successfully.')
        );
    }
}
