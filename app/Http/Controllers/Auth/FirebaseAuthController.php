<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirebaseLoginRequest;
use App\Services\Auth\FirebaseAuthenticationService;
use App\Services\Auth\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FirebaseAuthController extends Controller
{
    public function __construct(
        protected FirebaseAuthenticationService $authService,
        protected FirebaseTokenVerifier $tokenVerifier
    ) {}

    /**
     * Authenticate or register a student user using a Firebase ID token.
     */
    public function authenticate(FirebaseLoginRequest $request): JsonResponse
    {
        if (! $this->tokenVerifier->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => __('خدمة تسجيل الدخول عبر Firebase غير مفعلة أو لم يتم ضبط FIREBASE_PROJECT_ID في ملف .env.'),
            ], 400);
        }

        $validated = $request->validated();

        try {
            $result = $this->authService->authenticateUser(
                idToken: $validated['id_token'],
                remember: (bool) ($validated['remember'] ?? true)
            );

            if (($result['status'] ?? null) === 'pending') {
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'type' => 'info',
                    'message' => $result['message'] ?? __('حسابك قيد المراجعة وبانتظار موافقة إدارة المنصة. يرجى الانتظار حتى يتم اعتماد الحساب لتتمكن من تسجيل الدخول.'),
                    'user' => [
                        'id' => $result['user']->id,
                        'name' => $result['user']->name,
                        'email' => $result['user']->email,
                        'avatar' => $result['user']->avatar,
                        'status' => $result['user']->status,
                    ],
                ], 200);
            }

            return response()->json([
                'success' => true,
                'status' => 'authenticated',
                'type' => 'success',
                'message' => __('تم تسجيل الدخول بنجاح.'),
                'redirect' => $result['redirect'] ?? route('dashboard'),
                'user' => [
                    'id' => $result['user']->id,
                    'name' => $result['user']->name,
                    'email' => $result['user']->email,
                    'avatar' => $result['user']->avatar,
                ],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first() ?? __('فشل تسجيل الدخول.');

            if (str_contains($firstError, 'المراجعة') || str_contains($firstError, 'موافقة')) {
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'type' => 'info',
                    'message' => $firstError,
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
