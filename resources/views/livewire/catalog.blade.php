<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use Illuminate\Support\Facades\Log;
use function Livewire\Volt\{state, computed};

state([
    "selectedMaterial" => "", 
    "selectedType" => "",
    "name" => "",
    "materials" => Material::cases(),
    "types" => Type::cases(),
    "showOnlyPriced" => false,
]);

$jewels = computed(function () {
    $query = Jewel::query()->with(['media', 'collection']);

    if (!empty($this->selectedMaterial)) {
        $query->whereJsonContains('material', $this->selectedMaterial);
    }

    if (!empty($this->selectedType)) {
        $query->whereJsonContains('type', $this->selectedType);
    }

    if (!empty($this->name)) {
        $query->where('name', 'like', "%{$this->name}%");
    }

    if ($this->showOnlyPriced) {
        $query->where('price', '>', 0);
    }

    return $query->get();
});

$updateName = function($value) {
    $this->name = $value;
};

$updatePriceFilter = function() {
    $this->showOnlyPriced = !$this->showOnlyPriced;
};

$updateMaterial = function($value) {
    $this->selectedMaterial = $value;
};

$updateType = function($value) {
    $this->selectedType = $value;
};

$clearFilters = function() {
    $this->reset('selectedMaterial', 'selectedType', 'name', 'showOnlyPriced');
};
?>

<div>
    <div class="flex flex-wrap gap-4 items-center">
        <!-- Name filter input field -->
        <div class="flex-1">
            <h2>Search by Name</h2>
            <input
                type="text"
                wire:model.debounce.300ms="name"
                class="form-input mt-2 p-2 border border-gray-300 rounded w-full"
                placeholder="Enter jewel name"
            />
        </div>

        <!-- Price filter toggle -->
        <div class="flex items-center mt-8">
            <label class="relative inline-flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    wire:model="showOnlyPriced"
                    wire:change="updatePriceFilter"
                    class="sr-only peer"
                >
                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                <span class="ms-3 text-sm font-medium text-gray-900">Show only priced items</span>
            </label>
        </div>

        <!-- Clear filters button -->
        <button 
            wire:click="clearFilters"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium text-gray-700"
        >
            Clear Filters
        </button>
    </div>

    <div class="flex flex-wrap gap-4 mt-4">
        <div class="flex-1">
            <h2>Filter by Material</h2>
            <select
                wire:model="selectedMaterial"
                wire:change="updateMaterial($event.target.value)"
                class="form-select mt-2 p-2 border border-gray-300 rounded w-full"
            >
                <option value="">All Materials</option>
                @foreach ($materials as $material)
                    <option value="{{ $material->value }}">{{ $material->value }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1">
            <h2>Filter by Type</h2>
            <select
                wire:model="selectedType"
                wire:change="updateType($event.target.value)"
                class="form-select mt-2 p-2 border border-gray-300 rounded w-full"
            >
                <option value="">All Types</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Display filtered jewels -->
    <div wire:poll class="columns-1 sm:columns-2 lg:columns-3 gap-0 mt-6">
        @if($this->jewels->isEmpty())
            <div class="col-span-full text-center py-8 text-gray-500">
                No jewels found matching your filters.
            </div>
        @else
            @foreach ($this->jewels as $jewel)
                @php
                    $media = $jewel->getMedia('jewels/images');
                    $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
                    $randomSize = rand(1, 3);
                    $sizeClass = match($randomSize) {
                        1 => '',
                        2 => 'h-[120%] w-[120%]',
                        3 => 'h-[150%] w-[150%]'
                    };
                @endphp
                <div class="break-inside-avoid mb-0">
                    <livewire:catalogitem :jewel="$jewel" :mediaUrl="$firstMediaUrl" :key="$jewel->id" class="{{ $sizeClass }}" />
                </div>
            @endforeach
        @endif
    </div>
</div>
