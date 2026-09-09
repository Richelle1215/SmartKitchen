<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Recipe::query()->with('user')->where('is_public', true);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ingredients', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $recipes = $query->latest()->get();

        return view('recipes.index', compact('recipes'));
    }

    public function create(): View
    {
        return view('recipes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Breakfast,Lunch,Dinner,Dessert,Snacks,Beverages'],
            'description' => ['nullable', 'string'],
            'ingredients' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'prep_time' => ['required', 'integer', 'min:1'],
            'servings' => ['required', 'integer', 'min:1'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_public'] = $request->boolean('is_public', true);

        Recipe::create($validated);

        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully.');
    }
}
