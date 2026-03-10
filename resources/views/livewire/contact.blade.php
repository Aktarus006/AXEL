<?php
use function Livewire\Volt\{state};
?>

<div class="w-full border-t-8 border-white bg-black">
    <div class="flex flex-col lg:flex-row w-full">
        <!-- Social Links Section -->
        <div class="w-full lg:w-1/3 flex border-b-8 lg:border-b-0 lg:border-r-8 border-white">
            <a href="https://www.facebook.com/axelenglebertbijoutier" target="_blank" rel="noopener noreferrer" class="relative flex flex-col items-center justify-center flex-1 py-16 font-mono text-3xl text-black uppercase transition-all duration-300 bg-white border-r-4 border-white group hover:bg-red-600 hover:text-white">
                <div class="absolute inset-2 border-2 border-black group-hover:border-white"></div>
                <svg class="z-10 w-24 h-24 mb-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                </svg>
                <span class="relative z-10 font-black tracking-widest">Facebook</span>
            </a>
            <a href="https://www.instagram.com/axel.englebert/" target="_blank" rel="noopener noreferrer" class="relative flex flex-col items-center justify-center flex-1 py-16 font-mono text-3xl text-black uppercase transition-all duration-300 bg-white group hover:bg-red-600 hover:text-white">
                <div class="absolute inset-2 border-2 border-black group-hover:border-white"></div>
                <svg class="z-10 w-24 h-24 mb-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4.162 4.162 0 1 1 0-8.324 4.162 4.162 0 0 1 0 8.324zM18.406 3.506a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"/>
                </svg>
                <span class="relative z-10 font-black tracking-widest">Instagram</span>
            </a>
        </div>

        <!-- Contact Form Section -->
        <div class="w-full lg:w-2/3 bg-black flex flex-col md:flex-row">
            <!-- Vertical Text (Hidden on small screens, shown on md+) -->
            <div class="hidden md:flex bg-black border-r-8 border-white [writing-mode:vertical-rl] items-center justify-center px-12 font-mono text-6xl font-black tracking-tighter text-white uppercase hover:text-red-600 transition-colors duration-300">
                GET_IN_TOUCH
            </div>
            
            <!-- Mobile Title (Shown only on small screens) -->
            <div class="md:hidden p-8 border-b-4 border-white">
                <h2 class="font-mono text-5xl font-black text-white uppercase tracking-tighter">GET_IN_TOUCH</h2>
            </div>

            <!-- Form Container -->
            <div class="w-full p-8 md:p-16">
                <livewire:contactform />
            </div>
        </div>
    </div>
</div>
