<div class="w-full bg-black mt-auto">
    <form wire:submit.prevent="submit" class="w-full font-mono max-w-4xl mx-auto p-8 pb-0">
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
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit"
                class="bg-white text-black px-8 py-4 uppercase font-bold tracking-wider border-4 border-transparent hover:bg-red-700 hover:text-white hover:border-white transition-all duration-200"
            >
                SEND MESSAGE
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
    </form>
</div>

<?php
use function Livewire\Volt\{state};

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'success' => false
]);

$submit = function() {
    $this->success = true;
    $this->name = '';
    $this->email = '';
    $this->message = '';
};
?>
