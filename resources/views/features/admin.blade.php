<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
<div class="max-w-6xl mx-auto px-4 py-10">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
            <h1 class="text-4xl font-bold">Administration</h1>
        </div>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 font-medium hover:bg-stone-50">Home</a>
    </header>

    <div class="grid gap-6 md:grid-cols-4">
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200"><div class="text-sm text-stone-500">Users</div><div class="mt-3 text-3xl font-black text-orange-600">2,410</div></div>
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200"><div class="text-sm text-stone-500">Recipes</div><div class="mt-3 text-3xl font-black text-orange-600">8,640</div></div>
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200"><div class="text-sm text-stone-500">Reports</div><div class="mt-3 text-3xl font-black text-orange-600">24</div></div>
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200"><div class="text-sm text-stone-500">Moderation</div><div class="mt-3 text-3xl font-black text-orange-600">7</div></div>
    </div>
</div>
</body>
</html>
