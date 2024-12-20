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
            <div class="flex flex-col lg:flex-row h-screen">
                <!-- Image Section -->
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
                }" class="relative w-full lg:w-2/3 h-[60vh] lg:h-screen">
                    <!-- Images -->
                    <div class="w-full h-full">
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
                                     class="w-full h-full object-cover"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Arrows -->
                    @if(count($images) > 1)
                        <button @click="prev" class="absolute left-4 lg:left-8 top-1/2 transform -translate-y-1/2 font-mono text-4xl lg:text-6xl text-white hover:text-gray-300 z-10">←</button>
                        <button @click="next" class="absolute right-4 lg:right-8 top-1/2 transform -translate-y-1/2 font-mono text-4xl lg:text-6xl text-white hover:text-gray-300 z-10">→</button>
                        
                        <!-- Slide Counter -->
                        <div class="absolute bottom-4 lg:bottom-8 left-4 lg:left-8 font-mono text-lg lg:text-xl text-white">
                            <span x-text="activeSlide + 1"></span>/<span x-text="totalSlides"></span>
                        </div>
                    @endif
                </div>

                <!-- Content Section -->
                <div class="w-full lg:w-1/3 lg:border-l border-white overflow-y-auto">
                    <div class="h-full flex flex-col">
                        <!-- Back Button -->
                        <div class="border-t lg:border-t-0 border-b border-white">
                            <a href="/jewels" class="block font-mono text-white hover:text-gray-300 p-4 lg:p-8">
                                ← BACK
                            </a>
                        </div>

                        <!-- Jewel Info -->
                        <div class="p-4 lg:p-8 flex-grow">
                            <h1 class="text-4xl lg:text-5xl font-mono mb-8">{{ strtoupper($jewel->name) }}</h1>
                            
                            @if($jewel->material)
                                <div class="mb-8">
                                    <h2 class="font-mono text-gray-400 mb-2">MATERIAL</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(json_decode($jewel->material) as $material)
                                            <span class="font-mono text-sm bg-white text-black px-3 py-1 border-2 border-white hover:bg-black hover:text-white transition-colors duration-300">
                                                {{ strtoupper($material) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jewel->type)
                                <div class="mb-8">
                                    <h2 class="font-mono text-gray-400 mb-2">TYPE</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(json_decode($jewel->type) as $type)
                                            <span class="font-mono text-sm bg-black text-white px-3 py-1 border-2 border-white hover:bg-white hover:text-black transition-colors duration-300">
                                                {{ strtoupper($type) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jewel->collection)
                                <div class="font-mono mb-8">
                                    <h2 class="text-gray-400 mb-2">COLLECTION</h2>
                                    <a href="/collections/{{ $jewel->collection->id }}" class="text-white hover:text-gray-300 underline">
                                        {{ strtoupper($jewel->collection->name) }}
                                    </a>
                                </div>
                            @endif

                            @if($jewel->description)
                                <div class="mb-8">
                                    <h2 class="font-mono text-gray-400 mb-2">DESCRIPTION</h2>
                                    <div class="font-mono text-gray-300 space-y-4">
                                        {!! nl2br($jewel->description) !!}
                                    </div>
                                </div>
                            @endif

                            @if($jewel->price)
                                <div class="mb-8">
                                    <h2 class="font-mono text-gray-400 mb-2">PRICE</h2>
                                    <div class="font-mono text-2xl">
                                        €{{ number_format($jewel->price, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
