@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Meal Plans</h1>
            <a href="{{ route('meal-plans.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                + Create Meal Plan
            </a>
        </div>

        @if ($mealPlans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($mealPlans as $plan)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            
                            @if ($plan->description)
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($plan->description, 100) }}</p>
                            @endif

                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <p><strong>Type:</strong> {{ ucfirst($plan->meal_type) }}</p>
                                <p><strong>Duration:</strong> {{ $plan->start_date->format('M d') }} - {{ $plan->end_date->format('M d, Y') }}</p>
                                <p><strong>Meals:</strong> {{ $plan->items->count() }}</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('meal-plans.show', $plan) }}" class="flex-1 text-center bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 transition">
                                    View
                                </a>
                                <a href="{{ route('meal-plans.edit', $plan) }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('meal-plans.destroy', $plan) }}" style="display: inline;" onsubmit="return confirm('Delete this meal plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-100 text-red-700 px-4 py-2 rounded hover:bg-red-200 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $mealPlans->links() }}
            </div>
        @else
            <div class="text-center bg-gray-50 rounded-lg py-12">
                <p class="text-gray-600 text-lg mb-4">No meal plans yet. Create your first one!</p>
                <a href="{{ route('meal-plans.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Meal Plan
                </a>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('meal-plans.weekly-suggestion') }}" class="bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-lg p-8 shadow-lg hover:shadow-xl transition">
                <div class="text-3xl mb-2">📅</div>
                <h3 class="text-2xl font-bold mb-2">Weekly Suggestion</h3>
                <p class="text-purple-100">Get AI-powered meal plan suggestions for the week</p>
            </a>

            <a href="{{ route('cost-calculator.index') }}" class="bg-gradient-to-br from-green-500 to-blue-500 text-white rounded-lg p-8 shadow-lg hover:shadow-xl transition">
                <div class="text-3xl mb-2">💰</div>
                <h3 class="text-2xl font-bold mb-2">Cost Calculator</h3>
                <p class="text-green-100">Calculate recipe costs and budget your meals</p>
            </a>
        </div>
    </div>
</div>
@endsection
