<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Localization\LocalizationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentProfileService
{
    /**
     * Update student profile fields including geographical location, avatar, and locale sync.
     *
     * @param  array{
     *     name?: string,
     *     email?: string,
     *     phone?: string|null,
     *     country?: string|null,
     *     city?: string|null,
     *     address?: string|null,
     *     locale?: string|null
     * }  $data
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $avatar = null): User
    {
        $fillable = array_intersect_key($data, array_flip([
            'name',
            'email',
            'phone',
            'country',
            'city',
            'address',
            'locale',
        ]));

        $user->fill($fillable);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($avatar instanceof UploadedFile && $avatar->isValid()) {
            $path = $avatar->store('avatars', 'public');
            if ($path !== false) {
                $user->avatar = $path;
            }
        }

        $user->save();

        if (! empty($data['locale'])) {
            app(LocalizationService::class)->setLocale((string) $data['locale'], $user);
        }

        return $user;
    }

    /**
     * Verify current password and permanently delete student account + revoke tokens.
     *
     * @throws ValidationException
     */
    public function deleteAccount(User $user, string $currentPassword): void
    {
        if (! Hash::check($currentPassword, (string) $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('The provided password does not match your current password.')],
            ]);
        }

        try {
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
        } catch (\Throwable) {
            // Ignore if personal_access_tokens table is not migrated yet
        }

        $user->delete();
    }

    /**
     * Change user status (active / pending / suspended).
     */
    public function setStatus(User $user, string $status): User
    {
        if (! in_array($status, ['active', 'pending', 'suspended'], true)) {
            throw ValidationException::withMessages([
                'status' => __('الحالة المحددة غير صالحة. يجب أن تكون active أو pending أو suspended'),
            ]);
        }

        $user->status = $status;
        $user->save();

        return $user;
    }

    /**
     * Approve and activate a student account.
     */
    public function approve(User $user): User
    {
        return $this->activate($user);
    }

    /**
     * Suspend a student account.
     */
    public function suspend(User $user): User
    {
        return $this->setStatus($user, 'suspended');
    }

    /**
     * Activate a student account.
     */
    public function activate(User $user): User
    {
        return $this->setStatus($user, 'active');
    }
}
