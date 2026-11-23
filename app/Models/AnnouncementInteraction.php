<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $announcement_id
 * @property int|null $user_id
 * @property int|null $admin_id
 * @property string|null $device_fingerprint
 * @property string $platform // 'web', 'mobile', 'admin'
 * @property string $action // 'viewed', 'dismissed', 'acknowledged'
 * @property Carbon|null $viewed_at
 * @property Carbon|null $dismissed_at
 * @property Carbon|null $acknowledged_at
 * @property array<string, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Announcement $announcement
 * @property-read User|null $user
 * @property-read Admin|null $admin
 */
class AnnouncementInteraction extends Model
{
    use HasFactory;

    protected $table = 'announcement_interactions';

    protected $fillable = [
        'announcement_id',
        'user_id',
        'admin_id',
        'device_fingerprint',
        'platform',
        'action',
        'viewed_at',
        'dismissed_at',
        'acknowledged_at',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'viewed_at' => 'datetime',
            'dismissed_at' => 'datetime',
            'acknowledged_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Announcement, $this>
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Admin, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
