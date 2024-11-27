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
]);

mount(function () {
    $this->jewels = Jewel::with(['media', 'collection'])->get();
});

$filterJewels = function () {
    $this->selectedMaterials = (array) $this->selectedMaterials;
    $this->selectedTypes = (array) $this->selectedTypes;

    $query = Jewel::with(['media', 'collection']);

    if (count($this->selectedMaterials) > 0) {
        foreach ($this->selectedMaterials as $material) {
            $query->whereJsonContains('material', $material);
        }
    }

    if (count($this->selectedTypes) > 0) {
        foreach ($this->selectedTypes as $type) {
            $query->whereJsonContains('type', $type);
        }
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

?>

<div class="min-h-screen bg-black">
    <!-- Filters Section -->
    <div class="w-full border-b border-white">
        <div class="w-full grid grid-cols-1 md:grid-cols-3 border-b border-white">
            <!-- Search -->
            <div class="border-r border-white">
                <input
                    wire:model.live="name"
                    wire:input="filterJewels"
                    class="w-full h-12 px-4 text-sm font-mono bg-black text-white border-0 focus:outline-none focus:ring-0 placeholder-gray-500"
                    placeholder="SEARCH..."
                />
            </div>

            <!-- Materials -->
            <div class="border-r border-white">
                <input
                    list="materialsList"
                    wire:model.live="selectedMaterials"
                    wire:change="filterJewels"
                    class="w-full h-12 px-4 text-sm font-mono bg-black text-white border-0 focus:outline-none focus:ring-0 placeholder-gray-500"
                    placeholder="FILTER BY MATERIAL..."
                    multiple
                />
                <datalist id="materialsList">
                    @foreach ($materials as $material)
                        <option value="{{ $material->value }}">{{ $material->value }}</option>
                    @endforeach
                </datalist>
            </div>

            <!-- Types -->
            <div>
                <input
                    list="typesList"
                    wire:model.live="selectedTypes"
                    wire:change="filterJewels"
                    class="w-full h-12 px-4 text-sm font-mono bg-black text-white border-0 focus:outline-none focus:ring-0 placeholder-gray-500"
                    placeholder="FILTER BY TYPE..."
                    multiple
                />
                <datalist id="typesList">
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">{{ $type->value }}</option>
                    @endforeach
                </datalist>
            </div>
        </div>

        <!-- Toggle and Clear Section -->
        <div class="w-full flex border-t border-white">
            <!-- Toggle Price Filter -->
            <div class="w-1/2 border-r border-white">
                <button 
                    wire:click="togglePriceFilter"
                    class="w-full h-12 px-4 font-mono text-sm text-white hover:bg-white hover:text-black transition-colors duration-200 focus:outline-none"
                >
                    {{ $hideNoPrice ? '[ SHOW ALL JEWELS ]' : '[ SHOW ONLY FOR SALE ]' }}
                </button>
            </div>

            <!-- Clear Filters -->
            <div class="w-1/2">
                <button 
                    wire:click="clearFilters"
                    class="w-full h-12 px-4 font-mono text-sm text-white hover:bg-white hover:text-black transition-colors duration-200 focus:outline-none"
                >
                    [ CLEAR FILTERS ]
                </button>
            </div>
        </div>
    </div>

    <!-- Jewels Display -->
    <div class="w-full bg-black px-[1px]">
        @if($jewels->isEmpty())
            <div class="font-mono text-3xl uppercase text-center py-12 border-4 border-white bg-black text-white mx-[1px] mt-[1px]">
                No Jewels Found
            </div>
        @else
            <div class="columns-2 md:columns-3 lg:columns-4 xl:columns-5 gap-[1px] space-y-[1px]">
                @foreach ($jewels as $index => $jewel)
                    @php
                        $media = $jewel->getMedia('jewels/images');
                        $firstMediaUrl = $media->isNotEmpty() ? 
                            $media->first()->getUrl('thumbnail') : null;
                        
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
                                :mediaUrl="$firstMediaUrl" 
                                :key="$jewel->id.$index"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>