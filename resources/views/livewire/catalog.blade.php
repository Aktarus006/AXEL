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
    "showFilters" => true,
    "hideNoPrice" => false,
    "materialSearch" => "",
    "typeSearch" => "",
    "showMaterialDropdown" => false,
    "showTypeDropdown" => false,
]);

mount(function () {
    $this->filterJewels();
});

$filterJewels = function () {
    $query = Jewel::with(['media', 'collections'])
        ->whereHas('media'); // Show all with pictures

    if (count($this->selectedMaterials) > 0) {
        $query->where(function($q) {
            foreach ($this->selectedMaterials as $index => $material) {
                $index === 0 ? $q->whereJsonContains('material', $material) : $q->orWhereJsonContains('material', $material);
            }
        });
    }

    if (count($this->selectedTypes) > 0) {
        $query->where(function($q) {
            foreach ($this->selectedTypes as $index => $type) {
                $index === 0 ? $q->whereJsonContains('type', $type) : $q->orWhereJsonContains('type', $type);
            }
        });
    }

    if ($this->name) {
        $query->where('name', 'like', "%{$this->name}%");
    }

    if ($this->hideNoPrice) {
        $query->where('price', '>', 0);
    }

    $this->jewels = $query->inRandomOrder()->get();
};

$clearFilters = function () {
    $this->selectedMaterials = [];
    $this->selectedTypes = [];
    $this->name = "";
    $this->hideNoPrice = false;
    $this->filterJewels();
};

$togglePriceFilter = function () {
    $this->hideNoPrice = !$this->hideNoPrice;
    $this->filterJewels();
};

$addMaterial = function($material) {
    if (!in_array($material, $this->selectedMaterials)) {
        $this->selectedMaterials[] = $material;
        $this->filterJewels();
    }
    $this->showMaterialDropdown = false;
};

$addType = function($type) {
    if (!in_array($type, $this->selectedTypes)) {
        $this->selectedTypes[] = $type;
        $this->filterJewels();
    }
    $this->showTypeDropdown = false;
};

$removeMaterial = function($material) {
    $this->selectedMaterials = array_values(array_filter($this->selectedMaterials, fn($m) => $m !== $material));
    $this->filterJewels();
};

$removeType = function($type) {
    $this->selectedTypes = array_values(array_filter($this->selectedTypes, fn($t) => $t !== $type));
    $this->filterJewels();
};

?>

<div class="bg-white min-h-screen">
    <!-- Filter Bar -->
    <div class="sticky top-20 md:top-24 z-40 bg-white border-b-4 border-black px-4 md:px-8 py-4">
        <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            <div class="relative flex-1 max-w-md">
                <input
                    wire:model.live.debounce.300ms="name"
                    wire:input="filterJewels"
                    class="w-full h-12 px-4 font-mono text-sm border-4 border-black focus:bg-red-700 focus:text-white transition-colors outline-none placeholder-black/30"
                    placeholder="RECHERCHER DANS L'ATELIER..."
                />
            </div>

            <div class="flex flex-wrap gap-2 items-center">
                <button wire:click="$toggle('showMaterialDropdown')" class="px-4 py-2 border-2 border-black font-mono text-xs font-bold hover:bg-black hover:text-white transition-colors">
                    MATÉRIAUX {{ count($selectedMaterials) ? '('.count($selectedMaterials).')' : '' }}
                </button>
                <button wire:click="$toggle('showTypeDropdown')" class="px-4 py-2 border-2 border-black font-mono text-xs font-bold hover:bg-black hover:text-white transition-colors">
                    TYPES {{ count($selectedTypes) ? '('.count($selectedTypes).')' : '' }}
                </button>
                <button wire:click="togglePriceFilter" class="px-4 py-2 border-2 border-black font-mono text-xs font-bold hover:bg-red-700 hover:text-white transition-colors {{ $hideNoPrice ? 'bg-red-700 text-white' : '' }}">
                    EN VENTE UNIQUEMENT
                </button>
                @if(count($selectedMaterials) || count($selectedTypes) || $name || $hideNoPrice)
                    <button wire:click="clearFilters" class="px-4 py-2 bg-red-600 text-white border-2 border-black font-mono text-xs font-bold hover:bg-black transition-colors">
                        RÉINITIALISER
                    </button>
                @endif
            </div>
        </div>

        <!-- Dropdowns -->
        <div class="max-w-[1440px] mx-auto relative">
            @if($showMaterialDropdown)
                <div class="absolute top-2 left-0 z-50 bg-white border-4 border-black p-4 w-full md:w-64 shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($materials as $material)
                            <button wire:click="addMaterial('{{ $material->value }}')" class="text-left font-mono text-sm hover:text-red-700 {{ in_array($material->value, $selectedMaterials) ? 'font-black underline' : '' }}">
                                {{ $material->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($showTypeDropdown)
                <div class="absolute top-2 left-0 md:left-40 z-50 bg-white border-4 border-black p-4 w-full md:w-64 shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($types as $type)
                            <button wire:click="addType('{{ $type->value }}')" class="text-left font-mono text-sm hover:text-red-700 {{ in_array($type->value, $selectedTypes) ? 'font-black underline' : '' }}">
                                {{ $type->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Gapless Masonry Grid -->
    <div class="w-full border-b-4 border-black">
        @if($jewels->isEmpty())
            <div class="py-40 text-center bg-white">
                <h3 class="font-mono text-4xl font-black uppercase text-black">Aucun bijou trouvé_</h3>
                <button wire:click="clearFilters" class="mt-8 px-8 py-4 bg-black text-white font-mono font-bold uppercase">Voir toute la collection</button>
            </div>
        @else
            <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-0 space-y-0 border-l-4 border-black">
                @foreach ($jewels as $index => $jewel)
                    @php
                        $heights = ['h-[400px]', 'h-[550px]', 'h-[450px]', 'h-[650px]', 'h-[500px]'];
                        $height = $heights[$index % count($heights)];
                    @endphp
                    <div class="break-inside-avoid border-r-4 border-b-4 border-black relative">
                        <div class="{{ $height }}">
                            <livewire:catalogitem
                                :jewel="$jewel"
                                :wire:key="'catalog-item-'.$jewel->id.'-'.$index"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
