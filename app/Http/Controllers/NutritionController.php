<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Services\NutritionService;
use Illuminate\Http\Request;

class NutritionController extends Controller
{
    public function __construct(protected NutritionService $nutritionService)
    {
    }

    public function index()
    {
        $recipes = Recipe::where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('nutrition.index', compact('recipes'));
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => ['nullable', 'exists:recipes,id'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.name' => ['required_with:ingredients', 'string'],
            'ingredients.*.quantity' => ['required_with:ingredients', 'numeric', 'min:0'],
            'servings' => ['nullable', 'integer', 'min:1'],
        ]);

        if (! empty($validated['recipe_id'])) {
            $recipe = Recipe::with('ingredients')->findOrFail($validated['recipe_id']);
            $nutrition = $this->nutritionService->analyzeRecipe($recipe);
        } else {
            $nutrition = [
                'calories' => 0,
                'protein' => 0,
                'carbohydrates' => 0,
                'fat' => 0,
                'sugar' => 0,
            ];
        }

        return response()->json([
            'currency' => '₱',
            'nutrition' => $nutrition,
        ]);
    }
}
