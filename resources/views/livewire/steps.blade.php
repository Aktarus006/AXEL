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

<div class="mt-32 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($steps as $step)
            @php
                // Get the first image associated with this step
                $media = $step->getMedia('steps/images');
                $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
            @endphp

            <div class="border-4 border-white bg-black text-white font-mono relative overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                <!-- Position Number -->
                <div class="absolute top-0 right-0 bg-white text-black text-4xl font-bold p-4 border-l-4 border-b-4 border-white">
                    {{ str_pad($step->position, 2, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Image -->
                @if($firstMediaUrl)
                    <div class="aspect-square border-b-4 border-white overflow-hidden">
                        <img 
                            src="{{ $firstMediaUrl }}" 
                            alt="Image for {{ $step->title }}" 
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                @endif

                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-2xl uppercase mb-4 pr-16">{{ $step->title }}</h3>
                    <div class="text-sm leading-relaxed opacity-80">
                        {!! $step->description !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
