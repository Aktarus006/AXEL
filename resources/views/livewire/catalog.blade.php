<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount};

state([
    "jewels" => [],
    "selectedMaterials" => [], // Changed to array for multiple selections
    "selectedTypes" => [], // Changed to array for multiple selections
    "materials" => Material::cases(),
    "types" => Type::cases(),
]);

mount(function () {
    // Initially load all jewels
    $this->jewels = Jewel::with("media")->get();
});

$filterJewels = function () {
    $this->jewels = Jewel::when(count($this->selectedMaterials) > 0, function (
        $query
    ) {
        foreach ($this->selectedMaterials as $material) {
            $query->whereJsonContains("material", $material);
        }
    })
        ->when(count($this->selectedTypes) > 0, function ($query) {
            foreach ($this->selectedTypes as $type) {
                $query->whereJsonContains("type", $type);
            }
        })
        ->get();
};
?>

<div>
    <h2>Filter by Material</h2>

    <!-- Dropdown to filter jewels by material -->
    <select wire:model="selectedMaterials" multiple wire:change="filterJewels" class="form-select mt-2 p-2 border border-gray-300 rounded">
        <option value="">All Materials</option>
        @foreach ($materials as $material)
            <option value="{{ $material->value }}">{{ $material->value }}</option>
        @endforeach
    </select>

    <h2 class="mt-4">Filter by Type</h2>

    <!-- Dropdown to filter jewels by type -->
    <select wire:model="selectedTypes" multiple wire:change="filterJewels" class="form-select mt-2 p-2 border border-gray-300 rounded">
        <option value="">All Types</option>
        @foreach ($types as $type)
            <option value="{{ $type->value }}">{{ $type->value }}</option>
        @endforeach
    </select>

    <!-- Display filtered jewels using CatalogItem component -->
    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        @foreach ($jewels as $jewel)
        @php
                        // Access the media associated with the jewel
                        $media = $jewel->getMedia('jewels/images'); // Specify the collection name if needed
                        $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
                    @endphp


                    <livewire:catalogitem :jewel="$jewel" :mediaUrl="$firstMediaUrl" :key="$jewel->id" />
                    @endforeach
    </div>
</div>
