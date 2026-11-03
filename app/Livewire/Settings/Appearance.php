<?php

namespace App\Livewire\Settings;

use Flux\Flux;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Appearance settings')]
class Appearance extends Component
{
    public string $theme = 'light';

    public string $fontSize = 'normal';

    public string $motion = 'full';

    public bool $loaderEnabled = true;

    public bool $scrollProgressEnabled = true;

    public function mount(): void
    {
        $this->theme = (string) session('alameen_theme', request()->cookie('alameen_theme', 'light'));
        $this->fontSize = (string) session('alameen_font_size', request()->cookie('alameen_font_size', 'normal'));
        $this->motion = (string) session('alameen_motion', request()->cookie('alameen_motion', 'full'));
        $this->loaderEnabled = session('alameen_loader_enabled', request()->cookie('alameen_loader_enabled', 'true')) !== 'false';
        $this->scrollProgressEnabled = session('alameen_scroll_progress', request()->cookie('alameen_scroll_progress', 'true')) !== 'false';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function saveSettings(array $data): void
    {
        $this->theme = in_array($data['theme'] ?? '', ['light', 'dark', 'system'], true) ? $data['theme'] : 'light';
        $this->fontSize = in_array($data['fontSize'] ?? '', ['normal', 'medium', 'large'], true) ? $data['fontSize'] : 'normal';
        $this->motion = in_array($data['motion'] ?? '', ['full', 'reduced'], true) ? $data['motion'] : 'full';
        $this->loaderEnabled = (bool) ($data['loaderEnabled'] ?? true);
        $this->scrollProgressEnabled = (bool) ($data['scrollProgressEnabled'] ?? true);

        // Save into session for immediate server rendering
        session([
            'alameen_theme' => $this->theme,
            'alameen_font_size' => $this->fontSize,
            'alameen_motion' => $this->motion,
            'alameen_loader_enabled' => $this->loaderEnabled ? 'true' : 'false',
            'alameen_scroll_progress' => $this->scrollProgressEnabled ? 'true' : 'false',
        ]);

        // Queue 1-year long-lived cookies
        Cookie::queue('alameen_theme', $this->theme, 60 * 24 * 365);
        Cookie::queue('alameen_font_size', $this->fontSize, 60 * 24 * 365);
        Cookie::queue('alameen_motion', $this->motion, 60 * 24 * 365);
        Cookie::queue('alameen_loader_enabled', $this->loaderEnabled ? 'true' : 'false', 60 * 24 * 365);
        Cookie::queue('alameen_scroll_progress', $this->scrollProgressEnabled ? 'true' : 'false', 60 * 24 * 365);

        // Update Flux appearance if available
        if ($this->theme !== 'system') {
            Cookie::queue('flux.appearance', $this->theme, 60 * 24 * 365);
        } else {
            Cookie::expire('flux.appearance');
        }

        Flux::toast(variant: 'success', text: __('تم حفظ وتطبيق تفضيلات المظهر بنجاح!'));
    }

    public function render()
    {
        return view('web.settings.appearance')->layout('web.layouts.app');
    }
}
