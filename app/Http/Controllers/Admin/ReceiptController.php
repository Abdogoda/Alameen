<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectReceiptRequest;
use App\Models\Admin;
use App\Models\PaymentReceipt;
use App\Services\Payment\PaymentReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function __construct(
        protected PaymentReceiptService $receiptService
    ) {}

    /**
     * Display listing of manual payment receipts with filter and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = PaymentReceipt::with(['user', 'course', 'reviewer'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('sender_account', 'like', "%{$search}%");
            });
        }

        $receipts = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => PaymentReceipt::count(),
            'pending' => PaymentReceipt::where('status', 'pending')->count(),
            'approved' => PaymentReceipt::where('status', 'approved')->count(),
            'rejected' => PaymentReceipt::where('status', 'rejected')->count(),
        ];

        return view('admin.receipts.index', compact('receipts', 'counts', 'status', 'search'));
    }

    /**
     * Approve receipt and auto-grant student course access.
     */
    public function approve(PaymentReceipt $receipt): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();

        $this->receiptService->approveReceipt($receipt, $admin);

        return back()->with('success', __('تم اعتماد الإيصال وتفعيل وصول الطالب للكورس بنجاح فوري.'));
    }

    /**
     * Reject receipt with mandatory note.
     */
    public function reject(RejectReceiptRequest $request, PaymentReceipt $receipt): RedirectResponse
    {
        $validated = $request->validated();

        /** @var Admin $admin */
        $admin = auth('admin')->user();

        $this->receiptService->rejectReceipt(
            $receipt,
            $admin,
            $validated['rejection_reason']
        );

        return back()->with('warning', __('تم رفض الإيصال وتوثيق سبب الرفض بنجاح.'));
    }
}
