<?php
use App\Models\Jewel;
use Illuminate\Support\Str;

$id = request()->segment(count(request()->segments()));
$jewel = Jewel::with(['media', 'collections'])->find($id);

// On s'assure que les variables sont disponibles dans la vue
view()->share('jewel', $jewel);

// Récupérer les autres bijoux de la même collection
$relatedJewels = collect([]);
if ($jewel) {
    foreach ($jewel->collections as $collection) {
        $relatedJewels = $relatedJewels->merge(
            $collection->jewels()
                ->where('jewels.id', '!=', $jewel->id)
                ->with('media')
                ->get()
        );
    }
    $relatedJewels = $relatedJewels->unique('id');
}
view()->share('relatedJewels', $relatedJewels);
?>

@php
    // On récupère les variables dans la portée de la vue
    $jewel = $jewel ?? null;
    $relatedJewels = $relatedJewels ?? collect([]);
@endphp

<x-layouts.app>
    <div class="min-h-screen text-white bg-black">
        @if(!$jewel)
            <div class="flex items-center justify-center h-screen">
                <div class="text-center">
                    <h1 class="mb-4 font-mono text-2xl">JEWEL NOT FOUND</h1>
                    <a href="/jewels" class="px-4 py-2 font-mono text-white border-2 border-white hover:text-gray-300">
                        BACK TO CATALOG
                    </a>
                </div>
            </div>
        @else
            <!-- Main Content -->
            <div class="flex flex-col h-screen lg:flex-row">
                <!-- Image Section -->
                @php
                    $packshots = $jewel->getMedia('jewels/packshots');
                    $lifestyles = $jewel->getMedia('jewels/lifestyle');
                    $allImages = $packshots->merge($lifestyles);
                @endphp
                <div x-data="{
                    activeSlide: 0,
                    totalSlides: {{ count($allImages) }},
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                }" class="relative w-full lg:w-2/3 h-[60vh] lg:h-screen">
                    <!-- Images -->
                    <div class="w-full h-full">
                        @foreach($allImages as $index => $media)
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
                                     class="object-cover w-full h-full"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Arrows -->
                    @if(count($allImages) > 1)
                        <button @click="prev" class="absolute z-10 font-mono text-4xl text-white transform -translate-y-1/2 left-4 lg:left-8 top-1/2 lg:text-6xl hover:text-gray-300">←</button>
                        <button @click="next" class="absolute z-10 font-mono text-4xl text-white transform -translate-y-1/2 right-4 lg:right-8 top-1/2 lg:text-6xl hover:text-gray-300">→</button>

                        <!-- Slide Counter -->
                        <div class="absolute font-mono text-lg text-white bottom-4 lg:bottom-8 left-4 lg:left-8 lg:text-xl">
                            <span x-text="activeSlide + 1"></span>/<span x-text="totalSlides"></span>
                        </div>
                    @endif
                </div>

                <!-- Content Section -->
                <div class="w-full overflow-y-auto border-white lg:w-1/3 lg:border-l">
                    <div class="flex flex-col h-full">
                        <!-- Back Button -->
                        <div class="border-t border-b border-white lg:border-t-0">
                            <a href="/jewels" class="block p-4 font-mono text-white hover:text-gray-300 lg:p-8">
                                ← BACK
                            </a>
                        </div>

                        <!-- Jewel Info -->
                        <div class="flex-grow p-4 lg:p-8">
                            <h1 class="mb-8 font-mono text-2xl lg:text-3xl">{{ strtoupper($jewel->name) }}</h1>

                            @if($jewel->material)
                                <div class="mb-8">
                                    <h2 class="mb-2 font-mono text-gray-400">MATERIAL</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(json_decode($jewel->material) as $material)
                                            <span class="px-3 py-1 font-mono text-sm text-black transition-colors duration-300 bg-white border-2 border-white hover:bg-black hover:text-white">
                                                {{ strtoupper($material) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jewel->type)
                                <div class="mb-8">
                                    <h2 class="mb-2 font-mono text-gray-400">TYPE</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(json_decode($jewel->type) as $type)
                                            <span class="px-3 py-1 font-mono text-sm text-white transition-colors duration-300 bg-black border-2 border-white hover:bg-white hover:text-black">
                                                {{ strtoupper($type) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jewel->collections->isNotEmpty())
                                <div class="mb-8">
                                    <h2 class="mb-2 font-mono text-gray-400">COLLECTION{{ $jewel->collections->count() > 1 ? 'S' : '' }}</h2>
                                    <div class="space-y-4">
                                        @foreach($jewel->collections as $collection)
                                            <div class="p-4 border-2 border-white">
                                                <a href="/collections/{{ $collection->id }}" class="block">
                                                    <h3 class="font-mono text-xl text-white hover:text-gray-300">
                                                        {{ strtoupper($collection->name) }}
                                                    </h3>
                                                    @if($collection->description)
                                                        <p class="mt-2 font-mono text-sm text-gray-400">
                                                            {{ Str::limit($collection->description, 100) }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-2 font-mono text-sm text-white hover:text-gray-300">
                                                        VIEW COLLECTION →
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($jewel->description)
                                <div class="mb-8">
                                    <h2 class="mb-2 font-mono text-gray-400">DESCRIPTION</h2>
                                    <div class="space-y-4 font-mono text-gray-300">
                                        {!! $jewel->description !!}
                                    </div>
                                </div>
                            @endif

                            @if($jewel->price)
                                <div class="mb-8">
                                    <h2 class="mb-2 font-mono text-gray-400">PRICE</h2>
                                    <div class="font-mono text-2xl">
                                        €{{ number_format($jewel->price, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form Section -->
            @if($jewel->price)
                <div class="w-full border-t-4 border-white">
                    <div class="max-w-4xl p-8 mx-auto">
                        <livewire:jewel-contact-form :jewel="$jewel" />
                    </div>
                </div>
            @endif

            <!-- Related Jewels Slider -->
            @if($relatedJewels->isNotEmpty())
                <div class="w-full border-t-4 border-white">
                    <div class="p-8">
                        <h2 class="mb-8 font-mono text-2xl">AUTRES BIJOUX DE LA COLLECTION</h2>

                        <div class="relative" x-data="{
                            scroll: 0,
                            maxScroll: 0,
                            init() {
                                this.maxScroll = this.$refs.container.scrollWidth - this.$refs.container.clientWidth;
                                this.checkArrows();
                            },
                            scrollLeft() {
                                this.$refs.container.scrollBy({ left: -300, behavior: 'smooth' });
                                this.scroll = this.$refs.container.scrollLeft;
                                this.checkArrows();
                            },
                            scrollRight() {
                                this.$refs.container.scrollBy({ left: 300, behavior: 'smooth' });
                                this.scroll = this.$refs.container.scrollLeft;
                                this.checkArrows();
                            },
                            checkArrows() {
                                this.scroll = this.$refs.container.scrollLeft;
                                this.maxScroll = this.$refs.container.scrollWidth - this.$refs.container.clientWidth;
                            }
                        }" @resize.window="init()">
                            <!-- Navigation Arrows -->
                            <button
                                @click="scrollLeft"
                                class="absolute left-0 z-10 h-full px-4 font-mono text-4xl text-white transition-opacity duration-200 -translate-y-1/2 bg-black bg-opacity-50 top-1/2 hover:text-gray-300"
                                x-show="scroll > 0"
                                x-transition
                            >
                                ←
                            </button>

                            <button
                                @click="scrollRight"
                                class="absolute right-0 z-10 h-full px-4 font-mono text-4xl text-white transition-opacity duration-200 -translate-y-1/2 bg-black bg-opacity-50 top-1/2 hover:text-gray-300"
                                x-show="scroll < maxScroll"
                                x-transition
                            >
                                →
                            </button>

                            <!-- Jewels Container -->
                            <div
                                x-ref="container"
                                class="flex gap-8 pb-4 overflow-x-auto snap-x snap-mandatory hide-scrollbar"
                                @scroll="checkArrows"
                            >
                                @foreach($relatedJewels as $relatedJewel)
                                    <a
                                        href="/jewels/{{ $relatedJewel->id }}"
                                        class="flex-none w-72 snap-start group"
                                    >
                                        <div class="relative overflow-hidden border-4 border-white aspect-square">
                                            @php
                                                $packshot = $relatedJewel->getFirstMedia('jewels/packshots');
                                                $lifestyle = $relatedJewel->getFirstMedia('jewels/lifestyle');
                                            @endphp
                                            @if($packshot)
                                                <img
                                                    src="{{ $packshot->getUrl('medium') }}"
                                                    alt="{{ $relatedJewel->name }}"
                                                    class="absolute inset-0 z-10 object-cover w-full h-full transition-all duration-500 grayscale {{ $lifestyle ? 'group-hover:grayscale-0' : '' }}"
                                                    loading="lazy">
                                                @if($lifestyle)
                                                    <img src="{{ $lifestyle->getUrl('medium') }}"
                                                         alt="{{ $relatedJewel->name }} - Lifestyle"
                                                         class="absolute inset-0 object-cover w-full h-full transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-110"
                                                         loading="lazy">
                                                @endif
                                            @endif
                                        </div>
                                        <h3 class="mt-4 font-mono text-lg group-hover:text-gray-300">
                                            {{ strtoupper($relatedJewel->name) }}
                                        </h3>
                                        @if($relatedJewel->price)
                                            <p class="font-mono text-gray-400">
                                                €{{ number_format($relatedJewel->price, 2) }}
                                            </p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
