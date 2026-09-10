<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($pageTitle) ? $pageTitle . ' - SmartKitchen Admin' : 'Admin Dashboard - SmartKitchen' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            <!-- Admin Navbar -->
            <nav class="bg-red-900 text-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-yellow-400 text-red-900 font-bold flex items-center justify-center text-sm">⚙️</div>
                                <span class="font-bold hidden sm:inline">SmartKitchen Admin</span>
                            </a>
                        </div>

                        <!-- Admin Menu -->
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Users
                            </a>
                            <a href="{{ route('admin.recipes') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Recipes
                            </a>
                            <a href="{{ route('admin.reports') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Reports
                            </a>
                            <a href="{{ route('admin.statistics') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Statistics
                            </a>
                            <a href="{{ route('admin.activity-logs') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-800 transition">
                                Logs
                            </a>
                        </div>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative hidden md:block">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-red-800 transition">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white text-gray-900 rounded-lg shadow-lg border border-gray-200 z-50" style="display: none;">
                                <a href="{{ route('profile.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded-t-lg">
                                    👤 My Profile
                                </a>
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    🏠 Back to Site
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 rounded-b-lg">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden">
                            <button x-data="{ open: false }" @click="open = !open" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <div x-data="{ open: false }" x-show="open" class="md:hidden pb-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Users
                        </a>
                        <a href="{{ route('admin.recipes') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Recipes
                        </a>
                        <a href="{{ route('admin.reports') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Reports
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Statistics
                        </a>
                        <a href="{{ route('admin.activity-logs') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 transition">
                            Logs
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
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

            <!-- Breadcrumb Navigation (Optional) -->
            @isset($breadcrumbs)
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <nav class="flex items-center gap-2 py-3 text-sm text-gray-600">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">📊 Admin</a>
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
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} SmartKitchen Admin Panel. Manage with care.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
