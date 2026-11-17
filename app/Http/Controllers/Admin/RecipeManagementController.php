<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\View\View;

class RecipeManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.recipes', [
            'recipes' => Recipe::with(['user', 'category'])->latest()->paginate(20),
        ]);
    }
}
