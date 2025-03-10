<?php

use function Livewire\Volt\{state, rules};

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'jewel' => null,
    'showForm' => false,
    'success' => false
]);

rules([
    'name' => 'required|min:2',
    'email' => 'required|email',
    'message' => 'required|min:10'
]);

$submit = function() {
    $this->validate();

    // Envoyer l'email
    Mail::to('your@email.com')->send(new JewelInquiry(
        $this->name,
        $this->email,
        $this->message,
        $this->jewel
    ));

    $this->success = true;
    $this->reset(['name', 'email', 'message']);
    $this->showForm = false;
};

?>

<div class="font-mono">
    @if(!$showForm)
        <button
            wire:click="$set('showForm', true)"
            class="w-full px-8 py-4 text-black bg-white border-4 border-transparent uppercase font-bold tracking-wider hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200"
        >
            DEMANDE D'INFORMATION
        </button>
    @else
        <form wire:submit="submit" class="w-full border-4 border-white p-8">
            <h2 class="text-2xl mb-8 text-white font-bold tracking-wider">CONTACT À PROPOS DE {{ strtoupper($jewel->name) }}</h2>

            <div class="grid grid-cols-2 gap-8 mb-8">
                <!-- Name Input -->
                <div class="relative">
                    <label for="name" class="absolute -top-6 left-0 text-sm uppercase tracking-wider">
                        01 | NOM
                    </label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        class="w-full bg-black border-4 border-white px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black transition-all duration-300"
                        placeholder="VOTRE NOM"
                    />
                    @error('name') <span class="text-red-500 text-sm mt-1">Ce champ est requis</span> @enderror
                </div>

                <!-- Email Input -->
                <div class="relative">
                    <label for="email" class="absolute -top-6 left-0 text-sm uppercase tracking-wider">
                        02 | EMAIL
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        class="w-full bg-black border-4 border-white px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black transition-all duration-300"
                        placeholder="VOTRE EMAIL"
                    />
                    @error('email') <span class="text-red-500 text-sm mt-1">Email invalide</span> @enderror
                </div>
            </div>

            <!-- Message Input -->
            <div class="relative mb-8">
                <label for="message" class="absolute -top-6 left-0 text-sm uppercase tracking-wider">
                    03 | MESSAGE
                </label>
                <textarea
                    id="message"
                    wire:model="message"
                    rows="6"
                    class="w-full bg-black border-4 border-white px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black transition-all duration-300 resize-none"
                    placeholder="VOTRE MESSAGE"
                >Je suis intéressé(e) par {{ $jewel->name }} au prix de €{{ number_format($jewel->price, 2) }}.</textarea>
                @error('message') <span class="text-red-500 text-sm mt-1">Le message est trop court</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button
                    type="button"
                    wire:click="$set('showForm', false)"
                    class="px-8 py-4 text-white bg-black border-4 border-white uppercase font-bold tracking-wider hover:bg-white hover:text-black transition-all duration-200"
                >
                    ANNULER
                </button>
                <button
                    type="submit"
                    class="px-8 py-4 text-black bg-white border-4 border-transparent uppercase font-bold tracking-wider hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200"
                >
                    ENVOYER
                </button>
            </div>
        </form>
    @endif

    <!-- Success Message -->
    @if($success)
        <div
            class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50"
            x-data
            x-init="setTimeout(() => { $el.remove(); }, 3000)"
        >
            <div class="text-white text-2xl font-mono uppercase">
                Message Envoyé Avec Succès
            </div>
        </div>
    @endif
</div>
