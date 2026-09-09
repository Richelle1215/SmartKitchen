<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Meal Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
<div class="max-w-6xl mx-auto px-4 py-10">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
            <h1 class="text-4xl font-bold">Meal Planner</h1>
        </div>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 font-medium hover:bg-stone-50">Home</a>
    </header>

    <div class="grid gap-6 md:grid-cols-7">
        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
            <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">{{ $day }}</h3>
                <div class="mt-4 space-y-2 text-sm text-stone-700">
                    <div class="rounded-xl bg-orange-100 p-2">Breakfast</div>
                    <div class="rounded-xl bg-amber-100 p-2">Lunch</div>
                    <div class="rounded-xl bg-emerald-100 p-2">Dinner</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
