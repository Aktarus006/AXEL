<?php

use function Livewire\Volt\{state, mount};
use App\Models\Collection;

state([
    "collections" => [],
]);

mount(function () {
    $this->collections = Collection::with('media')->get();
});
?>


<div class="w-full bg-black text-white font-mono">
    <!-- Title -->
    <div class="w-full border-b-4 border-white">
        <div class="max-w-screen-xl mx-auto p-8">
            <h2 class="text-6xl font-bold tracking-tight uppercase relative inline-block">
                Collections
                <div class="absolute top-0 right-0 -mt-4 -mr-4 text-sm bg-white text-black px-2 py-1 transform rotate-12">
                    {{ str_pad($collections->count(), 2, '0', STR_PAD_LEFT) }}
                </div>
            </h2>
        </div>
    </div>

    <!-- Collections Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
        @foreach ($collections as $index => $collection)
            <div 
                class="group relative border-4 border-white overflow-hidden transition-transform duration-300 hover:-translate-y-2"
                x-data="{ isHovered: false }"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
            >
                <!-- Collection Number -->
                <div class="absolute top-0 left-0 bg-white text-black text-xl font-bold p-2 border-r-4 border-b-4 border-white z-10">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </div>

                <!-- Collection Image -->
                @if($collection->hasMedia('collections/images'))
                    <div class="aspect-square">
                        <img 
                            src="{{ $collection->getFirstMediaUrl('collections/images') }}" 
                            alt="{{ $collection->name }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        >
                    </div>
                @else
                    <div class="aspect-square bg-black flex items-center justify-center">
                        <div class="text-4xl font-bold opacity-20">NO IMAGE</div>
                    </div>
                @endif

                <!-- Collection Info -->
                <div 
                    class="absolute inset-0 bg-black bg-opacity-80 flex flex-col justify-end p-6 transition-opacity duration-300"
                    :class="isHovered ? 'opacity-100' : 'opacity-0'"
                >
                    <h3 class="text-3xl font-bold uppercase mb-4 transform transition-transform duration-300"
                        :class="isHovered ? 'translate-x-0' : '-translate-x-full'">
                        {{ $collection->name }}
                    </h3>
                    <p class="text-sm leading-relaxed transform transition-transform duration-300 delay-100"
                        :class="isHovered ? 'translate-x-0' : 'translate-x-full'">
                        {{ $collection->description }}
                    </p>
                </div>

                <!-- View Collection Button -->
                <a 
                    href="/collections/{{ $collection->id }}"
                    class="absolute bottom-0 right-0 bg-white text-black px-4 py-2 font-bold uppercase text-sm border-l-4 border-t-4 border-white transform translate-y-full transition-transform duration-300 hover:bg-black hover:text-white"
                    :class="isHovered ? 'translate-y-0' : 'translate-y-full'"
                >
                    View Collection
                </a>
            </div>
        @endforeach
    </div>
</div>
