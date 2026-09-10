@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('collections.index') }}" class="text-orange-500 hover:text-orange-600 font-medium flex items-center mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Collections
            </a>
            <h1 class="text-4xl font-bold text-gray-900">Create New Collection</h1>
            <p class="text-gray-600 mt-2">Organize your favorite recipes into custom collections</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('collections.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Collection Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           placeholder="e.g., My Desserts, Healthy Meals, Quick Recipes"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Describe what this collection is about..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Privacy -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Privacy</label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" id="private" name="is_public" value="0" 
                                   {{ old('is_public', 0) == 0 ? 'checked' : '' }} class="h-4 w-4 text-orange-500">
                            <label for="private" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                <span class="font-medium">Private</span>
                                <span class="text-gray-600 block text-xs">Only you can see this collection</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="public" name="is_public" value="1" 
                                   {{ old('is_public', 0) == 1 ? 'checked' : '' }} class="h-4 w-4 text-orange-500">
                            <label for="public" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                <span class="font-medium">Public</span>
                                <span class="text-gray-600 block text-xs">Anyone can see and browse this collection</span>
                            </label>
                        </div>
                    </div>
                    @error('is_public')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <a href="{{ route('collections.index') }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-medium">
                        Create Collection
                    </button>
                </div>
            </form>
        </div>

        <!-- Examples -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-3">Collection Ideas</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>My Desserts</strong> - Sweet treats and dessert recipes</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Healthy Meals</strong> - Nutritious and balanced recipes</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Quick Recipes</strong> - Meals that take 30 minutes or less</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Family Favorites</strong> - Recipes loved by the whole family</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Budget Meals</strong> - Affordable recipes for the whole week</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
