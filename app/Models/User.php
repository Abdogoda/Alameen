<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
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
use Laravel\Sanctum\HasApiTokens;

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
    use HasApiTokens, HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

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
     * Send the email verification notification using 6-digit OTP code.
     */
    public function sendEmailVerificationNotification(): void
    {
        app(\App\Services\Auth\EmailVerificationOtpService::class)->sendOtp($this);
    }

    /**
     * Check if the student account is active / approved.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the student account is pending admin approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the student account is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Scope query to only pending students.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope query to only active/approved students.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
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
     * Get all courses accessible to this student (explicitly enrolled + published auto-enrolled courses).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Course>
     */
    public function getAccessibleCourses(): \Illuminate\Database\Eloquent\Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Course> $enrolled */
        $enrolled = $this->courses()->with(['publishedVideos'])->get();

        if ($this->isSuspended()) {
            return $enrolled;
        }

        $enrolledIds = $enrolled->pluck('id')->all();

        $autoCourses = Course::query()
            ->where('is_published', true)
            ->where('auto_enroll_students', true)
            ->whereNotIn('id', $enrolledIds)
            ->with(['publishedVideos'])
            ->orderBy('sort_order')
            ->get();

        foreach ($autoCourses as $autoCourse) {
            $pivot = new \Illuminate\Database\Eloquent\Relations\Pivot([
                'user_id' => $this->id,
                'course_id' => $autoCourse->id,
                'status' => 'active',
                'access_starts_at' => $this->created_at ?? Carbon::now(),
                'access_expires_at' => null,
                'granted_by' => null,
            ]);
            $pivot->status = 'active';
            $pivot->access_expires_at = null;
            $autoCourse->setRelation('pivot', $pivot);
        }

        return $enrolled->concat($autoCourses);
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
     * Clinical task submissions by the student.
     *
     * @return HasMany<TaskSubmission, $this>
     */
    public function taskSubmissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    /**
     * Support tickets opened by the student.
     *
     * @return HasMany<SupportTicket, $this>
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
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

    /**
     * Get avatar URL or null if none set.
     */
    public function avatarUrl(): ?string
    {
        if (blank($this->avatar)) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://') || str_starts_with($this->avatar, 'data:')) {
            return $this->avatar;
        }

        return asset('storage/'.ltrim($this->avatar, '/'));
    }
}
