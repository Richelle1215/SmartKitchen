<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SmartKitchen');
    }

    public function test_recipe_index_can_filter_by_search_and_category(): void
    {
        $this->withoutExceptionHandling();

        \App\Models\Recipe::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Vegetable Stir Fry',
            'category' => 'Dinner',
            'description' => 'Fresh and quick dinner.',
            'ingredients' => 'Broccoli\nCarrots\nSoy sauce',
            'instructions' => 'Stir fry everything together.',
            'prep_time' => 20,
            'servings' => 2,
            'is_public' => true,
        ]);

        \App\Models\Recipe::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Berry Pancakes',
            'category' => 'Breakfast',
            'description' => 'Sweet breakfast stack.',
            'ingredients' => 'Flour\nMilk\nBerries',
            'instructions' => 'Cook the batter on a pan.',
            'prep_time' => 15,
            'servings' => 2,
            'is_public' => true,
        ]);

        $response = $this->get('/recipes?search=vegetable&category=Dinner');

        $response->assertOk();
        $response->assertSee('Vegetable Stir Fry');
        $response->assertDontSee('Berry Pancakes');
    }

    public function test_user_can_create_recipe(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/recipes', [
            'title' => 'Pasta Primavera',
            'category' => 'Dinner',
            'description' => 'A quick vegetarian dinner.',
            'ingredients' => "200g pasta\n2 zucchini\n1 tomato\n2 tbsp olive oil",
            'instructions' => "Boil the pasta.\nCook the vegetables.\nMix and serve.",
            'prep_time' => 25,
            'servings' => 2,
            'is_public' => true,
        ]);

        $response->assertRedirect('/recipes');
        $this->assertDatabaseHas('recipes', ['title' => 'Pasta Primavera', 'user_id' => $user->id]);
    }

    public function test_dashboard_shows_user_recipe_statistics(): void
    {
        $user = User::factory()->create();

        \App\Models\Recipe::create([
            'user_id' => $user->id,
            'title' => 'Lemon Chicken Bowl',
            'category' => 'Dinner',
            'description' => 'A bright meal prep bowl.',
            'ingredients' => 'Chicken\nLemon\nRice',
            'instructions' => 'Cook and assemble.',
            'prep_time' => 30,
            'servings' => 2,
            'is_public' => true,
        ]);

        \App\Models\Recipe::create([
            'user_id' => $user->id,
            'title' => 'Mango Smoothie',
            'category' => 'Beverages',
            'description' => 'Refreshing smoothie.',
            'ingredients' => 'Mango\nYogurt\nMilk',
            'instructions' => 'Blend until smooth.',
            'prep_time' => 10,
            'servings' => 1,
            'is_public' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('2');
        $response->assertSee('Lemon Chicken Bowl');
    }
}
