<?php

use App\Models\Jewel;
use function Livewire\Volt\{state, mount, layout};

state(["jewel" => null, "media" => []]);

layout("components.layouts.app");

mount(function ($id) {
    $this->jewel = Jewel::find($id);
    $this->media = $this->jewel->getMedia("jewels/images");
});
?>
@volt
<x-layouts.app>
    <div class="min-h-screen bg-black text-white">
        <!-- Hero Section -->
        <div class="relative h-screen">
            @php
                $media = $jewel->getMedia('jewels/images');
                $heroImage = $media->isNotEmpty() ? $media->first()->getUrl('large') : null;
            @endphp
            
            @if($heroImage)
                <div class="absolute inset-0">
                    <img 
                        src="{{ $heroImage }}" 
                        alt="{{ $jewel->name }}" 
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            @endif

            <!-- Hero Content -->
            <div class="absolute inset-0 flex flex-col justify-between p-8">
                <div class="w-full flex justify-between items-start">
                    <h1 class="font-mono text-6xl md:text-8xl tracking-tighter">
                        {{ $jewel->name }}
                    </h1>
                    @if($jewel->price)
                        <div class="bg-white text-black p-4 font-mono text-2xl">
                            €{{ number_format($jewel->price, 2) }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-end">
                    @if($jewel->creator)
                        <div class="font-mono text-xl">
                            BY {{ strtoupper($jewel->creator->first_name . ' ' . $jewel->creator->last_name) }}
                        </div>
                    @endif
                    @if($jewel->collection)
                        <div class="font-mono text-xl">
                            FROM {{ strtoupper($jewel->collection->name) }} COLLECTION
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="w-full border-t-2 border-white">
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Details -->
                    <div class="space-y-8">
                        @if($jewel->description)
                            <div>
                                <h2 class="font-mono text-2xl mb-4 uppercase">Description</h2>
                                <div class="font-mono text-lg leading-relaxed">
                                    {{ $jewel->description }}
                                </div>
                            </div>
                        @endif

                        @if($jewel->material)
                            <div>
                                <h2 class="font-mono text-2xl mb-4 uppercase">Materials</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($jewel->material as $material)
                                        <span class="bg-white text-black px-4 py-2 font-mono">
                                            {{ $material }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($jewel->type)
                            <div>
                                <h2 class="font-mono text-2xl mb-4 uppercase">Type</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($jewel->type as $type)
                                        <span class="border border-white px-4 py-2 font-mono">
                                            {{ $type }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Gallery -->
                    <div class="grid grid-cols-2 gap-[1px] bg-white">
                        @foreach($media->slice(1) as $image)
                            <div class="aspect-square bg-black">
                                <img 
                                    src="{{ $image->getUrl('medium') }}" 
                                    alt="{{ $jewel->name }}" 
                                    class="w-full h-full object-cover hover:opacity-90 transition-opacity"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Jewels -->
        @if($jewel->collection && $jewel->collection->jewels->count() > 1)
            <div class="w-full border-t-2 border-white">
                <div class="container mx-auto px-4 py-16">
                    <h2 class="font-mono text-4xl mb-8 uppercase">More from {{ $jewel->collection->name }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[1px]">
                        @foreach($jewel->collection->jewels->where('id', '!=', $jewel->id)->take(4) as $relatedJewel)
                            @php
                                $relatedMedia = $relatedJewel->getMedia('jewels/images');
                                $relatedImage = $relatedMedia->isNotEmpty() ? 
                                    $relatedMedia->first()->getUrl('thumbnail') : null;
                            @endphp
                            <div class="relative h-[300px] group">
                                <a href="{{ url('/jewels/' . $relatedJewel->id) }}" class="block w-full h-full">
                                    @if($relatedImage)
                                        <img 
                                            src="{{ $relatedImage }}" 
                                            alt="{{ $relatedJewel->name }}" 
                                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all"
                                        >
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-80 transition-all flex items-center justify-center">
                                        <span class="font-mono text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ $relatedJewel->name }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
@endvolt
