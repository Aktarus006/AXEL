<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount, computed};

state([
    "jewels" => [],
    "selectedMaterials" => [],
    "selectedTypes" => [],
    "name" => "",
    "materials" => Material::cases(),
    "types" => Type::cases(),
    "showFilters" => true,
    "hideNoPrice" => false,
    "materialSearch" => "",
    "typeSearch" => "",
    "showMaterialDropdown" => false,
    "showTypeDropdown" => false,
]);

mount(function () {
    $this->jewels = Jewel::with(['media', 'collections'])->get();
});

$filterJewels = function () {
    $this->selectedMaterials = (array) $this->selectedMaterials;
    $this->selectedTypes = (array) $this->selectedTypes;

    $query = Jewel::with(['media', 'collections']);

    if (count($this->selectedMaterials) > 0) {
        $query->where(function($q) {
            foreach ($this->selectedMaterials as $index => $material) {
                if ($index === 0) {
                    $q->whereJsonContains('material', $material);
                } else {
                    $q->orWhereJsonContains('material', $material);
                }
            }
        });
    }

    if (count($this->selectedTypes) > 0) {
        $query->where(function($q) {
            foreach ($this->selectedTypes as $index => $type) {
                if ($index === 0) {
                    $q->whereJsonContains('type', $type);
                } else {
                    $q->orWhereJsonContains('type', $type);
                }
            }
        });
    }

    if ($this->name) {
        $query->where('name', 'like', "%{$this->name}%");
    }

    if ($this->hideNoPrice) {
        $query->whereNotNull('price')->where('price', '>', 0);
    }

    $this->jewels = $query->get();
};

$clearFilters = function () {
    $this->selectedMaterials = [];
    $this->selectedTypes = [];
    $this->name = "";
    $this->hideNoPrice = false;
    $this->filterJewels();
};

$togglerFilters = function () {
    $this->showFilters = !$this->showFilters;
};

$togglePriceFilter = function () {
    $this->hideNoPrice = !$this->hideNoPrice;
    $this->filterJewels();
};

$addMaterial = function($material) {
    if (!in_array($material, $this->selectedMaterials)) {
        $this->selectedMaterials[] = $material;
        $this->filterJewels();
    }
    $this->materialSearch = "";
    $this->showMaterialDropdown = false;
};

$addType = function($type) {
    if (!in_array($type, $this->selectedTypes)) {
        $this->selectedTypes[] = $type;
        $this->filterJewels();
    }
    $this->typeSearch = "";
    $this->showTypeDropdown = false;
};

$getFilteredMaterials = function() {
    return array_filter($this->materials, function($material) {
        return empty($this->materialSearch) ||
            str_contains(strtolower($material->value), strtolower($this->materialSearch));
    });
};

$getFilteredTypes = function() {
    return array_filter($this->types, function($type) {
        return empty($this->typeSearch) ||
            str_contains(strtolower($type->value), strtolower($this->typeSearch));
    });
};

$closeDropdowns = function() {
    $this->showMaterialDropdown = false;
    $this->showTypeDropdown = false;
};

$removeMaterial = function($material) {
    $this->selectedMaterials = array_values(array_filter(
        $this->selectedMaterials,
        fn($m) => $m !== $material
    ));
    $this->filterJewels();
};

$removeType = function($type) {
    $this->selectedTypes = array_values(array_filter(
        $this->selectedTypes,
        fn($t) => $t !== $type
    ));
    $this->filterJewels();
};

?>

<div x-data="{ closeDropdowns: () => $wire.closeDropdowns() }" @click.away="closeDropdowns">
    <div class="min-h-screen bg-black">
        <!-- Filters Section -->
        <div class="w-full border-b border-white">
            <div class="grid w-full grid-cols-1 border-b border-white md:grid-cols-3">
                <!-- Search -->
                <div class="relative border-r border-white group">
                    <input
                        wire:model.live="name"
                        wire:input="filterJewels"
                        class="w-full h-12 px-4 font-mono text-sm text-white placeholder-gray-500 transition-all duration-300 bg-black border-0 focus:outline-none focus:ring-0 focus:bg-white focus:text-black"
                        placeholder="SEARCH..."
                    />
                    <div class="absolute inset-x-0 bottom-0 h-0.5 bg-red-700 scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left"></div>
                </div>

                <!-- Materials -->
                <div class="grid w-full grid-cols-1 border-b border-white md:grid-cols-3">
                    <div class="relative border-r border-white group">
                        <div class="flex items-center border-b border-white">
                            <input
                                wire:model.live="materialSearch"
                                wire:click="$set('showMaterialDropdown', true)"
                                class="w-full h-12 px-4 font-mono text-sm text-white placeholder-gray-500 bg-black border-0 focus:outline-none focus:ring-0"
                                placeholder="MATERIAUX..."
                            />
                        </div>

                        @if($showMaterialDropdown)
                            <div class="absolute z-50 w-full mt-1 bg-black border-2 border-white">
                                <div class="overflow-y-auto max-h-60">
                                    @foreach ($this->getFilteredMaterials() as $material)
                                        <button
                                            wire:click="addMaterial('{{ $material->value }}')"
                                            class="w-full px-4 py-2 font-mono text-sm text-left text-white transition-colors duration-200 hover:bg-white hover:text-black"
                                        >
                                            {{ $material->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Types -->
                    <div class="relative border-r border-white group">
                        <div class="flex items-center border-b border-white">
                            <input
                                wire:model.live="typeSearch"
                                wire:click="$set('showTypeDropdown', true)"
                                class="w-full h-12 px-4 font-mono text-sm text-white placeholder-gray-500 bg-black border-0 focus:outline-none focus:ring-0"
                                placeholder="TYPES..."
                            />
                        </div>

                        @if($showTypeDropdown)
                            <div class="absolute z-50 w-full mt-1 bg-black border-2 border-white">
                                <div class="overflow-y-auto max-h-60">
                                    @foreach ($this->getFilteredTypes() as $type)
                                        <button
                                            wire:click="addType('{{ $type->value }}')"
                                            class="w-full px-4 py-2 font-mono text-sm text-left text-white transition-colors duration-200 hover:bg-white hover:text-black"
                                        >
                                            {{ $type->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Selected Filters Display -->
                @if(count($selectedMaterials) > 0 || count($selectedTypes) > 0)
                    <div class="border-b border-white">
                        <div class="p-4 overflow-y-auto max-h-24">
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedMaterials as $material)
                                    <button
                                        wire:click="removeMaterial('{{ $material }}')"
                                        class="inline-flex items-center px-3 py-1 font-mono text-sm text-black transition-colors duration-300 bg-white border-2 border-white hover:bg-black hover:text-white"
                                    >
                                        {{ strtoupper($material) }} ×
                                    </button>
                                @endforeach

                                @foreach($selectedTypes as $type)
                                    <button
                                        wire:click="removeType('{{ $type }}')"
                                        class="inline-flex items-center px-3 py-1 font-mono text-sm text-white transition-colors duration-300 bg-black border-2 border-white hover:bg-white hover:text-black"
                                    >
                                        {{ strtoupper($type) }} ×
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Toggle and Clear Section -->
            <div class="flex w-full border-t border-white">
                <!-- Toggle Price Filter -->
                <div class="w-1/2 border-r border-white">
                    <button
                        wire:click="togglePriceFilter"
                        class="w-full h-12 px-4 font-mono text-sm text-white transition-colors duration-200 hover:bg-white hover:text-black focus:outline-none"
                    >
                        {{ $hideNoPrice ? '[ SHOW ALL JEWELS ]' : '[ SHOW ONLY FOR SALE ]' }}
                    </button>
                </div>

                <!-- Clear Filters -->
                <div class="w-1/2">
                    <button
                        wire:click="clearFilters"
                        class="w-full h-12 px-4 font-mono text-sm text-white transition-colors duration-200 hover:bg-white hover:text-black focus:outline-none"
                    >
                        [ CLEAR FILTERS ]
                    </button>
                </div>
            </div>
        </div>

        <!-- Jewels Display -->
        <div class="w-full bg-black">
            <!-- Results count -->
            <div class="px-4 py-2 font-mono text-sm text-white border-b border-white">
                {{ count($jewels) }} RESULT{{ count($jewels) !== 1 ? 'S' : '' }} FOUND
            </div>

            <!-- Selected Filters Display avec scroll horizontal -->
            @if(count($selectedMaterials) > 0 || count($selectedTypes) > 0)
                <div class="border-b border-white">
                    <div class="p-4 overflow-x-auto whitespace-nowrap" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <div class="inline-flex gap-2">
                            @foreach($selectedMaterials as $material)
                                <button
                                    wire:click="removeMaterial('{{ $material }}')"
                                    class="inline-flex items-center px-3 py-1 font-mono text-sm text-black transition-colors duration-300 bg-white border-2 border-white shrink-0 hover:bg-black hover:text-white"
                                >
                                    {{ strtoupper($material) }} ×
                                </button>
                            @endforeach

                            @foreach($selectedTypes as $type)
                                <button
                                    wire:click="removeType('{{ $type }}')"
                                    class="inline-flex items-center px-3 py-1 font-mono text-sm text-white transition-colors duration-300 bg-black border-2 border-white shrink-0 hover:bg-white hover:text-black"
                                >
                                    {{ strtoupper($type) }} ×
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Rest of the jewels display -->
            @if($jewels->isEmpty())
                <div class="font-mono text-3xl uppercase text-center py-12 border-4 border-white bg-black text-white mx-[1px] mt-[1px]">
                    No Jewels Found
                </div>
            @else
                <div class="columns-2 md:columns-3 lg:columns-4 xl:columns-5 gap-[0px] space-y-[0]">
                    @foreach ($jewels as $index => $jewel)
                        @php
                            $media = $jewel->getMedia('jewels/packshots');
                            $firstMediaUrl = $media->isNotEmpty() ?
                                $media->first()->getUrl('medium') : null;

                            // Create more height variations and make them depend on the index
                            // This helps prevent adjacent items from having the same height
                            $baseHeights = [
                                'h-[250px]', 'h-[280px]', 'h-[300px]', 'h-[320px]',
                                'h-[350px]', 'h-[380px]', 'h-[400px]', 'h-[420px]'
                            ];

                            // Use modulo to create a pattern that varies by column position
                            $heightIndex = ($index + floor($index / count($baseHeights))) % count($baseHeights);
                            $height = $baseHeights[$heightIndex];
                        @endphp

                        <div class="break-inside-avoid">
                            <div class="relative {{ $height }} group hover:z-10">
                                <livewire:catalogitem
                                    :jewel="$jewel"
                                    :key="$jewel->id.$index"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Cacher la scrollbar tout en gardant la fonctionnalité */
        .overflow-x-auto::-webkit-scrollbar {
            display: none;
        }
    </style>
</div>
