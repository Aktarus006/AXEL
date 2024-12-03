<x-layouts.app>
    <!-- Title Section -->
<div class="relative w-full bg-black border-t-8 border-b-8 border-white py-12 group overflow-hidden">
    <!-- Animated Icon -->
    <div class="absolute left-16 top-1/2 -translate-y-1/2 w-32 h-32 border-8 border-white group-hover:border-red-600 transition-colors duration-500"
        x-data="{ 
            icons: ['✧', '◇', '△', '□'],
            currentIcon: 0,
            init() {
                setInterval(() => {
                    this.currentIcon = (this.currentIcon + 1) % this.icons.length;
                }, 800);
            }
        }"
    >
        <template x-for="(icon, index) in icons" :key="index">
            <div 
                class="absolute inset-0 flex items-center justify-center text-6xl text-white transition-opacity duration-300"
                :class="{ 'opacity-100': currentIcon === index, 'opacity-0': currentIcon !== index }"
                x-text="icon"
            ></div>
        </template>
        <div class="absolute inset-1 border-4 border-black opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

    <!-- Title -->
    <h2 class="text-[10rem] font-mono font-black text-white text-right uppercase tracking-tight transform -skew-x-12 group-hover:-skew-x-0 transition-transform duration-500 px-32">
Bijoux    </h2>
    
    <div class="absolute inset-x-0 bottom-0 h-2 bg-red-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
</div>
    <livewire:catalog />
</x-layouts.app>
