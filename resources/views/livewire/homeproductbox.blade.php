<div>
    @volt
    <?php

    use function Livewire\Volt\{state, computed};
    use App\Models\Jewel;

    state('jewelId');
    state('isHovered', false);

    $jewel = computed(function () {
        return Jewel::with('media')->find($this->jewelId);
    });

    $hover = function () {
        if ($this->jewel) {
            $this->isHovered = true;
            $this->dispatch('jewel-hovered', jewel: [
                'id' => $this->jewel->id,
                'name' => $this->jewel->name,
                'price' => $this->jewel->price,
                'media' => [
                    ['original_url' => $this->jewel->getFirstMediaUrl('jewels/packshots', 'large')]
                ]
            ]);
        }
    };

    $unhover = function () {
        $this->isHovered = false;
        $this->dispatch('jewel-unhovered');
    };

    $getImageUrl = function () {
        if ($this->jewel && $this->jewel->hasMedia('jewels/packshots')) {
            return $this->jewel->getFirstMediaUrl('jewels/packshots', 'medium');
        }
        return null;
    };

    ?>

    <a
        href="/jewels/{{ $jewelId }}"
        wire:key="jewel-box-{{ $jewelId }}"
        class="relative flex items-center justify-center w-full h-full cursor-pointer group overflow-hidden bg-white"
        wire:mouseover="hover"
        wire:mouseout="unhover"
    >
        <img
            src="{{ $getImageUrl() }}"
            alt="{{ $jewel->name ?? 'Jewel' }}"
            class="w-full h-full object-cover transition-all duration-700
                {{ !$isHovered ? 'grayscale' : 'grayscale-0 scale-110' }}"
        >
        
        <!-- Brutalist Overlay -->
        <div class="absolute inset-0 border-4 border-transparent group-hover:border-black transition-all duration-300"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-2 bg-black text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 font-mono text-xs uppercase flex justify-between items-center">
            <span class="truncate">{{ $jewel->name }}</span>
            <span class="font-bold ml-2">€{{ number_format($jewel->price, 0) }}</span>
        </div>

        <div class="absolute top-2 right-2 bg-red-600 text-white font-mono text-[10px] px-2 py-1 transform rotate-12 opacity-0 group-hover:opacity-100 transition-opacity duration-300 border-2 border-black font-black">
            NEW
        </div>
    </a>
    @endvolt
</div>
