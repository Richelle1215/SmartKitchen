@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Recipe</h1>
                <p class="text-gray-600 mt-1">Share your culinary discovery with the global community.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="saveDraft()" class="px-6 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium">
                    Save Draft
                </button>
                <button form="recipeForm" type="submit" class="px-6 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 font-medium">
                    Publish Recipe
                </button>
            </div>
        </div>

        <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-3 gap-8">
                <!-- Left Column - Recipe Details -->
                <div class="col-span-1">
                    <div class="bg-white p-8 rounded space-y-6">
                        <!-- Recipe Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Recipe Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400"
                                   placeholder="Tuscan Garlic Lemon Pasta">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Short Description</label>
                            <textarea id="description" name="description" rows="3" value="{{ old('description') }}"
                                      class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm"
                                      placeholder="A creamy, delicious one-pot pasta...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                            <select id="category_id" name="category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400">
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

                        <!-- Prep Time -->
                        <div>
                            <label for="prep_time" class="block text-sm font-semibold text-gray-900 mb-2">Prep Time (min)</label>
                            <input type="number" id="prep_time" name="prep_time" value="{{ old('prep_time') }}" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400"
                                   placeholder="30">
                            @error('prep_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Servings -->
                        <div>
                            <label for="servings" class="block text-sm font-semibold text-gray-900 mb-2">Servings</label>
                            <input type="number" id="servings" name="servings" value="{{ old('servings') }}" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400"
                                   placeholder="4">
                            @error('servings')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ingredients -->
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Ingredients</h2>

                            <div id="ingredientsContainer" class="space-y-3 mb-4">
                                <div class="ingredientRow flex items-center gap-2">
                                    <input type="text" name="ingredients[0][name]" placeholder="Fresh Spaghetti" required
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
                                    <input type="number" name="ingredients[0][quantity]" placeholder="300" step="0.1" required
                                           class="w-20 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
                                    <select name="ingredients[0][unit]" required
                                            class="w-20 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
                                        <option value="grams">grams</option>
                                        <option value="ml">ml</option>
                                        <option value="tbsp">tbsp</option>
                                        <option value="tsp">tsp</option>
                                        <option value="cup">cup</option>
                                        <option value="piece">piece</option>
                                    </select>
                                    <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-lg leading-none p-1">
                                        🗑️
                                    </button>
                                </div>
                            </div>

                            <button type="button" onclick="addIngredient()" class="flex items-center text-gray-700 hover:text-gray-900 text-sm font-medium">
                                <span class="mr-1">⊕</span> Add Ingredient Row
                            </button>
                            @error('ingredients')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Media & Instructions -->
                <div class="col-span-2 space-y-8">
                    <!-- Media Upload -->
                    <div class="bg-white p-8 rounded">
                        <h2 class="text-sm font-bold text-gray-900 mb-6">Media Upload</h2>

                        <div class="border-2 border-dashed border-gray-300 rounded p-12 text-center cursor-pointer hover:border-gray-400 transition"
                             onclick="document.getElementById('recipe_image').click()">
                            <div class="text-5xl mb-4">🎥</div>
                            <p class="font-medium text-gray-700 mb-1">Drag and drop your cooking photo/video</p>
                            <p class="text-xs text-gray-500">PNG, JPG or MP4 up to 5MB</p>
                            <input type="file" id="recipe_image" name="recipe_image" accept="image/*,video/*" style="display: none;"
                                   onchange="updateFileName(this, 'recipe_image_name')">
                        </div>
                        <p id="recipe_image_name" class="text-xs text-gray-600 mt-2"></p>
                        @error('recipe_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Step-by-Step Instructions -->
                    <div class="bg-white p-8 rounded">
                        <h2 class="text-sm font-bold text-gray-900 mb-6">Step-by-Step Instructions</h2>

                        <div id="instructionsContainer" class="space-y-4 mb-4">
                            <div class="instructionRow flex gap-4">
                                <div class="flex-shrink-0 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs stepNumber">1</div>
                                <textarea name="instructions[0][text]" placeholder="Bring a large pot of salted water to a rolling boil..." required
                                          rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm"></textarea>
                                <button type="button" onclick="removeInstruction(this)" class="text-gray-400 hover:text-red-500 flex-shrink-0 mt-1">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <button type="button" onclick="addInstruction()" class="flex items-center text-gray-700 hover:text-gray-900 text-sm font-medium">
                            <span class="mr-1">⊕</span> Add Directional Step
                        </button>
                        @error('instructions')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recipe Video (Optional) -->
                    <div class="bg-white p-8 rounded">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Recipe Video (Optional)</h2>

                        <div class="border-2 border-dashed border-gray-300 rounded p-8 text-center cursor-pointer hover:border-gray-400 transition"
                             onclick="document.getElementById('recipe_video').click()">
                            <div class="text-4xl mb-2">🎬</div>
                            <p class="text-sm text-gray-600">Upload cooking video</p>
                            <p class="text-xs text-gray-500 mt-1">MP4 up to 20MB</p>
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
                <a href="{{ route('recipes.index') }}" class="px-6 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 font-medium">
                    Publish Recipe
                </button>
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
        <div class="ingredientRow flex items-center gap-2">
            <input type="text" name="ingredients[${ingredientCount}][name]" placeholder="Ingredient name" required
                   class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
            <input type="number" name="ingredients[${ingredientCount}][quantity]" placeholder="Qty" step="0.1" required
                   class="w-20 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
            <select name="ingredients[${ingredientCount}][unit]" required
                    class="w-20 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm">
                <option value="grams">grams</option>
                <option value="ml">ml</option>
                <option value="tbsp">tbsp</option>
                <option value="tsp">tsp</option>
                <option value="cup">cup</option>
                <option value="piece">piece</option>
            </select>
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-lg leading-none p-1">
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
            <div class="flex-shrink-0 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs stepNumber">${stepNum}</div>
            <textarea name="instructions[${instructionCount}][text]" placeholder="Write your instruction..." required
                      rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-sm"></textarea>
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
