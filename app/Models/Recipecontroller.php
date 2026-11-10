<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    // Menampilkan daftar semua resep
    public function index()
    {
        $recipes = Recipe::with('category')
            ->latest()
            ->get();

        return view('recipes.index', compact('recipes'));
    }

    // Menampilkan detail resep berdasarkan ID
    public function show($id)
    {
        // Mengambil resep beserta kategori, bahan, dan langkah memasak
        $recipe = Recipe::with([
            'category',
            'bahan',
            'steps',
        ])->findOrFail($id);

       return view('recipes.detail', compact('recipe'));
        
    }
}
