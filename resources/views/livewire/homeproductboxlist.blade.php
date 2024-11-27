<?php

use function Livewire\Volt\{state, mount, computed, on};
use App\Models\Jewel;

state(['jewels' => [], 'selectedJewel' => null]);

$refreshJewels = function() {
    $this->selectedJewel = null;
    
    // Get jewels with images only
    $jewelsWithImages = Jewel::with('media')
        ->whereHas('media', function($query) {
            $query->where('collection_name', 'jewels/images');
        })
        ->inRandomOrder()
        ->take(16) // Take more to ensure we have enough after filtering
        ->get()
        ->filter(function($jewel) {
            return $jewel->hasMedia('jewels/images');
        })
        ->take(8) // Take exactly 8 after filtering
        ->values()
        ->map(function($jewel) {
            return [
                'id' => $jewel->id,
                'name' => $jewel->name,
                'price' => $jewel->price,
                'media' => [
                    ['original_url' => $jewel->getFirstMediaUrl('jewels/images', 'thumb')]
                ]
            ];
        });

    // If we don't have enough jewels with images, get more
    if ($jewelsWithImages->count() < 8) {
        $this->refreshJewels(); // Recursively try again
        return;
    }

    $this->jewels = $jewelsWithImages;
    $this->dispatch('jewels-refreshed');
};

mount(function () {
    $this->refreshJewels();
});

on(['jewel-hovered' => function ($jewel) {
    $this->selectedJewel = $jewel;
}]);

on(['jewel-unhovered' => function () {
    $this->selectedJewel = null;
}]);

?>

<div 
    x-data
    class="flex w-full h-1/2 bg-black"
    wire:poll.20s="refreshJewels"
>
    <!-- Left Jewels -->
    <div class="flex flex-wrap w-1/3 border-r-4 border-white">
        @foreach ($jewels->take(4) as $index => $jewel)
            <div class="w-1/2 h-1/2 relative" wire:key="left-jewel-{{ $jewel['id'] }}">
                <div class="absolute inset-0 border-b-4 border-r-4 border-white {{ $index >= 2 ? 'border-b-0' : '' }}">
                    <livewire:homeproductbox :wire:key="'box-'.$jewel['id']" :jewelId="$jewel['id']" />
                </div>
            </div>
        @endforeach
    </div>

    <!-- Center Display -->
    <div class="w-1/3 border-r-4 border-white">
        <div class="flex items-center justify-center w-full h-full relative">
            <!-- Default State -->
            <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 {{ $selectedJewel ? 'opacity-0 pointer-events-none' : 'opacity-100' }}">
                <div class="text-4xl text-white font-mono tracking-widest">NOS PRODUITS</div>
            </div>

            <!-- Selected Jewel State -->
            <div class="absolute inset-0 transition-opacity duration-300 {{ $selectedJewel ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                @if($selectedJewel)
                    <img 
                        src="{{ $selectedJewel['media'][0]['original_url'] }}" 
                        alt="{{ $selectedJewel['name'] }}" 
                        class="w-full h-full object-contain"
                    >
                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-80 transition-opacity duration-300 flex items-end justify-start p-8">
                        <div class="text-white font-mono opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <h3 class="text-2xl uppercase mb-2">{{ $selectedJewel['name'] }}</h3>
                            <p class="text-xl">€{{ number_format($selectedJewel['price'], 2) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Jewels -->
    <div class="flex flex-wrap w-1/3">
        @foreach ($jewels->skip(4) as $index => $jewel)
            <div class="w-1/2 h-1/2 relative" wire:key="right-jewel-{{ $jewel['id'] }}">
                <div class="absolute inset-0 border-b-4 border-r-4 border-white {{ $index >= 2 ? 'border-b-0' : '' }}">
                    <livewire:homeproductbox :wire:key="'box-'.$jewel['id']" :jewelId="$jewel['id']" />
                </div>
            </div>
        @endforeach
    </div>
</div>
