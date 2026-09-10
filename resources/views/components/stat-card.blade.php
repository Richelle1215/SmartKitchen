<!-- Reusable Stat Card Component -->
<div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
        </div>
        @if (isset($icon))
            <div class="text-4xl">{{ $icon }}</div>
        @else
            <div class="w-12 h-12 rounded-full {{ $bgColor ?? 'bg-blue-100' }} flex items-center justify-center">
                <svg class="w-6 h-6 {{ $textColor ?? 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        @endif
    </div>
    @if (isset($subtext))
        <p class="text-xs text-gray-500 mt-4">{{ $subtext }}</p>
    @endif
</div>
