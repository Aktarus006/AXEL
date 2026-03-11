<?php

use function Livewire\Volt\{state, rules, action};
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'success' => false,
    'error' => false,
]);

rules([
    'name' => 'required|min:2',
    'email' => 'required|email',
    'message' => 'required|min:10'
]);

$submit = function() {  Log::info('Contact form submit function called');
    $this->validate();
    $this->success = false;
    $this->error = false;

    try {
        Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new ContactFormMail(
            $this->name,
            $this->email,
            $this->message
        ));

        Log::info('Contact form submitted successfully');
        $this->success = true;
        $this->name = '';
        $this->email = '';
        $this->message = '';
    } catch (\Exception $e) {
        Log::error('Contact form error: ' . $e->getMessage());
        $this->error = true;
        $this->addError('submit', 'Erreur lors de l\'envoi. Veuillez réessayer.');
    }
};
?>

<div class="w-full bg-black mt-auto pb-12">
    <form wire:submit.prevent="submit" class="w-full font-mono max-w-4xl mx-auto p-8 relative" data-no-barba>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
            <!-- Name Input -->
            <div class="relative">
                <label for="name" class="absolute -top-6 left-0 text-xs uppercase tracking-[0.2em] opacity-50 text-white">
                    01 | VOTRE NOM
                </label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name"
                    class="w-full bg-transparent border-b-4 border-white py-4 px-2 text-white placeholder-white/40 focus:outline-none focus:border-red-700 transition-all duration-300"
                    placeholder="NOM_PRÉNOM"
                />
                @error('name') <span class="text-red-700 text-sm mt-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <!-- Email Input -->
            <div class="relative">
                <label for="email" class="absolute -top-6 left-0 text-xs uppercase tracking-[0.2em] opacity-50 text-white">
                    02 | VOTRE EMAIL
                </label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email"
                    class="w-full bg-transparent border-b-4 border-white py-4 px-2 text-white placeholder-white/40 focus:outline-none focus:border-red-700 transition-all duration-300"
                    placeholder="EMAIL_ADRESSE"
                />
                @error('email') <span class="text-red-700 text-sm mt-1 uppercase">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Message Input -->
        <div class="relative mb-12">
            <label for="message" class="absolute -top-6 left-0 text-xs uppercase tracking-[0.2em] opacity-50 text-white">
                03 | VOTRE PROJET
            </label>
            <textarea 
                id="message"
                wire:model="message"
                rows="4"
                class="w-full bg-transparent border-b-4 border-white py-4 px-2 text-white placeholder-white/40 focus:outline-none focus:border-red-700 transition-all duration-300 resize-none"
                placeholder="DÉCRIVEZ VOTRE DEMANDE ICI..."
            ></textarea>
            @error('message') <span class="text-red-700 text-sm mt-1 uppercase">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex">
            <button 
                type="submit"
                class="w-full md:w-auto bg-white text-black px-12 py-6 uppercase font-black text-xl tracking-widest hover:bg-red-700 hover:text-white transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed relative"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <span wire:loading.remove wire:target="submit">ENVOYER LE MESSAGE_</span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ENVOI...
                </span>
            </button>
        </div>

        <!-- Success Message -->
        @if($success)
            <div 
                class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 p-8"
                x-data
                x-init="setTimeout(() => { $wire.set('success', false) }, 4000)"
            >
                <div class="text-white text-4xl font-black font-mono uppercase text-center border-8 border-white p-12 tracking-tighter">
                    MESSAGE_ENVOYÉ_AVEC_SUCCÈS
                </div>
            </div>
        @endif
    </form>
</div>
