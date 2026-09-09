<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Smart Pantry</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
<div class="max-w-6xl mx-auto px-4 py-10">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
            <h1 class="text-4xl font-bold">Smart Pantry</h1>
        </div>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 font-medium hover:bg-stone-50">Home</a>
    </header>

    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200">
            <div class="mb-4 text-3xl">🥕</div>
            <h2 class="text-xl font-bold">Available now</h2>
            <p class="mt-2 text-stone-600">Tomatoes, pasta, garlic, spinach, lemons.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200">
            <div class="mb-4 text-3xl">🧾</div>
            <h2 class="text-xl font-bold">Missing items</h2>
            <p class="mt-2 text-stone-600">Basil, feta, chicken, parsley.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200">
            <div class="mb-4 text-3xl">✨</div>
            <h2 class="text-xl font-bold">Suggestions</h2>
            <p class="mt-2 text-stone-600">Garlic pasta, spinach omelet, lemon herb chicken.</p>
        </div>
    </div>

    <div class="mt-10 rounded-3xl bg-orange-50 border border-orange-100 p-6">
        <h2 class="text-2xl font-bold">Tonight’s meal</h2>
        <p class="mt-3 text-stone-700">Use the ingredients you already have to create a quick, affordable dinner plan with minimal waste.</p>
        <button class="mt-5 rounded-full bg-orange-500 px-5 py-3 font-semibold text-white hover:bg-orange-600">Generate meal plan</button>
    </div>
</div>
</body>
</html>
