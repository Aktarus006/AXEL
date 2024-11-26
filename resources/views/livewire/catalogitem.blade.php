<?php
use App\Models\Jewel;
use function Livewire\Volt\{state};

state([
    "key" => "",
    "jewel" => null,
    "mediaUrl" => "",
]);
?>

<div class="relative w-full h-full">
    @if($jewel)
        <a href="/jewels/{{ $jewel->id }}" class="block w-full h-full">
            <!-- Main Image with Brutalist Treatment -->
            <div class="relative w-full h-full overflow-hidden">
                @if($mediaUrl)
                    <img 
                        src="{{ $mediaUrl }}" 
                        alt="{{ $jewel->name }}" 
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 group-hover:grayscale"
                    >
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 font-mono text-lg">NO IMAGE</span>
                    </div>
                @endif
                
                <!-- Brutalist Overlay Elements -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <!-- Price Tag - Top Right -->
                    @if($jewel->price > 0)
                        <div class="absolute top-0 right-0 bg-black text-white p-4 font-mono text-xl">
                            ${{ number_format($jewel->price, 2) }}
                        </div>
                    @endif

                    <!-- Material Tag - Top Left -->
                    @if($jewel->material)
                        <div class="absolute top-0 left-0 bg-white text-black p-4 font-mono text-sm uppercase">
                            {{ $jewel->material }}
                        </div>
                    @endif

                    <!-- Name and Details - Bottom -->
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-90 p-6">
                        <h3 class="font-mono text-2xl text-white uppercase mb-2 tracking-tight">
                            {{ $jewel->name }}
                        </h3>
                        
                        <!-- Type Tags -->
                        @if($jewel->type)
                            <div class="flex flex-wrap gap-2">
                                @foreach($jewel->type as $type)
                                    <span class="bg-white text-black px-3 py-1 text-xs font-mono uppercase">
                                        {{ $type }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @else
        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
            <span class="text-gray-400 font-mono">JEWEL NOT FOUND</span>
        </div>
    @endif
</div>
