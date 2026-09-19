@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">💰 Cost Calculator</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calculator Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Calculate Recipe Cost</h2>

                    <form id="calculatorForm" class="space-y-6">
                        @csrf

                        <!-- Mode Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Calculator Mode</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="mode" value="recipe" checked class="mode-radio">
                                    <span class="ml-2">Select Recipe</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="mode" value="custom" class="mode-radio">
                                    <span class="ml-2">Custom Ingredients</span>
                                </label>
                            </div>
                        </div>

                        <!-- Recipe Mode -->
                        <div id="recipeMode" class="space-y-4">
                            <div>
                                <label for="recipe_id" class="block text-sm font-medium text-gray-700 mb-1">Select Recipe</label>
                                <select id="recipe_id" name="recipe_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a recipe...</option>
                                    @foreach ($recipes as $recipe)
                                        <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="recipeIngredientsContainer"></div>
                        </div>

                        <!-- Custom Mode -->
                        <div id="customMode" style="display: none;" class="space-y-4">
                            <div id="customIngredientsContainer">
                                <div class="ingredient-row p-4 bg-gray-50 rounded-lg space-y-2">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                        <input type="text" placeholder="Ingredient name" name="ingredients[0][name]"
                                               class="px-3 py-2 border border-gray-300 rounded-lg">
                                        <input type="number" placeholder="Quantity" name="ingredients[0][quantity]" step="0.1"
                                               class="px-3 py-2 border border-gray-300 rounded-lg">
                                        <input type="number" placeholder="Price (₱)" name="ingredients[0][price]" step="0.01" min="0"
                                               class="px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <button type="button" class="text-red-600 hover:text-red-700 text-sm remove-custom-ingredient">
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="addCustomIngredientBtn" class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200">
                                + Add Ingredient
                            </button>
                        </div>

                        <!-- Servings -->
                        <div>
                            <label for="servings" class="block text-sm font-medium text-gray-700 mb-1">Number of Servings</label>
                            <input type="number" id="servings" name="servings" value="1" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Calculate Button -->
                        <button type="button" id="calculateBtn" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            Calculate Cost
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results Panel -->
            <div class="lg:col-span-1">
                <div id="resultsPanel" style="display: none;" class="bg-white rounded-lg shadow-md p-8 sticky top-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Results</h3>

                    <div class="space-y-4">
                        <div id="calcError" class="hidden rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"></div>

                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-blue-600 text-sm">Total Cost</p>
                            <p id="totalCost" class="text-3xl font-bold text-blue-900">₱0.00</p>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-green-600 text-sm">Cost Per Serving</p>
                            <p id="costPerServing" class="text-3xl font-bold text-green-900">₱0.00</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm">Servings</p>
                            <p id="resultServings" class="text-2xl font-bold text-gray-900">1</p>
                        </div>

                        <button id="printResultsBtn" class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            🖨️ Print
                        </button>
                    </div>

                    <div id="ingredientBreakdown" class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Ingredient Breakdown</h4>
                        <div id="breakdownList" class="space-y-2 text-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipes Grid -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Popular Recipes</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($recipes->take(8) as $recipe)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer"
                         onclick="selectRecipe({{ $recipe->id }}, '{{ addslashes($recipe->title) }}')">
                        <div class="bg-gray-200 h-40 flex items-center justify-center text-4xl">
                            🍳
                        </div>

                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $recipe->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $recipe->ingredients->count() }} ingredients</p>
                            <p class="text-sm text-gray-600">⏱️ {{ $recipe->prep_time }} min</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $recipes->links() }}
            </div>
        </div>
    </div>
</div>

<script>
let customIngredientCount = 1;

document.querySelectorAll('.mode-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('recipeMode').style.display = this.value === 'recipe' ? 'block' : 'none';
        document.getElementById('customMode').style.display = this.value === 'custom' ? 'block' : 'none';
        document.getElementById('resultsPanel').style.display = 'none';
    });
});

function showCalcError(message) {
    const errorBox = document.getElementById('calcError');
    errorBox.textContent = message;
    errorBox.classList.remove('hidden');
}

function clearCalcError() {
    const errorBox = document.getElementById('calcError');
    errorBox.textContent = '';
    errorBox.classList.add('hidden');
}

document.getElementById('recipe_id').addEventListener('change', function() {
    if (this.value) {
        fetch(`/recipes/${this.value}/cost`)
            .then(async (res) => {
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message || 'Unable to load recipe ingredients.');
                }
                return data;
            })
            .then(data => {
                let html = '';
                if (!data.ingredients || data.ingredients.length === 0) {
                    html = '<p class="text-sm text-gray-500">No ingredients are available for this recipe yet.</p>';
                } else {
                    data.ingredients.forEach(ing => {
                        html += `
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <p class="text-sm text-gray-600">${ing.name}</p>
                                    </div>
                                    <div class="text-right">
                                        <input type="number" placeholder="Price" name="ingredient_prices[${ing.id}]" value="0" 
                                               step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded">
                                        <p class="text-sm text-gray-600 mt-1">${ing.quantity} ${ing.unit}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                document.getElementById('recipeIngredientsContainer').innerHTML = html;
                clearCalcError();
            })
            .catch(error => {
                showCalcError(error.message || 'Unable to load recipe ingredients.');
            });
    } else {
        document.getElementById('recipeIngredientsContainer').innerHTML = '';
    }
});

document.getElementById('addCustomIngredientBtn').addEventListener('click', function() {
    const container = document.getElementById('customIngredientsContainer');
    const html = `
        <div class="ingredient-row p-4 bg-gray-50 rounded-lg space-y-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <input type="text" placeholder="Ingredient name" name="ingredients[${customIngredientCount}][name]"
                       class="px-3 py-2 border border-gray-300 rounded-lg">
                <input type="number" placeholder="Quantity" name="ingredients[${customIngredientCount}][quantity]" step="0.1"
                       class="px-3 py-2 border border-gray-300 rounded-lg">
                <input type="number" placeholder="Price (₱)" name="ingredients[${customIngredientCount}][price]" step="0.01" min="0"
                       class="px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="button" class="text-red-600 hover:text-red-700 text-sm remove-custom-ingredient">
                Remove
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    customIngredientCount++;
    attachRemoveListener();
});

function attachRemoveListener() {
    document.querySelectorAll('.remove-custom-ingredient').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.ingredient-row').remove();
        });
    });
}

document.getElementById('calculateBtn').addEventListener('click', function() {
    const mode = document.querySelector('input[name="mode"]:checked').value;
    const servings = parseInt(document.getElementById('servings').value) || 1;

    if (mode === 'recipe' && !document.getElementById('recipe_id').value) {
        showCalcError('Please select a recipe before calculating.');
        return;
    }

    const formData = new FormData(document.getElementById('calculatorForm'));
    formData.append('servings', servings);

    fetch('/cost-calculator/calculate', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok) {
            const firstError = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'Unable to calculate the recipe cost.');
            throw new Error(firstError);
        }
        return data;
    })
    .then(data => {
        const currency = data.currency || '₱';
        document.getElementById('totalCost').textContent = currency + Number(data.total_cost || 0).toFixed(2);
        document.getElementById('costPerServing').textContent = currency + Number(data.cost_per_serving || 0).toFixed(2);
        document.getElementById('resultServings').textContent = data.servings || servings;

        let breakdown = '';
        const ingredients = Array.isArray(data.ingredients) ? data.ingredients : [];
        if (ingredients.length === 0) {
            breakdown = '<p class="text-sm text-gray-500">No ingredient details were returned for this calculation.</p>';
        } else {
            ingredients.forEach(ing => {
                breakdown += `
                    <div class="flex justify-between py-1 border-b border-gray-200">
                        <span class="text-gray-700">${ing.name}</span>
                        <span class="font-semibold">${currency}${Number(ing.total ?? 0).toFixed(2)}</span>
                    </div>
                `;
            });
        }

        document.getElementById('breakdownList').innerHTML = breakdown;
        document.getElementById('resultsPanel').style.display = 'block';
        clearCalcError();
    })
    .catch(err => {
        showCalcError(err.message || 'Something went wrong while calculating the cost.');
    });
});

function selectRecipe(id, name) {
    document.querySelector('input[value="recipe"]').checked = true;
    document.getElementById('recipeMode').style.display = 'block';
    document.getElementById('customMode').style.display = 'none';
    document.getElementById('recipe_id').value = id;
    document.getElementById('recipe_id').dispatchEvent(new Event('change'));
}

attachRemoveListener();
</script>
@endsection
