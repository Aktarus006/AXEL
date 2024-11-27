<?php

use function Livewire\Volt\{state};
//
?>

<nav class="fixed top-0 left-0 w-full bg-white z-50">
    <div class="w-full flex flex-col">
        <!-- Main Header -->
        <div class="w-full flex border-b-2 border-black">
            <!-- Logo -->
            <a href="/" class="w-1/2 py-4 px-6 hover:bg-black hover:text-white border-r-2 border-black transition-colors duration-200">
                <span class="font-mono text-5xl tracking-tighter">AXEL JEWELRY</span>
            </a>
            
            <!-- Primary Navigation -->
            <div class="w-1/2 flex">
                <a href="/jewels" class="flex-1 flex items-center justify-center font-mono text-xl hover:bg-black hover:text-white border-r-2 border-black transition-colors duration-200">
                    BIJOUX
                </a>
                <a href="/collections" class="flex-1 flex items-center justify-center font-mono text-xl hover:bg-black hover:text-white border-r-2 border-black transition-colors duration-200">
                    COLLECTIONS
                </a>
                <a href="/news" class="flex-1 flex items-center justify-center font-mono text-xl hover:bg-black hover:text-white transition-colors duration-200">
                    BLOG
                </a>
            </div>
        </div>

        <!-- Secondary Navigation -->
        <div class="w-full flex border-b-2 border-black">
            <div class="w-1/2 flex">
                <a href="{{ url('/') }}#about" class="flex-1 py-2 px-4 font-mono hover:bg-black hover:text-white border-r-2 border-black transition-colors duration-200">
                    ABOUT
                </a>
                <a href="#contact" class="flex-1 py-2 px-4 font-mono hover:bg-black hover:text-white border-r-2 border-black transition-colors duration-200">
                    CONTACT
                </a>
            </div>
            <div class="w-1/2 flex justify-end items-center px-4 font-mono">
                <span class="text-sm tracking-widest">BRUTALIST JEWELRY DESIGN</span>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from hiding under fixed header -->
<div class="h-32"></div>
