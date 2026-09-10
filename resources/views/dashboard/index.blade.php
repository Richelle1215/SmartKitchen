@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-8 mb-8">
            <h1 class="text-4xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-blue-100 mt-2">Here's an overview of your cooking journey</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <x-stat-card 
                label="My Recipes" 
                :value="$stats['total_recipes']" 
                icon="📚"
                bgColor="bg-orange-100"
                textColor="text-orange-600"
                subtext="Recipes you've shared"
            />
            <x-stat-card 
                label="Total Likes" 
                :value="$stats['total_likes']" 
                icon="❤️"
                bgColor="bg-red-100"
                textColor="text-red-600"
                subtext="Likes received on your recipes"
            />
            <x-stat-card 
                label="Followers" 
                :value="$stats['followers']" 
                icon="👥"
                bgColor="bg-purple-100"
                textColor="text-purple-600"
                subtext="People following you"
            />
            <x-stat-card 
                label="Following" 
                :value="$stats['following']" 
                icon="⭐"
                bgColor="bg-yellow-100"
                textColor="text-yellow-600"
                subtext="Chefs you're following"
            />
            <x-stat-card 
                label="Total Ratings" 
                :value="$stats['total_ratings']" 
                icon="⭐"
                bgColor="bg-green-100"
                textColor="text-green-600"
                subtext="Ratings your recipes received"
            />
            <x-stat-card 
                label="Average Rating" 
                :value="number_format($stats['avg_rating'], 1)" 
                icon="🌟"
                bgColor="bg-indigo-100"
                textColor="text-indigo-600"
                subtext="Your average recipe rating"
            />
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-quick-action-card 
                    href="#" 
                    icon="🍳" 
                    label="Add Recipe" 
                    description="Share a new recipe with the community"
                />
                <x-quick-action-card 
                    href="{{ route('profile.show') }}" 
                    icon="👤" 
                    label="View Profile" 
                    description="Check your public profile"
                />
                <x-quick-action-card 
                    href="{{ route('profile.edit') }}" 
                    icon="⚙️" 
                    label="Edit Profile" 
                    description="Update your name, bio, and photo"
                />
            </div>
        </div>

        <!-- Recent Activity Placeholder -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Activity</h2>
            <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-gray-600">📊 Recent activity will appear here</p>
                <p class="text-sm text-gray-500 mt-2">Activity from your recipes and community engagement</p>
            </div>
        </div>

        <!-- Recommended Recipes Placeholder -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Recommended for You</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="border border-gray-300 rounded-lg overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gray-200 h-48 flex items-center justify-center">
                            <span class="text-4xl">🍽️</span>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold text-gray-900">Recommended Recipe #{{ $i }}</p>
                            <p class="text-sm text-gray-600 mt-2">Recipe recommendation placeholder</p>
                            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                View Recipe
                            </button>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
