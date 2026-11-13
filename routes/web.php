<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeamMemberController;
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
Route::patch('/team-members', [TeamMemberController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('team-members.update');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::get('/my-recipes', [RecipeController::class, 'mine'])->name('recipes.mine');
    Route::get('/favorites', [RecipeController::class, 'favorites'])->name('recipes.favorites');
    Route::get('/liked-recipes', [RecipeController::class, 'liked'])->name('recipes.liked');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('recipes.reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/visibility', [ReviewController::class, 'toggleVisibility'])->name('reviews.visibility');
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
        'unreadNotificationsCount' => auth()->user()->unreadNotifications()->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
