<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Planet F') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/planetf_logo.f2c8caa1.svg') }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/planetf_logo.f2c8caa1.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- App styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Icons (footer social links) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
</head>