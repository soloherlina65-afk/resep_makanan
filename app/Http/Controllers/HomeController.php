<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $recipes = Recipe::with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('categories', 'recipes'));
    }
}