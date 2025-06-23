<div class="p-4 bg-white text-black">
    <h2 class="text-xl mb-4">Test Form</h2>
    
    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label class="block mb-2">Name</label>
            <input type="text" wire:model="name" class="border p-2 w-full">
        </div>
        
        <div class="mb-4">
            <label class="block mb-2">Email</label>
            <input type="email" wire:model="email" class="border p-2 w-full">
        </div>
        
        <div class="mb-4">
            <label class="block mb-2">Message</label>
            <textarea wire:model="message" class="border p-2 w-full" rows="3"></textarea>
        </div>
        
        <button type="submit" class="bg-black text-white px-4 py-2">
            Submit Test Form
        </button>
    </form>
    
    <div class="mt-4 p-2 bg-gray-100">
        <p>Status: {{ $status }}</p>
        <p>Name: {{ $name }}</p>
        <p>Email: {{ $email }}</p>
        <p>Message: {{ $message }}</p>
    </div>
</div>

<?php
use function Livewire\Volt\{state};
use Illuminate\Support\Facades\Log;

state([
    'name' => '',
    'email' => '',
    'message' => '',
    'status' => 'Form not submitted yet'
]);

$submit = function() {
    $this->status = 'Submit function called at ' . now();
    Log::info('Test form submit function called');
};
?>
