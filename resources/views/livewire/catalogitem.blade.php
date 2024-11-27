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
        
        <!-- Subtle overlay with text -->
        @if($jewel)
            <!-- Title bar at top -->
            <div class="absolute top-0 left-0 right-0 p-3 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-500">
                <h3 class="font-mono text-white text-lg tracking-tight opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    {{ strtoupper($jewel->name) }}
                </h3>
            </div>

            <!-- Badges at bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-3 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-500">
                <div class="flex flex-wrap gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    @if($jewel->material)
                        @foreach(explode(',', str_replace(['"', '[', ']'], '', $jewel->material)) as $material)
                            @php
                                $decodedMaterial = json_decode('"' . trim($material) . '"');
                            @endphp
                            <span class="font-mono text-xs bg-white text-black px-2 py-0.5 border border-white hover:bg-black hover:text-white transition-colors duration-300">
                                {{ strtoupper($decodedMaterial) }}
                            </span>
                        @endforeach
                    @endif
                    @if($jewel->type)
                        @foreach(explode(',', str_replace(['"', '[', ']'], '', $jewel->type)) as $type)
                            @php
                                $decodedType = json_decode('"' . trim($type) . '"');
                            @endphp
                            <span class="font-mono text-xs bg-black text-white px-2 py-0.5 border border-white hover:bg-white hover:text-black transition-colors duration-300">
                                {{ strtoupper($decodedType) }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </a>
</div>