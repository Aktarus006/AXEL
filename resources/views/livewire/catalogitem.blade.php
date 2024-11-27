<?php
use App\Models\Jewel;
use function Livewire\Volt\{state};

state([
    "key" => "",
    "jewel" => null,
    "mediaUrl" => "",
]);
?>

<div class="relative w-full h-full border border-white bg-black">
    <a href="/jewels/{{ $jewel?->id }}" @class([
        "block w-full h-full relative group",
        "pointer-events-none" => !$jewel
    ])>
        <div class="w-full h-full overflow-hidden">
            @if($jewel && $mediaUrl)
                <img 
                    src="{{ $mediaUrl }}" 
                    alt="{{ $jewel->name }}" 
                    class="w-full h-full object-cover grayscale transition-all duration-500 group-hover:grayscale-0 group-hover:scale-105"
                    loading="lazy"
                >
            @else
                <div class="w-full h-full bg-black flex items-center justify-center">
                    <span class="text-white font-mono text-lg">
                        {{ $jewel ? 'NO IMAGE' : 'JEWEL NOT FOUND' }}
                    </span>
                </div>
            @endif
        </div>
        
        <!-- Semi-transparent overlay with text -->
        @if($jewel)
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-500 flex items-center justify-center">
                <div class="text-center p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform group-hover:translate-y-0 translate-y-4">
                    <h3 class="font-mono text-white text-xl mb-2 tracking-tight">{{ strtoupper($jewel->name) }}</h3>
                    @if($jewel->material)
                        <p class="font-mono text-white text-sm tracking-wider">{{ strtoupper($jewel->material) }}</p>
                    @endif
                    @if($jewel->type)
                        <p class="font-mono text-white text-sm tracking-wider mt-1">{{ strtoupper($jewel->type) }}</p>
                    @endif
                </div>
            </div>
        @endif
    </a>
</div>