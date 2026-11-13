<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating terlebih dahulu.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        $request->user()->reviews()->updateOrCreate(
            ['recipe_id' => $recipe->id],
            $validated,
        );

        return back()->with('success', 'Rating dan komentar berhasil disimpan.');
    }
}
