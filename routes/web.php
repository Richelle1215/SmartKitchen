<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Models\Recipe;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\PantryController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\CostCalculatorController;
use App\Http\Controllers\VideoShortController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $recipes = Recipe::where('is_published', true)
        ->with(['user', 'category', 'ratings'])
        ->latest()
        ->get();

    return view('home', compact('recipes'));
})->name('home');

// Search and Discovery (public)
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/by-ingredients', [SearchController::class, 'byIngredients'])->name('search.by-ingredients');
Route::get('/search/trending', [SearchController::class, 'trending'])->name('search.trending');
Route::get('/search/popular', [SearchController::class, 'popular'])->name('search.popular');
Route::get('/search/recent', [SearchController::class, 'recent'])->name('search.recent');

// Categories
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
Route::get('/recipes/search/ingredients', [RecipeController::class, 'searchByIngredients'])->name('recipes.search-by-ingredients');
Route::get('/recipes/user/{userId}', [RecipeController::class, 'userRecipes'])->name('recipes.user-recipes');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

// Feature pages
Route::view('/planner', 'features.planner')->name('planner');
Route::view('/assistant', 'features.assistant')->name('assistant');
Route::view('/community', 'features.community')->name('community');
Route::view('/admin', 'features.admin')->name('admin');
Route::get('/pantry', [PantryController::class, 'index'])->name('pantry.index');

// Dashboard - Redirect to Profile (My Profile Page)
Route::get('/dashboard', function () {
    return redirect()->route('profile.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// My Profile - Shows user profile + my recipes + manage recipes
Route::get('/profile/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('profile.dashboard');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-picture', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recipe management (for authenticated users)
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

    // Community features - Collections
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::get('/collections/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');
    Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
    Route::patch('/collections/{collection}', [CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
    Route::post('/collections/{collection}/recipes', [CollectionController::class, 'addRecipe'])->name('collections.add-recipe');
    Route::delete('/collections/{collection}/recipes/{recipe}', [CollectionController::class, 'removeRecipe'])->name('collections.remove-recipe');

    // Community features - Follow
    Route::get('/users/{user}', [ProfileController::class, 'publicProfile'])->name('users.profile');
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('users.following');
    Route::get('/users/{user}/follow-status', [FollowController::class, 'status'])->name('users.follow-status');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Recommendations (personalized discovery)
    Route::get('/recommendations', [SearchController::class, 'recommendations'])->name('search.recommendations');

    // Smart Pantry
    Route::post('/pantry', [PantryController::class, 'store'])->name('pantry.store');
    Route::patch('/pantry/{item}', [PantryController::class, 'update'])->name('pantry.update');
    Route::delete('/pantry/{item}', [PantryController::class, 'destroy'])->name('pantry.destroy');
    Route::patch('/pantry/{item}/quantity', [PantryController::class, 'updateQuantity'])->name('pantry.update-quantity');
    Route::get('/pantry/low-stock', [PantryController::class, 'lowStock'])->name('pantry.low-stock');
    Route::get('/pantry/expired', [PantryController::class, 'expired'])->name('pantry.expired');
    Route::get('/pantry/suggest-recipes', [PantryController::class, 'suggestRecipes'])->name('pantry.suggest-recipes');

    // Meal Planner
    Route::get('/meal-planner', [MealPlanController::class, 'planner'])->name('meal-planner');
    Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
    Route::get('/meal-plans/create', [MealPlanController::class, 'create'])->name('meal-plans.create');
    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::get('/meal-plans/{mealPlan}', [MealPlanController::class, 'show'])->name('meal-plans.show');
    Route::get('/meal-plans/{mealPlan}/edit', [MealPlanController::class, 'edit'])->name('meal-plans.edit');
    Route::patch('/meal-plans/{mealPlan}', [MealPlanController::class, 'update'])->name('meal-plans.update');
    Route::delete('/meal-plans/{mealPlan}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');
    Route::post('/meal-plans/{mealPlan}/items', [MealPlanController::class, 'addItem'])->name('meal-plans.add-item');
    Route::patch('/meal-plan-items/{item}', [MealPlanController::class, 'updateMealItem'])->name('meal-plans.update-item');
    Route::delete('/meal-plan-items/{item}', [MealPlanController::class, 'removeItem'])->name('meal-plans.remove-item');
    Route::get('/meal-plans/weekly/suggestion', [MealPlanController::class, 'generateWeekly'])->name('meal-plans.weekly-suggestion');
    Route::get('/meal-plans/{mealPlan}/shopping-list', [MealPlanController::class, 'shoppingList'])->name('meal-plans.shopping-list');

    // Cost Calculator
    Route::get('/cost-calculator', [CostCalculatorController::class, 'index'])->name('cost-calculator.index');
    Route::post('/cost-calculator/calculate', [CostCalculatorController::class, 'calculate'])->name('cost-calculator.calculate');
    Route::get('/recipes/{recipe}/cost', [CostCalculatorController::class, 'recipeCost'])->name('recipes.cost');

    Route::get('/nutrition-analysis', [\App\Http\Controllers\NutritionController::class, 'index'])->name('nutrition-analysis.index');
    Route::post('/nutrition-analysis', [\App\Http\Controllers\NutritionController::class, 'analyze'])->name('nutrition-analysis.analyze');

    // Video Shorts
    Route::get('/shorts', [VideoShortController::class, 'index'])->name('video-shorts.index');
    Route::get('/shorts/create', [VideoShortController::class, 'create'])->name('video-shorts.create');
    Route::post('/shorts', [VideoShortController::class, 'store'])->name('video-shorts.store');
    Route::get('/shorts/{short}', [VideoShortController::class, 'show'])->name('video-shorts.show');
    Route::delete('/shorts/{short}', [VideoShortController::class, 'destroy'])->name('video-shorts.destroy');
    Route::post('/shorts/{short}/like', [VideoShortController::class, 'toggleLike'])->name('video-shorts.like');
    Route::post('/shorts/{short}/comment', [VideoShortController::class, 'addComment'])->name('video-shorts.comment');
    Route::get('/shorts/trending/all', [VideoShortController::class, 'trending'])->name('video-shorts.trending');
    Route::get('/shorts/search', [VideoShortController::class, 'search'])->name('video-shorts.search');

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::post('/messages/{user}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');

    // Achievements
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/achievements/leaderboard', [AchievementController::class, 'leaderboard'])->name('achievements.leaderboard');
    Route::get('/achievements/all', [AchievementController::class, 'all'])->name('achievements.all');
    Route::post('/achievements/award', [AchievementController::class, 'award'])->name('achievements.award');
    Route::get('/achievements/stats', [AchievementController::class, 'stats'])->name('achievements.stats');

    // Reports
    Route::get('/report/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/report', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/report/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
});

// AI Cooking Assistant (public page, guest-limited mode, registered full)
Route::get('/ai-assistant', [AIAssistantController::class, 'index'])->name('ai.assistant');
Route::post('/ai/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');
Route::post('/ai/substitutions', [AIAssistantController::class, 'getSubstitutions'])->name('ai.substitutions');
Route::post('/ai/tips', [AIAssistantController::class, 'getTips'])->name('ai.tips');
Route::post('/ai/nutrition', [AIAssistantController::class, 'getNutritionInfo'])->name('ai.nutrition');
Route::get('/ai/suggest-recipes', [AIAssistantController::class, 'suggestRecipes'])->name('ai.suggest-recipes');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.user-details');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.suspend-user');
    Route::post('/users/{user}/unsuspend', [AdminController::class, 'unsuspendUser'])->name('admin.unsuspend-user');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    
    // Recipe Management
    Route::get('/recipes', [AdminController::class, 'recipes'])->name('admin.recipes');
    Route::get('/recipes/{recipe}', [AdminController::class, 'showRecipe'])->name('admin.recipe-details');
    Route::post('/recipes/{recipe}/toggle', [AdminController::class, 'toggleRecipeStatus'])->name('admin.toggle-recipe');
    Route::delete('/recipes/{recipe}', [AdminController::class, 'deleteRecipe'])->name('admin.delete-recipe');
    
    // Reports Management
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/{report}', [AdminController::class, 'showReport'])->name('admin.report-details');
    Route::post('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('admin.resolve-report');
    
    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    
    // Activity Logs
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
});

require __DIR__.'/auth.php';
