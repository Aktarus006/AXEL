<?php
use App\Models\News;
use App\Enums\Status;
use Illuminate\Support\Str;

$id = request()->segment(count(request()->segments()));
$article = News::with(['media', 'jewel', 'collection', 'creators'])->where('online', Status::ONLINE)->find($id);

// Ensure variables are available
view()->share('article', $article);

// Get other recent news
$otherNews = News::where('id', '!=', $id)->where('online', Status::ONLINE)->latest()->take(3)->get();
view()->share('otherNews', $otherNews);
?>

<x-layouts.app>
    <div class="min-h-screen bg-white text-black font-mono">
        @if(!$article)
            <div class="flex items-center justify-center h-screen bg-black text-white">
                <div class="text-center p-12 border-8 border-white">
                    <h1 class="mb-8 text-4xl font-black uppercase">ARTICLE_NON_TROUVÉ</h1>
                    <a href="/news" class="px-8 py-4 bg-white text-black font-bold uppercase hover:bg-red-700 hover:text-white transition-colors">
                        RETOUR AU JOURNAL
                    </a>
                </div>
            </div>
        @else
            <!-- Editorial Header -->
            <article>
                <header class="relative w-full border-b-8 border-black bg-black text-white overflow-hidden group">
                    <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000">
                        <div class="grid grid-cols-12 h-full w-full">
                            @foreach(range(1, 24) as $i)
                                <div class="border-[0.5px] border-white/20 aspect-square"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative z-10 max-w-[1440px] mx-auto px-8 py-24 md:py-40">
                        <div class="max-w-5xl space-y-8 text-left">
                            <div class="flex items-center gap-4">
                                <span class="bg-red-700 text-white px-3 py-1 text-xs font-black uppercase tracking-widest">Chroniques_Atelier</span>
                                <span class="text-xs opacity-50 uppercase font-black tracking-widest">{{ $article->created_at->format('d.m.Y') }}</span>
                            </div>
                            <h1 class="text-5xl md:text-[10vw] font-black uppercase leading-[0.85] tracking-tighter transform -skew-x-6">
                                {{ $article->title }}
                            </h1>
                        </div>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($article->hasMedia('news/images'))
                    <div class="w-full max-w-[1440px] mx-auto border-x-8 border-black">
                        <div class="aspect-[21/9] overflow-hidden bg-neutral-200 border-b-8 border-black relative">
                            <img src="{{ $article->getFirstMediaUrl('news/images', 'large') }}" 
                                 alt="{{ $article->title }}" 
                                 class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-[2000ms] hover:scale-105">
                            <div class="absolute bottom-8 right-8 bg-white text-black px-4 py-2 font-black text-sm border-4 border-black">
                                COVER_IMAGE_01
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Content Area -->
                <div class="max-w-[1440px] mx-auto border-x-8 border-black bg-white">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Redesigned Main Content (Much Wider) -->
                        <div class="w-full lg:w-3/4 p-8 md:p-16 lg:p-24 border-b-8 lg:border-b-0 lg:border-r-8 border-black">
                            <div class="prose prose-2xl font-mono leading-relaxed text-black max-w-none 
                                        prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tighter prose-headings:mt-12
                                        prose-p:mb-10 prose-strong:font-black prose-a:text-red-700 prose-img:border-8 prose-img:border-black">
                                {!! $article->description !!}
                            </div>

                            <!-- Visual Relations (Jewel & Collection) -->
                            @if($article->jewel || $article->collection)
                                <div class="mt-24 pt-24 border-t-8 border-black">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em] mb-12 italic">Objets_Et_Séries_Cités</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                        @if($article->jewel)
                                            <div class="space-y-6">
                                                <div class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-widest">
                                                    Objet_Vedette
                                                </div>
                                                <div class="border-4 border-black aspect-square">
                                                    <livewire:catalogitem :jewel="$article->jewel" :wire:key="'news-jewel-'.$article->jewel->id" />
                                                </div>
                                            </div>
                                        @endif

                                        @if($article->collection)
                                            <div class="space-y-6">
                                                <div class="inline-block bg-black text-white text-[10px] font-black uppercase px-2 py-1 tracking-widest">
                                                    Série_Connexe
                                                </div>
                                                <a href="/collections/{{ $article->collection->id }}" class="group block p-10 bg-neutral-100 border-4 border-black hover:bg-red-700 transition-all relative overflow-hidden h-full">
                                                    <div class="relative z-10">
                                                        <h4 class="text-4xl font-black uppercase tracking-tighter leading-none group-hover:text-white transition-colors">
                                                            {{ $article->collection->name }}
                                                        </h4>
                                                        <div class="mt-6 text-xs font-black uppercase opacity-40 group-hover:text-white group-hover:opacity-100 transition-all italic">
                                                            Explorer la collection →
                                                        </div>
                                                    </div>
                                                    <div class="absolute -bottom-4 -right-4 text-black/5 text-[120px] font-black uppercase leading-none group-hover:text-white/10 transition-colors">
                                                        {{ substr($article->collection->name, 0, 1) }}
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="w-full lg:w-1/4 p-8 md:p-12 bg-neutral-50 flex flex-col space-y-16">
                            <div class="sticky top-32 space-y-16">
                                <!-- Creators Relation -->
                                @if($article->creators->isNotEmpty())
                                    <div class="space-y-6">
                                        <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Intervenants</h3>
                                        <div class="space-y-4">
                                            @foreach($article->creators as $creator)
                                                <div class="group flex items-center gap-4 p-3 border-2 border-black/10 bg-white hover:border-black transition-all">
                                                    <div class="w-12 h-12 border-2 border-black overflow-hidden bg-neutral-200">
                                                        @if($creator->hasMedia('creators/profile'))
                                                            <img src="{{ $creator->getFirstMediaUrl('creators/profile', 'avatar-thumbnail') }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center font-black text-xs">{{ substr($creator->first_name, 0, 1) }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-black uppercase leading-none">{{ $creator->first_name }} {{ $creator->last_name }}</span>
                                                        <span class="text-[10px] opacity-50 uppercase font-bold">{{ $creator->job_title }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Navigation -->
                                <div class="space-y-4">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Navigation</h3>
                                    <a href="/news" class="group flex items-center gap-4 font-black text-xl hover:text-red-700 transition-colors">
                                        <span class="text-2xl group-hover:-translate-x-2 transition-transform">←</span> JOURNAL
                                    </a>
                                </div>

                                <!-- Share -->
                                <div class="space-y-6">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Partager</h3>
                                    <div class="grid grid-cols-1 gap-4">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="group flex items-center gap-4 p-4 border-4 border-black hover:bg-black transition-all">
                                            <div class="w-8 h-8 bg-black flex items-center justify-center group-hover:bg-red-700">
                                                <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/></svg>
                                            </div>
                                            <span class="font-black group-hover:text-white uppercase text-xs">FACEBOOK</span>
                                        </a>
                                        <a href="https://www.instagram.com/axel.englebert/" target="_blank" class="group flex items-center gap-4 p-4 border-4 border-black hover:bg-black transition-all">
                                            <div class="w-8 h-8 bg-black flex items-center justify-center group-hover:bg-red-700">
                                                <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4.162 4.162 0 1 1 0-8.324 4.162 4.162 0 0 1 0 8.324zM18.406 3.506a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"/></svg>
                                            </div>
                                            <span class="font-black group-hover:text-white uppercase text-xs">INSTAGRAM</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Metadata -->
                                <div class="pt-16 border-t-2 border-black/10 space-y-4">
                                    <h3 class="text-xs font-black opacity-30 uppercase tracking-[0.3em]">Informations</h3>
                                    <div class="text-[10px] font-bold uppercase leading-relaxed">
                                        Ref: JOURNAL_#{{ str_pad($article->id, 3, '0', STR_PAD_LEFT) }}<br/>
                                        Status: PUBLIÉ<br/>
                                        Atelier: AXEL_STUDIO
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Other News Section -->
            @if($otherNews->isNotEmpty())
                <section class="bg-neutral-50 border-t-8 border-black py-24 px-8">
                    <div class="max-w-[1440px] mx-auto">
                        <h2 class="text-4xl font-black uppercase mb-16 flex items-center gap-4">
                            <span class="w-12 h-4 bg-red-700"></span> À LIRE AUSSI
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                            @foreach($otherNews as $other)
                                <a href="/news/{{ $other->id }}" class="group block space-y-6">
                                    <div class="aspect-video overflow-hidden border-4 border-black relative">
                                        @if($other->hasMedia('news/images'))
                                            <img src="{{ $other->getFirstMediaUrl('news/images', 'medium') }}" 
                                                 alt="{{ $other->title }}" 
                                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full bg-black flex items-center justify-center font-black text-white text-4xl">AXEL_</div>
                                        @endif
                                        <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/10 transition-colors"></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="text-xs font-black opacity-40">{{ $other->created_at->format('d.m.Y') }}</div>
                                        <h3 class="text-2xl font-black uppercase leading-tight group-hover:text-red-700 transition-colors">{{ $other->title }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Contact CTA -->
            <section class="bg-black text-white py-24 px-8 border-t-8 border-black">
                <div class="max-w-4xl mx-auto text-center space-y-12">
                    <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none">UNE QUESTION ? UN PROJET_</h2>
                    <p class="text-xl uppercase opacity-60 font-mono">Discutons ensemble de votre prochaine création unique.</p>
                    <a href="/#contact" class="inline-block px-12 py-6 bg-red-700 text-white font-black text-2xl hover:bg-white hover:text-black transition-all transform hover:scale-110 shadow-[10px_10px_0px_0px_rgba(255,255,255,0.2)]">
                        CONTACTER L'ATELIER
                    </a>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
