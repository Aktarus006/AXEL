<div>
    @volt
    <?php

    use function Livewire\Volt\{state, computed};
    use App\Models\Jewel;

    state('showLargeImage', false);
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
                    ['original_url' => $this->jewel->getFirstMediaUrl('jewels/images', 'large')]
                ]
            ]);
        }
    };

    $unhover = function () {
        $this->isHovered = false;
        $this->dispatch('jewel-unhovered');
    };

    $getImageUrl = function () {
        if ($this->jewel && $this->jewel->hasMedia('jewels/images')) {
            return $this->jewel->getFirstMediaUrl('jewels/images', 'thumb');
        }
        return asset('path/to/default/image.jpg');
    };

    ?>

    <div
        class="w-full h-full flex justify-center items-center cursor-pointer group"
        wire:mouseover="hover"
        wire:mouseout="unhover"
    >
        <img 
            src="{{ $getImageUrl() }}" 
            alt="{{ $jewel->name ?? 'Jewel' }}" 
            class="w-full h-full object-cover transition-all duration-500
                {{ !$isHovered ? 'grayscale' : 'grayscale-0 saturate-150' }}"
        >
    </div>
    @endvolt
</div>
