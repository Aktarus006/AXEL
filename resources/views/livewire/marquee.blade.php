<div class="relative flex overflow-x-hidden transition-colors duration-300 bg-black border-t-8 border-b-8 border-white hover:bg-red-700">
    <div class="py-6 whitespace-nowrap animate-marquee">
        @foreach(['Bijoux', 'Personnalisé', 'Hand made', 'Or', 'Symboles', 'Diamants', 'Unassuming', 'Confiance', 'Sur mesure', 'Artisanat certifié', 'Argent', 'Pierres précieuses', 'Surprising', 'Clins d\'œil', 'Fiable', 'Unique', 'Intemporel', 'Durable', 'Créatif', 'Héritage', 'Éthique', 'Luxe', 'Finitions fines', 'Passion', 'Authenticité', 'Délicatesse', 'Subtilité', 'Exclusivité', 'Émotion', 'Inspiration', 'Éclat', 'Signature', 'Souvenir', 'Cadeau', 'Élégance', 'Charme', 'Équilibre', 'Style', 'Harmonie'] as $word)
            <span class="mx-8 font-mono text-4xl md:text-6xl tracking-tighter text-white uppercase font-black transform -skew-x-12">{{ $word }}</span>
            <span class="inline-block text-white text-5xl translate-y-[-4px] mx-4 font-black">★</span>
        @endforeach
    </div>

    <div class="absolute top-0 py-6 whitespace-nowrap animate-marquee2">
        @foreach(['Bijoux', 'Personnalisé', 'Hand made', 'Or', 'Symboles', 'Diamants', 'Unassuming', 'Confiance', 'Sur mesure', 'Artisanat certifié', 'Argent', 'Pierres précieuses', 'Surprising', 'Clins d\'œil', 'Fiable', 'Unique', 'Intemporel', 'Durable', 'Créatif', 'Héritage', 'Éthique', 'Luxe', 'Finitions fines', 'Passion', 'Authenticité', 'Délicatesse', 'Subtilité', 'Exclusivité', 'Émotion', 'Inspiration', 'Éclat', 'Signature', 'Souvenir', 'Cadeau', 'Élégance', 'Charme', 'Équilibre', 'Style', 'Harmonie'] as $word)
            <span class="mx-8 font-mono text-4xl md:text-6xl tracking-tighter text-white uppercase font-black transform -skew-x-12">{{ $word }}</span>
            <span class="inline-block text-white text-5xl translate-y-[-4px] mx-4 font-black">★</span>
        @endforeach
    </div>
</div>
