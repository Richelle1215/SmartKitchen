<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PantryMatchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_the_pantry_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('pantry.index'))
            ->assertOk();
    }

    public function test_pantry_can_identify_recipes_the_user_can_make_and_almost_can_make(): void
    {
        $user = User::factory()->create();

        $user->pantryItems()->createMany([
            ['ingredient_name' => 'Chicken', 'quantity' => 2, 'unit' => 'lb', 'low_stock_threshold' => 1],
            ['ingredient_name' => 'Rice', 'quantity' => 2, 'unit' => 'cups', 'low_stock_threshold' => 1],
            ['ingredient_name' => 'Garlic', 'quantity' => 1, 'unit' => 'head', 'low_stock_threshold' => 1],
            ['ingredient_name' => 'Onion', 'quantity' => 1, 'unit' => 'whole', 'low_stock_threshold' => 1],
        ]);

        $canMake = Recipe::factory()->create(['title' => 'Chicken Fried Rice', 'is_published' => true]);
        $canMake->ingredients()->createMany([
            ['ingredient_name' => 'Chicken', 'quantity' => 1, 'unit' => 'lb'],
            ['ingredient_name' => 'Rice', 'quantity' => 1, 'unit' => 'cup'],
            ['ingredient_name' => 'Garlic', 'quantity' => 1, 'unit' => 'clove'],
            ['ingredient_name' => 'Onion', 'quantity' => 1, 'unit' => 'whole'],
            ['ingredient_name' => 'Egg', 'quantity' => 1, 'unit' => 'egg'],
        ]);

        $almost = Recipe::factory()->create(['title' => 'Tomato Pasta', 'is_published' => true]);
        $almost->ingredients()->createMany([
            ['ingredient_name' => 'Tomato', 'quantity' => 2, 'unit' => 'whole'],
            ['ingredient_name' => 'Pasta', 'quantity' => 1, 'unit' => 'box'],
            ['ingredient_name' => 'Olive Oil', 'quantity' => 1, 'unit' => 'tbsp'],
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('pantry.suggest-recipes'));

        $response->assertOk();
        $this->assertNotNull($response->json('can_make'));
        $this->assertNotNull($response->json('almost_can_make'));

        $canMakeItems = collect($response->json('can_make'));
        $almostItems = collect($response->json('almost_can_make'));

        $this->assertTrue($canMakeItems->contains(fn ($recipe) => $recipe['title'] === 'Chicken Fried Rice'));
        $this->assertTrue($almostItems->contains(fn ($recipe) => $recipe['title'] === 'Tomato Pasta'));

        $chickenFriedRice = $canMakeItems->first(fn ($recipe) => $recipe['title'] === 'Chicken Fried Rice');
        $this->assertSame(['Egg'], $chickenFriedRice['missing_ingredients']);
    }
}
