<?php

declare(strict_types=1);

namespace App\Http\Requests\Student;

/**
 * Form request for submitting payment receipts.
 * Maintains compatibility with routes or controllers referencing SubmitPaymentReceiptRequest.
 */
class SubmitPaymentReceiptRequest extends SubmitReceiptRequest
{
}
