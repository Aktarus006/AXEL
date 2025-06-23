<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AXEL Jewelry</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile-menu.js'])
    @livewireStyles
    @stack('styles')
</head>
<body>
    <header class="w-full bg-black text-white">
        <nav class="container mx-auto flex items-center justify-between py-4 px-4 md:px-0">
            <a href="/" class="text-2xl font-bold tracking-widest">AXEL</a>
            <!-- Desktop Menu -->
            <ul class="hidden lg:flex space-x-8 text-lg font-semibold">
                <li><a href="#about" class="hover:text-red-700 transition">About</a></li>
                <li><a href="#collections" class="hover:text-red-700 transition">Collections</a></li>
                <li><a href="#creators" class="hover:text-red-700 transition">Creators</a></li>
                <li><a href="#contact" class="hover:text-red-700 transition">Contact</a></li>
            </ul>
            <!-- Hamburger Icon (Mobile) -->
            <div x-data="mobileMenu" class="lg:hidden">
                <button class="focus:outline-none" @click="toggle()">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <!-- Mobile Menu -->
                <div x-show="open" @click.away="close()" class="fixed inset-0 z-50 bg-black/90 flex flex-col items-center justify-center space-y-8 text-2xl font-bold transition-all duration-300" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <button class="absolute top-6 right-6 text-white" @click="close()">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <a href="#about" @click="close()" class="hover:text-red-700 transition">About</a>
                    <a href="#collections" @click="close()" class="hover:text-red-700 transition">Collections</a>
                    <a href="#creators" @click="close()" class="hover:text-red-700 transition">Creators</a>
                    <a href="#contact" @click="close()" class="hover:text-red-700 transition">Contact</a>
                </div>
            </div>
        </nav>
    </header>
    {{ $slot }}

    @livewireScripts
    @stack('scripts')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
