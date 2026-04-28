<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ELITISRIPIW') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="bg-[#1c1527] font-sans antialiased text-white">
        <div class="min-h-screen bg-[#1c1527]">
            @if (! request()->routeIs('admin.*'))
                <x-site-navbar />
            @endif

            @if (isset($header))
                <header class="border-b border-white/10 bg-[#241b35] shadow-lg">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>

            @if (! request()->routeIs('admin.*'))
                <x-site-footer />
            @endif
        </div>

        @stack('scripts')
    </body>
</html>
