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
                            ->pluck('ingredient_name')
                            ->toArray();

        if (empty($pantryItems)) {
            return response()->json([
                'message' => 'Add items to your pantry to get recipe suggestions',
                'recipes' => [],
            ]);
        }

        $recipes = \App\Models\Recipe::where('is_published', true)
                                     ->with('ingredients')
                                     ->get()
                                     ->map(function ($recipe) use ($pantryItems) {
                                         $recipeIngredients = $recipe->ingredients->pluck('ingredient_name')->toArray();
                                         $matches = array_intersect($pantryItems, $recipeIngredients);
                                         $matchPercentage = count($recipeIngredients) > 0 
                                             ? round((count($matches) / count($recipeIngredients)) * 100)
                                             : 0;

                                         return [
                                             'id' => $recipe->id,
                                             'title' => $recipe->title,
                                             'url' => route('recipes.show', $recipe),
                                             'match_percentage' => $matchPercentage,
                                             'match_count' => count($matches),
                                             'total_ingredients' => count($recipeIngredients),
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
}
