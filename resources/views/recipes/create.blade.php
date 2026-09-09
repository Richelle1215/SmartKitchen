@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Create New Recipe</h1>
            <p class="text-gray-600 mt-2">Share your delicious recipe with the community</p>
        </div>

        <form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Basic Information</h2>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Recipe Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           placeholder="Enter recipe title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Describe your recipe..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Servings Row -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" id="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Servings -->
                    <div>
                        <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">Servings *</label>
                        <input type="number" name="servings" id="servings" value="{{ old('servings', 1) }}" min="1" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('servings') border-red-500 @enderror">
                        @error('servings')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Times Row -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Prep Time -->
                    <div>
                        <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">Prep Time (minutes)</label>
                        <input type="number" name="prep_time" id="prep_time" value="{{ old('prep_time') }}" min="1"
                               placeholder="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Cook Time -->
                    <div>
                        <label for="cook_time" class="block text-sm font-medium text-gray-700 mb-2">Cook Time (minutes)</label>
                        <input type="number" name="cook_time" id="cook_time" value="{{ old('cook_time') }}" min="1"
                               placeholder="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Media</h2>

                <!-- Recipe Image -->
                <div class="mb-6">
                    <label for="recipe_image" class="block text-sm font-medium text-gray-700 mb-2">Recipe Image</label>
                    <input type="file" name="recipe_image" id="recipe_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-gray-500 text-sm mt-1">Max size: 5MB (JPEG, PNG, GIF)</p>
                    @error('recipe_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recipe Video -->
                <div>
                    <label for="recipe_video" class="block text-sm font-medium text-gray-700 mb-2">Recipe Video</label>
                    <input type="file" name="recipe_video" id="recipe_video" accept="video/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-gray-500 text-sm mt-1">Max size: 100MB (MP4, MOV, AVI, MKV)</p>
                    @error('recipe_video')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ingredients -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Ingredients *</h2>
                <div id="ingredients-container" class="space-y-4 mb-4">
                    <!-- Ingredients will be added here dynamically -->
                </div>
                <button type="button" onclick="addIngredient()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    + Add Ingredient
                </button>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Instructions *</h2>
                <div id="instructions-container" class="space-y-4 mb-4">
                    <!-- Instructions will be added here dynamically -->
                </div>
                <button type="button" onclick="addInstruction()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                    + Add Step
                </button>
            </div>

            <!-- Publish Options -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Publish Options</h2>
                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1" checked class="w-4 h-4">
                    <label for="is_published" class="ml-2 text-gray-700">Publish this recipe immediately</label>
                </div>
                <p class="text-gray-500 text-sm mt-2">Uncheck to save as draft</p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg transition">
                    Create Recipe
                </button>
                <a href="{{ route('recipes.index') }}" class="flex-1 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold rounded-lg transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let ingredientCount = 0;
let instructionCount = 0;

function addIngredient() {
    const container = document.getElementById('ingredients-container');
    const html = `
        <div class="ingredient-item border border-gray-200 rounded-lg p-4">
            <div class="grid grid-cols-4 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                    <input type="text" name="ingredients[${ingredientCount}][name]" required
                           placeholder="e.g., Flour" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="ingredients[${ingredientCount}][quantity]" step="0.01" min="0.01" required
                           placeholder="2" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                    <input type="text" name="ingredients[${ingredientCount}][unit]" required
                           placeholder="cups" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    ingredientCount++;
}

function addInstruction() {
    const container = document.getElementById('instructions-container');
    const html = `
        <div class="instruction-item border border-gray-200 rounded-lg p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Step ${instructionCount + 1} *</label>
            <textarea name="instructions[${instructionCount}][text]" required
                      placeholder="Describe this step..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded mb-3"></textarea>
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                Remove Step
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    instructionCount++;
}

// Initialize with one ingredient and instruction
document.addEventListener('DOMContentLoaded', function() {
    addIngredient();
    addInstruction();
});
</script>
@endsection
