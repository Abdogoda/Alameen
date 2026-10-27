<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class FirebaseAuthenticationService
{
    public function __construct(
        protected FirebaseTokenVerifier $tokenVerifier
    ) {}

    /**
     * Authenticate or register a student / user via Firebase ID token.
     *
     * @return array{
     *     user: User,
     *     token?: string,
     *     redirect?: string
     * }
     *
     * @throws ValidationException
     */
    public function authenticateUser(
        string $idToken,
        bool $remember = true,
        bool $isApi = false,
        ?string $deviceName = 'Firebase Token'
    ): array {
        try {
            $claims = $this->tokenVerifier->verify($idToken);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'id_token' => [__('فشل التحقق من رمز Google عبر Firebase: ').$e->getMessage()],
            ]);
        }

        $email = $claims['email'];
        if (blank($email)) {
            throw ValidationException::withMessages([
                'email' => [__('لم يتم العثور على بريد إلكتروني مرتبط بحساب Google.')],
            ]);
        }

        $firebaseUid = $claims['uid'];
        $googleId = $claims['google_id'];
        $avatar = $claims['avatar'];
        $name = $claims['name'] ?: 'طالب الأمين';
        $emailVerified = $claims['email_verified'];

        // 1. Find user by firebase_uid
        /** @var User|null $user */
        $user = User::where('firebase_uid', $firebaseUid)->first();

        // 2. Find user by google_id
        if (! $user && ! empty($googleId)) {
            $user = User::where('google_id', $googleId)->first();
        }

        // 3. Find user by email
        if (! $user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $updates = [];
            if (empty($user->firebase_uid)) {
                $updates['firebase_uid'] = $firebaseUid;
            }
            if (empty($user->google_id) && ! empty($googleId)) {
                $updates['google_id'] = $googleId;
            }
            if (empty($user->avatar) && ! empty($avatar)) {
                $updates['avatar'] = $avatar;
            }
            if (is_null($user->email_verified_at) && $emailVerified) {
                $updates['email_verified_at'] = now();
            }

            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }
        } else {
            // Create a new student user
            $user = new User;
            $user->forceFill([
                'name' => $name,
                'email' => $email,
                'firebase_uid' => $firebaseUid,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'status' => 'active',
                'email_verified_at' => $emailVerified ? now() : null,
                'locale' => app()->getLocale(),
                'password' => Hash::make(Str::random(32)),
            ]);
            $user->save();
        }

        if ($user->isSuspended()) {
            throw ValidationException::withMessages([
                'email' => [__('حسابك موقوف مؤقتاً، يرجى مراجعة إدارة المنصة.')],
            ]);
        }

        if ($isApi) {
            $token = $user->createToken($deviceName ?? 'firebase_auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        Auth::guard('web')->login($user, $remember);
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return [
            'user' => $user,
            'redirect' => route('dashboard'),
        ];
    }

    /**
     * Authenticate an existing administrator via Firebase ID token.
     *
     * @return array{
     *     admin: Admin,
     *     token?: string,
     *     redirect?: string
     * }
     *
     * @throws ValidationException
     */
    public function authenticateAdmin(
        string $idToken,
        bool $remember = true,
        bool $isApi = false,
        ?string $deviceName = 'Admin Firebase Token'
    ): array {
        try {
            $claims = $this->tokenVerifier->verify($idToken);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'id_token' => [__('فشل التحقق من رمز Google للمسؤول: ').$e->getMessage()],
            ]);
        }

        $email = $claims['email'];
        if (blank($email)) {
            throw ValidationException::withMessages([
                'email' => [__('لم يتم العثور على بريد إلكتروني مرتبط بحساب Google.')],
            ]);
        }

        $firebaseUid = $claims['uid'];
        $googleId = $claims['google_id'];
        $avatar = $claims['avatar'];

        // 1. Find admin by firebase_uid
        /** @var Admin|null $admin */
        $admin = Admin::where('firebase_uid', $firebaseUid)->first();

        // 2. Find admin by google_id
        if (! $admin && ! empty($googleId)) {
            $admin = Admin::where('google_id', $googleId)->first();
        }

        // 3. Find admin by email
        if (! $admin) {
            $admin = Admin::where('email', $email)->first();
        }

        // Admins cannot self-register: Only existing authorized admins may log in
        if (! $admin) {
            throw ValidationException::withMessages([
                'email' => [__('هذا الحساب ليس لديه صلاحيات وصول إلى لوحة الإدارة.')],
            ]);
        }

        $updates = [];
        if (empty($admin->firebase_uid)) {
            $updates['firebase_uid'] = $firebaseUid;
        }
        if (empty($admin->google_id) && ! empty($googleId)) {
            $updates['google_id'] = $googleId;
        }
        if (empty($admin->avatar) && ! empty($avatar)) {
            $updates['avatar'] = $avatar;
        }

        if (! empty($updates)) {
            $admin->forceFill($updates)->save();
        }

        if ($isApi) {
            $token = $admin->createToken($deviceName ?? 'admin_firebase_token')->plainTextToken;

            return [
                'admin' => $admin,
                'token' => $token,
            ];
        }

        Auth::guard('admin')->login($admin, $remember);
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return [
            'admin' => $admin,
            'redirect' => route('admin.dashboard'),
        ];
    }
}
