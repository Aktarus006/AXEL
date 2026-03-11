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

<div class="w-full font-mono text-black bg-white min-h-screen">
    <!-- Sophisticated Title Section -->
    <div class="relative w-full border-b-8 border-black overflow-hidden bg-black text-white group">
        <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000">
            <div class="grid grid-cols-12 h-full w-full">
                @foreach(range(1, 48) as $i)
                    <div class="border-[0.5px] border-white/20 aspect-square"></div>
                @endforeach
            </div>
        </div>

        <div class="relative z-10 px-8 py-32 md:py-48 max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-end gap-12">
            <h1 class="text-[12vw] md:text-[8vw] font-black uppercase leading-[0.8] tracking-tighter transform -skew-x-12">
                LES<br/><span class="text-red-700">SÉRIES</span>
            </h1>
            <div class="text-right max-w-md">
                <p class="text-xl uppercase font-bold mb-4">L'archeologie du geste</p>
                <p class="text-sm opacity-60 uppercase leading-relaxed">
                    Chaque série explore une thématique, un matériau ou une technique spécifique. 
                    Des objets singuliers nés de la rencontre entre l'esprit et la matière.
                </p>
            </div>
        </div>
        
        <!-- Bottom Accent Bar -->
        <div class="absolute inset-x-0 bottom-0 h-4 transition-transform duration-500 origin-left scale-x-0 bg-red-700 group-hover:scale-x-100"></div>
    </div>

    <!-- Collections List (High-End Layout) -->
    <div class="w-full">
        @foreach ($collections as $index => $collection)
            <div class="group border-b-8 border-black hover:bg-neutral-50 transition-colors duration-500">
                <a href="/collections/{{ $collection->id }}" class="flex flex-col md:flex-row items-stretch min-h-[500px]">
                    <!-- Index Number -->
                    <div class="w-full md:w-24 border-b-4 md:border-b-0 md:border-r-8 border-black flex items-center justify-center bg-white text-black text-4xl font-black group-hover:bg-red-700 group-hover:text-white transition-colors">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <!-- Image Section -->
                    <div class="w-full md:w-1/2 lg:w-2/5 border-b-4 md:border-b-0 md:border-r-8 border-black overflow-hidden relative bg-neutral-200">
                        @if($collection->hasMedia('collections/images'))
                            <img
                                src="{{ $collection->getFirstMediaUrl('collections/images', 'large') }}"
                                alt="{{ $collection->name }}"
                                class="w-full h-full object-cover transition-all duration-1000 grayscale group-hover:grayscale-0 group-hover:scale-110"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center font-black text-black/10 text-6xl">NO_IMG</div>
                        @endif
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/10 transition-colors duration-500"></div>
                    </div>

                    <!-- Content Section -->
                    <div class="flex-1 p-8 md:p-16 flex flex-col justify-center space-y-8 bg-white group-hover:bg-neutral-50 transition-colors">
                        <div class="space-y-4">
                            <span class="text-xs font-black uppercase tracking-[0.3em] text-red-700">Exploration_Thématique</span>
                            <h2 class="text-5xl md:text-7xl font-black uppercase leading-none tracking-tighter group-hover:translate-x-4 transition-transform duration-500">
                                {{ $collection->name }}
                            </h2>
                        </div>

                        <div class="prose prose-sm font-mono text-black/60 max-w-xl group-hover:text-black transition-colors duration-500 line-clamp-3">
                            {!! strip_tags($collection->description) !!}
                        </div>

                        <div class="flex items-center gap-8 pt-4">
                            <div class="flex-1 h-1 bg-black/10 relative overflow-hidden">
                                <div class="absolute inset-0 bg-red-700 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-1000"></div>
                            </div>
                            <span class="font-black text-xl group-hover:text-red-700 transition-colors">VOIR LA SÉRIE →</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
