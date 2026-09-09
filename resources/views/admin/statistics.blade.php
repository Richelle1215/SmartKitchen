@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Platform Statistics</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow p-6">
                <p class="text-blue-100 text-sm">Total Users</p>
                <p class="text-4xl font-bold">{{ $stats['total_users'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow p-6">
                <p class="text-green-100 text-sm">Total Recipes</p>
                <p class="text-4xl font-bold">{{ $stats['total_recipes'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow p-6">
                <p class="text-purple-100 text-sm">Total Comments</p>
                <p class="text-4xl font-bold">{{ $stats['total_comments'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow p-6">
                <p class="text-yellow-100 text-sm">Total Ratings</p>
                <p class="text-4xl font-bold">{{ $stats['total_ratings'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow p-6">
                <p class="text-red-100 text-sm">Total Reports</p>
                <p class="text-4xl font-bold">{{ $stats['total_reports'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg shadow p-6">
                <p class="text-indigo-100 text-sm">Avg Recipe Rating</p>
                <p class="text-4xl font-bold">⭐ {{ number_format($stats['avg_recipe_rating'] ?? 0, 1) }}</p>
            </div>
        </div>

        <!-- Growth Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">User Growth (Last 30 Days)</h2>
                <div class="h-40 bg-gray-50 rounded flex items-center justify-center text-gray-600">
                    <p>Chart loading... (implement with Chart.js)</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recipe Growth (Last 30 Days)</h2>
                <div class="h-40 bg-gray-50 rounded flex items-center justify-center text-gray-600">
                    <p>Chart loading... (implement with Chart.js)</p>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">User Growth Data</h2>
            </div>

            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">New Users</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">New Recipes</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($userGrowth as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-blue-600">{{ $day->count }}</td>
                            <td class="px-6 py-3 text-right">
                                @php
                                    $recipeCount = $recipeGrowth->firstWhere('date', $day->date)->count ?? 0;
                                @endphp
                                <span class="font-semibold text-green-600">{{ $recipeCount }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-600">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Export Options -->
        <div class="mt-8 flex gap-4">
            <button onclick="exportData('csv')" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                📥 Export CSV
            </button>
            <button onclick="exportData('pdf')" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">
                📥 Export PDF
            </button>
        </div>
    </div>
</div>

<script>
function exportData(format) {
    alert(`Export as ${format.toUpperCase()} feature coming soon!`);
}
</script>
@endsection
