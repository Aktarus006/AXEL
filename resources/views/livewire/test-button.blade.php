<div class="p-4 bg-white text-black">
    <h2 class="text-xl mb-4">Test Livewire Component</h2>
    <p class="mb-4">Counter: {{ $count }}</p>
    <button 
        wire:click="increment" 
        class="px-4 py-2 bg-black text-white hover:bg-red-700"
    >
        Click to increment
    </button>
</div>

<?php
use function Livewire\Volt\{state};

state(['count' => 0]);

$increment = function () {
    $this->count++;
};
?>
