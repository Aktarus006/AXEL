<?php
use App\Models\Jewel;
use Illuminate\Support\Str;

$id = request()->segment(count(request()->segments()));
$jewel = Jewel::with(['media', 'collections'])->find($id);

// Ensure variables are available
view()->share('jewel', $jewel);

// Get related jewels
$relatedJewels = collect([]);
if ($jewel) {
    foreach ($jewel->collections as $collection) {
        $relatedJewels = $relatedJewels->merge(
            $collection->jewels()
                ->where('jewels.id', '!=', $jewel->id)
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
                
                <!-- Left: Giant Image Gallery (Scrollable) -->
                <div class="w-full lg:w-3/5 bg-neutral-100 border-r-4 border-black">
                    @php
                        $packshots = $jewel->getMedia('jewels/packshots');
                        $lifestyles = $jewel->getMedia('jewels/lifestyle');
                        $allImages = $packshots->merge($lifestyles);
                    @endphp
                    
                    <div class="flex flex-col space-y-4 p-4 lg:p-8">
                        @foreach($allImages as $index => $media)
                            @php
                                $startsInColor = ($index === 0) || (rand(1, 10) > 7);
                            @endphp
                            <div class="relative group overflow-hidden border-4 border-black bg-white shadow-[10px_10px_0px_0px_rgba(185,28,28,1)] mb-8">
                                <img src="{{ $media->getUrl('large') }}" 
                                     alt="{{ $jewel->name }}" 
                                     @class([
                                         "w-full h-auto object-cover transition-all duration-700 group-hover:grayscale-0 hover:scale-105",
                                         "grayscale" => !$startsInColor,
                                         "grayscale-0" => $startsInColor
                                     ])
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                <div class="absolute bottom-4 right-4 bg-black text-white px-3 py-1 text-xs font-black">
                                    IMAGE_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Content & Specs (Sticky) -->
                <div class="w-full lg:w-2/5 lg:sticky lg:top-24 lg:h-[calc(100vh-6rem)] overflow-y-auto bg-white p-8 lg:p-16">
                    <div class="space-y-12">
                        <!-- Navigation -->
                        <div class="flex justify-between items-center border-b-4 border-black pb-4">
                            <a href="/jewels" class="font-black hover:text-red-700 transition-colors">← COLLECTION</a>
                            <div class="text-[10px] opacity-40">REF: {{ strtoupper(Str::slug($jewel->name)) }}_{{ $jewel->id }}</div>
                        </div>

                        <!-- Header -->
                        <div class="space-y-4">
                            <h1 class="text-5xl lg:text-7xl font-black uppercase leading-none tracking-tighter">{{ $jewel->name }}</h1>
                            @if($jewel->price > 0)
                            <div class="flex items-center gap-4">
                                <span class="bg-red-700 text-white px-4 py-2 text-2xl font-black italic">€{{ number_format($jewel->price, 0) }}</span>
                                <span class="text-xs font-black uppercase tracking-widest bg-black text-white px-2 py-1">Prêt_à_expédier</span>
                            </div>
                            @else
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-black uppercase tracking-widest bg-black text-white px-4 py-2 border-4 border-black">Pièce_déjà_acquise_ou_en_exposition</span>
                            </div>
                            @endif
                        </div>

                        <!-- Technical Specs -->
                        <div class="grid grid-cols-2 gap-8 py-8 border-y-2 border-black/10">
                            <div>
                                <h3 class="text-xs font-black opacity-30 mb-2">MATÉRIAUX</h3>
                                <div class="space-y-1">
                                    @foreach(json_decode($jewel->material ?? '[]') as $material)
                                        <div class="font-bold uppercase">{{ $material }}</div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xs font-black opacity-30 mb-2">TYPE</h3>
                                <div class="space-y-1">
                                    @foreach(json_decode($jewel->type ?? '[]') as $type)
                                        <div class="font-bold uppercase">{{ $type }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="prose prose-sm font-mono leading-relaxed text-black max-w-none">
                            {!! $jewel->description !!}
                        </div>

                        <!-- Actions -->
                        <div class="pt-8">
                            @if($jewel->price > 0)
                                <a href="#inquiry" class="block w-full py-6 bg-black text-white text-center font-black text-xl hover:bg-red-700 transition-all transform hover:-translate-y-2 shadow-[8px_8px_0px_0px_rgba(185,28,28,1)] uppercase">
                                    Demander l'acquisition
                                </a>
                            @else
                                <a href="#custom" class="block w-full py-6 bg-white text-black border-4 border-black text-center font-black text-xl hover:bg-red-700 hover:text-white transition-all transform hover:-translate-y-2 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] uppercase tracking-tighter">
                                    Une création unique sur-mesure ?
                                </a>
                            @endif
                        </div>

                        <!-- Collections Info -->
                        @if($jewel->collections->isNotEmpty())
                            <div class="pt-12 border-t-2 border-black/10">
                                <h3 class="text-xs font-black opacity-30 mb-6">ARCHIVE_DE_LA_SÉRIE</h3>
                                @foreach($jewel->collections as $collection)
                                    <a href="/collections/{{ $collection->id }}" class="group block p-6 border-4 border-black hover:bg-neutral-50 transition-colors">
                                        <div class="font-black text-xl uppercase group-hover:text-red-700 transition-colors">{{ $collection->name }}</div>
                                        <div class="mt-2 text-xs opacity-60 uppercase">Explorer toute la série →</div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Conditional Form Section -->
            @if($jewel->price > 0)
                <section id="inquiry" class="bg-red-700 py-24 px-8 border-y-8 border-black">
                    <div class="max-w-4xl mx-auto bg-white border-8 border-black p-8 lg:p-16 shadow-[20px_20px_0px_0px_rgba(0,0,0,1)]">
                        <h2 class="text-4xl font-black uppercase mb-4 border-b-4 border-black pb-4 text-black">Demande d'acquisition</h2>
                        <p class="font-mono text-sm mb-12 opacity-60 uppercase">Cette pièce est disponible immédiatement. Veuillez remplir le formulaire ci-dessous pour réserver l'objet.</p>
                        <livewire:jewel-contact-form :jewel="$jewel" />
                    </div>
                </section>
            @else
                <section id="custom" class="bg-black py-24 px-8 border-y-8 border-black">
                    <div class="max-w-4xl mx-auto bg-white border-8 border-black p-8 lg:p-16 shadow-[20px_20px_0px_0px_rgba(185,28,28,1)]">
                        <h2 class="text-4xl font-black uppercase mb-4 border-b-4 border-black pb-4 text-black">Projet Personnel</h2>
                        <p class="font-mono text-sm mb-12 opacity-60 uppercase tracking-tighter">
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
                            <button @click="$refs.relatedSlider.scrollBy({left: -400, behavior: 'smooth'})" class="w-12 h-12 border-4 border-black flex items-center justify-center hover:bg-red-700 transition-colors">←</button>
                            <button @click="$refs.relatedSlider.scrollBy({left: 400, behavior: 'smooth'})" class="w-12 h-12 border-4 border-black flex items-center justify-center hover:bg-red-700 transition-colors">→</button>
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
