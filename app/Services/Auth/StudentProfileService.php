<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class StudentProfileService
{
    /**
     * Update student profile fields including geographical location.
     *
     * @param  array{
     *     name?: string,
     *     phone?: string|null,
     *     country?: string|null,
     *     city?: string|null,
     *     address?: string|null,
     *     locale?: string|null
     * }  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        $fillable = array_intersect_key($data, array_flip([
            'name',
            'phone',
            'country',
            'city',
            'address',
            'locale',
        ]));

        $user->fill($fillable);
        $user->save();

        return $user;
    }

    /**
     * Change user status (active / suspended).
     */
    public function setStatus(User $user, string $status): User
    {
        if (! in_array($status, ['active', 'suspended'], true)) {
            throw ValidationException::withMessages([
                'status' => __('الحالة المحددة غير صالحة. يجب أن تكون active أو suspended'),
            ]);
        }

        $user->status = $status;
        $user->save();

        return $user;
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
