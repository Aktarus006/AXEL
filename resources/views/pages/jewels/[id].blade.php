@php
use App\Models\Jewel;
use function Livewire\Volt\{state};

$jewel = Jewel::with(['media', 'collection'])->find(request()->route('id'));

state([
    'jewel' => fn() => $jewel,
    'currentImageIndex' => 0,
]);
@endphp

<x-layouts.app>
    <div class="min-h-screen bg-black text-white">
        @if($jewel)
            <!-- Image Slider -->
            <div 
                x-data="{ 
                    currentIndex: @entangle('currentImageIndex'),
                    images: {{ json_encode($jewel->getMedia('jewels/images')->map(fn($media) => $media->getUrl())) }},
                    next() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    },
                    prev() {
                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                    }
                }"
                class="relative w-full h-screen"
            >
                <!-- Images -->
                <template x-for="(image, index) in images" :key="index">
                    <div
                        x-show="currentIndex === index"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0"
                    >
                        <img 
                            :src="image" 
                            class="w-full h-full object-contain bg-black"
                            alt="{{ $jewel->name }}"
                        >
                    </div>
                </template>

                <!-- Navigation Buttons -->
                <template x-if="images.length > 1">
                    <div>
                        <!-- Previous Button -->
                        <button 
                            @click="prev"
                            class="absolute left-0 top-1/2 -translate-y-1/2 px-4 py-8 bg-black bg-opacity-50 text-white hover:bg-white hover:text-black transition-colors duration-200 font-mono"
                        >
                            [ PREV ]
                        </button>

                        <!-- Next Button -->
                        <button 
                            @click="next"
                            class="absolute right-0 top-1/2 -translate-y-1/2 px-4 py-8 bg-black bg-opacity-50 text-white hover:bg-white hover:text-black transition-colors duration-200 font-mono"
                        >
                            [ NEXT ]
                        </button>

                        <!-- Image Counter -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 font-mono text-sm">
                            [ <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span> ]
                        </div>
                    </div>
                </template>
            </div>

            <!-- Jewel Details -->
            <div class="w-full border-t-4 border-white">
                <div class="container mx-auto px-4 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <h1 class="font-mono text-4xl">{{ strtoupper($jewel->name) }}</h1>
                            
                            @if($jewel->collection)
                                <div class="font-mono">
                                    COLLECTION: {{ strtoupper($jewel->collection->name) }}
                                </div>
                            @endif

                            @if($jewel->material)
                                <div class="font-mono">
                                    MATERIAL: {{ strtoupper($jewel->material) }}
                                </div>
                            @endif

                            @if($jewel->type)
                                <div class="font-mono">
                                    TYPE: {{ strtoupper($jewel->type) }}
                                </div>
                            @endif
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            @if($jewel->description)
                                <div class="font-mono whitespace-pre-line">
                                    {{ $jewel->description }}
                                </div>
                            @endif

                            @if($jewel->price)
                                <div class="font-mono text-2xl">
                                    PRICE: {{ $jewel->price }} €
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-8">
                        <a 
                            href="{{ url()->previous() }}" 
                            class="inline-block border-2 border-white px-6 py-2 font-mono hover:bg-white hover:text-black transition-colors duration-200"
                        >
                            [ BACK ]
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="h-screen flex items-center justify-center">
                <div class="font-mono text-2xl">JEWEL NOT FOUND</div>
            </div>
        @endif
    </div>
</x-layouts.app>
