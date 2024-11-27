<div class="fixed bottom-0 left-0 w-full">
    <div class="w-full bg-black border-t-4 border-white">
        <form wire:submit="submit" class="max-w-4xl mx-auto p-8 font-mono">
            <!-- Name Input -->
            <div class="mb-6 relative group">
                <input 
                    type="text"
                    wire:model="name"
                    placeholder="YOUR NAME"
                    class="w-full bg-black text-white border-4 border-white p-4 placeholder-white/50
                        focus:outline-none focus:border-white transition-all duration-300
                        group-hover:border-white/50"
                >
                @error('name')
                    <span class="text-white mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-6 relative group">
                <input 
                    type="email"
                    wire:model="email"
                    placeholder="YOUR EMAIL"
                    class="w-full bg-black text-white border-4 border-white p-4 placeholder-white/50
                        focus:outline-none focus:border-white transition-all duration-300
                        group-hover:border-white/50"
                >
                @error('email')
                    <span class="text-white mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Message Input -->
            <div class="mb-6 relative group">
                <textarea
                    wire:model="message"
                    placeholder="YOUR MESSAGE"
                    rows="4"
                    class="w-full bg-black text-white border-4 border-white p-4 placeholder-white/50
                        focus:outline-none focus:border-white transition-all duration-300
                        group-hover:border-white/50 resize-none"
                ></textarea>
                @error('message')
                    <span class="text-white mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="relative group">
                <button
                    type="submit"
                    class="w-full bg-white text-black border-4 border-white p-4 font-bold
                        transition-all duration-300 relative z-10
                        group-hover:bg-black group-hover:text-white
                        focus:outline-none"
                >
                    SEND MESSAGE
                </button>
            </div>

            <!-- Success Message Overlay -->
            @if($showSuccess)
                <div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50">
                    <div class="text-white text-2xl font-mono tracking-widest animate-pulse">
                        MESSAGE SENT
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<?php
use function Livewire\Volt\{state, computed};

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'showSuccess' => false
]);

$submit = function() {
    $this->validate([
        'name' => 'required|min:2',
        'email' => 'required|email',
        'message' => 'required|min:10'
    ]);

    // Here you would typically send the email or save to database
    $this->showSuccess = true;

    // Reset form
    $this->name = '';
    $this->email = '';
    $this->message = '';

    // Hide success message after 3 seconds
    $this->dispatch('setTimeout', function() {
        $this->showSuccess = false;
    }, 3000);
};
?>
