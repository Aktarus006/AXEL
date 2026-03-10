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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full">
        @foreach ($steps as $index => $step)
            @php
                // Get the first image associated with this step
                $media = $step->getMedia('steps/images');
                $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
            @endphp

            <div @class([
                "group relative flex flex-col min-h-[400px] border-b-8 border-white md:border-r-8 transition-colors duration-500",
                "lg:border-r-0" => ($index + 1) % 4 === 0,
                "md:border-r-0" => ($index + 1) % 2 === 0,
            ])>
                <!-- Position Number -->
                <div class="absolute top-0 right-0 bg-white text-black text-6xl font-black p-6 z-20 border-l-8 border-b-8 border-black group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                    {{ str_pad($step->position, 2, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Image -->
                <div class="relative h-64 w-full overflow-hidden bg-white border-b-8 border-white group-hover:border-red-600 transition-colors duration-300">
                    @if($firstMediaUrl)
                        <img 
                            src="{{ $firstMediaUrl }}" 
                            alt="Image for {{ $step->title }}" 
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center font-mono text-black font-black">NO_IMAGE</div>
                    @endif
                    <div class="absolute inset-0 bg-red-600/0 group-hover:bg-red-600/20 transition-all duration-500"></div>
                </div>

                <!-- Content -->
                <div class="p-8 flex-1 bg-black text-white group-hover:bg-white group-hover:text-black transition-all duration-500">
                    <div class="font-mono text-sm mb-4 opacity-50 uppercase tracking-widest">Process_Step_{{ $index + 1 }}</div>
                    <h3 class="font-mono text-3xl font-black uppercase mb-6 leading-none">{{ $step->title }}</h3>
                    <div class="font-mono text-sm leading-relaxed opacity-80 group-hover:opacity-100">
                        {!! $step->description !!}
                    </div>
                </div>
                
                <!-- Bottom Decorative Bar -->
                <div class="h-4 bg-white group-hover:bg-red-600 transition-colors duration-500"></div>
            </div>
        @endforeach
    </div>
</div>
