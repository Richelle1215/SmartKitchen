@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-orange-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Recommended For You</h1>
            <p class="text-gray-600">Based on your favorite categories and recipe preferences</p>
        </div>

        @if($recommended->count() > 0)
            <!-- Recommendations Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($recommended as $recipe)
                    <div class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <!-- Image -->
                        <div class="relative h-56 bg-gray-200 overflow-hidden">
                            @if($recipe->recipe_image)
                                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-200">
                                    <svg class="w-20 h-20 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-orange-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                                ⭐ {{ number_format($recipe->average_rating, 1) }}
                            </div>
                            <div class="absolute top-3 left-3 bg-white bg-opacity-90 text-gray-900 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $recipe->category->name ?? 'Uncategorized' }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-orange-500 transition">
                                {{ $recipe->title }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>

                            <!-- Recipe Stats -->
                            <div class="flex flex-wrap gap-4 text-sm text-gray-700 mb-4 pb-4 border-b border-gray-100">
                                @if($recipe->prep_time)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-2.828 2.829a1 1 0 101.414 1.414L8 9.586V6z" clip-rule="evenodd"></path></svg>
                                        {{ $recipe->prep_time }}m prep
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path></svg>
                                    {{ $recipe->servings }}
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-orange-200 flex items-center justify-center text-xs font-bold text-orange-600">
                                        {{ substr($recipe->user->name, 0, 1) }}
                                    </div>
                                    <span class="ml-2 text-sm text-gray-700 font-medium">{{ $recipe->user->name }}</span>
                                </div>
                                <a href="{{ route('recipes.show', $recipe) }}" class="inline-block px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition">
                                    View Recipe
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Explore More -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Want more suggestions?</h2>
                <p class="text-gray-600 mb-6">Rate and favorite recipes to get better personalized recommendations</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('search.index') }}" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                        Browse All Recipes
                    </a>
                    <a href="{{ route('recipes.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
                        Explore Trending
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Start Building Your Profile</h2>
                <p class="text-gray-600 mb-6">Like, favorite, and rate recipes to get personalized recommendations</p>
                <a href="{{ route('recipes.index') }}" class="inline-block px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                    Discover Recipes
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
