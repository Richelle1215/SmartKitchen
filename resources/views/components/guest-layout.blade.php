<!-- Guest Layout Component for Auth Pages -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($pageTitle) ? $pageTitle . ' - SmartKitchen' : 'SmartKitchen' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-orange-50 to-red-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Header -->
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center">SK</div>
                <span class="text-2xl font-bold text-gray-900">SmartKitchen</span>
            </a>
            <p class="text-gray-600">AI-powered recipe sharing and cooking community</p>
        </div>

        <!-- Main Content Card -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white rounded-lg shadow-lg border border-gray-200">
            {{ $slot }}
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-gray-900 mr-4">Home</a>
            <a href="{{ route('recipes.index') }}" class="hover:text-gray-900 mr-4">Recipes</a>
            <a href="#" class="hover:text-gray-900">About</a>
        </div>

        <!-- Flash Messages -->
        @if ($errors->any())
            <div class="mt-6 w-full sm:max-w-md">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="mt-6 w-full sm:max-w-md">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-700">{{ session('status') }}</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
