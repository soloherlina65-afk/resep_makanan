<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        return view('admin.activities', [
            'activities' => ActivityLog::with('user')->latest()->paginate(30),
        ]);
    }
}
