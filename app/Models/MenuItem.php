<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/**
 * @property int $id
 * @property int $menu_id
 * @property string $title_ar
 * @property string|null $title_en
 * @property string $url
 * @property string|null $route_name
 * @property string $target
 * @property int $sort_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'menu_id',
    'title_ar',
    'title_en',
    'url',
    'route_name',
    'target',
    'sort_order',
    'is_active',
])]
class MenuItem extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'menu_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return ($locale === 'en' && ! empty($this->title_en))
            ? $this->title_en
            : $this->title_ar;
    }

    /**
     * Resolves absolute or route URL.
     */
    public function getResolvedUrlAttribute(): string
    {
        if (! empty($this->route_name) && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return $this->url;
    }
}
