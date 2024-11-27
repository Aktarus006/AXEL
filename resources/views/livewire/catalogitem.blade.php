<?php
use App\Models\Jewel;
use function Livewire\Volt\{state};

state([
    "key" => "",
    "jewel" => null,
    "mediaUrl" => "",
]);
?>

<div class="relative w-full h-full">
    @if($jewel)
        <a href="/jewels/{{ $jewel->id }}" class="block w-full h-full relative group">
            <div class="absolute inset-0 overflow-hidden bg-black">
                @if($mediaUrl)
                    <img 
                        src="{{ $mediaUrl }}" 
                        alt="{{ $jewel->name }}" 
                        class="w-full h-full object-cover grayscale transition-all duration-300 group-hover:grayscale-0 group-hover:scale-105"
                        loading="lazy"
                    >
                @else
                    <div class="w-full h-full bg-black flex items-center justify-center">
                        <span class="text-white font-mono text-lg">NO IMAGE</span>
                    </div>
                @endif
            </div>
            
            <!-- Overlay with text -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-90 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="text-center p-4">
                    <h3 class="font-mono text-white text-xl mb-2 tracking-tight">{{ $jewel->name }}</h3>
                    @if($jewel->creator)
                        <p class="font-mono text-white text-sm tracking-wider">BY {{ strtoupper($jewel->creator->first_name . ' ' . $jewel->creator->last_name) }}</p>
                    @endif
                </div>
            </div>
        </a>
    @else
        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
            <span class="text-gray-400 font-mono">JEWEL NOT FOUND</span>
        </div>
    @endif
</div>
