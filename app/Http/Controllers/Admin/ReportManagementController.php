<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in([
                Report::STATUS_NEW,
                Report::STATUS_REVIEWING,
                Report::STATUS_RESOLVED,
                Report::STATUS_DISMISSED,
            ])],
        ]);
        $reports = Report::with(['user', 'reportable'])
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'status' => $filters['status'] ?? '',
        ]);
    }

    public function show(Report $report): View
    {
        $report->load(['user', 'reportable']);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Report::STATUS_NEW,
                Report::STATUS_REVIEWING,
                Report::STATUS_RESOLVED,
                Report::STATUS_DISMISSED,
            ])],
        ]);
        $report->update($validated);
        $activityLogger->log('Memperbarui status laporan #'.$report->id, $report);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
