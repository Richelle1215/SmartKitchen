<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $recipes = $user->recipes()->latest()->get();
    $stats = [
        'total_recipes' => $recipes->count(),
        'total_servings' => $recipes->sum('servings'),
        'avg_prep_time' => $recipes->count() ? (int) round($recipes->avg('prep_time')) : 0,
    ];

    return view('dashboard', compact('recipes', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('recipes', RecipeController::class)->except(['index']);
});

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

require __DIR__.'/auth.php';
