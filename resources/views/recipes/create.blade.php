@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-8 py-12">
    <!-- Header -->
    <div class="flex justify-between items-start mb-12">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Create New Recipe</h1>
            <p class="text-gray-600 text-sm mt-2">Share your culinary discovery with the global community.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="saveDraft()" class="px-6 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium text-sm">
                Save Draft
            </button>
            <button form="recipeForm" type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-sm">
                Publish Recipe
            </button>
        </div>
    </div>

    <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-2 gap-12">
            <!-- LEFT COLUMN -->
            <div class="space-y-8">
                <!-- Recipe Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">Recipe Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Tuscan Garlic Lemon Pasta">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Short Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">Short Description</label>
                    <textarea id="description" name="description" rows="5" 
                              class="w-full px-4 py-3 border border-gray-300 rounded text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                              placeholder="A creamy, delicious one-pot pasta...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-3">Category</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prep Time & Servings -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="prep_time" class="block text-sm font-semibold text-gray-900 mb-3">Prep Time (min)</label>
                        <input type="number" id="prep_time" name="prep_time" value="{{ old('prep_time') }}" min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="30">
                        @error('prep_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="servings" class="block text-sm font-semibold text-gray-900 mb-3">Servings</label>
                        <input type="number" id="servings" name="servings" value="{{ old('servings') }}" required min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="4">
                        @error('servings')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Ingredients -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Ingredients</h2>

                    <div id="ingredientsContainer" class="space-y-3 mb-6">
                        <div class="ingredientRow flex items-center gap-3">
                            <input type="text" name="ingredients[0][name]" placeholder="Fresh Spaghetti" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <input type="number" name="ingredients[0][quantity]" placeholder="300" step="0.1" required
                                   class="w-24 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <select name="ingredients[0][unit]" required
                                    class="w-24 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="grams">grams</option>
                                <option value="ml">ml</option>
                                <option value="tbsp">tbsp</option>
                                <option value="tsp">tsp</option>
                                <option value="cup">cup</option>
                                <option value="piece">piece</option>
                            </select>
                            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-lg flex-shrink-0">
                                🗑️
                            </button>
                        </div>
                    </div>

                    <button type="button" onclick="addIngredient()" class="flex items-center text-gray-700 hover:text-gray-900 text-sm font-semibold">
                        <span class="mr-2">⊕</span> Add Ingredient Row
                    </button>
                    @error('ingredients')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="space-y-8">
                <!-- Media Upload -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Media Upload</h2>

                    <div class="border-2 border-dashed border-gray-300 rounded p-12 text-center cursor-pointer hover:border-gray-400 transition"
                         onclick="document.getElementById('recipe_image').click()">
                        <div class="text-5xl mb-3">🎥</div>
                        <p class="font-medium text-gray-700 mb-1">Drag and drop your cooking photo/video</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, or WebP up to 50MB</p>
                        <input type="file" id="recipe_image" name="recipe_image" accept="image/*,video/*" style="display: none;"
                               onchange="updateFileName(this, 'recipe_image_name')">
                    </div>
                    <p id="recipe_image_name" class="text-xs text-gray-600 mt-2"></p>
                    @error('recipe_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step-by-Step Instructions -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Step-by-Step Instructions</h2>

                    <div id="instructionsContainer" class="space-y-4 mb-6">
                        <div class="instructionRow flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm stepNumber">1</div>
                            <textarea name="instructions[0][text]" placeholder="Bring a large pot of salted water to a rolling boil..." required
                                      rows="3" class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            <button type="button" onclick="removeInstruction(this)" class="text-gray-400 hover:text-red-500 flex-shrink-0 mt-1">
                                ✕
                            </button>
                        </div>
                    </div>

                    <button type="button" onclick="addInstruction()" class="flex items-center text-gray-700 hover:text-gray-900 text-sm font-semibold">
                        <span class="mr-2">⊕</span> Add Directional Step
                    </button>
                    @error('instructions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recipe Video (Optional) -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Recipe Video (Optional)</h2>

                    <div class="border-2 border-dashed border-gray-300 rounded p-8 text-center cursor-pointer hover:border-gray-400 transition"
                         onclick="document.getElementById('recipe_video').click()">
                        <div class="text-4xl mb-2">🎬</div>
                        <p class="text-sm text-gray-700 font-medium">Upload cooking video</p>
                        <p class="text-xs text-gray-500 mt-1">MP4, WebM, MOV, AVI, MKV up to 500MB</p>
                        <input type="file" id="recipe_video" name="recipe_video" accept="video/*" style="display: none;"
                               onchange="updateFileName(this, 'recipe_video_name')">
                    </div>
                    <p id="recipe_video_name" class="text-xs text-gray-600 mt-2"></p>
                    @error('recipe_video')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-12 flex gap-3 justify-end">
            <a href="{{ route('recipes.index') }}" class="px-6 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium text-sm">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-sm">
                Publish Recipe
            </button>
        </div>

        <!-- Hidden field for publishing -->
        <input type="hidden" name="is_published" value="1">
    </form>
</div>

<!-- Validation Errors Display -->
@if ($errors->any())
    <div class="max-w-7xl mx-auto px-8 py-4 mt-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <h3 class="font-semibold text-red-800 mb-2">Validation Errors:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<script>
let ingredientCount = 1;
let instructionCount = 1;

function addIngredient() {
    const container = document.getElementById('ingredientsContainer');
    const html = `
        <div class="ingredientRow flex items-center gap-3">
            <input type="text" name="ingredients[${ingredientCount}][name]" placeholder="Ingredient name" required
                   class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <input type="number" name="ingredients[${ingredientCount}][quantity]" placeholder="Qty" step="0.1" required
                   class="w-24 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <select name="ingredients[${ingredientCount}][unit]" required
                    class="w-24 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="grams">grams</option>
                <option value="ml">ml</option>
                <option value="tbsp">tbsp</option>
                <option value="tsp">tsp</option>
                <option value="cup">cup</option>
                <option value="piece">piece</option>
            </select>
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-lg flex-shrink-0">
                🗑️
            </button>
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
        <div class="instructionRow flex gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm stepNumber">${stepNum}</div>
            <textarea name="instructions[${instructionCount}][text]" placeholder="Write your instruction..." required
                      rows="3" class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            <button type="button" onclick="removeInstruction(this)" class="text-gray-400 hover:text-red-500 flex-shrink-0 mt-1">
                ✕
            </button>
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

function saveDraft() {
    alert('Draft saving feature coming soon!');
}
</script>
@endsection
