<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Recipes</p>
                    <p class="mt-3 text-4xl font-bold text-orange-600">{{ $stats['total_recipes'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Servings</p>
                    <p class="mt-3 text-4xl font-bold text-orange-600">{{ $stats['total_servings'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Avg. Prep</p>
                    <p class="mt-3 text-4xl font-bold text-orange-600">{{ $stats['avg_prep_time'] }}m</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Your recipes</h3>
                    <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-semibold hover:bg-orange-600">Add recipe</a>
                </div>

                @if($recipes->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">You have not created any recipes yet. Share your first dish with the SmartKitchen community.</p>
                @else
                    <div class="space-y-4">
                        @foreach($recipes as $recipe)
                            <div class="border border-stone-200 dark:border-gray-700 rounded-2xl p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.2em] text-orange-500">{{ $recipe->category }}</p>
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $recipe->title }}</h4>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $recipe->servings }} servings</span>
                                </div>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ Str::limit($recipe->description ?? 'Recipe created in SmartKitchen.', 120) }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
