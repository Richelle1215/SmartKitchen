@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Nutrition Analysis</h1>
            <p class="mt-2 text-gray-600">See calories, protein, carbohydrates, fat, and sugar for a recipe.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <label for="recipe_id" class="block text-sm font-medium text-gray-700 mb-2">Select a recipe</label>
                <select id="recipe_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    <option value="">Choose a recipe...</option>
                    @foreach ($recipes as $recipe)
                        <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                    @endforeach
                </select>
                <button id="analyze-btn" class="mt-4 w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700">
                    Analyze Recipe
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Nutrition Summary</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg"><p class="text-sm text-blue-700">Calories</p><p id="calories" class="text-2xl font-bold">0</p></div>
                    <div class="bg-red-50 p-4 rounded-lg"><p class="text-sm text-red-700">Protein</p><p id="protein" class="text-2xl font-bold">0g</p></div>
                    <div class="bg-yellow-50 p-4 rounded-lg"><p class="text-sm text-yellow-700">Carbohydrates</p><p id="carbohydrates" class="text-2xl font-bold">0g</p></div>
                    <div class="bg-orange-50 p-4 rounded-lg"><p class="text-sm text-orange-700">Fat</p><p id="fat" class="text-2xl font-bold">0g</p></div>
                    <div class="bg-pink-50 p-4 rounded-lg col-span-2"><p class="text-sm text-pink-700">Sugar</p><p id="sugar" class="text-2xl font-bold">0g</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const analyzeBtn = document.getElementById('analyze-btn');
    const recipeId = document.getElementById('recipe_id');

    analyzeBtn.addEventListener('click', async () => {
        const id = recipeId.value;
        if (!id) return;

        const response = await fetch('{{ route('nutrition-analysis.analyze') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ recipe_id: id })
        });

        const data = await response.json();
        const nutrition = data.nutrition || {};

        document.getElementById('calories').textContent = nutrition.calories ?? 0;
        document.getElementById('protein').textContent = (nutrition.protein ?? 0) + 'g';
        document.getElementById('carbohydrates').textContent = (nutrition.carbohydrates ?? 0) + 'g';
        document.getElementById('fat').textContent = (nutrition.fat ?? 0) + 'g';
        document.getElementById('sugar').textContent = (nutrition.sugar ?? 0) + 'g';
    });
</script>
@endsection
