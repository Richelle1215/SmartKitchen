@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600 mt-1">Stay updated on your activity and community interactions.</p>
        </div>
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                Mark all as read
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 flex">
            <a href="{{ route('notifications.index', ['tab' => 'unread']) }}" class="px-5 py-3 font-medium {{ ($activeTab === 'unread' ? 'text-orange-600 border-b-2 border-orange-500' : 'text-gray-600') }}">
                Unread
            </a>
            <a href="{{ route('notifications.index', ['tab' => 'read']) }}" class="px-5 py-3 font-medium {{ ($activeTab === 'read' ? 'text-orange-600 border-b-2 border-orange-500' : 'text-gray-600') }}">
                Read
            </a>
        </div>

        <div class="divide-y divide-gray-200">
            @if ($activeTab === 'unread' && $unread->isEmpty())
                <div class="px-6 py-8 text-center text-gray-500">No unread notifications.</div>
            @elseif ($activeTab === 'read' && $read->isEmpty())
                <div class="px-6 py-8 text-center text-gray-500">No read notifications.</div>
            @else
                @php $notifications = $activeTab === 'unread' ? $unread : $read; @endphp
                @foreach ($notifications as $notification)
                    <div class="px-6 py-4 flex items-start justify-between gap-4 {{ is_null($notification->read_at) ? 'bg-orange-50/40' : 'bg-white' }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700">
                                    {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                </span>
                                @if (is_null($notification->read_at))
                                    <span class="text-xs font-medium text-red-600">Unread</span>
                                @endif
                            </div>
                            <h2 class="mt-2 text-lg font-semibold text-gray-900">{{ $notification->title }}</h2>
                            <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex flex-col gap-2 items-end">
                            @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                    View
                                </a>
                            @endif
                            @if(is_null($notification->read_at))
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm text-gray-700 hover:text-gray-900 font-medium">
                                        Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
