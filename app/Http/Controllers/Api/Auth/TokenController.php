<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTokenRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\TokenManagementService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function __construct(
        protected TokenManagementService $tokenService
    ) {}

    /**
     * List user's active API tokens.
     */
    public function index(Request $request): JsonResponse
    {
        $tokens = $this->tokenService->listTokens($request->user());

        return ApiResponse::success(
            data: $tokens,
            message: __('API tokens retrieved.')
        );
    }

    /**
     * Create a new API token.
     */
    public function store(CreateTokenRequest $request): JsonResponse
    {
        $expiresAt = $request->input('expires_at')
            ? Carbon::parse($request->input('expires_at'))
            : null;

        $created = $this->tokenService->createToken(
            user: $request->user(),
            name: $request->string('name')->toString(),
            abilities: $request->input('abilities', ['*']),
            expiresAt: $expiresAt
        );

        return ApiResponse::success(
            data: [
                'id' => $created['token']->id,
                'name' => $created['token']->name,
                'abilities' => $created['token']->abilities,
                'expires_at' => $created['token']->expires_at?->toISOString(),
                'token' => $created['plain_text_token'],
                'token_type' => 'Bearer',
            ],
            message: __('API token created successfully.'),
            status: 201
        );
    }

    /**
     * Revoke a specific API token.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $revoked = $this->tokenService->revokeToken($request->user(), $id);

        if (! $revoked) {
            return ApiResponse::error(
                message: __('Token not found or does not belong to you.'),
                status: 404
            );
        }

        return ApiResponse::success(
            message: __('API token revoked successfully.')
        );
    }

    /**
     * Revoke all other API tokens except current one.
     */
    public function destroyOthers(Request $request): JsonResponse
    {
        $this->tokenService->revokeOtherTokens($request->user());

        return ApiResponse::success(
            message: __('Other API tokens revoked successfully.')
        );
    }
}
