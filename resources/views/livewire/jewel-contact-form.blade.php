<?php

use function Livewire\Volt\{state, rules};
use App\Mail\JewelInquiry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

state([
    'jewel' => null,
    'name' => '',
    'email' => '',
    'message' => '',
    'success' => false,
]);

rules([
    'name' => 'required|min:2',
    'email' => 'required|email',
    'message' => 'required|min:10',
]);

$submit = function () {
    $this->validate();

    try {
        Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new JewelInquiry(
            $this->jewel,
            $this->name,
            $this->email,
            $this->message
        ));

        $this->success = true;
        $this->name = '';
        $this->email = '';
        $this->message = '';
    } catch (\Exception $e) {
        Log::error('Jewel inquiry error: ' . $e->getMessage());
        $this->addError('submit', 'Erreur lors de l\'envoi.');
    }
};

?>

<div class="font-mono bg-neutral-50 p-4 md:p-10 border-4 border-black relative overflow-hidden group">
    <!-- Brutalist Background Detail -->
    <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
        <div class="text-[80px] md:text-[150px] font-black leading-none">AXEL</div>
    </div>

    <form wire:submit.prevent="submit" class="relative z-10">
        <div class="mb-10 md:mb-12">
            <h2 class="text-2xl md:text-5xl font-black tracking-tighter uppercase leading-none transform -skew-x-12 inline-block bg-black text-white px-3 py-2 mb-2">
                Acquisition_
            </h2>
            <div class="h-1.5 w-20 md:w-32 bg-red-700 mt-2"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 mt-8 mb-8 md:mb-10">
            <!-- Name Input -->
            <div class="relative group/input">
                <label for="name" class="block text-[10px] uppercase tracking-[0.2em] font-black text-black/40 group-focus-within/input:text-red-700 transition-colors mb-2">
                    01_VOTRE_NOM
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    class="w-full bg-white border-b-4 border-black p-3 md:p-4 text-sm md:text-base text-black placeholder-black/10 focus:outline-none focus:bg-neutral-100 transition-all"
                    placeholder="NOM_PRÉNOM"
                />
                @error('name') <span class="text-red-700 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Email Input -->
            <div class="relative group/input">
                <label for="email" class="block text-[10px] uppercase tracking-[0.2em] font-black text-black/40 group-focus-within/input:text-red-700 transition-colors mb-2">
                    02_VOTRE_EMAIL
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    class="w-full bg-white border-b-4 border-black p-3 md:p-4 text-sm md:text-base text-black placeholder-black/10 focus:outline-none focus:bg-neutral-100 transition-all"
                    placeholder="EMAIL_ADRESSE"
                />
                @error('email') <span class="text-red-700 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Message Input -->
        <div class="relative group/input mb-8 md:mb-12">
            <label for="message" class="block text-[10px] uppercase tracking-[0.2em] font-black text-black/40 group-focus-within/input:text-red-700 transition-colors mb-2">
                03_VOTRE_MESSAGE
            </label>
            <textarea
                id="message"
                wire:model="message"
                rows="5"
                class="w-full bg-white border-b-4 border-black p-3 md:p-4 text-sm md:text-base text-black placeholder-black/10 focus:outline-none focus:bg-neutral-100 transition-all resize-none"
                placeholder="Indiquez vos précisions concernant {{ $this->jewel->name ?? '' }}..."
            ></textarea>
            @error('message') <span class="text-red-700 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-end gap-4 md:gap-8">
            <div class="hidden md:block flex-1 border-t-2 border-black/10 mb-6"></div>
            <button
                type="submit"
                class="group relative w-full md:w-auto px-10 md:px-12 py-5 md:py-6 text-white bg-black uppercase font-black text-xl md:text-2xl tracking-tighter hover:bg-red-700 transition-all overflow-hidden"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <span class="relative z-10 flex items-center justify-center gap-4">
                    <span wire:loading.remove wire:target="submit">Transmettre_</span>
                    <span wire:loading wire:target="submit">Envoi_</span>
                    <svg class="w-5 h-5 md:w-6 md:h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
                <div class="absolute inset-0 bg-red-700 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            </button>
        </div>
    </form>

    <!-- Success Message -->
    @if($success)
        <div
            class="absolute inset-0 bg-black z-50 flex flex-col items-center justify-center p-6 text-center"
            x-data
            x-init="setTimeout(() => { $wire.set('success', false) }, 5000)"
        >
            <div class="text-red-700 text-6xl md:text-8xl mb-4">✓</div>
            <h3 class="text-white text-3xl md:text-4xl font-black uppercase tracking-tighter transform -skew-x-12">Confirmé_</h3>
            <p class="text-white/60 uppercase text-[10px] md:text-xs mt-4 tracking-widest px-4">L'Atelier AXEL vous répondra sous peu.</p>
            <button @click="$wire.set('success', false)" class="mt-10 md:mt-12 text-white border-2 border-white px-6 py-2 text-[10px] font-black uppercase hover:bg-white hover:text-black transition-all">
                Fermer_
            </button>
        </div>
    @endif
</div>
