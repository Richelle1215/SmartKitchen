<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Notification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like on a recipe
     */
    public function store(Recipe $recipe)
    {
        $this->authorize('like', $recipe);

        $user = auth()->user();
        
        if ($user->likes()->where('recipe_id', $recipe->id)->exists()) {
            // Unlike
            $user->likes()->detach($recipe->id);
            $recipe->decrementLikeCount();
            $message = 'Like removed!';
        } else {
            // Like
            $user->likes()->attach($recipe->id);
            $recipe->incrementLikeCount();

            // Create notification for recipe owner
            if ($user->id !== $recipe->user_id) {
                Notification::create([
                    'user_id' => $recipe->user_id,
                    'type' => 'like',
                    'title' => $user->name . ' liked your recipe',
                    'message' => $user->name . ' liked your recipe: ' . $recipe->title,
                    'action_url' => route('recipes.show', $recipe),
                    'related_user_id' => $user->id,
                    'related_recipe_id' => $recipe->id,
                ]);
            }

            $message = 'Recipe liked!';
        }

        // Update user statistics
        if ($recipe->user->statistics) {
            $recipe->user->statistics->update([
                'total_likes_received' => $recipe->likedByUsers()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Get likes count for a recipe
     */
    public function count(Recipe $recipe)
    {
        return response()->json([
            'count' => $recipe->like_count,
            'is_liked' => auth()->check() ? $recipe->likedByUsers()->where('user_id', auth()->id())->exists() : false,
        ]);
    }
}
