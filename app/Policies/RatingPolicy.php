<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Determine whether the user can rate a recipe
     */
    public function rate(User $user, Recipe $recipe): bool
    {
        return $user->isRegistered() && $user->id !== $recipe->user_id;
    }

    /**
     * Determine whether the user can delete the rating.
     */
    public function delete(User $user, Rating $rating): bool
    {
        return $user->id === $rating->user_id;
    }
}
