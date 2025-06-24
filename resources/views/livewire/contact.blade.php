<?php
use function Livewire\Volt\{state};
//
?>

<div class="w-full border-t-4 border-white">
    <!-- Social Links -->
    <div class="flex w-full">
        <!-- Social Links Column -->
        <div class="flex w-1/4 border-r-4 border-white">
            <a href="https://www.facebook.com/axelenglebertbijoutier" target="_blank" rel="noopener noreferrer" data-no-barba @click="window.open($el.href, '_blank', 'noopener,noreferrer'); return false;" class="relative flex flex-col items-center flex-1 pt-8 font-mono text-3xl text-black uppercase transition-colors duration-300 bg-white border-8 border-r-2 border-white group hover:bg-black hover:text-white">
                <!-- Facebook Logo -->
                <div class="absolute border-4 border-black opacity-100 inset-1"></div>
                <svg class="z-10 w-16 h-16 mb-8 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                </svg>
                <span class="relative z-10 [writing-mode:vertical-rl]">Facebook</span>
                <span class="absolute inset-[8px] border-2 border-black opacity-0 transform scale-90 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 group-hover:border-white"></span>
                <span class="absolute inset-[16px] border border-black opacity-0 transform scale-90 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 group-hover:border-white"></span>
            </a>
            <a href="https://www.instagram.com/axel.englebert/" target="_blank" rel="noopener noreferrer" data-no-barba @click="window.open($el.href, '_blank', 'noopener,noreferrer'); return false;" class="relative flex flex-col items-center flex-1 pt-8 font-mono text-3xl text-black uppercase transition-colors duration-300 bg-white border-8 border-white group hover:bg-black hover:text-white">
                <!-- Instagram Logo -->
                <div class="absolute border-4 border-black opacity-100 inset-1"></div>
                <svg class="z-10 w-16 h-16 mb-8 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 0 0-10 10 10 10 0 0 0 10 10 10 10 0 0 0 10-10A10 10 0 0 0 12 2m0 2c4.41 0 8 3.59 8 8s-3.59 8-8 8-8-3.59-8-8 3.59-8 8-8M8.5 8A1.5 1.5 0 0 0 7 9.5 1.5 1.5 0 0 0 8.5 11 1.5 1.5 0 0 0 10 9.5 1.5 1.5 0 0 0 8.5 8m7 0A1.5 1.5 0 0 0 14 9.5a1.5 1.5 0 0 0 1.5 1.5 1.5 1.5 0 0 0 1.5-1.5A1.5 1.5 0 0 0 15.5 8M12 13a4 4 0 0 0-4 4 4 4 0 0 0 4 4 4 4 0 0 0 4-4 4 4 0 0 0-4-4"/>
                </svg>
                <span class="relative z-10 [writing-mode:vertical-rl]">Instagram</span>
                <span class="absolute inset-[8px] border-2 border-black opacity-0 transform scale-90 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 group-hover:border-white"></span>
                <span class="absolute inset-[16px] border border-black opacity-0 transform scale-90 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100 group-hover:border-white"></span>
            </a>
        </div>

        <!-- Separator -->
        <div class="flex items-center justify-center w-12 bg-black border-r-4 border-white">
            <div class="flex flex-col items-center justify-center h-full gap-4 py-8">
                <div class="w-px bg-white h-1/3"></div>
                <div class="w-1.5 h-24 bg-white"></div>
                <div class="w-px bg-white h-1/3"></div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="w-[calc(75%-3rem)] bg-black text-white flex">
            <!-- Vertical Text -->
            <div class="bg-black border-r-4 border-white [writing-mode:vertical-rl] flex items-center justify-center px-8 font-mono text-4xl tracking-widest">
                CONTACT
            </div>

            <!-- Form Container -->
            <div class="w-full p-8">
                <livewire:contactform />
            </div>
        </div>
    </div>
</div>
