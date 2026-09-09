@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-orange-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Discover Recipes</h1>
            <p class="text-gray-600">Browse and search from our collection of delicious recipes</p>
        </div>

        <!-- Search and Filters -->
        <div class="mb-8 bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('recipes.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" id="search" placeholder="Recipe name..." 
                               value="{{ request('search') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recent</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                            <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending</option>
                            <option value="rated" {{ request('sort') == 'rated' ? 'selected' : '' }}>Top Rated</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-md transition">
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Create Recipe Button (for authenticated users) -->
        @auth
            @if(auth()->user()->isRegistered())
                <div class="mb-8">
                    <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Recipe
                    </a>
                </div>
            @endif
        @endauth

        <!-- Recipes Grid -->
        @if($recipes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($recipes as $recipe)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Image -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($recipe->recipe_image)
                                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-200">
                                    <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                {{ $recipe->category->name ?? 'Uncategorized' }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $recipe->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>

                            <!-- Recipe Info -->
                            <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-4">
                                @if($recipe->prep_time)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-2.828 2.829a1 1 0 101.414 1.414L8 9.586V6z" clip-rule="evenodd"></path></svg>
                                        {{ $recipe->prep_time }}m prep
                                    </span>
                                @endif
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path></svg>
                                        {{ $recipe->servings }}
                                    </span>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($recipe->average_rating))
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ number_format($recipe->average_rating, 1) }}</span>
                                    <span class="ml-1 text-sm text-gray-500">({{ $recipe->rating_count }})</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $recipe->view_count }} views</span>
                            </div>

                            <!-- User Info -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-orange-200 flex items-center justify-center">
                                        @if($recipe->user->profile_picture)
                                            <img src="{{ asset('storage/' . $recipe->user->profile_picture) }}" alt="{{ $recipe->user->name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span class="text-orange-600 font-bold">{{ substr($recipe->user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $recipe->user->name }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('recipes.show', $recipe) }}" class="text-orange-500 hover:text-orange-600 font-medium text-sm">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $recipes->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No recipes found matching your criteria.</p>
                <a href="{{ route('recipes.index') }}" class="mt-4 inline-block text-orange-500 hover:text-orange-600 font-medium">
                    View all recipes
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
