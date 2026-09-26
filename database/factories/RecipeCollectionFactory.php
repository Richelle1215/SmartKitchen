<?php

namespace Database\Factories;

use App\Models\RecipeCollection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecipeCollection>
 */
class RecipeCollectionFactory extends Factory
{
    protected $model = RecipeCollection::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
            'is_public' => false,
        ];
    }
}
//recipe collection factory
