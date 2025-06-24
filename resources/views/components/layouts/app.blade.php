<html class="bg-white text-gray-600">
<head>
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Swup wrapper (STATIC) -->
    <div id="swup-wrapper">
        <livewire:header />

        <!-- Swup dynamic container (CHANGES) -->
        <div id="swup" class="transition-brutal">
            {{ $slot }}
        </div>

        <livewire:footer />
    </div>

    @vite('resources/js/app.js')
</body>
</html>
