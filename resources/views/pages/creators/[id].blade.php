<?php
use App\Models\Creator;

$id = request()->segment(count(request()->segments()));
$creator = Creator::with(['media', 'collections'])->find($id);

// On s'assure que la variable est disponible dans la vue
view()->share('creator', $creator);
?>

@php
    // On récupère la variable dans la portée de la vue
    $creator = $creator ?? null;
@endphp

<x-layouts.app>
    <div class="min-h-screen font-mono text-white bg-black">
        @if(!$creator)
            <div class="flex items-center justify-center h-screen">
                <div class="text-center">
                    <h1 class="mb-4 font-mono text-2xl">CREATOR NOT FOUND</h1>
                    <a href="/" class="px-4 py-2 font-mono text-white border-2 border-white hover:text-gray-300">
                        BACK TO HOME
                    </a>
                </div>
            </div>
        @else
            <!-- Creator Header -->
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

                <!-- Creator Name -->
                <div class="relative z-10 px-8 py-32">
                    <div class="max-w-screen-xl mx-auto">
                        <h1 class="text-[8rem] font-black uppercase leading-none tracking-tighter"
                            x-show="showContent"
                            x-transition:enter="transition-transform duration-1000 delay-500"
                            x-transition:enter-start="-translate-x-full"
                            x-transition:enter-end="translate-x-0">
                            {{ $creator->first_name }}<br>{{ $creator->last_name }}
                        </h1>

                        @if($creator->date_of_birth)
                            <div class="mt-4 text-2xl"
                                 x-show="showContent"
                                 x-transition:enter="transition-transform duration-1000 delay-700"
                                 x-transition:enter-start="translate-x-full"
                                 x-transition:enter-end="translate-x-0">
                                {{ $creator->date_of_birth->format('Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Creator Content -->
            <div class="max-w-screen-xl p-8 mx-auto">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Creator Image -->
                    <div class="relative overflow-hidden border-4 border-white group"
                         x-data="{ showImage: false, isHovered: false }"
                         x-intersect="showImage = true"
                         @mouseenter="isHovered = true"
                         @mouseleave="isHovered = false">
                        @if($creator->hasMedia('creators/profile'))
                            <img
                                :src="isHovered && '{{ $creator->getMedia('creators/profile')->where('name', 'avatar_hover')->first()?->getUrl() }}' ? '{{ $creator->getMedia('creators/profile')->where('name', 'avatar_hover')->first()?->getUrl() }}' : '{{ $creator->getMedia('creators/profile')->where('name', 'avatar')->first()?->getUrl() }}'"
                                alt="{{ $creator->first_name }} {{ $creator->last_name }}"
                                class="object-cover w-full transition-all duration-500 aspect-square filter grayscale group-hover:grayscale-0 group-hover:scale-110"
                                x-show="showImage"
                                x-transition:enter="transition-transform duration-1000"
                                x-transition:enter-start="scale-150"
                                x-transition:enter-end="scale-100"
                            >
                        @else
                            <div class="flex items-center justify-center w-full text-xl font-bold text-black bg-white aspect-square">
                                {{ strtoupper($creator->first_name[0] . $creator->last_name[0]) }}
                            </div>
                        @endif

                        <!-- Hover Effect -->
                        <div class="absolute inset-0 transition-opacity duration-500 bg-red-700 opacity-0 mix-blend-multiply group-hover:opacity-100"></div>
                    </div>

                    <!-- Creator Info -->
                    <div class="space-y-8"
                         x-data="{ showInfo: false }"
                         x-intersect="showInfo = true">
                        @if($creator->description)
                            <div class="overflow-hidden">
                                <div class="text-lg prose prose-invert max-w-none"
                                     x-show="showInfo"
                                     x-transition:enter="transition-transform duration-1000"
                                     x-transition:enter-start="translate-y-full"
                                     x-transition:enter-end="translate-y-0">
                                    {!! $creator->description !!}
                                </div>
                            </div>
                        @endif

                        @if($creator->website_url)
                            <div class="overflow-hidden">
                                <div class="p-4 border-2 border-white"
                                     x-show="showInfo"
                                     x-transition:enter="transition-transform duration-1000 delay-200"
                                     x-transition:enter-start="translate-y-full"
                                     x-transition:enter-end="translate-y-0">
                                    <h2 class="mb-2 text-xl">WEBSITE</h2>
                                    <a href="{{ $creator->website_url }}" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                        {{ $creator->website_url }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($creator->collections->isNotEmpty())
                            <div class="overflow-hidden">
                                <div class="p-4 border-2 border-white"
                                     x-show="showInfo"
                                     x-transition:enter="transition-transform duration-1000 delay-400"
                                     x-transition:enter-start="translate-y-full"
                                     x-transition:enter-end="translate-y-0">
                                    <h2 class="mb-2 text-xl">COLLECTIONS</h2>
                                    <div class="space-y-2">
                                        @foreach($creator->collections as $collection)
                                            <a href="/collections/{{ $collection->id }}" class="block text-white hover:text-gray-300">
                                                {{ strtoupper($collection->name) }} →
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-16 overflow-hidden text-center">
                    <a href="/"
                       class="inline-block px-8 py-4 text-xl transition-colors duration-300 border-4 border-white hover:bg-white hover:text-black"
                       x-data="{ showButton: false }"
                       x-intersect="showButton = true"
                       x-show="showButton"
                       x-transition:enter="transition-transform duration-1000"
                       x-transition:enter-start="translate-y-full"
                       x-transition:enter-end="translate-y-0">
                        ← BACK TO HOME
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
