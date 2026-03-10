<?php
use function Livewire\Volt\{state};
?>

<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 z-50 w-full bg-white border-b-4 border-black">
    <div class="flex items-stretch justify-between h-20 md:h-24">
        <!-- Logo -->
        <a href="/" class="flex items-center px-6 md:px-10 border-r-4 border-black hover:bg-black hover:text-white transition-colors duration-300">
            <span class="font-mono text-3xl md:text-5xl font-black tracking-tighter uppercase">AXEL</span>
        </a>
        
        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex flex-1">
            <a href="/jewels" class="flex-1 flex items-center justify-center font-mono text-xl font-bold border-r-4 border-black hover:bg-black hover:text-white transition-all duration-300 hover:skew-x-2">
                BIJOUX
            </a>
            <a href="/collections" class="flex-1 flex items-center justify-center font-mono text-xl font-bold border-r-4 border-black hover:bg-black hover:text-white transition-all duration-300 hover:-skew-x-2">
                COLLECTIONS
            </a>
            <a href="/news" class="flex-1 flex items-center justify-center font-mono text-xl font-bold border-r-4 border-black hover:bg-black hover:text-white transition-all duration-300 hover:skew-x-2">
                NEWS
            </a>
            <a href="/#about" class="flex-1 flex items-center justify-center font-mono text-xl font-bold border-r-4 border-black hover:bg-black hover:text-white transition-all duration-300">
                ABOUT
            </a>
        </nav>

        <!-- Right Side / Contact / Mobile Trigger -->
        <div class="flex">
            <a href="/#contact" class="hidden md:flex items-center px-8 font-mono font-black text-xl bg-black text-white hover:bg-red-600 transition-colors duration-300 border-l-4 border-black">
                CONTACT
            </a>
            
            <!-- Mobile Menu Trigger -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden px-6 flex items-center justify-center border-l-4 border-black bg-white hover:bg-black hover:text-white transition-colors duration-300">
                <div class="space-y-2">
                    <div class="w-8 h-1 bg-current transition-all" :class="mobileMenuOpen ? 'rotate-45 translate-y-3' : ''"></div>
                    <div class="w-8 h-1 bg-current transition-opacity" :class="mobileMenuOpen ? 'opacity-0' : 'opacity-100'"></div>
                    <div class="w-8 h-1 bg-current transition-all" :class="mobileMenuOpen ? '-rotate-45 -translate-y-3' : ''"></div>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-0 top-20 md:top-24 bg-white z-40 lg:hidden flex flex-col border-t-4 border-black"
    >
        <nav class="flex flex-col h-full divide-y-4 divide-black">
            <a href="/jewels" @click="mobileMenuOpen = false" class="flex-1 flex items-center px-10 font-mono text-5xl font-black uppercase hover:bg-black hover:text-white transition-colors">BIJOUX</a>
            <a href="/collections" @click="mobileMenuOpen = false" class="flex-1 flex items-center px-10 font-mono text-5xl font-black uppercase hover:bg-black hover:text-white transition-colors">COLLECTIONS</a>
            <a href="/news" @click="mobileMenuOpen = false" class="flex-1 flex items-center px-10 font-mono text-5xl font-black uppercase hover:bg-black hover:text-white transition-colors">NEWS</a>
            <a href="/#about" @click="mobileMenuOpen = false" class="flex-1 flex items-center px-10 font-mono text-5xl font-black uppercase hover:bg-black hover:text-white transition-colors">ABOUT</a>
            <a href="/#contact" @click="mobileMenuOpen = false" class="flex-1 flex items-center px-10 font-mono text-5xl font-black uppercase bg-black text-white hover:bg-red-600 transition-colors">CONTACT</a>
        </nav>
    </div>

    <!-- Background Decoration -->
    <div class="absolute top-0 right-0 h-full w-2 bg-red-600 hidden lg:block"></div>
</header>

<!-- Spacer -->
<div class="h-20 md:h-24"></div>
