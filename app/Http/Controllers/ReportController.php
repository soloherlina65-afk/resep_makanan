<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Report;
use App\Models\Review;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ReportController extends Controller
{
    public function recipe(Request $request, Recipe $recipe, ActivityLogger $activityLogger): RedirectResponse
    {
        return $this->store($request, $recipe, $activityLogger);
    }

    public function review(Request $request, Review $review, ActivityLogger $activityLogger): RedirectResponse
    {
        return $this->store($request, $review, $activityLogger);
    }

    private function store(Request $request, Model $target, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($target->user_id === $request->user()->id) {
            return back()->with('error', 'Kamu tidak dapat melaporkan konten milik sendiri.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'in:'.implode(',', array_keys(Report::REASONS))],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $alreadyReported = $target->reports()
            ->where('user_id', $request->user()->id)
            ->whereIn('status', [Report::STATUS_NEW, Report::STATUS_REVIEWING])
            ->exists();
        if ($alreadyReported) {
            return back()->with('error', 'Laporan untuk konten ini sedang ditinjau.');
        }

        $report = $target->reports()->create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);
        $activityLogger->log('Mengirim laporan konten.', $report);

        return back()->with('success', 'Laporan berhasil dikirim untuk ditinjau.');
    }
}
