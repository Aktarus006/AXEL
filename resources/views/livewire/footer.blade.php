<?php
use function Livewire\Volt\{state};
?>

<footer class="w-full bg-black border-t-8 border-white group">
    <div 
        x-data="{ isHovered: false }"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        class="flex flex-col md:flex-row items-center justify-between px-8 py-12 md:py-20 relative overflow-hidden"
    >
        <!-- Logo/Name -->
        <div class="z-10 mb-8 md:mb-0">
            <h3 class="font-mono text-4xl md:text-6xl font-black text-white uppercase tracking-tighter transform group-hover:-skew-x-12 transition-transform duration-500">
                AXEL_ENGLEBERT
            </h3>
            <div class="mt-2 text-white font-mono text-sm md:text-xl font-black tracking-widest opacity-60 group-hover:opacity-100 transition-opacity">
                TEL: 0499/62.51.95
            </div>
        </div>

        <!-- Copyright & Info -->
        <div class="z-10 flex flex-col items-center md:items-end text-center md:text-right space-y-4">
            <div class="font-mono text-sm md:text-xl text-white uppercase tracking-widest font-bold">
                <span class="bg-white text-black px-2 mr-2">©2026</span> HANDCRAFTED_IN_LIÈGE_BELGIUM
            </div>
            <div class="font-mono text-xs md:text-sm text-white/60 uppercase tracking-widest">
                Surgical Precision // Brutalist Aesthetics // Eternal Materials
            </div>
            <div class="pt-4 border-t-4 border-white md:w-full">
                <a href="#" class="font-mono text-white hover:text-red-600 transition-colors uppercase font-black" aria-label="Retourner en haut de la page">Back to Top ↑</a>
            </div>
        </div>

        <!-- Animated Background Decoration -->
        <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full font-mono text-[20vw] font-black text-white whitespace-nowrap animate-marquee opacity-20">
                AXEL AXEL AXEL AXEL AXEL AXEL
            </div>
        </div>
    </div>

    <!-- Bottom Strip -->
    <div class="w-full h-10 bg-white relative flex items-center overflow-hidden border-t-4 border-black">
        <div class="font-mono text-[10px] md:text-xs font-black tracking-[1em] text-black animate-marquee whitespace-nowrap py-2">
            STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_
        </div>
        <div class="absolute top-0 font-mono text-[10px] md:text-xs font-black tracking-[1em] text-black animate-marquee2 whitespace-nowrap py-2">
            STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_STAY_BRUTAL_STAY_UNIQUE_
        </div>
    </div>
</footer>
