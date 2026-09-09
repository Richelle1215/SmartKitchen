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
}
