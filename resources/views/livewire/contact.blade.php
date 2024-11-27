<?php
use function Livewire\Volt\{state};
//
?>

<div class="w-full min-h-[600px] border-t-4 border-white">
    <!-- Social Links -->
    <div class="flex w-full">
        <!-- Social Links Column -->
        <div class="w-1/4 flex flex-col border-r-4 border-white">
            <a href="#" class="h-32 bg-black hover:bg-white hover:text-black text-white font-mono flex items-center justify-center text-xl uppercase border-b-4 border-white transition-colors duration-300">
                Facebook
            </a>
            <a href="#" class="h-32 bg-black hover:bg-white hover:text-black text-white font-mono flex items-center justify-center text-xl uppercase transition-colors duration-300">
                Instagram
            </a>
        </div>

        <!-- Contact Form Section -->
        <div class="w-3/4 bg-black text-white flex">
            <!-- Vertical Text -->
            <div class="bg-black border-r-4 border-white [writing-mode:vertical-rl] flex items-center justify-center px-8 font-mono text-2xl tracking-widest">
                CONTACT
            </div>

            <!-- Form Container -->
            <div class="w-full p-8">
                <livewire:contactform />
            </div>
        </div>
    </div>
</div>
