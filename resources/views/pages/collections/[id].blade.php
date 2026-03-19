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
                @if($collection->hasMedia('collections/cover'))
                    <img src="{{ $collection->getFirstMediaUrl('collections/cover', 'cover') }}" 
                         alt="{{ $collection->name }}" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[2000ms]">
                @elseif($collection->cover)
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
                @php
                    $mediaItems = $collection->getMedia('collections')->sortBy(fn($media) => $media->getCustomProperty('order') ?? $media->order_column);
                    $isLargeGallery = $mediaItems->count() > 10;
                @endphp
                
                <section 
                    x-data="{ 
                        galleryOpen: false, 
                        currentIndex: 0,
                        mediaCount: {{ $mediaItems->count() }},
                        next() { this.currentIndex = (this.currentIndex + 1) % this.mediaCount },
                        prev() { this.currentIndex = (this.currentIndex - 1 + this.mediaCount) % this.mediaCount }
                    }"
                    @keydown.escape.window="galleryOpen = false"
                    class="bg-neutral-100 py-24 border-t-8 border-black overflow-hidden"
                >
                    <div class="px-8 mb-12 max-w-[1440px] mx-auto flex items-end justify-between">
                        <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none">
                            L'UNIVERS_<br><span class="text-red-700">DE LA SÉRIE.</span>
                        </h2>
                        <div class="hidden md:block font-mono text-xs font-black opacity-20 text-right">
                            EXPLORATION_VISUELLE / {{ str_pad($mediaItems->count(), 2, '0', STR_PAD_LEFT) }}_SAMPLES
                        </div>
                    </div>

                    @if($isLargeGallery)
                        <!-- Brutalist Bento Grid for 10+ images with white space -->
                        <div class="max-w-[1440px] mx-auto px-4 md:px-8 grid grid-cols-2 md:grid-cols-12 gap-4 md:gap-6">
                            @foreach($mediaItems as $index => $media)
                                @php
                                    $patterns = [
                                        ['cols' => 'md:col-span-8', 'rows' => 'md:row-span-2'],
                                        ['cols' => 'md:col-span-4', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-4', 'rows' => 'md:row-span-2'],
                                        ['cols' => 'md:col-span-4', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-4', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-6', 'rows' => 'md:row-span-2'],
                                        ['cols' => 'md:col-span-6', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-3', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-3', 'rows' => 'md:row-span-1'],
                                        ['cols' => 'md:col-span-12', 'rows' => 'md:row-span-1'],
                                    ];
                                    $pattern = $patterns[$index % count($patterns)];
                                @endphp
                                <div 
                                    @click="galleryOpen = true; currentIndex = {{ $index }}"
                                    class="col-span-2 {{ $pattern['cols'] }} {{ $pattern['rows'] }} border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden group relative bg-white cursor-zoom-in min-h-[300px]">
                                    <img src="{{ $media->getUrl('large') }}" 
                                         alt="Mood image for {{ $collection->name }}" 
                                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110">
                                    
                                    <!-- Technical Overlay -->
                                    <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/10 transition-colors pointer-events-none"></div>
                                    <div class="absolute top-4 right-4 bg-black text-white text-[10px] px-2 py-1 font-black opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-4 group-hover:translate-x-0">
                                        SAMPLE_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    
                                    <!-- Brutalist Cross -->
                                    <div class="absolute top-0 left-0 w-4 h-4 border-r border-b border-black/20 group-hover:border-white transition-colors"></div>
                                    <div class="absolute bottom-0 right-0 w-4 h-4 border-l border-t border-black/20 group-hover:border-white transition-colors"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Standard Impact Grid for smaller sets -->
                        <div class="max-w-[1440px] mx-auto px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($mediaItems as $index => $media)
                                <div 
                                    @click="galleryOpen = true; currentIndex = {{ $index }}"
                                    class="border-4 border-black bg-white shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] overflow-hidden group aspect-square md:aspect-auto md:h-96 relative cursor-zoom-in">
                                    <img src="{{ $media->getUrl('large') }}" 
                                         alt="Mood image for {{ $collection->name }}" 
                                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 hover:scale-105">
                                    <div class="absolute bottom-4 left-4 bg-black text-white px-2 py-1 text-[10px] font-black uppercase">
                                        VIEW_DETAIL_{{ $index + 1 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Brutalist Modal Gallery -->
                    <template x-teleport="body">
                        <div 
                            x-show="galleryOpen" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[100] bg-black/95 flex flex-col items-center justify-center p-4 md:p-12"
                            x-cloak
                        >
                            <!-- Modal Header -->
                            <div class="absolute top-0 left-0 w-full p-6 md:p-8 flex justify-between items-start z-[110]">
                                <div class="text-white font-mono flex flex-col gap-2">
                                    <span class="bg-red-700 px-3 py-1 font-black uppercase text-sm inline-block w-fit">Exploration_Mode</span>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="opacity-50 text-xs" x-text="(currentIndex + 1) + ' / ' + mediaCount"></span>
                                    </div>
                                </div>
                                <button @click="galleryOpen = false" class="text-white hover:text-red-700 transition-colors" aria-label="Fermer la galerie">
                                    <span class="text-6xl leading-none font-light">×</span>
                                </button>
                            </div>

                            <!-- Main Slider Area -->
                            <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                                @foreach($mediaItems as $index => $media)
                                    <div 
                                        x-show="currentIndex === {{ $index }}"
                                        x-transition:enter="transition ease-out duration-500 transform"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-300 transform absolute"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="w-full h-full flex items-center justify-center p-4 md:p-12"
                                    >
                                        <div class="relative border-4 md:border-[12px] border-white shadow-[15px_15px_0px_0px_rgba(185,28,28,1)] md:shadow-[30px_30px_0px_0px_rgba(185,28,28,1)] max-w-full max-h-full">
                                            <img src="{{ $media->getUrl('large') }}" 
                                                 class="max-w-full max-h-[60vh] md:max-h-[80vh] object-contain">
                                            <div class="absolute -bottom-8 md:-bottom-12 left-0 w-full text-white/20 font-mono text-[10vw] md:text-[8vw] font-black uppercase leading-none select-none pointer-events-none truncate">
                                                SAMPLE_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bottom Navigation -->
                            <div class="absolute bottom-0 left-0 w-full p-8 flex justify-center items-center gap-8 z-[120] bg-gradient-to-t from-black via-black/50 to-transparent">
                                <button @click="prev()" class="w-16 h-16 border-4 border-white flex items-center justify-center text-white text-3xl font-black hover:bg-red-700 hover:border-red-700 transition-all active:scale-95">
                                    ←
                                </button>
                                
                                <div class="hidden md:block text-white/30 font-mono text-[10px] uppercase tracking-widest">
                                    [←/→] Naviguer // [Esc] Fermer
                                </div>

                                <button @click="next()" class="w-16 h-16 border-4 border-white flex items-center justify-center text-white text-3xl font-black hover:bg-red-700 hover:border-red-700 transition-all active:scale-95">
                                    →
                                </button>
                            </div>
                        </div>
                    </template>
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
