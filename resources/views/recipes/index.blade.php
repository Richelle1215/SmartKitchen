<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Recipes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
                <h1 class="text-4xl font-bold">Discover Recipes</h1>
            </div>
            <div class="flex gap-3">
                <a href="/" class="bg-white border border-stone-200 rounded-full px-4 py-2 font-medium hover:bg-stone-50">Home</a>
                <a href="/recipes/create" class="bg-orange-500 text-white rounded-full px-4 py-2 font-medium hover:bg-orange-600">Add Recipe</a>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-100 text-emerald-800 px-4 py-3 border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="mb-8 bg-white rounded-2xl border border-stone-200 p-5 shadow-sm">
            <form method="GET" action="{{ route('recipes.index') }}" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_220px_auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search recipes or ingredients" class="w-full border border-stone-300 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200">

                <select name="category" class="border border-stone-300 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200">
                    <option value="">All categories</option>
                    @foreach(['Breakfast','Lunch','Dinner','Dessert','Snacks','Beverages'] as $option)
                        <option value="{{ $option }}" {{ request('category') === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-orange-500 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-orange-600">Filter</button>
            </form>
        </section>

        <section class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($recipes as $recipe)
                <article class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-orange-200 via-amber-100 to-stone-200 flex items-center justify-center text-3xl">
                        🍽️
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $recipe->category }}</span>
                            <span class="text-xs text-stone-500">{{ $recipe->prep_time }} min</span>
                        </div>
                        <h2 class="text-2xl font-semibold mb-2">{{ $recipe->title }}</h2>
                        <p class="text-stone-600 mb-4">{{ Str::limit($recipe->description ?? 'A delicious home-cooked recipe from the SmartKitchen community.', 110) }}</p>
                        <div class="flex items-center justify-between text-sm text-stone-500">
                            <span>by {{ $recipe->user?->name ?? 'Chef' }}</span>
                            <span>{{ $recipe->servings }} servings</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-dashed border-stone-300 p-10 text-center text-stone-600">
                    No recipes match your search yet. Try another ingredient or category.
                </div>
            @endforelse
        </section>
    </div>
</body>
</html>
