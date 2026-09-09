@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Create Meal Plan</h1>

        <form method="POST" action="{{ route('meal-plans.store') }}" class="bg-white rounded-lg shadow-md p-8 space-y-8">
            @csrf

            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Meal Plan Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Meal Plan Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Weekly Healthy Eating">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add notes about your meal plan...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-1">Plan Type *</label>
                        <select id="meal_type" name="meal_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a type</option>
                            <option value="daily" {{ old('meal_type') == 'daily' ? 'selected' : '' }}>Daily Plan</option>
                            <option value="weekly" {{ old('meal_type') == 'weekly' ? 'selected' : '' }}>Weekly Plan</option>
                            <option value="custom" {{ old('meal_type') == 'custom' ? 'selected' : '' }}>Custom Plan</option>
                        </select>
                        @error('meal_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Add Meals -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Add Meals</h2>
                
                <div id="meals-container" class="space-y-4">
                    <div class="meal-item bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input type="date" name="meals[0][meal_date]" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meal Type</label>
                                <select name="meals[0][meal_type]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="breakfast">Breakfast</option>
                                    <option value="lunch">Lunch</option>
                                    <option value="dinner">Dinner</option>
                                    <option value="snack">Snack</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Recipe</label>
                                <select name="meals[0][recipe_id]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Recipe</option>
                                    @foreach ($recipes as $recipe)
                                        <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Servings</label>
                                <input type="number" name="meals[0][servings]" value="1" min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <button type="button" class="mt-2 text-red-600 hover:text-red-700 text-sm font-medium remove-meal">
                            Remove Meal
                        </button>
                    </div>
                </div>

                <button type="button" id="add-meal-btn" class="mt-4 bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 transition">
                    + Add Another Meal
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Create Meal Plan
                </button>
                <a href="{{ route('meal-plans.index') }}" class="flex-1 text-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let mealIndex = 1;

document.getElementById('add-meal-btn').addEventListener('click', function() {
    const container = document.getElementById('meals-container');
    const newMeal = document.querySelector('.meal-item').cloneNode(true);
    
    // Update all input names for the new meal
    newMeal.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${mealIndex}]`);
        el.value = '';
    });
    
    container.appendChild(newMeal);
    attachRemoveListener(newMeal);
    mealIndex++;
});

function attachRemoveListener(element) {
    element.querySelector('.remove-meal').addEventListener('click', function() {
        element.remove();
    });
}

// Attach listeners to initial meal
document.querySelectorAll('.meal-item').forEach(attachRemoveListener);
</script>
@endsection
