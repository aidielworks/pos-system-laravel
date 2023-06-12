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
        <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
            <h1 class="text-9xl font-extrabold tracking-widest">404</h1>
            <div class="bg-yellow-500 px-2 text-sm rounded rotate-12 absolute">
                Page Not Found
            </div>
            <a href="{{ route('dashboard') }}" class="mt-5 relative inline-block text-sm font-medium text-yellow-900 group active:text-yellow-500 focus:outline-none focus:ring">
                <span class="absolute inset-0 transition-transform translate-x-1 translate-y-1 bg-yellow-500 group-hover:translate-y-0 group-hover:translate-x-0"></span>

                <span class="relative block px-8 py-3 border border-current">Go Back</span>
            </a>
        </div>
    </body>
</html>
