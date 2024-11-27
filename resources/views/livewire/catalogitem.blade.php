<?php
use App\Models\Jewel;
use function Livewire\Volt\{props};

props(['jewel', 'mediaUrl']);

state([
    "key" => "",
    "jewel" => null,
    "mediaUrl" => "",
]);
?>

<div class="relative w-full h-full">
    @if($jewel)
        <a href="{{ route('jewels.show', $jewel) }}" class="block w-full h-full relative group">
            <!-- Image Container with Grayscale Effect -->
            <div class="absolute inset-0 w-full h-full overflow-hidden">
                @if($mediaUrl)
                    <img 
                        src="{{ $mediaUrl }}" 
                        alt="{{ $jewel->name }}" 
                        class="w-full h-full object-cover transition-all duration-300 filter grayscale group-hover:grayscale-0"
                        loading="lazy"
                    />
                @else
                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                        <span class="text-white font-mono text-sm">No Image</span>
                    </div>
                @endif
            </div>

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-80 transition-all duration-300">
                <div class="absolute inset-0 flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <!-- Title -->
                    <h3 class="font-mono text-white text-xl mb-2 truncate">
                        {{ $jewel->name }}
                    </h3>
                    
                    <!-- Creator -->
                    @if($jewel->creator)
                        <p class="font-mono text-white text-sm truncate">
                            {{ $jewel->creator->name }}
                        </p>
                    @endif

                    <!-- Collection -->
                    @if($jewel->collection)
                        <p class="font-mono text-white text-sm truncate">
                            {{ $jewel->collection->name }}
                        </p>
                    @endif

                    <!-- Price -->
                    <p class="font-mono text-white text-sm mt-2">
                        €{{ number_format($jewel->price, 2) }}
                    </p>
                </div>
            </div>
        </a>
    @else
        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
            <span class="text-gray-400 font-mono">JEWEL NOT FOUND</span>
        </div>
    @endif
</div>
