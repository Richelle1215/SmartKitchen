<?php

namespace Tests\Feature;

use App\Models\RecipeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategorySystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Categories index page displays all categories
     */
    public function test_categories_index_displays_all_categories()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);

        $response = $this->get('/categories');

        $response->assertOk();
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
        
        $categories = RecipeCategory::all();
        $this->assertGreaterThan(0, $categories->count());
    }

    /**
     * Test 2: Categories index page contains category links
     */
    public function test_categories_index_contains_category_links()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);

        $response = $this->get('/categories');

        $category = RecipeCategory::first();
        $response->assertSee(route('categories.show', $category->slug));
    }

    /**
     * Test 3: Category detail page shows category information
     */
    public function test_category_show_page_displays_category_info()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $category = RecipeCategory::first();

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertOk();
        $response->assertViewIs('categories.show');
        $response->assertViewHas('category');
        $response->assertViewHas('recipes');
        $response->assertSee($category->name);
    }

    /**
     * Test 4: Category detail page shows no recipes message when empty
     */
    public function test_category_show_empty_category()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $category = RecipeCategory::first();

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertOk();
        $response->assertSee('No recipes found');
    }

    /**
     * Test 5: Categories are properly seeded
     */
    public function test_categories_seeder_creates_categories()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);

        $categories = RecipeCategory::all();

        // Check minimum categories
        $this->assertGreaterThanOrEqual(10, $categories->count());

        // Check specific categories exist
        $this->assertTrue(RecipeCategory::where('slug', 'breakfast')->exists());
        $this->assertTrue(RecipeCategory::where('slug', 'lunch')->exists());
        $this->assertTrue(RecipeCategory::where('slug', 'dinner')->exists());
        $this->assertTrue(RecipeCategory::where('slug', 'beverages')->exists());
    }

    /**
     * Test 6: Category attributes are set
     */
    public function test_category_attributes()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $category = RecipeCategory::first();

        $this->assertNotNull($category->name);
        $this->assertNotNull($category->slug);
        $this->assertNotNull($category->icon);
    }

    /**
     * Test 7: Categories are accessible by slug
     */
    public function test_category_accessible_by_slug()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);
        $category = RecipeCategory::where('slug', 'breakfast')->first();

        $this->assertNotNull($category);

        $response = $this->get(route('categories.show', $category->slug));
        $response->assertOk();
    }

    /**
     * Test 8: Invalid category returns 404
     */
    public function test_invalid_category_returns_404()
    {
        $response = $this->get('/categories/non-existent-category');
        $response->assertNotFound();
    }

    /**
     * Test 9: Category index displays recipe counts
     */
    public function test_categories_index_displays_recipe_counts()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);

        $response = $this->get('/categories');

        // Check that recipe counts are displayed (will be 0 initially)
        $response->assertSee('recipes');
    }

    /**
     * Test 10: Categories route returns correct view
     */
    public function test_categories_route_returns_correct_view()
    {
        $this->artisan('db:seed', ['--class' => 'RecipeCategorySeeder']);

        $response = $this->get('/categories');

        $response->assertViewIs('categories.index');
        $response->assertSee('Recipe Categories');
    }
}
