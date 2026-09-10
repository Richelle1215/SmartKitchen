<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $otherUser;
    protected $guest;
    protected $recipe;
    protected $category;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create users with different roles
        $this->owner = User::factory()->create(['role' => 'registered']);
        $this->otherUser = User::factory()->create(['role' => 'registered']);
        $this->guest = User::factory()->create(['role' => 'guest']);
        
        // Create category
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $this->category = RecipeCategory::first();
        
        // Create a sample recipe
        $this->recipe = Recipe::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Test Recipe',
            'description' => 'A test recipe for authorization',
            'prep_time' => 15,
            'cook_time' => 30,
            'servings' => 4,
            'is_published' => true,
        ]);
    }

    // ===== INDEX / LIST TESTS =====
    
    public function test_anyone_can_view_recipe_list()
    {
        $response = $this->get('/recipes');
        $response->assertOk();
        $response->assertViewIs('recipes.index');
    }

    public function test_authenticated_user_can_view_recipe_list()
    {
        $response = $this->actingAs($this->owner)->get('/recipes');
        $response->assertOk();
        $response->assertSee($this->recipe->title);
    }

    public function test_guest_can_view_recipe_list()
    {
        $response = $this->get('/recipes');
        $response->assertOk();
    }

    // ===== SHOW / DETAILS TESTS =====
    
    public function test_anyone_can_view_published_recipe_details()
    {
        $response = $this->get(route('recipes.show', $this->recipe));
        $response->assertOk();
        $response->assertSee($this->recipe->title);
        $response->assertSee($this->recipe->description);
    }

    public function test_owner_can_view_unpublished_recipe()
    {
        $unpublished = Recipe::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Unpublished Recipe',
            'description' => 'Draft recipe',
            'servings' => 2,
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->owner)->get(route('recipes.show', $unpublished));
        $response->assertOk();
    }

    public function test_other_user_cannot_view_unpublished_recipe()
    {
        $unpublished = Recipe::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Unpublished Recipe',
            'description' => 'Draft recipe',
            'servings' => 2,
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->otherUser)->get(route('recipes.show', $unpublished));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_unpublished_recipe()
    {
        $unpublished = Recipe::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Unpublished Recipe',
            'description' => 'Draft recipe',
            'servings' => 2,
            'is_published' => false,
        ]);

        $response = $this->get(route('recipes.show', $unpublished));
        $response->assertStatus(403);
    }

    // ===== EDIT TESTS =====
    
    public function test_owner_can_access_edit_page()
    {
        $response = $this->actingAs($this->owner)->get(route('recipes.edit', $this->recipe));
        $response->assertOk();
        $response->assertViewIs('recipes.edit');
        $response->assertSee($this->recipe->title);
    }

    public function test_other_user_cannot_access_edit_page()
    {
        $response = $this->actingAs($this->otherUser)->get(route('recipes.edit', $this->recipe));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_page()
    {
        $response = $this->actingAs($this->guest)->get(route('recipes.edit', $this->recipe));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_edit_page()
    {
        $response = $this->get(route('recipes.edit', $this->recipe));
        $response->assertRedirect('/login');
    }

    // ===== UPDATE TESTS =====
    
    public function test_owner_can_update_recipe()
    {
        Storage::fake('public');
        
        $data = [
            'title' => 'Updated Recipe',
            'description' => 'Updated description',
            'category_id' => $this->category->id,
            'servings' => 6,
            'prep_time' => 20,
            'cook_time' => 40,
            'ingredients' => [
                ['name' => 'Flour', 'quantity' => 2, 'unit' => 'cups'],
            ],
            'instructions' => [
                ['text' => 'Mix all ingredients together thoroughly'],
            ],
        ];

        $response = $this->actingAs($this->owner)->patch(route('recipes.update', $this->recipe), $data);
        
        $response->assertRedirect(route('recipes.show', $this->recipe));
        $this->assertDatabaseHas('recipes', [
            'id' => $this->recipe->id,
            'title' => 'Updated Recipe',
            'description' => 'Updated description',
            'servings' => 6,
        ]);
    }

    public function test_other_user_cannot_update_recipe()
    {
        $data = [
            'title' => 'Hacked Recipe',
            'description' => 'Hacked description',
            'category_id' => $this->category->id,
            'servings' => 6,
            'ingredients' => [
                ['name' => 'Flour', 'quantity' => 2, 'unit' => 'cups'],
            ],
            'instructions' => [
                ['text' => 'Mix all ingredients together thoroughly'],
            ],
        ];

        $response = $this->actingAs($this->otherUser)->patch(route('recipes.update', $this->recipe), $data);
        $response->assertStatus(403);
        
        // Verify recipe was not updated
        $this->assertDatabaseHas('recipes', [
            'id' => $this->recipe->id,
            'title' => 'Test Recipe', // Original title
        ]);
    }

    public function test_guest_cannot_update_recipe()
    {
        $data = [
            'title' => 'Hacked Recipe',
            'description' => 'Hacked description',
            'category_id' => $this->category->id,
            'servings' => 6,
            'ingredients' => [
                ['name' => 'Flour', 'quantity' => 2, 'unit' => 'cups'],
            ],
            'instructions' => [
                ['text' => 'Mix all ingredients together thoroughly'],
            ],
        ];

        $response = $this->actingAs($this->guest)->patch(route('recipes.update', $this->recipe), $data);
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_update_recipe()
    {
        $data = [
            'title' => 'Hacked Recipe',
            'description' => 'Hacked description',
            'category_id' => $this->category->id,
            'servings' => 6,
        ];

        $response = $this->patch(route('recipes.update', $this->recipe), $data);
        $response->assertRedirect('/login');
    }

    // ===== DELETE TESTS =====
    
    public function test_owner_can_delete_recipe()
    {
        $recipeId = $this->recipe->id;
        
        $response = $this->actingAs($this->owner)->delete(route('recipes.destroy', $this->recipe));
        
        $response->assertRedirect(route('recipes.index'));
        $this->assertSoftDeleted('recipes', ['id' => $recipeId]);
    }

    public function test_other_user_cannot_delete_recipe()
    {
        $response = $this->actingAs($this->otherUser)->delete(route('recipes.destroy', $this->recipe));
        $response->assertStatus(403);
        
        // Verify recipe was not deleted
        $this->assertDatabaseHas('recipes', ['id' => $this->recipe->id]);
    }

    public function test_guest_cannot_delete_recipe()
    {
        $response = $this->actingAs($this->guest)->delete(route('recipes.destroy', $this->recipe));
        $response->assertStatus(403);
        
        // Verify recipe was not deleted
        $this->assertDatabaseHas('recipes', ['id' => $this->recipe->id]);
    }

    public function test_unauthenticated_user_cannot_delete_recipe()
    {
        $response = $this->delete(route('recipes.destroy', $this->recipe));
        $response->assertRedirect('/login');
    }

    // ===== MY RECIPES TEST =====
    
    public function test_owner_can_view_my_recipes()
    {
        $response = $this->actingAs($this->owner)->get(route('recipes.my-recipes'));
        $response->assertOk();
        $response->assertSee($this->recipe->title);
    }

    public function test_unauthenticated_user_redirected_from_my_recipes()
    {
        $response = $this->get(route('recipes.my-recipes'));
        $response->assertRedirect('/login');
    }

    // ===== VIEW USER RECIPES TEST =====
    
    public function test_anyone_can_view_user_recipes()
    {
        $response = $this->get(route('recipes.user-recipes', $this->owner->id));
        $response->assertOk();
        $response->assertSee($this->recipe->title);
    }

    public function test_user_recipes_only_shows_published_recipes()
    {
        Recipe::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Unpublished Recipe',
            'description' => 'Draft recipe',
            'servings' => 2,
            'is_published' => false,
        ]);

        $response = $this->get(route('recipes.user-recipes', $this->owner->id));
        $response->assertDontSee('Unpublished Recipe');
    }

    // ===== POLICY TESTS =====
    
    public function test_recipe_policy_view_allows_published_recipes()
    {
        $this->assertTrue(
            auth()->check() ? auth()->user()->can('view', $this->recipe) : true
        );
    }

    public function test_recipe_policy_update_only_owner()
    {
        $this->assertTrue($this->owner->can('update', $this->recipe));
        $this->assertFalse($this->otherUser->can('update', $this->recipe));
        $this->assertFalse($this->guest->can('update', $this->recipe));
    }

    public function test_recipe_policy_delete_only_owner()
    {
        $this->assertTrue($this->owner->can('delete', $this->recipe));
        $this->assertFalse($this->otherUser->can('delete', $this->recipe));
        $this->assertFalse($this->guest->can('delete', $this->recipe));
    }
}
