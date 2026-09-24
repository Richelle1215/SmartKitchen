<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile with recipes and management options
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $recipes = $user->recipes()->latest()->paginate(12);
        $contributions = $this->contributionsFor($user);
        $stats = [
            'total_recipes' => $user->recipes()->count(),
            'total_likes' => $user->recipes()->sum('like_count'),
            'total_ratings' => $user->recipes()->sum('rating_count'),
            'avg_rating' => $user->recipes()->avg('average_rating') ?? 0,
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
        ];

        return view('profile.dashboard', compact('user', 'recipes', 'stats', 'contributions'));
    }

    /**
     * Build daily activity for the contribution graph.
     */
    private function contributionsFor(User $user): array
    {
        $start = now()->subMonths(11)->startOfMonth()->startOfWeek();
        $end = now()->endOfDay();
        $counts = [];

        foreach ([$user->recipes(), $user->comments(), $user->ratings()] as $activity) {
            foreach ($activity->whereBetween('created_at', [$start, $end])->get(['created_at']) as $item) {
                $day = $item->created_at->toDateString();
                $counts[$day] = ($counts[$day] ?? 0) + 1;
            }
        }

        foreach (DB::table('likes')->where('user_id', $user->id)->whereBetween('created_at', [$start, $end])->pluck('created_at') as $createdAt) {
            $day = \Illuminate\Support\Carbon::parse($createdAt)->toDateString();
            $counts[$day] = ($counts[$day] ?? 0) + 1;
        }

        $weeks = [];
        for ($week = $start->copy(); $week <= $end; $week->addWeek()) {
            $days = [];
            for ($day = $week->copy(); $day < $week->copy()->addDays(7); $day->addDay()) {
                $date = $day->toDateString();
                $count = $counts[$date] ?? 0;
                $days[] = [
                    'date' => $date,
                    'count' => $count,
                    'level' => $count === 0 ? 0 : min(4, (int) ceil($count / 2)),
                ];
            }
            $weeks[] = $days;
        }

        return $weeks;
    }

    /**
     * Display the user's public profile page
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile.show', [
            'user' => $user,
            'recipeCount' => $user->recipes()->count(),
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
        ]);
    }

    /**
     * Display any user's public profile.
     */
    public function publicProfile(User $user): View
    {
        return view('users.profile', [
            'user' => $user,
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'recipeCount' => $user->recipes()->where('is_published', true)->count(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Show change password form
     */
    public function showChangePassword(Request $request): View
    {
        return view('profile.change-password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Upload user profile picture
     */
    public function uploadProfilePicture(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif'],
        ]);

        $user = $request->user();

        // Delete old profile picture if it exists
        if ($user->profile_picture && Storage::exists($user->profile_picture)) {
            Storage::delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        
        $user->update(['profile_picture' => $path]);

        return back()->with('status', 'profile-picture-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
