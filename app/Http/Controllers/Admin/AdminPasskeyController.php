<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPasskeyDeleteRequest;
use App\Http\Requests\Admin\AdminPasskeyLoginRequest;
use App\Http\Requests\Admin\AdminPasskeyRegisterRequest;
use App\Models\Admin;
use App\Models\AdminPasskey;
use App\Services\Auth\AdminPasskeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPasskeyController extends Controller
{
    public function __construct(
        protected AdminPasskeyService $passkeyService
    ) {}

    /**
     * Get passkey assertion options for administrator login.
     */
    public function loginOptions(Request $request): JsonResponse
    {
        $payload = $this->passkeyService->generateLoginOptions();

        return response()->json($payload);
    }

    /**
     * Verify passkey assertion and log the administrator in.
     */
    public function login(AdminPasskeyLoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->passkeyService->verifyLogin(
            credential: $validated['credential'],
            challengeId: $validated['challenge_id'] ?? null,
            remember: (bool) ($validated['remember'] ?? true)
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تسجيل دخول المشرف بنجاح عبر مفتاح المرور.'),
            'redirect' => route('admin.dashboard'),
            'admin' => [
                'id' => $result['admin']->id,
                'name' => $result['admin']->name,
                'email' => $result['admin']->email,
                'role' => $result['admin']->role_name,
            ],
        ]);
    }

    /**
     * Get passkey creation options for the currently authenticated admin.
     */
    public function registrationOptions(Request $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $payload = $this->passkeyService->generateRegistrationOptions($admin);

        return response()->json($payload);
    }

    /**
     * Register and store a new passkey for the authenticated admin.
     */
    public function store(AdminPasskeyRegisterRequest $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        $validated = $request->validated();

        $passkey = $this->passkeyService->registerPasskey(
            admin: $admin,
            name: $validated['name'],
            credential: $validated['credential'],
            challengeId: $validated['challenge_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => __('تم تسجيل مفتاح المرور الجديد بنجاح.'),
            'passkey' => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at_human' => $passkey->created_at?->diffForHumans() ?? '',
            ],
        ]);
    }

    /**
     * Delete a passkey belonging to the authenticated admin.
     */
    public function destroy(AdminPasskeyDeleteRequest $request, AdminPasskey $passkey): JsonResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        if ($passkey->admin_id !== $admin->id) {
            abort(403, __('غير مصرح لك بإجراء هذه العملية.'));
        }

        $this->passkeyService->deletePasskey($admin, $passkey->id);

        return response()->json([
            'success' => true,
            'message' => __('تم حذف مفتاح المرور بنجاح.'),
        ]);
    }

    /**
     * List all passkeys belonging to the authenticated admin.
     */
    public function index(): JsonResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'passkeys' => $this->passkeyService->listPasskeys($admin),
        ]);
    }
}
