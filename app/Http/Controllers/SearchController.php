<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeCategory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Advanced search page
     */
    public function index(Request $request)
    {
        $query = Recipe::where('is_published', true);

        // Search by title or description
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by prep time
        if ($request->filled('max_prep_time')) {
            $query->where('prep_time', '<=', $request->max_prep_time);
        }

        // Filter by servings
        if ($request->filled('servings')) {
            $query->where('servings', '>=', $request->servings);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // Search by ingredients
        if ($request->filled('ingredients')) {
            $ingredients = explode(',', $request->ingredients);
            $ingredients = array_map('trim', $ingredients);
            
            $query->whereHas('ingredients', function ($q) use ($ingredients) {
                $q->whereIn('ingredient_name', $ingredients);
            });
        }

        // Sort options
        $sort = $request->sort ?? 'recent';
        match ($sort) {
            'popular' => $query->orderByDesc('view_count'),
            'trending' => $query->orderByDesc('like_count'),
            'rated' => $query->orderByDesc('average_rating'),
            'commented' => $query->orderByDesc('comment_count'),
            default => $query->orderByDesc('created_at'),
        };

        $recipes = $query->with(['user', 'category', 'ratings'])
                        ->paginate(12)
                        ->appends($request->query());

        $categories = RecipeCategory::all();

        return view('search.index', compact('recipes', 'categories'));
    }

    /**
     * Get personalized recommendations (for authenticated users)
     */
    public function recommendations()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Get recipes the user hasn't viewed or rated
        $viewedRecipeIds = $user->recipes()
                                ->pluck('id')
                                ->merge($user->ratings()->pluck('recipe_id'))
                                ->merge($user->likes()->pluck('recipe_id'))
                                ->unique()
                                ->toArray();

        // Get user's favorite categories from their favorites
        $favoriteCategories = $user->favorites()
                                   ->with('category')
                                   ->get()
                                   ->pluck('category_id')
                                   ->unique()
                                   ->toArray();

        // Build recommendations
        $query = Recipe::where('is_published', true)
                      ->whereNotIn('id', $viewedRecipeIds);

        // Prioritize recipes from favorite categories
        if (!empty($favoriteCategories)) {
            $recommended = $query->whereIn('category_id', $favoriteCategories)
                                ->orderByDesc('average_rating')
                                ->orderByDesc('like_count')
                                ->with(['user', 'category', 'ratings'])
                                ->limit(6)
                                ->get();

            // If less than 6, add more from other categories
            if ($recommended->count() < 6) {
                $needed = 6 - $recommended->count();
                $moreRecipes = Recipe::where('is_published', true)
                                     ->whereNotIn('id', $viewedRecipeIds)
                                     ->whereNotIn('id', $recommended->pluck('id')->toArray())
                                     ->whereNotIn('category_id', $favoriteCategories)
                                     ->orderByDesc('average_rating')
                                     ->with(['user', 'category', 'ratings'])
                                     ->limit($needed)
                                     ->get();

                $recommended = $recommended->merge($moreRecipes);
            }
        } else {
            // No favorites, recommend top rated recipes
            $recommended = $query->orderByDesc('average_rating')
                                ->orderByDesc('like_count')
                                ->with(['user', 'category', 'ratings'])
                                ->limit(6)
                                ->get();
        }

        return view('search.recommendations', compact('recommended'));
    }

    /**
     * Search by ingredients endpoint
     */
    public function byIngredients(Request $request)
    {
        $ingredients = $request->ingredients ?? [];

        if (empty($ingredients)) {
            return redirect()->route('search.index')->with('info', 'Please provide ingredients to search.');
        }

        $query = Recipe::where('is_published', true);

        $matchCount = $request->match_type ?? 'any'; // 'any' or 'all'

        if ($matchCount === 'all') {
            // Find recipes with ALL ingredients
            foreach ($ingredients as $ingredient) {
                $query->whereHas('ingredients', function ($q) use ($ingredient) {
                    $q->where('ingredient_name', 'like', "%{$ingredient}%");
                });
            }
        } else {
            // Find recipes with ANY ingredient
            $query->whereHas('ingredients', function ($q) use ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $q->orWhere('ingredient_name', 'like', "%{$ingredient}%");
                }
            });
        }

        $recipes = $query->with(['user', 'category', 'ingredients', 'ratings'])
                        ->orderByDesc('average_rating')
                        ->paginate(12)
                        ->appends($request->query());

        return view('search.by-ingredients', compact('recipes', 'ingredients', 'matchCount'));
    }

    /**
     * Get trending recipes
     */
    public function trending()
    {
        $recipes = Recipe::where('is_published', true)
                        ->orderByDesc('view_count')
                        ->orderByDesc('like_count')
                        ->with(['user', 'category', 'ratings'])
                        ->paginate(12);

        return view('search.trending', compact('recipes'));
    }

    /**
     * Get popular recipes (by rating)
     */
    public function popular()
    {
        $recipes = Recipe::where('is_published', true)
                        ->orderByDesc('average_rating')
                        ->orderByDesc('rating_count')
                        ->with(['user', 'category', 'ratings'])
                        ->paginate(12);

        return view('search.popular', compact('recipes'));
    }

    /**
     * Get recently added recipes
     */
    public function recent()
    {
        $recipes = Recipe::where('is_published', true)
                        ->latest()
                        ->with(['user', 'category', 'ratings'])
                        ->paginate(12);

        return view('search.recent', compact('recipes'));
    }

    /**
     * Get category recipes
     */
    public function category(RecipeCategory $category)
    {
        $recipes = Recipe::where('is_published', true)
                        ->where('category_id', $category->id)
                        ->orderByDesc('average_rating')
                        ->with(['user', 'category', 'ratings'])
                        ->paginate(12);

        return view('search.category', compact('category', 'recipes'));
    }
}
