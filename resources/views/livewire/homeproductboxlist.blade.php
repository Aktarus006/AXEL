<?php

use function Livewire\Volt\{state, mount, on};
use Illuminate\Support\Facades\Http;

state(['jewels' => [], 'selectedJewel' => null]);

mount(function () {
    // $response = Http::get('/api/jewels/random');
    // $this->jewels = $response->json();
    // Fake data
    $this->jewels = collect([
        [
            'id' => 1,
            'name' => 'Diamond Ring',
            'price' => 1000,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Diamond+Ring']],
        ],
        [
            'id' => 2,
            'name' => 'Gold Necklace',
            'price' => 800,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Gold+Necklace']],
        ],
        [
            'id' => 3,
            'name' => 'Silver Bracelet',
            'price' => 500,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Silver+Bracelet']],
        ],
        [
            'id' => 4,
            'name' => 'Pearl Earrings',
            'price' => 600,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Pearl+Earrings']],
        ],
        [
            'id' => 5,
            'name' => 'Emerald Pendant',
            'price' => 1200,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Emerald+Pendant']],
        ],
        [
            'id' => 6,
            'name' => 'Ruby Bracelet',
            'price' => 900,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Ruby+Bracelet']],
        ],
        [
            'id' => 7,
            'name' => 'Sapphire Ring',
            'price' => 1100,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Sapphire+Ring']],
        ],
        [
            'id' => 8,
            'name' => 'Platinum Watch',
            'price' => 2000,
            'media' => [['original_url' => 'https://via.placeholder.com/300x300.png?text=Platinum+Watch']],
        ],
    ]);
});

on(['jewel-hovered' => function ($jewel) {
    $this->selectedJewel = $jewel;
}]);

?>

<div class="flex w-full h-1/2 bg-slate-900">
    <div class="flex flex-wrap w-1/3">
        @foreach ($jewels->take(4) as $jewel)
            <div class="w-1/2 h-1/2">
                <livewire:homeproductbox :$jewel />
            </div>
        @endforeach
    </div>
    <div class="flex w-1/3 border border-white border-1">
        <div class="flex items-center justify-center w-full">
            @if ($selectedJewel)
                <img src="{{ $selectedJewel['media'][0]['original_url'] }}" alt="{{ $selectedJewel['name'] }}" class="w-full h-full object-contain">
            @else
                <div class="text-4xl text-white">NOS PRODUITS</div>
            @endif
        </div>
    </div>
    <div class="flex flex-wrap w-1/3">
        @foreach ($jewels->skip(4) as $jewel)
            <div class="w-1/2 h-1/2">
                <livewire:homeproductbox :$jewel />
            </div>
        @endforeach
    </div>
</div>
