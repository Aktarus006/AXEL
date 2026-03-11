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
        }
    };

    $unhover = function () {
        $this->isHovered = false;
    };

    $getImageUrl = function () {
        if ($this->jewel && $this->jewel->hasMedia('jewels/packshots')) {
            return $this->jewel->getFirstMediaUrl('jewels/packshots', 'medium');
        }
        return null;
    };

    ?>

    <a
        href="/jewels/{{ $this->jewel->id ?? '' }}"
        wire:key="jewel-box-{{ $jewelId }}"
        class="relative flex items-center justify-center w-full h-full cursor-pointer group overflow-hidden bg-white"
        wire:mouseover="hover"
        wire:mouseout="unhover"
    >
        <img
            src="{{ $getImageUrl() }}"
            alt="{{ $this->jewel->name ?? 'Jewel' }}"
            class="w-full h-full object-cover transition-all duration-700
                {{ !$isHovered ? 'grayscale' : 'grayscale-0 scale-110' }}"
        >
        
        <!-- Brutalist Overlay -->
        <div class="absolute inset-0 border-4 border-transparent group-hover:border-red-700 transition-all duration-300"></div>
        
        @if($this->jewel)
            <div class="absolute bottom-0 left-0 w-full p-4 bg-black text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 font-mono text-xs uppercase flex justify-between items-center">
                <span class="truncate font-black text-lg">{{ $this->jewel->name }}</span>
                @if($this->jewel->price > 0)
                    <span class="font-black text-xl italic ml-2">€{{ number_format($this->jewel->price, 0) }}</span>
                @endif
            </div>
        @endif
    </a>
    @endvolt
</div>
