<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function show($id)
    {
        $recipe = Recipe::with([
            'category',
            'ingredients',
            'steps'
        ])->findOrFail($id);

        return view('recipes.detail', compact('recipe'));
    }
}
