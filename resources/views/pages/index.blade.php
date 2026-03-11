<x-layouts.app>
    <livewire:homeproductboxlist />

    <livewire:marquee />

    <livewire:homepage-slider />

    <!-- About Section -->
    <section id="about" class="w-full bg-black overflow-hidden border-t-8 border-b-8 border-white">
        <!-- Main Content Area with Max Width to prevent stretching on UHD -->
        <div class="max-w-[1920px] mx-auto relative py-24 md:py-40 lg:py-60 px-8 group">
            
            <!-- Background Grid (Scaled for UHD) -->
            <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000 pointer-events-none">
                <div class="grid grid-cols-6 md:grid-cols-12 lg:grid-cols-18 h-full w-full">
                    @foreach(range(1, 72) as $i)
                        <div class="border-[0.5px] border-white/20 aspect-square"></div>
                    @endforeach
                </div>
            </div>

            <!-- Giant Fluid Heading (Uses VW to scale perfectly on 4K) -->
            <div class="relative z-10 flex flex-col items-center justify-center text-center">
                <h2 class="font-mono font-black text-white uppercase tracking-tighter leading-[0.8] flex flex-col md:flex-row items-center gap-x-8">
                    <!-- Solid Part -->
                    <span class="text-[15vw] lg:text-[12vw] transform -skew-x-12 group-hover:skew-x-0 transition-transform duration-700">L'ATELIER</span>
                    <!-- Outlined Part -->
                    <span class="text-[15vw] lg:text-[12vw] text-transparent text-outline-white group-hover:text-white transition-all duration-700 delay-100">AXEL</span>
                </h2>
                
                <!-- Underline Decoration -->
                <div class="w-full h-4 bg-red-700 mt-8 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-1000 origin-left"></div>
            </div>

            <!-- Diamond Animation (Positioned relatively to the text) -->
            <div class="hidden lg:flex absolute top-20 right-20 w-40 h-40 transition-all duration-1000 border-8 border-white rotate-45 group-hover:rotate-[225deg] items-center justify-center pointer-events-none">
                <div class="absolute inset-4 border-4 border-white group-hover:border-red-700 -rotate-90 group-hover:rotate-[180deg] transition-all duration-700"></div>
                <div class="w-6 h-6 bg-white group-hover:bg-red-700 animate-pulse -rotate-45"></div>
            </div>
        </div>
        
        <!-- Process Steps (Responsive Grid) -->
        <div class="max-w-[1920px] mx-auto border-t-8 border-white">
            <livewire:steps />
        </div>
    </section>

    <livewire:creators-slider />

    <!-- Contact Section -->
    <section id="contact" class="w-full bg-black">
        <div class="max-w-[1920px] mx-auto">
            <livewire:contact />
        </div>
    </section>

    <style>
    .text-outline-white {
        -webkit-text-stroke: 2px white;
    }
    @media (min-width: 2560px) {
        .text-outline-white {
            -webkit-text-stroke: 4px white;
        }
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
