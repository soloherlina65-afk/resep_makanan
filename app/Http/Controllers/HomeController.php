<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('recipes')
            ->orderBy('nama_kategori')
            ->get();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'max_cook_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'difficulty' => ['nullable', 'in:Mudah,Sedang,Sulit'],
            'sort' => ['nullable', 'in:latest,rating'],
        ]);
        $search = trim($filters['search'] ?? '');
        $categoryId = isset($filters['category_id']) ? (int) $filters['category_id'] : null;
        $maxCookTime = isset($filters['max_cook_time']) ? (int) $filters['max_cook_time'] : null;
        $difficulty = $filters['difficulty'] ?? '';
        $sort = $filters['sort'] ?? 'latest';
        $hasFilters = $search !== '' || $categoryId !== null || $maxCookTime !== null
            || $difficulty !== '' || $sort === 'rating';

        $recipesQuery = Recipe::with(['category', 'user'])
            ->withAvg('visibleReviews', 'rating');

        if ($search !== '') {
            $recipesQuery->where(function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama_kategori', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('bahan', function ($ingredientQuery) use ($search) {
                        $ingredientQuery->where('nama_bahan', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($categoryId !== null) {
            $recipesQuery->where('category_id', $categoryId);
        }

        if ($maxCookTime !== null) {
            $recipesQuery->where('waktu_masak', '<=', $maxCookTime);
        }

        if ($difficulty !== '') {
            $recipesQuery->where('tingkat_kesulitan', $difficulty);
        }

        if ($sort === 'rating') {
            $recipesQuery->orderByDesc('visible_reviews_avg_rating')->latest();
        } else {
            $recipesQuery->latest();
        }

        $recipes = $recipesQuery
            ->paginate($hasFilters ? 9 : 6)
            ->withQueryString();

        return view('home', compact(
            'categories',
            'recipes',
            'search',
            'categoryId',
            'maxCookTime',
            'difficulty',
            'sort',
            'hasFilters',
        ));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);
        $search = trim($validated['q']);

        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $recipes = Recipe::with('category')
            ->where(function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama_kategori', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('bahan', function ($ingredientQuery) use ($search) {
                        $ingredientQuery->where('nama_bahan', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->limit(6)
            ->get(['id', 'category_id', 'nama_resep', 'foto']);

        return response()->json($recipes->map(function (Recipe $recipe) {
            $imagePath = $recipe->foto;

            if (! $imagePath) {
                $knownImages = [
                    'ayam kecap' => 'images/ayamkecap.png',
                    'udang saus padang' => 'images/udangsauspadang.png',
                    'nasi goreng spesial' => 'images/nasigorengspesial.png',
                    'pancake klasik madu' => 'images/pancakeklasikmadu.png',
                    'mocalatte spesial' => 'images/mocalatte.png',
                    'waterlemon' => 'images/waterlemon.png',
                ];
                $imagePath = $knownImages[mb_strtolower(trim($recipe->nama_resep))] ?? null;
            }

            $image = $imagePath
                ? (str_starts_with($imagePath, 'images/')
                    ? asset($imagePath)
                    : asset('storage/' . $imagePath))
                : null;

            return [
                'name' => $recipe->nama_resep,
                'category' => $recipe->category?->nama_kategori ?? 'Umum',
                'image' => $image,
                'url' => route('recipes.show', $recipe->id),
            ];
        }));
    }

    // Method baru untuk halaman detail resep
    public function show($id)
    {
        // Mencari resep berdasarkan ID beserta data kategori dan bahannya
        $recipe = Recipe::with(['category', 'bahan', 'steps', 'user', 'visibleReviews.user'])->findOrFail($id);

        return view('recipe-detail', compact('recipe'));
    }

    public function about()
    {
        return view('about', [
            'teamMembers' => TeamMember::orderBy('position')->get(),
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'contactEmail' => config('mail.contact_to'),
        ]);
    }
}