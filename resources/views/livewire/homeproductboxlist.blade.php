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
        ->take(8)
        ->get()
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
});

on(['jewel-hovered' => function ($jewel) {
    $this->selectedJewel = $jewel;
}]);

on(['jewel-unhovered' => function () {
    $this->selectedJewel = null;
}]);

?>

<div class="flex w-full bg-black h-1/2">
    <!-- Left Jewels -->
    <div class="flex flex-wrap w-1/3 border-r-4 border-white">
        @foreach ($jewels->take(4) as $index => $jewel)
            <div class="relative w-1/2 h-1/2">
                <div class="absolute inset-0 border-b-4 border-r-4 border-white {{ $index >= 2 ? 'border-b-0' : '' }}">
                    <livewire:homeproductbox :wire:key="'box-'.$jewel['id']" :jewelId="$jewel['id']" />
                </div>
            </div>
        @endforeach
    </div>

    <!-- Center Display -->
    <div class="w-1/3 border-b-4 border-r-4 border-white">
        <div class="relative flex items-center justify-center w-full h-full">
            <!-- Default State -->
            <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 {{ $selectedJewel ? 'opacity-0 pointer-events-none' : 'opacity-100' }}">
                <div class="font-mono text-4xl tracking-widest text-white">NOS PRODUITS</div>
            </div>

            <!-- Selected Jewel State -->
            <div class="absolute inset-0 transition-opacity duration-300 {{ $selectedJewel ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                @if($selectedJewel)
                    <img
                        src="{{ $selectedJewel['media'][0]['original_url'] }}"
                        alt="{{ $selectedJewel['name'] }}"
                        class="object-cover w-full h-full"
                    >
                    <div class="absolute inset-0 flex items-end justify-start p-8 transition-opacity duration-300 bg-black bg-opacity-0 hover:bg-opacity-80">
                        <div class="font-mono text-white transition-opacity duration-300 opacity-0 hover:opacity-100">
                            <h3 class="mb-2 text-2xl uppercase">{{ $selectedJewel['name'] }}</h3>
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
            <div class="relative w-1/2 h-1/2">
                <div class="absolute inset-0 border-b-4 border-r-4 border-white {{ $index >= 2 ? 'border-b-0' : '' }}">
                    <livewire:homeproductbox :wire:key="'box-'.$jewel['id']" :jewelId="$jewel['id']" />
                </div>
            </div>
        @endforeach
    </div>
</div>
