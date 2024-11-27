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
            <div class="w-full h-96 flex items-center justify-center">
                <span class="font-mono text-white text-xl">NO JEWELS FOUND</span>
            </div>
        @else
            <div 
                x-data="{ show: false }" 
                x-init="
                    $nextTick(() => { show = true });
                    $watch('show', value => {
                        if (!value) setTimeout(() => show = true, 100);
                    })
                "
                class="columns-2 md:columns-3 lg:columns-4 xl:columns-5 gap-[2px] space-y-[2px]"
                wire:loading.class="opacity-50"
                wire:target="filterJewels"
            >
                @foreach($jewels as $jewel)
                    <div 
                        x-show="show"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-4"
                        class="break-inside-avoid"
                    >
                        <livewire:catalogitem :jewel="$jewel" :key="$jewel->id" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>