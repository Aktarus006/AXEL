@volt
@php
use App\Models\Collection;
use App\Enums\Status;

$collection = Collection::with(['jewels' => function($q) {
    $q->where('online', Status::ONLINE)
      ->whereHas('media'); // Only jewels with images
}, 'creators'])->where('online', Status::ONLINE)->find(request()->route('id'));
@endphp

<x-layouts.app>
    <div class="min-h-screen bg-white text-black font-mono">
        @if(!$collection)
            <div class="flex items-center justify-center h-screen bg-black text-white">
                <div class="text-center p-12 border-8 border-white">
                    <h1 class="mb-8 text-4xl font-black uppercase">SERIE_NON_TROUVEE</h1>
                    <a href="/collections" class="px-8 py-4 bg-white text-black font-bold uppercase hover:bg-red-700 hover:text-white transition-colors">
                        RETOUR AUX SÉRIES
                    </a>
                </div>
            </div>
        @else
            <!-- Immersive Hero Header -->
            <div class="relative w-full h-[70vh] bg-black overflow-hidden border-b-8 border-black group">
                <!-- Background Image (Large) -->
                @if($collection->cover)
                    <img src="{{ Storage::url($collection->cover) }}" 
                         alt="{{ $collection->name }}" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[2000ms]">
                @elseif($collection->hasMedia('collections'))
                    <img src="{{ $collection->getFirstMediaUrl('collections', 'large') }}" 
                         alt="{{ $collection->name }}" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[2000ms]">
                @endif
                
                <!-- Overlay Content -->
                <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-20 bg-gradient-to-t from-black to-transparent">
                    <div class="max-w-[1440px] mx-auto w-full">
                        <div class="flex flex-col md:flex-row justify-between items-end gap-12">
                            <div class="space-y-4">
                                <span class="bg-red-700 text-white px-4 py-1 text-sm font-black uppercase tracking-widest">SÉRIE_EXPLORATION</span>
                                <h1 class="text-6xl md:text-[10vw] font-black text-white leading-[0.8] uppercase tracking-tighter transform -skew-x-12">
                                    {{ $collection->name }}
                                </h1>
                            </div>
                            
                            @if($collection->creators->isNotEmpty())
                                <div class="text-white text-right space-y-2">
                                    <span class="text-xs opacity-50 uppercase tracking-widest">Collaboration_Artiste</span>
                                    @foreach($collection->creators as $creator)
                                        <a href="/creators/{{ $creator->id }}" class="block text-2xl font-black hover:text-red-700 transition-colors uppercase underline decoration-4 underline-offset-8">
                                            {{ $creator->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row border-x-8 border-black bg-white">
                
                <!-- Left: Description & Meta -->
                <div class="w-full lg:w-1/3 p-8 md:p-16 border-b-8 lg:border-b-0 lg:border-r-8 border-black">
                    <div class="sticky top-32 space-y-12">
                        <div class="space-y-6">
                            <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Concept_Note</h3>
                            <div class="prose prose-sm font-mono leading-relaxed text-black">
                                {!! $collection->description !!}
                            </div>
                        </div>

                        <div class="pt-12 border-t-2 border-black/10">
                            <a href="/collections" class="group flex items-center gap-4 font-black hover:text-red-700 transition-colors">
                                <span class="text-2xl group-hover:-translate-x-2 transition-transform">←</span> TOUTES LES SÉRIES
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: The Objects (Masonry-like Grid) -->
                <div class="w-full lg:w-2/3 p-4 md:p-12">
                    <div class="flex justify-between items-center mb-12 border-b-4 border-black pb-4">
                        <h2 class="text-2xl font-black uppercase tracking-tighter">OBJETS_DE_LA_SÉRIE</h2>
                        <div class="text-xs opacity-40 uppercase">{{ $collection->jewels->count() }} PIÈCES RÉPERTORIÉES</div>
                    </div>

                    <div class="columns-1 md:columns-2 gap-8 space-y-8">
                        @foreach ($collection->jewels as $index => $jewel)
                            @php
                                $heights = ['h-[400px]', 'h-[500px]', 'h-[450px]', 'h-[550px]'];
                                $height = $heights[$index % count($heights)];
                            @endphp
                            <div class="break-inside-avoid">
                                <div class="relative {{ $height }}">
                                    <livewire:catalogitem
                                        :jewel="$jewel"
                                        :wire:key="'collection-jewel-'.$jewel->id.'-'.$index"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Collection Gallery (Mood/Ambience) -->
            @if($collection->hasMedia('collections'))
                <section class="bg-neutral-100 py-24 border-t-8 border-black">
                    <div class="px-8 mb-12 max-w-[1440px] mx-auto">
                        <h2 class="text-4xl font-black uppercase tracking-tighter">L'UNIVERS_ DE LA SÉRIE</h2>
                    </div>
                    <div class="max-w-[1440px] mx-auto px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($collection->getMedia('collections') as $media)
                            <div class="border-4 border-black bg-white shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] overflow-hidden group aspect-square md:aspect-auto md:h-96">
                                <img src="{{ $media->getUrl('large') }}" 
                                     alt="Mood image for {{ $collection->name }}" 
                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 hover:scale-105">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Bottom Call to Action -->
            <section class="bg-black text-white py-24 px-8 border-t-8 border-black">
                <div class="max-w-4xl mx-auto text-center space-y-12">
                    <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none">UNE PIÈCE UNIQUE SUR_MESURE ?</h2>
                    <p class="text-xl uppercase opacity-60">L'Atelier Axel réalise vos projets les plus audacieux.</p>
                    <a href="/#contact" class="inline-block px-12 py-6 bg-red-700 text-white font-black text-2xl hover:bg-white hover:text-black transition-all transform hover:scale-110 shadow-[10px_10px_0px_0px_rgba(255,255,255,0.2)]">
                        CONTACTER L'ATELIER
                    </a>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
