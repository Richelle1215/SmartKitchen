<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\MealPlanItem;
use App\Models\Recipe;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    /**
     * Display meal plans
     */
    public function index()
    {
        $user = auth()->user();
        $mealPlans = $user->mealPlans()
                         ->latest()
                         ->paginate(10);

        return view('meal-plans.index', compact('mealPlans'));
    }

    /**
     * Show create meal plan form
     */
    public function create()
    {
        $recipes = Recipe::where('is_published', true)
                        ->with('ingredients')
                        ->get();

        return view('meal-plans.create', compact('recipes'));
    }

    /**
     * Store a new meal plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'meal_type' => ['required', 'in:weekly,daily,custom'],
            'meals' => ['required', 'array'],
        ]);

        $validated['user_id'] = auth()->id();

        $mealPlan = MealPlan::create($validated);

        // Add meal items
        foreach ($request->meals as $mealData) {
            if (isset($mealData['recipe_id']) && isset($mealData['meal_date']) && isset($mealData['meal_type'])) {
                MealPlanItem::create([
                    'meal_plan_id' => $mealPlan->id,
                    'recipe_id' => $mealData['recipe_id'],
                    'meal_date' => $mealData['meal_date'],
                    'meal_type' => $mealData['meal_type'],
                    'servings' => $mealData['servings'] ?? 1,
                ]);
            }
        }

        return redirect()->route('meal-plans.show', $mealPlan)
                       ->with('success', 'Meal plan created successfully!');
    }

    /**
     * Show meal plan details
     */
    public function show(MealPlan $mealPlan)
    {
        $this->authorize('view', $mealPlan);

        $mealPlan->load(['items' => fn($q) => $q->orderBy('meal_date')]);

        // Group items by date
        $itemsByDate = $mealPlan->items->groupBy('meal_date');

        // Calculate nutrition and cost
        $totalNutrition = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];
        $totalCost = 0;

        foreach ($mealPlan->items as $item) {
            // Simplified nutrition calculation (expand as needed)
            $totalNutrition['calories'] += 500 * $item->servings; // Placeholder
            $totalCost += 5 * $item->servings; // Placeholder
        }

        return view('meal-plans.show', compact('mealPlan', 'itemsByDate', 'totalNutrition', 'totalCost'));
    }

    /**
     * Show edit form
     */
    public function edit(MealPlan $mealPlan)
    {
        $this->authorize('update', $mealPlan);

        $mealPlan->load('items');
        $recipes = Recipe::where('is_published', true)->get();

        return view('meal-plans.edit', compact('mealPlan', 'recipes'));
    }

    /**
     * Update meal plan
     */
    public function update(Request $request, MealPlan $mealPlan)
    {
        $this->authorize('update', $mealPlan);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'meal_type' => ['required', 'in:weekly,daily,custom'],
        ]);

        $mealPlan->update($validated);

        return redirect()->route('meal-plans.show', $mealPlan)
                       ->with('success', 'Meal plan updated!');
    }

    /**
     * Delete meal plan
     */
    public function destroy(MealPlan $mealPlan)
    {
        $this->authorize('delete', $mealPlan);

        $mealPlan->delete();

        return redirect()->route('meal-plans.index')
                       ->with('success', 'Meal plan deleted!');
    }

    /**
     * Add item to meal plan
     */
    public function addItem(Request $request, MealPlan $mealPlan)
    {
        $this->authorize('update', $mealPlan);

        $validated = $request->validate([
            'recipe_id' => ['required', 'exists:recipes,id'],
            'meal_date' => ['required', 'date'],
            'meal_type' => ['required', 'in:breakfast,lunch,dinner,snack'],
            'servings' => ['required', 'integer', 'min:1'],
        ]);

        $validated['meal_plan_id'] = $mealPlan->id;

        MealPlanItem::create($validated);

        return back()->with('success', 'Item added to meal plan!');
    }

    /**
     * Remove item from meal plan
     */
    public function removeItem(MealPlanItem $item)
    {
        $this->authorize('update', $item->mealPlan);

        $mealPlan = $item->mealPlan;
        $item->delete();

        return back()->with('success', 'Item removed!');
    }

    /**
     * Generate weekly meal plan suggestion
     */
    public function generateWeekly()
    {
        $recipes = Recipe::where('is_published', true)
                        ->orderByDesc('average_rating')
                        ->take(14) // 14 recipes for 2 meals per day for a week
                        ->get()
                        ->shuffle();

        $weeklyPlan = [];
        $startDate = now()->startOfWeek();

        foreach (range(0, 6) as $day) {
            $date = $startDate->copy()->addDays($day);
            $weeklyPlan[$date->format('Y-m-d')] = [
                'breakfast' => $recipes[$day * 2] ?? null,
                'lunch' => $recipes[$day * 2 + 1] ?? null,
                'dinner' => $recipes[(6 - $day) * 2] ?? null,
            ];
        }

        return view('meal-plans.weekly-suggestion', compact('weeklyPlan', 'startDate'));
    }

    /**
     * Get shopping list for meal plan
     */
    public function shoppingList(MealPlan $mealPlan)
    {
        $this->authorize('view', $mealPlan);

        $items = $mealPlan->items()->with('recipe.ingredients')->get();

        $shoppingList = [];

        foreach ($items as $item) {
            foreach ($item->recipe->ingredients as $ingredient) {
                $key = strtolower($ingredient->ingredient_name);
                
                if (!isset($shoppingList[$key])) {
                    $shoppingList[$key] = [
                        'name' => $ingredient->ingredient_name,
                        'total_quantity' => 0,
                        'unit' => $ingredient->unit,
                    ];
                }

                $shoppingList[$key]['total_quantity'] += $ingredient->quantity * $item->servings;
            }
        }

        ksort($shoppingList);

        return view('meal-plans.shopping-list', compact('mealPlan', 'shoppingList'));
    }
}
