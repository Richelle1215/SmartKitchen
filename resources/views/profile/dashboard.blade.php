@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-8">
            <div class="flex items-center gap-8 mb-8">
                <!-- Profile Picture -->
                <div class="flex-shrink-0">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-green-500">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-orange-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-green-500">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600 text-lg">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                            ✎ Edit Profile
                        </a>
                    </div>

                @if ($user->bio)
                    <p class="text-gray-700 text-lg mb-4">{{ $user->bio }}</p>
                @endif

                    <p class="text-sm text-gray-500">Joined {{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 border-t pt-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['total_recipes'] }}</div>
                    <div class="text-sm text-gray-600">Recipes</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['total_likes'] }}</div>
                    <div class="text-sm text-gray-600">Likes Received</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ round($stats['avg_rating'], 1) }}/5</div>
                    <div class="text-sm text-gray-600">Avg Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['followers'] }}</div>
                    <div class="text-sm text-gray-600">Followers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['following'] }}</div>
                    <div class="text-sm text-gray-600">Following</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['total_ratings'] }}</div>
                    <div class="text-sm text-gray-600">Ratings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Recipes Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">My Recipes</h2>
                <p class="text-gray-600">Create, manage, and share your recipes</p>
            </div>
            <a href="{{ route('recipes.create') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-lg transition">
                + Create New Recipe
            </a>
        </div>

        <!-- Recipes Grid -->
        @if ($recipes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recipes as $recipe)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Recipe Image -->
                        <div class="w-full h-48 bg-gray-300 overflow-hidden relative group">
                            @if ($recipe->recipe_image)
                                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-5xl bg-gradient-to-br from-gray-200 to-gray-300">
                                    🍽️
                                </div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if ($recipe->is_published)
                                    <span class="inline-block bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">Published</span>
                                @else
                                    <span class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold">Draft</span>
                                @endif
                            </div>
                        </div>

                        <!-- Recipe Details -->
                        <div class="p-5">
                            <!-- Category -->
                            <div class="mb-2">
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded font-medium">
                                    {{ $recipe->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $recipe->title }}</h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>

                            <!-- Stats -->
                            <div class="flex gap-4 text-xs text-gray-600 mb-4 pb-4 border-b">
                                @if ($recipe->prep_time)
                                    <div class="flex items-center gap-1">
                                        <span>⏱️</span>
                                        <span>{{ $recipe->prep_time }} min</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-1">
                                    <span>👥</span>
                                    <span>{{ $recipe->servings }}</span>
                                </div>
                            </div>

                            <!-- Engagement Stats -->
                            <div class="flex gap-3 text-xs text-gray-600 mb-5">
                                <div class="flex items-center gap-1">
                                    <span>👁️</span>
                                    <span>{{ $recipe->view_count ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span>❤️</span>
                                    <span>{{ $recipe->like_count ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span>⭐</span>
                                    <span>{{ round($recipe->average_rating ?? 0, 1) }}/5</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition text-center">
                                    View
                                </a>
                                <a href="{{ route('recipes.edit', $recipe) }}" class="flex-1 px-3 py-2 bg-orange-600 text-white text-sm font-medium rounded hover:bg-orange-700 transition text-center">
                                    Edit
                                </a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this recipe? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $recipes->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-12 text-center border-2 border-dashed border-gray-300">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No recipes yet</h3>
                <p class="text-gray-600 mb-6 text-lg">Start creating amazing recipes to share with the community!</p>
                <a href="{{ route('recipes.create') }}" class="inline-block px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-lg transition">
                    Create Your First Recipe
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
