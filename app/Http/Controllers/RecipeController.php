<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function mine(Request $request)
    {
        return view('recipes.mine', [
            'recipes' => $request->user()->recipes()->with('category')->latest()->get(),
        ]);
    }

    public function favorites(Request $request)
    {
        $categoryId = $request->integer('category');
        $search = trim((string) $request->input('search', ''));
        $favoritesQuery = $request->user()->favorites()->with('recipe.category')->latest();

        if ($categoryId) {
            $favoritesQuery->whereHas('recipe', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
        if ($search !== '') {
            $favoritesQuery->whereHas('recipe', function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        return view('recipes.favorites', [
            'favorites' => $favoritesQuery->get(),
            'categories' => Category::orderBy('nama_kategori')->get(),
            'selectedCategory' => $categoryId,
            'search' => $search,
        ]);
    }

    public function liked(Request $request)
    {
        $categoryId = $request->integer('category');
        $search = trim((string) $request->input('search', ''));
        $likedQuery = $request->user()->likedRecipes()->with('recipe.category')->latest();

        if ($categoryId) {
            $likedQuery->whereHas('recipe', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
        if ($search !== '') {
            $likedQuery->whereHas('recipe', function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        return view('recipes.liked', [
            'likedRecipes' => $likedQuery->get(),
            'categories' => Category::orderBy('nama_kategori')->get(),
            'selectedCategory' => $categoryId,
            'search' => $search,
        ]);
    }

    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        abort_unless($recipe->user_id === $request->user()->id, 403);

        if ($recipe->foto && ! str_starts_with($recipe->foto, 'images/')) {
            Storage::disk('public')->delete($recipe->foto);
        }

        $recipe->delete();

        return redirect()->route('recipes.mine')
            ->with('success', 'Resep berhasil dihapus.');
    }

    public function create()
    {
        return view('recipes.create', [
            'categories' => Category::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_resep' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'deskripsi' => ['required', 'string'],
            'waktu_masak' => ['required', 'integer', 'min:1', 'max:1440'],
            'porsi' => ['required', 'integer', 'min:1', 'max:100'],
            'tingkat_kesulitan' => ['required', 'in:Mudah,Sedang,Sulit'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bahan' => ['required', 'array', 'min:1'],
            'bahan.*' => ['required', 'string', 'max:255'],
            'langkah' => ['required', 'array', 'min:1'],
            'langkah.*' => ['required', 'string', 'max:1000'],
        ], [
            'bahan.required' => 'Tambahkan minimal satu bahan.',
            'bahan.*.required' => 'Nama bahan tidak boleh kosong.',
            'langkah.required' => 'Tambahkan minimal satu langkah.',
            'langkah.*.required' => 'Deskripsi langkah tidak boleh kosong.',
        ]);

        $recipe = DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('recipes', 'public');
            }

            $recipe = Recipe::create([
                'user_id' => $request->user()->id,
                'category_id' => $validated['category_id'],
                'nama_resep' => $validated['nama_resep'],
                'deskripsi' => $validated['deskripsi'],
                'foto' => $validated['foto'] ?? null,
                'waktu_masak' => $validated['waktu_masak'],
                'porsi' => $validated['porsi'],
                'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
            ]);

            foreach ($validated['bahan'] as $ingredient) {
                $recipe->bahan()->create(['nama_bahan' => $ingredient]);
            }

            foreach ($validated['langkah'] as $order => $step) {
                $recipe->steps()->create([
                    'urutan' => $order + 1,
                    'deskripsi' => $step,
                ]);
            }

            return $recipe;
        });

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Resep berhasil diposting.');
    }

    /**
     * Menampilkan daftar semua resep.
     */
    public function index()
    {
        $recipes = Recipe::with('category')->latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Menampilkan detail satu resep berdasarkan ID.
     */
    public function show($id)
    {
        // Mengambil resep beserta relasi kategori, bahan (ingredients), dan langkah (steps)
      $recipe = Recipe::with(['category', 'bahan', 'steps', 'user'])->findOrFail($id);
        // Mengarahkan ke view detail (sesuaikan path foldernya, misal: recipes.detail atau recipe-detail)
        return view('recipe-detail', compact('recipe'));
    }
}