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
        
        // Create categories
        $dinnerCategory = \App\Models\RecipeCategory::create([
            'name' => 'Dinner',
            'slug' => 'dinner',
            'description' => 'Dinner recipes',
        ]);
        $breakfastCategory = \App\Models\RecipeCategory::create([
            'name' => 'Breakfast',
            'slug' => 'breakfast',
            'description' => 'Breakfast recipes',
        ]);

        \App\Models\Recipe::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Vegetable Stir Fry',
            'category_id' => $dinnerCategory->id,
            'description' => 'Fresh and quick dinner.',
            'prep_time' => 20,
            'servings' => 2,
            'is_published' => true,
        ]);

        \App\Models\Recipe::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Berry Pancakes',
            'category_id' => $breakfastCategory->id,
            'description' => 'Sweet breakfast stack.',
            'prep_time' => 15,
            'servings' => 2,
            'is_published' => true,
        ]);

        $response = $this->get('/recipes?search=vegetable&category_id=' . $dinnerCategory->id);

        $response->assertOk();
        $response->assertSee('Vegetable Stir Fry');
    }
}

