<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        $category = RecipeCategory::query()->first() ?? RecipeCategory::create([
            'name' => 'Desserts',
            'slug' => 'desserts',
            'description' => 'Dessert recipes',
        ]);

        return [
            'user_id' => User::factory(),
            'category_id' => $category->id,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'prep_time' => $this->faker->numberBetween(10, 45),
            'cook_time' => $this->faker->numberBetween(10, 60),
            'servings' => $this->faker->numberBetween(2, 8),
            'is_published' => true,
            'average_rating' => 0,
            'rating_count' => 0,
            'view_count' => 0,
            'like_count' => 0,
            'comment_count' => 0,
        ];
    }
}
