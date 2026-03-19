<?php
use App\Models\News;
use App\Enums\Status;

$news = News::where('online', Status::ONLINE)->latest()->get();
view()->share('news', $news);
?>

<x-layouts.app>
    <div class="min-h-screen bg-white text-black font-mono">
        <!-- Editorial Title Section -->
        <div class="relative w-full border-b-8 border-black overflow-hidden bg-black text-white group">
            <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity duration-1000">
                <div class="grid grid-cols-12 h-full w-full">
                    @foreach(range(1, 48) as $i)
                        <div class="border-[0.5px] border-white/20 aspect-square"></div>
                    @endforeach
                </div>
            </div>

            <div class="relative z-10 px-8 py-32 md:py-48 max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-end gap-12">
                <h1 class="text-6xl md:text-[10vw] font-black uppercase leading-[0.8] tracking-tighter transform -skew-x-12">
                    LE<br/><span class="text-red-700">JOURNAL</span>
                </h1>
                <div class="text-right max-w-md">
                    <p class="text-xl uppercase font-bold mb-4">Chroniques de l'établi</p>
                    <p class="text-sm opacity-60 uppercase leading-relaxed">
                        Explorations créatives, événements et coulisses de l'Atelier Axel. 
                        Plongez dans l'univers de la joaillerie contemporaine.
                    </p>
                </div>
            </div>
            
            <!-- Bottom Accent Bar -->
            <div class="absolute inset-x-0 bottom-0 h-4 transition-transform duration-500 origin-left scale-x-0 bg-red-700 group-hover:scale-x-100"></div>
        </div>

        <!-- News Editorial Grid -->
        <div class="max-w-[1440px] mx-auto border-x-8 border-black">
            @forelse ($news as $index => $article)
                <div class="group border-b-8 border-black last:border-b-0 hover:bg-neutral-50 transition-colors duration-500">
                    <a href="/news/{{ $article->id }}" class="grid grid-cols-1 md:grid-cols-12 items-stretch">
                        <!-- Date & Index (1 Col) -->
                        <div class="md:col-span-1 border-b-4 md:border-b-0 md:border-r-8 border-black flex flex-col items-center justify-center bg-white text-black p-4 group-hover:bg-red-700 group-hover:text-white transition-colors py-8 md:py-12">
                            <span class="text-[10px] font-black opacity-40 group-hover:opacity-100 uppercase mb-auto">Item_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="flex-1 flex items-center justify-center">
                                <div class="text-2xl font-black rotate-0 md:-rotate-90 whitespace-nowrap">
                                    {{ $article->created_at->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Content Section (7 Cols) -->
                        <div class="md:col-span-7 p-8 md:p-16 flex flex-col justify-center space-y-6">
                            <div class="space-y-2">
                                <span class="text-xs font-black uppercase tracking-[0.3em] text-red-700">Actualités_Atelier</span>
                                <h2 class="text-4xl md:text-6xl font-black uppercase leading-none tracking-tighter group-hover:translate-x-4 transition-transform duration-500">
                                    {{ $article->title }}
                                </h2>
                            </div>

                            <div class="prose prose-sm font-mono text-black/60 max-w-2xl group-hover:text-black transition-colors duration-500">
                                {{ Str::limit(strip_tags($article->description), 200) }}
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <span class="font-black text-lg underline decoration-4 underline-offset-8 group-hover:text-red-700 transition-colors">LIRE L'ARTICLE_</span>
                            </div>
                        </div>

                        <!-- Featured Image (4 Cols) -->
                        <div class="md:col-span-4 border-t-4 md:border-t-0 md:border-l-8 border-black overflow-hidden relative bg-neutral-200 aspect-video md:aspect-auto">
                            @if($article->hasMedia('news/images'))
                                <img
                                    src="{{ $article->getFirstMediaUrl('news/images', 'large') }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover transition-all duration-1000 grayscale group-hover:grayscale-0 group-hover:scale-110"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center font-black text-black/10 text-6xl">AXEL_</div>
                            @endif
                            <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/10 transition-colors duration-500"></div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="text-center py-40 bg-white">
                    <h3 class="font-mono text-4xl font-black uppercase opacity-20">Aucune actualité_</h3>
                </div>
            @endforelse
        </div>

        <!-- Pagination Placeholder / Footer Join -->
        <div class="bg-black py-20 border-t-8 border-black text-center">
            <div class="font-mono text-white text-xs opacity-40 uppercase tracking-[0.5em]">Fin de la lecture</div>
        </div>
    </div>
</x-layouts.app>
