@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <!-- Cover Image -->
            <div class="h-32 bg-gradient-to-r from-orange-400 to-orange-600"></div>

            <div class="px-6 pb-6">
                <!-- Profile Info -->
                <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 mb-6">
                    <div class="flex items-end mb-4 md:mb-0">
                        <div class="w-24 h-24 rounded-full bg-orange-200 border-4 border-white flex items-center justify-center overflow-hidden">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-orange-600 font-bold text-4xl">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-4 mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">@if($user->bio){{ $user->bio }}@else No bio @endif</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->id === $user->id)
                            <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                                Edit Profile
                            </a>
                        @else
                            <form method="POST" action="{{ route('users.follow', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 {{ auth()->user()->isFollowing($user) ? 'bg-gray-500 hover:bg-gray-600' : 'bg-orange-500 hover:bg-orange-600' }} text-white rounded-lg transition">
                                    {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-3 md:grid-cols-6 gap-4 py-6 border-t border-b border-gray-200">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-500">{{ $user->total_recipes }}</p>
                        <p class="text-sm text-gray-600">Recipes</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-500">{{ $user->total_followers }}</p>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('users.followers', $user) }}" class="hover:text-orange-500 transition">Followers</a>
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-500">{{ $user->total_following }}</p>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('users.following', $user) }}" class="hover:text-orange-500 transition">Following</a>
                        </p>
                    </div>
                    @if($user->statistics)
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-500">{{ $user->statistics->total_views }}</p>
                            <p class="text-sm text-gray-600">Views</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-500">{{ $user->statistics->total_likes_received }}</p>
                            <p class="text-sm text-gray-600">Likes</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-500">{{ number_format($user->statistics->average_rating, 1) }}</p>
                            <p class="text-sm text-gray-600">Avg Rating</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User's Recipes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Recipes</h2>

            @if($user->recipes()->where('is_published', true)->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->recipes()->where('is_published', true)->latest()->limit(6)->get() as $recipe)
                        <a href="{{ route('recipes.show', $recipe) }}" class="group">
                            <div class="bg-gray-100 rounded-lg overflow-hidden mb-3">
                                @if($recipe->recipe_image)
                                    <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-40 object-cover group-hover:opacity-75 transition">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-orange-500 transition truncate">{{ $recipe->title }}</h3>
                            <p class="text-sm text-gray-600">{{ number_format($recipe->average_rating, 1) }} ★</p>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('recipes.user-recipes', $user->id) }}" class="text-orange-500 hover:text-orange-600 font-medium">
                        View all recipes →
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">This user hasn't published any recipes yet</p>
            @endif
        </div>
    </div>
</div>
@endsection
