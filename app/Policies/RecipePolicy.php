<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Recipe $recipe): bool
    {
        // Anyone can view published recipes
        if ($recipe->is_published) {
            return true;
        }

        // Only owner can view unpublished recipes
        return $user?->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isRegistered();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can like the recipe.
     */
    public function like(User $user, Recipe $recipe): bool
    {
        // Only registered users can like, and cannot like their own recipes
        return $user->isRegistered() && $user->id !== $recipe->user_id;
    }
}
