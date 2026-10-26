<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $section_key
 * @property string|null $title_ar
 * @property string|null $title_en
 * @property string|null $subtitle_ar
 * @property string|null $subtitle_en
 * @property string|null $content_ar
 * @property string|null $content_en
 * @property array<string, mixed>|null $metadata
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'section_key',
    'title_ar',
    'title_en',
    'subtitle_ar',
    'subtitle_en',
    'content_ar',
    'content_en',
    'metadata',
    'is_active',
])]
class ContentBlock extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getTitleAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    public function getSubtitleAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->subtitle_en))
            ? $this->subtitle_en
            : $this->subtitle_ar;
    }

    public function getContentAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->content_en))
            ? $this->content_en
            : $this->content_ar;
    }
}
