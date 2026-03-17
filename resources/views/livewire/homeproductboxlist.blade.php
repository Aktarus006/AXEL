<?php

use function Livewire\Volt\{state, mount, computed, on};
use App\Models\Jewel;

use App\Enums\Status;

state(['jewels' => []]);

mount(function () {
    $this->jewels = Jewel::with('media')
        ->where('online', Status::ONLINE)
        ->whereHas('media', function($query) {
            $query->where('collection_name', 'jewels/packshots');
        })
        // Removed the price filter to show all products
        ->inRandomOrder()
        ->take(6)
        ->get();
});

?>

<div class="w-full bg-white border-b-8 border-black">
    <!-- Header Section -->
    <div class="max-w-[1440px] mx-auto px-8 py-20 flex flex-col md:flex-row justify-between items-end gap-12 text-black">
        <div class="max-w-2xl">
            <h2 class="font-mono text-5xl md:text-[7vw] leading-[0.85] font-black tracking-tighter uppercase">
                SÉLECTION<br/><span class="text-red-700">D'OBJETS</span>
            </h2>
            <p class="font-mono text-xl mt-8 uppercase font-bold tracking-tight">
                Une exploration du geste et de la matière. Des pièces uniques forgées à l'établi pour l'individu contemporain.
            </p>
        </div>
        <div class="flex flex-col items-end text-right">
            <div class="font-mono font-black text-2xl border-4 border-black px-4 py-2 mb-4">NOUVEAU_DROP</div>
            <p class="font-mono text-sm uppercase tracking-widest opacity-60 max-w-[200px]">Fait main à l'Atelier / Pièces Limitées</p>
        </div>
    </div>

    <!-- The Product Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 border-t-8 border-black">
        @foreach($jewels as $index => $jewel)
            <div class="relative aspect-square border-b-8 border-black [&:nth-child(2n)]:border-l-8 lg:[&:nth-child(2n)]:border-l-0 lg:[&:nth-child(3n-1)]:border-l-8 lg:[&:nth-child(3n)]:border-l-8 overflow-hidden group">
                <livewire:homeproductbox :wire:key="'box-'.$jewel->id" :jewelId="$jewel->id" />
            </div>
        @endforeach
    </div>
    
    <!-- View All Button Section -->
    <div class="p-8 md:p-20 bg-black flex justify-center items-center overflow-hidden relative">
        <div class="absolute inset-0 opacity-20 pointer-events-none font-mono text-[20vw] font-black text-white whitespace-nowrap animate-marquee">
            COLLECTION_COLLECTION_COLLECTION
        </div>
        <a href="/jewels" class="relative z-10 px-8 py-6 md:px-16 md:py-8 bg-white text-black font-mono text-xl md:text-4xl font-black uppercase hover:bg-red-700 hover:text-white focus:bg-red-700 focus:text-white focus:outline-none transition-all transform hover:scale-110 active:scale-95 border-4 md:border-8 border-black shadow-[6px_6px_0px_0px_rgba(255,255,255,0.3)] md:shadow-[10px_10px_0px_0px_rgba(255,255,255,0.3)] text-center">
            VOIR TOUTE LA COLLECTION
        </a>
    </div>
</div>
