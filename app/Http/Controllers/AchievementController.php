<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    /**
     * Display user's achievements
     */
    public function index()
    {
        $user = auth()->user();
        
        $achievements = Achievement::with(['users' => function($q) use ($user) {
                                    $q->where('user_id', $user->id);
                                }])
                                   ->orderBy('points', 'desc')
                                   ->get();

        $earnedCount = $user->achievements()->count();
        $totalPoints = $user->achievements()->sum('points');

        return view('achievements.index', compact('achievements', 'earnedCount', 'totalPoints'));
    }

    /**
     * Show leaderboard
     */
    public function leaderboard()
    {
        $leaderboard = \App\Models\User::withCount('achievements')
                                       ->withSum('achievements', 'points')
                                       ->orderByDesc('achievements_sum_points')
                                       ->take(100)
                                       ->get();

        return view('achievements.leaderboard', compact('leaderboard'));
    }

    /**
     * Get all available achievements (admin view)
     */
    public function all()
    {
        $achievements = Achievement::orderBy('points', 'desc')->paginate(15);
        return view('achievements.all', compact('achievements'));
    }

    /**
     * Award achievement to user
     */
    public function award(Request $request)
    {
        $this->authorize('award-achievement');

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'achievement_id' => ['required', 'exists:achievements,id'],
        ]);

        $user = \App\Models\User::find($validated['user_id']);
        $achievement = Achievement::find($validated['achievement_id']);

        // Check if already earned
        if ($user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            return back()->with('warning', 'User already has this achievement!');
        }

        $user->achievements()->attach($achievement->id, [
            'earned_at' => now(),
        ]);

        return back()->with('success', "Achievement '{$achievement->name}' awarded!");
    }

    /**
     * Get user's achievement statistics
     */
    public function stats()
    {
        $user = auth()->user();

        $stats = [
            'total_achievements' => $user->achievements()->count(),
            'total_points' => $user->achievements()->sum('points'),
            'recent_achievements' => $user->achievements()
                                         ->orderBy('pivot_earned_at', 'desc')
                                         ->take(5)
                                         ->get(),
            'rank' => \App\Models\User::where('id', '!=', $user->id)
                                      ->where(function($q) {
                                          $q->having(\DB::raw('(SELECT SUM(points) FROM achievement_user WHERE user_id = users.id)'), '>', 
                                                    \DB::raw("(SELECT SUM(points) FROM achievement_user WHERE user_id = {$user->id})"))
                                            ->orRaw("1 = 1");
                                      })
                                      ->count() + 1,
        ];

        return response()->json($stats);
    }

    /**
     * Check and award achievements based on actions
     */
    public function checkAndAward($userId, $type)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return;

        $criteria = [
            'first_recipe' => fn() => $user->recipes()->count() === 1,
            'recipe_master' => fn() => $user->recipes()->count() >= 10,
            'community_champion' => fn() => $user->recipes()->average('average_rating') >= 4.5,
            'food_blogger' => fn() => $user->recipes()->count() >= 5 && $user->recipes()->average('average_rating') >= 4.0,
            'helper' => fn() => $user->comments()->count() >= 20,
            'taste_maker' => fn() => $user->favorites()->count() >= 50,
            'trending_star' => fn() => $user->recipes()->orderBy('view_count', 'desc')->first()?->view_count >= 1000,
            'early_adopter' => fn() => $user->created_at->diffInDays(now()) <= 7,
            'recipe_collector' => fn() => $user->favorites()->count() >= 100,
            'top_contributor' => fn() => $user->recipes()->sum('average_rating') >= 100,
        ];

        foreach ($criteria as $key => $check) {
            if ($check()) {
                $achievement = Achievement::where('slug', $key)->first();
                if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                    $user->achievements()->attach($achievement->id, ['earned_at' => now()]);
                }
            }
        }
    }
}
