@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Meal Plan</h1>

        <form method="POST" action="{{ route('meal-plans.update', $mealPlan) }}" class="bg-white rounded-lg shadow-md p-8 space-y-8">
            @csrf
            @method('PATCH')

            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Meal Plan Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Meal Plan Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $mealPlan->name) }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description', $mealPlan->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $mealPlan->start_date->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $mealPlan->end_date->format('Y-m-d')) }}" required
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
                            <option value="daily" {{ old('meal_type', $mealPlan->meal_type) == 'daily' ? 'selected' : '' }}>Daily Plan</option>
                            <option value="weekly" {{ old('meal_type', $mealPlan->meal_type) == 'weekly' ? 'selected' : '' }}>Weekly Plan</option>
                            <option value="custom" {{ old('meal_type', $mealPlan->meal_type) == 'custom' ? 'selected' : '' }}>Custom Plan</option>
                        </select>
                        @error('meal_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Meals -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Meals</h2>
                
                <div class="space-y-3">
                    @forelse ($mealPlan->items as $item)
                        <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ ucfirst($item->meal_type) }} - {{ $item->meal_date->format('M d, Y') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $item->recipe->title }}</p>
                            </div>

                            <form method="POST" action="{{ route('meal-plans.remove-item', $item) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-600">No meals added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    Update Meal Plan
                </button>
                <a href="{{ route('meal-plans.show', $mealPlan) }}" class="flex-1 text-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
