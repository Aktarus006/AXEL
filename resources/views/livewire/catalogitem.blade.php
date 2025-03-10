<?php
use App\Models\Jewel;
use function Livewire\Volt\{state, mount};

state([
    "key" => "",
    "jewel" => null,
    "packshotUrl" => "",
    "lifestyleUrl" => "",
]);

mount(function (Jewel $jewel = null) {
    if ($jewel) {
        $this->jewel = $jewel;
        $packshot = $jewel->getMedia('jewels/packshots')->first();
        $lifestyle = $jewel->getMedia('jewels/lifestyle')->first();

        if ($packshot) {
            $this->packshotUrl = $packshot->getUrl('medium');
            \Log::info("Packshot URL for jewel {$jewel->name}: " . $this->packshotUrl);
            \Log::info("Packshot path: " . $packshot->getPath());
            \Log::info("Packshot exists: " . (file_exists($packshot->getPath()) ? 'yes' : 'no'));
            \Log::info("Packshot disk: " . $packshot->disk);
            \Log::info("Packshot collection: " . $packshot->collection_name);
        } else {
            \Log::warning("No packshot found for jewel {$jewel->name}");
        }

        if ($lifestyle) {
            $this->lifestyleUrl = $lifestyle->getUrl('medium');
            \Log::info("Lifestyle URL for jewel {$jewel->name}: " . $this->lifestyleUrl);
        }
    }
});
?>

<div class="relative w-full h-full border border-white cursor-pointer">
    <a href="/jewels/{{ $jewel?->id }}" @class([
        "block w-full h-full relative group",
        "pointer-events-none" => !$jewel
    ])>
        <div class="inset-0 w-full h-full overflow-hidden transition-colors duration-300 border-8 border-white hover:border-red-700">
            @if($jewel && ($packshotUrl || $lifestyleUrl))
                <div class="relative overflow-hidden">
                    <!-- Packshot Image (default) -->
                    @if($packshotUrl)
                        <div class="w-full h-full">
                            <img
                                src="{{ $packshotUrl }}"
                                alt="{{ $jewel->name }} - Photo packshot"
                                class="absolute inset-0 z-10 object-cover object-center w-full h-full transition-all duration-500 grayscale {{ $lifestyleUrl ? '' : 'group-hover:grayscale-0' }}"
                                loading="lazy"
                                decoding="async"
                                fetchpriority="high"
                            />
                        </div>
                    @endif

                    <!-- Lifestyle Image (on hover) -->
                    @if($lifestyleUrl)
                        <div class="w-full h-full">
                            <img
                                src="{{ $lifestyleUrl }}"
                                alt="{{ $jewel->name }} - Photo lifestyle"
                                class="absolute inset-0 object-cover object-center w-full h-full transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-110"
                                loading="lazy"
                                decoding="async"
                            />
                        </div>
                    @endif

                    <!-- Title at Top -->
                    <div class="absolute inset-x-0 top-0 bg-red-700 p-4 transform -translate-y-[calc(100%+1px)] group-hover:translate-y-0 transition-transform duration-300 z-20">
                        <h3 class="font-mono text-xl font-bold text-white">{{ strtoupper($jewel->name) }}</h3>
                    </div>

                    <!-- Price Tag -->
                    @if(isset($jewel->price))
                        <div class="absolute z-30 top-4 right-4">
                            <div x-data="{ width: '3rem', showPrice: false }"
                                 x-on:mouseenter="width = '7rem'; showPrice = true"
                                 x-on:mouseleave="width = '3rem'; showPrice = false"
                                 class="relative overflow-hidden bg-white border-4 border-black group-hover:border-red-700 transition-all duration-300 h-[3rem] min-w-[3rem]"
                                 :style="{ width: width }">
                                <!-- Symbol € -->
                                <div class="absolute inset-0 flex items-center justify-center transition-transform duration-300 transform bg-white"
                                     :class="{ 'translate-x-full': showPrice }">
                                    <span class="font-mono text-xl font-bold">€</span>
                                </div>
                                <!-- Full Price -->
                                <div class="absolute inset-0 flex items-center justify-center bg-white transform translate-x-[200%] transition-transform duration-300"
                                     :class="{ 'translate-x-0': showPrice, 'translate-x-[200%]': !showPrice }">
                                    <span class="font-mono text-xl font-bold whitespace-nowrap">{{ number_format($jewel->price, 0) }}€</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Badges at bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-red-700 bg-opacity-0 group-hover:bg-opacity-100 transition-all duration-500 transform translate-y-[calc(100%+1px)] group-hover:translate-y-0 z-20">
                        <div class="flex flex-wrap gap-2 transition-opacity duration-500 opacity-0 group-hover:opacity-100"
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
                <div class="flex items-center justify-center w-full h-full bg-black">
                    <span class="font-mono text-lg text-white">
                        {{ $jewel ? 'NO IMAGE' : 'JEWEL NOT FOUND' }}
                    </span>
                </div>
            @endif
        </div>
    </a>
</div>
