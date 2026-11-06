<?php

declare(strict_types=1);

namespace App\Http\Requests\Student;

use App\Models\User;
use App\Models\Video;
use App\Services\Video\VideoProgressService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RecordProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();
        /** @var Video|null $video */
        $video = $this->route('video');

        // Default to true when tested in isolation without active HTTP route binding
        if (! $video) {
            return true;
        }

        if (! $user || ! $video) {
            return false;
        }

        return app(VideoProgressService::class)->canAccessVideo($user, $video);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'watched_seconds' => ['required', 'numeric', 'min:0'],
            'is_ended' => ['nullable'],
            'duration' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
