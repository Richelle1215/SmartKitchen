<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'registered']);
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $this->category = RecipeCategory::first();
    }

    /**
     * Test complete recipe creation flow: list → details → edit → delete
     */
    public function test_complete_recipe_workflow()
    {
        Storage::fake('public');

        // Step 1: User views recipe list
        $response = $this->get(route('recipes.index'));
        $response->assertOk();
        $response->assertViewIs('recipes.index');

        // Step 2: Authenticated user creates a recipe
        $recipeData = [
            'title' => 'Spaghetti Carbonara',
            'description' => 'A classic Italian pasta dish with creamy sauce',
            'category_id' => $this->category->id,
            'prep_time' => 15,
            'cook_time' => 20,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'Spaghetti', 'quantity' => 400, 'unit' => 'grams'],
                ['name' => 'Bacon', 'quantity' => 200, 'unit' => 'grams'],
                ['name' => 'Eggs', 'quantity' => 3, 'unit' => 'piece'],
            ],
            'instructions' => [
                ['text' => 'Boil spaghetti in salted water until al dente'],
                ['text' => 'Fry bacon until crispy and chop into pieces'],
                ['text' => 'Mix eggs with cheese and cream'],
                ['text' => 'Combine hot pasta with bacon and egg mixture'],
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('recipes.store'), $recipeData);
        $response->assertRedirect();
        
        $recipe = Recipe::where('title', 'Spaghetti Carbonara')->first();
        $this->assertNotNull($recipe);
        $this->assertEquals($this->user->id, $recipe->user_id);
        $this->assertTrue($recipe->is_published);

        // Step 3: Anyone can view the published recipe
        $response = $this->get(route('recipes.show', $recipe));
        $response->assertOk();
        $response->assertSee('Spaghetti Carbonara');
        $response->assertSee('Boil spaghetti in salted water until al dente');

        // Step 4: Recipe appears in recipe list
        $response = $this->get(route('recipes.index'));
        $response->assertSee('Spaghetti Carbonara');

        // Step 5: Recipe appears in user's recipes
        $response = $this->get(route('recipes.user-recipes', $this->user->id));
        $response->assertOk();
        $response->assertSee('Spaghetti Carbonara');

        // Step 6: Owner can access edit page
        $response = $this->actingAs($this->user)->get(route('recipes.edit', $recipe));
        $response->assertOk();
        $response->assertViewIs('recipes.edit');
        $response->assertSee('Spaghetti Carbonara');

        // Step 7: Owner can update the recipe
        $updateData = [
            'title' => 'Updated Spaghetti Carbonara',
            'description' => 'An improved version of the classic',
            'category_id' => $this->category->id,
            'prep_time' => 20,
            'cook_time' => 25,
            'servings' => 6,
            'ingredients' => [
                ['name' => 'Spaghetti', 'quantity' => 500, 'unit' => 'grams'],
                ['name' => 'Bacon', 'quantity' => 250, 'unit' => 'grams'],
                ['name' => 'Eggs', 'quantity' => 4, 'unit' => 'piece'],
            ],
            'instructions' => [
                ['text' => 'Boil spaghetti in salted water until al dente'],
                ['text' => 'Fry bacon until crispy and chop into pieces'],
                ['text' => 'Mix eggs with cheese and cream'],
                ['text' => 'Combine hot pasta with bacon and egg mixture'],
                ['text' => 'Season with black pepper and serve'],
            ],
        ];

        $response = $this->actingAs($this->user)->patch(route('recipes.update', $recipe), $updateData);
        $response->assertRedirect(route('recipes.show', $recipe));

        // Verify update
        $recipe->refresh();
        $this->assertEquals('Updated Spaghetti Carbonara', $recipe->title);
        $this->assertEquals(20, $recipe->prep_time);
        $this->assertEquals(6, $recipe->servings);

        // Step 8: Updated recipe shows in list
        $response = $this->get(route('recipes.index'));
        $response->assertSee('Updated Spaghetti Carbonara');

        // Step 9: Owner can delete the recipe
        $recipeId = $recipe->id;
        $response = $this->actingAs($this->user)->delete(route('recipes.destroy', $recipe));
        $response->assertRedirect(route('recipes.index'));

        // Verify soft delete
        $this->assertSoftDeleted('recipes', ['id' => $recipeId]);
    }

    /**
     * Test that owner sees edit/delete buttons on own recipe
     */
    public function test_owner_sees_edit_delete_buttons_on_own_recipe()
    {
        Storage::fake('public');

        // Create recipe
        $recipe = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'My Recipe',
            'description' => 'Test recipe',
            'servings' => 4,
            'is_published' => true,
        ]);

        // Owner sees edit/delete buttons
        $response = $this->actingAs($this->user)->get(route('recipes.show', $recipe));
        $content = $response->getContent();
        // Look for the action buttons in the header area (not in nav)
        $this->assertStringContainsString('class="inline-flex items-center px-4 py-2 bg-blue-500', $content);
        $this->assertStringContainsString('class="inline-flex items-center px-4 py-2 bg-red-500', $content);
    }

    /**
     * Test that other users don't see edit/delete buttons
     */
    public function test_other_users_dont_see_edit_delete_buttons()
    {
        Storage::fake('public');

        $otherUser = User::factory()->create(['role' => 'registered']);

        // Create recipe
        $recipe = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Someone Elses Recipe',
            'description' => 'Test recipe',
            'servings' => 4,
            'is_published' => true,
        ]);

        // Other user doesn't see edit/delete buttons
        $response = $this->actingAs($otherUser)->get(route('recipes.show', $recipe));
        $content = $response->getContent();
        // Verify buttons are not in the header (the specific button container)
        $this->assertStringNotContainsString('<a href="' . route('recipes.edit', $recipe) . '"', $content);
        $this->assertStringNotContainsString('form method="POST" action="' . route('recipes.destroy', $recipe) . '"', $content);
    }

    /**
     * Test that guest users don't see edit/delete buttons
     */
    public function test_guests_dont_see_edit_delete_buttons()
    {
        Storage::fake('public');

        // Create recipe
        $recipe = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Public Recipe',
            'description' => 'Test recipe',
            'servings' => 4,
            'is_published' => true,
        ]);

        // Guest doesn't see edit/delete buttons
        $response = $this->get(route('recipes.show', $recipe));
        $content = $response->getContent();
        $this->assertStringNotContainsString('<a href="' . route('recipes.edit', $recipe) . '"', $content);
        $this->assertStringNotContainsString('form method="POST" action="' . route('recipes.destroy', $recipe) . '"', $content);
    }

    /**
     * Test search and filtering on recipe list
     */
    public function test_recipe_list_search_and_filter()
    {
        Storage::fake('public');

        // Create multiple recipes in different categories
        $breakfast = RecipeCategory::where('name', 'Breakfast')->first();
        $lunch = RecipeCategory::where('name', 'Lunch')->first();

        Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $breakfast->id,
            'title' => 'Pancakes',
            'description' => 'Fluffy pancakes',
            'servings' => 2,
            'is_published' => true,
        ]);

        Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $lunch->id,
            'title' => 'Sandwich',
            'description' => 'Delicious sandwich',
            'servings' => 1,
            'is_published' => true,
        ]);

        // Search for "Pancakes"
        $response = $this->get(route('recipes.index', ['search' => 'Pancakes']));
        $response->assertSee('Pancakes');
        $response->assertDontSee('Sandwich');

        // Filter by breakfast category
        $response = $this->get(route('recipes.index', ['category_id' => $breakfast->id]));
        $response->assertSee('Pancakes');
        $response->assertDontSee('Sandwich');

        // Filter by lunch category
        $response = $this->get(route('recipes.index', ['category_id' => $lunch->id]));
        $response->assertDontSee('Pancakes');
        $response->assertSee('Sandwich');
    }

    /**
     * Test sorting on recipe list
     */
    public function test_recipe_list_sorting()
    {
        Storage::fake('public');

        // Create two recipes
        $recipe1 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'First Recipe',
            'description' => 'First',
            'servings' => 2,
            'is_published' => true,
        ]);

        $recipe2 = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Second Recipe',
            'description' => 'Second',
            'servings' => 2,
            'is_published' => true,
        ]);

        // Both recipes should appear in list
        $response = $this->get(route('recipes.index'));
        $response->assertOk();
        $response->assertSee('First Recipe');
        $response->assertSee('Second Recipe');

        // Test sort by popular (update view counts)
        $recipe1->increment('view_count', 100);
        $response = $this->get(route('recipes.index', ['sort' => 'popular']));
        $response->assertOk();
        $response->assertSee('First Recipe');
    }

    /**
     * Test that user can view their own recipes
     */
    public function test_user_can_view_my_recipes()
    {
        Storage::fake('public');

        // Create recipes
        Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'My First Recipe',
            'description' => 'Test',
            'servings' => 2,
            'is_published' => true,
        ]);

        Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'My Second Recipe',
            'description' => 'Test',
            'servings' => 2,
            'is_published' => true,
        ]);

        // View my recipes
        $response = $this->actingAs($this->user)->get(route('recipes.my-recipes'));
        $response->assertOk();
        $response->assertSee('My First Recipe');
        $response->assertSee('My Second Recipe');
    }

    /**
     * Test that unauthenticated users are redirected from /my-recipes
     */
    public function test_unauthenticated_redirected_from_my_recipes()
    {
        $response = $this->get(route('recipes.my-recipes'));
        $response->assertRedirect('/login');
    }
}
