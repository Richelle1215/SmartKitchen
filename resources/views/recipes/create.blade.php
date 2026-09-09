<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Create Recipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
                <h1 class="text-3xl font-bold">Create a recipe</h1>
            </div>
            <a href="/recipes" class="text-sm font-medium text-stone-700 underline">Back to recipes</a>
        </div>

        <form action="{{ route('recipes.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-stone-200 p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input type="text" name="title" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select name="category" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200">
                        <option>Breakfast</option>
                        <option>Lunch</option>
                        <option selected>Dinner</option>
                        <option>Dessert</option>
                        <option>Snacks</option>
                        <option>Beverages</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Prep time (minutes)</label>
                    <input type="number" min="1" name="prep_time" value="30" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Servings</label>
                    <input type="number" min="1" name="servings" value="2" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
                <div class="flex items-center pt-7">
                    <label class="inline-flex items-center gap-2 text-sm font-medium">
                        <input type="checkbox" name="is_public" value="1" checked class="h-4 w-4 text-orange-500">
                        Public recipe
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Ingredients</label>
                <textarea name="ingredients" rows="6" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="One ingredient per line"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Instructions</label>
                <textarea name="instructions" rows="7" required class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="Add step-by-step cooking instructions"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl px-5 py-3">Publish Recipe</button>
            </div>
        </form>
    </div>
</body>
</html>
