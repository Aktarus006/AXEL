<?php

use function Livewire\Volt\{state, mount};
use App\Models\Step;

// Initialize state variables
state([
    "steps" => [],
]);

// Initial mounting of data
mount(function () {
    $this->steps = Step::with("media")->orderBy("position")->get();
});
?>

<div class="w-full bg-black border-t-8 border-white">
    <!-- Balanced Grid for 3 columns on LG+ -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 w-full border-l-8 border-white">
        @foreach ($steps as $index => $step)
            @php
                // Get the first image associated with this step
                $media = $step->getMedia('steps/images');
                $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
            @endphp

            <div @class([
                "group relative flex flex-col min-h-[450px] border-b-8 border-r-8 border-white transition-colors duration-500",
            ])>
                <!-- Position Number -->
                <div class="absolute top-0 right-0 bg-white text-black text-6xl font-black p-6 z-20 border-l-8 border-b-8 border-black group-hover:bg-red-700 group-hover:text-white transition-colors duration-300">
                    {{ str_pad($step->position, 2, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Image Container -->
                <div class="relative h-72 w-full overflow-hidden bg-white border-b-8 border-white group-hover:border-red-700 transition-colors duration-300">
                    @if($firstMediaUrl)
                        <img 
                            src="{{ $firstMediaUrl }}" 
                            alt="Étape {{ $step->position }} du processus AXEL : {{ $step->title }}" 
                            loading="lazy"
                            width="600"
                            height="450"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center font-mono text-black font-black text-4xl opacity-10">AXEL_STUDIO</div>
                    @endif
                    <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/20 transition-all duration-500"></div>
                </div>

                <!-- Content -->
                <div class="p-10 flex-1 bg-black text-white group-hover:bg-white group-hover:text-black transition-all duration-500">
                    <div class="font-mono text-xs mb-4 text-white/70 group-hover:text-black/70 uppercase tracking-[0.3em]">Phase_D'exécution_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="font-mono text-4xl font-black uppercase mb-8 leading-none tracking-tighter">{{ $step->title }}</h3>
                    <div class="font-mono text-sm leading-relaxed text-white/80 group-hover:text-black/80">
                        {!! $step->description !!}
                    </div>
                </div>
                
                <!-- Bottom Decorative Bar -->
                <div class="h-4 bg-white group-hover:bg-red-700 transition-colors duration-500"></div>
            </div>
        @endforeach
        
        <!-- Filler box if the grid is not full (to maintain border structure) -->
        @if($steps->count() % 3 != 0)
            @php $fillers = 3 - ($steps->count() % 3); @endphp
            @for($i = 0; $i < $fillers; $i++)
                <div class="hidden lg:flex flex-col border-b-8 border-r-8 border-white bg-black items-center justify-center p-12">
                    <div class="w-20 h-20 border-4 border-white/20 rotate-45 flex items-center justify-center">
                        <div class="w-2 h-2 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
            @endfor
        @endif
    </div>
</div>
