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
        <header class="flex justify-between items-center py-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center">SK</div>
                <div>
                    <div class="text-lg font-bold">SmartKitchen</div>
                    <div class="text-xs uppercase tracking-[0.22em] text-stone-500">AI-powered recipe community</div>
                </div>
            </div>

            <nav class="hidden lg:flex items-center gap-2 text-sm text-stone-600">
                <a href="#features" class="px-3 py-2 rounded-full hover:bg-stone-200 hover:text-stone-900">Features</a>
                <a href="#pantry" class="px-3 py-2 rounded-full hover:bg-stone-200 hover:text-stone-900">Pantry</a>
                <a href="#planner" class="px-3 py-2 rounded-full hover:bg-stone-200 hover:text-stone-900">Meal Planner</a>
                <a href="#assistant" class="px-3 py-2 rounded-full hover:bg-stone-200 hover:text-stone-900">AI Assistant</a>
                <a href="#community" class="px-3 py-2 rounded-full hover:bg-stone-200 hover:text-stone-900">Community</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 hover:bg-stone-200 rounded-full">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-stone-900 text-white px-4 py-2 rounded-full hover:bg-stone-700">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 hover:bg-stone-200 rounded-full">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600">Sign Up</a>
                @endauth
            </div>
        </header>

        <main class="mt-8 grid lg:grid-cols-[1.2fr_0.8fr] gap-8 items-center">
            <section>
                <span class="inline-block mb-4 bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-[0.2em]">Fresh ideas every day</span>
                <h1 class="text-5xl font-black leading-tight mb-4">Cook smarter with <span class="text-orange-600">SmartKitchen</span></h1>
                <p class="text-xl text-stone-600 mb-8 max-w-xl">
                    Discover recipes, plan balanced meals, get AI-powered cooking help, and connect with food lovers who share practical, delicious ideas.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="{{ route('recipes.index') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-orange-600">Explore Recipes</a>
                    @auth
                        <a href="{{ route('recipes.create') }}" class="bg-white border border-stone-200 px-6 py-3 rounded-full font-semibold hover:bg-stone-50">Share a Recipe</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white border border-stone-200 px-6 py-3 rounded-full font-semibold hover:bg-stone-50">Share a Recipe</a>
                    @endauth
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

        <section class="mt-20">
            <div class="flex items-center justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">Community uploads</p>
                    <h2 class="text-3xl font-bold text-stone-900">All uploaded recipes</h2>
                </div>
                <a href="{{ route('recipes.index') }}" class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-stone-900">
                    Browse all recipes
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            @if($recipes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($recipes as $recipe)
                        <article class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="relative h-48 bg-stone-200 overflow-hidden">
                                @if($recipe->recipe_image)
                                    <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-amber-100">
                                        <svg class="w-16 h-16 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-orange-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold">
                                    {{ $recipe->category->name ?? 'Uncategorized' }}
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs uppercase tracking-[0.2em] text-stone-500">{{ $recipe->prep_time ?? 0 }} min</span>
                                    <span class="text-sm font-medium text-stone-600">{{ $recipe->servings ?? 2 }} servings</span>
                                </div>
                                <h3 class="text-xl font-bold text-stone-900 mb-2">{{ $recipe->title }}</h3>
                                <p class="text-sm text-stone-600 mb-4 line-clamp-3">{{ $recipe->description }}</p>

                                <div class="flex items-center justify-between pt-4 border-t border-stone-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-xs font-bold text-orange-700">
                                            {{ strtoupper(substr($recipe->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-stone-700">{{ $recipe->user->name ?? 'Unknown' }}</span>
                                    </div>
                                    <a href="{{ route('recipes.show', $recipe) }}" class="text-orange-600 hover:text-orange-700 font-semibold">View</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-stone-200 p-12 text-center">
                    <p class="text-lg text-stone-600">No recipes have been uploaded yet.</p>
                </div>
            @endif
        </section>

        <section id="features" class="mt-20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">Everything you need</p>
                    <h2 class="text-3xl font-bold">Built for everyday cooking</h2>
                </div>
                <a href="{{ route('recipes.index') }}" class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-stone-700 hover:text-stone-900">
                    Browse all recipes
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                <a href="{{ route('recipes.index') }}#pantry" id="pantry" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-2xl">🥬</div>
                    <h3 class="text-xl font-bold mb-2">Smart Pantry</h3>
                    <p class="text-stone-600">Track ingredients, spot missing items, and turn what you have into meals you can cook tonight.</p>
                </a>

                <a href="{{ route('recipes.index') }}#planner" id="planner" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-2xl">📅</div>
                    <h3 class="text-xl font-bold mb-2">Meal Planner</h3>
                    <p class="text-stone-600">Organize weekly meals, plan prep sessions, and keep your kitchen routine simple and consistent.</p>
                </a>

                <a href="{{ route('recipes.index') }}#assistant" id="assistant" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-2xl">🤖</div>
                    <h3 class="text-xl font-bold mb-2">AI Assistant</h3>
                    <p class="text-stone-600">Get recipe suggestions, substitutions, and cooking help tailored to what you have right now.</p>
                </a>

                <a href="{{ route('recipes.index') }}#nutrition" id="nutrition" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-lime-100 text-2xl">📊</div>
                    <h3 class="text-xl font-bold mb-2">Nutrition Analysis</h3>
                    <p class="text-stone-600">Review calories, macros, and healthier recipe swaps without overcomplicating meal prep.</p>
                </a>

                <a href="{{ route('recipes.index') }}#community" id="community" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-2xl">👨‍🍳</div>
                    <h3 class="text-xl font-bold mb-2">Community</h3>
                    <p class="text-stone-600">Follow creators, save favorites, join conversations, and discover recipes from real home cooks.</p>
                </a>

                <a href="{{ route('recipes.index') }}#admin" id="admin" class="block rounded-3xl border border-stone-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-md transition">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-2xl">⚙️</div>
                    <h3 class="text-xl font-bold mb-2">Administration</h3>
                    <p class="text-stone-600">Manage users, recipes, categories, reports, moderation, and platform statistics from one place.</p>
                </a>
            </div>
        </section>

        <section class="mt-20 bg-stone-900 text-white rounded-3xl p-8 md:p-10">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-8 items-center">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-orange-300 font-semibold">Smart choices</p>
                    <h2 class="mt-3 text-3xl font-bold">More than recipes — a kitchen system that keeps improving.</h2>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="text-2xl font-black text-orange-300">24/7</div>
                        <div class="text-stone-300">Cooking guidance</div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="text-2xl font-black text-orange-300">1-click</div>
                        <div class="text-stone-300">Meal planning</div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="text-2xl font-black text-orange-300">10x</div>
                        <div class="text-stone-300">More kitchen ideas</div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="text-2xl font-black text-orange-300">100%</div>
                        <div class="text-stone-300">Practical recipes</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
