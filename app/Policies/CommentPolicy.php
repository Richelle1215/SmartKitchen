<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can comment on a recipe
     */
    public function comment(User $user, Recipe $recipe): bool
    {
        return $user->isRegistered();
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->id === $comment->recipe->user_id;
    }
}
