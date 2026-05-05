<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="elit-page font-sans antialiased">
        <div class="elit-auth min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div class="relative z-10">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-white/80" />
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-lg mt-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
