<html class="bg-white text-gray-600">
    <head>
        <title>{{ config('app.name') }}</title>
        @vite('resources/css/app.css')
    </head>
    <body data-barba="wrapper">
        <div class="flex flex-col min-h-screen">
            <livewire:header />
            <main class="flex-1">
                {{ $slot }}
            </main>
            <livewire:footer />
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
