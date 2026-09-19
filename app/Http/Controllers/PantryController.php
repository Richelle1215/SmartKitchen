<?php

namespace App\Http\Controllers;

use App\Models\PantryItem;
use Illuminate\Http\Request;

class PantryController extends Controller
{
    /**
     * Display user's pantry
     */
    public function index()
    {
        $user = auth()->user();

        if (! $user) {
            return view('features.pantry');
        }

        $items = $user->pantryItems()
                      ->orderBy('category')
                      ->paginate(12);

        $categories = $user->pantryItems()
                          ->select('category')
                          ->distinct()
                          ->pluck('category')
                          ->filter();

        // Get low stock items
        $lowStockItems = $user->pantryItems()
                             ->where('quantity', '>', 0)
                             ->get()
                             ->filter(fn($item) => $item->isLowOnStock());

        return view('pantry.index', compact('items', 'categories', 'lowStockItems'));
    }

    /**
     * Store a new pantry item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['user_id'] = auth()->id();

        PantryItem::create($validated);

        return back()->with('success', 'Item added to pantry!');
    }

    /**
     * Update pantry item
     */
    public function update(Request $request, PantryItem $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'ingredient_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
        ]);

        $item->update($validated);

        return back()->with('success', 'Item updated!');
    }

    /**
     * Delete pantry item
     */
    public function destroy(PantryItem $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return back()->with('success', 'Item removed from pantry!');
    }

    /**
     * Update item quantity (quick update)
     */
    public function updateQuantity(Request $request, PantryItem $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'min:0'],
        ]);

        $item->update($validated);

        return response()->json(['success' => true, 'item' => $item]);
    }

    /**
     * Get low stock items
     */
    public function lowStock()
    {
        $items = auth()->user()->pantryItems()
                              ->where('quantity', '>', 0)
                              ->get()
                              ->filter(fn($item) => $item->isLowOnStock());

        return view('pantry.low-stock', compact('items'));
    }

    /**
     * Get expired items
     */
    public function expired()
    {
        $items = auth()->user()->pantryItems()
                              ->where('quantity', '>', 0)
                              ->get()
                              ->filter(fn($item) => $item->isExpired());

        return view('pantry.expired', compact('items'));
    }

    /**
     * Suggest recipes based on pantry items (API endpoint)
     */
    public function suggestRecipes()
    {
        $user = auth()->user();

        $pantryItems = $user->pantryItems()
            ->where('quantity', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtolower(trim($item->ingredient_name)) => $item];
            });

        if ($pantryItems->isEmpty()) {
            return response()->json([
                'message' => 'Add items to your pantry to get recipe suggestions',
                'can_make' => [],
                'almost_can_make' => [],
                'recipes' => [],
            ]);
        }

        $canMake = [];
        $almostCanMake = [];

        \App\Models\Recipe::where('is_published', true)
            ->with('ingredients')
            ->get()
            ->each(function ($recipe) use ($pantryItems, &$canMake, &$almostCanMake) {
                $recipeIngredients = $recipe->ingredients->all();

                if (empty($recipeIngredients)) {
                    return;
                }

                $missingIngredients = [];
                $matchCount = 0;

                foreach ($recipeIngredients as $ingredient) {
                    $name = trim((string) $ingredient->ingredient_name);
                    $key = strtolower($name);

                    if ($pantryItems->has($key)) {
                        $matchCount++;
                        continue;
                    }

                    $missingIngredients[] = $name;
                }

                $recipeData = [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'url' => route('recipes.show', $recipe),
                    'match_count' => $matchCount,
                    'total_ingredients' => count($recipeIngredients),
                    'match_percentage' => count($recipeIngredients) > 0
                        ? round(($matchCount / count($recipeIngredients)) * 100)
                        : 0,
                    'missing_ingredients' => $missingIngredients,
                ];

                if (count($missingIngredients) <= 1) {
                    $canMake[] = $recipeData;
                    return;
                }

                $almostCanMake[] = $recipeData;
            });

        $recipes = \App\Models\Recipe::where('is_published', true)
            ->with('ingredients')
            ->get();

        foreach ($recipes as $recipe) {
            $ingredients = $recipe->ingredients->all();

            if (empty($ingredients)) {
                continue;
            }

            $missingIngredients = [];
            foreach ($ingredients as $ingredient) {
                $key = strtolower(trim((string) $ingredient->ingredient_name));
                if (! $pantryItems->has($key)) {
                    $missingIngredients[] = trim((string) $ingredient->ingredient_name);
                }
            }

            if ($missingIngredients === []) {
                continue;
            }

            $alreadyInCanMake = collect($canMake)->contains(fn ($item) => (int) $item['id'] === (int) $recipe->id);
            $alreadyInAlmostMake = collect($almostCanMake)->contains(fn ($item) => (int) $item['id'] === (int) $recipe->id);

            if ($alreadyInCanMake || $alreadyInAlmostMake) {
                continue;
            }

            $almostCanMake[] = [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'url' => route('recipes.show', $recipe),
                'match_count' => count($ingredients) - count($missingIngredients),
                'total_ingredients' => count($ingredients),
                'match_percentage' => count($ingredients) > 0 ? round(((count($ingredients) - count($missingIngredients)) / count($ingredients)) * 100) : 0,
                'missing_ingredients' => $missingIngredients,
            ];
        }

        $canMake = collect($canMake)
            ->sortByDesc('match_count')
            ->values()
            ->all();

        $almostCanMake = collect($almostCanMake)
            ->sortByDesc('match_count')
            ->values()
            ->all();

        return response()->json([
            'message' => 'Based on your pantry, here are recipes you can make:',
            'can_make' => $canMake,
            'almost_can_make' => $almostCanMake,
            'recipes' => array_merge($canMake, $almostCanMake),
        ]);
    }
}
