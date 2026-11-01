<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Support;

use Illuminate\Foundation\Http\FormRequest;

class AdminSendMessageRequest extends FormRequest
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
            'message' => ['nullable', 'string', 'max:10000', 'required_without:attachments'],
            'is_internal_note' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:20480', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip,mp3,wav,ogg,m4a'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'message' => __('نص الرد'),
            'is_internal_note' => __('ملاحظة داخلية خاصة'),
            'attachments' => __('المرفقات'),
            'attachments.*' => __('الملف المرفق'),
        ];
    }
}
