<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeCreationTest extends TestCase
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

    public function test_guest_cannot_access_create_page()
    {
        $response = $this->get('/recipes/create');
        $this->assertTrue($response->status() === 403 || $response->status() === 302);
    }

    public function test_authenticated_user_can_access_create_page()
    {
        $response = $this->actingAs($this->user)->get('/recipes/create');
        $response->assertOk();
        $response->assertViewIs('recipes.create');
        $response->assertViewHas('categories');
    }

    public function test_create_page_shows_categories()
    {
        $response = $this->actingAs($this->user)->get('/recipes/create');
        $categories = $response->viewData('categories');
        $this->assertGreaterThan(0, $categories->count());
    }

    public function test_create_recipe_with_valid_data()
    {
        Storage::fake('public');
        $data = [
            'title' => 'Pasta Carbonara',
            'description' => 'A classic Italian pasta dish',
            'category_id' => $this->category->id,
            'servings' => 4,
            'prep_time' => 15,
            'ingredients' => [
                ['name' => 'Pasta', 'quantity' => 400, 'unit' => 'grams'],
                ['name' => 'Bacon', 'quantity' => 200, 'unit' => 'grams'],
                ['name' => 'Eggs', 'quantity' => 3, 'unit' => 'piece'],
            ],
            'instructions' => [
                ['text' => 'Boil pasta according to package directions'],
                ['text' => 'Fry bacon until crispy and chop into pieces'],
                ['text' => 'Mix eggs with cheese and cream'],
                ['text' => 'Combine hot pasta with bacon and egg mixture'],
            ],
        ];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $this->assertTrue($response->status() >= 200 && $response->status() < 400);
        $this->assertDatabaseHas('recipes', ['title' => 'Pasta Carbonara']);
    }

    public function test_recipe_title_required()
    {
        $data = ['title' => '', 'description' => 'test', 'category_id' => $this->category->id, 'servings' => 4];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('title');
    }

    public function test_recipe_description_required()
    {
        $data = ['title' => 'test', 'description' => '', 'category_id' => $this->category->id, 'servings' => 4];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('description');
    }

    public function test_recipe_category_required()
    {
        $data = ['title' => 'test', 'description' => 'test', 'category_id' => '', 'servings' => 4];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('category_id');
    }

    public function test_recipe_servings_required()
    {
        $data = ['title' => 'test', 'description' => 'test', 'category_id' => $this->category->id, 'servings' => ''];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('servings');
    }

    public function test_recipe_servings_must_be_positive()
    {
        $data = ['title' => 'test', 'description' => 'test', 'category_id' => $this->category->id, 'servings' => 0];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('servings');
    }

    public function test_recipe_belongs_to_user()
    {
        Storage::fake('public');
        $data = [
            'title' => 'My Recipe',
            'description' => 'My dish description here',
            'category_id' => $this->category->id,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'Flour', 'quantity' => 2, 'unit' => 'cups'],
            ],
            'instructions' => [
                ['text' => 'Mix all ingredients together thoroughly'],
            ],
        ];
        $this->actingAs($this->user)->post('/recipes', $data);
        $recipe = Recipe::where('title', 'My Recipe')->first();
        $this->assertNotNull($recipe);
        $this->assertEquals($this->user->id, $recipe->user_id);
    }

    public function test_recipe_belongs_to_category()
    {
        Storage::fake('public');
        $data = [
            'title' => 'Cat Recipe',
            'description' => 'Cat dish description here',
            'category_id' => $this->category->id,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'Milk', 'quantity' => 1, 'unit' => 'cup'],
            ],
            'instructions' => [
                ['text' => 'Pour milk into bowl and serve'],
            ],
        ];
        $this->actingAs($this->user)->post('/recipes', $data);
        $recipe = Recipe::where('title', 'Cat Recipe')->first();
        $this->assertNotNull($recipe);
        $this->assertEquals($this->category->id, $recipe->category_id);
    }

    public function test_recipe_is_published()
    {
        Storage::fake('public');
        $data = [
            'title' => 'Pub Recipe',
            'description' => 'Pub dish description here',
            'category_id' => $this->category->id,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'Beer', 'quantity' => 1, 'unit' => 'pint'],
            ],
            'instructions' => [
                ['text' => 'Pour beer into glass and enjoy cold'],
            ],
        ];
        $this->actingAs($this->user)->post('/recipes', $data);
        $recipe = Recipe::where('title', 'Pub Recipe')->first();
        $this->assertNotNull($recipe);
        $this->assertTrue($recipe->is_published);
    }

    public function test_unregistered_user_cannot_create()
    {
        $guest = User::factory()->create(['role' => 'guest']);
        $data = ['title' => 'test', 'description' => 'test', 'category_id' => $this->category->id, 'servings' => 4];
        $response = $this->actingAs($guest)->post('/recipes', $data);
        $response->assertStatus(403);
    }

    public function test_prep_time_optional()
    {
        Storage::fake('public');
        $data = [
            'title' => 'Optional',
            'description' => 'No prep time specified',
            'category_id' => $this->category->id,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'Water', 'quantity' => 1, 'unit' => 'cup'],
            ],
            'instructions' => [
                ['text' => 'Boil water in a pot on stove'],
            ],
        ];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $this->assertTrue($response->status() >= 200 && $response->status() < 400);
        $this->assertDatabaseHas('recipes', ['title' => 'Optional']);
    }

    public function test_recipe_image_must_be_image()
    {
        Storage::fake('public');
        $data = [
            'title' => 'test',
            'description' => 'test',
            'category_id' => $this->category->id,
            'servings' => 4,
            'recipe_image' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ];
        $response = $this->actingAs($this->user)->post('/recipes', $data);
        $response->assertSessionHasErrors('recipe_image');
    }

    public function test_recipe_form_displays()
    {
        $response = $this->actingAs($this->user)->get('/recipes/create');
        $response->assertSee('Create New Recipe');
        $response->assertSee('Recipe Title');
        $response->assertSee('Category');
    }

    public function test_recipe_fields_set()
    {
        Storage::fake('public');
        $data = [
            'title' => 'Full Recipe',
            'description' => 'Full dish description',
            'category_id' => $this->category->id,
            'prep_time' => 30,
            'cook_time' => 45,
            'servings' => 6,
            'ingredients' => [
                ['name' => 'Eggs', 'quantity' => 3, 'unit' => 'piece'],
                ['name' => 'Sugar', 'quantity' => 100, 'unit' => 'grams'],
            ],
            'instructions' => [
                ['text' => 'Beat eggs with sugar until fluffy'],
                ['text' => 'Pour into baking pan and bake'],
            ],
        ];
        $this->actingAs($this->user)->post('/recipes', $data);
        $this->assertDatabaseHas('recipes', [
            'title' => 'Full Recipe',
            'description' => 'Full dish description',
            'prep_time' => 30,
            'cook_time' => 45,
            'servings' => 6,
        ]);
    }
}
