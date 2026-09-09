<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display user's messages/conversations
     */
    public function index()
    {
        $user = auth()->user();
        
        $conversations = Message::where('sender_id', $user->id)
                                ->orWhere('recipient_id', $user->id)
                                ->with('sender', 'recipient')
                                ->latest()
                                ->get()
                                ->groupBy(function($message) use ($user) {
                                    return $message->sender_id === $user->id 
                                        ? $message->recipient_id 
                                        : $message->sender_id;
                                })
                                ->map(function($messages) {
                                    return $messages->first();
                                });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user
     */
    public function show(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return redirect()->route('messages.index');
        }

        $messages = Message::where(function($q) use ($authUser, $user) {
                            $q->where('sender_id', $authUser->id)
                              ->where('recipient_id', $user->id);
                        })
                        ->orWhere(function($q) use ($authUser, $user) {
                            $q->where('sender_id', $user->id)
                              ->where('recipient_id', $authUser->id);
                        })
                        ->with('sender', 'recipient')
                        ->orderBy('created_at')
                        ->get();

        // Mark messages as read
        Message::where('recipient_id', $authUser->id)
               ->where('sender_id', $user->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    /**
     * Send a new message
     */
    public function store(Request $request, User $recipient)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json($message->load('sender'));
        }

        return back()->with('success', 'Message sent!');
    }

    /**
     * Delete a message
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);
        $message->delete();

        return back()->with('success', 'Message deleted!');
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $count = Message::where('recipient_id', auth()->id())
                       ->where('is_read', false)
                       ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead(User $user)
    {
        Message::where('recipient_id', auth()->id())
               ->where('sender_id', $user->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Search messages
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $user = auth()->user();

        $messages = Message::where(function($q) use ($user, $query) {
                            $q->where('sender_id', $user->id)
                              ->orWhere('recipient_id', $user->id);
                        })
                        ->where('content', 'like', "%{$query}%")
                        ->with('sender', 'recipient')
                        ->latest()
                        ->paginate(20);

        return view('messages.search', compact('messages', 'query'));
    }
}
