<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, computed};

state(['search' => '']);
state(['selectedMaterials' => []]);
state(['selectedTypes' => []]);
state(['jewels' => []]);

$materials = computed(function () {
    return Jewel::all()->pluck('material')->flatten()->unique()->sort()->values();
});

$types = computed(function () {
    return Jewel::all()->pluck('type')->flatten()->unique()->sort()->values();
});

$filterJewels = function () {
    $query = Jewel::query();

    if ($this->search) {
        $query->where('name', 'like', '%' . $this->search . '%');
    }

    if (!empty($this->selectedMaterials)) {
        $query->where(function ($q) {
            foreach ($this->selectedMaterials as $material) {
                $q->orWhere('material', 'like', '%' . $material . '%');
            }
        });
    }

    if (!empty($this->selectedTypes)) {
        $query->where(function ($q) {
            foreach ($this->selectedTypes as $type) {
                $q->orWhere('type', 'like', '%' . $type . '%');
            }
        });
    }

    $this->jewels = $query->get();
};

// Initial load
$this->jewels = Jewel::all();
?>

<div class="bg-black">
    <!-- Filters Section -->
    <div class="divide-y-2 divide-white">
        <!-- Name Search -->
        <div class="bg-black text-white py-4">
            <label class="font-mono text-2xl uppercase tracking-tight ml-4 mb-2 block">
                Search
            </label>
            <input
                wire:model.live="search"
                wire:change="filterJewels"
                type="text"
                class="w-full border-2 border-white p-3 text-xl font-mono bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
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
                class="w-full border-2 border-white p-3 text-xl font-mono bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="SELECT MATERIALS..."
                multiple
            />
            <datalist id="materialsList">
                @foreach ($this->materials as $material)
                    <option value="{{ $material }}">{{ $material }}</option>
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
                class="w-full border-2 border-white p-3 text-xl font-mono bg-black text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                placeholder="SELECT TYPES..."
                multiple
            />
            <datalist id="typesList">
                @foreach ($this->types as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </datalist>
        </div>
    </div>

    <!-- Masonry Grid -->
    <div class="min-h-screen bg-black p-[1px]">
        @if($jewels->isEmpty())
            <div class="font-mono text-3xl uppercase text-center py-12 border-2 border-white text-white">
                No Jewels Found
            </div>
        @else
            <div class="columns-2 lg:columns-3 xl:columns-4 gap-[1px] space-y-[1px]">
                @foreach ($jewels as $index => $jewel)
                    @php
                        $media = $jewel->getMedia('jewels/images');
                        $firstMediaUrl = $media->isNotEmpty() ? 
                            $media->first()->getUrl('thumbnail') : null;
                        
                        // Random height selection
                        $heights = [
                            'h-[300px]', // Regular
                            'h-[400px]', // Tall
                            'h-[500px]', // Extra tall
                        ];
                        $randomHeight = $heights[array_rand($heights)];
                    @endphp
                    <div class="break-inside-avoid mb-[1px]">
                        <div class="relative {{ $randomHeight }} w-full group">
                            <div class="absolute inset-0 border border-white bg-black">
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