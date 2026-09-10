<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $categories;
    protected $recipes = [];

    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'registered']);
        
        // Create categories directly
        $this->createCategories();
        
        // Create test recipes
        $this->setupTestRecipes();
    }

    protected function createCategories()
    {
        $categoryNames = [
            'Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snacks',
        ];

        foreach ($categoryNames as $name) {
            RecipeCategory::create([
                'name' => $name,
                'slug' => strtolower(str_replace(' ', '-', $name)),
                'description' => "{$name} recipes",
            ]);
        }

        $this->categories = RecipeCategory::all();
    }

    protected function setupTestRecipes()
    {
        // Recipe 1: Chicken Pasta (15 min prep, Dinner)
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $recipe1 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $dinnerCategory->id,
            'title' => 'Chicken Pasta',
            'description' => 'Delicious pasta with grilled chicken',
            'prep_time' => 15,
            'cook_time' => 20,
            'servings' => 4,
            'is_published' => true,
        ]);
        RecipeIngredient::create([
            'recipe_id' => $recipe1->id,
            'ingredient_name' => 'Chicken breast',
            'quantity' => 500,
            'unit' => 'grams',
            'order' => 1,
        ]);
        $this->recipes['chicken_pasta'] = $recipe1;

        // Recipe 2: Chocolate Cake (10 min prep, Dessert)
        $dessertCategory = $this->categories->where('name', 'Dessert')->first();
        $recipe2 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $dessertCategory->id,
            'title' => 'Chocolate Cake',
            'description' => 'Rich and moist chocolate cake',
            'prep_time' => 10,
            'cook_time' => 30,
            'servings' => 8,
            'is_published' => true,
        ]);
        RecipeIngredient::create([
            'recipe_id' => $recipe2->id,
            'ingredient_name' => 'Chocolate',
            'quantity' => 200,
            'unit' => 'grams',
            'order' => 1,
        ]);
        $this->recipes['chocolate_cake'] = $recipe2;

        // Recipe 3: Caesar Salad (5 min prep, Lunch)
        $lunchCategory = $this->categories->where('name', 'Lunch')->first();
        $recipe3 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $lunchCategory->id,
            'title' => 'Caesar Salad',
            'description' => 'Fresh and crisp caesar salad',
            'prep_time' => 5,
            'cook_time' => 0,
            'servings' => 2,
            'is_published' => true,
        ]);
        RecipeIngredient::create([
            'recipe_id' => $recipe3->id,
            'ingredient_name' => 'Lettuce',
            'quantity' => 500,
            'unit' => 'grams',
            'order' => 1,
        ]);
        $this->recipes['caesar_salad'] = $recipe3;

        // Recipe 4: Pancakes (15 min prep, Breakfast)
        $breakfastCategory = $this->categories->where('name', 'Breakfast')->first();
        $recipe4 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $breakfastCategory->id,
            'title' => 'Pancakes',
            'description' => 'Fluffy breakfast pancakes',
            'prep_time' => 15,
            'cook_time' => 15,
            'servings' => 4,
            'is_published' => true,
        ]);
        RecipeIngredient::create([
            'recipe_id' => $recipe4->id,
            'ingredient_name' => 'Flour',
            'quantity' => 200,
            'unit' => 'grams',
            'order' => 1,
        ]);
        $this->recipes['pancakes'] = $recipe4;

        // Unpublished recipe (should not appear)
        $unpublished = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $dinnerCategory->id,
            'title' => 'Secret Recipe',
            'description' => 'Unpublished recipe',
            'prep_time' => 20,
            'cook_time' => 30,
            'servings' => 4,
            'is_published' => false,
        ]);
        $this->recipes['unpublished'] = $unpublished;
    }

    // ===== SEARCH BY TITLE/DESCRIPTION =====

    public function test_search_by_recipe_title()
    {
        $response = $this->get(route('recipes.index', ['search' => 'Chicken']));
        $response->assertOk();
        $response->assertSee('Chicken Pasta');
    }

    public function test_search_by_description()
    {
        $response = $this->get(route('recipes.index', ['search' => 'moist']));
        $response->assertOk();
        $response->assertSee('Chocolate Cake');
    }

    // ===== SEARCH BY INGREDIENTS =====

    public function test_search_by_ingredient_name_chocolate()
    {
        $response = $this->get(route('recipes.index', ['search' => 'Chocolate']));
        $response->assertSee('Chocolate Cake');
    }

    public function test_search_by_ingredient_name_lettuce()
    {
        $response = $this->get(route('recipes.index', ['search' => 'Lettuce']));
        $response->assertSee('Caesar Salad');
    }

    public function test_search_case_insensitive()
    {
        $response = $this->get(route('recipes.index', ['search' => 'chicken']));
        $response->assertSee('Chicken Pasta');
    }

    // ===== CATEGORY FILTER TESTS =====

    public function test_filter_by_category_dessert()
    {
        $dessertCategory = $this->categories->where('name', 'Dessert')->first();
        $response = $this->get(route('recipes.index', ['category_id' => $dessertCategory->id]));
        $response->assertSee('Chocolate Cake');
    }

    public function test_filter_by_category_lunch()
    {
        $lunchCategory = $this->categories->where('name', 'Lunch')->first();
        $response = $this->get(route('recipes.index', ['category_id' => $lunchCategory->id]));
        $response->assertSee('Caesar Salad');
    }

    public function test_filter_by_category_dinner()
    {
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $response = $this->get(route('recipes.index', ['category_id' => $dinnerCategory->id]));
        $response->assertSee('Chicken Pasta');
    }

    public function test_category_filter_excludes_unpublished()
    {
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $response = $this->get(route('recipes.index', ['category_id' => $dinnerCategory->id]));
        // Should not show unpublished Secret Recipe in Dinner category
        $response->assertDontSee('Secret Recipe');
    }

    // ===== PREP TIME FILTER TESTS =====

    public function test_filter_by_max_prep_time_5_minutes()
    {
        $response = $this->get(route('recipes.index', ['max_prep_time' => 5]));
        $response->assertSee('Caesar Salad');
    }

    public function test_filter_by_max_prep_time_10_minutes()
    {
        $response = $this->get(route('recipes.index', ['max_prep_time' => 10]));
        $response->assertSee('Caesar Salad');
        $response->assertSee('Chocolate Cake');
    }

    public function test_filter_by_max_prep_time_15_minutes()
    {
        $response = $this->get(route('recipes.index', ['max_prep_time' => 15]));
        $response->assertSee('Caesar Salad');
        $response->assertSee('Chocolate Cake');
        $response->assertSee('Chicken Pasta');
        $response->assertSee('Pancakes');
    }

    // ===== SORT TESTS =====

    public function test_sort_by_recent_default()
    {
        $response = $this->get(route('recipes.index', ['sort' => 'recent']));
        $response->assertOk();
        $response->assertSee('Chicken Pasta');
    }

    public function test_sort_by_popular()
    {
        $this->recipes['chicken_pasta']->increment('view_count', 100);
        $response = $this->get(route('recipes.index', ['sort' => 'popular']));
        $response->assertOk();
    }

    // ===== COMBINED FILTERS =====

    public function test_search_and_category_filter_combined()
    {
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $response = $this->get(route('recipes.index', [
            'search' => 'Chicken',
            'category_id' => $dinnerCategory->id
        ]));
        $response->assertSee('Chicken Pasta');
    }

    public function test_search_and_prep_time_combined()
    {
        $response = $this->get(route('recipes.index', [
            'search' => 'Pasta',
            'max_prep_time' => 20
        ]));
        $response->assertSee('Chicken Pasta');
    }

    public function test_category_and_prep_time_combined()
    {
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $response = $this->get(route('recipes.index', [
            'category_id' => $dinnerCategory->id,
            'max_prep_time' => 20
        ]));
        $response->assertSee('Chicken Pasta');
    }

    public function test_all_filters_combined()
    {
        $dinnerCategory = $this->categories->where('name', 'Dinner')->first();
        $response = $this->get(route('recipes.index', [
            'search' => 'Chicken',
            'category_id' => $dinnerCategory->id,
            'max_prep_time' => 20,
            'sort' => 'recent'
        ]));
        $response->assertSee('Chicken Pasta');
    }

    // ===== EMPTY RESULTS =====

    public function test_empty_results_on_nonexistent_search()
    {
        $response = $this->get(route('recipes.index', ['search' => 'xyz123nonexistent']));
        $response->assertOk();
        $response->assertSee('No recipes found matching your criteria');
    }

    public function test_empty_results_with_empty_category()
    {
        $snacksCategory = $this->categories->where('name', 'Snacks')->first();
        $response = $this->get(route('recipes.index', ['category_id' => $snacksCategory->id]));
        $response->assertSee('No recipes found matching your criteria');
    }

    public function test_empty_results_with_impossible_prep_time()
    {
        $response = $this->get(route('recipes.index', ['max_prep_time' => 1]));
        $response->assertSee('No recipes found matching your criteria');
    }

    // ===== CLEAR FILTERS =====

    public function test_clear_filters_returns_all_recipes()
    {
        $response = $this->get(route('recipes.index'));
        $response->assertSee('Chicken Pasta');
        $response->assertSee('Chocolate Cake');
        $response->assertSee('Caesar Salad');
        $response->assertSee('Pancakes');
    }

    public function test_unpublished_not_shown_by_default()
    {
        $response = $this->get(route('recipes.index'));
        $response->assertDontSee('Secret Recipe');
    }

    // ===== PAGINATION =====

    public function test_pagination_works()
    {
        $response = $this->get(route('recipes.index'));
        $response->assertOk();
        $this->assertIsObject($response->viewData('recipes'));
    }
}
