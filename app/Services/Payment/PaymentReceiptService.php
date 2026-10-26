<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Admin;
use App\Models\Course;
use App\Models\PaymentReceipt;
use App\Models\User;
use App\Services\Course\CourseEnrollmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentReceiptService
{
    public function __construct(
        protected CourseEnrollmentService $enrollmentService
    ) {}

    /**
     * Submit a new payment receipt by a student.
     *
     * @param  array{
     *     payment_method: string,
     *     amount: float|string,
     *     sender_name?: string|null,
     *     sender_account?: string|null,
     *     transaction_reference?: string|null,
     *     receipt_image: UploadedFile|string
     * }  $data
     */
    public function submitReceipt(User $user, Course $course, array $data): PaymentReceipt
    {
        $allowedMethods = ['vodafone_cash', 'instapay', 'bank_transfer'];
        if (! in_array($data['payment_method'], $allowedMethods, true)) {
            throw ValidationException::withMessages([
                'payment_method' => __('طريقة الدفع المحددة غير مدعومة.'),
            ]);
        }

        $imagePath = '';
        if ($data['receipt_image'] instanceof UploadedFile) {
            $imagePath = $data['receipt_image']->store('receipts', 'public');
        } elseif (is_string($data['receipt_image'])) {
            $imagePath = $data['receipt_image'];
        }

        return PaymentReceipt::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_method' => $data['payment_method'],
            'sender_name' => $data['sender_name'] ?? null,
            'sender_account' => $data['sender_account'] ?? null,
            'amount' => (float) $data['amount'],
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'receipt_image_path' => $imagePath,
            'status' => 'pending',
        ]);
    }

    /**
     * Approve a payment receipt and AUTO-GRANT course access to the student.
     */
    public function approveReceipt(PaymentReceipt $receipt, Admin $admin): PaymentReceipt
    {
        return DB::transaction(function () use ($receipt, $admin) {
            $receipt->status = 'approved';
            $receipt->reviewed_by = $admin->id;
            $receipt->reviewed_at = Carbon::now();
            $receipt->rejection_reason = null;
            $receipt->save();

            // Auto-grant access to course
            $user = $receipt->user;
            $course = $receipt->course;

            if ($user && $course) {
                $this->enrollmentService->enroll(
                    user: $user,
                    course: $course,
                    durationDays: $course->access_duration_days ?? 365,
                    adminId: $admin->id
                );
            }

            return $receipt;
        });
    }

    /**
     * Reject a payment receipt with a recorded reason.
     */
    public function rejectReceipt(PaymentReceipt $receipt, Admin $admin, string $reason): PaymentReceipt
    {
        $receipt->status = 'rejected';
        $receipt->reviewed_by = $admin->id;
        $receipt->reviewed_at = Carbon::now();
        $receipt->rejection_reason = $reason;
        $receipt->save();

        return $receipt;
    }
}
