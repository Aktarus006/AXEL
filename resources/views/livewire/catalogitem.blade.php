<?php
use App\Models\Jewel;
use function Livewire\Volt\{state, mount};

state([
    "key" => "",
    "jewel" => null,
    "packshotUrl" => "",
    "lifestyleUrl" => "",
    "startInColor" => false,
]);

mount(function (Jewel $jewel = null) {
    if ($jewel) {
        $this->jewel = $jewel;
        $packshot = $jewel->getMedia('jewels/packshots')->first();
        $lifestyle = $jewel->getMedia('jewels/lifestyle')->first();

        if ($packshot) {
            $this->packshotUrl = $packshot->getUrl('medium');
        }

        if ($lifestyle) {
            $this->lifestyleUrl = $lifestyle->getUrl('medium');
        }
    }
});
?>

<div 
    class="relative w-full h-full cursor-pointer group bg-black"
    x-data="{ 
        inColor: false,
        init() {
            setTimeout(() => {
                this.inColor = true;
            }, Math.random() * 7000 + 1000);
        }
    }"
>
    <a href="/jewels/{{ $this->jewel->id ?? '' }}" class="block w-full h-full relative overflow-hidden transition-all duration-500">
        <!-- Image Container -->
        <div class="relative w-full h-full overflow-hidden flex flex-col">
            <div class="relative flex-1 overflow-hidden">
                @if($packshotUrl)
                    <img
                        src="{{ $packshotUrl }}"
                        alt="Photographie de {{ $this->jewel->name ?? 'bijou' }} AXEL"
                        width="600"
                        height="600"
                        class="absolute inset-0 object-cover w-full h-full transition-all duration-1000 group-hover:grayscale-0 group-hover:scale-105"
                        :class="inColor ? 'grayscale-0' : 'grayscale'"
                        loading="lazy"
                    />
                @endif

                @if($lifestyleUrl)
                    <img
                        src="{{ $lifestyleUrl }}"
                        alt="Vue portée de {{ $this->jewel->name ?? 'bijou' }}"
                        width="600"
                        height="600"
                        @class([
                            "absolute inset-0 object-cover w-full h-full transition-opacity duration-700",
                            "opacity-0 group-hover:opacity-100" => $packshotUrl,
                            "opacity-100" => !$packshotUrl
                        ])
                        loading="lazy"
                    />
                @endif
                
                @if(!$packshotUrl && !$lifestyleUrl)
                    <div class="absolute inset-0 flex items-center justify-center bg-neutral-900">
                        <span class="font-mono text-[10px] text-white/20 uppercase font-black">IMAGE_A_VENIR</span>
                    </div>
                @endif
                
                <!-- Overlay details on hover -->
                <div class="absolute inset-0 bg-red-700/0 group-hover:bg-red-700/10 transition-colors duration-500"></div>
                
                <div class="absolute top-0 left-0 p-4 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                    <div class="bg-black text-white font-mono text-[10px] px-2 py-1 uppercase tracking-widest border border-white">
                        PIÈCE_N°{{ $this->jewel->id ?? '00' }}
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="p-6 bg-white border-t-4 border-black group-hover:bg-red-700 group-hover:text-white transition-all duration-500">
                <div class="flex justify-between items-end">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-mono text-2xl font-black uppercase leading-none truncate pr-4">{{ $this->jewel->name ?? 'Sans titre' }}</h3>
                        <div class="mt-2 flex gap-4">
                            <span class="font-mono text-[10px] uppercase opacity-50 font-bold group-hover:opacity-100 transition-opacity tracking-widest">Fait main à l'Atelier</span>
                        </div>
                    </div>
                    <div class="text-right min-w-[100px]">
                        @if($this->jewel && $this->jewel->price > 0)
                            <span class="font-mono text-3xl font-black italic tracking-tighter leading-none">{{ number_format($this->jewel->price, 0, '.', ' ') }} €</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
