@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 px-4">
            <h1 class="text-4xl font-bold text-white">Video Shorts</h1>
            <a href="{{ route('video-shorts.create') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                + Create Short
            </a>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 mb-8 px-4">
            <a href="{{ route('video-shorts.index') }}" class="text-white border-b-2 border-red-600 pb-2 font-semibold">
                For You
            </a>
            <a href="{{ route('video-shorts.trending') }}" class="text-gray-400 hover:text-white pb-2">
                Trending
            </a>
        </div>

        @if ($shorts->count() > 0)
            <!-- Video Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
                @foreach ($shorts as $short)
                    <div class="bg-gray-900 rounded-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105">
                        <!-- Video Thumbnail -->
                        <div class="relative bg-black h-80 flex items-center justify-center group cursor-pointer">
                            <div class="text-6xl">🎥</div>
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <a href="{{ route('video-shorts.show', $short) }}" class="bg-red-600 text-white rounded-full p-4 hover:bg-red-700">
                                    ▶
                                </a>
                            </div>
                            <span class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                {{ $short->duration }}s
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="text-white font-bold truncate">{{ $short->title }}</h3>
                            <p class="text-gray-400 text-sm">{{ $short->recipe->title ?? 'Recipe' }}</p>
                            
                            <div class="flex items-center justify-between mt-3 text-gray-400 text-sm">
                                <span>👁️ {{ $short->view_count }} views</span>
                                <span>❤️ {{ $short->likes()->count() }} likes</span>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('video-shorts.show', $short) }}" class="flex-1 text-center bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-sm">
                                    Watch
                                </a>
                                @can('delete', $short)
                                    <form method="POST" action="{{ route('video-shorts.destroy', $short) }}" style="display: inline;" 
                                          onsubmit="return confirm('Delete this short?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-sm">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 px-4">
                {{ $shorts->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl mb-4">No video shorts yet. Be the first to create one!</p>
                <a href="{{ route('video-shorts.create') }}" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    Create Your First Short
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
