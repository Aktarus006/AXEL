<?php

use function Livewire\Volt\{state};
//
?>

<div>
    <nav class="fixed top-0 left-0 z-50 w-full bg-white">
        <div class="flex flex-col w-full">
            <!-- Main Header -->
            <div class="flex w-full border-b-2 border-black">
                <!-- Logo -->
                <a href="/" class="w-1/2 px-6 py-4 transition-colors duration-200 border-r-2 border-black hover:bg-black hover:text-white">
                    <span class="font-mono text-5xl tracking-tighter">AXEL JEWELRY</span>
                </a>
                
                <!-- Primary Navigation -->
                <div class="flex w-1/2">
                    <a href="/jewels" class="flex items-center justify-center flex-1 font-mono text-xl transition-colors duration-200 border-r-2 border-black hover:bg-black hover:text-white">
                        BIJOUX
                    </a>
                    <a href="/collections" class="flex items-center justify-center flex-1 font-mono text-xl transition-colors duration-200 border-r-2 border-black hover:bg-black hover:text-white">
                        COLLECTIONS
                    </a>
                    <a href="/news" class="flex items-center justify-center flex-1 font-mono text-xl transition-colors duration-200 hover:bg-black hover:text-white">
                        BLOG
                    </a>
                </div>
            </div>

            <!-- Secondary Navigation -->
            <div class="flex w-full border-b-2 border-black">
                <div class="flex w-1/2">
                    <a href="{{ url('/') }}#about" class="flex-1 px-4 py-2 font-mono transition-colors duration-200 border-r-2 border-black hover:bg-black hover:text-white">
                        ABOUT
                    </a>
                    <a href="#contact" class="flex-1 px-4 py-2 font-mono transition-colors duration-200 border-r-2 border-black hover:bg-black hover:text-white">
                        CONTACT
                    </a>
                </div>
                <div class="flex items-center justify-end w-1/2 px-4 font-mono">
                    <span class="text-sm tracking-widest">BRUTALIST JEWELRY DESIGN</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Spacer to prevent content from hiding under fixed header -->
    <div class="h-32"></div>
</div>
