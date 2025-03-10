@volt
@php
use App\Models\Collection;

$collection = Collection::with(['jewels', 'creators'])->find(request()->route('id'));
@endphp

<x-layouts.app>
    <div class="min-h-screen text-white bg-black">
        @if(!$collection)
            <div class="flex items-center justify-center h-screen">
                <div class="text-center">
                    <h1 class="mb-4 font-mono text-2xl">COLLECTION NOT FOUND</h1>
                    <a href="/collections" class="px-4 py-2 font-mono text-white border-2 border-white hover:text-gray-300">
                        BACK TO COLLECTIONS
                    </a>
                </div>
            </div>
        @else
            <!-- Title Section -->
            <div class="relative w-full overflow-hidden border-b-8 border-white"
                 x-data="{ showContent: false }"
                 x-init="setTimeout(() => showContent = true, 100)">

                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-black"
                     x-show="showContent"
                     x-transition:enter="transition-transform duration-1000"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0">
                    <div class="absolute inset-0 opacity-20">
                        @for ($i = 0; $i < 50; $i++)
                            <div class="absolute border border-white"
                                 style="
                                    left: {{ rand(0, 100) }}%;
                                    top: {{ rand(0, 100) }}%;
                                    width: {{ rand(10, 100) }}px;
                                    height: {{ rand(10, 100) }}px;
                                    transform: rotate({{ rand(0, 360) }}deg);
                                 ">
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Title -->
                <div class="relative z-10 px-8 py-32">
                    <div class="max-w-screen-xl mx-auto">
                        <h1 class="text-[8rem] font-black uppercase leading-none tracking-tighter"
                            x-show="showContent"
                            x-transition:enter="transition-transform duration-1000 delay-500"
                            x-transition:enter-start="-translate-x-full"
                            x-transition:enter-end="translate-x-0">
                            {{ $collection->name }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="w-full border-b-4 border-white">
                <div class="max-w-screen-xl p-8 mx-auto">
                    <a href="/collections"
                       class="inline-block px-8 py-4 font-mono text-xl text-white transition-colors duration-300 border-4 border-white hover:bg-white hover:text-black"
                       x-data
                       x-transition:enter="transition-transform duration-500"
                       x-transition:enter-start="-translate-x-full"
                       x-transition:enter-end="translate-x-0">
                        ← BACK TO COLLECTIONS
                    </a>
                </div>
            </div>

            <div class="max-w-screen-xl px-8 mx-auto mt-12">
                @if($collection->hasMedia('collections/images'))
                    <div class="relative mb-8 overflow-hidden border-4 border-white group"
                         x-data="{ showImage: false }"
                         x-intersect="showImage = true">
                        <img src="{{ $collection->getFirstMediaUrl('collections/images') }}"
                             alt="{{ $collection->name }}"
                             class="object-cover w-full transition-all duration-500 grayscale group-hover:grayscale-0 group-hover:scale-110"
                             x-show="showImage"
                             x-transition:enter="transition-transform duration-1000"
                             x-transition:enter-start="scale-150"
                             x-transition:enter-end="scale-100">
                        <div class="absolute inset-0 transition-opacity duration-500 bg-red-700 opacity-0 mix-blend-multiply group-hover:opacity-100"></div>
                    </div>
                @endif

                @if($collection->creators->isNotEmpty())
                    <div class="p-8 mb-8 overflow-hidden border-4 border-white"
                         x-data="{ show: false }"
                         x-intersect="show = true">
                        <h2 class="mb-4 text-2xl font-bold">CRÉATEURS</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                             x-show="show"
                             x-transition:enter="transition-transform duration-1000"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0">
                            @foreach($collection->creators as $creator)
                                <a href="/creators/{{ $creator->id }}"
                                   class="block p-4 transition-colors duration-300 border-2 border-white hover:bg-white hover:text-black">
                                    <h3 class="text-xl font-bold">{{ $creator->name }}</h3>
                                    @if($creator->description)
                                        <p class="mt-2 text-sm">
                                            {{ Str::limit(strip_tags($creator->description), 100) }}
                                        </p>
                                    @endif
                                    <div class="mt-2 text-sm">
                                        VOIR LE CRÉATEUR →
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($collection->description)
                    <div class="p-8 mb-8 overflow-hidden border-4 border-white"
                         x-data="{ show: false }"
                         x-intersect="show = true">
                        <div class="font-mono text-xl prose prose-invert max-w-none"
                             x-show="show"
                             x-transition:enter="transition-transform duration-1000"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0">
                            {!! $collection->description !!}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($collection->jewels as $index => $jewel)
                        <div class="overflow-hidden"
                             x-data="{ show: false }"
                             x-intersect="show = true">
                            <a href="/jewels/{{ $jewel->id }}" class="block">
                                <div class="relative overflow-hidden border-4 border-white group"
                                     x-show="show"
                                     x-transition:enter="transition-transform duration-500"
                                     x-transition:enter-start="translate-y-full"
                                     x-transition:enter-end="translate-y-0"
                                     style="transition-delay: {{ $index * 100 }}ms">
                                    @if($jewel->hasMedia('jewels/packshots'))
                                        <img src="{{ $jewel->getFirstMediaUrl('jewels/packshots') }}"
                                             alt="{{ $jewel->name }}"
                                             class="object-cover w-full transition-all duration-500 aspect-square grayscale group-hover:grayscale-0 group-hover:scale-110">
                                        <div class="absolute inset-0 transition-opacity duration-500 bg-red-700 opacity-0 mix-blend-multiply group-hover:opacity-100"></div>
                                    @endif
                                    <div class="absolute inset-x-0 bottom-0 p-4 font-mono text-xl text-white transition-transform duration-300 transform translate-y-full bg-black group-hover:translate-y-0">
                                        {{ strtoupper($jewel->name) }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
