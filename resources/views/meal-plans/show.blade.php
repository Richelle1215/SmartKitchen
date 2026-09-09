@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $mealPlan->name }}</h1>
                @if ($mealPlan->description)
                    <p class="text-gray-600 mt-2">{{ $mealPlan->description }}</p>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('meal-plans.destroy', $mealPlan) }}" style="display: inline;" 
                      onsubmit="return confirm('Delete this meal plan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </form>
                <a href="{{ route('meal-plans.shopping-list', $mealPlan) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Shopping List
                </a>
            </div>
        </div>

        <!-- Plan Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Plan Type</p>
                <p class="text-2xl font-bold text-gray-900">{{ ucfirst($mealPlan->meal_type) }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Duration</p>
                <p class="text-2xl font-bold text-gray-900">{{ $mealPlan->start_date->diffInDays($mealPlan->end_date) + 1 }} days</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Meals</p>
                <p class="text-2xl font-bold text-gray-900">{{ $mealPlan->items->count() }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Estimated Cost</p>
                <p class="text-2xl font-bold text-green-600">${{ number_format($totalCost, 2) }}</p>
            </div>
        </div>

        <!-- Nutrition Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-blue-600 text-sm font-medium">Total Calories</p>
                <p class="text-3xl font-bold text-blue-900">{{ number_format($totalNutrition['calories']) }}</p>
            </div>

            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-red-600 text-sm font-medium">Total Protein</p>
                <p class="text-3xl font-bold text-red-900">{{ number_format($totalNutrition['protein']) }}g</p>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <p class="text-yellow-600 text-sm font-medium">Total Carbs</p>
                <p class="text-3xl font-bold text-yellow-900">{{ number_format($totalNutrition['carbs']) }}g</p>
            </div>

            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-orange-600 text-sm font-medium">Total Fat</p>
                <p class="text-3xl font-bold text-orange-900">{{ number_format($totalNutrition['fat']) }}g</p>
            </div>
        </div>

        <!-- Meals by Date -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Meal Schedule</h2>
            </div>

            @foreach ($itemsByDate as $date => $items)
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                    </h3>

                    <div class="space-y-3">
                        @foreach ($items as $item)
                            <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600">{{ ucfirst($item->meal_type) }}</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $item->recipe->title }}</p>
                                    <p class="text-sm text-gray-600">Servings: {{ $item->servings }}</p>
                                </div>

                                <form method="POST" action="{{ route('meal-plans.remove-item', $item) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add More Meals -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add More Meals</h3>

            <form method="POST" action="{{ route('meal-plans.add-item', $mealPlan) }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="meal_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meal Type</label>
                    <select name="meal_type" required
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
                    <select name="recipe_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Recipe</option>
                        @foreach ($itemsByDate->first()->first()->mealPlan->items()->first()->recipe()->get() as $recipe)
                            <option value="">Loading recipes...</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Servings</label>
                    <input type="number" name="servings" value="1" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Add Meal
                    </button>
                </div>
            </form>
        </div>

        <!-- Navigation -->
        <div class="mt-8">
            <a href="{{ route('meal-plans.index') }}" class="text-blue-600 hover:text-blue-700">← Back to Meal Plans</a>
        </div>
    </div>
</div>
@endsection
