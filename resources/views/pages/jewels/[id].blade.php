@php
use App\Models\Jewel;

$jewel = Jewel::with(['media', 'collection'])->find(request()->route('id'));
@endphp

<x-layouts.app>
    <div class="min-h-screen bg-black text-white">
        @if(!$jewel)
            <div class="flex items-center justify-center h-screen">
                <div class="text-center">
                    <h1 class="text-4xl font-mono mb-4">JEWEL NOT FOUND</h1>
                    <a href="/jewels" class="text-white hover:text-gray-300 font-mono border-2 border-white px-4 py-2">
                        BACK TO CATALOG
                    </a>
                </div>
            </div>
        @else
            <!-- Main Content -->
            <div class="w-full">
                <!-- Back Button and Title Section -->
                <div class="container mx-auto px-4 mb-8 pt-24">
                    <div class="mb-8">
                        <a href="/jewels" class="inline-block font-mono text-white hover:text-gray-300 border-2 border-white px-4 py-2">
                            ← BACK
                        </a>
                    </div>
                    <h1 class="text-5xl font-mono mb-4">{{ strtoupper($jewel->name) }}</h1>
                    <div class="flex flex-wrap gap-2 mt-4">
                        @if($jewel->material)
                            <span class="font-mono text-sm bg-white text-black px-3 py-1 border-2 border-white hover:bg-black hover:text-white transition-colors duration-300">
                                {{ strtoupper($jewel->material) }}
                            </span>
                        @endif
                        @if($jewel->type)
                            <span class="font-mono text-sm bg-black text-white px-3 py-1 border-2 border-white hover:bg-white hover:text-black transition-colors duration-300">
                                {{ strtoupper($jewel->type) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Image Slider -->
                @php
                    $images = $jewel->getMedia('jewels/images');
                @endphp
                <div x-data="{ 
                    activeSlide: 0,
                    totalSlides: {{ count($images) }},
                    next() { 
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                }" class="relative w-full">
                    <!-- Images -->
                    <div class="w-full h-[80vh] relative overflow-hidden">
                        @foreach($images as $index => $media)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-x-full"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 transform translate-x-0"
                                 x-transition:leave-end="opacity-0 transform -translate-x-full"
                                 class="absolute inset-0">
                                <img src="{{ $media->getUrl('large') }}" 
                                     alt="{{ $jewel->name }}" 
                                     class="w-full h-full object-contain"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Arrows -->
                    @if(count($images) > 1)
                        <div class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none">
                            <button @click="prev" class="pointer-events-auto font-mono text-4xl text-white hover:text-gray-300 bg-black/50 px-6 py-4 backdrop-blur-sm transition-all duration-300 hover:bg-black/70">←</button>
                            <button @click="next" class="pointer-events-auto font-mono text-4xl text-white hover:text-gray-300 bg-black/50 px-6 py-4 backdrop-blur-sm transition-all duration-300 hover:bg-black/70">→</button>
                        </div>
                        
                        <!-- Slide Counter -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 font-mono text-white bg-black/50 px-4 py-2 backdrop-blur-sm">
                            <span x-text="activeSlide + 1"></span>/<span x-text="totalSlides"></span>
                        </div>
                    @endif
                </div>

                <!-- Collection Link -->
                @if($jewel->collection)
                    <div class="container mx-auto px-4 py-12">
                        <div class="font-mono">
                            <span class="text-gray-400">Collection: </span>
                            <a href="/collections/{{ $jewel->collection->id }}" class="text-white hover:text-gray-300 underline">
                                {{ strtoupper($jewel->collection->name) }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layouts.app>
