<?php
use App\Models\Creator;
use Illuminate\Support\Str;

$id = request()->segment(count(request()->segments()));
$creator = Creator::with(['media', 'collections'])->find($id);

// Ensure variables are available
view()->share('creator', $creator);
?>

<x-layouts.app>
    <div class="min-h-screen bg-white text-black font-mono">
        @if(!$creator)
            <div class="flex items-center justify-center h-screen bg-black text-white">
                <div class="text-center p-12 border-8 border-white">
                    <h1 class="mb-8 text-4xl font-black uppercase">ARTISTE_NON_TROUVÉ</h1>
                    <a href="/" class="px-8 py-4 bg-white text-black font-bold uppercase hover:bg-red-700 hover:text-white transition-colors">
                        RETOUR À L'ACCUEIL
                    </a>
                </div>
            </div>
        @else
            <!-- Hero Title Section -->
            <header class="relative w-full border-b-8 border-black bg-black text-white overflow-hidden group">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000">
                    <div class="grid grid-cols-12 h-full w-full">
                        @foreach(range(1, 24) as $i)
                            <div class="border-[0.5px] border-white/20 aspect-square"></div>
                        @endforeach
                    </div>
                </div>

                <div class="relative z-10 max-w-[1440px] mx-auto px-8 py-24 md:py-40">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-12">
                        <div class="space-y-8 max-w-4xl">
                            <div class="flex items-center gap-4">
                                <span class="bg-red-700 text-white px-3 py-1 text-xs font-black uppercase tracking-widest">Résident_Atelier</span>
                                <span class="text-xs opacity-50 uppercase font-black tracking-widest">{{ $creator->job_title ?? 'ARTISTE' }}</span>
                            </div>
                            <h1 class="text-6xl md:text-[12vw] font-black uppercase leading-[0.8] tracking-tighter transform -skew-x-6 group-hover:skew-x-0 transition-transform duration-700">
                                {{ $creator->first_name }}<br/>{{ $creator->last_name }}
                            </h1>
                        </div>
                        
                        <!-- Simplified Corner Branding -->
                        <div class="hidden md:flex flex-col items-end text-right">
                            <div class="text-xs opacity-40 uppercase tracking-[0.5em] mb-2">Ref_Artiste</div>
                            <div class="text-4xl font-black italic">#{{ str_pad($creator->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Grid -->
            <div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row border-x-8 border-black bg-white">
                
                <!-- Left Sidebar: Info & Bio -->
                <div class="w-full lg:w-1/3 p-8 md:p-16 border-b-8 lg:border-b-0 lg:border-r-8 border-black bg-neutral-50">
                    <div class="sticky top-32 space-y-16">
                        <!-- Bio -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">À_Propos</h3>
                            <div class="prose prose-sm font-mono leading-relaxed text-black">
                                {!! $creator->description !!}
                            </div>
                        </div>

                        <!-- Links & Social -->
                        <div class="space-y-12 pt-12 border-t-2 border-black/10">
                            <!-- Prominent Website Link -->
                            @if($creator->website_url)
                                <div class="space-y-4">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Portfolio_Online</h3>
                                    <a href="{{ $creator->website_url }}" target="_blank" class="inline-block p-4 border-4 border-black font-black text-xl hover:bg-black hover:text-white transition-all transform hover:-translate-y-1 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none break-all">
                                        {{ str_replace(['http://', 'https://', 'www.'], '', $creator->website_url) }} ↗
                                    </a>
                                </div>
                            @endif

                            @if($creator->collections->isNotEmpty())
                                <div class="space-y-4">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Séries_Collaboratives</h3>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach($creator->collections as $collection)
                                            <a href="/collections/{{ $collection->id }}" class="px-4 py-2 border-2 border-black font-black uppercase text-sm hover:bg-black hover:text-white transition-all">
                                                {{ $collection->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pt-16">
                            <a href="/" class="group flex items-center gap-4 font-black hover:text-red-700 transition-colors">
                                <span class="text-2xl group-hover:-translate-x-2 transition-transform">←</span> RETOUR
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Content: Massive Portrait Gallery -->
                <div class="w-full lg:w-2/3 p-4 md:p-12">
                    <div class="flex flex-col space-y-12">
                        @php
                            $images = $creator->getMedia('creators/profile');
                        @endphp
                        
                        @foreach($images as $index => $media)
                            <div class="relative group overflow-hidden border-8 border-black bg-neutral-200 shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] hover:shadow-[15px_15px_0px_0px_rgba(185,28,28,1)] transition-all duration-500 mb-8">
                                <img src="{{ $media->getUrl('large') }}" 
                                     alt="{{ $creator->name }}" 
                                     class="w-full h-auto object-cover grayscale group-hover:grayscale-0 transition-all duration-[1500ms] hover:scale-105">
                                <div class="absolute bottom-6 right-6 bg-black text-white px-4 py-2 text-xs font-black border border-white">
                                    PORTRAIT_REF_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        @endforeach

                        @if($images->isEmpty())
                            <div class="aspect-[3/4] bg-neutral-100 border-8 border-black border-dashed flex items-center justify-center">
                                <span class="font-black text-black/10 text-6xl rotate-12">IMAGE_SOON_</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Artist CTA -->
            <section class="bg-black text-white py-24 px-8 border-t-8 border-black">
                <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="max-w-2xl">
                        <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none mb-8">ENSEMBLE_ CRÉER L'UNIQUE_</h2>
                        <p class="text-xl uppercase opacity-60 font-mono italic">Collaborations spéciales et projets sur-mesure avec nos artistes résidents.</p>
                    </div>
                    <a href="/#contact" class="inline-block px-12 py-8 bg-red-700 text-white font-black text-3xl hover:bg-white hover:text-black transition-all transform hover:scale-110 shadow-[12px_12px_0px_0px_rgba(255,255,255,0.2)]">
                        CONTACTER L'ATELIER_
                    </a>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
