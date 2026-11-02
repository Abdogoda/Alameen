<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Certificates are managed exclusively on each student's profile page.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('admin.students.index');
    }
}
