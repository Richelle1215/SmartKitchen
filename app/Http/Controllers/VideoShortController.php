<?php

namespace App\Http\Controllers;

use App\Models\VideoShort;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoShortController extends Controller
{
    /**
     * Display video shorts feed
     */
    public function index()
    {
        $shorts = VideoShort::where('is_published', true)
                           ->with('recipe', 'user')
                           ->latest()
                           ->paginate(10);

        return view('video-shorts.index', compact('shorts'));
    }

    /**
     * Show create video short form
     */
    public function create()
    {
        $recipes = Recipe::where('is_published', true)->get();
        return view('video-shorts.create', compact('recipes'));
    }

    /**
     * Store a new video short
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => ['required', 'exists:recipes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'video_url' => ['required', 'url'],
            'duration' => ['required', 'integer', 'min:1', 'max:60'],
            'tags' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_published'] = true;
        $validated['view_count'] = 0;
        $validated['slug'] = Str::slug($validated['title']);

        VideoShort::create($validated);

        return redirect()->route('video-shorts.index')
                       ->with('success', 'Video short created successfully!');
    }

    /**
     * Show video short details
     */
    public function show(VideoShort $short)
    {
        $short->increment('view_count');
        $short->load(['recipe', 'user', 'comments', 'likes']);

        $relatedShorts = VideoShort::where('recipe_id', $short->recipe_id)
                                   ->where('id', '!=', $short->id)
                                   ->where('is_published', true)
                                   ->take(3)
                                   ->get();

        return view('video-shorts.show', compact('short', 'relatedShorts'));
    }

    /**
     * Delete video short
     */
    public function destroy(VideoShort $short)
    {
        $this->authorize('delete', $short);
        $short->delete();

        return redirect()->route('video-shorts.index')
                       ->with('success', 'Video short deleted!');
    }

    /**
     * Like/Unlike a video short
     */
    public function toggleLike(VideoShort $short)
    {
        $user = auth()->user();

        $like = $short->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'count' => $short->likes()->count()]);
        }

        $short->likes()->create(['user_id' => $user->id]);
        return response()->json(['liked' => true, 'count' => $short->likes()->count()]);
    }

    /**
     * Add comment to video short
     */
    public function addComment(Request $request, VideoShort $short)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500'],
        ]);

        $short->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comment added!');
    }

    /**
     * Get trending video shorts
     */
    public function trending()
    {
        $shorts = VideoShort::where('is_published', true)
                           ->with('recipe', 'user')
                           ->orderByDesc('view_count')
                           ->paginate(12);

        return view('video-shorts.trending', compact('shorts'));
    }

    /**
     * Search video shorts
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $shorts = VideoShort::where('is_published', true)
                           ->where(function($q) use ($query) {
                               $q->where('title', 'like', "%{$query}%")
                                 ->orWhere('description', 'like', "%{$query}%")
                                 ->orWhere('tags', 'like', "%{$query}%");
                           })
                           ->with('recipe', 'user')
                           ->latest()
                           ->paginate(12);

        return view('video-shorts.search', compact('shorts', 'query'));
    }
}
