<?php
use function Livewire\Volt\{state};
?>

<div x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     class="relative">
    
    <!-- Skip to content link for keyboard users -->
    <a href="#swup" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:px-6 focus:py-4 focus:bg-red-700 focus:text-white focus:font-black focus:border-4 focus:border-black uppercase tracking-widest">
        Passer au contenu principal ↑
    </a>

    <header :class="{ 'bg-white shadow-xl': scrolled, 'bg-white': !scrolled }" 
            class="fixed top-0 left-0 z-50 w-full transition-all duration-300 border-b-4 border-black">
        <div class="max-w-[1440px] mx-auto flex items-stretch justify-between h-20 md:h-24 px-4 md:px-8">
            <!-- Logo Section -->
            <a href="/" class="flex items-center group relative gap-6 focus:outline-none focus:bg-black focus:text-white px-2 -mx-2 transition-colors" aria-label="AXEL STUDIO - Retour à l'accueil">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <!-- Outer Facet -->
                    <div class="absolute inset-0 border-2 border-black rotate-45 group-hover:rotate-[225deg] transition-all duration-1000 ease-in-out group-hover:border-red-700" aria-hidden="true"></div>
                    <!-- Middle Facet -->
                    <div class="absolute inset-2 border-2 border-black -rotate-45 group-hover:rotate-[135deg] transition-all duration-700 ease-in-out group-hover:border-red-700 opacity-50" aria-hidden="true"></div>
                    <!-- Inner Core -->
                    <div class="w-2 h-2 bg-black group-hover:bg-red-700 animate-ping" aria-hidden="true"></div>
                    <div class="absolute w-1 h-1 bg-black group-hover:bg-red-700" aria-hidden="true"></div>
                </div>
                <span class="font-mono text-3xl md:text-4xl font-black tracking-tighter uppercase group-hover:text-red-700 transition-colors">
                    AXEL<span class="text-red-700 group-hover:text-black">.</span>STUDIO
                </span>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8" aria-label="Navigation principale">
                <a href="/jewels" class="font-mono text-lg font-bold uppercase hover:text-red-700 focus:outline-none transition-colors tracking-tight">Bijoux</a>
                <a href="/collections" class="font-mono text-lg font-bold uppercase hover:text-red-700 focus:outline-none transition-colors tracking-tight">Collections</a>
                <a href="/news" class="font-mono text-lg font-bold uppercase hover:text-red-700 focus:outline-none transition-colors tracking-tight">Actualités</a>
                <a href="/#about" class="font-mono text-lg font-bold uppercase hover:text-red-700 focus:outline-none transition-colors tracking-tight">L'Atelier</a>
                <a href="/#contact" class="px-6 py-3 bg-black text-white font-mono font-bold uppercase hover:bg-red-700 focus:outline-none transition-all transform hover:-translate-y-1 active:translate-y-0">
                    Contact
                </a>
            </nav>

            <!-- Mobile Trigger -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen" 
                class="lg:hidden flex items-center justify-center p-2"
                aria-label="Menu principal"
                :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                aria-haspopup="true"
            >
                <div class="space-y-1.5" aria-hidden="true">
                    <div class="w-8 h-1.5 bg-black transition-all" :class="mobileMenuOpen ? 'rotate-45 translate-y-3' : ''"></div>
                    <div class="w-8 h-1.5 bg-black transition-opacity" :class="mobileMenuOpen ? 'opacity-0' : 'opacity-100'"></div>
                    <div class="w-8 h-1.5 bg-black transition-all" :class="mobileMenuOpen ? '-rotate-45 -translate-y-3' : ''"></div>
                </div>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-cloak
             @keydown.escape.window="mobileMenuOpen = false"
             class="absolute top-full left-0 w-full bg-white border-b-8 border-black shadow-2xl lg:hidden">
            <nav class="flex flex-col p-8 space-y-6">
                <a href="/jewels" @click="mobileMenuOpen = false" class="font-mono text-4xl font-black uppercase hover:text-red-700 focus:bg-black focus:text-white px-2 transition-colors">Bijoux</a>
                <a href="/collections" @click="mobileMenuOpen = false" class="font-mono text-4xl font-black uppercase hover:text-red-700 focus:bg-black focus:text-white px-2 transition-colors">Collections</a>
                <a href="/news" @click="mobileMenuOpen = false" class="font-mono text-4xl font-black uppercase hover:text-red-700 focus:bg-black focus:text-white px-2 transition-colors">Actualités</a>
                <a href="/#about" @click="mobileMenuOpen = false" class="font-mono text-4xl font-black uppercase hover:text-red-700 focus:bg-black focus:text-white px-2 transition-colors">L'Atelier</a>
                <a href="/#contact" @click="mobileMenuOpen = false" class="font-mono text-4xl font-black uppercase bg-black text-white p-4 text-center focus:bg-red-700 transition-colors">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Spacer -->
    <div class="h-20 md:h-24"></div>
</div>
