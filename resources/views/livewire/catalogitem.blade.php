<?php

use App\Models\Jewel;
use function Livewire\Volt\{state};

state(["key", "jewel", "mediaUrl"]);
?>
<div class="group relative">
    <a href="/jewels/{{ $jewel->id }}">
        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
          <img src="{{ $mediaUrl }}" alt="" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
        </div>
        <div class="mt-4 flex justify-between">
          <div>
            <h3 class="text-sm text-gray-700">
                <span aria-hidden="true" class="absolute inset-0"></span>
                {{ $jewel->name }}

            </h3>
            <p class="mt-1 text-sm text-gray-500">{{ $jewel->material }}</p>
          </div>
          <p class="text-sm font-medium text-gray-900">{{ $jewel->price }}</p>
        </div>
    </a>
      </div>
