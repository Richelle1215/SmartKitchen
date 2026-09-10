@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('collections.index') }}" class="text-orange-500 hover:text-orange-600 font-medium flex items-center mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Collections
            </a>

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $collection->name }}</h1>
                    @if($collection->description)
                        <p class="text-gray-600 text-lg">{{ $collection->description }}</p>
                    @endif
                </div>
                @can('update', $collection)
                    <div class="flex gap-2">
                        <a href="{{ route('collections.edit', $collection) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('collections.destroy', $collection) }}" class="inline" onsubmit="return confirm('Delete this collection? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            <!-- Collection Info -->
            <div class="flex gap-4 text-sm">
                <span class="text-gray-600">
                    <span class="font-bold text-orange-500">{{ $collection->recipes_count }}</span> recipes
                </span>
                <span class="text-gray-600">
                    Created {{ $collection->created_at->diffForHumans() }}
                </span>
                @if($collection->is_public)
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Public</span>
                @else
                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">Private</span>
                @endif
            </div>
        </div>

        <!-- Status Messages -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-600 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Add Recipe Section -->
        @can('addRecipe', $collection)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Add Recipe to Collection</h2>
                <form method="POST" action="{{ route('collections.add-recipe', $collection) }}" class="flex gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="number" name="recipe_id" placeholder="Enter recipe ID" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               required>
                        <p class="mt-1 text-xs text-gray-500">You can find recipe IDs in the URL: /recipes/[ID]</p>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-medium h-fit">
                        Add Recipe
                    </button>
                </form>
            </div>
        @endcan

        <!-- Recipes Grid -->
        @if($recipes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recipes as $recipe)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Recipe Image -->
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($recipe->recipe_image)
                                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Recipe Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-500 transition">
                                    {{ $recipe->title }}
                                </a>
                            </h3>

                            <!-- Stats -->
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                @if($recipe->prep_time)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $recipe->prep_time }}m
                                    </span>
                                @endif
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ number_format($recipe->average_rating, 1) }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="flex-1 px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-lg transition text-center font-medium">
                                    View
                                </a>
                                @can('removeRecipe', $collection)
                                    <form method="POST" action="{{ route('collections.remove-recipe', [$collection, $recipe]) }}" class="flex-1" onsubmit="return confirm('Remove this recipe from the collection?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition font-medium">
                                            Remove
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($recipes->hasPages())
                <div class="mt-8">
                    {{ $recipes->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No recipes in this collection yet</h3>
                <p class="text-gray-600 mb-6">Add recipes to organize them in this collection</p>
                @can('addRecipe', $collection)
                    <p class="text-sm text-gray-500">Use the form above to add recipes by their ID</p>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
