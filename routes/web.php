<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/resep/{id}', [RecipeController::class, 'show'])
    ->name('recipes.show');
