<!-- <livewire:loader  wire:loading.remove/> -->

<x-layouts.app>
    <livewire:homeproductboxlist />
    <section id="about">

    </section>
    <section id="cta" class="flex w-full h-3/5 bg-cyan">
        <div class="w-2/3 transition-all duration-500 grayscale hover:grayscale-0" id="touchArea">
            <img src="https://images.unsplash.com/photo-1606687768105-37e58a241dfb?q=80&w=2428&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
        </div>
        <div class="flex flex-col items-center w-1/3 px-8 text-xl justify-evenly">
            <span class="text-7xl">BOLD <span class="text-white transition duration-100 transform bg-black hover:bg-red-600 hover:skew-y-2">JEWELRY</span> FOR BOLDIER PEOPLE</span>
            <button class="p-8 text-white bg-black border-4 border-white hover:bg-white hover:text-black hover:border-black">Show Collections PUNK !</button>
        </div>
    </section>
    <section class="flex items-center justify-center w-full h-1/5 ">
        <span class="relative h-auto transition-opacity duration-1000 ease-in-out transform border-2 border-black before:scale-150 before:absolute before:border-4 hover:before:border-red-200 before:w-full before:h-full before:opacity-0 before:hover:scroll-py-8 before:hover:opacity-100">NOT A BUTTON</span>
    </section>
    <livewire:marquee />
    <section id="contact" class="w-full">
        <livewire:contact />
    </section>
</x-layouts.app>
