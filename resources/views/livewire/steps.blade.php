<?php

use function Livewire\Volt\{state, mount};
use App\Models\Step;

// Initialize state variables
state([
    "steps" => [],
]);

// Initial mounting of data
mount(function () {
    $this->steps = Step::with("media")->orderBy("position")->get();
});
?>

<div class="mt-6 grid grid-cols-1 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($steps as $step)
        @php
            // Get the first image associated with this step
            $media = $step->getMedia('steps/images');
            $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
        @endphp

        <!-- Display each step's details -->
        <div class="p-4 bg-white shadow-md rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold">{{ $step->position }}. {{ $step->title }}</h3>
            <p class="text-gray-600 mt-2">{{ $step->description }}</p>

            <!-- Display image if available -->
            @if($firstMediaUrl)
                <img src="{{ $firstMediaUrl }}" alt="Image for {{ $step->title }}" class="w-full mt-4 rounded-lg">
            @endif
        </div>
    @endforeach
</div>
