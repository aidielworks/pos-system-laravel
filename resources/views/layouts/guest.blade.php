<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POSnita') }}</title>
        <link rel="icon" href="{{ asset('asset/img/app_icon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-100">
            <div class="flex flex-col items-center">
                <img class="block max-h-36 text-gray-800" src="{{ asset('asset/img/app_logo.png') }}" alt="App Logo">
            </div>

            <div class="bg-white max-w-7xl mx-auto rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
