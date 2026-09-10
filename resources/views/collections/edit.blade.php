@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('collections.show', $collection) }}" class="text-orange-500 hover:text-orange-600 font-medium flex items-center mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Collection
            </a>
            <h1 class="text-4xl font-bold text-gray-900">Edit Collection</h1>
            <p class="text-gray-600 mt-2">Update collection details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('collections.update', $collection) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Collection Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $collection->name) }}" 
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
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror">{{ old('description', $collection->description) }}</textarea>
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
                                   {{ old('is_public', $collection->is_public ? 0 : 1) == 0 ? 'checked' : '' }} class="h-4 w-4 text-orange-500">
                            <label for="private" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                <span class="font-medium">Private</span>
                                <span class="text-gray-600 block text-xs">Only you can see this collection</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="public" name="is_public" value="1" 
                                   {{ old('is_public', $collection->is_public ? 1 : 0) == 1 ? 'checked' : '' }} class="h-4 w-4 text-orange-500">
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
                    <a href="{{ route('collections.show', $collection) }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-8 bg-red-50 rounded-lg p-6 border border-red-200">
            <h3 class="font-semibold text-red-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Danger Zone
            </h3>
            <p class="text-sm text-red-800 mb-4">Deleting this collection cannot be undone. All recipes will remain in your account, but this collection will be permanently deleted.</p>
            <form method="POST" action="{{ route('collections.destroy', $collection) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this collection? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Delete Collection
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
