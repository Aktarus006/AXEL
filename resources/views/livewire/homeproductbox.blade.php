<div>
    @volt
    <?php

    use function Livewire\Volt\{state, computed};
    use App\Models\Jewel;

    state('showLargeImage', false);
    state('jewelId');

    $jewel = computed(function () {
        return Jewel::find($this->jewelId);
    });

    $hover = function () {
        $this->dispatch('jewel-hovered', jewel: $this->jewel);
    };

    $getImageUrl = function () {
        if ($this->jewel && $this->jewel->hasMedia('jewels/images')) {
            return $this->jewel->getFirstMediaUrl('jewels/images');
        }
        return asset('path/to/default/image.jpg');
    };

    ?>

    <div
        class="w-full h-full flex justify-center items-center border-r-4 border-white cursor-pointer group"
        wire:mouseover="hover"
    >
        <img 
            src="{{ $getImageUrl() }}" 
            alt="{{ $jewel->name ?? 'Jewel' }}" 
            class="w-full h-full object-cover grayscale transition-all duration-500 group-hover:grayscale-0 group-hover:saturate-150"
        >
    </div>
    @endvolt
</div>
