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

    $this->jewels = $query->get();
};
?>

<div class="bg-white">
    <!-- Filters Section with Brutalist Style -->
    <div class="divide-y-4 divide-black">
        <!-- Name Search -->
        <div class="bg-black text-white py-4">
            <label class="font-mono text-2xl uppercase tracking-tight ml-4 mb-2 block">
                Search
            </label>
            <input
                type="text"
                wire:model.live="name"
                wire:input="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="TYPE JEWEL NAME..."
            />
        </div>

        <!-- Material Filter -->
        <div class="bg-white py-4">
            <label class="font-mono text-2xl uppercase tracking-tight ml-4 mb-2 block">
                Materials
            </label>
            <input
                list="materialsList"
                wire:model.live="selectedMaterials"
                wire:change="filterJewels"
                class="w-full border-4 border-black p-3 text-xl font-bold placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="SELECT MATERIALS..."
                multiple
            />
            <datalist id="materialsList">
                @foreach ($materials as $material)
                    <option value="{{ $material->value }}">{{ $material->value }}</option>
                @endforeach
            </datalist>
        </div>

        <!-- Type Filter -->
        <div class="bg-black text-white py-4">
            <label class="font-mono text-2xl uppercase tracking-tight ml-4 mb-2 block">
                Types
            </label>
            <input
                list="typesList"
                wire:model.live="selectedTypes"
                wire:change="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="SELECT TYPES..."
                multiple
            />
            <datalist id="typesList">
                @foreach ($types as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </datalist>
        </div>
    </div>

    <!-- Jewels Grid with Brutalist Layout -->
    <div class="mt-8">
        @if($jewels->isEmpty())
            <div class="font-mono text-3xl uppercase text-center py-12 border-4 border-black">
                No Jewels Found
            </div>
        @else
            <div class="columns-1 md:columns-2 lg:columns-3 gap-0 space-y-0">
                @foreach ($jewels as $index => $jewel)
                    @php
                        $media = $jewel->getMedia('jewels/images');
                        $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
                        // Randomize sizes for visual interest
                        $sizeClass = match($index % 5) {
                            0 => 'h-[600px]', // Extra large
                            1 => 'h-[400px]', // Large
                            2 => 'h-[300px]', // Medium
                            3 => 'h-[500px]', // Large-medium
                            4 => 'h-[350px]', // Medium-small
                        };
                    @endphp
                    <div class="break-inside-avoid relative group hover:z-10 transform transition-transform duration-200 hover:scale-[1.02]">
                        <div class="relative {{ $sizeClass }} border-4 border-black">
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