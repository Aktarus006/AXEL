<div class="font-mono">
    @if(!$showForm)
        <button
            wire:click="$set('showForm', true)"
            class="w-full px-8 py-4 text-black bg-white border-4 border-transparent uppercase font-bold tracking-wider hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200"
        >
            DEMANDE D'INFORMATION
        </button>
    @else
        <form wire:submit="submit" class="w-full border-4 border-white p-8 relative">
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
                    @error('name') <span class="text-red-700 text-sm mt-1">Ce champ est requis</span> @enderror
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
                    @error('email') <span class="text-red-700 text-sm mt-1">Email invalide</span> @enderror
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
                ></textarea>
                @error('message') <span class="text-red-700 text-sm mt-1">Le message est trop court</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button
                    type="button"
                    wire:click="$set('showForm', false)"
                    class="px-8 py-4 text-white bg-black border-4 border-white uppercase font-bold tracking-wider hover:bg-white hover:text-black transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                >
                    ANNULER
                </button>
                <button
                    type="submit"
                    class="px-8 py-4 text-black bg-white border-4 border-transparent uppercase font-bold tracking-wider hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed relative"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                >
                    <span wire:loading.remove wire:target="submit">ENVOYER</span>
                    <span wire:loading wire:target="submit" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        ENVOI EN COURS...
                    </span>
                </button>
            </div>
        </form>
    @endif

    <!-- Success Message -->
    @if(session()->has('success'))
        <div
            class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50"
            x-data
            x-init="setTimeout(() => { $el.remove(); $wire.set('showForm', false); }, 3000)"
        >
            <div class="text-white text-2xl font-mono uppercase">
                Message Envoyé Avec Succès
            </div>
        </div>
    @endif
</div>

<?php

use function Livewire\Volt\{state, rules};
use Illuminate\Support\Facades\Mail;
use App\Mail\JewelInquiry;
use Illuminate\Support\Facades\Log;

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'jewel' => null,
    'showForm' => false,
]);

rules([
    'name' => 'required|min:2',
    'email' => 'required|email',
    'message' => 'required|min:10'
]);

$mount = function($jewel) {
    $this->jewel = $jewel;
    $this->message = "Je suis intéressé(e) par {$jewel->name} au prix de €" . number_format($jewel->price, 2) . ".";
};

$submit = function() {
    Log::info('Jewel inquiry form submit function called');
    
    $this->validate();
    
    try {
        // Just for testing - we'll set success without actually sending email
        session()->flash('success', 'Message sent successfully!');
        
        // Uncomment this when ready to send actual emails
        /*
        Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new JewelInquiry(
            $this->name,
            $this->email,
            $this->message,
            $this->jewel
        ));
        */
        
        Log::info('Jewel inquiry submitted successfully');
        
        $this->name = '';
        $this->email = '';
        // Don't reset message as it will be reset when the form is hidden
    } catch (\Exception $e) {
        Log::error('Jewel inquiry error: ' . $e->getMessage());
    }
};

?>
