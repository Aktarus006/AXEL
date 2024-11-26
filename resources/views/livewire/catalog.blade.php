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
    $this->jewels = Jewel::with('media')->get();
});

$filterJewels = function () {
    $this->selectedMaterials = (array) $this->selectedMaterials;
    $this->selectedTypes = (array) $this->selectedTypes;

    $this->jewels = Jewel::with('media')  // Make sure to include media relationship
        ->when(count($this->selectedMaterials) > 0, function ($query) {
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

<div class="bg-white">
    <!-- Filters Section with Brutalist Style -->
    <div class="border-b-4 border-black pb-8">
        <div class="space-y-6">
            <!-- Name Search -->
            <div>
                <label class="font-mono text-2xl uppercase tracking-tight">
                    Search
                </label>
                <input
                    type="text"
                    wire:model="name"
                    wire:input="filterJewels"
                    class="mt-2 w-full border-4 border-black p-4 text-xl font-bold placeholder-gray-500 focus:outline-none focus:ring-0"
                    placeholder="TYPE JEWEL NAME..."
                />
            </div>

            <!-- Material Filter -->
            <div>
                <label class="font-mono text-2xl uppercase tracking-tight">
                    Materials
                </label>
                <input
                    list="materialsList"
                    wire:model="selectedMaterials"
                    wire:change="filterJewels"
                    class="mt-2 w-full border-4 border-black p-4 text-xl font-bold placeholder-gray-500 focus:outline-none focus:ring-0"
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
            <div>
                <label class="font-mono text-2xl uppercase tracking-tight">
                    Types
                </label>
                <input
                    list="typesList"
                    wire:model="selectedTypes"
                    wire:change="filterJewels"
                    class="mt-2 w-full border-4 border-black p-4 text-xl font-bold placeholder-gray-500 focus:outline-none focus:ring-0"
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
                                :key="$jewel->id"
                            />
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>