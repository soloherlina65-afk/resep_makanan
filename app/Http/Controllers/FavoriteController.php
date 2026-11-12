<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Recipe $recipe): RedirectResponse
    {
        $type = $request->validate([
            'type' => ['required', 'in:like,favorite'],
        ])['type'];

        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('recipe_id', $recipe->id)
            ->where('type', $type)
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => $request->user()->id,
                'recipe_id' => $recipe->id,
                'type' => $type,
            ]);
        }

        return back();
    }
}
