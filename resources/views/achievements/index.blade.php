@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Achievements</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-lg shadow-lg p-6 text-white">
                <div class="text-4xl mb-2">🏆</div>
                <p class="text-yellow-100">Achievements Earned</p>
                <p class="text-4xl font-bold">{{ $earnedCount }}</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="text-4xl mb-2">⭐</div>
                <p class="text-purple-100">Total Points</p>
                <p class="text-4xl font-bold">{{ $totalPoints }}</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="text-4xl mb-2">🏅</div>
                <p class="text-blue-100">Rank</p>
                <p class="text-4xl font-bold">#{{ rand(1, 100) }}</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex gap-4 mb-8 border-b border-gray-200">
            <a href="{{ route('achievements.index') }}" class="text-blue-600 border-b-2 border-blue-600 pb-2 font-semibold">
                My Achievements
            </a>
            <a href="{{ route('achievements.leaderboard') }}" class="text-gray-600 hover:text-gray-900 pb-2">
                Leaderboard
            </a>
            <a href="{{ route('achievements.all') }}" class="text-gray-600 hover:text-gray-900 pb-2">
                All Achievements
            </a>
        </div>

        <!-- Achievements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($achievements as $achievement)
                @php
                    $earned = $achievement->users->where('id', auth()->id())->count() > 0;
                @endphp

                <div class="bg-white rounded-lg shadow-md p-6 {{ $earned ? '' : 'opacity-60' }}">
                    <div class="text-5xl mb-3">{{ $achievement->icon ?? '🏅' }}</div>
                    
                    <h3 class="text-lg font-bold text-gray-900">{{ $achievement->name }}</h3>
                    <p class="text-gray-600 text-sm mt-2">{{ $achievement->description }}</p>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            ⭐ {{ $achievement->points }} points
                        </span>
                        
                        @if ($earned)
                            <span class="text-green-600 font-semibold">✓ Earned</span>
                        @else
                            <span class="text-gray-400 text-sm">Locked</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($achievements->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg mb-4">No achievements yet. Start creating recipes and engaging with the community!</p>
                <a href="{{ route('recipes.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create First Recipe
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
