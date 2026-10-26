<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property int $watched_seconds
 * @property float $completion_percentage
 * @property bool $is_completed
 * @property Carbon|null $last_watched_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'video_id',
    'watched_seconds',
    'completion_percentage',
    'is_completed',
    'last_watched_at',
])]
class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'video_id' => 'integer',
            'watched_seconds' => 'integer',
            'completion_percentage' => 'float',
            'is_completed' => 'boolean',
            'last_watched_at' => 'datetime',
        ];
    }

    /**
     * Student.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Video.
     *
     * @return BelongsTo<Video, $this>
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
