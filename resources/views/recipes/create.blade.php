@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Create New Recipe</h1>
            <p class="text-gray-600">Share your culinary discovery with the global community.</p>
        </div>

        <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Recipe Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Recipe Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                       placeholder="e.g., Tuscan Garlic Lemon Pasta">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description *</label>
                <textarea id="description" name="description" rows="4" value="{{ old('description') }}"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Describe your recipe...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Category *</label>
                <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prep Time & Servings -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="prep_time" class="block text-sm font-semibold text-gray-900 mb-2">Prep Time (minutes) *</label>
                    <input type="number" id="prep_time" name="prep_time" value="{{ old('prep_time') }}" required min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="30">
                    @error('prep_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="servings" class="block text-sm font-semibold text-gray-900 mb-2">Servings *</label>
                    <input type="number" id="servings" name="servings" value="{{ old('servings') }}" required min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="4">
                    @error('servings')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ingredients -->
            <div class="border-t pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Ingredients</h2>

                <div id="ingredientsContainer" class="space-y-3 mb-6">
                    <div class="ingredientRow grid grid-cols-12 gap-2">
                        <input type="text" name="ingredients[0][name]" placeholder="e.g., Fresh Spaghetti" required
                               class="col-span-5 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="number" name="ingredients[0][quantity]" placeholder="300" step="0.1" required
                               class="col-span-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <select name="ingredients[0][unit]" required
                                class="col-span-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="grams">grams</option>
                            <option value="ml">ml</option>
                            <option value="tbsp">tbsp</option>
                            <option value="tsp">tsp</option>
                            <option value="cup">cup</option>
                            <option value="piece">piece</option>
                        </select>
                        <button type="button" onclick="removeIngredient(this)" class="col-span-1 text-red-600 hover:text-red-700 font-bold text-lg">✕</button>
                    </div>
                </div>

                <button type="button" onclick="addIngredient()" class="text-blue-600 hover:text-blue-700 font-semibold mb-6">
                    + Add Ingredient
                </button>
                @error('ingredients')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions -->
            <div class="border-t pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Step-by-Step Instructions</h2>

                <div id="instructionsContainer" class="space-y-4 mb-6">
                    <div class="instructionRow bg-gray-50 p-4 rounded-lg flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold stepNumber">1</div>
                        <textarea name="instructions[0][text]" placeholder="Write your instruction..." required
                                  rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        <button type="button" onclick="removeInstruction(this)" class="text-red-600 hover:text-red-700 font-bold text-lg">✕</button>
                    </div>
                </div>

                <button type="button" onclick="addInstruction()" class="text-blue-600 hover:text-blue-700 font-semibold mb-6">
                    + Add Step
                </button>
                @error('instructions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Media Upload -->
            <div class="border-t pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload Image & Video</h2>

                <div class="space-y-6">
                    <!-- Recipe Image -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Recipe Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition"
                             onclick="document.getElementById('recipe_image').click()">
                            <div class="text-5xl mb-2">📸</div>
                            <p class="font-medium text-gray-700">Click to upload image</p>
                            <p class="text-sm text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                            <input type="file" id="recipe_image" name="recipe_image" accept="image/*" style="display: none;"
                                   onchange="updateFileName(this, 'recipe_image_name')">
                        </div>
                        <p id="recipe_image_name" class="text-sm text-gray-600 mt-2"></p>
                        @error('recipe_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recipe Video -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Recipe Video (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition"
                             onclick="document.getElementById('recipe_video').click()">
                            <div class="text-5xl mb-2">🎥</div>
                            <p class="font-medium text-gray-700">Click to upload video</p>
                            <p class="text-sm text-gray-500 mt-1">MP4 up to 20MB</p>
                            <input type="file" id="recipe_video" name="recipe_video" accept="video/*" style="display: none;"
                                   onchange="updateFileName(this, 'recipe_video_name')">
                        </div>
                        <p id="recipe_video_name" class="text-sm text-gray-600 mt-2"></p>
                        @error('recipe_video')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="border-t pt-8 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition text-lg">
                    Publish Recipe
                </button>
                <a href="{{ route('recipes.index') }}" class="flex-1 text-center bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-300 transition text-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let ingredientCount = 1;
let instructionCount = 1;

function addIngredient() {
    const container = document.getElementById('ingredientsContainer');
    const html = `
        <div class="ingredientRow grid grid-cols-12 gap-2">
            <input type="text" name="ingredients[${ingredientCount}][name]" placeholder="Ingredient name" required
                   class="col-span-5 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <input type="number" name="ingredients[${ingredientCount}][quantity]" placeholder="Qty" step="0.1" required
                   class="col-span-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <select name="ingredients[${ingredientCount}][unit]" required
                    class="col-span-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="grams">grams</option>
                <option value="ml">ml</option>
                <option value="tbsp">tbsp</option>
                <option value="tsp">tsp</option>
                <option value="cup">cup</option>
                <option value="piece">piece</option>
            </select>
            <button type="button" onclick="removeIngredient(this)" class="col-span-1 text-red-600 hover:text-red-700 font-bold text-lg">✕</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    ingredientCount++;
}

function removeIngredient(btn) {
    btn.closest('.ingredientRow').remove();
}

function addInstruction() {
    const container = document.getElementById('instructionsContainer');
    const stepNum = document.querySelectorAll('.instructionRow').length + 1;
    const html = `
        <div class="instructionRow bg-gray-50 p-4 rounded-lg flex gap-3">
            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold stepNumber">${stepNum}</div>
            <textarea name="instructions[${instructionCount}][text]" placeholder="Write your instruction..." required
                      rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            <button type="button" onclick="removeInstruction(this)" class="text-red-600 hover:text-red-700 font-bold text-lg">✕</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    updateStepNumbers();
    instructionCount++;
}

function removeInstruction(btn) {
    btn.closest('.instructionRow').remove();
    updateStepNumbers();
}

function updateStepNumbers() {
    const steps = document.querySelectorAll('.stepNumber');
    steps.forEach((step, index) => {
        step.textContent = index + 1;
    });
}

function updateFileName(input, displayId) {
    const fileName = input.files[0]?.name || '';
    document.getElementById(displayId).textContent = fileName ? '✓ ' + fileName : '';
}
</script>
@endsection
