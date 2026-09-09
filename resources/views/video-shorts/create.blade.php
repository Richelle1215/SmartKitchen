@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Create Video Short</h1>

        <form method="POST" action="{{ route('video-shorts.store') }}" class="bg-white rounded-lg shadow-md p-8 space-y-8">
            @csrf

            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Details</h2>

                <div class="space-y-4">
                    <div>
                        <label for="recipe_id" class="block text-sm font-medium text-gray-700 mb-1">Recipe *</label>
                        <select id="recipe_id" name="recipe_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a recipe</option>
                            @foreach ($recipes as $recipe)
                                <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                            @endforeach
                        </select>
                        @error('recipe_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., 30-Second Pasta Recipe">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe your short video...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">Video URL *</label>
                            <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://example.com/video.mp4">
                            @error('video_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (seconds) *</label>
                            <input type="number" id="duration" name="duration" value="{{ old('duration') }}" required min="1" max="60"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="30">
                            @error('duration')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="quick-recipe, cooking, tutorial (comma-separated)">
                        @error('tags')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">📹 Video Tips</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Keep videos between 15-60 seconds for best engagement</li>
                    <li>• Use clear, bright lighting for food shots</li>
                    <li>• Add subtitles or voiceover for clarity</li>
                    <li>• Highlight key steps and ingredients</li>
                    <li>• Use trending audio or background music</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-medium">
                    Upload Short
                </button>
                <a href="{{ route('video-shorts.index') }}" class="flex-1 text-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
