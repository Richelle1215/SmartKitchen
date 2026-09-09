<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\PantryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Search and Discovery (public)
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/by-ingredients', [SearchController::class, 'byIngredients'])->name('search.by-ingredients');
Route::get('/search/trending', [SearchController::class, 'trending'])->name('search.trending');
Route::get('/search/popular', [SearchController::class, 'popular'])->name('search.popular');
Route::get('/search/recent', [SearchController::class, 'recent'])->name('search.recent');
Route::get('/categories/{category}', [SearchController::class, 'category'])->name('categories.show');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/user/{userId}', [RecipeController::class, 'userRecipes'])->name('recipes.user-recipes');
Route::get('/recipes/search/ingredients', [RecipeController::class, 'searchByIngredients'])->name('recipes.search-by-ingredients');

// Feature pages
Route::view('/pantry', 'features.pantry')->name('pantry');
Route::view('/planner', 'features.planner')->name('planner');
Route::view('/assistant', 'features.assistant')->name('assistant');
Route::view('/community', 'features.community')->name('community');
Route::view('/admin', 'features.admin')->name('admin');

// Dashboard
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

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recipe management (for authenticated users)
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my-recipes');

    // Community features - Ratings
    Route::post('/recipes/{recipe}/rate', [RatingController::class, 'store'])->name('recipes.rate');
    Route::delete('/recipes/{recipe}/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    // Community features - Comments
    Route::post('/recipes/{recipe}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Community features - Likes
    Route::post('/recipes/{recipe}/like', [LikeController::class, 'store'])->name('recipes.like');
    Route::get('/recipes/{recipe}/likes/count', [LikeController::class, 'count'])->name('recipes.likes-count');

    // Community features - Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'store'])->name('recipes.favorite');
    Route::delete('/recipes/{recipe}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Community features - Follow
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('users.following');
    Route::get('/users/{user}/follow-status', [FollowController::class, 'status'])->name('users.follow-status');

    // Recommendations (personalized discovery)
    Route::get('/recommendations', [SearchController::class, 'recommendations'])->name('search.recommendations');

    // AI Cooking Assistant
    Route::get('/ai-assistant', [AIAssistantController::class, 'index'])->name('ai.assistant');
    Route::post('/ai/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/substitutions', [AIAssistantController::class, 'getSubstitutions'])->name('ai.substitutions');
    Route::post('/ai/tips', [AIAssistantController::class, 'getTips'])->name('ai.tips');
    Route::post('/ai/nutrition', [AIAssistantController::class, 'getNutritionInfo'])->name('ai.nutrition');
    Route::get('/ai/suggest-recipes', [AIAssistantController::class, 'suggestRecipes'])->name('ai.suggest-recipes');

    // Smart Pantry
    Route::get('/pantry', [PantryController::class, 'index'])->name('pantry.index');
    Route::post('/pantry', [PantryController::class, 'store'])->name('pantry.store');
    Route::patch('/pantry/{item}', [PantryController::class, 'update'])->name('pantry.update');
    Route::delete('/pantry/{item}', [PantryController::class, 'destroy'])->name('pantry.destroy');
    Route::patch('/pantry/{item}/quantity', [PantryController::class, 'updateQuantity'])->name('pantry.update-quantity');
    Route::get('/pantry/low-stock', [PantryController::class, 'lowStock'])->name('pantry.low-stock');
    Route::get('/pantry/expired', [PantryController::class, 'expired'])->name('pantry.expired');
    Route::get('/pantry/suggest-recipes', [PantryController::class, 'suggestRecipes'])->name('pantry.suggest-recipes');
});

require __DIR__.'/auth.php';
