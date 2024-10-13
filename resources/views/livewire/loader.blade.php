<?php

use function Livewire\Volt\{state};

//
?>

<div x-data="{ show: true, disableScroll: true, scrollTop: 0 }"
     x-init="scrollTop = window.pageYOffset; setTimeout(() => { show = false; disableScroll = false; window.scrollTo({top: scrollTop, behavior: 'smooth'}); }, 4000)"
     x-show="show"
     :class="{ 'overflow-hidden': disableScroll }"
     class="fixed inset-0 z-50 flex items-center justify-center p-10 bg-gradient-to-tr from-slate-700 to-slate-900"
     @keydown.window="if (disableScroll) $event.preventDefault()">
  <div class="w-max">
    <h1
      class="pr-5 overflow-hidden font-bold text-white border-r-4 animate-typing whitespace-nowrap border-r-white text-7xl">
        AXEL...
      </h1>
  </div>
</div>

