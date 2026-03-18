<?php
use App\Models\Jewel;
use App\Enums\Type;
use App\Enums\Material;
use function Livewire\Volt\{state, mount, computed};

use App\Enums\Status;

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
    "showMobileFilters" => false,
]);

mount(function () {
    $this->filterJewels();
});

$filterJewels = function () {
    $query = Jewel::with(['media', 'collections'])
        ->where('online', Status::ONLINE)
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
    <div class="sticky top-20 md:top-24 z-40 bg-white border-b-4 border-black px-4 md:px-8 py-6">
        @php
            $activeCount = count($selectedMaterials) + count($selectedTypes) + ($hideNoPrice ? 1 : 0);
        @endphp
        
        <div class="max-w-[1440px] mx-auto flex flex-col xl:flex-row justify-between items-stretch xl:items-center gap-6">
            <div class="relative flex-1 flex gap-3">
                <input
                    wire:model.live.debounce.300ms="name"
                    wire:input="filterJewels"
                    class="flex-1 h-14 px-6 font-mono text-base border-4 border-black focus:bg-black focus:text-white transition-colors outline-none placeholder-black/50"
                    placeholder="RECHERCHER DANS L'ATELIER..."
                    aria-label="Rechercher par nom d'objet"
                />
                
                <!-- Mobile Filter Toggle (Visible only on mobile/tablet) -->
                <button 
                    wire:click="$toggle('showMobileFilters')" 
                    type="button"
                    class="xl:hidden h-14 px-4 border-4 border-black font-mono text-sm font-black uppercase hover:bg-black hover:text-white focus:bg-black focus:text-white focus:outline-none transition-all flex items-center gap-2 {{ $showMobileFilters ? 'bg-black text-white' : 'bg-white text-black' }}"
                >
                    FILTRES 
                    @if($activeCount)
                        <span class="bg-red-700 text-white px-2 py-0.5 text-xs">{{ $activeCount }}</span>
                    @endif
                    <span class="text-xs transition-transform {{ $showMobileFilters ? 'rotate-180' : '' }}">▼</span>
                </button>
            </div>

            <div class="{{ $showMobileFilters ? 'flex' : 'hidden xl:flex' }} flex-wrap gap-3 items-center" role="group" aria-label="Filtres de la collection">
                <button 
                    wire:click="$toggle('showMaterialDropdown')" 
                    type="button"
                    class="flex-1 sm:flex-none h-14 px-6 border-4 border-black font-mono text-sm font-black uppercase hover:bg-black hover:text-white focus:bg-black focus:text-white focus:outline-none transition-all flex items-center justify-center gap-2"
                    :class="$wire.showMaterialDropdown ? 'bg-black text-white' : 'bg-white text-black'"
                    aria-haspopup="listbox"
                    aria-expanded="{{ $showMaterialDropdown ? 'true' : 'false' }}">
                    MATÉRIAUX 
                    @if(count($selectedMaterials))
                        <span class="bg-red-700 text-white px-2 py-0.5 text-xs" aria-label="{{ count($selectedMaterials) }} sélectionnés">{{ count($selectedMaterials) }}</span>
                    @endif
                    <span class="text-xs transition-transform {{ $showMaterialDropdown ? 'rotate-180' : '' }}" aria-hidden="true">▼</span>
                </button>
                <button 
                    wire:click="$toggle('showTypeDropdown')" 
                    type="button"
                    class="flex-1 sm:flex-none h-14 px-6 border-4 border-black font-mono text-sm font-black uppercase hover:bg-black hover:text-white focus:bg-black focus:text-white focus:outline-none transition-all flex items-center justify-center gap-2"
                    :class="$wire.showTypeDropdown ? 'bg-black text-white' : 'bg-white text-black'"
                    aria-haspopup="listbox"
                    aria-expanded="{{ $showTypeDropdown ? 'true' : 'false' }}">
                    TYPES 
                    @if(count($selectedTypes))
                        <span class="bg-red-700 text-white px-2 py-0.5 text-xs" aria-label="{{ count($selectedTypes) }} sélectionnés">{{ count($selectedTypes) }}</span>
                    @endif
                    <span class="text-xs transition-transform {{ $showTypeDropdown ? 'rotate-180' : '' }}" aria-hidden="true">▼</span>
                </button>
                <button 
                    wire:click="togglePriceFilter" 
                    type="button"
                    class="w-full sm:w-auto h-14 px-6 border-4 border-black font-mono text-sm font-black uppercase hover:bg-red-700 hover:text-white focus:bg-red-700 focus:text-white focus:outline-none transition-all {{ $hideNoPrice ? 'bg-red-700 text-white border-red-700' : 'bg-white text-black' }}"
                    aria-pressed="{{ $hideNoPrice ? 'true' : 'false' }}">
                    EN VENTE UNIQUEMENT
                </button>
                @if($activeCount || $name)
                    <button 
                        wire:click="clearFilters" 
                        type="button"
                        class="w-full sm:w-auto h-14 px-6 bg-black text-white border-4 border-black font-mono text-sm font-black uppercase hover:bg-red-700 focus:bg-red-700 focus:outline-none transition-all"
                        aria-label="Réinitialiser tous les filtres">
                        RESET_ALL
                    </button>
                @endif
            </div>
        </div>

        <!-- Dropdowns with better contrast and accessibility -->
        <div class="max-w-[1440px] mx-auto relative">
            @if($showMaterialDropdown)
                <div 
                    wire:click.outside="$set('showMaterialDropdown', false)"
                    class="absolute top-4 left-0 z-50 bg-white border-4 border-black p-6 w-full md:w-80 shadow-[15px_15px_0px_0px_rgba(0,0,0,1)]" 
                    role="listbox" 
                    aria-multiselectable="true"
                >
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($materials as $material)
                            <button 
                                wire:click="addMaterial('{{ $material->value }}')" 
                                type="button"
                                role="option"
                                aria-selected="{{ in_array($material->value, $selectedMaterials) ? 'true' : 'false' }}"
                                class="text-left font-mono text-base py-3 px-4 border-2 border-transparent hover:border-black hover:bg-neutral-100 transition-all flex justify-between items-center {{ in_array($material->value, $selectedMaterials) ? 'bg-neutral-100 font-black' : '' }}">
                                <span>{{ $material->value }}</span>
                                @if(in_array($material->value, $selectedMaterials))
                                    <span class="text-red-700" aria-hidden="true">✓</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($showTypeDropdown)
                <div 
                    wire:click.outside="$set('showTypeDropdown', false)"
                    class="absolute top-4 left-0 xl:left-auto xl:right-0 z-50 bg-white border-4 border-black p-6 w-full md:w-80 shadow-[15px_15px_0px_0px_rgba(0,0,0,1)]" 
                    role="listbox" 
                    aria-multiselectable="true"
                >
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($types as $type)
                            <button 
                                wire:click="addType('{{ $type->value }}')" 
                                type="button"
                                role="option"
                                aria-selected="{{ in_array($type->value, $selectedTypes) ? 'true' : 'false' }}"
                                class="text-left font-mono text-base py-3 px-4 border-2 border-transparent hover:border-black hover:bg-neutral-100 transition-all flex justify-between items-center {{ in_array($type->value, $selectedTypes) ? 'bg-neutral-100 font-black' : '' }}">
                                <span>{{ $type->value }}</span>
                                @if(in_array($type->value, $selectedTypes))
                                    <span class="text-red-700" aria-hidden="true">✓</span>
                                @endif
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
