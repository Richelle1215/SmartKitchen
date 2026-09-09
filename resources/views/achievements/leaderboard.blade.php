@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🏆 Leaderboard</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Rank</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">User</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Achievements</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($leaderboard as $index => $user)
                        <tr class="{{ $user->id === auth()->id() ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4">
                                <span class="text-2xl">
                                    {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : '')) }}
                                </span>
                                <span class="ml-2 font-bold text-lg text-gray-900">#{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('users.profile', $user) }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    {{ $user->name }}
                                </a>
                                @if ($user->id === auth()->id())
                                    <span class="ml-2 inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                                        You
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-semibold">
                                    {{ $user->achievements_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-2xl font-bold text-yellow-600">
                                    {{ $user->achievements_sum_points ?? 0 }} ⭐
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-600">
                                No users on the leaderboard yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Your Rank -->
        @php
            $yourRank = $leaderboard->search(fn($u) => $u->id === auth()->id()) + 1;
        @endphp

        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-3">Your Position</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Your Rank</p>
                    <p class="text-3xl font-bold text-blue-600">#{{ $yourRank ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Achievements</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ auth()->user()->achievements()->count() }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Points</p>
                    <p class="text-3xl font-bold text-purple-600">{{ auth()->user()->achievements()->sum('points') ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mt-8">
            <a href="{{ route('achievements.index') }}" class="text-blue-600 hover:text-blue-700">← Back to Achievements</a>
        </div>
    </div>
</div>
@endsection
