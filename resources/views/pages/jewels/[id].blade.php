<?php
use App\Models\Jewel;
use App\Enums\Status;
use Illuminate\Support\Str;

$id = request()->segment(count(request()->segments()));
$jewel = Jewel::with(['media', 'collections'])->where('online', Status::ONLINE)->find($id);

// Ensure variables are available
view()->share('jewel', $jewel);

// Get related jewels
$relatedJewels = collect([]);
if ($jewel) {
    foreach ($jewel->collections as $collection) {
        $relatedJewels = $relatedJewels->merge(
            $collection->jewels()
                ->where('jewels.id', '!=', $jewel->id)
                ->where('jewels.online', Status::ONLINE)
                ->with('media')
                ->get()
        );
    }
    $relatedJewels = $relatedJewels->unique('id');
}
view()->share('relatedJewels', $relatedJewels);
?>

<x-layouts.app>
    <div class="min-h-screen bg-white text-black font-mono">
        @if(!$jewel)
            <div class="flex items-center justify-center h-screen bg-black text-white">
                <div class="text-center p-12 border-8 border-white">
                    <h1 class="mb-8 text-4xl font-black uppercase">OBJECT_NOT_FOUND</h1>
                    <a href="/jewels" class="px-8 py-4 bg-white text-black font-bold uppercase hover:bg-red-700 hover:text-white transition-colors">
                        RETOUR_A_LA_COLLECTION
                    </a>
                </div>
            </div>
        @else
            <!-- Immersive Layout -->
            <div class="flex flex-col lg:flex-row min-h-screen">
                
                <!-- Left: Advanced Gallery Layout -->
                <div class="w-full lg:w-3/5 bg-neutral-100 border-r-4 border-black">
                    @php
                        $packshots = $jewel->getMedia('jewels/packshots');
                        $lifestyles = $jewel->getMedia('jewels/lifestyle');
                        $firstLifestyle = $lifestyles->first();
                        $remainingLifestyles = $lifestyles->slice(1);
                    @endphp
                    
                    <div class="flex flex-col p-4 lg:p-8 space-y-16">
                        <!-- 1. The Hero Atmosphere -->
                        @if($firstLifestyle)
                            <div class="relative group overflow-hidden border-4 border-black bg-white shadow-[15px_15px_0px_0px_rgba(185,28,28,1)]">
                                <div class="absolute top-6 left-6 z-10 bg-black text-white px-4 py-2 font-mono text-xs font-black uppercase tracking-widest border border-white">
                                    ATMOSPHÈRE_01
                                </div>
                                <img src="{{ $firstLifestyle->getUrl('large') }}" 
                                     alt="Mise en situation de {{ $jewel->name }}" 
                                     class="w-full h-auto object-cover transition-all duration-1000 hover:scale-105"
                                     loading="eager">
                            </div>
                        @endif

                        <!-- 2. Technical Details (Grid or Slider) -->
                        @if($packshots->isNotEmpty())
                            <div class="space-y-6">
                                <div class="flex items-center justify-between gap-4">
                                    <h2 class="font-mono font-black text-sm uppercase tracking-[0.3em] bg-black text-white px-4 py-1">DÉTAILS_TECHNIQUES</h2>
                                    @if($packshots->count() > 2)
                                        <span class="text-[10px] font-black uppercase opacity-30 tracking-widest hidden md:block">Scroll_Horizontal_</span>
                                    @endif
                                </div>

                                @if($packshots->count() <= 2)
                                    <!-- Simple Grid for 1 or 2 images -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach($packshots as $index => $media)
                                            <div class="relative group overflow-hidden border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                                                <img src="{{ $media->getUrl('medium') }}" class="w-full h-full object-cover transition-all duration-700 grayscale group-hover:grayscale-0" loading="lazy">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Slider with Visible Brutalist Scrollbar & Smooth Drag to Scroll -->
                                    <div class="relative group" 
                                         x-data="{ 
                                            isDown: false, 
                                            startX: 0, 
                                            scrollLeft: 0,
                                            mouseDown(e) {
                                                this.isDown = true;
                                                $refs.slider.style.scrollSnapType = 'none';
                                                this.startX = e.pageX - $refs.slider.offsetLeft;
                                                this.scrollLeft = $refs.slider.scrollLeft;
                                            },
                                            mouseLeave() {
                                                this.isDown = false;
                                                $refs.slider.style.scrollSnapType = 'x mandatory';
                                            },
                                            mouseUp() {
                                                this.isDown = false;
                                                $refs.slider.style.scrollSnapType = 'x mandatory';
                                            },
                                            mouseMove(e) {
                                                if(!this.isDown) return;
                                                e.preventDefault();
                                                const x = e.pageX - $refs.slider.offsetLeft;
                                                const walk = (x - this.startX) * 1.5; // Ajustement sensibilité
                                                $refs.slider.scrollLeft = this.scrollLeft - walk;
                                            }
                                         }">
                                        <style>
                                            .brutalist-scroll::-webkit-scrollbar {
                                                height: 14px;
                                            }
                                            .brutalist-scroll::-webkit-scrollbar-track {
                                                background: #f3f4f6;
                                                border: 3px solid black;
                                            }
                                            .brutalist-scroll::-webkit-scrollbar-thumb {
                                                background: black;
                                                border: 1px solid white;
                                            }
                                            .brutalist-scroll::-webkit-scrollbar-thumb:hover {
                                                background: #b91c1c;
                                            }
                                            /* Supprimer les effets de sélection bleus */
                                            .no-select {
                                                -webkit-user-select: none;
                                                -khtml-user-select: none;
                                                -moz-user-select: none;
                                                -ms-user-select: none;
                                                user-select: none;
                                                -webkit-user-drag: none;
                                            }
                                        </style>
                                        <div 
                                            x-ref="slider"
                                            @mousedown="mouseDown"
                                            @mouseleave="mouseLeave"
                                            @mouseup="mouseUp"
                                            @mousemove="mouseMove"
                                            class="flex overflow-x-auto snap-x snap-mandatory brutalist-scroll gap-6 pb-12 cursor-grab active:cursor-grabbing no-select" 
                                            style="scrollbar-width: auto; scroll-behavior: auto;">
                                            @foreach($packshots as $index => $media)
                                                <div class="flex-none w-4/5 md:w-1/2 snap-start relative group overflow-hidden border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] no-select">
                                                    <img src="{{ $media->getUrl('medium') }}" 
                                                         draggable="false"
                                                         class="w-full aspect-square object-cover transition-all duration-700 grayscale group-hover:grayscale-0 pointer-events-none no-select" 
                                                         loading="lazy">
                                                    <div class="absolute bottom-4 right-4 bg-black text-white px-2 py-0.5 text-[10px] font-black">
                                                        DET_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- 3. Dynamic Lifestyle Gallery -->
                        @if($remainingLifestyles->isNotEmpty())
                            <div class="space-y-12">
                                @foreach($remainingLifestyles->chunk(3) as $chunk)
                                    @foreach($chunk as $index => $life)
                                        @if($loop->first)
                                            <!-- Full width for the first of the chunk -->
                                            <div class="relative group overflow-hidden border-4 border-black bg-white shadow-[15px_15px_0px_0px_rgba(185,28,28,1)]">
                                                <img src="{{ $life->getUrl('large') }}" class="w-full h-auto object-cover transition-all duration-1000 hover:scale-105">
                                            </div>
                                        @else
                                            <!-- Two columns for the others -->
                                            @if($loop->iteration == 2)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            @endif
                                                <div class="relative group overflow-hidden border-4 border-black bg-white shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                                                    <img src="{{ $life->getUrl('medium') }}" class="w-full aspect-[4/5] object-cover transition-all duration-1000 hover:scale-105">
                                                </div>
                                            @if($loop->last || $loop->iteration == 3)
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Content & Specs (Sticky Dashboard) -->
                <div class="w-full lg:w-2/5 lg:sticky lg:top-24 lg:h-[calc(100vh-6rem)] flex flex-col bg-white border-l-4 lg:border-l-8 border-black">
                    <!-- Fixed Header within the Sidebar (Desktop only) -->
                    <div class="p-8 lg:p-12 border-b-4 border-black bg-white z-30">
                        <div class="flex justify-between items-center mb-8">
                            <a href="/jewels" class="font-black hover:text-red-700 transition-colors flex items-center gap-2 text-sm">
                                <span class="bg-black text-white px-2 py-0.5">←</span> CRÉATIONS
                            </a>
                            <div class="text-[10px] text-black font-black uppercase tracking-widest bg-neutral-100 px-2 py-1 border border-black/10">
                                DOSSIER_#{{ str_pad($jewel->id, 3, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h1 class="text-4xl lg:text-6xl font-black uppercase leading-[0.9] tracking-tighter">{{ $jewel->name }}</h1>
                            <div class="flex flex-wrap items-center gap-4">
                                @if($jewel->price > 0)
                                    <span class="bg-red-700 text-white px-4 py-2 text-2xl font-black italic">{{ number_format($jewel->price, 0, '.', ' ') }} €</span>
                                @else
                                    <span class="text-xs font-black uppercase tracking-widest bg-black text-white px-4 py-2 border-4 border-black italic">PIÈCE_UNIQUE</span>
                                @endif
                                <span class="text-[10px] font-black uppercase tracking-widest border-2 border-black px-2 py-1">ORIGINAL_WORK</span>
                            </div>
                        </div>
                    </div>

                    <!-- Scrollable Body with Scale Ruler -->
                    <div class="flex-1 overflow-y-auto scrollbar-brutal relative group" x-data="{ progress: 0 }" @scroll="progress = ($event.target.scrollTop / ($event.target.scrollHeight - $event.target.offsetHeight)) * 100">
                        <style>
                            .scrollbar-brutal::-webkit-scrollbar { width: 10px; }
                            .scrollbar-brutal::-webkit-scrollbar-track { background: transparent; }
                            .scrollbar-brutal::-webkit-scrollbar-thumb { background: black; border: 2px solid white; }
                        </style>

                        <!-- Scale Ruler (Visual Progress & Brutalist Detail) -->
                        <div class="absolute left-0 top-0 bottom-0 w-8 border-r border-black/10 hidden lg:flex flex-col items-center pointer-events-none opacity-20 group-hover:opacity-100 transition-opacity z-20">
                            <!-- Progress Fill -->
                            <div class="absolute top-0 left-0 w-full bg-red-700" :style="`height: ${progress}%`" style="will-change: height;"></div>
                            
                            <!-- Graduation Markers -->
                            <div class="relative flex-1 w-full flex flex-col justify-between py-12 text-[8px] font-black text-black z-10">
                                <span class="text-center">00</span>
                                <span class="text-center">25</span>
                                <span class="text-center">50</span>
                                <span class="text-center">75</span>
                                <span class="text-center">100</span>
                            </div>
                        </div>

                        <div class="p-8 lg:p-12 lg:pl-16 space-y-16">
                            <!-- Technical Specs -->
                            <section class="space-y-8">
                                <h3 class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-[0.2em]">01_SPECIFICATIONS</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-l-2 border-black/10 pl-8">
                                    <div class="space-y-4">
                                        <h4 class="text-xs font-black opacity-30 uppercase tracking-widest">Matériaux</h4>
                                        <div class="space-y-2">
                                            @php $materials = is_string($jewel->material) ? json_decode($jewel->material, true) : $jewel->material; @endphp
                                            @foreach($materials ?? [] as $material)
                                                <div class="font-black uppercase text-lg italic tracking-tighter">{{ $material instanceof \App\Enums\Material ? $material->value : $material }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <h4 class="text-xs font-black opacity-30 uppercase tracking-widest">Classification</h4>
                                        <div class="space-y-2">
                                            @php $types = is_string($jewel->type) ? json_decode($jewel->type, true) : $jewel->type; @endphp
                                            @foreach($types ?? [] as $type)
                                                <div class="font-black uppercase text-lg italic tracking-tighter">{{ $type instanceof \App\Enums\Type ? $type->value : $type }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Description -->
                            <section class="space-y-8">
                                <h3 class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-[0.2em]">02_HISTORIQUE_CONCEPT</h3>
                                <div class="prose prose-sm font-mono leading-relaxed text-black max-w-none border-l-2 border-black/10 pl-8">
                                    {!! $jewel->description !!}
                                </div>
                            </section>

                            <!-- Actions -->
                            <section class="space-y-8 pt-8">
                                <h3 class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-[0.2em]">03_ACQUISITION</h3>
                                <div class="pl-8">
                                    @if($jewel->price > 0)
                                        <a href="#inquiry" class="group block relative w-full p-8 bg-black hover:bg-red-700 transition-colors text-center overflow-hidden">
                                            <span class="relative z-10 text-white font-black text-2xl uppercase italic tracking-tighter">Demander une variante_</span>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-10 translate-y-10 group-hover:translate-y-0 transition-all">
                                                <span class="text-white text-6xl font-black uppercase">CONFIRMER_</span>
                                            </div>
                                        </a>
                                    @else
                                        <a href="#custom" class="block w-full py-8 border-4 border-black text-center font-black text-xl hover:bg-black hover:text-white transition-all uppercase tracking-tighter shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                                            S'inspirer de cet univers_
                                        </a>
                                    @endif
                                </div>
                            </section>

                            <!-- Collections Archive -->
                            @if($jewel->collections->isNotEmpty())
                                <section class="space-y-8 pt-12">
                                    <h3 class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-[0.2em]">04_CONTEXTE_SERIE</h3>
                                    <div class="pl-8 space-y-4">
                                        @foreach($jewel->collections as $collection)
                                            <a href="/collections/{{ $collection->id }}" class="group block p-6 border-2 border-black/20 hover:border-red-700 hover:bg-neutral-50 transition-all relative">
                                                <div class="font-black text-xl uppercase group-hover:text-red-700 transition-colors">{{ $collection->name }}</div>
                                                <div class="mt-2 text-[10px] opacity-40 uppercase tracking-widest font-black">Archive_Série_Connectée →</div>
                                                <div class="absolute top-2 right-2 w-2 h-2 bg-black/10 group-hover:bg-red-700 group-hover:animate-ping"></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            <!-- Bottom padding for scrollability -->
                            <div class="h-24"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conditional Form Section -->
            @if($jewel->price > 0)
                <section id="inquiry" class="bg-red-700 py-24 px-4 md:px-8 border-y-8 border-black">
                    <div class="max-w-4xl mx-auto bg-white border-4 md:border-8 border-black p-4 md:p-12 lg:p-16 shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] md:shadow-[20px_20px_0px_0px_rgba(0,0,0,1)]">
                        <h2 class="text-3xl md:text-4xl font-black uppercase mb-4 border-b-4 border-black pb-4 text-black">Demande d'acquisition</h2>
                        <p class="font-mono text-xs md:text-sm mb-12 opacity-60 uppercase">Cette pièce est disponible immédiatement. Veuillez remplir le formulaire ci-dessous pour réserver l'objet.</p>
                        <livewire:jewel-contact-form :jewel="$jewel" />
                    </div>
                </section>
            @else
                <section id="custom" class="bg-black py-24 px-4 md:px-8 border-y-8 border-black">
                    <div class="max-w-4xl mx-auto bg-white border-4 md:border-8 border-black p-4 md:p-12 lg:p-16 shadow-[10px_10px_0px_0px_rgba(185,28,28,1)] md:shadow-[20px_20px_0px_0px_rgba(185,28,28,1)]">
                        <h2 class="text-3xl md:text-4xl font-black uppercase mb-4 border-b-4 border-black pb-4 text-black">Projet Personnel</h2>
                        <p class="font-mono text-xs md:text-sm mb-12 opacity-60 uppercase tracking-tighter">
                            Cette pièce n'est plus disponible, mais l'Atelier Axel peut s'inspirer de cet univers pour réaliser une création originale, unique et sur-mesure.
                        </p>
                        <livewire:contactform />
                    </div>
                </section>
            @endif

            <!-- Cool Horizontal Related Slider -->
            @if($relatedJewels->isNotEmpty())
                <section class="bg-white py-24 border-t-8 border-black overflow-hidden">
                    <div class="px-8 mb-12 flex items-end justify-between gap-8 max-w-[1440px] mx-auto w-full">
                        <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter">
                            OBJETS_PROCHES<span class="text-red-700">.</span>
                        </h2>
                        <div class="hidden md:block flex-1 h-2 bg-black mb-4 mx-8"></div>
                        <div class="flex gap-4 mb-2">
                            <button @click="$refs.relatedSlider.scrollBy({left: -400, behavior: 'smooth'})" 
                                    class="w-12 h-12 border-4 border-black flex items-center justify-center hover:bg-red-700 transition-colors group"
                                    aria-label="Objet précédent">
                                <span class="font-mono text-2xl font-black group-hover:text-white" aria-hidden="true">←</span>
                            </button>
                            <button @click="$refs.relatedSlider.scrollBy({left: 400, behavior: 'smooth'})" 
                                    class="w-12 h-12 border-4 border-black flex items-center justify-center hover:bg-red-700 transition-colors group"
                                    aria-label="Objet suivant">
                                <span class="font-mono text-2xl font-black group-hover:text-white" aria-hidden="true">→</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="relative w-full overflow-hidden" x-data="{}">
                        <div x-ref="relatedSlider" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide gap-0 border-y-8 border-black" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($relatedJewels as $index => $related)
                                <div class="flex-none w-full md:w-[45vw] lg:w-[25vw] snap-start border-r-8 border-black h-[500px]">
                                    <livewire:catalogitem :jewel="$related" :wire:key="'related-'.$related->id" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endif
    </div>
</x-layouts.app>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
</style>
