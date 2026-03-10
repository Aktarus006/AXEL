<x-layouts.app>
    <livewire:homeproductboxlist />

    <livewire:marquee />

    <livewire:homepage-slider />

    <!-- About Section -->
    <section id="about" class="w-full">
        <div class="relative w-full py-12 md:py-20 bg-black border-t-8 border-b-8 border-white group overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000">
                <div class="grid grid-cols-12 h-full w-full">
                    @foreach(range(1, 48) as $i)
                        <div class="border-[0.5px] border-white/20 aspect-square"></div>
                    @endforeach
                </div>
            </div>

            <!-- Animated Icon Square (Responsive) -->
            <div class="hidden md:flex absolute w-32 h-32 transition-colors duration-500 -translate-y-1/2 border-8 border-white left-8 lg:left-16 top-1/2 group-hover:border-red-700"
                x-data="{
                    icons: ['★', '◆', '◢', '▣', '◎', '✕'],
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
                <div class="absolute transition-opacity border-4 border-black opacity-0 inset-1 group-hover:opacity-100"></div>
            </div>

            <!-- Title -->
            <div class="relative z-10 px-6 md:px-20 lg:px-32 text-center md:text-right">
                <h2 class="text-6xl md:text-[8rem] lg:text-[10rem] font-mono font-black text-white uppercase tracking-tighter transform md:-skew-x-12 group-hover:-skew-x-0 transition-transform duration-500 leading-none">
                    ABOUT<br/><span class="text-outline-white text-transparent group-hover:text-white transition-colors duration-500">US</span>
                </h2>
            </div>

            <!-- Bottom Accent Bar -->
            <div class="absolute inset-x-0 bottom-0 h-4 transition-transform duration-500 origin-left scale-x-0 bg-red-700 group-hover:scale-x-100"></div>
        </div>
        
        <livewire:steps />
    </section>

    <livewire:creators-slider />

    <livewire:separator />

    <section id="contact" class="w-full mb-0">
        <livewire:contact />
    </section>

    <livewire:separator />

    <style>
    .text-outline-white {
        -webkit-text-stroke: 2px white;
    }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash) {
                const targetId = window.location.hash;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
</x-layouts.app>
