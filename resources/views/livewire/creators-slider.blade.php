<?php

use function Livewire\Volt\{state};
use App\Models\Creator;
use App\Enums\Status;

state([
    'creators' => Creator::where('online', Status::ONLINE)
        ->with(['media', 'collections'])
        ->get()
]);
?>

<section class="bg-black pt-20 border-t-8 border-white overflow-hidden">
    <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>

    <!-- Header -->
    <div class="px-8 mb-12 flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <h2 class="font-mono text-5xl md:text-8xl font-black text-white leading-none uppercase tracking-tighter">
                CREATORS<span class="text-red-600">.</span>
            </h2>
            <p class="font-mono text-xl text-white mt-4 uppercase">Direct from our studio to your hands.</p>
        </div>
        <div class="hidden md:block w-32 h-2 bg-red-600"></div>
    </div>

    <!-- Horizontal Scroll Wrapper -->
    <div class="relative w-full overflow-hidden border-t-8 border-white" x-data="{ }">
        <div 
            x-ref="slider"
            class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide space-x-0"
            style="scrollbar-width: none; -ms-overflow-style: none; scroll-behavior: smooth;"
        >
            @foreach($creators as $creator)
                <div class="flex-none w-full md:w-[45vw] lg:w-[30vw] snap-start border-r-4 border-white group relative">
                    <a href="/creators/{{ $creator->id }}" class="block overflow-hidden relative aspect-[4/5] bg-neutral-900">
                        <!-- Optimized Image with Srcset -->
                        @if($creator->getFirstMediaUrl('creators/profile'))
                            <img
                                src="{{ $creator->getFirstMediaUrl('creators/profile', 'medium') }}"
                                srcset="{{ $creator->getFirstMediaUrl('creators/profile', 'small') }} 400w, {{ $creator->getFirstMediaUrl('creators/profile', 'medium') }} 800w"
                                sizes="(max-width: 768px) 100vw, (max-width: 1024px) 45vw, 30vw"
                                alt="Portrait de {{ $creator->name }}, créateur chez AXEL"
                                width="800"
                                height="1000"
                                class="w-full h-full object-cover transition-all duration-700 grayscale group-hover:grayscale-0 group-hover:scale-110"
                                loading="lazy"
                            >
                            @if($creator->getFirstMediaUrl('creators/profile_hover'))
                                <img
                                    src="{{ $creator->getFirstMediaUrl('creators/profile_hover', 'medium') }}"
                                    srcset="{{ $creator->getFirstMediaUrl('creators/profile_hover', 'small') }} 400w, {{ $creator->getFirstMediaUrl('creators/profile_hover', 'medium') }} 800w"
                                    sizes="(max-width: 768px) 100vw, (max-width: 1024px) 45vw, 30vw"
                                    alt="Vue alternative de {{ $creator->name }}"
                                    width="800"
                                    height="1000"
                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0 group-hover:opacity-100"
                                    loading="lazy"
                                >
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center font-mono text-white text-4xl">★</div>
                        @endif

                        <!-- Content Overlay -->
                        <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black via-black/90 to-transparent translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                            <span class="font-mono text-sm text-red-600 font-bold uppercase tracking-widest">{{ $creator->job_title ?? 'Artist' }}</span>
                            <h3 class="font-mono text-4xl font-black text-white uppercase mt-2 group-hover:text-red-600 transition-colors">{{ $creator->name }}</h3>
                        </div>
                    </a>
                    
                    <!-- Hover Detail (Desktop) -->
                    <div class="absolute top-8 left-8 p-4 bg-white border-4 border-black font-mono text-black opacity-0 group-hover:opacity-100 transition-all -rotate-12 group-hover:rotate-0 translate-x-10 group-hover:translate-x-0 pointer-events-none z-20 shadow-[8px_8px_0px_0px_rgba(220,38,38,1)]">
                        <span class="font-black uppercase">View Profile_</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Custom Navigation (Refined Borders) -->
        <div class="flex border-t-8 border-white bg-black h-32 items-stretch">
            <div class="flex-1 px-8 flex items-center font-mono text-white/70 text-sm uppercase tracking-widest text-center md:text-left border-r-8 border-white">
                SCROLL TO DISCOVER / ARCHAEOLOGY OF CRAFT
            </div>
            <div class="flex items-stretch">
                <button 
                    @click="$refs.slider.scrollBy({left: -400, behavior: 'smooth'})" 
                    class="px-10 border-r-8 border-white hover:bg-red-600 focus:bg-red-600 focus:outline-none transition-colors group"
                    aria-label="Créateur précédent"
                >
                    <span class="font-mono text-4xl text-white font-black group-hover:scale-125 inline-block transition-transform" aria-hidden="true">←</span>
                </button>
                <button 
                    @click="$refs.slider.scrollBy({left: 400, behavior: 'smooth'})" 
                    class="px-10 hover:bg-red-600 focus:bg-red-600 focus:outline-none transition-colors group"
                    aria-label="Créateur suivant"
                >
                    <span class="font-mono text-4xl text-white font-black group-hover:scale-125 inline-block transition-transform" aria-hidden="true">→</span>
                </button>
            </div>
        </div>
    </div>
</section>
