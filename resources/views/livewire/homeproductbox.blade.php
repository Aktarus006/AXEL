<div>
    @volt
    <?php

    use function Livewire\Volt\{state, computed};
    use App\Models\Jewel;

    state('showLargeImage', false);
    state('jewelId');
    state('isHovered', false);

    $jewel = computed(function () {
        return Jewel::with(['media'])->find($this->jewelId);
    });

    $hover = function () {
        $this->isHovered = true;
        $this->dispatch('jewel-hovered', jewel: $this->jewel);
    };

    $unhover = function () {
        $this->isHovered = false;
    };

    $getImageUrl = function () {
        if ($this->jewel && $this->jewel->hasMedia('jewels/images')) {
            return $this->jewel->getFirstMediaUrl('jewels/images');
        }
        return asset('path/to/default/image.jpg');
    };

    ?>

    <div 
        class="group relative w-full h-full overflow-hidden border-4 border-white transition-all duration-300"
        wire:mouseover="hover"
        wire:mouseout="unhover"
        x-data="{ showDetails: false }"
        @mouseenter="showDetails = true"
        @mouseleave="showDetails = false"
    >
        <!-- Main Image -->
        <div class="w-full h-full transform transition-transform duration-500 group-hover:scale-110">
            <img 
                src="{{ $getImageUrl() }}" 
                alt="{{ $jewel->name ?? 'Jewel' }}" 
                class="w-full h-full object-cover"
            >
        </div>

        <!-- Overlay -->
        <div 
            x-show="showDetails"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-full"
            class="absolute inset-0 bg-black bg-opacity-80 flex flex-col justify-end p-4 font-mono"
        >
            @if($jewel)
                <!-- Title -->
                <h3 class="text-white text-xl uppercase mb-2 tracking-wider">
                    {{ $jewel->name }}
                </h3>

                <!-- Price -->
                <div class="flex justify-between items-center">
                    <span class="text-white text-lg">
                        €{{ number_format($jewel->price, 2) }}
                    </span>
                    
                    <!-- Type Badge -->
                    @if($jewel->type)
                        <span class="bg-white text-black px-3 py-1 text-sm uppercase">
                            {{ $jewel->type }}
                        </span>
                    @endif
                </div>

                <!-- Material -->
                @if($jewel->material)
                    <div class="mt-2 text-white text-sm opacity-75 uppercase">
                        {{ $jewel->material }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Corner Accent -->
        <div class="absolute top-0 right-0 w-8 h-8 border-l-4 border-b-4 border-white transform rotate-0 transition-transform duration-300 group-hover:rotate-90"></div>
    </div>
    @endvolt
</div>
