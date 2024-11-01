<html class="bg-white text-gray-600">
    <head>
    <title>{{ config('app.name') }}</title>

    @vite('resources/css/app.css')
    </head>
    <body data-barba="wrapper">
    <div class="w-full h-full" data-barba="container">

        <livewire:header />
        <div data-barba="container">
        {{ $slot }}
        </div>
    </div>
    @vite('resources/js/app.js')
    </body>
</html>
