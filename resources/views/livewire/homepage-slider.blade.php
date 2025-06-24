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

<style>
.text-outline-black {
    text-shadow: 2px 2px 0 #000, -2px 2px 0 #000, 2px -2px 0 #000, -2px -2px 0 #000;
}
</style>

<div 
    x-data="{ 
        current: 0, 
        slides: @js($slides->count()), 
        autoplay: null,
        autoplayTimeout: null, // Track the timeout
        init() {
            if (this.slides > 1) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.stopAutoplay(); // Always clear previous interval
            this.autoplay = setInterval(() => {
                this.current = (this.current + 1) % this.slides;
            }, 5000);
        },
        stopAutoplay() {
            if (this.autoplay) {
                clearInterval(this.autoplay);
                this.autoplay = null;
            }
            if (this.autoplayTimeout) {
                clearTimeout(this.autoplayTimeout);
                this.autoplayTimeout = null;
            }
        },
        restartAutoplay() {
            this.stopAutoplay();
            this.autoplayTimeout = setTimeout(() => {
                this.startAutoplay();
            }, 3000);
        }
    }" 
    @mouseenter="stopAutoplay" 
    @mouseleave="startAutoplay"
    class="relative w-full h-[66vh] homepage-slider-section mb-0"
>
    @if($slides->isNotEmpty())
        @foreach($slides as $idx => $slide)
            <section 
                x-show="current === {{ $idx }}"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-full"
                class="flex w-full h-full absolute inset-0"
                style="background-color: {{ $slide->background_color ?? '#06b6d4' }}"
            >
                <div class="flex flex-col items-center w-1/3 px-8 text-xl justify-evenly">
                    @php
                    $bg = $slide->background_color ?? '#06b6d4';
                    $outline = isLight($bg) ? 'text-outline-black' : '';
                    $ctaColor = $slide->cta_background_color ?? '#f43f5e';
                    @endphp
                    <span class="text-7xl font-mono uppercase tracking-tight text-white text-outline-black">
                        {!! preg_replace(
                            [
                                '/<b>(.*?)<\/b>/i',
                                '/<strong>(.*?)<\/strong>/i'
                            ],
                            '<span class="px-2 rounded transition-all duration-300 hover:italic hover:skew-x-12" style="background-color: ' . $ctaColor . '; display: inline-block;">$1</span>',
                            $slide->title
                        ) !!}
                    </span>
                    @if($slide->button_url && $slide->button_text)
                        <a href="{{ $slide->button_url }}" 
                           class="p-8 text-white bg-black border-4 border-white hover:bg-white hover:text-black hover:border-black transition-all duration-300 uppercase font-bold tracking-wider">
                            {{ $slide->button_text }}
                        </a>
                    @endif
                </div>
                <div class="w-2/3 transition-all duration-500 grayscale hover:grayscale-0">
                    @if($slide->getFirstMediaUrl('slides'))
                        <img 
                            src="{{ $slide->getFirstMediaUrl('slides', 'desktop') ?: $slide->getFirstMediaUrl('slides') }}"
                            srcset="{{ $slide->getFirstMediaUrl('slides', 'mobile') }} 600w, {{ $slide->getFirstMediaUrl('slides', 'desktop') }} 1200w"
                            sizes="(max-width: 600px) 600px, 1200px"
                            alt="{{ $slide->title }}"
                            class="w-full h-full object-cover"
                            loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                        >
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                            No image available
                        </div>
                    @endif
                </div>
            </section>
        @endforeach
    @endif

    @if($slides->count() > 1)
        <!-- Navigation arrows -->
        <button
            @click="current = (current - 1 + slides) % slides; restartAutoplay()"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black text-white border-4 border-white text-3xl font-black w-16 h-16 flex items-center justify-center hover:bg-red-700 transition-all duration-300 z-10"
        >
            &#8592;
        </button>
        
        <button
            @click="current = (current + 1) % slides; restartAutoplay()"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black text-white border-4 border-white text-3xl font-black w-16 h-16 flex items-center justify-center hover:bg-red-700 transition-all duration-300 z-10"
        >
            &#8594;
        </button>

        <!-- Dots navigation -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-3 z-10">
            @foreach($slides as $idx => $slide)
                <button
                    @click="current = {{ $idx }}; restartAutoplay()"
                    :class="{'bg-white border-black': current === {{ $idx }}, 'bg-black border-white': current !== {{ $idx }}}"
                    class="w-4 h-4 border-2 transition-all duration-300 hover:scale-110"
                ></button>
            @endforeach
        </div>
    @endif
</div>

