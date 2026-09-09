@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden h-screen flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-blue-100">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('messages.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                    Back
                </a>
            </div>

            <!-- Messages -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                @forelse ($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-900' }} rounded-lg p-4 shadow">
                            <p>{{ $message->content }}</p>
                            <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-600">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="border-t p-6 bg-white">
                <form method="POST" action="{{ route('messages.store', $user) }}" id="messageForm" class="flex gap-2">
                    @csrf
                    <input type="text" name="content" id="messageInput" placeholder="Type your message..." required
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll to bottom of messages
document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;

// Focus input on load
document.getElementById('messageInput').focus();

// Submit form with Enter key
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('messageForm').submit();
    }
});

// Auto-refresh messages every 2 seconds
setInterval(function() {
    location.reload();
}, 2000);
</script>
@endsection
