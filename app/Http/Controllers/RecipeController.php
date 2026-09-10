<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;
use App\Models\RecipeInstruction;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes (public view)
     */
    public function index(Request $request)
    {
        $query = Recipe::where('is_published', true);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search by title
        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Sort options
        $sort = $request->sort ?? 'recent';
        match ($sort) {
            'popular' => $query->orderByDesc('view_count'),
            'trending' => $query->orderByDesc('like_count'),
            'rated' => $query->orderByDesc('average_rating'),
            default => $query->orderByDesc('created_at'),
        };

        $recipes = $query->with(['user', 'category', 'ratings'])
                        ->paginate(12);
        
        $categories = RecipeCategory::all();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create()
    {
        $this->authorize('create', Recipe::class);
        
        $categories = RecipeCategory::all();
        return view('recipes.create', compact('categories'));
    }

    /**
     * Store a newly created recipe in storage
     */
    public function store(StoreRecipeRequest $request)
    {
        $this->authorize('create', Recipe::class);

        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['is_published'] = true;  // Ensure this is set
            
            // Handle image upload
            if ($request->hasFile('recipe_image')) {
                $data['recipe_image'] = $request->file('recipe_image')
                    ->storeAs('recipes/images', Str::uuid() . '.' . $request->file('recipe_image')->getClientOriginalExtension(), 'public');
            }

            // Handle video upload
            if ($request->hasFile('recipe_video')) {
                $data['recipe_video'] = $request->file('recipe_video')
                    ->storeAs('recipes/videos', Str::uuid() . '.' . $request->file('recipe_video')->getClientOriginalExtension(), 'public');
            }

            $recipe = Recipe::create($data);

            // Store ingredients
            if ($request->ingredients) {
                foreach ($request->ingredients as $index => $ingredient) {
                    RecipeIngredient::create([
                        'recipe_id' => $recipe->id,
                        'ingredient_name' => $ingredient['name'],
                        'quantity' => $ingredient['quantity'],
                        'unit' => $ingredient['unit'],
                        'order' => $index,
                    ]);
                }
            }

            // Store instructions
            if ($request->instructions) {
                foreach ($request->instructions as $index => $instruction) {
                    RecipeInstruction::create([
                        'recipe_id' => $recipe->id,
                        'step_number' => $index + 1,
                        'instruction' => $instruction['text'],
                        'image' => $instruction['image'] ?? null,
                        'video' => $instruction['video'] ?? null,
                    ]);
                }
            }

            // Create user statistics if not exists
            if (!auth()->user()->statistics) {
                auth()->user()->statistics()->create();
            }
            
            auth()->user()->statistics->increment('total_recipes');
            if ($recipe->is_published) {
                auth()->user()->statistics->increment('total_published_recipes');
            }

            return redirect()->route('recipes.show', $recipe)
                           ->with('success', 'Recipe created successfully!');
        } catch (\Exception $e) {
            \Log::error('Recipe creation error: ' . $e->getMessage());
            return back()->with('error', 'Error creating recipe: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe)
    {
        // Increment view count
        if (!session()->has("recipe_viewed_{$recipe->id}")) {
            $recipe->incrementViewCount();
            session()->put("recipe_viewed_{$recipe->id}", true);
        }

        $recipe->load([
            'user',
            'category',
            'ingredients' => fn($q) => $q->orderBy('order'),
            'instructions' => fn($q) => $q->orderBy('step_number'),
            'ratings',
            'comments' => fn($q) => $q->where('parent_id', null)->latest(),
        ]);

        $rating = auth()->check() ? $recipe->ratings()->where('user_id', auth()->id())->first() : null;
        $isLiked = auth()->check() ? $recipe->likedByUsers()->where('user_id', auth()->id())->exists() : false;
        $isFavorited = auth()->check() ? $recipe->favoritedByUsers()->where('user_id', auth()->id())->exists() : false;

        return view('recipes.show', compact('recipe', 'rating', 'isLiked', 'isFavorited'));
    }

    /**
     * Show the form for editing the specified recipe
     */
    public function edit(Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $recipe->load(['ingredients', 'instructions', 'category']);
        $categories = RecipeCategory::all();

        return view('recipes.edit', compact('recipe', 'categories'));
    }

    /**
     * Update the specified recipe in storage
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('recipe_image')) {
            if ($recipe->recipe_image) {
                Storage::disk('public')->delete($recipe->recipe_image);
            }
            $data['recipe_image'] = $request->file('recipe_image')
                ->storeAs('recipes/images', Str::uuid() . '.' . $request->file('recipe_image')->getClientOriginalExtension(), 'public');
        }

        // Handle video upload
        if ($request->hasFile('recipe_video')) {
            if ($recipe->recipe_video) {
                Storage::disk('public')->delete($recipe->recipe_video);
            }
            $data['recipe_video'] = $request->file('recipe_video')
                ->storeAs('recipes/videos', Str::uuid() . '.' . $request->file('recipe_video')->getClientOriginalExtension(), 'public');
        }

        $recipe->update($data);

        // Update ingredients
        if ($request->ingredients) {
            $recipe->ingredients()->delete();
            foreach ($request->ingredients as $index => $ingredient) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'order' => $index,
                ]);
            }
        }

        // Update instructions
        if ($request->instructions) {
            $recipe->instructions()->delete();
            foreach ($request->instructions as $index => $instruction) {
                RecipeInstruction::create([
                    'recipe_id' => $recipe->id,
                    'step_number' => $index + 1,
                    'instruction' => $instruction['text'],
                    'image' => $instruction['image'] ?? null,
                    'video' => $instruction['video'] ?? null,
                ]);
            }
        }

        return redirect()->route('recipes.show', $recipe)
                       ->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified recipe from storage
     */
    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        // Delete associated files
        if ($recipe->recipe_image) {
            Storage::disk('public')->delete($recipe->recipe_image);
        }
        if ($recipe->recipe_video) {
            Storage::disk('public')->delete($recipe->recipe_video);
        }

        // Delete ingredients and instructions
        $recipe->ingredients()->delete();
        $recipe->instructions()->delete();

        // Update statistics
        if (auth()->user()->statistics) {
            auth()->user()->statistics->decrement('total_recipes');
            if ($recipe->is_published) {
                auth()->user()->statistics->decrement('total_published_recipes');
            }
        }

        $recipe->delete();

        return redirect()->route('recipes.index')
                       ->with('success', 'Recipe deleted successfully!');
    }

    /**
     * Get user's recipes
     */
    public function myRecipes()
    {
        $recipes = auth()->user()->recipes()
                    ->with(['category', 'ratings'])
                    ->latest()
                    ->paginate(12);

        return view('recipes.my-recipes', compact('recipes'));
    }

    /**
     * Get user's published recipes
     */
    public function userRecipes($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $recipes = $user->recipes()
                   ->where('is_published', true)
                   ->with(['category', 'ratings'])
                   ->latest()
                   ->paginate(12);

        return view('recipes.user-recipes', compact('user', 'recipes'));
    }

    /**
     * Search recipes by ingredients
     */
    public function searchByIngredients(Request $request)
    {
        $ingredients = $request->ingredients ?? [];
        
        $query = Recipe::where('is_published', true);

        if (!empty($ingredients)) {
            $query->whereHas('ingredients', function ($q) use ($ingredients) {
                $q->whereIn('ingredient_name', $ingredients);
            }, '>=', count($ingredients));
        }

        $recipes = $query->with(['user', 'category', 'ingredients'])
                        ->paginate(12);

        return view('recipes.search-results', compact('recipes', 'ingredients'));
    }
}
