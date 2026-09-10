@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <x-profile-header :user="$user" :showEditButton="true" />

        <!-- Profile Stats -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-4xl font-bold text-blue-600">{{ $recipeCount }}</p>
                <p class="text-gray-600 mt-2">Recipes</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-4xl font-bold text-purple-600">{{ $followersCount }}</p>
                <p class="text-gray-600 mt-2">Followers</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-4xl font-bold text-green-600">{{ $followingCount }}</p>
                <p class="text-gray-600 mt-2">Following</p>
            </div>
        </div>

        <!-- Bio Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">About</h2>
            <p class="text-gray-700 leading-relaxed">
                {{ $user->bio ?? 'No bio added yet. Head to your profile settings to add one!' }}
            </p>
        </div>

        <!-- Recent Recipes Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Recipes</h2>
            @if ($recipeCount > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for ($i = 1; $i <= min(4, $recipeCount); $i++)
                        <div class="border border-gray-300 rounded-lg overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gray-200 h-40 flex items-center justify-center">
                                <span class="text-3xl">🍳</span>
                            </div>
                            <div class="p-4">
                                <p class="font-semibold text-gray-900">Placeholder Recipe #{{ $i }}</p>
                                <p class="text-sm text-gray-600 mt-1">Recipe details coming soon</p>
                            </div>
                        </div>
                    @endfor
                </div>
            @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <p class="text-gray-600">📚 No recipes yet</p>
                    <p class="text-sm text-gray-500 mt-2">Recipes shared will appear here</p>
                </div>
            @endif
        </div>

        <!-- Achievements Placeholder -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Achievements</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="text-center p-4 border border-gray-300 rounded-lg hover:shadow-md transition">
                        <div class="text-4xl mb-2">🏆</div>
                        <p class="text-sm text-gray-600">Achievement</p>
                    </div>
                @endfor
            </div>
            <p class="text-sm text-gray-500 mt-4 text-center">Achievements will unlock as you engage with the community</p>
        </div>
    </div>
</div>
@endsection
