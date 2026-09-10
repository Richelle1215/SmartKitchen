@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">My Recipes</h1>
            <p class="text-gray-600 text-sm mt-2">Manage and view all your created recipes</p>
        </div>
        <a href="{{ route('recipes.create') }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-sm">
            + Create Recipe
        </a>
    </div>

    <!-- Recipes Grid -->
    @if ($recipes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($recipes as $recipe)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <!-- Recipe Image -->
                    <div class="w-full h-48 bg-gray-200 rounded-t-lg overflow-hidden">
                        @if ($recipe->recipe_image)
                            <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-5xl">
                                🍽️
                            </div>
                        @endif
                    </div>

                    <!-- Recipe Info -->
                    <div class="p-4">
                        <!-- Category Badge -->
                        <div class="mb-2">
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                {{ $recipe->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $recipe->title }}</h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>

                        <!-- Stats -->
                        <div class="flex gap-4 text-sm text-gray-600 mb-4">
                            @if ($recipe->prep_time)
                                <div class="flex items-center gap-1">
                                    <span>⏱️</span>
                                    <span>{{ $recipe->prep_time }} min</span>
                                </div>
                            @endif
                            @if ($recipe->servings)
                                <div class="flex items-center gap-1">
                                    <span>👥</span>
                                    <span>{{ $recipe->servings }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Engagement Stats -->
                        <div class="flex gap-4 text-xs text-gray-500 mb-4 pb-4 border-b">
                            <div class="flex items-center gap-1">
                                <span>👁️</span>
                                <span>{{ $recipe->view_count ?? 0 }} views</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span>❤️</span>
                                <span>{{ $recipe->like_count ?? 0 }} likes</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span>⭐</span>
                                <span>{{ $recipe->average_rating ?? 0 }}/5</span>
                            </div>
                        </div>

                        <!-- Status & Actions -->
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                @if ($recipe->is_published)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Published</span>
                                @else
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Draft</span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('recipes.edit', $recipe) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
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
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No recipes yet</h3>
            <p class="text-gray-600 mb-6">Start creating recipes to share with the community!</p>
            <a href="{{ route('recipes.create') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                Create Your First Recipe
            </a>
        </div>
    @endif
</div>
@endsection
