<div class="font-mono">
    @if(!$showForm)
        <button
            wire:click="$set('showForm', true)"
            class="w-full px-8 py-6 text-black bg-white border-4 border-black uppercase font-black text-xl tracking-widest hover:bg-red-700 hover:text-white transition-all duration-300 transform hover:-translate-y-1 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]"
        >
            DEMANDER L'ACQUISITION_
        </button>
    @else
        <form wire:submit="submit" class="w-full border-2 md:border-4 border-black p-2 md:p-8 relative bg-white">
            <h2 class="text-xl md:text-3xl mb-10 md:mb-12 text-black font-black tracking-tighter uppercase border-b-4 border-black pb-4">DEMANDE D'INFORMATION_</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-8 mb-8 md:mb-12">
                <!-- Name Input -->
                <div class="relative px-2 md:px-0">
                    <label for="name" class="absolute -top-6 left-2 md:left-0 text-[10px] md:text-xs uppercase tracking-[0.2em] opacity-50 text-black">
                        01 | VOTRE NOM
                    </label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        class="w-full bg-transparent border-b-2 md:border-b-4 border-black py-3 md:py-4 text-black placeholder-black/10 focus:outline-none focus:border-red-700 transition-all duration-300"
                        placeholder="NOM_PRÉNOM"
                    />
                    @error('name') <span class="text-red-700 text-xs mt-1 uppercase">{{ $message }}</span> @enderror
                </div>

                <!-- Email Input -->
                <div class="relative px-2 md:px-0">
                    <label for="email" class="absolute -top-6 left-2 md:left-0 text-[10px] md:text-xs uppercase tracking-[0.2em] opacity-50 text-black">
                        02 | VOTRE EMAIL
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        class="w-full bg-transparent border-b-2 md:border-b-4 border-black py-3 md:py-4 text-black placeholder-black/10 focus:outline-none focus:border-red-700 transition-all duration-300"
                        placeholder="EMAIL_ADRESSE"
                    />
                    @error('email') <span class="text-red-700 text-xs mt-1 uppercase">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Message Input -->
            <div class="relative mb-8 md:mb-12 px-2 md:px-0">
                <label for="message" class="absolute -top-6 left-2 md:left-0 text-[10px] md:text-xs uppercase tracking-[0.2em] opacity-50 text-black">
                    03 | VOTRE MESSAGE
                </label>
                <textarea
                    id="message"
                    wire:model="message"
                    rows="4"
                    class="w-full bg-transparent border-b-2 md:border-b-4 border-black py-3 md:py-4 text-black placeholder-black/10 focus:outline-none focus:border-red-700 transition-all duration-300 resize-none"
                    placeholder="VOTRE MESSAGE..."
                ></textarea>
                @error('message') <span class="text-red-700 text-xs mt-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row justify-end gap-4">
                <button
                    type="button"
                    wire:click="$set('showForm', false)"
                    class="px-8 py-4 text-black bg-white border-4 border-black uppercase font-black tracking-wider hover:bg-black hover:text-white transition-all duration-300"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                >
                    ANNULER
                </button>
                <button
                    type="submit"
                    class="px-12 py-4 text-white bg-red-700 border-4 border-black uppercase font-black tracking-wider hover:bg-black transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed relative"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                >
                    <span wire:loading.remove wire:target="submit">ENVOYER LA DEMANDE_</span>
                    <span wire:loading wire:target="submit" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        ENVOI...
                    </span>
                </button>
            </div>
        </form>
    @endif

    <!-- Success Message -->
    @if(session()->has('success'))
        <div
            class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 p-8"
            x-data
            x-init="setTimeout(() => { $wire.set('showForm', false); }, 4000)"
        >
            <div class="text-white text-4xl font-black font-mono uppercase text-center border-8 border-white p-12 tracking-tighter">
                DEMANDE_ENVOYÉE_AVEC_SUCCÈS
            </div>
        </div>
    @endif
</div>
