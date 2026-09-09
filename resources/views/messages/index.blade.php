@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Messages</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Conversations List -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 max-h-96 overflow-y-auto">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Conversations</h2>

                    @if ($conversations && $conversations->count() > 0)
                        <div class="space-y-2">
                            @foreach ($conversations as $otherUserId => $lastMessage)
                                <a href="{{ route('messages.show', $lastMessage->sender_id === auth()->id() ? $lastMessage->recipient : $lastMessage->sender) }}"
                                   class="block p-3 rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <p class="font-semibold text-gray-900">
                                            {{ $lastMessage->sender_id === auth()->id() ? $lastMessage->recipient->name : $lastMessage->sender->name }}
                                        </p>
                                        <span class="text-xs text-gray-500">{{ $lastMessage->created_at->format('H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">{{ $lastMessage->content }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No conversations yet</p>
                    @endif
                </div>
            </div>

            <!-- Main Message Area -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-600">Select a conversation to start messaging</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
