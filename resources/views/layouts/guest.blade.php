<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="smart-auth-shell">
            <div class="smart-auth-brand">
                <a href="/" aria-label="SmartKitchen home">
                    <div class="smart-auth-mark" aria-hidden="true"></div>
                </a>
            </div>

            <div class="smart-auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
