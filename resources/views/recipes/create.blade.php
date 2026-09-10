@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Recipe</h1>
                <p class="text-gray-600 mt-1">Share your culinary discovery with the global community.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="saveDraft()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    Save Draft
                </button>
                <button form="recipeForm" type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                    Publish Recipe
                </button>
            </div>
        </div>

        <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg p-8 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Recipe Details</h2>

                        <!-- Recipe Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Recipe Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Tuscan Garlic Lemon Pasta">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                            <textarea id="description" name="description" rows="3" value="{{ old('description') }}"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="A creamy, delicious one-pot pasta...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category & Basic Info -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select id="category_id" name="category_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Category</option>
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

                            <div>
                                <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">Prep Time (min)</label>
                                <input type="number" id="prep_time" name="prep_time" value="{{ old('prep_time') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="30">
                                @error('prep_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">Servings</label>
                                <input type="number" id="servings" name="servings" value="{{ old('servings') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="4">
                                @error('servings')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients Section -->
                    <div class="bg-white rounded-lg p-8 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Ingredients</h2>

                        <div id="ingredientsContainer" class="space-y-3 mb-4">
                            <div class="ingredientRow flex gap-3 items-end">
                                <input type="text" name="ingredients[0][name]" placeholder="Fresh Spaghetti" required
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <input type="number" name="ingredients[0][quantity]" placeholder="300" step="0.1" required
                                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <select name="ingredients[0][unit]" required
                                        class="w-20 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="grams">grams</option>
                                    <option value="ml">ml</option>
                                    <option value="tbsp">tbsp</option>
                                    <option value="tsp">tsp</option>
                                    <option value="cup">cup</option>
                                    <option value="piece">piece</option>
                                </select>
                                <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-2xl leading-none">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <button type="button" onclick="addIngredient()" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <span class="text-xl mr-1">+</span> Add Ingredient Row
                        </button>
                        @error('ingredients')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions Section -->
                    <div class="bg-white rounded-lg p-8 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Step-by-Step Instructions</h2>

                        <div id="instructionsContainer" class="space-y-4 mb-4">
                            <div class="instructionRow flex gap-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm stepNumber">1</div>
                                <div class="flex-1">
                                    <textarea name="instructions[0][text]" placeholder="Bring a large pot of salted water to a rolling boil..." required
                                              rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                </div>
                                <button type="button" onclick="removeInstruction(this)" class="text-red-500 hover:text-red-700 self-start mt-2">
                                    <span class="text-2xl leading-none">✕</span>
                                </button>
                            </div>
                        </div>

                        <button type="button" onclick="addInstruction()" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <span class="text-xl mr-1">+</span> Add Directional Step
                        </button>
                        @error('instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Media Upload -->
                <div>
                    <div class="bg-white rounded-lg p-8 shadow-sm sticky top-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Media Upload</h3>

                        <!-- Recipe Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 transition"
                                 onclick="document.getElementById('recipe_image').click()">
                                <div class="text-4xl mb-2">📸</div>
                                <p class="text-sm text-gray-600">Drag and drop your cooking photo/video</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG or MP4 up to 5MB</p>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Video (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 transition"
                                 onclick="document.getElementById('recipe_video').click()">
                                <div class="text-4xl mb-2">🎥</div>
                                <p class="text-sm text-gray-600">Upload cooking video</p>
                                <p class="text-xs text-gray-500 mt-1">MP4 up to 20MB</p>
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
        <div class="ingredientRow flex gap-3 items-end">
            <input type="text" name="ingredients[${ingredientCount}][name]" placeholder="Ingredient name" required
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <input type="number" name="ingredients[${ingredientCount}][quantity]" placeholder="0" step="0.1" required
                   class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <select name="ingredients[${ingredientCount}][unit]" required
                    class="w-20 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="grams">grams</option>
                <option value="ml">ml</option>
                <option value="tbsp">tbsp</option>
                <option value="tsp">tsp</option>
                <option value="cup">cup</option>
                <option value="piece">piece</option>
            </select>
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 text-2xl leading-none">
                ✕
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
    const html = `
        <div class="instructionRow flex gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm stepNumber">${instructionCount + 1}</div>
            <div class="flex-1">
                <textarea name="instructions[${instructionCount}][text]" placeholder="Write your instruction..." required
                          rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
            <button type="button" onclick="removeInstruction(this)" class="text-red-500 hover:text-red-700 self-start mt-2">
                <span class="text-2xl leading-none">✕</span>
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
