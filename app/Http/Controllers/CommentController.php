<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request, Recipe $recipe)
    {
        $this->authorize('comment', $recipe);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['recipe_id'] = $recipe->id;

        $comment = Comment::create($validated);

        // Increment comment count on recipe
        $recipe->incrementCommentCount();

        // Create notification for recipe owner or parent comment author
        if ($comment->parent_id) {
            // Reply to comment
            $parentComment = Comment::find($comment->parent_id);
            if ($parentComment->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $parentComment->user_id,
                    'type' => 'comment_reply',
                    'title' => auth()->user()->name . ' replied to your comment',
                    'message' => auth()->user()->name . ' replied to your comment on: ' . $recipe->title,
                    'action_url' => route('recipes.show', $recipe),
                    'related_user_id' => auth()->id(),
                    'related_recipe_id' => $recipe->id,
                ]);
            }
        } else {
            // Top-level comment
            if (auth()->id() !== $recipe->user_id) {
                Notification::create([
                    'user_id' => $recipe->user_id,
                    'type' => 'comment',
                    'title' => auth()->user()->name . ' commented on your recipe',
                    'message' => auth()->user()->name . ' commented on your recipe: ' . $recipe->title,
                    'action_url' => route('recipes.show', $recipe),
                    'related_user_id' => auth()->id(),
                    'related_recipe_id' => $recipe->id,
                ]);
            }
        }

        // Update user statistics
        $stats = $recipe->user->statistics()->firstOrCreate([
            'user_id' => $recipe->user_id,
        ]);
        $stats->increment('total_comments_received');

        return redirect()->route('recipes.show', $recipe)
                       ->with('success', 'Comment posted successfully!');
    }

    /**
     * Update a comment
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $recipe = $comment->recipe;

        // Decrement comment count
        if ($comment->parent_id === null) {
            $recipe->decrementCommentCount();
        }

        $comment->delete();

        // Update user statistics
        if ($recipe->user->statistics) {
            $recipe->user->statistics->decrement('total_comments_received');
        }

        return back()->with('success', 'Comment deleted successfully!');
    }
}
