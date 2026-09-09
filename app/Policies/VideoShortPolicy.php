<?php

namespace App\Policies;

use App\Models\VideoShort;
use App\Models\User;

class VideoShortPolicy
{
    /**
     * Determine whether the user can delete the video short.
     */
    public function delete(User $user, VideoShort $short): bool
    {
        return $user->id === $short->user_id;
    }
}
