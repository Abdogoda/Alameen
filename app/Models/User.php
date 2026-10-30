<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $locale
 * @property string|null $country
 * @property string|null $city
 * @property string|null $address
 * @property string $status
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $google_id
 * @property string|null $firebase_uid
 * @property string|null $avatar
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'email',
    'phone',
    'locale',
    'country',
    'city',
    'address',
    'status',
    'google_id',
    'firebase_uid',
    'avatar',
    'email_verified_at',
    'password',
])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locale' => 'string',
            'status' => 'string',
        ];
    }

    /**
     * Check if the student account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the student account is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Courses the user is enrolled in.
     *
     * @return BelongsToMany<Course, $this>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['id', 'access_starts_at', 'access_expires_at', 'status', 'granted_by'])
            ->withTimestamps();
    }

    /**
     * Video progress records.
     *
     * @return HasMany<VideoProgress, $this>
     */
    public function videoProgress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Payment receipts submitted by the student.
     *
     * @return HasMany<PaymentReceipt, $this>
     */
    public function paymentReceipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Certificates earned by the student.
     *
     * @return HasMany<Certificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
