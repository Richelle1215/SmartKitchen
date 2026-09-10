<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCollection;
use App\Models\RecipeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeFavoritesAndCollectionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected Recipe $recipe;
    protected Recipe $anotherRecipe;
    protected RecipeCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create category
        $this->category = RecipeCategory::create(['name' => 'Desserts']);

        // Create users
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'other@example.com',
        ]);

        // Create recipes
        $this->recipe = Recipe::factory()->create([
            'user_id' => $this->otherUser->id,
            'recipe_category_id' => $this->category->id,
            'title' => 'Chocolate Cake',
        ]);

        $this->anotherRecipe = Recipe::factory()->create([
            'user_id' => $this->otherUser->id,
            'recipe_category_id' => $this->category->id,
            'title' => 'Vanilla Cheesecake',
        ]);
    }

    // ==================== FAVORITES TESTS ====================

    /**
     * Test: Authenticated user can favorite a recipe
     */
    public function test_authenticated_user_can_favorite_recipe(): void
    {
        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe))
            ->assertRedirect();

        $this->assertTrue(
            $this->recipe->favoritedByUsers()->where('user_id', $this->user->id)->exists()
        );
    }

    /**
     * Test: Authenticated user can unfavorite a recipe
     */
    public function test_authenticated_user_can_unfavorite_recipe(): void
    {
        // First favorite
        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe));

        // Then unfavorite
        $this->actingAs($this->user)
            ->delete(route('favorites.destroy', $this->recipe))
            ->assertRedirect();

        $this->assertFalse(
            $this->recipe->favoritedByUsers()->where('user_id', $this->user->id)->exists()
        );
    }

    /**
     * Test: User can view their favorite recipes
     */
    public function test_user_can_view_favorite_recipes(): void
    {
        // Favorite multiple recipes
        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe));

        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->anotherRecipe));

        $response = $this->actingAs($this->user)
            ->get(route('favorites.index'));

        $response->assertSuccessful()
            ->assertViewHas('favorites');
    }

    /**
     * Test: Guest cannot favorite a recipe
     */
    public function test_guest_cannot_favorite_recipe(): void
    {
        $this->post(route('recipes.favorite', $this->recipe))
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Guest cannot unfavorite a recipe
     */
    public function test_guest_cannot_unfavorite_recipe(): void
    {
        $this->delete(route('favorites.destroy', $this->recipe))
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Favorite count updates correctly
     */
    public function test_favorite_count_updates(): void
    {
        $initialCount = $this->recipe->favorites()->count();

        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe));

        $this->assertEquals($initialCount + 1, $this->recipe->favorites()->count());
    }

    /**
     * Test: Toggling favorite twice results in not favorited
     */
    public function test_favorite_toggle_twice_unfavorites(): void
    {
        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe));

        $this->assertTrue(
            $this->recipe->favoritedByUsers()->where('user_id', $this->user->id)->exists()
        );

        // Toggle again (unfavorite)
        $this->actingAs($this->user)
            ->post(route('recipes.favorite', $this->recipe));

        $this->assertFalse(
            $this->recipe->favoritedByUsers()->where('user_id', $this->user->id)->exists()
        );
    }

    // ==================== COLLECTIONS TESTS ====================

    /**
     * Test: Authenticated user can create a collection
     */
    public function test_authenticated_user_can_create_collection(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'My Desserts',
                'description' => 'All my favorite desserts',
                'is_public' => false,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('recipe_collections', [
            'user_id' => $this->user->id,
            'name' => 'My Desserts',
            'description' => 'All my favorite desserts',
        ]);
    }

    /**
     * Test: Guest cannot create a collection
     */
    public function test_guest_cannot_create_collection(): void
    {
        $this->post(route('collections.store'), [
            'name' => 'My Desserts',
            'description' => 'All my favorite desserts',
        ])->assertRedirect(route('login'));
    }

    /**
     * Test: User can view their collections
     */
    public function test_user_can_view_their_collections(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.index'));

        $response->assertSuccessful()
            ->assertViewHas('collections');
    }

    /**
     * Test: User can view a specific collection
     */
    public function test_user_can_view_collection_details(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.show', $collection));

        $response->assertSuccessful()
            ->assertViewHas('collection', $collection);
    }

    /**
     * Test: User cannot view other user's private collection
     */
    public function test_user_cannot_view_private_collection_of_others(): void
    {
        $collection = RecipeCollection::factory()->create([
            'user_id' => $this->otherUser->id,
            'is_public' => false,
        ]);

        $this->actingAs($this->user)
            ->get(route('collections.show', $collection))
            ->assertForbidden();
    }

    /**
     * Test: Guest cannot view private collection
     */
    public function test_guest_cannot_view_private_collection(): void
    {
        $collection = RecipeCollection::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => false,
        ]);

        $this->get(route('collections.show', $collection))
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Anyone can view public collection
     */
    public function test_anyone_can_view_public_collection(): void
    {
        $collection = RecipeCollection::factory()->create([
            'user_id' => $this->otherUser->id,
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.show', $collection));

        $response->assertSuccessful();
    }

    /**
     * Test: User can rename their collection
     */
    public function test_user_can_rename_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->patch(route('collections.update', $collection), [
                'name' => 'Updated Collection Name',
                'description' => $collection->description,
                'is_public' => $collection->is_public,
            ])->assertRedirect();

        $this->assertDatabaseHas('recipe_collections', [
            'id' => $collection->id,
            'name' => 'Updated Collection Name',
        ]);
    }

    /**
     * Test: User cannot rename other user's collection
     */
    public function test_user_cannot_rename_others_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->otherUser->id]);

        $this->actingAs($this->user)
            ->patch(route('collections.update', $collection), [
                'name' => 'Updated Name',
                'description' => $collection->description,
                'is_public' => $collection->is_public,
            ])->assertForbidden();
    }

    /**
     * Test: User can delete their collection
     */
    public function test_user_can_delete_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);
        $collectionId = $collection->id;

        $this->actingAs($this->user)
            ->delete(route('collections.destroy', $collection))
            ->assertRedirect();

        $this->assertDatabaseMissing('recipe_collections', ['id' => $collectionId]);
    }

    /**
     * Test: User cannot delete other user's collection
     */
    public function test_user_cannot_delete_others_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->otherUser->id]);

        $this->actingAs($this->user)
            ->delete(route('collections.destroy', $collection))
            ->assertForbidden();
    }

    /**
     * Test: User can add recipe to their collection
     */
    public function test_user_can_add_recipe_to_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => $this->recipe->id,
            ])->assertRedirect();

        $this->assertTrue(
            $collection->recipes()->where('recipe_id', $this->recipe->id)->exists()
        );
    }

    /**
     * Test: User cannot add duplicate recipe to collection
     */
    public function test_user_cannot_add_duplicate_recipe(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);
        $collection->recipes()->attach($this->recipe->id);

        $response = $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => $this->recipe->id,
            ]);

        $response->assertRedirect()
            ->assertSessionHas('error', 'Recipe already in collection!');
    }

    /**
     * Test: User cannot add recipe to other user's collection
     */
    public function test_user_cannot_add_recipe_to_others_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->otherUser->id]);

        $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => $this->recipe->id,
            ])->assertForbidden();
    }

    /**
     * Test: User can remove recipe from their collection
     */
    public function test_user_can_remove_recipe_from_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);
        $collection->recipes()->attach($this->recipe->id);

        $this->actingAs($this->user)
            ->delete(route('collections.remove-recipe', [$collection, $this->recipe]))
            ->assertRedirect();

        $this->assertFalse(
            $collection->recipes()->where('recipe_id', $this->recipe->id)->exists()
        );
    }

    /**
     * Test: User cannot remove recipe from other user's collection
     */
    public function test_user_cannot_remove_recipe_from_others_collection(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->otherUser->id]);
        $collection->recipes()->attach($this->recipe->id);

        $this->actingAs($this->user)
            ->delete(route('collections.remove-recipe', [$collection, $this->recipe]))
            ->assertForbidden();
    }

    /**
     * Test: Collection can have multiple recipes
     */
    public function test_collection_can_have_multiple_recipes(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => $this->recipe->id,
            ]);

        $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => $this->anotherRecipe->id,
            ]);

        $this->assertEquals(2, $collection->recipes()->count());
    }

    /**
     * Test: Collection name is required
     */
    public function test_collection_name_is_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => '',
                'description' => 'A collection',
            ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Collection name cannot exceed 255 characters
     */
    public function test_collection_name_max_255_characters(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => str_repeat('a', 256),
                'description' => 'A collection',
            ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Collection description cannot exceed 1000 characters
     */
    public function test_collection_description_max_1000_characters(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'My Collection',
                'description' => str_repeat('a', 1001),
            ]);

        $response->assertSessionHasErrors('description');
    }

    /**
     * Test: User can set collection as public
     */
    public function test_user_can_set_collection_as_public(): void
    {
        $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'Public Collection',
                'is_public' => true,
            ])->assertRedirect();

        $this->assertDatabaseHas('recipe_collections', [
            'user_id' => $this->user->id,
            'name' => 'Public Collection',
            'is_public' => true,
        ]);
    }

    /**
     * Test: User can set collection as private
     */
    public function test_user_can_set_collection_as_private(): void
    {
        $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'Private Collection',
                'is_public' => false,
            ])->assertRedirect();

        $this->assertDatabaseHas('recipe_collections', [
            'user_id' => $this->user->id,
            'name' => 'Private Collection',
            'is_public' => false,
        ]);
    }

    /**
     * Test: Collection view shows recipe count
     */
    public function test_collection_view_shows_recipe_count(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);
        $collection->recipes()->attach([$this->recipe->id, $this->anotherRecipe->id]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.show', $collection));

        $response->assertSuccessful();
        $this->assertEquals(2, $collection->recipes()->count());
    }

    /**
     * Test: Collections index shows all user collections
     */
    public function test_collections_index_shows_all_user_collections(): void
    {
        RecipeCollection::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.index'));

        $response->assertSuccessful()
            ->assertViewHas('collections');

        $this->assertEquals(3, $this->user->recipeCollections()->count());
    }

    /**
     * Test: Collections index does not show other user's private collections
     */
    public function test_collections_index_does_not_show_others_private_collections(): void
    {
        RecipeCollection::factory(2)->create(['user_id' => $this->otherUser->id, 'is_public' => false]);

        $response = $this->actingAs($this->user)
            ->get(route('collections.index'));

        // Should only show the user's collections, not others'
        $this->assertEquals(0, $this->user->recipeCollections()->count());
    }

    /**
     * Test: Invalid recipe ID returns error
     */
    public function test_adding_invalid_recipe_id_returns_error(): void
    {
        $collection = RecipeCollection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->post(route('collections.add-recipe', $collection), [
                'recipe_id' => 99999,
            ]);

        $response->assertSessionHasErrors('recipe_id');
    }

    /**
     * Test: Can create multiple collections with same name
     */
    public function test_user_can_create_multiple_collections_with_same_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'My Desserts',
                'description' => 'First collection',
            ]);

        $this->actingAs($this->user)
            ->post(route('collections.store'), [
                'name' => 'My Desserts',
                'description' => 'Second collection',
            ]);

        $this->assertEquals(2, $this->user->recipeCollections()->where('name', 'My Desserts')->count());
    }
}
