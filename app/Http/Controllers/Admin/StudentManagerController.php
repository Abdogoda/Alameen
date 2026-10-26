<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\StudentProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentManagerController extends Controller
{
    public function __construct(
        protected StudentProfileService $profileService
    ) {}

    /**
     * Display listing of students with enrollment stats and location data.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = User::with(['courses', 'videoProgress', 'certificates'])->latest();

        if ($status !== 'all' && in_array($status, ['active', 'suspended'], true)) {
            $query->where('status', $status);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.students.index', compact('students', 'counts', 'status', 'search'));
    }

    /**
     * Toggle active/suspended status of a student account.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->isActive()) {
            $this->profileService->suspend($user);
            $msg = __('تم تعليق حساب الطالب بنجاح.');
        } else {
            $this->profileService->activate($user);
            $msg = __('تم تنشيط حساب الطالب بنجاح.');
        }

        return back()->with('success', $msg);
    }
}
