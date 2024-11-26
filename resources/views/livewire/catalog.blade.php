<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount};

state([
    "jewels" => [],
    "selectedMaterial" => "", 
    "selectedType" => "",
    "name" => "",
    "materials" => Material::cases(),
    "types" => Type::cases(),
    "showOnlyPriced" => false,
]);

mount(function () {
    $this->refreshJewels();
});

$refreshJewels = function () {
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

    $this->jewels = $query->get();
};

$updateName = function($value) {
    $this->name = $value;
    $this->refreshJewels();
};

$updatePriceFilter = function() {
    $this->showOnlyPriced = !$this->showOnlyPriced;
    $this->refreshJewels();
};

$updateMaterial = function($value) {
    $this->selectedMaterial = $value;
    $this->refreshJewels();
};

$updateType = function($value) {
    $this->selectedType = $value;
    $this->refreshJewels();
};
?>

<div>
    <div class="flex flex-wrap gap-4 items-center">
        <!-- Name filter input field -->
        <div class="flex-1">
            <h2>Search by Name</h2>
            <input
                type="text"
                wire:model="name"
                wire:change="updateName($event.target.value)"
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
    <div class="columns-1 sm:columns-2 lg:columns-3 gap-0 mt-6">
        @foreach ($jewels as $jewel)
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
    </div>
</div>
