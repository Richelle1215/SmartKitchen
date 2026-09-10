@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Image -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            @if($recipe->recipe_image)
                <img src="{{ asset('storage/' . $recipe->recipe_image) }}" alt="{{ $recipe->title }}" class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                    <svg class="w-24 h-24 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif

            <div class="p-6">
                <!-- Title and Category -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $recipe->title }}</h1>
                        <span class="inline-block bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-medium">
                            {{ $recipe->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    @auth
                        @if(auth()->user()->id === $recipe->user_id)
                            <div class="flex gap-2">
                                <a href="{{ route('recipes.edit', $recipe) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" class="inline" onsubmit="return confirm('Delete this recipe?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Recipe Stats -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 pb-6 border-b border-gray-200">
                    @if($recipe->prep_time)
                        <div>
                            <p class="text-gray-600 text-sm">Prep Time</p>
                            <p class="text-2xl font-bold text-orange-500">{{ $recipe->prep_time }}m</p>
                        </div>
                    @endif
                    @if($recipe->cook_time)
                        <div>
                            <p class="text-gray-600 text-sm">Cook Time</p>
                            <p class="text-2xl font-bold text-orange-500">{{ $recipe->cook_time }}m</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-600 text-sm">Servings</p>
                        <p class="text-2xl font-bold text-orange-500">{{ $recipe->servings }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Views</p>
                        <p class="text-2xl font-bold text-orange-500">{{ $recipe->view_count }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Rating</p>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-orange-500">{{ number_format($recipe->average_rating, 1) }}</span>
                            <span class="text-gray-500 ml-1">/ 5</span>
                        </div>
                    </div>
                </div>

                <!-- Interaction Buttons -->
                <div class="flex flex-wrap gap-3 mb-6">
                    @auth
                        @if(!($recipe->user_id === auth()->user()->id))
                            <!-- Like Button -->
                            <form method="POST" action="{{ route('recipes.like', $recipe) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 {{ $isLiked ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} hover:bg-red-100 hover:text-red-600 rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ $recipe->like_count }} Likes
                                </button>
                            </form>

                            <!-- Favorite Button -->
                            <form method="POST" action="{{ route('recipes.favorite', $recipe) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 {{ $isFavorited ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }} hover:bg-yellow-100 hover:text-yellow-600 rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2 {{ $isFavorited ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Save
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                            Login to interact
                        </a>
                    @endauth
                </div>

                <!-- User Info -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-orange-200 flex items-center justify-center">
                            @if($recipe->user->profile_picture)
                                <img src="{{ asset('storage/' . $recipe->user->profile_picture) }}" alt="{{ $recipe->user->name }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <span class="text-orange-600 font-bold text-lg">{{ substr($recipe->user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $recipe->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $recipe->user->total_followers }} followers</p>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->id !== $recipe->user_id)
                            <button onclick="alert('Follow feature coming soon')" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                                Follow
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Recipe</h2>
            <p class="text-gray-700 leading-relaxed">{{ $recipe->description }}</p>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Ingredients -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Ingredients
                    </h2>
                    <ul class="space-y-3">
                        @forelse($recipe->ingredients as $ingredient)
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span class="text-gray-700">
                                    <span class="font-medium">{{ $ingredient->quantity }}</span>
                                    <span class="text-gray-600">{{ $ingredient->unit }}</span>
                                    <span class="text-gray-700">{{ $ingredient->ingredient_name }}</span>
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-500">No ingredients added</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Instructions -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Instructions
                    </h2>
                    <div class="space-y-6">
                        @forelse($recipe->instructions as $instruction)
                            <div class="flex">
                                <div class="flex items-center justify-center w-8 h-8 bg-orange-500 text-white rounded-full font-bold flex-shrink-0">
                                    {{ $instruction->step_number }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-gray-700">{{ $instruction->instruction }}</p>
                                    @if($instruction->image)
                                        <img src="{{ $instruction->image }}" alt="Step {{ $instruction->step_number }}" class="mt-3 rounded-lg max-w-xs">
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No instructions added</p>
                        @endforelse
                    </div>
                </div>

                <!-- Video -->
                @if($recipe->recipe_video)
                    <div class="bg-white rounded-lg shadow p-6 mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Video Tutorial</h3>
                        <video controls class="w-full rounded-lg">
                            <source src="{{ asset('storage/' . $recipe->recipe_video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif

                <!-- Ratings Section -->
                <div class="bg-white rounded-lg shadow p-6 mt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Ratings & Reviews</h3>

                    @auth
                        @if(!($recipe->user_id === auth()->user()->id))
                            <form method="POST" action="{{ route('recipes.rate', $recipe) }}" class="mb-8 pb-8 border-b border-gray-200">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" 
                                                   {{ $rating && $rating->stars == $i ? 'checked' : '' }} class="hidden">
                                            <label for="star{{ $i }}" class="cursor-pointer text-3xl {{ ($rating && $rating->stars >= $i) || (!$rating && false) ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition">
                                                ★
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <textarea name="review" placeholder="Share your thoughts about this recipe..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" rows="3">{{ $rating?->review }}</textarea>
                                <button type="submit" class="mt-4 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                                    {{ $rating ? 'Update Rating' : 'Submit Rating' }}
                                </button>
                            </form>
                        @endif
                    @endauth

                    <!-- Ratings List -->
                    <div class="space-y-4">
                        @forelse($recipe->ratings as $r)
                            <div class="pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $r->user->name }}</h4>
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $r->stars)
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700">{{ $r->review }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $r->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No ratings yet. Be the first to rate this recipe!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-lg shadow p-6 mt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        Comments ({{ $recipe->comments()->where('parent_id', null)->count() }})
                    </h3>

                    @auth
                        <!-- Add Comment Form -->
                        <form method="POST" action="{{ route('comments.store', $recipe) }}" class="mb-8 pb-8 border-b border-gray-200">
                            @csrf
                            <div class="flex gap-4 mb-4">
                                <div class="w-10 h-10 rounded-full bg-orange-200 flex-shrink-0 flex items-center justify-center">
                                    @if(auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-orange-600 font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <textarea name="content" placeholder="Share your thoughts about this recipe..." 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none" 
                                              rows="3" required></textarea>
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <p class="text-gray-600"><a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-600">Login</a> to post a comment</p>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse($recipe->comments()->where('parent_id', null)->latest()->get() as $comment)
                            <div class="pb-6 border-b border-gray-100 last:border-0">
                                <!-- Comment Header -->
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-full bg-orange-200 flex-shrink-0 flex items-center justify-center">
                                        @if($comment->user->profile_picture)
                                            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span class="text-orange-600 font-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                            @auth
                                                @if(auth()->user()->id === $comment->user_id)
                                                    <div class="flex gap-2">
                                                        <button onclick="editComment({{ $comment->id }})" class="text-blue-500 hover:text-blue-600 text-sm">Edit</button>
                                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Delete this comment?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-600 text-sm">Delete</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="text-gray-700 mb-2">{{ $comment->content }}</p>
                                        <p class="text-xs text-gray-500 mb-3">{{ $comment->created_at->diffForHumans() }}</p>

                                        <!-- Replies -->
                                        @if($comment->replies->count() > 0)
                                            <div class="ml-4 space-y-3 mb-3 pl-4 border-l-2 border-gray-200">
                                                @foreach($comment->replies as $reply)
                                                    <div class="pb-3 border-b border-gray-100 last:border-0">
                                                        <div class="flex justify-between items-start mb-1">
                                                            <h5 class="font-medium text-sm text-gray-900">{{ $reply->user->name }}</h5>
                                                            @auth
                                                                @if(auth()->user()->id === $reply->user_id)
                                                                    <div class="flex gap-2">
                                                                        <button onclick="editComment({{ $reply->id }})" class="text-blue-500 hover:text-blue-600 text-xs">Edit</button>
                                                                        <form method="POST" action="{{ route('comments.destroy', $reply) }}" class="inline" onsubmit="return confirm('Delete this reply?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-red-500 hover:text-red-600 text-xs">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                        <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $reply->created_at->diffForHumans() }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No comments yet. Be the first to share your thoughts!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editComment(commentId) {
    alert('Edit comment functionality coming soon');
    // TODO: Implement inline edit for comments
}
</script>
@endsection
