<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Axel Englebert Bijoutier</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body>
    {{ $slot }}

    @livewireScripts
    @stack('scripts')
</body>
</html>
