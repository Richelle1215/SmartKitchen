@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-orange-500 font-semibold">Community</p>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }} is following</h1>
                </div>
                <a href="{{ route('users.profile', $user) }}" class="text-orange-600 hover:text-orange-700 font-medium">Back to profile</a>
            </div>

            @if($following->count())
                <div class="space-y-4">
                    @foreach($following as $followed)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-orange-200 text-orange-700 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($followed->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('users.profile', $followed) }}" class="font-semibold text-gray-900 hover:text-orange-600">
                                        {{ $followed->name }}
                                    </a>
                                    <p class="text-sm text-gray-600">{{ $followed->email }}</p>
                                </div>
                            </div>
                            @auth
                                @if(auth()->user()->id !== $followed->id)
                                    <form method="POST" action="{{ route('users.follow', $followed) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-lg {{ auth()->user()->isFollowing($followed) ? 'bg-gray-500 text-white' : 'bg-orange-500 text-white' }} hover:opacity-90 transition">
                                            {{ auth()->user()->isFollowing($followed) ? 'Unfollow' : 'Follow' }}
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $following->links() }}
                </div>
            @else
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                    Not following anyone yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
