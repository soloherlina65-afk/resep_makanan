<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Review;
use App\Notifications\ActivityNotification;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function myComments(Request $request): View
    {
        return $this->myReviews($request, true);
    }

    public function myRatings(Request $request): View
    {
        return $this->myReviews($request, false);
    }

    private function myReviews(Request $request, bool $commentsOnly): View
    {
        $reviews = $request->user()->reviews()
            ->with(['recipe.category'])
            ->when($commentsOnly, fn ($query) => $query
                ->whereNotNull('comment')
                ->where('comment', '<>', ''))
            ->latest()
            ->paginate(12);

        return view('reviews.mine', [
            'reviews' => $reviews,
            'commentsOnly' => $commentsOnly,
        ]);
    }

    public function adminIndex(): \Illuminate\Contracts\View\View
    {
        $this->authorize('viewAny', Review::class);

        return view('admin.reviews', [
            'reviews' => Review::with(['user', 'recipe'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function toggleVisibility(Review $review, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('moderate', $review);

        $review->update(['is_visible' => ! $review->is_visible]);
        $activityLogger->log(
            ($review->is_visible ? 'Menampilkan' : 'Menyembunyikan') . ' komentar pada resep #' . $review->recipe_id,
            $review,
        );

        return back()->with('success', $review->is_visible
            ? 'Komentar ditampilkan kembali.'
            : 'Komentar disembunyikan.');
    }

    public function store(Request $request, Recipe $recipe, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating terlebih dahulu.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        $review = $request->user()->reviews()->updateOrCreate(
            ['recipe_id' => $recipe->id],
            $validated,
        );
        $activityLogger->log('Memberi atau memperbarui rating resep ' . $recipe->nama_resep, $review);

        if ($recipe->user_id && $recipe->user_id !== $request->user()->id) {
            $owner = $recipe->user;

            if ($owner->notify_likes) {
                $owner->notify(new ActivityNotification(
                    'review',
                    $request->user()->name . ' memberi rating ' . $validated['rating'] . '/5 pada resep "' . $recipe->nama_resep . '".',
                    $recipe->id,
                ));
            }
        }

        return back()->with('success', 'Rating dan komentar berhasil disimpan.');
    }

    public function update(Request $request, Review $review, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating terlebih dahulu.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        $review->update($validated);
        $activityLogger->log('Memperbarui komentar pada resep #' . $review->recipe_id, $review);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroy(Request $request, Review $review, ActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorize('delete', $review);

        $activityLogger->log('Menghapus komentar pada resep #' . $review->recipe_id, $review);
        $review->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
