<?php

use App\Models\Jewel;
use function Livewire\Volt\{state};

state(["key", "jewel", "mediaUrl"]);
?>

<div class="relative w-full h-full">
    <a href="/jewels/{{ $jewel->id }}" class="block w-full h-full">
        <!-- Main Image with Brutalist Treatment -->
        <div class="relative w-full h-full overflow-hidden">
            <img 
                src="{{ $mediaUrl }}" 
                alt="{{ $jewel->name }}" 
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 group-hover:grayscale"
            >
            
            <!-- Brutalist Overlay Elements -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <!-- Price Tag - Top Right -->
                @if($jewel->price > 0)
                    <div class="absolute top-0 right-0 bg-black text-white p-4 font-mono text-xl">
                        ${{ number_format($jewel->price, 2) }}
                    </div>
                @endif

                <!-- Material Tag - Top Left -->
                <div class="absolute top-0 left-0 bg-white text-black p-4 font-mono text-sm uppercase">
                    {{ $jewel->material }}
                </div>

                <!-- Name and Details - Bottom -->
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-90 p-6">
                    <h3 class="font-mono text-2xl text-white uppercase mb-2 tracking-tight">
                        {{ $jewel->name }}
                    </h3>
                    
                    <!-- Type Tags -->
                    <div class="flex flex-wrap gap-2">
                        @foreach($jewel->type as $type)
                            <span class="bg-white text-black px-3 py-1 text-xs font-mono uppercase">
                                {{ $type }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
