<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Support;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdatePriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
        ];
    }
}
