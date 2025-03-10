<?php

use function Livewire\Volt\{state, mount};
use App\Models\Collection;
use Illuminate\Support\Str;

state([
    "collections" => [],
]);

mount(function () {
    $this->collections = Collection::with(['media', 'creators'])->get();
});
?>


<div class="w-full font-mono text-white bg-black">
    <!-- Title Section -->
    <div class="relative w-full border-b-8 border-white overflow-hidden"
         x-data="{ showContent: false }"
         x-init="setTimeout(() => showContent = true, 100)">

        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black"
             x-show="showContent"
             x-transition:enter="transition-transform duration-1000"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0">
            <div class="absolute inset-0 opacity-20">
                @for ($i = 0; $i < 50; $i++)
                    <div class="absolute border border-white"
                         style="
                            left: {{ rand(0, 100) }}%;
                            top: {{ rand(0, 100) }}%;
                            width: {{ rand(10, 100) }}px;
                            height: {{ rand(10, 100) }}px;
                            transform: rotate({{ rand(0, 360) }}deg);
                         ">
                    </div>
                @endfor
            </div>
        </div>

        <!-- Title -->
        <div class="relative z-10 px-8 py-32">
            <div class="max-w-screen-xl mx-auto">
                <h1 class="text-[8rem] font-black uppercase leading-none tracking-tighter"
                    x-show="showContent"
                    x-transition:enter="transition-transform duration-1000 delay-500"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0">
                    Collections
                    <span class="absolute top-0 right-0 px-4 py-2 text-4xl text-black bg-white -rotate-12">
                        {{ str_pad($collections->count(), 2, '0', STR_PAD_LEFT) }}
                    </span>
                </h1>
            </div>
        </div>
    </div>

    <!-- Collections Grid -->
    <div class="grid grid-cols-1 gap-8 p-8 md:grid-cols-2">
        @foreach ($collections as $index => $collection)
            <div
                class="group relative border-4 border-white overflow-hidden transition-transform duration-300 hover:-translate-y-2"
                x-data="{ isHovered: false }"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
            >
                <a href="/collections/{{ $collection->id }}" class="block relative">
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
                        <div class="prose prose-invert max-w-none text-sm leading-relaxed transform transition-transform duration-300 delay-100"
                            :class="isHovered ? 'translate-x-0' : 'translate-x-full'">
                            {!! $collection->description !!}
                            @if($collection->creators->isNotEmpty())
                                <div class="mt-4">
                                    <div class="text-sm font-bold">CRÉATEURS</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($collection->creators as $creator)
                                            <span class="inline-block px-2 py-1 text-xs border border-white">
                                                {{ $creator->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>

                <!-- View Collection Button -->
                <div
                    class="absolute bottom-0 right-0 bg-white text-black px-4 py-2 font-bold uppercase text-sm border-l-4 border-t-4 border-white transform translate-y-full transition-transform duration-300"
                    :class="isHovered ? 'translate-y-0' : 'translate-y-full'"
                >
                    View Collection
                </div>

                <!-- Hover Effect -->
                <div class="absolute inset-0 bg-red-700 mix-blend-multiply opacity-0 group-hover:opacity-30 transition-opacity duration-500 pointer-events-none"></div>
            </div>
        @endforeach
    </div>
</div>
