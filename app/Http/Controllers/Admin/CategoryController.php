<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories', [
            'categories' => Category::withCount('recipes')->orderBy('nama_kategori')->paginate(30),
        ]);
    }

    public function store(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:categories,nama_kategori'],
        ]);
        $category = Category::create($validated);
        $activityLogger->log('Menambahkan kategori '.$category->nama_kategori, $category);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:categories,nama_kategori,'.$category->id],
        ]);
        $category->update($validated);
        $activityLogger->log('Memperbarui kategori '.$category->nama_kategori, $category);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($category->recipes()->exists()) {
            return back()->with('error', 'Kategori yang masih digunakan resep tidak dapat dihapus.');
        }

        $name = $category->nama_kategori;
        $category->delete();
        $activityLogger->log('Menghapus kategori '.$name);

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
