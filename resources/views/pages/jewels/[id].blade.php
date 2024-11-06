<?php

use App\Models\Jewel;
use function Livewire\Volt\{state, mount, layout};

state(["jewel" => null, "media" => []]);

layout("components.layouts.app");

mount(function ($id) {
    $this->jewel = Jewel::find($id);
    $this->media = $this->jewel->getMedia("jewels/images");
});
?>
@volt
<div>
<x-layouts.app>
       {{ $media->first->getUrl() }}
       <!-- Jewel Details Section -->
           <div class="w-full max-w-screen-md mx-auto py-10 px-8 ">
               <!-- Jewel Name -->
               <div class="bg-black text-white p-6">
                   <h1 class="text-4xl font-bold">{{ $jewel->name }}</h1>
               </div>

               <!-- Jewel Materials as Tags -->
                       <div class="bg-white text-black border-2 border-black p-6">
                           <h2 class="text-lg font-semibold mb-2">Materials:</h2>
                           <div class="flex flex-wrap gap-2">
                               @foreach ($jewel->material as $material)
                                   <span class="px-3 py-1 bg-gray-200 text-black x text-sm font-medium">
                                       {{ $material }}
                                   </span>
                               @endforeach
                           </div>
                       </div>

               <!-- Jewel Price -->
               <div class="bg-black text-white p-6 flex justify-between items-center">
                   <span class="text-lg font-semibold">Price:</span>
                   <span class="text-2xl font-bold">${{ number_format($jewel->price, 2) }}</span>
               </div>

               <!-- Jewel Description -->
               <div class="bg-white text-black border-2 border-black p-6">
                   <h2 class="text-2xl font-bold mb-4">Description</h2>
                   <p class="text-lg">{!! $jewel->description !!}</p>
               </div>
           </div>
</x-layouts.app>
</div>
@endvolt
