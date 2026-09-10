@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-4xl font-bold text-gray-900">My Collections</h1>
                <a href="{{ route('collections.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Collection
                </a>
            </div>
            <p class="text-gray-600">Create and organize your favorite recipes into custom collections</p>
        </div>

        <!-- Status Messages -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-600 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Collections Grid -->
        @if($collections->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($collections as $collection)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Collection Header -->
                        <div class="h-32 bg-gradient-to-br from-orange-400 to-orange-500 flex items-end p-4">
                            <h3 class="text-2xl font-bold text-white">{{ $collection->name }}</h3>
                        </div>

                        <!-- Collection Body -->
                        <div class="p-6">
                            @if($collection->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $collection->description }}</p>
                            @endif

                            <!-- Stats -->
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">
                                        <span class="font-bold text-orange-500">{{ $collection->recipes_count }}</span> recipes
                                    </span>
                                    <span class="text-gray-600">
                                        @if($collection->is_public)
                                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Public</span>
                                        @else
                                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">Private</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('collections.show', $collection) }}" class="flex-1 px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-lg transition text-center font-medium">
                                    View
                                </a>
                                <a href="{{ route('collections.edit', $collection) }}" class="flex-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition text-center font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('collections.destroy', $collection) }}" class="inline flex-1" onsubmit="return confirm('Delete this collection? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($collections->hasPages())
                <div class="mt-8">
                    {{ $collections->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No collections yet</h3>
                <p class="text-gray-600 mb-6">Create your first collection to organize recipes</p>
                <a href="{{ route('collections.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create First Collection
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
