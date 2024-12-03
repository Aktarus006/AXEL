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

<div class="mt-8 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($steps as $step)
            @php
                // Get the first image associated with this step
                $media = $step->getMedia('steps/images');
                $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
            @endphp

            <div class="border-4 border-white hover:border-red-600 bg-black text-white font-mono relative overflow-hidden transition-all duration-300 hover:-translate-y-2 flex flex-col h-[300px]">
                <!-- Position Number -->
                <div class="absolute top-0 right-0 bg-white text-black text-4xl font-bold p-4 border-l-4 border-b-4 border-white group-hover:border-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                    {{ str_pad($step->position, 2, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Image -->
                @if($firstMediaUrl)
                    <div class="h-[300px] border-b-4 border-white group-hover:border-red-600 overflow-hidden flex-shrink-0 transition-colors duration-300">
                        <img 
                            src="{{ $firstMediaUrl }}" 
                            alt="Image for {{ $step->title }}" 
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                @endif

                <!-- Content -->
                <div class="p-6 pt-16 flex-1 overflow-y-auto">
                    <h3 class="text-2xl uppercase mb-4 group-hover:text-red-600 transition-colors duration-300">{{ $step->title }}</h3>
                    <div class="text-sm leading-relaxed opacity-80">
                        {!! $step->description !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
