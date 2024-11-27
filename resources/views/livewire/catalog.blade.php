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

<div class="bg-black">
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
        <div class="bg-black text-white py-4">
            <label class="font-mono text-2xl uppercase tracking-tight ml-4 mb-2 block">
                Materials
            </label>
            <input
                list="materialsList"
                wire:model.live="selectedMaterials"
                wire:change="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
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
    <div class="w-full min-h-screen bg-black p-[1px]">
        @if($jewels->isEmpty())
            <div class="font-mono text-3xl uppercase text-center py-12 border-4 border-white bg-black text-white">
                No Jewels Found
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[1px]">
                @foreach ($jewels as $index => $jewel)
                    @php
                        $media = $jewel->getMedia('jewels/images');
                        $firstMediaUrl = $media->isNotEmpty() ? 
                            $media->first()->getUrl('thumbnail') : null;
                        
                        // Random height selection with slightly larger ranges
                        $heights = [
                            'h-[250px]', // Small
                            'h-[300px]', // Medium
                            'h-[350px]', // Medium-large
                            'h-[400px]', // Large
                        ];
                        $randomHeight = $heights[array_rand($heights)];
                    @endphp
                    <div class="w-full">
                        <div class="relative {{ $randomHeight }} w-full group hover:z-10">
                            <div class="absolute inset-0 border border-white">
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