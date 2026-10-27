<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\FirebaseAuthenticationService;
use App\Services\Auth\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FirebaseApiController extends Controller
{
    public function __construct(
        protected FirebaseAuthenticationService $authService,
        protected FirebaseTokenVerifier $tokenVerifier
    ) {}

    /**
     * Authenticate a user or admin via API using a Firebase ID token.
     */
    public function authenticate(Request $request): JsonResponse
    {
        if (! $this->tokenVerifier->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => __('Firebase authentication is disabled. Configure FIREBASE_PROJECT_ID in .env.'),
            ], 400);
        }

        $validated = $request->validate([
            'id_token' => ['required', 'string'],
            'guard' => ['sometimes', 'string', 'in:web,admin,user'],
            'device_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $guard = $validated['guard'] ?? 'web';
        $deviceName = $validated['device_name'] ?? 'api_firebase_token';

        try {
            if ($guard === 'admin') {
                $result = $this->authService->authenticateAdmin(
                    idToken: $validated['id_token'],
                    remember: false,
                    isApi: true,
                    deviceName: $deviceName
                );

                return response()->json([
                    'success' => true,
                    'message' => __('Admin authenticated successfully.'),
                    'token' => $result['token'] ?? '',
                    'token_type' => 'Bearer',
                    'admin' => [
                        'id' => $result['admin']->id,
                        'name' => $result['admin']->name,
                        'email' => $result['admin']->email,
                        'avatar' => $result['admin']->avatar,
                        'role' => $result['admin']->role_name,
                    ],
                ]);
            }

            $result = $this->authService->authenticateUser(
                idToken: $validated['id_token'],
                remember: false,
                isApi: true,
                deviceName: $deviceName
            );

            return response()->json([
                'success' => true,
                'message' => __('User authenticated successfully.'),
                'token' => $result['token'] ?? '',
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $result['user']->id,
                    'name' => $result['user']->name,
                    'email' => $result['user']->email,
                    'avatar' => $result['user']->avatar,
                    'status' => $result['user']->status,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? __('Authentication failed.'),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
