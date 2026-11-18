<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationManagementController;
use App\Http\Controllers\Admin\RecipeManagementController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowController;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;

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
Route::get('/search/suggestions', [HomeController::class, 'suggestions'])->name('recipes.suggestions');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::get('/komunitas', [FollowController::class, 'community'])->name('users.community');
Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.send');
Route::patch('/team-members', [TeamMemberController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('team-members.update');
Route::post('/team-members', [TeamMemberController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('team-members.store');
Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('team-members.destroy');
Route::get('/recipes', [HomeController::class, 'index'])->name('recipes.index');
Route::get('/users/{id}', [FollowController::class, 'show'])->name('users.show');
Route::get('/users/{id}/followers', [FollowController::class, 'followers'])->name('users.followers');
Route::get('/users/{id}/following', [FollowController::class, 'following'])->name('users.following');

Route::middleware('auth')->group(function () {
    Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::delete('/meal-plans/{mealPlan}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');
    Route::post('/users/{id}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{id}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');
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
    Route::post('/recipes/{recipe}/reports', [ReportController::class, 'recipe'])
        ->middleware('throttle:5,1')
        ->name('recipes.reports.store');
    Route::post('/reviews/{review}/reports', [ReportController::class, 'review'])
        ->middleware('throttle:5,1')
        ->name('reviews.reports.store');
    Route::get('/my-comments', [ReviewController::class, 'myComments'])->name('reviews.mine.comments');
    Route::get('/my-ratings', [ReviewController::class, 'myRatings'])->name('reviews.mine.ratings');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::get('/recipes', [RecipeManagementController::class, 'index'])->name('recipes.index');
    Route::patch('/recipes/{recipe}/visibility', [RecipeManagementController::class, 'toggleVisibility'])->name('recipes.visibility');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [UserManagementController::class, 'updateDetails'])->name('users.update');
    Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus'])->name('users.status');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportManagementController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [\App\Http\Controllers\Admin\ReportManagementController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/status', [\App\Http\Controllers\Admin\ReportManagementController::class, 'updateStatus'])->name('reports.status');
    Route::get('/messages', [\App\Http\Controllers\Admin\ContactMessageManagementController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageManagementController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{contactMessage}/status', [\App\Http\Controllers\Admin\ContactMessageManagementController::class, 'updateStatus'])->name('messages.status');
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/notifications', [NotificationManagementController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{notification}', [NotificationManagementController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::patch('/reviews/{review}/visibility', [ReviewController::class, 'toggleVisibility'])->name('reviews.visibility');
});
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes/{recipe}/reaction', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('recipes.reaction');

Route::get('/dashboard', UserDashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
