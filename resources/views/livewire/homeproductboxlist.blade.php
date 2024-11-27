<?php

use function Livewire\Volt\{state, mount, on};
use App\Models\Jewel;

state(['jewels' => [], 'selectedJewel' => null]);

mount(function () {
    // Get 8 random jewels with their media
    $this->jewels = Jewel::with('media')
        ->inRandomOrder()
        ->take(8)
        ->get()
        ->map(function($jewel) {
            return [
                'id' => $jewel->id,
                'name' => $jewel->name,
                'price' => $jewel->price,
                'media' => $jewel->media->map(function($media) {
                    return ['original_url' => $media->getUrl()];
                })->toArray()
            ];
        });
});

on(['jewel-hovered' => function ($jewel) {
    $this->selectedJewel = $jewel;
}]);

?>

<div class="flex w-full h-1/2 bg-black">
    <div class="flex flex-wrap w-1/3 border-r-4 border-white">
        @foreach ($jewels->take(4) as $jewel)
            <div class="w-1/2 h-1/2 border-b-4 border-white last:border-b-0">
                <livewire:homeproductbox :jewelId="$jewel['id']" />
            </div>
        @endforeach
    </div>
    <div class="w-1/3 border-r-4 border-white">
        <div class="flex items-center justify-center w-full h-full">
            @if ($selectedJewel)
                <div class="w-full h-full relative group">
                    <img 
                        src="{{ $selectedJewel['media'][0]['original_url'] }}" 
                        alt="{{ $selectedJewel['name'] }}" 
                        class="w-full h-full object-contain"
                    >
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-80 transition-opacity duration-300 flex items-end justify-start p-8 opacity-0 group-hover:opacity-100">
                        <div class="text-white font-mono">
                            <h3 class="text-2xl uppercase mb-2">{{ $selectedJewel['name'] }}</h3>
                            <p class="text-xl">€{{ number_format($selectedJewel['price'], 2) }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-4xl text-white font-mono tracking-widest">NOS PRODUITS</div>
            @endif
        </div>
    </div>
    <div class="flex flex-wrap w-1/3">
        @foreach ($jewels->skip(4) as $jewel)
            <div class="w-1/2 h-1/2 border-b-4 border-white last:border-b-0">
                <livewire:homeproductbox :jewelId="$jewel['id']" />
            </div>
        @endforeach
    </div>
</div>
