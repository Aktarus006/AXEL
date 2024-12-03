<?php

use function Livewire\Volt\{state};

//

?>

<div class="mt-0">
    @volt
    <footer class="w-full bg-black border-t-4 border-white hover:border-red-600 transition-colors duration-300">
        <div 
            x-data="{ isHovered: false }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
            class="flex items-center justify-center h-12 relative overflow-hidden"
        >
            <!-- Static text -->
            <div 
                class="font-mono text-white text-lg tracking-widest transition-transform duration-500"
                :class="isHovered ? 'transform -translate-y-full opacity-0' : 'transform translate-y-0 opacity-100'"
            >
                2024 AKTARUS DESIGN
            </div>

            <!-- Animated elements -->
            <div 
                class="absolute inset-0 flex items-center justify-center"
                :class="isHovered ? 'opacity-100' : 'opacity-0'"
            >
                <div class="flex space-x-4 font-mono text-lg tracking-widest"
                    x-data="{ letters: Array.from('2024 AKTARUS DESIGN') }"
                >
                    <template x-for="(letter, index) in letters" :key="index">
                        <span 
                            class="text-white transition-all duration-500 hover:text-red-600"
                            :class="isHovered ? 'transform translate-y-0 rotate-0 scale-100' : 'transform translate-y-full rotate-180 scale-0'"
                            :style="{ 'transition-delay': (index * 50) + 'ms' }"
                            x-text="letter"
                        ></span>
                    </template>
                </div>
            </div>

            <!-- Background animation -->
            <div 
                class="absolute inset-0 bg-white transition-transform duration-500 origin-bottom"
                :class="isHovered ? 'transform scale-y-[0.02] opacity-100' : 'transform scale-y-0 opacity-0'"
            ></div>
        </div>
    </footer>
    @endvolt
</div>
