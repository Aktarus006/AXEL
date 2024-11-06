<?php

use function Livewire\Volt\{state};
//
?>

<div class="w-full h-1/5 bg-white text-slate-900 flex">
    <a class="w-3/5 h-full hover:bg-slate-900 hover:text-white text-7xl items-center justify-center flex border border-3 border-slate-900" href="/">
        AXEL JEWELRY
    </a>
    <div class="w-1/5 flex flex-col justify-between">
        <a class="border border-x-0 border-b-0 border-1 border-slate-900 hover:underline flex h-1/3 items-center justify-center" href="/jewels">BIJOUX</a>
        <a class="border border-x-0 border-b-0 border-1 border-slate-900 hover:underline flex h-1/3 items-center justify-center" href="/collections">COLLECTIONS</a>
        <div class="border border-x-0 border-1 border-slate-900 hover:underline flex h-1/3 items-center justify-center" href="/news">BLOG</div>
    </div>
    <div class="w-1/5 flex flex-col justify-between">
        <a class="flex items-center justify-center hover:bg-slate-900 hover:text-white h-full border border-1 border-b-0 border-slate-900 cursor-pointer" href="{{ url('/') }}#about">ABOUT</a>
        <a class="flex items-center justify-center hover:bg-slate-900 hover:text-white h-full border border-1 border-slate-900 cursor-pointer" href="#contact">CONTACT</a>
    </div>
</div>
