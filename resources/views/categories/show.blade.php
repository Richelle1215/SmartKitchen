@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 px-4 sm:px-6 lg:px-8 mb-12">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-4">
            <span class="text-5xl">{{ $category->icon }}</span>
            <h1 class="text-4xl font-bold">{{ $category->name }}</h1>
        </div>
        <p class="text-blue-100 text-lg">{{ $category->description ?? 'Browse recipes in this category' }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Home</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-700">Categories</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-900 font-medium">{{ $category->name }}</span>
    </nav>

    <!-- Recipes Grid -->
    @if ($recipes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($recipes as $recipe)
                <a href="{{ route('recipes.show', $recipe->id) }}" class="group">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 hover:border-blue-400 h-full">
                        <!-- Recipe Image -->
                        <div class="bg-gray-200 h-48 flex items-center justify-center overflow-hidden">
                            @if ($recipe->recipe_image)
                                <img src="{{ Storage::url($recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            @else
                                <div class="text-4xl">🍳</div>
                            @endif
                        </div>

                        <!-- Recipe Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2">
                                {{ $recipe->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $recipe->description }}</p>

                            <!-- Recipe Meta -->
                            <div class="mt-4 flex gap-4 text-xs text-gray-500">
                                @if ($recipe->prep_time)
                                    <span>⏱️ {{ $recipe->prep_time }}m prep</span>
                                @endif
                                @if ($recipe->cook_time)
                                    <span>🔥 {{ $recipe->cook_time }}m cook</span>
                                @endif
                                @if ($recipe->servings)
                                    <span>👥 {{ $recipe->servings }} servings</span>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                <span class="text-yellow-500">⭐ {{ number_format($recipe->average_rating, 1) }}</span>
                                <span class="text-gray-600 text-xs">({{ $recipe->rating_count }} ratings)</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-2xl mb-2">📭</p>
            <p class="text-gray-600">No recipes found in this category yet.</p>
            <p class="text-sm text-gray-500 mt-2">Check back soon for more recipes!</p>
        </div>
    @endif
</div>
@endsection
