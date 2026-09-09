<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\IngredientSubstitution;
use App\Models\PantryItem;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    /**
     * Show AI Cooking Assistant page
     */
    public function index()
    {
        return view('ai.assistant');
    }

    /**
     * Get AI chatbot response
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = $request->message;
        $response = $this->generateAIResponse($message);

        return response()->json([
            'response' => $response,
            'timestamp' => now(),
        ]);
    }

    /**
     * Get ingredient substitutions
     */
    public function getSubstitutions(Request $request)
    {
        $request->validate([
            'ingredient' => ['required', 'string'],
        ]);

        $ingredient = $request->ingredient;

        // Search for substitutions
        $substitutions = IngredientSubstitution::where('ingredient', 'like', "%{$ingredient}%")
                                              ->orWhere('substitute', 'like', "%{$ingredient}%")
                                              ->get();

        return response()->json([
            'ingredient' => $ingredient,
            'substitutions' => $substitutions->map(function ($sub) use ($ingredient) {
                return [
                    'from' => $sub->ingredient,
                    'to' => $sub->substitute,
                    'ratio' => $sub->ratio,
                    'notes' => $sub->notes,
                    'category' => $sub->category,
                ];
            }),
        ]);
    }

    /**
     * Get recipe suggestions based on available ingredients (Smart Pantry)
     */
    public function suggestRecipes()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        // Get user's pantry items
        $pantryItems = $user->pantryItems()
                            ->where('quantity', '>', 0)
                            ->pluck('ingredient_name')
                            ->toArray();

        if (empty($pantryItems)) {
            return response()->json([
                'message' => 'Your pantry is empty. Add ingredients to get recipe suggestions.',
                'recipes' => [],
            ]);
        }

        // Find recipes matching pantry items
        $recipes = Recipe::where('is_published', true)
                        ->with('ingredients')
                        ->get()
                        ->map(function ($recipe) use ($pantryItems) {
                            $recipeIngredients = $recipe->ingredients->pluck('ingredient_name')->toArray();
                            $matches = array_intersect($pantryItems, $recipeIngredients);
                            $matchPercentage = count($recipeIngredients) > 0 
                                ? round((count($matches) / count($recipeIngredients)) * 100)
                                : 0;

                            return [
                                'recipe' => $recipe,
                                'match_count' => count($matches),
                                'match_percentage' => $matchPercentage,
                                'missing_ingredients' => array_diff($recipeIngredients, $pantryItems),
                            ];
                        })
                        ->filter(fn($item) => $item['match_percentage'] > 0)
                        ->sortByDesc('match_percentage')
                        ->take(6)
                        ->values();

        return response()->json([
            'message' => 'Based on your pantry, here are recipes you can make:',
            'recipes' => $recipes,
        ]);
    }

    /**
     * Get cooking tips
     */
    public function getTips(Request $request)
    {
        $request->validate([
            'technique' => ['required', 'string'],
        ]);

        $technique = strtolower($request->technique);

        $tips = $this->getCookingTips($technique);

        return response()->json([
            'technique' => $technique,
            'tips' => $tips,
        ]);
    }

    /**
     * Get nutritional information suggestions
     */
    public function getNutritionInfo(Request $request)
    {
        $request->validate([
            'ingredients' => ['required', 'array'],
            'servings' => ['required', 'integer', 'min:1'],
        ]);

        $ingredients = $request->ingredients;
        $servings = $request->servings;

        // Basic nutrition database (can be expanded)
        $nutritionDb = [
            'chicken' => ['calories' => 165, 'protein' => 31, 'carbs' => 0, 'fat' => 3.6],
            'rice' => ['calories' => 130, 'protein' => 2.7, 'carbs' => 28, 'fat' => 0.3],
            'olive oil' => ['calories' => 119, 'protein' => 0, 'carbs' => 0, 'fat' => 13.5],
            'tomato' => ['calories' => 18, 'protein' => 0.9, 'carbs' => 3.9, 'fat' => 0.2],
            'garlic' => ['calories' => 149, 'protein' => 6.4, 'carbs' => 33, 'fat' => 0.5],
            'onion' => ['calories' => 40, 'protein' => 1.1, 'carbs' => 9, 'fat' => 0.1],
            'potato' => ['calories' => 77, 'protein' => 2.1, 'carbs' => 17, 'fat' => 0.1],
            'broccoli' => ['calories' => 34, 'protein' => 2.8, 'carbs' => 7, 'fat' => 0.4],
            'carrot' => ['calories' => 41, 'protein' => 0.9, 'carbs' => 10, 'fat' => 0.2],
            'pasta' => ['calories' => 131, 'protein' => 5, 'carbs' => 25, 'fat' => 1.1],
        ];

        $totalNutrition = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];

        foreach ($ingredients as $ingredient) {
            $ingredientLower = strtolower($ingredient['name'] ?? '');
            
            foreach ($nutritionDb as $key => $nutrition) {
                if (strpos($ingredientLower, $key) !== false) {
                    $quantity = $ingredient['quantity'] ?? 1;
                    $totalNutrition['calories'] += $nutrition['calories'] * $quantity;
                    $totalNutrition['protein'] += $nutrition['protein'] * $quantity;
                    $totalNutrition['carbs'] += $nutrition['carbs'] * $quantity;
                    $totalNutrition['fat'] += $nutrition['fat'] * $quantity;
                    break;
                }
            }
        }

        // Per serving
        $perServing = array_map(fn($val) => round($val / $servings, 1), $totalNutrition);

        return response()->json([
            'per_serving' => $perServing,
            'per_recipe' => array_map(fn($val) => round($val, 1), $totalNutrition),
        ]);
    }

    /**
     * Generate AI response based on message
     */
    private function generateAIResponse(string $message): string
    {
        $message = strtolower(trim($message));

        // Simple keyword-based responses (can be replaced with real AI API)
        $responses = [
            // Substitutions
            ['keywords' => ['substitute', 'replacement', 'instead of'], 'response' => 'What ingredient would you like to substitute? I can help you find alternatives!'],
            
            // Cooking techniques
            ['keywords' => ['how to', 'cooking', 'technique', 'method'], 'response' => 'I\'d be happy to help with cooking techniques! What would you like to learn about?'],
            
            // Recipe suggestions
            ['keywords' => ['recipe', 'suggest', 'what can', 'make'], 'response' => 'What ingredients do you have available? I can suggest recipes based on what you have!'],
            
            // Pantry management
            ['keywords' => ['pantry', 'inventory', 'ingredients'], 'response' => 'You can manage your pantry to get personalized recipe suggestions. Would you like to add items to your pantry?'],
            
            // Nutritional info
            ['keywords' => ['nutrition', 'calories', 'healthy', 'diet'], 'response' => 'I can help you with nutritional information! Share the ingredients and I\'ll calculate the nutrition facts.'],
            
            // Cooking tips
            ['keywords' => ['tip', 'help', 'problem', 'issue', 'mistake'], 'response' => 'I\'m here to help! Tell me what you\'re having trouble with and I\'ll provide cooking tips.'],
        ];

        foreach ($responses as $item) {
            foreach ($item['keywords'] as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $item['response'];
                }
            }
        }

        // Default response
        return 'I\'m your AI Cooking Assistant! I can help you with: ingredient substitutions, cooking techniques, recipe suggestions, nutritional information, and cooking tips. What would you like to know?';
    }

    /**
     * Get cooking tips for a technique
     */
    private function getCookingTips(string $technique): array
    {
        $tips = [
            'boiling' => [
                'Salt your water generously, it should taste like the sea',
                'Bring water to a rolling boil before adding food',
                'Don\'t cover the pot if cooking pasta to prevent boiling over',
                'Add pasta to boiling water, not cold water',
                'Stir occasionally to prevent sticking',
            ],
            'frying' => [
                'Use oil with high smoke point (vegetable, canola, or peanut oil)',
                'Heat the oil to the right temperature before adding food',
                'Don\'t overcrowd the pan, it lowers temperature and causes steaming',
                'Pat dry food before frying to avoid splashing',
                'Use a splatter guard to prevent burns',
            ],
            'baking' => [
                'Preheat your oven for even cooking',
                'Use room temperature ingredients unless recipe specifies otherwise',
                'Measure ingredients accurately, especially flour and sugar',
                'Don\'t open the oven door frequently, it causes temperature drops',
                'Let baked goods cool in the pan before removing',
            ],
            'grilling' => [
                'Oil your grill grates to prevent sticking',
                'Let meat rest at room temperature before grilling',
                'Don\'t flip too often, let it develop a crust',
                'Use high heat for vegetables, medium for meat',
                'Let meat rest after cooking for juicier results',
            ],
            'steaming' => [
                'Use a steamer basket or bamboo steamer',
                'Don\'t let water touch the food',
                'Maintain consistent steam throughout cooking',
                'Don\'t overcrowd, food needs steam to circulate',
                'Perfect for preserving nutrients',
            ],
            'roasting' => [
                'Cut vegetables to similar sizes for even cooking',
                'Toss with oil and season well',
                'Use a hot oven (400-450°F) for best results',
                'Arrange in a single layer',
                'Stir halfway through for even browning',
            ],
        ];

        return $tips[$technique] ?? [
            'For this technique, here are general tips:',
            'Prepare all ingredients before starting (mise en place)',
            'Follow the recipe carefully, especially timing',
            'Don\'t be afraid to taste and adjust seasonings',
            'Keep your workspace clean and organized',
        ];
    }
}
