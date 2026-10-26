<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateController extends Controller
{
    /**
     * Display listing of issued certificates.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = Certificate::with(['user', 'course'])->latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('course', function ($cq) use ($search) {
                        $cq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $certificates = $query->paginate(15)->withQueryString();
        $totalCertificatesCount = Certificate::count();

        return view('admin.certificates.index', compact('certificates', 'totalCertificatesCount', 'search'));
    }
}
