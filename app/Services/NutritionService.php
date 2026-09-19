<?php

namespace App\Services;

use App\Models\Recipe;

class NutritionService
{
    public function analyzeRecipe(Recipe $recipe): array
    {
        $recipe->loadMissing('ingredients');

        $calories = 0;
        $protein = 0;
        $carbohydrates = 0;
        $fat = 0;
        $sugar = 0;

        foreach ($recipe->ingredients as $ingredient) {
            $name = strtolower(trim((string) $ingredient->ingredient_name));

            $factor = max(1, (float) $ingredient->quantity ?? 1);

            if (str_contains($name, 'chicken')) {
                $calories += 165 * $factor;
                $protein += 31 * $factor;
                $carbohydrates += 0 * $factor;
                $fat += 3.6 * $factor;
                $sugar += 0 * $factor;
                continue;
            }

            if (str_contains($name, 'rice')) {
                $calories += 130 * $factor;
                $protein += 2.7 * $factor;
                $carbohydrates += 28 * $factor;
                $fat += 0.3 * $factor;
                $sugar += 0 * $factor;
                continue;
            }

            if (str_contains($name, 'vegetable') || str_contains($name, 'spinach') || str_contains($name, 'broccoli') || str_contains($name, 'tomato')) {
                $calories += 35 * $factor;
                $protein += 2 * $factor;
                $carbohydrates += 7 * $factor;
                $fat += 0.4 * $factor;
                $sugar += 2 * $factor;
                continue;
            }

            if (str_contains($name, 'egg')) {
                $calories += 70 * $factor;
                $protein += 6 * $factor;
                $carbohydrates += 0.4 * $factor;
                $fat += 5 * $factor;
                $sugar += 0.2 * $factor;
                continue;
            }

            if (str_contains($name, 'oil') || str_contains($name, 'butter')) {
                $calories += 120 * $factor;
                $protein += 0 * $factor;
                $carbohydrates += 0 * $factor;
                $fat += 14 * $factor;
                $sugar += 0 * $factor;
                continue;
            }

            if (str_contains($name, 'fruit') || str_contains($name, 'banana') || str_contains($name, 'apple')) {
                $calories += 95 * $factor;
                $protein += 1 * $factor;
                $carbohydrates += 25 * $factor;
                $fat += 0.3 * $factor;
                $sugar += 19 * $factor;
                continue;
            }

            $calories += 50 * $factor;
            $protein += 2 * $factor;
            $carbohydrates += 8 * $factor;
            $fat += 2 * $factor;
            $sugar += 2 * $factor;
        }

        return [
            'calories' => round($calories, 1),
            'protein' => round($protein, 1),
            'carbohydrates' => round($carbohydrates, 1),
            'fat' => round($fat, 1),
            'sugar' => round($sugar, 1),
        ];
    }
}
