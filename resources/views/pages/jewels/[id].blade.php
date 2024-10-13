<?php

use App\Models\Jewel;
use function Livewire\Volt\{state, mount, layout};

state(["jewel" => null, "media" => []]);

layout("components.layouts.app");

mount(function ($id) {
    $this->jewel = Jewel::find($id);
    $this->media = $this->jewel->getMedia("jewels/images");
});
?>
@volt
<div>
<x-layouts.app>
       {{ $media->first->getUrl() }}

</x-layouts.app>
</div>
@endvolt
