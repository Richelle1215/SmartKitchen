@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 px-4 sm:px-6 lg:px-8 mb-12">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold mb-3">Recipe Categories</h1>
        <p class="text-blue-100 text-lg">Explore all recipe categories and find your next favorite meal</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="group">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden h-full border border-gray-200 hover:border-blue-400">
                    <!-- Category Header with Icon -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-8 flex items-center justify-center">
                        <div class="text-6xl">{{ $category->icon }}</div>
                    </div>

                    <!-- Category Content -->
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition">
                            {{ $category->name }}
                        </h2>
                        <p class="text-gray-600 mt-3 line-clamp-2">
                            {{ $category->description ?? 'Explore recipes in this category' }}
                        </p>

                        <!-- Recipe Count -->
                        <div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $category->recipes()->count() }} 
                                {{ Str::plural('recipe', $category->recipes()->count()) }}
                            </span>
                            <span class="text-blue-600 font-semibold group-hover:translate-x-2 transition">→</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600 text-lg">No categories found</p>
            </div>
        @endforelse
    </div>

    <!-- Stats Section -->
    <div class="mt-16 bg-gray-50 rounded-lg p-8 border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Category Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6 border border-gray-200 text-center">
                <div class="text-4xl font-bold text-blue-600">{{ $categories->count() }}</div>
                <p class="text-gray-600 mt-2">Total Categories</p>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200 text-center">
                <div class="text-4xl font-bold text-green-600">{{ $categories->sum(fn($c) => $c->recipes()->count()) }}</div>
                <p class="text-gray-600 mt-2">Total Recipes</p>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200 text-center">
                <div class="text-4xl font-bold text-orange-600">{{ $categories->max(fn($c) => $c->recipes()->count()) }}</div>
                <p class="text-gray-600 mt-2">Largest Category</p>
            </div>
        </div>
    </div>
</div>
@endsection
