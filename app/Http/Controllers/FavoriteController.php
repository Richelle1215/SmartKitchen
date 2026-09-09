<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display user's favorite recipes
     */
    public function index()
    {
        $user = auth()->user();
        $favorites = $user->favorites()
                    ->with(['category', 'user', 'ratings'])
                    ->latest()
                    ->paginate(12);

        return view('recipes.favorites', compact('favorites'));
    }

    /**
     * Toggle favorite for a recipe
     */
    public function store(Recipe $recipe)
    {
        $this->authorize('favorite', $recipe);

        $user = auth()->user();

        if ($user->favorites()->where('recipe_id', $recipe->id)->exists()) {
            // Remove from favorites
            $user->favorites()->detach($recipe->id);
            $message = 'Recipe removed from favorites!';
        } else {
            // Add to favorites
            $user->favorites()->attach($recipe->id);
            $message = 'Recipe added to favorites!';
        }

        return back()->with('success', $message);
    }

    /**
     * Remove from favorites
     */
    public function destroy(Recipe $recipe)
    {
        $this->authorize('favorite', $recipe);

        auth()->user()->favorites()->detach($recipe->id);

        return back()->with('success', 'Recipe removed from favorites!');
    }
}
