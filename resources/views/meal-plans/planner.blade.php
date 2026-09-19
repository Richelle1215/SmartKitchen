@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Meal Planner</h1>
                <p class="mt-2 text-gray-600">Plan breakfast, lunch, and dinner for the week.</p>
            </div>
            <a href="{{ route('meal-plans.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                + Add Meal Plan
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-4">
            @foreach ($week as $dayName => $day)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 min-h-[320px]">
                    <div class="mb-4 border-b border-gray-200 pb-2">
                        <h2 class="text-lg font-bold text-gray-900">{{ $dayName }}</h2>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($day['date'])->format('M d') }}</p>
                    </div>

                    @php
                        $slots = ['breakfast' => 'Breakfast', 'lunch' => 'Lunch', 'dinner' => 'Dinner'];
                    @endphp

                    @foreach ($slots as $key => $label)
                        <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                            </div>

                            @if ($day[$key])
                                @php $meal = $day[$key]; @endphp
                                <div class="text-sm text-gray-900 font-medium">{{ $meal->recipe->title }}</div>
                                <div class="mt-1 text-xs text-gray-500">Servings: {{ $meal->servings }}</div>

                                <form method="POST" action="{{ route('meal-plans.update-item', $meal) }}" class="mt-3 space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="meal_date" value="{{ $day['date'] }}">
                                    <input type="hidden" name="meal_type" value="{{ $key }}">
                                    <select name="recipe_id" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                        @foreach ($recipes as $recipe)
                                            <option value="{{ $recipe->id }}" {{ $recipe->id == $meal->recipe_id ? 'selected' : '' }}>
                                                {{ $recipe->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="servings" min="1" value="{{ $meal->servings }}" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                    <button type="submit" class="w-full bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1.5 rounded hover:bg-blue-200">
                                        Save
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('meal-plans.remove-item', $meal) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-700">Remove</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('meal-plans.add-item', $mealPlan) }}" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="meal_date" value="{{ $day['date'] }}">
                                    <input type="hidden" name="meal_type" value="{{ $key }}">
                                    <select name="recipe_id" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                        <option value="">Choose recipe</option>
                                        @foreach ($recipes as $recipe)
                                            <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="servings" value="1" min="1" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                    <button type="submit" class="w-full bg-green-100 text-green-700 text-xs font-medium px-2 py-1.5 rounded hover:bg-green-200">
                                        Add
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
