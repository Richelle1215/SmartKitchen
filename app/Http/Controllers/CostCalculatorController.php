<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class CostCalculatorController extends Controller
{
    /**
     * Show cost calculator page
     */
    public function index()
    {
        $recipes = Recipe::where('is_published', true)
                        ->with('ingredients')
                        ->latest()
                        ->paginate(12);

        return view('calculators.cost-calculator', compact('recipes'));
    }

    /**
     * Calculate cost for a recipe
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => ['nullable', 'exists:recipes,id'],
            'ingredients' => ['required_without:recipe_id', 'array'],
            'ingredients.*.name' => ['required_with:ingredients', 'string'],
            'ingredients.*.quantity' => ['required_with:ingredients', 'numeric', 'min:0'],
            'ingredients.*.price' => ['required_with:ingredients', 'numeric', 'min:0'],
            'servings' => ['required', 'integer', 'min:1'],
        ]);

        $servings = $validated['servings'];
        $ingredients = [];
        $totalCost = 0;

        if ($validated['recipe_id'] ?? false) {
            // Use recipe ingredients
            $recipe = Recipe::find($validated['recipe_id'])->load('ingredients');
            
            foreach ($recipe->ingredients as $ingredient) {
                $cost = ($request->input("ingredient_prices.{$ingredient->id}") ?? 0);
                $totalCost += $cost;
                
                $ingredients[] = [
                    'name' => $ingredient->ingredient_name,
                    'quantity' => $ingredient->quantity,
                    'unit' => $ingredient->unit,
                    'price' => $cost,
                    'total' => $cost,
                ];
            }
        } else {
            // Use provided ingredients
            foreach ($validated['ingredients'] as $ingredient) {
                $totalCost += $ingredient['price'];
                
                $ingredients[] = [
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'price' => $ingredient['price'],
                    'total' => $ingredient['price'],
                ];
            }
        }

        $costPerServing = $totalCost / $servings;

        return response()->json([
            'total_cost' => round($totalCost, 2),
            'cost_per_serving' => round($costPerServing, 2),
            'servings' => $servings,
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Get recipe cost breakdown
     */
    public function recipeCost(Recipe $recipe)
    {
        $recipe->load('ingredients');

        $breakdown = [];
        $totalCost = 0;

        foreach ($recipe->ingredients as $ingredient) {
            $cost = 0; // Default - user would provide actual prices
            $totalCost += $cost;
            
            $breakdown[] = [
                'name' => $ingredient->ingredient_name,
                'quantity' => $ingredient->quantity,
                'unit' => $ingredient->unit,
                'estimated_price' => $cost,
            ];
        }

        return response()->json([
            'recipe' => [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'servings' => $recipe->servings,
            ],
            'total_cost' => $totalCost,
            'cost_per_serving' => $recipe->servings > 0 ? round($totalCost / $recipe->servings, 2) : 0,
            'ingredients' => $breakdown,
        ]);
    }
}
