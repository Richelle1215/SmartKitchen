<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
<div class="max-w-5xl mx-auto px-4 py-10">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
            <h1 class="text-4xl font-bold">AI Assistant</h1>
        </div>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 font-medium hover:bg-stone-50">Home</a>
    </header>

    <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200">
        <div class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-orange-600">Ask SmartKitchen</div>
        <div class="space-y-4">
            <div class="rounded-2xl bg-stone-100 p-4 text-stone-700">What can I cook with tomatoes, pasta, and spinach?</div>
            <div class="rounded-2xl bg-orange-100 p-4 text-stone-800">Try a quick garlic tomato pasta with spinach and lemon zest.</div>
        </div>

        <div class="mt-6 flex gap-3">
            <input type="text" value="What can I cook with what I have?" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3" readonly>
            <button class="rounded-2xl bg-orange-500 px-5 py-3 font-semibold text-white hover:bg-orange-600">Ask</button>
        </div>
    </div>
</div>
</body>
</html>
