<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Support;

use Illuminate\Foundation\Http\FormRequest;

class AdminAssignRequest extends FormRequest
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
            'admin_id' => ['nullable', 'integer', 'exists:admins,id'],
        ];
    }
}
