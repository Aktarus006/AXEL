<div
    x-data="{ 
        show: false,
        init() {
            if (!localStorage.getItem('cookie-consent')) {
                setTimeout(() => this.show = true, 1000);
            }
        },
        accept() {
            localStorage.setItem('cookie-consent', 'accepted');
            this.show = false;
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full"
    class="fixed bottom-0 left-0 w-full z-[100] bg-white border-t-8 border-black p-6 md:p-8"
    style="display: none;"
>
    <div class="max-w-[1920px] mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="font-mono text-sm md:text-base text-black font-black uppercase tracking-tight">
            <span class="bg-black text-white px-2 mr-2 text-xl">!</span>
            NOUS UTILISONS DES COOKIES POUR AMÉLIORER VOTRE EXPÉRIENCE. EN CONTINUANT, VOUS ACCEPTEZ NOTRE 
            <a href="/politique-de-confidentialite" class="underline hover:text-red-700 transition-colors">POLITIQUE_DE_CONFIDENTIALITÉ</a>.
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <button 
                @click="accept()"
                class="flex-1 md:flex-none bg-black text-white px-12 py-4 font-mono font-black uppercase hover:bg-red-700 transition-all duration-300 border-4 border-black active:translate-y-1"
            >
                ACCEPTER_
            </button>
        </div>
    </div>
</div>
