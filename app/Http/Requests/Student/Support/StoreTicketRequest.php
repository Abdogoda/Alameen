<?php

declare(strict_types=1);

namespace App\Http\Requests\Student\Support;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'subject' => ['required', 'string', 'min:3', 'max:255'],
            'category' => ['required', 'string', 'in:technical,academic,billing,courses,certificates,general'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'message' => ['required', 'string', 'min:5', 'max:10000'],
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
            'subject' => __('عنوان التذكرة'),
            'category' => __('التصنيف'),
            'priority' => __('درجة الأهمية'),
            'course_id' => __('الكورس المرتبط'),
            'message' => __('تفاصيل الرسالة'),
            'attachments' => __('المرفقات'),
            'attachments.*' => __('الملف المرفق'),
        ];
    }
}
