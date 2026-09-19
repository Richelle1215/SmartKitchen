@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 flex items-center">
                    <span class="text-4xl mr-3">📦</span>
                    My Pantry
                </h1>
                <p class="text-gray-600 mt-2">Manage your ingredients and get recipe suggestions</p>
            </div>
            <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" 
                    class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                + Add Item
            </button>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Items</p>
                <p class="text-3xl font-bold text-orange-500">{{ $items->total() }}</p>
            </div>
            @if($lowStockItems->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow p-6">
                    <p class="text-gray-600 text-sm">Low Stock</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $lowStockItems->count() }}</p>
                </div>
            @endif
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Categories</p>
                <p class="text-3xl font-bold text-blue-500">{{ $categories->count() }}</p>
            </div>
        </div>

        <!-- Pantry Items Table -->
        @if($items->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ingredient</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Quantity</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Category</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Expiry</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-900 font-medium">{{ $item->ingredient_name }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="inline-flex items-center gap-2 bg-gray-100 px-3 py-1 rounded">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if($item->category)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                            {{ $item->category }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if($item->expiry_date)
                                        @if($item->isExpired())
                                            <span class="inline-block bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">
                                                ⚠️ Expired
                                            </span>
                                        @else
                                            {{ $item->expiry_date->format('M d, Y') }}
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->isLowOnStock())
                                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">
                                            🔔 Low Stock
                                        </span>
                                    @else
                                        <span class="text-green-600 text-sm">✓ In Stock</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="editItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-3">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('pantry.destroy', $item) }}" class="inline" onsubmit="return confirm('Remove this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mb-8">
                {{ $items->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center mb-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-4">Your pantry is empty</p>
                <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" 
                        class="inline-block px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                    Add First Item
                </button>
            </div>
        @endif

        <!-- Recipe Suggestions -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">💡 Recipe Suggestions</h2>
            <p class="text-gray-600 mb-6">Based on items in your pantry, you can make these recipes:</p>
            <div id="recipe-suggestions" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <p class="text-gray-500 text-center py-8 col-span-full">Loading suggestions...</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="add-item-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Add Pantry Item</h2>
        <form method="POST" action="{{ route('pantry.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="ingredient_name" class="block text-sm font-medium text-gray-700 mb-1">Ingredient Name *</label>
                <input type="text" name="ingredient_name" id="ingredient_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="quantity" id="quantity" step="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
                </div>
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                    <input type="text" name="unit" id="unit" placeholder="kg, g, L, etc" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
                </div>
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input type="text" name="category" id="category" placeholder="Vegetables, Spices, Dairy, etc"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
            </div>
            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
            </div>
            <div>
                <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                <input type="number" name="low_stock_threshold" id="low_stock_threshold" step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                    Add Item
                </button>
                <button type="button" onclick="document.getElementById('add-item-modal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load recipe suggestions
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('{{ route("pantry.suggest-recipes") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();

        const container = document.getElementById('recipe-suggestions');
        const canMake = data.can_make || [];
        const almostCanMake = data.almost_can_make || [];
        const suggestions = [...canMake, ...almostCanMake];

        if (suggestions.length > 0) {
            const sections = [
                canMake.length ? `<div class="col-span-full"><h3 class="font-bold text-gray-900 mb-3">Can make</h3><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">${canMake.map(recipe => `
                    <a href="${recipe.url}" class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 hover:shadow-lg transition block">
                        <div class="font-bold text-gray-900 mb-2">${recipe.title}</div>
                        <div class="text-sm text-gray-700 mb-2">
                            <span class="inline-block bg-white px-2 py-1 rounded mr-2">${recipe.match_count}/${recipe.total_ingredients} ingredients</span>
                        </div>
                        <div class="text-xs text-gray-600">Missing: ${recipe.missing_ingredients && recipe.missing_ingredients.length ? recipe.missing_ingredients.join(', ') : 'None'}</div>
                    </a>
                `).join('')}</div></div>` : '',
                almostCanMake.length ? `<div class="col-span-full mt-6"><h3 class="font-bold text-gray-900 mb-3">Almost can make</h3><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">${almostCanMake.map(recipe => `
                    <a href="${recipe.url}" class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 hover:shadow-lg transition block">
                        <div class="font-bold text-gray-900 mb-2">${recipe.title}</div>
                        <div class="text-sm text-gray-700 mb-2">
                            <span class="inline-block bg-white px-2 py-1 rounded mr-2">${recipe.match_count}/${recipe.total_ingredients} ingredients</span>
                        </div>
                        <div class="text-xs text-gray-600">Missing: ${recipe.missing_ingredients && recipe.missing_ingredients.length ? recipe.missing_ingredients.join(', ') : 'None'}</div>
                    </a>
                `).join('')}</div></div>` : ''
            ].filter(Boolean).join('');

            container.innerHTML = sections;
        } else {
            container.innerHTML = '<p class="text-gray-500 text-center py-8 col-span-full">Add items to your pantry to get recipe suggestions</p>';
        }
    } catch (error) {
        console.error('Error loading suggestions:', error);
    }
});

function editItem(id) {
    // TODO: Implement edit functionality
    alert('Edit functionality coming soon');
}
</script>
@endsection
