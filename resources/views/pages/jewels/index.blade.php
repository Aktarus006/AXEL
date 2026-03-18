<x-layouts.app>
    <!-- Title Section -->
<div class="relative w-full py-12 overflow-hidden bg-black border-t-8 border-b-8 border-white group">
    <!-- Sophisticated Diamond Animation -->
    <div class="absolute w-16 h-16 sm:w-32 sm:h-32 transition-all duration-1000 -translate-y-1/2 border-4 sm:border-8 border-white left-4 sm:left-16 top-1/2 group-hover:border-red-700 rotate-45 group-hover:rotate-[225deg] flex items-center justify-center">
        <!-- Inner Facet -->
        <div class="absolute inset-2 sm:inset-4 border-2 sm:border-4 border-white group-hover:border-red-700 -rotate-90 group-hover:rotate-[180deg] transition-all duration-700"></div>
        <div class="w-2 h-2 sm:w-4 sm:h-4 bg-white group-hover:bg-red-700 animate-pulse -rotate-45"></div>
    </div>

    <!-- Title -->
    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-[8rem] font-mono font-black text-white text-right uppercase tracking-tighter transform md:-skew-x-12 group-hover:-skew-x-0 transition-transform duration-500 px-4 sm:px-8 md:px-32 leading-none">
        NOS_RÉALISATIONS
    </h2>

    <div class="absolute inset-x-0 bottom-0 h-4 transition-transform duration-500 origin-left scale-x-0 bg-red-700 group-hover:scale-x-100"></div>
</div>

<livewire:catalog />
</x-layouts.app>
