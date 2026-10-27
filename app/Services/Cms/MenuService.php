<?php

declare(strict_types=1);

namespace App\Services\Cms;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public const CACHE_PREFIX = 'alameen_menu_';

    /**
     * Retrieve active menu items for a specific location (cached).
     *
     * @return Collection<int, MenuItem>
     */
    public function getMenu(string $location): Collection
    {
        $cacheKey = self::CACHE_PREFIX.$location;

        try {
            $cached = Cache::get($cacheKey);
            if ($cached instanceof Collection) {
                return $cached;
            }
        } catch (\Throwable) {
            // In case of cache serialization or deserialization errors
        }

        Cache::forget($cacheKey);

        $menu = Menu::query()->where('location', $location)->first();

        $items = $menu
            ? $menu->items()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
            : new Collection;

        Cache::put($cacheKey, $items, 3600);

        return $items;
    }

    /**
     * Add or update an item in a menu.
     */
    public function saveItem(
        Menu $menu,
        string $titleAr,
        ?string $titleEn,
        string $url,
        ?string $routeName = null,
        int $sortOrder = 0,
        string $target = '_self',
        bool $isActive = true,
        ?int $itemId = null
    ): MenuItem {
        $item = $itemId ? MenuItem::findOrFail($itemId) : new MenuItem;
        $item->menu_id = $menu->id;
        $item->title_ar = $titleAr;
        $item->title_en = $titleEn;
        $item->url = $url;
        $item->route_name = $routeName;
        $item->sort_order = $sortOrder;
        $item->target = $target;
        $item->is_active = $isActive;
        $item->save();

        $this->flushMenuCache($menu->location);

        return $item;
    }

    /**
     * Flush cache for a specific menu location or all menus.
     */
    public function flushMenuCache(?string $location = null): void
    {
        if ($location) {
            Cache::forget(self::CACHE_PREFIX.$location);
        } else {
            foreach (['header', 'footer_quick', 'footer_support', 'footer_legal'] as $loc) {
                Cache::forget(self::CACHE_PREFIX.$loc);
            }
        }
    }
}
