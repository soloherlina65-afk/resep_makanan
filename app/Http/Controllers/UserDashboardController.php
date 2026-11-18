<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'user' => $user,
            'favorites' => $user->favorites()->with('recipe.category')->latest()->limit(6)->get(),
            'likedRecipes' => $user->likedRecipes()->with('recipe.category')->latest()->limit(6)->get(),
            'userRecipes' => $user->recipes()->with('category')->latest()->limit(6)->get(),
            'favoritesCount' => $user->favorites()->count(),
            'likedRecipesCount' => $user->likedRecipes()->count(),
            'userRecipesCount' => $user->recipes()->count(),
            'commentsCount' => $user->reviews()
                ->whereNotNull('comment')
                ->where('comment', '<>', '')
                ->count(),
            'ratingsCount' => $user->reviews()->count(),
            'likesReceivedCount' => Favorite::query()
                ->where('type', 'like')
                ->whereHas('recipe', fn ($query) => $query->where('user_id', $user->id))
                ->count(),
            'latestComments' => $user->reviews()
                ->with('recipe')
                ->whereNotNull('comment')
                ->where('comment', '<>', '')
                ->latest()
                ->limit(5)
                ->get(),
            'followingUsers' => $user->following()->withCount('recipes')->orderBy('name')->limit(5)->get(),
            'followingCount' => $user->following()->count(),
            'unreadNotificationsCount' => $user->unreadNotifications()->count(),
            'latestNotifications' => $user->notifications()->latest()->limit(5)->get(),
        ]);
    }
}
