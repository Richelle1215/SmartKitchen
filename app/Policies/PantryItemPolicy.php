<?php

namespace App\Policies;

use App\Models\PantryItem;
use App\Models\User;

class PantryItemPolicy
{
    /**
     * Determine whether the user can update the pantry item.
     */
    public function update(User $user, PantryItem $item): bool
    {
        return $user->id === $item->user_id;
    }

    /**
     * Determine whether the user can delete the pantry item.
     */
    public function delete(User $user, PantryItem $item): bool
    {
        return $user->id === $item->user_id;
    }
}
