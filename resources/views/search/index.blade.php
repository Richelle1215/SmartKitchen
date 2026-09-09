@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Advanced Search</h1>
            <p class="text-gray-600 mt-2">Find recipes by ingredients, category, rating, and more</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('search.index') }}" class="space-y-6">
                <!-- Main Search -->
                <div>
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Search by Recipe Name or Description</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}" 
                           placeholder="e.g., Pasta Carbonara..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Filters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Max Prep Time -->
                    <div>
                        <label for="max_prep_time" class="block text-sm font-medium text-gray-700 mb-2">Max Prep Time (minutes)</label>
                        <input type="number" name="max_prep_time" id="max_prep_time" value="{{ request('max_prep_time') }}" min="1"
                               placeholder="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Min Servings -->
                    <div>
                        <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">Min Servings</label>
                        <input type="number" name="servings" id="servings" value="{{ request('servings') }}" min="1"
                               placeholder="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Min Rating -->
                    <div>
                        <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">Min Rating (★)</label>
                        <select name="min_rating" id="min_rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Any Rating</option>
                            <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ ★</option>
                            <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ ★</option>
                            <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ ★</option>
                        </select>
                    </div>
                </div>

                <!-- Ingredients Search -->
                <div>
                    <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">Search by Ingredients</label>
                    <input type="text" name="ingredients" id="ingredients" value="{{ request('ingredients') }}"
                           placeholder="e.g., pasta, tomato, garlic (comma-separated)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Sort and Buttons -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" id="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recent</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular (Views)</option>
                            <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending (Likes)</option>
                            <option value="rated" {{ request('sort') == 'rated' ? 'selected' : '' }}>Top Rated</option>
                            <option value="commented" {{ request('sort') == 'commented' ? 'selected' : '' }}>Most Commented</option>
                        </select>
                    </div>
                    <div class="flex gap-3 md:items-end">
                        <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                            Search
                        </button>
                        <a href="{{ route('search.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('search.trending') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="text-orange-500 text-3xl mb-2">🔥</div>
                <h3 class="font-bold text-gray-900">Trending</h3>
                <p class="text-sm text-gray-600">Most viewed recipes</p>
            </a>
            <a href="{{ route('search.popular') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="text-yellow-500 text-3xl mb-2">⭐</div>
                <h3 class="font-bold text-gray-900">Top Rated</h3>
                <p class="text-sm text-gray-600">Highest rated recipes</p>
            </a>
            <a href="{{ route('search.recent') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="text-green-500 text-3xl mb-2">✨</div>
                <h3 class="font-bold text-gray-900">Recent</h3>
                <p class="text-sm text-gray-600">Latest additions</p>
            </a>
            @auth
                <a href="{{ route('search.recommendations') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="text-blue-500 text-3xl mb-2">💡</div>
                    <h3 class="font-bold text-gray-900">For You</h3>
                    <p class="text-sm text-gray-600">Personalized picks</p>
                </a>
            @endauth
        </div>

        <!-- Results -->
        @if($recipes->count() > 0)
            <div class="mb-8">
                <p class="text-gray-600 mb-4">Found {{ $recipes->total() }} recipes</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                        <span>⏱️ {{ $recipe->prep_time }}m</span>
                                    @endif
                                    <span>👥 {{ $recipe->servings }}</span>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-yellow-400">★</span>
                                        <span class="ml-1 font-medium text-gray-700">{{ number_format($recipe->average_rating, 1) }}</span>
                                        <span class="ml-1 text-sm text-gray-500">({{ $recipe->rating_count }})</span>
                                    </div>
                                    <a href="{{ route('recipes.show', $recipe) }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $recipes->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-4">No recipes found matching your search</p>
                <a href="{{ route('search.index') }}" class="text-orange-500 hover:text-orange-600 font-medium">
                    Clear filters and try again
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
