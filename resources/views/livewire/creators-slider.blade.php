<?php

use function Livewire\Volt\{state};

state([
    'creators' => [
        [
            'name' => 'Sarah Miller',
            'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330',
            'image2' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb',
            'specialty' => 'CONTEMPORARY'
        ],
        [
            'name' => 'James Chen',
            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d',
            'image2' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e',
            'specialty' => 'METAL ART'
        ],
        [
            'name' => 'Elena Rodriguez',
            'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80',
            'image2' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2',
            'specialty' => 'GEMSTONE'
        ],
        [
            'name' => 'Marcus Black',
            'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e',
            'image2' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d',
            'specialty' => 'AVANT-GARDE'
        ],
        [
            'name' => 'Yuki Tanaka',
            'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb',
            'image2' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330',
            'specialty' => 'MINIMALIST'
        ],
        [
            'name' => 'Alex Rivera',
            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2',
            'image2' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80',
            'specialty' => 'PUNK STYLE'
        ],
        [
            'name' => 'Luna Park',
            'image' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04',
            'image2' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9',
            'specialty' => 'GOTHIC'
        ],
        [
            'name' => 'Kai Wong',
            'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9',
            'image2' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04',
            'specialty' => 'STREET ART'
        ]
    ]
]);
?>

<div 
    x-data="{ 
        position: 0,
        maxScroll: 0,
        canScrollLeft: false,
        canScrollRight: true,
        isScrolling: false,
        autoScrollInterval: null,
        init() {
            this.$nextTick(() => {
                this.maxScroll = this.$refs.scrollContent.scrollWidth - this.$refs.container.offsetWidth;
                this.updateScrollability();
            });
        },
        updateScrollability() {
            this.canScrollLeft = this.position > 0;
            this.canScrollRight = this.position < this.maxScroll;
        },
        smoothScroll(target) {
            if (this.isScrolling) return;
            this.isScrolling = true;
            
            const start = this.position;
            const distance = target - start;
            const duration = 300;
            let startTime = null;

            const animation = currentTime => {
                if (!startTime) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const progress = Math.min(timeElapsed / duration, 1);
                
                const easing = t => t<.5 ? 2*t*t : -1+(4-2*t)*t;
                
                this.position = start + (distance * easing(progress));
                this.$refs.scrollContent.style.transform = `translate3d(-${this.position}px, 0, 0)`;
                
                if (progress < 1) {
                    requestAnimationFrame(animation);
                } else {
                    this.position = target;
                    this.updateScrollability();
                    this.isScrolling = false;
                }
            };
            
            requestAnimationFrame(animation);
        },
        startAutoScroll() {
            if (this.autoScrollInterval) return;
            this.autoScrollInterval = setInterval(() => {
                const target = this.position >= this.maxScroll ? 0 : Math.min(this.maxScroll, this.position + 200);
                this.smoothScroll(target);
            }, 2000);
        },
        stopAutoScroll() {
            if (this.autoScrollInterval) {
                clearInterval(this.autoScrollInterval);
                this.autoScrollInterval = null;
            }
        },
        scrollLeft() {
            this.stopAutoScroll();
            const target = this.position === 0 ? this.maxScroll : Math.max(0, this.position - 400);
            this.smoothScroll(target);
        },
        scrollRight() {
            this.stopAutoScroll();
            const target = this.position >= this.maxScroll ? 0 : Math.min(this.maxScroll, this.position + 400);
            this.smoothScroll(target);
        }
    }" 
    class="creators-section relative w-full bg-black"
    @mouseenter="startAutoScroll"
    @mouseleave="stopAutoScroll"
>
    <!-- Title -->
    <div class="absolute top-0 left-0 w-full border-t-8 border-b-8 border-white bg-black py-4 z-10">
        <h2 class="text-6xl font-mono text-white text-center transform -skew-x-12">NOS COLLABORATEURS</h2>
    </div>

    <!-- Navigation Arrows -->
    <div 
        class="absolute left-8 top-1/2 -translate-y-1/2 z-20 transition-opacity duration-300"
        :class="{ 'opacity-0 pointer-events-none': !canScrollLeft }"
    >
        <button 
            @click="scrollLeft"
            class="text-white text-8xl transform hover:scale-150 transition-all duration-300 cursor-pointer relative group"
        >
            <span class="absolute inset-0 border-8 border-white transform scale-90 transition-transform duration-300 group-hover:scale-100"></span>
            <span class="absolute inset-0 border-4 border-white transform scale-[0.85] transition-transform duration-300 group-hover:scale-95"></span>
            <span class="relative z-10 block px-4 py-2">←</span>
        </button>
    </div>
    <div 
        class="absolute right-8 top-1/2 -translate-y-1/2 z-20 transition-opacity duration-300"
        :class="{ 'opacity-0 pointer-events-none': !canScrollRight }"
    >
        <button 
            @click="scrollRight"
            class="text-white text-8xl transform hover:scale-150 transition-all duration-300 cursor-pointer relative group"
        >
            <span class="absolute inset-0 border-8 border-white transform scale-90 transition-transform duration-300 group-hover:scale-100"></span>
            <span class="absolute inset-0 border-4 border-white transform scale-[0.85] transition-transform duration-300 group-hover:scale-95"></span>
            <span class="relative z-10 block px-4 py-2">→</span>
        </button>
    </div>

    <!-- Creators Row -->
    <div 
        x-ref="container"
        class="relative w-full h-[400px] bg-black border-t-4 border-white overflow-hidden"
    >
        <div 
            x-ref="scrollContent"
            class="absolute left-0 top-0 h-full flex items-center gap-4 px-8 transition-transform duration-300 will-change-transform"
            style="transform: translate3d(0, 0, 0)"
        >
            @foreach($creators as $creator)
            <div class="group relative flex-none w-80">
                <div class="relative border-8 border-white hover:border-red-600 transition-colors duration-300">
                    <div class="relative w-full aspect-square overflow-hidden">
                        <img 
                            src="{{ $creator['image'] }}" 
                            alt="{{ $creator['name'] }}"
                            class="w-full h-full object-cover grayscale transition-all duration-500 group-hover:grayscale-0 group-hover:scale-110"
                        >
                        <img 
                            src="{{ $creator['image2'] }}" 
                            alt="{{ $creator['name'] }}"
                            class="absolute top-0 left-0 w-full h-full object-cover opacity-0 grayscale-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                        <div class="absolute inset-0 border-4 border-black opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <div class="absolute -bottom-16 left-0 right-0 bg-white border-8 border-black p-4 transform group-hover:-translate-y-16 transition-transform duration-300">
                        <h3 class="font-mono text-xl font-bold text-black">{{ strtoupper($creator['name']) }}</h3>
                        <p class="font-mono text-sm text-black">{{ $creator['specialty'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>