<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GradeTaskSubmissionRequest;
use App\Models\Admin;
use App\Models\TaskSubmission;
use App\Services\Clinical\ClinicalTaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalTaskController extends Controller
{
    public function __construct(
        protected ClinicalTaskService $taskService
    ) {}

    /**
     * Display listing of student clinical voice tasks.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = TaskSubmission::with(['task.course', 'user', 'grader'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'graded'], true)) {
            $query->where('status', $status);
        }

        $submissions = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => TaskSubmission::count(),
            'pending' => TaskSubmission::where('status', 'pending')->count(),
            'graded' => TaskSubmission::where('status', 'graded')->count(),
        ];

        return view('admin.tasks.index', compact('submissions', 'counts', 'status'));
    }

    /**
     * Grade a voice task submission.
     */
    public function grade(GradeTaskSubmissionRequest $request, TaskSubmission $submission): RedirectResponse
    {
        $validated = $request->validated();

        /** @var Admin $admin */
        $admin = auth('admin')->user();

        $this->taskService->gradeSubmission(
            $submission,
            $admin,
            (int) $validated['score'],
            $validated['clinical_feedback'] ?? null
        );

        return back()->with('success', __('تم تقييم ورصد درجة التسجيل السريري بنجاح.'));
    }
}
