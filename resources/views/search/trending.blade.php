@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 flex items-center">
                    <span class="text-4xl mr-3">🔥</span>
                    Trending Recipes
                </h1>
                <p class="text-gray-600 mt-2">Most viewed recipes right now</p>
            </div>
            <a href="{{ route('search.index') }}" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                Advanced Search
            </a>
        </div>

        <!-- Recipes Grid -->
        @if($recipes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($recipes as $recipe)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="relative h-48 bg-gray-200">
                            @if($recipe->recipe_image)
                                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-200">
                                    <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold flex items-center gap-1">
                                👁️ {{ $recipe->view_count }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $recipe->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-400">★</span>
                                    <span class="font-medium">{{ number_format($recipe->average_rating, 1) }}</span>
                                </div>
                                <a href="{{ route('recipes.show', $recipe) }}" class="text-orange-500 hover:text-orange-600 font-medium text-sm">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $recipes->links() }}
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500">No trending recipes yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
