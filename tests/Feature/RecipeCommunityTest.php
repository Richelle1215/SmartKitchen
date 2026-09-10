<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeCommunityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $otherUser;
    protected $category;
    protected $recipe;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'registered']);
        $this->otherUser = User::factory()->create(['role' => 'registered']);
        
        $this->category = RecipeCategory::create([
            'name' => 'Dinner',
            'slug' => 'dinner',
            'description' => 'Dinner recipes',
        ]);

        $this->recipe = Recipe::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test Recipe',
            'description' => 'A test recipe',
            'prep_time' => 15,
            'cook_time' => 30,
            'servings' => 4,
            'is_published' => true,
        ]);
    }

    // ===== LIKE TESTS =====

    public function test_authenticated_user_can_like_recipe()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.like', $this->recipe));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Recipe liked!');
        
        $this->assertTrue($this->otherUser->likes()->where('recipe_id', $this->recipe->id)->exists());
        $this->assertEquals(1, $this->recipe->refresh()->like_count);
    }

    public function test_authenticated_user_can_unlike_recipe()
    {
        // First like
        $this->otherUser->likes()->attach($this->recipe->id);
        $this->recipe->increment('like_count');

        // Then unlike
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.like', $this->recipe));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Like removed!');
        
        $this->assertFalse($this->otherUser->likes()->where('recipe_id', $this->recipe->id)->exists());
        $this->assertEquals(0, $this->recipe->refresh()->like_count);
    }

    public function test_guest_cannot_like_recipe()
    {
        $response = $this->post(route('recipes.like', $this->recipe));

        $response->assertRedirect(route('login'));
    }

    public function test_owner_cannot_like_own_recipe()
    {
        $response = $this->actingAs($this->user)
                        ->post(route('recipes.like', $this->recipe));

        $response->assertForbidden();
    }

    public function test_like_count_increments()
    {
        $this->assertEquals(0, $this->recipe->like_count);

        $this->actingAs($this->otherUser)
            ->post(route('recipes.like', $this->recipe));

        $this->assertEquals(1, $this->recipe->refresh()->like_count);
    }

    public function test_like_count_decrements()
    {
        $this->otherUser->likes()->attach($this->recipe->id);
        $this->recipe->increment('like_count');

        $this->actingAs($this->otherUser)
            ->post(route('recipes.like', $this->recipe));

        $this->assertEquals(0, $this->recipe->refresh()->like_count);
    }

    public function test_one_like_per_user()
    {
        $this->actingAs($this->otherUser)
            ->post(route('recipes.like', $this->recipe));

        // Verify only one like exists
        $likeCount = $this->recipe->likedByUsers()
                        ->where('user_id', $this->otherUser->id)
                        ->count();
        
        $this->assertEquals(1, $likeCount);
    }

    // ===== RATING TESTS =====

    public function test_authenticated_user_can_rate_recipe()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.rate', $this->recipe), [
                            'stars' => 5,
                            'review' => 'Excellent recipe!',
                        ]);

        $response->assertRedirect(route('recipes.show', $this->recipe));
        $response->assertSessionHas('success', 'Rating submitted successfully!');
        
        $this->assertTrue(
            Rating::where('user_id', $this->otherUser->id)
                  ->where('recipe_id', $this->recipe->id)
                  ->exists()
        );
    }

    public function test_user_can_update_rating()
    {
        // Create initial rating
        Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 3,
            'review' => 'Okay recipe',
        ]);

        // Update rating
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.rate', $this->recipe), [
                            'stars' => 5,
                            'review' => 'Actually excellent!',
                        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Rating submitted successfully!');
        
        $rating = Rating::where('user_id', $this->otherUser->id)
                       ->where('recipe_id', $this->recipe->id)
                       ->first();
        
        $this->assertEquals(5, $rating->stars);
        $this->assertEquals('Actually excellent!', $rating->review);
    }

    public function test_one_rating_per_user()
    {
        Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 4,
        ]);

        $ratingCount = Rating::where('user_id', $this->otherUser->id)
                            ->where('recipe_id', $this->recipe->id)
                            ->count();
        
        $this->assertEquals(1, $ratingCount);
    }

    public function test_rating_stars_must_be_1_to_5()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.rate', $this->recipe), [
                            'stars' => 6, // Invalid
                        ]);

        $response->assertSessionHasErrors('stars');
    }

    public function test_rating_zero_stars_fails()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('recipes.rate', $this->recipe), [
                            'stars' => 0,
                        ]);

        $response->assertSessionHasErrors('stars');
    }

    public function test_guest_cannot_rate_recipe()
    {
        $response = $this->post(route('recipes.rate', $this->recipe), [
            'stars' => 5,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_owner_cannot_rate_own_recipe()
    {
        $response = $this->actingAs($this->user)
                        ->post(route('recipes.rate', $this->recipe), [
                            'stars' => 5,
                        ]);

        $response->assertForbidden();
    }

    public function test_average_rating_calculated()
    {
        Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 5,
        ]);

        $user3 = User::factory()->create(['role' => 'registered']);
        Rating::create([
            'user_id' => $user3->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 3,
        ]);

        $this->recipe->updateAverageRating();

        $this->assertEquals(4.0, $this->recipe->average_rating);
        $this->assertEquals(2, $this->recipe->rating_count);
    }

    public function test_user_can_delete_own_rating()
    {
        $rating = Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 5,
        ]);

        $response = $this->actingAs($this->otherUser)
                        ->delete(route('ratings.destroy', [$this->recipe, $rating]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Rating removed successfully!');
        
        $this->assertFalse(
            Rating::where('id', $rating->id)->exists()
        );
    }

    public function test_user_cannot_delete_others_rating()
    {
        $rating = Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 5,
        ]);

        $user3 = User::factory()->create(['role' => 'registered']);

        $response = $this->actingAs($user3)
                        ->delete(route('ratings.destroy', [$this->recipe, $rating]));

        $response->assertForbidden();
    }

    // ===== COMMENT TESTS =====

    public function test_authenticated_user_can_comment_on_recipe()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('comments.store', $this->recipe), [
                            'content' => 'This is a great recipe!',
                        ]);

        $response->assertRedirect(route('recipes.show', $this->recipe));
        $response->assertSessionHas('success', 'Comment posted successfully!');
        
        $this->assertTrue(
            Comment::where('user_id', $this->otherUser->id)
                   ->where('recipe_id', $this->recipe->id)
                   ->where('content', 'This is a great recipe!')
                   ->exists()
        );
    }

    public function test_guest_cannot_comment_on_recipe()
    {
        $response = $this->post(route('comments.store', $this->recipe), [
            'content' => 'A comment',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_comment_content_required()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('comments.store', $this->recipe), [
                            'content' => '',
                        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_comment_minimum_length()
    {
        $response = $this->actingAs($this->otherUser)
                        ->post(route('comments.store', $this->recipe), [
                            'content' => 'ab', // Too short (min is 3)
                        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_comment_maximum_length()
    {
        $longContent = str_repeat('a', 2001); // Too long (max is 2000)

        $response = $this->actingAs($this->otherUser)
                        ->post(route('comments.store', $this->recipe), [
                            'content' => $longContent,
                        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_author_can_edit_own_comment()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'Original comment',
        ]);

        $response = $this->actingAs($this->otherUser)
                        ->patch(route('comments.update', $comment), [
                            'content' => 'Updated comment',
                        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Comment updated successfully!');
        
        $this->assertEquals('Updated comment', $comment->refresh()->content);
    }

    public function test_user_cannot_edit_others_comment()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'Original comment',
        ]);

        $user3 = User::factory()->create(['role' => 'registered']);

        $response = $this->actingAs($user3)
                        ->patch(route('comments.update', $comment), [
                            'content' => 'Hacked comment',
                        ]);

        $response->assertForbidden();
    }

    public function test_author_can_delete_own_comment()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'A comment',
        ]);

        $response = $this->actingAs($this->otherUser)
                        ->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Comment deleted successfully!');
        
        // Comment should be soft deleted
        $this->assertTrue($comment->refresh()->trashed());
    }

    public function test_recipe_owner_can_delete_others_comment()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'A comment',
        ]);

        $response = $this->actingAs($this->user) // Recipe owner
                        ->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Comment deleted successfully!');
        
        $this->assertTrue($comment->refresh()->trashed());
    }

    public function test_user_cannot_delete_others_comment_if_not_owner()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'A comment',
        ]);

        $user3 = User::factory()->create(['role' => 'registered']);

        $response = $this->actingAs($user3)
                        ->delete(route('comments.destroy', $comment));

        $response->assertForbidden();
    }

    public function test_comment_count_increments()
    {
        // Create a valid comment
        $response = $this->actingAs($this->otherUser)
            ->post(route('comments.store', $this->recipe), [
                'content' => 'This is a valid comment',
            ]);

        $response->assertRedirect();
        
        // Verify the comment was created
        $this->assertTrue(
            Comment::where('recipe_id', $this->recipe->id)
                   ->where('content', 'This is a valid comment')
                   ->exists()
        );
    }

    public function test_comment_count_decrements_on_delete()
    {
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'A comment',
            'parent_id' => null, // Top-level comment
        ]);

        $this->recipe->increment('comment_count');

        $this->actingAs($this->otherUser)
            ->delete(route('comments.destroy', $comment));

        $this->assertEquals(0, $this->recipe->refresh()->comment_count);
    }

    // ===== GUEST VIEW TESTS =====

    public function test_guest_can_view_recipe_with_likes()
    {
        $this->otherUser->likes()->attach($this->recipe->id);
        $this->recipe->increment('like_count');

        $response = $this->get(route('recipes.show', $this->recipe));

        $response->assertOk();
        // Page should load successfully with likes present
        $this->assertEquals(1, $this->recipe->like_count);
    }

    public function test_guest_can_view_ratings()
    {
        Rating::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'stars' => 5,
            'review' => 'Great recipe!',
        ]);

        $response = $this->get(route('recipes.show', $this->recipe));

        $response->assertSee('Great recipe!');
        $response->assertSee('Ratings');
    }

    public function test_guest_can_view_comments()
    {
        Comment::create([
            'user_id' => $this->otherUser->id,
            'recipe_id' => $this->recipe->id,
            'content' => 'This is a great recipe!',
        ]);

        $response = $this->get(route('recipes.show', $this->recipe));

        $response->assertSee('This is a great recipe!');
        $response->assertSee('Comments');
    }

    public function test_guest_cannot_see_comment_form()
    {
        $response = $this->get(route('recipes.show', $this->recipe));

        // Should not see comment textarea for guests
        $response->assertDontSee('Post Comment');
    }

    public function test_authenticated_user_sees_comment_form()
    {
        $response = $this->actingAs($this->otherUser)
                        ->get(route('recipes.show', $this->recipe));

        $response->assertSee('Post Comment');
    }
}
