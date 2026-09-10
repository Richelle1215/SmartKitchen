<?php

namespace Database\Seeders;

use App\Models\RecipeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Breakfast',
                'description' => 'Morning meals and breakfast dishes',
                'icon' => '🍳',
            ],
            [
                'name' => 'Lunch',
                'description' => 'Midday meals and lunch recipes',
                'icon' => '🥗',
            ],
            [
                'name' => 'Dinner',
                'description' => 'Evening meals and dinner recipes',
                'icon' => '🍽️',
            ],
            [
                'name' => 'Appetizers',
                'description' => 'Starters and appetizers',
                'icon' => '🥒',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweets, cakes, and desserts',
                'icon' => '🍰',
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks and beverages',
                'icon' => '🍹',
            ],
            [
                'name' => 'Soups',
                'description' => 'Soups and broths',
                'icon' => '🍲',
            ],
            [
                'name' => 'Salads',
                'description' => 'Salads and cold dishes',
                'icon' => '🥙',
            ],
            [
                'name' => 'Pasta',
                'description' => 'Pasta and noodle dishes',
                'icon' => '🍝',
            ],
            [
                'name' => 'Rice',
                'description' => 'Rice and grain dishes',
                'icon' => '🍚',
            ],
            [
                'name' => 'Meat & Poultry',
                'description' => 'Beef, chicken, and poultry recipes',
                'icon' => '🍗',
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fish and seafood recipes',
                'icon' => '🐟',
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Vegetarian and meat-free dishes',
                'icon' => '🥬',
            ],
            [
                'name' => 'Vegan',
                'description' => 'Vegan recipes without animal products',
                'icon' => '🌱',
            ],
            [
                'name' => 'Baking',
                'description' => 'Breads, pastries, and baked goods',
                'icon' => '🥐',
            ],
            [
                'name' => 'Grilling',
                'description' => 'Grilled and barbecued dishes',
                'icon' => '🔥',
            ],
            [
                'name' => 'Cooking Techniques',
                'description' => 'Specific cooking methods',
                'icon' => '👨‍🍳',
            ],
            [
                'name' => 'Ethnic Cuisine',
                'description' => 'International and ethnic recipes',
                'icon' => '🌍',
            ],
        ];

        foreach ($categories as $category) {
            RecipeCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
            ]);
        }
    }
}
