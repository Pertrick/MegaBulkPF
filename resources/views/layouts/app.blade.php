<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Planet F') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('images/planetf_logo.f2c8caa1.svg') }}">
        <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/planetf_logo.f2c8caa1.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-darkmode text-grey overflow-x-hidden">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-dark_grey shadow-lg border-b border-dark_border">
                <div class="max-w-screen-xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
