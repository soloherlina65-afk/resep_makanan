<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);
        $search = trim($filters['search'] ?? '');
        $categoryId = isset($filters['category_id']) ? (int) $filters['category_id'] : null;
        $recipesQuery = Recipe::with(['user', 'category'])->latest();

        if ($search !== '') {
            $recipesQuery->where(function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%'.$search.'%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama_kategori', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($categoryId !== null) {
            $recipesQuery->where('category_id', $categoryId);
        }

        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'recipesCount' => Recipe::count(),
            'categoriesCount' => Category::count(),
            'reviewsCount' => Review::count(),
            'pendingReviewsCount' => Review::where('is_visible', false)->count(),
            'favoritesCount' => Favorite::count(),
            'mealPlansCount' => MealPlan::count(),
            'followsCount' => DB::table('follows')->count(),
            'latestActivities' => ActivityLog::with('user')->latest()->limit(8)->get(),
            'latestRecipes' => $recipesQuery->paginate(8)->withQueryString(),
            'categories' => Category::orderBy('nama_kategori')->get(),
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }
}
