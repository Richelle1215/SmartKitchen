<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKitchen | Community</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-900">
<div class="max-w-6xl mx-auto px-4 py-10">
    <header class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-orange-600 font-semibold">SmartKitchen</p>
            <h1 class="text-4xl font-bold">Community</h1>
        </div>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 font-medium hover:bg-stone-50">Home</a>
    </header>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-500 font-bold text-white">LH</div>
                    <div>
                        <div class="font-semibold">Lina Hart</div>
                        <div class="text-sm text-stone-500">Shared 2 hours ago</div>
                    </div>
                </div>
                <button class="rounded-full bg-orange-100 px-3 py-1.5 text-sm font-semibold text-orange-700">Follow</button>
            </div>
            <p class="mt-4 text-stone-700">Fresh garlic pasta for busy weeknights. Quick, cheap, and family-approved.</p>
        </div>

        <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-500 font-bold text-white">SK</div>
                    <div>
                        <div class="font-semibold">Sara Kim</div>
                        <div class="text-sm text-stone-500">Posted a meal prep idea</div>
                    </div>
                </div>
                <button class="rounded-full bg-stone-900 px-3 py-1.5 text-sm font-semibold text-white">Like</button>
            </div>
            <p class="mt-4 text-stone-700">Here’s my 3-day healthy meal prep routine using pantry staples and greens.</p>
        </div>
    </div>
</div>
</body>
</html>
