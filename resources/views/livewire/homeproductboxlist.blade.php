<?php

use function Livewire\Volt\{state, mount, computed, on};
use App\Models\Jewel;

state(['jewels' => [], 'selectedJewel' => null]);

mount(function () {
    $this->jewels = Jewel::with('media')
        ->whereHas('media', function($query) {
            $query->where('collection_name', 'jewels/packshots');
        })
        ->inRandomOrder()
        ->take(12)
        ->get();
});

on(['jewel-hovered' => function ($jewel) {
    $this->selectedJewel = $jewel;
}]);

on(['jewel-unhovered' => function () {
    $this->selectedJewel = null;
}]);

?>

<div class="w-full bg-black border-b-8 border-white overflow-hidden">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end p-8 border-b-8 border-white bg-black">
        <h2 class="font-mono text-[15vw] md:text-[8vw] leading-none text-white font-black tracking-tighter uppercase mb-4 md:mb-0">
            RANDOM<br/><span class="text-outline-white text-transparent">DROP</span>
        </h2>
        <div class="font-mono text-white text-right space-y-2 uppercase text-sm md:text-xl font-bold">
            <p class="bg-red-600 px-4 py-1 inline-block transform -skew-x-12">Limited Pieces</p>
            <p>Handcrafted in Studio</p>
            <p class="text-gray-400">#AXEL_ARCHIVE_2026</p>
        </div>
    </div>

    <!-- The Product Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 w-full">
        @foreach($jewels as $index => $jewel)
            <div @class([
                "relative aspect-square border-r-4 border-b-4 border-white overflow-hidden group",
                "col-span-2 md:col-span-1 lg:col-span-2 md:row-span-2 lg:row-span-2 aspect-auto" => $index === 0, // First item is bigger
                "hidden lg:block lg:col-span-2" => $index === 5, // Another bigger item for LG screens
                "border-r-0" => ($index + 1) % 2 === 0 && (app()->getLocale() == 'en' ? false : true), // Placeholder logic for mobile r-border
            ])>
                <livewire:homeproductbox :wire:key="'box-'.$jewel->id" :jewelId="$jewel->id" />
            </div>
        @endforeach
        
        <!-- Interactive Brutalist Box (Desktop only) -->
        <div class="hidden lg:flex col-span-2 row-span-2 bg-white border-b-4 border-white flex-col items-center justify-center p-8 text-center group cursor-help hover:bg-black transition-colors duration-500">
            <div class="text-6xl mb-4 group-hover:invert animate-bounce">⚡</div>
            <h3 class="font-mono text-4xl font-black mb-4 group-hover:text-white">EXPLORE THE ARCHIVE</h3>
            <p class="font-mono text-sm group-hover:text-white uppercase">Surgical precision meets brutalist aesthetics. Each piece is unique.</p>
            <div class="mt-8 border-4 border-black group-hover:border-white px-8 py-4 font-mono font-black group-hover:text-white group-hover:bg-red-600 transition-all duration-300">
                VIEW ALL
            </div>
        </div>
    </div>
</div>

<style>
.text-outline-white {
    -webkit-text-stroke: 2px white;
    text-shadow: none;
}
</style>
