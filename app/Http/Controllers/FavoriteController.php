<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Notifications\ActivityNotification;
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

            if ($recipe->user_id && $recipe->user_id !== $request->user()->id) {
                $owner = $recipe->user;
                $preference = $type === 'like' ? 'notify_likes' : 'notify_favorites';

                if ($owner->{$preference}) {
                    $activity = $type === 'like' ? 'like' : 'favorite';
                    $verb = $type === 'like' ? 'menyukai' : 'menambahkan ke favorit';
                    $owner->notify(new ActivityNotification(
                        $activity,
                        $request->user()->name . ' ' . $verb . ' resep "' . $recipe->nama_resep . '".',
                        $recipe->id,
                    ));
                }
            }
        }

        return back();
    }
}
