<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow a user
     */
    public function store(User $user)
    {
        $currentUser = auth()->user();

        // Can't follow yourself
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself!');
        }

        if ($currentUser->isFollowing($user)) {
            // Unfollow
            $currentUser->following()->detach($user->id);

            $followerStats = $currentUser->statistics()->firstOrCreate(['user_id' => $currentUser->id]);
            $targetStats = $user->statistics()->firstOrCreate(['user_id' => $user->id]);

            $followerStats->decrement('total_following');
            $targetStats->decrement('total_followers');

            $message = 'Unfollowed!';
        } else {
            // Follow
            $currentUser->following()->attach($user->id);

            $followerStats = $currentUser->statistics()->firstOrCreate(['user_id' => $currentUser->id]);
            $targetStats = $user->statistics()->firstOrCreate(['user_id' => $user->id]);

            $followerStats->increment('total_following');
            $targetStats->increment('total_followers');

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'follow',
                'title' => $currentUser->name . ' started following you',
                'message' => $currentUser->name . ' is now following you',
                'action_url' => route('users.profile', $user),
                'related_user_id' => $currentUser->id,
            ]);

            $message = 'Following!';
        }

        return back()->with('success', $message);
    }

    /**
     * Get followers list
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
                    ->with('statistics')
                    ->paginate(12);

        return view('users.followers', compact('user', 'followers'));
    }

    /**
     * Get following list
     */
    public function following(User $user)
    {
        $following = $user->following()
                   ->with('statistics')
                   ->paginate(12);

        return view('users.following', compact('user', 'following'));
    }

    /**
     * Get follow status
     */
    public function status(User $user)
    {
        if (!auth()->check()) {
            return response()->json(['is_following' => false]);
        }

        return response()->json([
            'is_following' => auth()->user()->isFollowing($user),
        ]);
    }
}
