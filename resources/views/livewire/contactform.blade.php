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
        $this->addError('submit', 'Error sending message. Please try again.');
    }
};
?>

<div class="w-full bg-black mt-auto">
    <form wire:submit.prevent="submit" class="w-full font-mono max-w-4xl mx-auto p-8 pb-0 relative" data-no-barba>
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Name Input -->
            <div class="relative">
                <label for="name" class="absolute -top-6 left-0 text-sm uppercase tracking-wider">
                    01 | NAME
                </label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name"
                    class="w-full bg-black border-4 border-white px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black transition-all duration-300"
                    placeholder="YOUR NAME"
                />
                @error('name') <span class="text-red-700 text-sm mt-1">{{ $message }}</span> @enderror
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
                    placeholder="YOUR EMAIL"
                />
                @error('email') <span class="text-red-700 text-sm mt-1">{{ $message }}</span> @enderror
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
                placeholder="YOUR MESSAGE"
            ></textarea>
            @error('message') <span class="text-red-700 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit"
                class="bg-white text-black px-8 py-4 uppercase font-bold tracking-wider border-4 border-transparent hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed relative"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <span wire:loading.remove wire:target="submit">SEND MESSAGE</span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    SENDING...
                </span>
            </button>
        </div>

        <!-- Success Message -->
        @if($success)
            <div 
                class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50"
                x-data
                x-init="setTimeout(() => { $el.remove(); }, 3000)"
            >
                <div class="text-white text-2xl font-mono uppercase">
                    Message Sent Successfully
                </div>
            </div>
        @endif
        <!-- Error Message -->
        @if($error)
            <div 
                class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50"
                x-data
                x-init="setTimeout(() => { $el.remove(); }, 3000)"
            >
                <div class="text-red-600 text-2xl font-mono uppercase">
                    Error sending message. Please try again.
                </div>
            </div>
        @endif
    </form>
</div>
