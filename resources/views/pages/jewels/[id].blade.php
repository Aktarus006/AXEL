@php
use App\Models\Jewel;

$jewel = Jewel::with(['media', 'collection'])->find(request()->route('id'));
@endphp

<x-layouts.app>
    <div class="min-h-screen bg-black text-white relative">
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
            <!-- Back Button -->
            <div class="fixed top-8 left-8 z-50">
                <a href="/jewels" class="font-mono text-white hover:text-gray-300 border-2 border-white px-4 py-2 bg-black/80 backdrop-blur-sm">
                    ← BACK
                </a>
            </div>

            <!-- Main Content -->
            <div class="w-full">
                <!-- Title Section -->
                <div class="container mx-auto px-4 mb-12 pt-24">
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
                }" class="w-full flex">
                    <!-- Main Image -->
                    <div class="w-3/4 h-[90vh] relative border-r border-white">
                        @foreach($images as $index => $media)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0">
                                <img src="{{ $media->getUrl('large') }}" 
                                     alt="{{ $jewel->name }}" 
                                     class="w-full h-full object-contain"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- Thumbnails -->
                    <div class="w-1/4 h-[90vh] overflow-y-auto border-l border-white">
                        @foreach($images as $index => $media)
                            <button 
                                @click="activeSlide = {{ $index }}"
                                class="w-full aspect-square relative group border-b border-white last:border-b-0"
                                :class="{ 'opacity-50': activeSlide !== {{ $index }} }"
                            >
                                <img 
                                    src="{{ $media->getUrl('thumbnail') }}" 
                                    alt="{{ $jewel->name }}" 
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                                <div 
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300"
                                    :class="{ 'bg-white bg-opacity-10': activeSlide === {{ $index }} }"
                                ></div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Navigation Arrows (only visible on main image hover) -->
                    @if(count($images) > 1)
                        <button @click="prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 font-mono text-4xl text-white hover:text-gray-300 bg-black/50 px-4 py-2 backdrop-blur-sm opacity-0 hover:opacity-100 transition-opacity duration-300">←</button>
                        <button @click="next" class="absolute right-[calc(25%+1rem)] top-1/2 transform -translate-y-1/2 font-mono text-4xl text-white hover:text-gray-300 bg-black/50 px-4 py-2 backdrop-blur-sm opacity-0 hover:opacity-100 transition-opacity duration-300">→</button>
                        
                        <!-- Slide Counter -->
                        <div class="absolute bottom-4 left-[37.5%] transform -translate-x-1/2 font-mono text-white bg-black/50 px-4 py-2 backdrop-blur-sm">
                            <span x-text="activeSlide + 1"></span>/<span x-text="totalSlides"></span>
                        </div>
                    @endif
                </div>

                <!-- Details Section -->
                <div class="container mx-auto px-4 py-12">
                    @if($jewel->description)
                        <div class="mb-8">
                            <h2 class="text-2xl font-mono mb-4">DESCRIPTION</h2>
                            <p class="font-mono leading-relaxed">{{ $jewel->description }}</p>
                        </div>
                    @endif

                    @if($jewel->collection)
                        <div class="mb-8">
                            <h2 class="text-2xl font-mono mb-4">COLLECTION</h2>
                            <p class="font-mono">{{ strtoupper($jewel->collection->name) }}</p>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h2 class="text-2xl font-mono mb-4">PRICE</h2>
                        <p class="font-mono text-2xl">€{{ number_format($jewel->price, 2) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
