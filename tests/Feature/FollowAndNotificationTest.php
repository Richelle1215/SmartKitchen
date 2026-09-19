<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $follower;
    protected User $target;
    protected RecipeCategory $category;
    protected Recipe $recipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = RecipeCategory::create([
            'name' => 'Desserts',
            'slug' => 'desserts',
        ]);

        $this->follower = User::factory()->create();
        $this->target = User::factory()->create();

        $this->recipe = Recipe::factory()->create([
            'user_id' => $this->target->id,
            'category_id' => $this->category->id,
            'title' => 'Chocolate Cake',
        ]);
    }

    public function test_user_can_follow_and_unfollow_another_user(): void
    {
        $this->actingAs($this->follower)
            ->post(route('users.follow', $this->target))
            ->assertRedirect();

        $this->assertTrue($this->follower->isFollowing($this->target));

        $this->actingAs($this->follower)
            ->post(route('users.follow', $this->target))
            ->assertRedirect();

        $this->assertFalse($this->follower->isFollowing($this->target));
    }

    public function test_user_cannot_follow_themselves(): void
    {
        $this->actingAs($this->target)
            ->post(route('users.follow', $this->target))
            ->assertRedirect();

        $this->assertFalse($this->target->isFollowing($this->target));
    }

    public function test_profile_shows_follower_and_following_counts(): void
    {
        $this->target->followers()->syncWithoutDetaching([$this->follower->id]);
        $this->target->following()->syncWithoutDetaching([User::factory()->create()->id]);

        $response = $this->actingAs($this->follower)
            ->get(route('users.profile', $this->target));

        $response->assertOk();
        $response->assertSeeText('Followers');
        $response->assertSeeText('Following');
    }

    public function test_followers_and_following_pages_load(): void
    {
        $this->target->followers()->syncWithoutDetaching([$this->follower->id]);
        $this->follower->following()->syncWithoutDetaching([$this->target->id]);

        $this->actingAs($this->follower)
            ->get(route('users.followers', $this->target))
            ->assertOk();

        $this->actingAs($this->follower)
            ->get(route('users.following', $this->follower))
            ->assertOk();
    }

    public function test_follow_creates_notification(): void
    {
        $this->actingAs($this->follower)
            ->post(route('users.follow', $this->target));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->target->id,
            'type' => 'follow',
        ]);
    }

    public function test_like_comment_and_rating_create_notifications(): void
    {
        $this->actingAs($this->follower)
            ->post(route('recipes.like', $this->recipe));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->target->id,
            'type' => 'like',
        ]);

        $this->actingAs($this->follower)
            ->post(route('comments.store', $this->recipe), [
                'content' => 'Nice recipe!',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->target->id,
            'type' => 'comment',
        ]);

        $this->actingAs($this->follower)
            ->post(route('recipes.rate', $this->recipe), [
                'stars' => 5,
                'review' => 'Amazing!',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->target->id,
            'type' => 'rating',
        ]);
    }

    public function test_user_can_view_notifications_and_mark_them_read(): void
    {
        Notification::create([
            'user_id' => $this->follower->id,
            'type' => 'follow',
            'title' => 'New follower',
            'message' => 'Someone started following you',
            'action_url' => route('users.profile', $this->target),
            'related_user_id' => $this->target->id,
        ]);

        $notification = $this->follower->notifications()->first();

        $this->actingAs($this->follower)
            ->get(route('notifications.index'))
            ->assertOk();

        $this->actingAs($this->follower)
            ->patch(route('notifications.read', $notification))
            ->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        Notification::create([
            'user_id' => $this->follower->id,
            'type' => 'follow',
            'title' => 'New follower',
            'message' => 'Someone started following you',
            'action_url' => route('users.profile', $this->target),
            'related_user_id' => $this->target->id,
        ]);

        Notification::create([
            'user_id' => $this->follower->id,
            'type' => 'like',
            'title' => 'Recipe liked',
            'message' => 'Your recipe was liked',
            'action_url' => route('recipes.show', $this->recipe),
            'related_recipe_id' => $this->recipe->id,
        ]);

        $this->actingAs($this->follower)
            ->post(route('notifications.mark-all-read'))
            ->assertRedirect();

        $this->assertEquals(0, $this->follower->notifications()->whereNull('read_at')->count());
    }
}
