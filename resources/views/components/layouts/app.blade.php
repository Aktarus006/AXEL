<html class="bg-white text-gray-600">
    <head>
        <title>{{ config('app.name') }}</title>
        @vite('resources/css/app.css')
    </head>
    <body data-barba="wrapper" class="min-h-screen flex flex-col">
        <div class="w-full flex-1" data-barba="container">
            <livewire:header />
            <div data-barba="container" class="mb-auto">
                {{ $slot }}
            </div>
            <livewire:footer />
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
