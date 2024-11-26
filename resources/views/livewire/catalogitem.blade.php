
<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount};

state([
    "jewels" => [],
    "selectedMaterials" => [], // Ensure these are initialized as arrays
    "selectedTypes" => [],
    "name" => "",
    "materials" => Material::cases(),
    "types" => Type::cases(),
]);

mount(function () {
    $this->jewels = Jewel::with("media")->get();
});

$filterJewels = function () {
    $this->selectedMaterials = (array) $this->selectedMaterials; // Cast to array
    $this->selectedTypes = (array) $this->selectedTypes; // Cast to array

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
        ->when($this->name, function ($query) {
            $query->where("name", "like", "%{$this->name}%");
        })
        ->get();
};
?>

<div>
    <!-- Name filter input field -->
    <h2>Search by Name</h2>
    <input
        type="text"
        wire:model="name"
        wire:input="filterJewels"
        class="form-input mt-2 p-2 border border-gray-300 rounded"
        placeholder="Enter jewel name"
    />

    <h2 class="mt-4">Filter by Material</h2>
    <!-- Material filter combobox -->
    <input
        list="materialsList"
        wire:model="selectedMaterials"
        wire:change="filterJewels"
        class="form-select mt-2 p-2 border border-gray-300 rounded"
        placeholder="Select or type material"
        multiple
    />
    <datalist id="materialsList">
        <option value="">All Materials</option>
        @foreach ($materials as $material)
            <option value="{{ $material->value }}">{{ $material->value }}</option>
        @endforeach
    </datalist>

    <h2 class="mt-4">Filter by Type</h2>
    <!-- Type filter combobox -->
    <input
        list="typesList"
        wire:model="selectedTypes"
        wire:change="filterJewels"
        class="form-select mt-2 p-2 border border-gray-300 rounded"
        placeholder="Select or type type"
        multiple
    />
    <datalist id="typesList">
        <option value="">All Types</option>
        @foreach ($types as $type)
            <option value="{{ $type->value }}">{{ $type->value }}</option>
        @endforeach
    </datalist>

    <!-- Display filtered jewels -->
    <div class="max-w-screen mt-6 grid grid-cols-1 gap-y-10 sm:grid-cols-3 lg:grid-cols-3">
        @foreach ($jewels as $jewel)
            @php
                $media = $jewel->getMedia('jewels/images');
                $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
            @endphp
            <livewire:catalogitem :jewel="$jewel" :mediaUrl="$firstMediaUrl" :key="$jewel->id" />
        @endforeach
    </div>
</div>