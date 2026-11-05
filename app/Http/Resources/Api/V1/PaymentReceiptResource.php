<?php

namespace App\Http\Resources\Api\V1;

use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PaymentReceipt
 */
class PaymentReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = null;
        if (! empty($this->receipt_image_path)) {
            $imageUrl = str_starts_with($this->receipt_image_path, 'http')
                ? $this->receipt_image_path
                : asset('storage/'.ltrim($this->receipt_image_path, '/'));
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'payment_method' => $this->payment_method,
            'payment_method_label' => $this->paymentMethodLabel(),
            'sender_name' => $this->sender_name,
            'sender_account' => $this->sender_account,
            'amount' => (float) $this->amount,
            'transaction_reference' => $this->transaction_reference,
            'receipt_image_path' => $this->receipt_image_path,
            'receipt_image_url' => $imageUrl,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'course' => $this->whenLoaded('course', fn () => $this->course ? new CourseSummaryResource($this->course) : null),
        ];
    }
}
