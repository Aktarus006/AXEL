<?php

use function Livewire\Volt\state;
use App\Models\Slide;

state([
    'slides' => Slide::with('media')->orderBy('order')->get(),
]);
?>

<div class="relative w-full bg-white border-8 border-black homepage-slider-section min-h-[28rem] md:min-h-[32rem] flex flex-col justify-center">
    <div class="w-full flex items-center justify-center flex-1">
        <button
            @click="current = (current - 1 + slides.length) % slides.length"
            class="bg-black text-white border-4 border-white text-3xl font-black w-14 h-14 flex items-center justify-center hover:bg-red-700 transition"
        >&#8592;</button>
        <div class="w-full max-w-4xl flex flex-col items-center justify-center border-x-8 border-black min-h-[22rem] md:min-h-[28rem]">
            @if($slides->isEmpty())
                <div class="py-10 text-center text-black">No slides found</div>
            @else
                @foreach($slides as $idx => $slide)
                    <div
                        x-show="current === {{ $idx }}"
                        class="flex flex-col items-center justify-center w-full h-full"
                    >
                        <img
                            src="{{ $slide->getFirstMediaUrl('slides', 'desktop') ?: $slide->getFirstMediaUrl('slides') }}"
                            srcset="{{ $slide->getFirstMediaUrl('slides', 'mobile') }} 600w, {{ $slide->getFirstMediaUrl('slides', 'desktop') }} 1200w"
                            sizes="(max-width: 600px) 600px, 1200px"
                            alt="{{ $slide->title }}"
                            class="w-full h-64 md:h-96 object-cover border-b-8 border-black"
                            loading="lazy"
                        >
                        <div class="w-full px-6 py-4 border-t-8 border-black bg-white text-center">
                            <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-2">{{ $slide->title }}</h2>
                            <p class="text-lg md:text-xl font-mono text-black mb-4">{{ $slide->description }}</p>
                            @if($slide->button_url && $slide->button_text)
                                <a href="{{ $slide->button_url }}" class="inline-block bg-black text-white font-bold px-8 py-3 border-4 border-black uppercase tracking-widest hover:bg-red-700 hover:text-white transition">{{ $slide->button_text }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button
            @click="current = (current + 1) % slides.length"
            class="bg-black text-white border-4 border-white text-3xl font-black w-14 h-14 flex items-center justify-center hover:bg-red-700 transition"
        >&#8594;</button>
    </div>
    <!-- Dots -->
    <div class="flex justify-center mt-4 space-x-2">
        @foreach($slides as $idx => $slide)
            <button
                @click="current = {{ $idx }}"
                :class="{'bg-black': current === {{ $idx }}, 'bg-gray-300': current !== {{ $idx }}}"
                class="w-4 h-4 border-2 border-black"
            ></button>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('homepageSlider', () => ({
            current: 0,
            slides: @json($slides->values()),
        }));
    });
</script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
