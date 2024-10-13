<?php

use function Livewire\Volt\{state};
//
?>

<div class="w-full h-1/3 flex">
    <div class="w-1/4 flex flex-col bg-fuschia items-center">
        <div class="bg-purple-300 w-full h-1/2 hover:bg-purple-800 flex items-center justify-center">Facebook</div>
        <div class="bg-purple-300 w-full h-1/2 hover:bg-purple-800 flex items-center justify-center">Instagram</div>
    </div>

    <div class="w-3/4 bg-white text-black flex">
        <div class="bg-black [writing-mode:vertical-rl] flex items-center justify-center hover:bg-white px-4 h-full">
            CONTACT
        </div>
        <div class="w-full">
        <livewire:contactform />
        </div>
    </div>
</div>
