<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminFirebaseLoginRequest;
use App\Services\Auth\FirebaseAuthenticationService;
use App\Services\Auth\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AdminFirebaseAuthController extends Controller
{
    public function __construct(
        protected FirebaseAuthenticationService $authService,
        protected FirebaseTokenVerifier $tokenVerifier
    ) {}

    /**
     * Authenticate an administrator using a Firebase ID token.
     */
    public function authenticate(AdminFirebaseLoginRequest $request): JsonResponse
    {
        if (! $this->tokenVerifier->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => __('خدمة تسجيل دخول المشرفين عبر Firebase غير مفعلة، يرجى ضبط FIREBASE_PROJECT_ID في ملف .env.'),
            ], 400);
        }

        $validated = $request->validated();

        try {
            $result = $this->authService->authenticateAdmin(
                idToken: $validated['id_token'],
                remember: (bool) ($validated['remember'] ?? true)
            );

            return response()->json([
                'success' => true,
                'message' => __('تم تسجيل دخول المشرف بنجاح.'),
                'redirect' => $result['redirect'] ?? route('admin.dashboard'),
                'admin' => [
                    'id' => $result['admin']->id,
                    'name' => $result['admin']->name,
                    'email' => $result['admin']->email,
                    'avatar' => $result['admin']->avatar,
                    'role' => $result['admin']->role_name,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? __('فشل تسجيل الدخول للإدارة.'),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
