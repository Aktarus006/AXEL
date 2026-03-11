<?php

use function Livewire\Volt\{state};
use App\Models\Slide;

state([
    'slides' => Slide::with('media')->where('is_active', true)->orderBy('order')->get(),
]);

// Helper to determine if a color is light
if (!function_exists('isLight')) {
    function isLight($color) {
        $color = ltrim($color, '#');
        if (strlen($color) === 3) {
            $r = hexdec(str_repeat(substr($color, 0, 1), 2));
            $g = hexdec(str_repeat(substr($color, 1, 1), 2));
            $b = hexdec(str_repeat(substr($color, 2, 1), 2));
        } else {
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
        }
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        return $luminance > 0.5;
    }
}
?>

<div 
    x-data="{ 
        current: 0, 
        slides: @js($slides->count()), 
        autoplay: null,
        init() {
            if (this.slides > 1) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.autoplay = setInterval(() => {
                this.current = (this.current + 1) % this.slides;
            }, 6000);
        },
        stopAutoplay() {
            if (this.autoplay) clearInterval(this.autoplay);
        }
    }" 
    @mouseenter="stopAutoplay" 
    @mouseleave="startAutoplay"
    class="relative w-full h-[80vh] md:h-[90vh] bg-black overflow-hidden"
>
    @if($slides->isNotEmpty())
        @foreach($slides as $idx => $slide)
            <section 
                x-show="current === {{ $idx }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-110"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-700"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="absolute inset-0 w-full h-full flex flex-col md:flex-row"
            >
                <!-- Background Image (Mobile) / Right Side (Desktop) -->
                <div class="absolute inset-0 md:relative md:w-2/3 h-full overflow-hidden">
                    <img 
                        src="{{ $slide->getFirstMediaUrl('slides', 'desktop') ?: $slide->getFirstMediaUrl('slides') }}"
                        alt="{{ $slide->title }}"
                        class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-1000"
                    >
                    <div class="absolute inset-0 bg-black/40 md:hidden"></div>
                </div>

                <!-- Text Content -->
                <div class="relative z-10 w-full md:w-1/3 h-full flex flex-col justify-center p-8 md:p-12 bg-black md:bg-white text-white md:text-black border-l-0 md:border-l-8 border-white md:border-black">
                    <div class="space-y-6">
                        <div class="font-mono text-sm uppercase tracking-[0.5em] mb-4 opacity-70">FEATURED_PROJECT_{{ $idx + 1 }}</div>
                        <h2 class="font-mono text-5xl md:text-7xl font-black leading-none uppercase tracking-tighter">
                            {!! $slide->title !!}
                        </h2>
                        <div class="w-24 h-4 bg-red-600"></div>
                        
                        @if($slide->button_url && $slide->button_text)
                            <a href="{{ $slide->button_url }}" 
                               class="inline-block mt-8 px-10 py-5 bg-white md:bg-black text-black md:text-white border-4 border-black md:border-white font-mono font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all duration-300 transform -skew-x-12 hover:skew-x-0">
                                {{ $slide->button_text }}
                            </a>
                        @endif
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    <!-- Brutalist Navigation -->
    @if($slides->count() > 1)
        <div class="absolute bottom-12 left-8 md:left-auto md:right-1/3 md:translate-x-1/2 flex items-center gap-4 z-20">
            <button
                @click="current = (current - 1 + slides) % slides"
                class="w-16 h-16 bg-white border-4 border-black flex items-center justify-center hover:bg-red-600 transition-colors group"
            >
                <span class="font-mono text-3xl font-black group-hover:text-white">←</span>
            </button>
            <div class="font-mono text-2xl font-black text-white md:text-black bg-black md:bg-white px-4 py-2 border-4 border-white md:border-black">
                <span x-text="current + 1"></span>/<span x-text="slides"></span>
            </div>
            <button
                @click="current = (current + 1) % slides"
                class="w-16 h-16 bg-white border-4 border-black flex items-center justify-center hover:bg-red-600 transition-colors group"
            >
                <span class="font-mono text-3xl font-black group-hover:text-white">→</span>
            </button>
        </div>
    @endif

    <!-- Progress Bar -->
    <div class="absolute bottom-0 left-0 h-4 bg-red-600 z-30 transition-all duration-[6000ms] linear"
         :style="'width: ' + ((current + 1) / slides * 100) + '%'"></div>
</div>
