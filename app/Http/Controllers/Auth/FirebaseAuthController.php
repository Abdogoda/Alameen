<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\FirebaseAuthenticationService;
use App\Services\Auth\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function authenticate(Request $request): JsonResponse
    {
        if (! $this->tokenVerifier->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => __('خدمة تسجيل الدخول عبر Firebase غير مفعلة أو لم يتم ضبط FIREBASE_PROJECT_ID في ملف .env.'),
            ], 400);
        }

        $validated = $request->validate([
            'id_token' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        try {
            $result = $this->authService->authenticateUser(
                idToken: $validated['id_token'],
                remember: (bool) ($validated['remember'] ?? true)
            );

            return response()->json([
                'success' => true,
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
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? __('فشل تسجيل الدخول.'),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
