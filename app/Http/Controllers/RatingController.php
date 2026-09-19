<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Rating;
use App\Models\Notification;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Store or update a rating for a recipe
     */
    public function store(Request $request, Recipe $recipe)
    {
        $this->authorize('rate', $recipe);

        $validated = $request->validate([
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'recipe_id' => $recipe->id,
            ],
            $validated
        );

        // Update recipe rating statistics
        $recipe->updateAverageRating();

        // Create notification for recipe owner
        if (auth()->id() !== $recipe->user_id) {
            Notification::create([
                'user_id' => $recipe->user_id,
                'type' => 'rating',
                'title' => auth()->user()->name . ' rated your recipe',
                'message' => auth()->user()->name . ' gave ' . $validated['stars'] . ' stars to your recipe: ' . $recipe->title,
                'action_url' => route('recipes.show', $recipe),
                'related_user_id' => auth()->id(),
                'related_recipe_id' => $recipe->id,
            ]);
        }

        // Update user statistics
        $stats = $recipe->user->statistics()->firstOrCreate([
            'user_id' => $recipe->user_id,
        ]);
        $stats->update([
            'total_ratings_received' => $recipe->ratings()->count(),
            'average_rating' => $recipe->average_rating,
        ]);

        return redirect()->route('recipes.show', $recipe)
                       ->with('success', 'Rating submitted successfully!');
    }

    /**
     * Delete a rating
     */
    public function destroy(Recipe $recipe, Rating $rating)
    {
        $this->authorize('delete', $rating);

        $rating->delete();

        // Update recipe rating statistics
        $recipe->updateAverageRating();

        // Update user statistics
        if ($recipe->user->statistics) {
            $recipe->user->statistics->update([
                'total_ratings_received' => $recipe->ratings()->count(),
                'average_rating' => $recipe->average_rating,
            ]);
        }

        return back()->with('success', 'Rating removed successfully!');
    }
}
