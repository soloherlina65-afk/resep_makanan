<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $search = trim((string) $request->query('search', ''));

        $recipesQuery = Recipe::with(['category', 'user'])->latest();

        if ($search !== '') {
            $recipesQuery->where(function ($query) use ($search) {
                $query->where('nama_resep', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama_kategori', 'like', '%' . $search . '%');
                    });
            });
        } else {
            $recipesQuery->take(6);
        }

        $recipes = $recipesQuery->get();

        return view('home', compact('categories', 'recipes', 'search'));
    }

    // Method baru untuk halaman detail resep
    public function show($id)
    {
        // Mencari resep berdasarkan ID beserta data kategori dan bahannya
        $recipe = Recipe::with(['category', 'bahan', 'steps', 'user', 'reviews.user'])->findOrFail($id);

        return view('recipe-detail', compact('recipe'));
    }

    public function about()
    {
        return view('about', [
            'teamMembers' => TeamMember::orderBy('position')->get(),
        ]);
    }

    public function contact()
    {
        return view('contact');
    }
}