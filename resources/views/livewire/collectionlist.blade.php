<?php

use function Livewire\Volt\{state, mount};
use App\Models\Collection;

state([
    "collections" => [],
]);

mount(function () {
    $this->collections = Collection::all();
});
?>


<div>
    <h2 class="text-2xl font-bold text-center my-8">Collections</h2>

    @foreach ($collections as $index => $collection)
        <div class="
            w-full py-10 px-8
            {{ $index % 2 === 0 ? 'bg-black text-white' : 'bg-white text-black' }}
            hover:bg-gray-800 hover:text-white transition-colors duration-200
        ">
            <div class="max-w-screen-lg mx-auto {{ $index % 2 === 0 ? 'text-left' : 'text-right' }}">
                <h3 class="text-3xl font-bold">{{ $collection->name }}</h3>
                <p class="mt-4 text-lg">{{ $collection->description }}</p>
            </div>
        </div>
    @endforeach
</div>
