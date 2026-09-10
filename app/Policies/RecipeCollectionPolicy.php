<?php

namespace App\Policies;

use App\Models\RecipeCollection;
use App\Models\User;

class RecipeCollectionPolicy
{
    /**
     * Determine if the user can view a collection
     */
    public function view(?User $user, RecipeCollection $collection): bool
    {
        // Owner can always view
        if ($user && $user->id === $collection->user_id) {
            return true;
        }

        // Public collections can be viewed by anyone
        return $collection->is_public;
    }

    /**
     * Determine if the user can create collections
     */
    public function create(User $user): bool
    {
        // Authenticated users can create collections
        return true;
    }

    /**
     * Determine if the user can update a collection
     */
    public function update(User $user, RecipeCollection $collection): bool
    {
        // Only the owner can update
        return $user->id === $collection->user_id;
    }

    /**
     * Determine if the user can delete a collection
     */
    public function delete(User $user, RecipeCollection $collection): bool
    {
        // Only the owner can delete
        return $user->id === $collection->user_id;
    }

    /**
     * Determine if the user can add recipes to a collection
     */
    public function addRecipe(User $user, RecipeCollection $collection): bool
    {
        // Only the owner can add recipes
        return $user->id === $collection->user_id;
    }

    /**
     * Determine if the user can remove recipes from a collection
     */
    public function removeRecipe(User $user, RecipeCollection $collection): bool
    {
        // Only the owner can remove recipes
        return $user->id === $collection->user_id;
    }
}
