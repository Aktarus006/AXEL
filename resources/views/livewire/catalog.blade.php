<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount};

state([
    "jewels" => [],
    "selectedMaterials" => [], 
    "selectedTypes" => [],
    "name" => "",
    "materials" => Material::cases(),
    "types" => Type::cases(),
    "showOnlyPriced" => false,
]);

mount(function () {
    $this->jewels = Jewel::with("media")->get();
});

$filterJewels = function () {
    $query = Jewel::query()->with("media");

    if (!empty($this->selectedMaterials)) {
        foreach ($this->selectedMaterials as $material) {
            if (!empty($material)) {
                $query->whereJsonContains("material", $material);
            }
        }
    }

    if (!empty($this->selectedTypes)) {
        foreach ($this->selectedTypes as $type) {
            if (!empty($type)) {
                $query->whereJsonContains("type", $type);
            }
        }
    }

    if (!empty($this->name)) {
        $query->where("name", "like", "%{$this->name}%");
    }

    if ($this->showOnlyPriced) {
        $query->where('price', '>', 0);
    }

    $this->jewels = $query->get();
};
?>

<div>
    <div class="flex flex-wrap gap-4 items-center">
        <!-- Name filter input field -->
        <div class="flex-1">
            <h2>Search by Name</h2>
            <input
                type="text"
                wire:model.live="name"
                wire:change="filterJewels"
                class="form-input mt-2 p-2 border border-gray-300 rounded w-full"
                placeholder="Enter jewel name"
            />
        </div>

        <!-- Price filter toggle -->
        <div class="flex items-center mt-8">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="showOnlyPriced" wire:change="filterJewels" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900">Show only priced items</span>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-4 mt-4">
        <div class="flex-1">
            <h2>Filter by Material</h2>
            <!-- Material filter combobox -->
            <input
                list="materialsList"
                wire:model.live="selectedMaterials"
                wire:change="filterJewels"
                class="form-select mt-2 p-2 border border-gray-300 rounded w-full"
                placeholder="Select or type material"
                multiple
            />
            <datalist id="materialsList">
                <option value="">All Materials</option>
                <option value="">-- None --</option>
                @foreach ($materials as $material)
                    <option value="{{ $material->value }}">{{ $material->value }}</option>
                @endforeach
            </datalist>
        </div>

        <div class="flex-1">
            <h2>Filter by Type</h2>
            <!-- Type filter combobox -->
            <input
                list="typesList"
                wire:model.live="selectedTypes"
                wire:change="filterJewels"
                class="form-select mt-2 p-2 border border-gray-300 rounded w-full"
                placeholder="Select or type type"
                multiple
            />
            <datalist id="typesList">
                <option value="">All Types</option>
                <option value="">-- None --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </datalist>
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
