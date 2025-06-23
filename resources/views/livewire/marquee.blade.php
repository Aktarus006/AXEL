<div class="relative flex overflow-x-hidden transition-colors duration-300 bg-black border-t-4 border-b-4 border-white hover:bg-red-700">
    <div class="py-2 whitespace-nowrap animate-marquee">
        @foreach(['Bijoux', 'Personnalisé', 'Hand made', 'Or', 'Symboles', 'Diamants', 'Unassuming', 'Confiance', 'Sur mesure', 'Artisanat certifié', 'Argent', 'Pierres précieuses', 'Surprising', 'Clins d\'œil', 'Fiable', 'Unique', 'Intemporel', 'Durable', 'Créatif', 'Héritage', 'Éthique', 'Luxe', 'Finitions fines', 'Passion', 'Authenticité', 'Délicatesse', 'Subtilité', 'Exclusivité', 'Émotion', 'Inspiration', 'Éclat', 'Signature', 'Souvenir', 'Cadeau', 'Élégance', 'Charme', 'Équilibre', 'Style', 'Harmonie'] as $word)
            <span class="mx-4 font-mono text-2xl tracking-wider text-white uppercase transform -skew-x-12">{{ $word }}</span>
            <svg class="inline-block w-6 h-6 mx-2 text-white align-middle stroke-current fill-none translate-y-[-2px]" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M12 2L22 12L12 22L2 12L12 2Z" />
                <path d="M12 2L17 12L12 22L7 12L12 2Z" />
                <path d="M2 12H22" />
                <circle cx="12" cy="12" r="1.5" class="fill-current" />
            </svg>
        @endforeach
    </div>

    <div class="absolute top-0 py-2 whitespace-nowrap animate-marquee2">
        @foreach(['Bijoux', 'Personnalisé', 'Hand made', 'Or', 'Symboles', 'Diamants', 'Unassuming', 'Confiance', 'Sur mesure', 'Artisanat certifié', 'Argent', 'Pierres précieuses', 'Surprising', 'Clins d\'œil', 'Fiable', 'Unique', 'Intemporel', 'Durable', 'Créatif', 'Héritage', 'Éthique', 'Luxe', 'Finitions fines', 'Passion', 'Authenticité', 'Délicatesse', 'Subtilité', 'Exclusivité', 'Émotion', 'Inspiration', 'Éclat', 'Signature', 'Souvenir', 'Cadeau', 'Élégance', 'Charme', 'Équilibre', 'Style', 'Harmonie'] as $word)
            <span class="mx-4 font-mono text-2xl tracking-wider text-white uppercase transform -skew-x-12">{{ $word }}</span>
            <svg class="inline-block w-6 h-6 mx-2 text-white align-middle stroke-current fill-none translate-y-[-2px]" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M12 2L22 12L12 22L2 12L12 2Z" />
                <path d="M12 2L17 12L12 22L7 12L12 2Z" />
                <path d="M2 12H22" />
                <circle cx="12" cy="12" r="1.5" class="fill-current" />
            </svg>
        @endforeach
    </div>
</div>
