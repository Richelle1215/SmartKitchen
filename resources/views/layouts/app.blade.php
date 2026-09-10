<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($pageTitle) ? $pageTitle . ' - SmartKitchen' : config('app.name', 'SmartKitchen') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            <!-- Navigation Bar -->
            @include('components.navbar')

            <!-- Breadcrumb Navigation (Optional) -->
            @isset($breadcrumbs)
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <nav class="flex items-center gap-2 py-3 text-sm text-gray-600">
                            <a href="{{ route('home') }}" class="hover:text-gray-900">🏠 Home</a>
                            @foreach($breadcrumbs as $label => $url)
                                <span class="text-gray-400">/</span>
                                @if($loop->last)
                                    <span class="text-gray-900 font-medium">{{ $label }}</span>
                                @else
                                    <a href="{{ $url }}" class="hover:text-gray-900">{{ $label }}</a>
                                @endif
                            @endforeach
                        </nav>
                    </div>
                </div>
            @endisset

            <!-- Flash Messages Container -->
            <div class="fixed top-20 right-4 z-50 space-y-3 w-96 max-w-full">
                <!-- Success Messages -->
                @if ($message = Session::get('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-green-800">Success</h3>
                                <p class="text-sm text-green-700 mt-1">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($message = Session::get('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-red-600 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-red-800">Error</h3>
                                <p class="text-sm text-red-700 mt-1">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Warning Messages -->
                @if ($message = Session::get('warning'))
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow-lg animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-yellow-600 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-yellow-800">Warning</h3>
                                <p class="text-sm text-yellow-700 mt-1">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Messages -->
                @if ($message = Session::get('info'))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-lg animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-blue-800">Information</h3>
                                <p class="text-sm text-blue-700 mt-1">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-red-600 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-red-800">Validation Errors</h3>
                                <ul class="text-sm text-red-700 mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Heading (Optional) -->
            @isset($header)
                <header class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Main Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900 text-gray-300 mt-20 border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="text-white font-bold mb-4">SmartKitchen</h3>
                            <p class="text-sm text-gray-400">AI-powered recipe sharing and cooking community platform.</p>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-4">Features</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('recipes.index') }}" class="hover:text-white transition">Recipes</a></li>
                                <li><a href="{{ route('ai.assistant') }}" class="hover:text-white transition">AI Assistant</a></li>
                                <li><a href="{{ route('pantry.index') }}" class="hover:text-white transition">Smart Pantry</a></li>
                                <li><a href="{{ route('meal-plans.index') }}" class="hover:text-white transition">Meal Planner</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-4">Community</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('search.index') }}" class="hover:text-white transition">Categories</a></li>
                                <li><a href="{{ route('video-shorts.index') }}" class="hover:text-white transition">Shorts</a></li>
                                <li><a href="{{ route('achievements.index') }}" class="hover:text-white transition">Achievements</a></li>
                                <li><a href="{{ route('messages.index') }}" class="hover:text-white transition">Messages</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-4">Account</h4>
                            <ul class="space-y-2 text-sm">
                                @auth
                                    <li><a href="{{ route('profile.dashboard') }}" class="hover:text-white transition">My Profile</a></li>
                                    <li><a href="{{ route('recipes.my-recipes') }}" class="hover:text-white transition">My Recipes</a></li>
                                    <li><a href="{{ route('favorites.index') }}" class="hover:text-white transition">Favorites</a></li>
                                @else
                                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Sign In</a></li>
                                    <li><a href="{{ route('register') }}" class="hover:text-white transition">Sign Up</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                        <p>&copy; {{ date('Y') }} SmartKitchen. All rights reserved. Built with ❤️ by the SmartKitchen Team.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
