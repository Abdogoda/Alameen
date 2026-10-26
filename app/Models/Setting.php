<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value_ar
 * @property string|null $value_en
 * @property string $group
 * @property bool $is_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'key',
    'value_ar',
    'value_en',
    'group',
    'is_secret',
])]
class Setting extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_secret' => 'boolean',
        ];
    }

    /**
     * Get value based on active locale.
     */
    public function getValueAttribute(): ?string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->value_en))
            ? $this->value_en
            : $this->value_ar;
    }
}
