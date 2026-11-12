<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::middleware('auth')->group(function () {
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::get('/my-recipes', [RecipeController::class, 'mine'])->name('recipes.mine');
    Route::get('/favorites', [RecipeController::class, 'favorites'])->name('recipes.favorites');
    Route::get('/liked-recipes', [RecipeController::class, 'liked'])->name('recipes.liked');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
});
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes/{recipe}/reaction', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('recipes.reaction');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'favorites' => auth()->user()->favorites()->with('recipe.category')->latest()->get(),
        'likedRecipes' => auth()->user()->likedRecipes()->with('recipe.category')->latest()->get(),
        'userRecipes' => auth()->user()->recipes()->with('category')->latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
