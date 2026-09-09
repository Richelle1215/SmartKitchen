@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Video Player -->
            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-lg overflow-hidden">
                    <div class="bg-black aspect-video flex items-center justify-center text-6xl">
                        🎥
                    </div>
                    
                    <!-- Video Controls -->
                    <div class="p-4">
                        <h1 class="text-white text-2xl font-bold">{{ $short->title }}</h1>
                        <p class="text-gray-400 text-sm mt-1">Duration: {{ $short->duration }}s</p>
                        
                        <div class="flex items-center justify-between mt-4 text-gray-400">
                            <div class="flex gap-4 text-sm">
                                <span>👁️ {{ $short->view_count }} views</span>
                                <span>❤️ {{ $short->likes()->count() }} likes</span>
                                <span>💬 {{ $short->comments()->count() }} comments</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($short->description)
                        <div class="px-4 pb-4 border-t border-gray-700">
                            <h2 class="text-white font-semibold mt-4 mb-2">Description</h2>
                            <p class="text-gray-400">{{ $short->description }}</p>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if ($short->tags)
                        <div class="px-4 pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $short->tags) as $tag)
                                    <a href="{{ route('video-shorts.search', ['q' => trim($tag)]) }}" class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-1 rounded-full text-sm">
                                        #{{ trim($tag) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Creator -->
                    <div class="px-4 py-4 border-t border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($short->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-white font-semibold">{{ $short->user->name }}</p>
                                <p class="text-gray-400 text-sm">{{ $short->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <a href="{{ route('users.follow', $short->user) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm">
                            Follow
                        </a>
                    </div>

                    <!-- Recipe Link -->
                    @if ($short->recipe)
                        <div class="px-4 py-4 border-t border-gray-700 bg-gray-900">
                            <h3 class="text-white font-semibold mb-2">Recipe</h3>
                            <a href="{{ route('recipes.show', $short->recipe) }}" class="text-red-500 hover:text-red-400">
                                → View Full Recipe: {{ $short->recipe->title }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <!-- Interaction Buttons -->
                <div class="bg-gray-900 rounded-lg p-4 space-y-2">
                    <button onclick="toggleLike()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                        ❤️ Like
                    </button>
                    <button onclick="shareShort()" class="w-full bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                        📤 Share
                    </button>

                    @can('delete', $short)
                        <form method="POST" action="{{ route('video-shorts.destroy', $short) }}" style="display: inline;" 
                              onsubmit="return confirm('Delete this short?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-900 hover:bg-red-800 text-white px-4 py-2 rounded transition">
                                🗑️ Delete
                            </button>
                        </form>
                    @endcan
                </div>

                <!-- Comments Section -->
                <div class="bg-gray-900 rounded-lg p-4">
                    <h3 class="text-white font-semibold mb-3">Comments ({{ $short->comments()->count() }})</h3>
                    
                    @auth
                        <form method="POST" action="{{ route('video-shorts.comment', $short) }}" class="mb-4">
                            @csrf
                            <textarea name="content" placeholder="Add a comment..." rows="2"
                                      class="w-full px-3 py-2 bg-gray-800 text-white rounded border border-gray-700 focus:border-red-600 text-sm"
                                      required></textarea>
                            <button type="submit" class="mt-2 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                                Post
                            </button>
                        </form>
                    @endauth

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse ($short->comments as $comment)
                            <div class="text-sm border-b border-gray-800 pb-2">
                                <p class="text-white font-semibold">{{ $comment->user->name }}</p>
                                <p class="text-gray-400">{{ $comment->content }}</p>
                                <p class="text-gray-500 text-xs mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm">No comments yet. Be the first!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Related Shorts -->
                @if ($relatedShorts->count() > 0)
                    <div class="bg-gray-900 rounded-lg p-4">
                        <h3 class="text-white font-semibold mb-3">Related Shorts</h3>
                        
                        <div class="space-y-2">
                            @foreach ($relatedShorts as $related)
                                <a href="{{ route('video-shorts.show', $related) }}" class="block hover:opacity-80 transition">
                                    <div class="bg-black rounded aspect-video flex items-center justify-center text-2xl mb-1">
                                        🎥
                                    </div>
                                    <p class="text-gray-300 text-sm font-semibold truncate">{{ $related->title }}</p>
                                    <p class="text-gray-500 text-xs">{{ $related->view_count }} views</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleLike() {
    fetch("{{ route('video-shorts.like', $short) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.liked ? 'Liked!' : 'Unliked!');
        location.reload();
    });
}

function shareShort() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: '{{ $short->title }}',
            url: url
        });
    } else {
        alert('Share URL: ' + url);
    }
}
</script>
@endsection
