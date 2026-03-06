<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-darkmode text-grey antialiased">
        <div class="min-h-screen flex items-center justify-center px-4 py-10 relative overflow-hidden">
            <div class="pointer-events-none absolute w-80 h-80 bg-gradient-to-bl from-tealGreen from-40% to-charcoalGray to-80% rounded-full blur-400 opacity-70 -top-24 -left-24 z-0"></div>
            <div class="pointer-events-none absolute w-80 h-80 bg-gradient-to-br from-primary from-40% to-secondary to-80% rounded-full blur-400 opacity-70 -bottom-24 -right-24 z-0"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-lg bg-dark_grey/90 backdrop-blur-md border border-dark_border shadow-2xl px-8 pt-14 pb-8 text-center z-10">
                <a href="{{ url('/') }}" class="absolute top-0 right-0 mr-8 mt-8 text-grey hover:text-primary transition" aria-label="Close / Back to home">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
