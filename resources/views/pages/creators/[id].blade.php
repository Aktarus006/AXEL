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
            <!-- Creator Header - Brutalist Rectangles, Date Left, Hover Image Right -->
            <div class="relative w-full h-[200px] md:h-[300px] border-b-[12px] border-white flex items-stretch overflow-hidden">
                <!-- Left: Date Block -->
                <!-- Left: First Name Vertical Block -->
                <div class="flex flex-col justify-center items-center w-16 md:w-24 border-r-[8px] border-white h-full bg-black">
                    <span class="text-white font-mono text-2xl md:text-4xl font-black tracking-tight rotate-180 md:rotate-0 md:vertical-lr italic" style="writing-mode: vertical-lr; letter-spacing:0.01em;">
                        {{ $creator->first_name }}
                    </span>
                </div>
                <!-- Center: Last Name Block (max space, right aligned, italic on hover) -->
                <div class="flex-1 flex flex-col justify-center border-r-[8px] border-white h-full bg-black group">
                    <span class="block w-full text-[16vw] md:text-[12rem] font-black uppercase leading-none tracking-tight text-white text-right pb-2 pr-3 transition-all duration-300 group-hover:italic" style="letter-spacing:-0.04em;">
                        {{ $creator->last_name }}
                    </span>
                </div>
                <!-- Right: Hover Image Block or Empty -->
                @php
                    $hover = $creator->getMedia('creators/profile')->where('name', 'avatar_hover')->first();
                @endphp
                @if($hover)
                <div class="flex items-center justify-center w-32 md:w-48 h-full bg-black">
                    <img src="{{ $hover->getUrl() }}" alt="{{ $creator->first_name }} {{ $creator->last_name }}" class="object-cover w-full h-full border-4 border-white bg-white" style="aspect-ratio: 1/1; max-height:100%; max-width:100%;" />
                </div>
                @else
                <div class="w-32 md:w-48 h-full bg-black"></div>
                @endif
            </div>

            <!-- Creator Content -->
            <div class="max-w-screen-xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Creator Image (Full Height, Left) -->
                    <div class="relative overflow-hidden border-4 border-white group h-full min-h-[400px] lg:min-h-[600px] flex lg:block items-stretch m-0 p-0">
                        @php
                            $main = $creator->getMedia('creators/profile')->first();
                            $hover = $creator->getMedia('creators/profile')->where('name', 'avatar_hover')->first();
                        @endphp
                        @if($main)
                            <img
                                src="{{ $main->getUrl() }}"
                                alt="{{ $creator->first_name }} {{ $creator->last_name }}"
                                class="object-cover w-full h-full min-h-[400px] lg:min-h-[600px] transition-all duration-500 filter grayscale group-hover:grayscale-0 group-hover:scale-110"
                                style="aspect-ratio: 3/4;"
                            >
                            @if($hover)
                                <img
                                    src="{{ $hover->getUrl() }}"
                                    alt="{{ $creator->first_name }} {{ $creator->last_name }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100"
                                    style="aspect-ratio: 3/4;"
                                >
                            @endif
                        @else
                            <div class="flex items-center justify-center w-full h-full text-5xl font-black text-black bg-white min-h-[400px] lg:min-h-[600px]" style="aspect-ratio: 3/4;">
                                {{ strtoupper($creator->first_name[0] . $creator->last_name[0]) }}
                            </div>
                        @endif
                        <div class="absolute inset-0 transition-opacity duration-500 bg-red-700 opacity-0 mix-blend-multiply group-hover:opacity-100"></div>
                    </div>

                    <!-- Creator Info (Brutalist, Responsive, Placeholder Fallbacks) -->
                    <div class="flex flex-col justify-between h-full m-0 p-0">
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="overflow-hidden p-6">
                                <div class="text-lg prose prose-invert max-w-none">
                                    {!! $creator->description ?? '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, urna eu tincidunt consectetur, nisi nisl aliquam nunc, eget aliquam massa nisl quis neque.</p>' !!}
                                </div>
                                <div class="mt-4 w-16 h-1 bg-white rounded mx-auto"></div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4 mt-8">
                            <div class="p-4 border-2 border-white">
                                <h2 class="mb-2 text-xl">WEBSITE</h2>
                                @if($creator->website_url)
                                    <a href="{{ $creator->website_url }}" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                        {{ $creator->website_url }}
                                    </a>
                                @else
                                    <span class="text-gray-400">No website available</span>
                                @endif
                            </div>
                            <div class="p-4 border-2 border-white">
                                <h2 class="mb-2 text-xl">COLLECTIONS</h2>
                                @if($creator->collections->isNotEmpty())
                                    <div class="space-y-2">
                                        @foreach($creator->collections as $collection)
                                            <a href="/collections/{{ $collection->id }}" class="block text-white hover:text-gray-300">
                                                {{ strtoupper($collection->name) }} →
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">No collections available</span>
                                @endif
                            </div>
                        </div>
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
