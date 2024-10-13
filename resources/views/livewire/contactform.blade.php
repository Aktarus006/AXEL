<?php

use function Livewire\Volt\{state};
//
?>

<form class="w-full h-full">
    <div class="flex justify-between w-full mb-8">
        <div class="flex flex-col w-1/2">
            <label for="name" class="w-full uppercase text-2xl text-left p-0 border-4 border-black border-l-0 pl-4">Name</label>
            <input type="text" id="name" class="border-2" placeholder="Nom"/>
        </div>
        <div class="flex flex-col w-1/2">
            <label for="email" class="w-full uppercase text-2xl text-left p-0 border-l-0 border-4 border-black pl-4">Email</label>
            <input type="text" id="email" placeholder="email"/>
        </div>
    </div>
    <div class="flex flex-col w-full h-auto mx-auto">
        <label for="message" class="-mt-8 text-2xl border-black border-4 border-l-0">Message</label>
        <textarea name="message" id="message" rows="4" class="w-full"></textarea>
    </div>
</form>
