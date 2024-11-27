@volt
<?php
use function Livewire\Volt\{state, computed};
use App\Models\Jewel;

state(['jewel' => null]);

$loadJewel = computed(function () {
    return Jewel::with(['media', 'collection'])->find(request()->route('id'));
});
?>

<x-layouts.app>
    <div class="min-h-screen bg-black text-white">
        @if(!$loadJewel)
            <div class="flex items-center justify-center h-screen">
                <div class="text-center">
                    <h1 class="text-4xl font-mono mb-4">JEWEL NOT FOUND</h1>
                    <a href="/jewels" class="text-white hover:text-gray-300 font-mono border-2 border-white px-4 py-2">
                        BACK TO CATALOG
                    </a>
                </div>
            </div>
        @else
            <!-- Back Button -->
            <div class="fixed top-4 left-4 z-50">
                <a href="/jewels" class="font-mono text-white hover:text-gray-300 border-2 border-white px-4 py-2">
                    ← BACK
                </a>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 py-8">
                <!-- Title Section -->
                <div class="mb-12 pt-16">
                    <h1 class="text-5xl font-mono mb-4">{{ strtoupper($loadJewel->name) }}</h1>
                    <div class="flex flex-wrap gap-2 mt-4">
                        @if($loadJewel->material)
                            <span class="font-mono text-sm bg-white text-black px-3 py-1 border-2 border-white hover:bg-black hover:text-white transition-colors duration-300">
                                {{ strtoupper($loadJewel->material) }}
                            </span>
                        @endif
                        @if($loadJewel->type)
                            <span class="font-mono text-sm bg-black text-white px-3 py-1 border-2 border-white hover:bg-white hover:text-black transition-colors duration-300">
                                {{ strtoupper($loadJewel->type) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Image Gallery -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($loadJewel->getMedia('jewels/images') as $media)
                        <div class="aspect-square overflow-hidden border-2 border-white">
                            <img 
                                src="{{ $media->getUrl('thumbnail') }}" 
                                alt="{{ $loadJewel->name }}" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                    @endforeach
                </div>

                <!-- Details Section -->
                <div class="mt-12">
                    @if($loadJewel->description)
                        <div class="mb-8">
                            <h2 class="text-2xl font-mono mb-4">DESCRIPTION</h2>
                            <p class="font-mono leading-relaxed">{{ $loadJewel->description }}</p>
                        </div>
                    @endif

                    @if($loadJewel->collection)
                        <div class="mb-8">
                            <h2 class="text-2xl font-mono mb-4">COLLECTION</h2>
                            <p class="font-mono">{{ strtoupper($loadJewel->collection->name) }}</p>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h2 class="text-2xl font-mono mb-4">PRICE</h2>
                        <p class="font-mono text-2xl">€{{ number_format($loadJewel->price, 2) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
