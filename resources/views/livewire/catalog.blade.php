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

<div class="min-h-screen bg-black">
    <!-- Filters Section -->
    <div class="sticky top-0 z-50 bg-black divide-y-4 divide-black border-b-4 border-white">
        <!-- Name Search -->
        <div class="bg-black text-white py-4">
            <input
                type="text"
                wire:model.live="name"
                wire:input="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="SEARCH BY NAME..."
            />
        </div>

        <!-- Material Filter -->
        <div class="bg-black text-white py-4">
            <input
                list="materialsList"
                wire:model.live="selectedMaterials"
                wire:change="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="FILTER BY MATERIAL..."
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
            <input
                list="typesList"
                wire:model.live="selectedTypes"
                wire:change="filterJewels"
                class="w-full border-4 border-white p-3 text-xl font-bold bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
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
                        
                        $heights = ['h-[250px]', 'h-[300px]', 'h-[350px]', 'h-[400px]'];
                        $randomHeight = $heights[array_rand($heights)];
                    @endphp
                    
                    <div class="break-inside-avoid">
                        <div class="relative {{ $randomHeight }} group hover:z-10">
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