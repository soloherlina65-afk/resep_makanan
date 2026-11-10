<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
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
      $recipe = Recipe::with(['category', 'bahan', 'steps'])->findOrFail($id);
        // Mengarahkan ke view detail (sesuaikan path foldernya, misal: recipes.detail atau recipe-detail)
        return view('recipe-detail', compact('recipe'));
    }
}