<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::resource('recipes', RecipeController::class)->middleware('auth')->except(['index']);
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
