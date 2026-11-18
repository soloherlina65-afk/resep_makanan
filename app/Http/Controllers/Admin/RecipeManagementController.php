<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Recipe;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RecipeManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'visibility' => ['nullable', Rule::in(['visible', 'hidden'])],
        ]);
        $search = trim($filters['search'] ?? '');

        return view('admin.recipes', [
            'recipes' => Recipe::with(['user', 'category'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->when($search !== '', fn ($query) => $query->where('nama_resep', 'like', '%'.$search.'%'))
                ->when(isset($filters['category_id']), fn ($query) => $query->where('category_id', $filters['category_id']))
                ->when(isset($filters['visibility']), fn ($query) => $query->where(
                    'is_visible',
                    $filters['visibility'] === 'visible',
                ))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'categories' => Category::orderBy('nama_kategori')->get(),
            'search' => $search,
            'categoryId' => $filters['category_id'] ?? '',
            'visibility' => $filters['visibility'] ?? '',
        ]);
    }

    public function toggleVisibility(Recipe $recipe, ActivityLogger $activityLogger): RedirectResponse
    {
        $recipe->update(['is_visible' => ! $recipe->is_visible]);
        $activityLogger->log(
            ($recipe->is_visible ? 'Menampilkan' : 'Menyembunyikan').' resep '.$recipe->nama_resep,
            $recipe,
        );

        return back()->with('success', $recipe->is_visible
            ? 'Resep berhasil ditampilkan.'
            : 'Resep berhasil disembunyikan.');
    }
}
