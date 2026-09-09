@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Shopping List</h1>
                <p class="text-gray-600">{{ $mealPlan->name }}</p>
            </div>

            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                🖨️ Print
            </button>
        </div>

        <!-- Shopping List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($shoppingList && count($shoppingList) > 0)
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach ($shoppingList as $key => $ingredient)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <input type="checkbox" class="w-5 h-5 text-blue-600 rounded cursor-pointer mr-4">
                                
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $ingredient['name'] }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ number_format($ingredient['total_quantity'], 2) }} {{ $ingredient['unit'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Print Friendly Format -->
                    <div class="mt-8 pt-8 border-t-2 border-gray-300 print:block hidden">
                        <h3 class="text-xl font-bold mb-4">Shopping List - {{ $mealPlan->name }}</h3>
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left pb-2">Item</th>
                                    <th class="text-right pb-2">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shoppingList as $ingredient)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $ingredient['name'] }}</td>
                                        <td class="text-right py-2">{{ number_format($ingredient['total_quantity'], 2) }} {{ $ingredient['unit'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm mb-3">Export as:</p>
                    <div class="flex gap-2">
                        <button onclick="exportAsCSV()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            📥 CSV
                        </button>
                        <button onclick="exportAsJSON()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            📥 JSON
                        </button>
                    </div>
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-600">No items in shopping list.</p>
                </div>
            @endif
        </div>

        <!-- Meal Summary -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Meal Summary</h2>
            
            <div class="space-y-2 text-gray-700">
                <p><strong>Meal Plan:</strong> {{ $mealPlan->name }}</p>
                <p><strong>Duration:</strong> {{ $mealPlan->start_date->format('M d, Y') }} - {{ $mealPlan->end_date->format('M d, Y') }}</p>
                <p><strong>Total Meals:</strong> {{ $mealPlan->items->count() }}</p>
                <p><strong>Plan Type:</strong> {{ ucfirst($mealPlan->meal_type) }}</p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('meal-plans.show', $mealPlan) }}" class="text-blue-600 hover:text-blue-700">← Back to Meal Plan</a>
        </div>
    </div>
</div>

<script>
function exportAsCSV() {
    const data = @json($shoppingList);
    let csv = 'Item,Quantity,Unit\n';
    
    data.forEach(function(item) {
        csv += `"${item.name}",${item.total_quantity},"${item.unit}"\n`;
    });

    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
    element.setAttribute('download', 'shopping-list.csv');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}

function exportAsJSON() {
    const data = @json($shoppingList);
    const element = document.createElement('a');
    element.setAttribute('href', 'data:application/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data, null, 2)));
    element.setAttribute('download', 'shopping-list.json');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}
</script>
@endsection
