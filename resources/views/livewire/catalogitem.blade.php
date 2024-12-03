<?php
use App\Models\Jewel;
use function Livewire\Volt\{state};

state([
    "key" => "",
    "jewel" => null,
    "mediaUrl" => "",
]);
?>

<div class="relative w-full h-full border border-white cursor-pointer">
    <a href="/jewels/{{ $jewel?->id }}" @class([
        "block w-full h-full relative group",
        "pointer-events-none" => !$jewel
    ])>
        <div class="w-full h-full overflow-hidden inset-0 border-8 border-white hover:border-red-600 transition-colors duration-300">
            @if($jewel && $mediaUrl)
                <div class="relative group overflow-hidden">
                    <!-- Image with improved quality settings -->
                    <img 
                        src="{{ $mediaUrl }}" 
                        alt="{{ $jewel->name }}"
                        class="w-full h-full object-cover object-center grayscale transition-all duration-500 group-hover:grayscale-0 group-hover:scale-110"
                        loading="lazy"
                        decoding="async"
                        fetchpriority="high"
                    />
                    <!-- Title at Top -->
                    <div class="absolute inset-x-0 top-0 bg-red-600  p-4 transform -translate-y-[calc(100%+1px)] group-hover:translate-y-0 transition-transform duration-300 z-10">
                        <h3 class="font-mono text-xl text-white font-bold">{{ strtoupper($jewel->name) }}</h3>
                    </div>
                    
                    <!-- Price Tag -->
                    @if(isset($jewel->price))
                        <div class="absolute top-4 right-4 flex items-start group/price z-20">
                            <div class="relative overflow-hidden bg-white border-4 border-black">
                                <!-- Euro Symbol -->
                                <div class="p-2 bg-red-600 text-white transform group-hover/price:translate-x-full transition-transform duration-300">
                                    <span class="font-mono text-xl font-bold">€</span>
                                </div>
                                <!-- Price -->
                                <div class="absolute inset-0 bg-white p-2 transform -translate-x-full group-hover/price:translate-x-0 transition-transform duration-300">
                                    <span class="font-mono text-xl font-bold whitespace-nowrap">{{ $jewel->price }}€</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Badges at bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-red-600 bg-opacity-0 group-hover:bg-opacity-100 transition-all duration-500 transform translate-y-[calc(100%+1px)] group-hover:translate-y-0">
                        <div class="flex flex-wrap gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                            x-data="{ 
                                materials: {{ json_encode(array_map(function($m) { 
                                    return strtoupper(trim(json_decode('"' . trim(str_replace(['"', '[', ']'], '', $m)) . '"'))); 
                                }, explode(',', $jewel->material ?? ''))) }},
                                types: {{ json_encode(array_map(function($t) { 
                                    return strtoupper(trim(json_decode('"' . trim(str_replace(['"', '[', ']'], '', $t)) . '"'))); 
                                }, explode(',', $jewel->type ?? ''))) }}
                            }"
                        >
                            <template x-for="(material, index) in materials" :key="index">
                                <span 
                                    class="font-mono text-xs bg-white text-black px-2 py-0.5 border border-white hover:bg-black hover:text-white transition-colors duration-300"
                                    :style="{ 'transition-delay': (index * 50) + 'ms' }"
                                    x-text="material"
                                ></span>
                            </template>
                            
                            <template x-for="(type, index) in types" :key="index">
                                <span 
                                    class="font-mono text-xs bg-black text-white px-2 py-0.5 border border-white hover:bg-white hover:text-black transition-colors duration-300"
                                    :style="{ 'transition-delay': ((index + materials.length) * 50) + 'ms' }"
                                    x-text="type"
                                ></span>
                            </template>
                        </div>
                    </div>
                </div>
            @else
                <div class="w-full h-full bg-black flex items-center justify-center">
                    <span class="text-white font-mono text-lg">
                        {{ $jewel ? 'NO IMAGE' : 'JEWEL NOT FOUND' }}
                    </span>
                </div>
            @endif
        </div>

    <div class=""></div>
    </a>
</div>