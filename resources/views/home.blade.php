<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="flex justify-between items-center py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center">SK</div>
                <div>
                    <div class="text-lg font-bold">SmartKitchen</div>
                    <div class="text-xs uppercase tracking-[0.22em] text-stone-500">AI-powered recipe community</div>
                </div>
            </div>
            <nav class="flex gap-3 items-center">
                <a href="/recipes" class="px-4 py-2 hover:bg-stone-200 rounded-full">Recipes</a>
                <a href="/recipes/create" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600">Create Recipe</a>
            </nav>
        </header>

        <main class="mt-8 grid lg:grid-cols-[1.2fr_0.8fr] gap-8 items-center">
            <section>
                <span class="inline-block mb-4 bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-[0.2em]">Fresh ideas every day</span>
                <h1 class="text-5xl font-black leading-tight mb-4">Cook smarter with <span class="text-orange-600">SmartKitchen</span></h1>
                <p class="text-xl text-stone-600 mb-8 max-w-xl">
                    Discover recipes, save favorites, get AI-powered cooking guidance, and connect with food lovers who love sharing practical, delicious ideas.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="/recipes" class="bg-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-orange-600">Explore Recipes</a>
                    <a href="/recipes/create" class="bg-white border border-stone-200 px-6 py-3 rounded-full font-semibold hover:bg-stone-50">Share a Recipe</a>
                </div>

                <div class="mt-10 grid sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl p-4 border border-stone-200">
                        <div class="text-3xl font-black text-orange-600">12K+</div>
                        <div class="text-stone-500">Community recipes</div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-stone-200">
                        <div class="text-3xl font-black text-orange-600">4.9/5</div>
                        <div class="text-stone-500">Average rating</div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-stone-200">
                        <div class="text-3xl font-black text-orange-600">AI</div>
                        <div class="text-stone-500">Cooking coach</div>
                    </div>
                </div>
            </section>

            <aside class="bg-white rounded-3xl border border-stone-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Trending now</h2>
                    <span class="text-xs uppercase tracking-[0.2em] text-stone-400">Today</span>
                </div>
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-orange-50 border border-orange-100">
                        <div class="text-sm text-orange-700 font-semibold">Dinner</div>
                        <div class="text-lg font-bold">Crispy Garlic Pasta</div>
                        <div class="text-sm text-stone-500">by Lina Hart</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                        <div class="text-sm text-amber-700 font-semibold">Breakfast</div>
                        <div class="text-lg font-bold">Avocado Toast Bowl</div>
                        <div class="text-sm text-stone-500">by Marco Lee</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                        <div class="text-sm text-emerald-700 font-semibold">Healthy</div>
                        <div class="text-lg font-bold">Green Power Smoothie</div>
                        <div class="text-sm text-stone-500">by Sara Kim</div>
                    </div>
                </div>
            </aside>
        </main>
    </div>
</body>
</html>
