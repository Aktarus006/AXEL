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

    <!-- Jewels Grid with Enhanced Brutalist Layout -->
<div class="mt-8 bg-black min-h-screen">
    @if($jewels->isEmpty())
        <div class="font-mono text-3xl uppercase text-center py-12 border-4 border-white bg-black text-white">
            No Jewels Found
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-0">
            @foreach ($jewels as $index => $jewel)
                @php
                    $media = $jewel->getMedia('jewels/images');
                    $firstMediaUrl = $media->isNotEmpty() ? $media->first()->getUrl() : null;
                    
                    // Create different size variations with smaller heights
                    $sizes = [
                        ['h-[400px] md:col-span-2', 'w-full'], // Extra large
                        ['h-[300px]', 'w-full'], // Large
                        ['h-[250px]', 'w-full'], // Medium
                        ['h-[350px] md:col-span-2', 'w-full'], // Large-medium
                        ['h-[280px]', 'w-full'], // Medium-small
                    ];
                    
                    $sizeClass = $sizes[$index % 5][0];
                    $widthClass = $sizes[$index % 5][1];
                @endphp
                <div class="flex items-center justify-center">
                    <div class="relative {{ $sizeClass }} {{ $widthClass }} group hover:z-10 transform transition-transform duration-200 hover:scale-[1.02]">
                        <div class="h-full border-2 border-white bg-black">
                            <livewire:catalogitem 
                                :jewel="$jewel" 
                                :mediaUrl="$firstMediaUrl" 
                                :key="$jewel->id.$index"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>