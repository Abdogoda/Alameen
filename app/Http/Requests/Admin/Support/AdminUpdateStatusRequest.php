<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Support;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateStatusRequest extends FormRequest
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
            'status' => ['required', 'string', 'in:open,in_progress,waiting_student,waiting_admin,resolved,closed'],
        ];
    }
}
