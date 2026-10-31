<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveMenuItemRequest;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Services\Cms\MenuService;
use App\Services\Cms\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService,
        protected MenuService $menuService
    ) {}

    /**
     * Display dynamic settings and CMS control panel.
     */
    public function index(): View
    {
        $settings = $this->settingsService->allCached();
        $heroBlock = ContentBlock::where('section_key', 'hero')->first();
        $faqBlock = ContentBlock::where('section_key', 'faq')->first();
        $menus = Menu::with('items')->get();

        return view('admin.settings.index', compact('settings', 'heroBlock', 'faqBlock', 'menus'));
    }

    /**
     * Save/update global payment, branding, or contact settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $input = $request->except(['_token', '_method']);

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $this->settingsService->set(
                    key: $key,
                    valueAr: $value['ar'] ?? null,
                    valueEn: $value['en'] ?? null,
                    group: $value['group'] ?? 'general'
                );
            }
        }

        return back()->with('success', __('تم حفظ وتحديث الإعدادات الديناميكية وتفريغ الكاش بنجاح.'));
    }

    /**
     * Store or update a dynamic menu item.
     */
    public function saveMenuItem(SaveMenuItemRequest $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validated();

        $this->menuService->saveItem(
            menu: $menu,
            titleAr: $validated['title_ar'],
            titleEn: $validated['title_en'] ?? null,
            url: $validated['url'],
            routeName: $validated['route_name'] ?? null,
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            target: $validated['target'] ?? '_self',
            isActive: $request->boolean('is_active', true),
            itemId: $request->input('item_id') ? (int) $request->input('item_id') : null
        );

        return back()->with('success', __('تم تحديث رابط القائمة بنجاح.'));
    }
}
