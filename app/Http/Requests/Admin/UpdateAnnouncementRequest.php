<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check() && auth('admin')->user()->hasPermission('announcements.manage');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $announcementId = $this->route('announcement')?->id ?? $this->route('announcement');

        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('announcements', 'slug')->ignore($announcementId)],
            'content_type' => ['required', 'string', 'in:text,video,mixed'],
            'summary_ar' => ['nullable', 'string', 'max:1000'],
            'summary_en' => ['nullable', 'string', 'max:1000'],
            'body_ar' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],

            // Video details
            'video_type' => ['nullable', 'string', 'in:url,upload,youtube,vimeo,mux'],
            'video_url' => ['nullable', 'string'],
            'video_mux_playback_id' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,ogg,qt,webm', 'max:102400'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],

            // Targeting
            'target_platforms' => ['required', 'array', 'min:1'],
            'target_platforms.*' => ['string', 'in:web,mobile,admin,all'],
            'target_audience' => ['required', 'string', 'in:all,students,admins,guests'],

            // Display Mode & Enforcement
            'display_mode' => ['required', 'string', 'in:popup,force,banner,inline'],
            'frequency' => ['required', 'string', 'in:once,until_dismissed,every_session,always'],

            // Call to action
            'button_text_ar' => ['nullable', 'string', 'max:100'],
            'button_text_en' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:1000'],

            // Scheduling & Status
            'is_active' => ['nullable', 'boolean'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }
}
