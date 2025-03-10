<?php
use App\Models\News;

$news = News::latest()->get();
view()->share('news', $news);
?>

@php
    // On récupère la variable dans la portée de la vue
    $news = $news ?? collect([]);
@endphp

<x-layouts.app>
    <div class="min-h-screen bg-black text-white font-mono">
        <!-- Title Section -->
        <div class="relative w-full border-b-8 border-white overflow-hidden"
             x-data="{ showContent: false }"
             x-init="setTimeout(() => showContent = true, 100)">

            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black"
                 x-show="showContent"
                 x-transition:enter="transition-transform duration-1000"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0">
                <div class="absolute inset-0 opacity-20">
                    @for ($i = 0; $i < 50; $i++)
                        <div class="absolute border border-white"
                             style="
                                left: {{ rand(0, 100) }}%;
                                top: {{ rand(0, 100) }}%;
                                width: {{ rand(10, 100) }}px;
                                height: {{ rand(10, 100) }}px;
                                transform: rotate({{ rand(0, 360) }}deg);
                             ">
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Title -->
            <div class="relative z-10 px-8 py-32">
                <div class="max-w-screen-xl mx-auto">
                    <h1 class="text-[8rem] font-black uppercase leading-none tracking-tighter"
                        x-show="showContent"
                        x-transition:enter="transition-transform duration-1000 delay-500"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0">
                        News & Blog
                    </h1>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <div class="max-w-screen-xl mx-auto p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse ($news as $index => $article)
                    <div class="group relative border-4 border-white overflow-hidden"
                         x-data="{ show: false }"
                         x-intersect="show = true"
                         x-show="show"
                         x-transition:enter="transition-transform duration-500"
                         x-transition:enter-start="translate-y-full"
                         x-transition:enter-end="translate-y-0"
                         style="transition-delay: {{ $index * 100 }}ms">

                        <!-- Article Number -->
                        <div class="absolute top-0 left-0 bg-white text-black text-xl font-bold p-2 border-r-4 border-b-4 border-white z-10">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>

                        <!-- Article Image -->
                        @if($article->hasMedia('news/images'))
                            <div class="aspect-video overflow-hidden">
                                <img
                                    src="{{ $article->getFirstMediaUrl('news/images') }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover transition-all duration-500 grayscale group-hover:grayscale-0 group-hover:scale-110"
                                >
                            </div>
                        @endif

                        <!-- Article Info -->
                        <div class="p-6 bg-black">
                            <h2 class="text-3xl font-bold mb-4 group-hover:text-red-700 transition-colors duration-300">
                                {{ $article->title }}
                            </h2>

                            <div class="mb-4 text-sm text-gray-400">
                                {{ $article->created_at->format('d.m.Y') }}
                            </div>

                            <p class="text-lg mb-6">
                                {{ Str::limit($article->excerpt ?? $article->content, 150) }}
                            </p>

                            <a href="/news/{{ $article->id }}"
                               class="inline-block border-2 border-white px-4 py-2 hover:bg-white hover:text-black transition-colors duration-300">
                                READ MORE →
                            </a>
                        </div>

                        <!-- Hover Effect -->
                        <div class="absolute inset-0 bg-red-700 mix-blend-multiply opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-32 border-4 border-white">
                        <div class="text-4xl">NO NEWS YET</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
