<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Guest cannot access dashboard
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test 2: Guest cannot access profile show page
     */
    public function test_guest_cannot_access_profile_show()
    {
        $response = $this->get('/profile/show');
        $response->assertRedirect('/login');
    }

    /**
     * Test 3: Authenticated user can access dashboard (redirects to profile.dashboard)
     */
    public function test_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        
        // Dashboard redirects to profile.dashboard
        $response->assertRedirect('/profile/dashboard');
        
        // Now test the actual dashboard page
        $response = $this->actingAs($user)->get('/profile/dashboard');
        $response->assertOk();
        $response->assertViewIs('profile.dashboard');
        $response->assertViewHas(['user', 'stats', 'recipes']);
    }

    /**
     * Test 4: Authenticated user can access profile show page
     */
    public function test_user_can_access_profile_show()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/show');
        
        $response->assertOk();
        $response->assertViewIs('profile.show');
        $response->assertViewHas(['user', 'recipeCount', 'followersCount', 'followingCount']);
    }

    /**
     * Test 5: Authenticated user can access profile edit page
     */
    public function test_user_can_access_profile_edit()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        
        $response->assertOk();
        $response->assertViewIs('profile.edit');
        $response->assertViewHas('user');
    }

    /**
     * Test 6: User can update profile information
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'bio' => 'Old bio',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'bio' => 'New bio',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('status', 'profile-updated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'bio' => 'New bio',
        ]);
    }

    /**
     * Test 7: User can upload profile picture
     */
    public function test_user_can_upload_profile_picture()
    {
        $user = User::factory()->create();

        // Create a fake file (doesn't require GD extension)
        $file = \Illuminate\Http\UploadedFile::fake()->create('profile.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post('/profile/upload-picture', [
            'profile_picture' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'profile-picture-updated');

        // Verify file was stored
        $this->assertNotNull($user->fresh()->profile_picture);
    }

    /**
     * Test 8: Profile picture upload validates file type
     */
    public function test_profile_picture_validates_file_type()
    {
        $user = User::factory()->create();

        // Create a fake text file
        $file = \Illuminate\Http\UploadedFile::fake()->create('profile.txt', 100, 'text/plain');

        $response = $this->actingAs($user)->post('/profile/upload-picture', [
            'profile_picture' => $file,
        ]);

        $response->assertSessionHasErrors('profile_picture');
    }

    /**
     * Test 9: Profile picture upload validates file size
     */
    public function test_profile_picture_validates_file_size()
    {
        $user = User::factory()->create();

        // Create a fake image larger than 2MB
        $file = \Illuminate\Http\UploadedFile::fake()->create('profile.jpg', 3000, 'image/jpeg');

        $response = $this->actingAs($user)->post('/profile/upload-picture', [
            'profile_picture' => $file,
        ]);

        $response->assertSessionHasErrors('profile_picture');
    }

    /**
     * Test 10: Dashboard displays correct stats
     */
    public function test_dashboard_displays_correct_stats()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/dashboard');

        $response->assertViewHas('stats', function ($stats) {
            return isset($stats['total_recipes'])
                && isset($stats['total_likes'])
                && isset($stats['followers'])
                && isset($stats['following'])
                && isset($stats['total_ratings'])
                && isset($stats['avg_rating']);
        });
    }

    /**
     * Test 11: Profile shows user information
     */
    public function test_profile_shows_user_information()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'bio' => 'Test bio',
        ]);

        $response = $this->actingAs($user)->get('/profile/show');

        $response->assertSee('Test User');
        $response->assertSee('test@example.com');
        $response->assertSee('Test bio');
    }

    /**
     * Test 12: Unauthenticated user redirected on profile access
     */
    public function test_unauthenticated_user_redirected_from_profile_edit()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }
}
