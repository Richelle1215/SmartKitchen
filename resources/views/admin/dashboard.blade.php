@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Recipes</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_recipes'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Pending Reports</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['pending_reports'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Reports</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['total_reports'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Active Today</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['active_users_today'] }}</p>
            </div>
        </div>

        <!-- Admin Menu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.users') }}" class="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="text-3xl mb-2">👥</div>
                <h3 class="font-bold text-gray-900">User Management</h3>
                <p class="text-gray-600 text-sm">View and manage users</p>
            </a>

            <a href="{{ route('admin.recipes') }}" class="bg-green-50 border border-green-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="text-3xl mb-2">🍳</div>
                <h3 class="font-bold text-gray-900">Recipe Management</h3>
                <p class="text-gray-600 text-sm">Moderate recipes</p>
            </a>

            <a href="{{ route('admin.reports') }}" class="bg-red-50 border border-red-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="text-3xl mb-2">📋</div>
                <h3 class="font-bold text-gray-900">Reports</h3>
                <p class="text-gray-600 text-sm">Handle user reports</p>
            </a>

            <a href="{{ route('admin.statistics') }}" class="bg-purple-50 border border-purple-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="font-bold text-gray-900">Statistics</h3>
                <p class="text-gray-600 text-sm">View analytics</p>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Users</h2>
                
                <div class="space-y-3">
                    @forelse ($recentUsers as $user)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('admin.user-details', $user) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                        </div>
                    @empty
                        <p class="text-gray-600">No recent users</p>
                    @endforelse
                </div>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Pending Reports</h2>
                
                <div class="space-y-3">
                    @forelse ($pendingReports as $report)
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded border border-red-200">
                            <div>
                                <p class="font-semibold text-gray-900">{{ class_basename($report->reportable_type) }}</p>
                                <p class="text-gray-600 text-sm">Reason: {{ $report->reason }}</p>
                            </div>
                            <a href="{{ route('admin.report-details', $report) }}" class="text-red-600 hover:text-red-700 text-sm">Review</a>
                        </div>
                    @empty
                        <p class="text-gray-600">No pending reports</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Recipes -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Recipes</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Author</th>
                            <th class="px-4 py-2 text-right">Rating</th>
                            <th class="px-4 py-2 text-right">Comments</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($recentRecipes as $recipe)
                            <tr>
                                <td class="px-4 py-2">{{ $recipe->title }}</td>
                                <td class="px-4 py-2">{{ $recipe->user->name }}</td>
                                <td class="px-4 py-2 text-right">⭐ {{ $recipe->average_rating ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-right">{{ $recipe->comments()->count() }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.recipe-details', $recipe) }}" class="text-blue-600 hover:text-blue-700 text-xs">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-gray-600">No recent recipes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
