@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white">🔥 Trending Shorts</h1>
            <p class="text-gray-400 mt-2">Most watched video shorts this week</p>
        </div>

        @if ($shorts->count() > 0)
            <!-- Shorts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach ($shorts as $short)
                    <div class="group cursor-pointer">
                        <a href="{{ route('video-shorts.show', $short) }}">
                            <!-- Video Thumbnail -->
                            <div class="relative bg-black h-48 rounded-lg flex items-center justify-center overflow-hidden group-hover:shadow-xl transition">
                                <div class="text-4xl">🎥</div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition flex items-end justify-center pb-4">
                                    <button class="bg-red-600 text-white rounded-full p-3 transform scale-0 group-hover:scale-100 transition">
                                        ▶
                                    </button>
                                </div>
                                <span class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {{ $short->duration }}s
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="mt-3">
                                <h3 class="text-white font-bold truncate group-hover:text-red-500 transition">
                                    {{ $short->title }}
                                </h3>
                                <p class="text-gray-400 text-sm">{{ $short->recipe->title ?? 'Recipe' }}</p>
                                <p class="text-gray-500 text-xs mt-2">
                                    👁️ {{ $short->view_count }} views • ❤️ {{ $short->likes()->count() }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="text-white">
                {{ $shorts->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl">No trending shorts yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
