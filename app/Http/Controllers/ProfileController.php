<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $stats = [
            'total_recipes' => $user->recipes()->count(),
            'total_likes' => $user->recipes()->sum('like_count'),
            'total_ratings' => $user->recipes()->sum('rating_count'),
            'avg_rating' => $user->recipes()->avg('average_rating') ?? 0,
            'followers' => $user->followers()->count(),
            'following' => $user->following()->count(),
        ];

        return view('profile.dashboard', compact('user', 'recipes', 'stats'));
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
