<?php

use App\Models\Jewel;
use function Livewire\Volt\{state};

state(["key", "jewel", "mediaUrl"]);
?>
<div class="group relative border-2 border-black p-4 hover:bg-black hover:text-white transition duration-200">
    <a href="/jewels/{{ $jewel->id }}" class="block">
        <!-- Image container with harsh edges and grayscale background for brutalist feel -->
        <div class="w-full h-60 overflow-hidden bg-gray-100 border-2 border-black">
            <img src="{{ $mediaUrl }}" alt="{{ $jewel->name }}" class="w-full h-full object-cover">
        </div>

        <!-- Jewel Info -->
        <div class="mt-4 text-left">
            <h3 class="text-lg font-bold text-black group-hover:text-white">
                {{ $jewel->name }}
            </h3>
            <p class="text-sm text-gray-700 group-hover:text-gray-300">{{ $jewel->material }}</p>
            <p class="text-xl font-bold text-black mt-2 group-hover:text-white">
                ${{ number_format($jewel->price, 2) }}
            </p>
        </div>
    </a>
</div>
