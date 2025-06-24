<?php

use function Livewire\Volt\{state};
use App\Models\Creator;

state([
    'creators' => Creator::where('online', true)
        ->with(['media', 'collections'])
        ->get()
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
    class="relative w-full bg-black creators-section"
    @mouseenter="startAutoScroll"
    @mouseleave="stopAutoScroll"
>
    <!-- Title -->
    <div class="absolute top-0 left-0 z-10 w-full py-4 bg-black border-t-8 border-b-8 border-white">
        <h2 class="font-mono text-6xl text-center text-white transform -skew-x-12">NOS COLLABORATEURS</h2>
    </div>

    <!-- Navigation Arrows -->
    <div
        class="absolute z-20 transition-opacity duration-300 -translate-y-1/2 left-8 top-1/2"
        :class="{ 'opacity-0 pointer-events-none': !canScrollLeft }"
    >
        <button
            @click="scrollLeft"
            class="relative text-white transition-all duration-300 transform cursor-pointer text-8xl hover:scale-150 group"
        >
            <span class="absolute inset-0 transition-transform duration-300 transform scale-90 border-8 border-white group-hover:scale-100"></span>
            <span class="absolute inset-0 border-4 border-white transform scale-[0.85] transition-transform duration-300 group-hover:scale-95"></span>
            <span class="relative z-10 block px-4 py-2">←</span>
        </button>
    </div>
    <div
        class="absolute z-20 transition-opacity duration-300 -translate-y-1/2 right-8 top-1/2"
        :class="{ 'opacity-0 pointer-events-none': !canScrollRight }"
    >
        <button
            @click="scrollRight"
            class="relative text-white transition-all duration-300 transform cursor-pointer text-8xl hover:scale-150 group"
        >
            <span class="absolute inset-0 transition-transform duration-300 transform scale-90 border-8 border-white group-hover:scale-100"></span>
            <span class="absolute inset-0 border-4 border-white transform scale-[0.85] transition-transform duration-300 group-hover:scale-95"></span>
            <span class="relative z-10 block px-4 py-2">→</span>
        </button>
    </div>

    <!-- Creators Row -->
    <div
        x-ref="container"
        class="relative w-full h-[650px] bg-black border-t-4 border-white overflow-hidden flex items-center justify-center"
    >
        @if($creators->isEmpty())
            <div class="py-10 text-center text-white">No creators found</div>
        @endif

        <div
            x-ref="scrollContent"
            class="absolute inset-0 flex items-center gap-8 px-8 transition-transform duration-300 will-change-transform"
            style="transform: translate3d(0, 0, 0)"
        >
            @foreach($creators as $creator)
            <div class="relative flex-none group w-[400px]">
                <a href="/creators/{{ $creator->id }}" class="block">
                    <div class="relative transition-colors duration-300 border-8 border-white hover:border-red-700">
                        <div class="relative w-full overflow-hidden aspect-square">
                            @if($creator->getFirstMediaUrl('creators/profile'))
                            <img
                                srcset="{{ $creator->getFirstMediaUrl('creators/profile', 'small') }} 480w,
                                        {{ $creator->getFirstMediaUrl('creators/profile', 'medium') }} 768w,
                                        {{ $creator->getFirstMediaUrl('creators/profile', 'large') }} 1280w,
                                        {{ $creator->getFirstMediaUrl('creators/profile', 'xl') }} 1920w,
                                        {{ $creator->getFirstMediaUrl('creators/profile', 'hd') }} 2560w"
                                sizes="(max-width: 480px) 480px,
                                       (max-width: 768px) 768px,
                                       (max-width: 1280px) 1280px,
                                       (max-width: 1920px) 1920px,
                                       2560px"
                                src="{{ $creator->getFirstMediaUrl('creators/profile', 'medium') }}"
                                alt="{{ $creator->name }}"
                                class="object-cover w-full h-full transition-all duration-500 grayscale group-hover:grayscale-0 group-hover:scale-110"
                                loading="lazy"
                            >
                            @if($creator->getFirstMediaUrl('creators/profile_hover'))
                            <img
                                srcset="{{ $creator->getFirstMediaUrl('creators/profile_hover', 'small') }} 480w,
                                        {{ $creator->getFirstMediaUrl('creators/profile_hover', 'medium') }} 768w,
                                        {{ $creator->getFirstMediaUrl('creators/profile_hover', 'large') }} 1280w,
                                        {{ $creator->getFirstMediaUrl('creators/profile_hover', 'xl') }} 1920w,
                                        {{ $creator->getFirstMediaUrl('creators/profile_hover', 'hd') }} 2560w"
                                sizes="(max-width: 480px) 480px,
                                       (max-width: 768px) 768px,
                                       (max-width: 1280px) 1280px,
                                       (max-width: 1920px) 1920px,
                                       2560px"
                                src="{{ $creator->getFirstMediaUrl('creators/profile_hover', 'medium') }}"
                                alt="{{ $creator->name }}"
                                class="absolute top-0 left-0 object-cover w-full h-full transition-opacity duration-500 opacity-0 grayscale-0 group-hover:opacity-100"
                                loading="lazy"
                            >
                            @endif
                            @else
                            <div class="flex items-center justify-center w-full h-full text-white bg-gray-800">
                                No image available for {{ $creator->name }}
                            </div>
                            @endif
                            <div class="absolute inset-0 transition-opacity duration-300 border-4 border-black opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <div class="p-4 bg-white border-8 border-black">
                            <h3 class="font-mono text-xl font-bold text-black">{{ strtoupper($creator->name) }}</h3>
                            <p class="font-mono text-sm text-black">{{ strtoupper($creator->job_title) ?? 'ARTISTE' }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
