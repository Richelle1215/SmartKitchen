@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Weekly Meal Plan Suggestion</h1>
            <a href="{{ route('meal-plans.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                + Use This Plan
            </a>
        </div>

        <p class="text-gray-600 mb-8">
            AI-powered meal suggestions for the week starting {{ $startDate->format('F d, Y') }}
        </p>

        <!-- Weekly Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($weeklyPlan as $date => $meals)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
                        <h3 class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($date)->format('l') }}
                        </h3>
                        <p class="text-blue-100">{{ \Carbon\Carbon::parse($date)->format('M d') }}</p>
                    </div>

                    <div class="p-4 space-y-4">
                        <!-- Breakfast -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Breakfast</p>
                            @if ($meals['breakfast'])
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $meals['breakfast']->title }}</p>
                                <p class="text-sm text-gray-600">⏱️ {{ $meals['breakfast']->prep_time }} min</p>
                                <a href="{{ route('recipes.show', $meals['breakfast']) }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">
                                    View Recipe →
                                </a>
                            @else
                                <p class="text-gray-400 italic">No suggestion</p>
                            @endif
                        </div>

                        <hr class="border-gray-200">

                        <!-- Lunch -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Lunch</p>
                            @if ($meals['lunch'])
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $meals['lunch']->title }}</p>
                                <p class="text-sm text-gray-600">⏱️ {{ $meals['lunch']->prep_time }} min</p>
                                <a href="{{ route('recipes.show', $meals['lunch']) }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">
                                    View Recipe →
                                </a>
                            @else
                                <p class="text-gray-400 italic">No suggestion</p>
                            @endif
                        </div>

                        <hr class="border-gray-200">

                        <!-- Dinner -->
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Dinner</p>
                            @if ($meals['dinner'])
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $meals['dinner']->title }}</p>
                                <p class="text-sm text-gray-600">⏱️ {{ $meals['dinner']->prep_time }} min</p>
                                <a href="{{ route('recipes.show', $meals['dinner']) }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">
                                    View Recipe →
                                </a>
                            @else
                                <p class="text-gray-400 italic">No suggestion</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tips -->
        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">💡 Planning Tips</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Prep Ahead</h3>
                    <p class="text-gray-700">Consider recipes with similar ingredients to save shopping time and reduce waste.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Flexible Schedule</h3>
                    <p class="text-gray-700">Feel free to swap meals between days based on your availability and preferences.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Budget Friendly</h3>
                    <p class="text-gray-700">Use the cost calculator to estimate your grocery spending before shopping.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Check Pantry</h3>
                    <p class="text-gray-700">Review your pantry items and use what you already have on hand.</p>
                </div>
            </div>
        </div>

        <!-- Create Meal Plan from This Suggestion -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Create Meal Plan</h2>
            
            <p class="text-gray-700 mb-6">
                Ready to use this weekly plan? Click below to create a new meal plan with these suggestions.
            </p>

            <a href="{{ route('meal-plans.create') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                Create Meal Plan
            </a>
        </div>

        <!-- Back Link -->
        <div class="mt-8">
            <a href="{{ route('meal-plans.index') }}" class="text-blue-600 hover:text-blue-700">← Back to Meal Plans</a>
        </div>
    </div>
</div>
@endsection
