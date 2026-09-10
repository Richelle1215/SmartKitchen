<?php

namespace App\Http\Controllers;

use App\Models\RecipeCategory;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    /**
     * Display all recipe categories
     */
    public function index(): View
    {
        $categories = RecipeCategory::all();
        
        return view('categories.index', [
            'categories' => $categories,
            'pageTitle' => 'Categories',
        ]);
    }

    /**
     * Display recipes for a specific category
     */
    public function show($categorySlug): View
    {
        $category = RecipeCategory::where('slug', $categorySlug)->firstOrFail();
        $recipes = $category->recipes()->where('is_published', true)->get();
        
        return view('categories.show', [
            'category' => $category,
            'recipes' => $recipes,
            'pageTitle' => $category->name,
        ]);
    }
}
