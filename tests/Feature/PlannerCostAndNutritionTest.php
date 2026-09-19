<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PlannerCostAndNutritionTest extends TestCase
{
    use RefreshDatabase;

    public function test_meal_planner_page_displays_weekdays_and_meal_slots(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('meal-planner'))
            ->assertOk()
            ->assertSee('Monday')
            ->assertSee('Tuesday')
            ->assertSee('Wednesday')
            ->assertSee('Thursday')
            ->assertSee('Friday')
            ->assertSee('Saturday')
            ->assertSee('Sunday')
            ->assertSee('Breakfast')
            ->assertSee('Lunch')
            ->assertSee('Dinner');
    }

    public function test_cost_calculator_returns_philippine_peso_total_and_per_serving(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('cost-calculator.calculate'), [
                'ingredients' => [
                    ['name' => 'Chicken', 'quantity' => 1, 'price' => 150],
                    ['name' => 'Rice', 'quantity' => 1, 'price' => 50],
                    ['name' => 'Vegetables', 'quantity' => 1, 'price' => 80],
                    ['name' => 'Sauce', 'quantity' => 1, 'price' => 30],
                ],
                'servings' => 5,
            ]);

        $response->assertOk()
            ->assertJsonPath('currency', '₱')
            ->assertJsonPath('total_cost', 310)
            ->assertJsonPath('cost_per_serving', 62);
    }

    public function test_nutrition_service_returns_expected_macros_for_recipe(): void
    {
        $recipe = Recipe::factory()->create(['title' => 'Chicken Bowl']);
        $recipe->ingredients()->createMany([
            ['ingredient_name' => 'Chicken', 'quantity' => 200, 'unit' => 'g'],
            ['ingredient_name' => 'Rice', 'quantity' => 150, 'unit' => 'g'],
            ['ingredient_name' => 'Vegetables', 'quantity' => 100, 'unit' => 'g'],
        ]);

        $nutrition = app(\App\Services\NutritionService::class)->analyzeRecipe($recipe);

        $this->assertArrayHasKey('calories', $nutrition);
        $this->assertArrayHasKey('protein', $nutrition);
        $this->assertArrayHasKey('carbohydrates', $nutrition);
        $this->assertArrayHasKey('fat', $nutrition);
        $this->assertArrayHasKey('sugar', $nutrition);
        $this->assertGreaterThan(0, $nutrition['calories']);
    }
}
