<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $unread = $user->notifications()->whereNull('read_at')->latest()->get();
        $read = $user->notifications()->whereNotNull('read_at')->latest()->get();

        return view('notifications.index', [
            'unread' => $unread,
            'read' => $read,
            'activeTab' => $request->query('tab', 'unread'),
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if (auth()->id() !== $notification->user_id) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update([
            'read_at' => now(),
        ]);

        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read.');
    }
}
