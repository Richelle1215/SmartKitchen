<?php

namespace App\Http\Controllers;

use App\Models\RecipeCollection;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display user's collections
     */
    public function index()
    {
        $collections = auth()->user()->recipeCollections()
                            ->withCount('recipes')
                            ->latest()
                            ->paginate(12);

        return view('collections.index', compact('collections'));
    }

    /**
     * Show collection with recipes
     */
    public function show(RecipeCollection $collection)
    {
        $this->authorize('view', $collection);

        $recipes = $collection->recipes()
                            ->with(['category', 'user', 'ratings'])
                            ->latest()
                            ->paginate(12);

        return view('collections.show', compact('collection', 'recipes'));
    }

    /**
     * Show create collection form
     */
    public function create()
    {
        return view('collections.create');
    }

    /**
     * Store a new collection
     */
    public function store(Request $request)
    {
        $this->authorize('create', RecipeCollection::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
        ]);

        $validated['user_id'] = auth()->id();

        $collection = RecipeCollection::create($validated);

        return redirect()->route('collections.show', $collection)
                       ->with('success', 'Collection created successfully!');
    }

    /**
     * Show edit collection form
     */
    public function edit(RecipeCollection $collection)
    {
        $this->authorize('update', $collection);

        return view('collections.edit', compact('collection'));
    }

    /**
     * Update collection
     */
    public function update(Request $request, RecipeCollection $collection)
    {
        $this->authorize('update', $collection);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
        ]);

        $collection->update($validated);

        return redirect()->route('collections.show', $collection)
                       ->with('success', 'Collection updated successfully!');
    }

    /**
     * Delete collection
     */
    public function destroy(RecipeCollection $collection)
    {
        $this->authorize('delete', $collection);

        $collection->forceDelete();

        return redirect()->route('collections.index')
                       ->with('success', 'Collection deleted successfully!');
    }

    /**
     * Add recipe to collection
     */
    public function addRecipe(Request $request, RecipeCollection $collection)
    {
        $this->authorize('addRecipe', $collection);

        $validated = $request->validate([
            'recipe_id' => ['required', 'exists:recipes,id'],
        ]);

        $recipe = Recipe::findOrFail($validated['recipe_id']);

        // Check if recipe is already in collection
        if ($collection->recipes()->where('recipe_id', $recipe->id)->exists()) {
            return back()->with('error', 'Recipe already in collection!');
        }

        $collection->recipes()->attach($recipe->id);

        return back()->with('success', 'Recipe added to collection!');
    }

    /**
     * Remove recipe from collection
     */
    public function removeRecipe(RecipeCollection $collection, Recipe $recipe)
    {
        $this->authorize('removeRecipe', $collection);

        $collection->recipes()->detach($recipe->id);

        return back()->with('success', 'Recipe removed from collection!');
    }
}
