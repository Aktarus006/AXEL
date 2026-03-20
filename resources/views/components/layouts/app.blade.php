<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white text-gray-950 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AXEL - Atelier de création de bijoux et d'objets uniques forgés à la main.">
    
    <title>{{ config('app.name', 'Axel Englebert Bijoutier') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=4">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    @stack('styles')
</head>
<body class="font-sans">
    <!-- Swup wrapper (STATIC) -->
    <div id="swup-wrapper">
        <livewire:header />

        <!-- Swup dynamic container (CHANGES) -->
        <main id="swup" class="transition-brutal">
            {{ $slot }}
        </main>

        <livewire:footer />
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
