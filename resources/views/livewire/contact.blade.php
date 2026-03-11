<?php
use function Livewire\Volt\{state};
?>

<div class="w-full bg-black border-t-8 border-black">
    <div class="flex flex-col lg:flex-row w-full max-w-[1440px] mx-auto border-x-8 border-black">
        
        <!-- Social Section (1/3 Width) -->
        <div class="w-full lg:w-1/3 bg-white border-b-8 lg:border-b-0 lg:border-r-8 border-black overflow-hidden flex flex-col">
            <div class="flex-1 flex flex-col">
                <!-- Instagram Card -->
                <a href="https://www.instagram.com/axel.englebert/" target="_blank" rel="noopener noreferrer" 
                   class="group relative flex-1 min-h-[300px] border-b-8 border-black overflow-hidden bg-neutral-100">
                    
                    <!-- Scanline Overlay Effect -->
                    <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] bg-[length:100%_2px,3px_100%]"></div>

                    <!-- Background Label -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="font-mono text-[10vw] font-black opacity-5 uppercase tracking-tighter group-hover:scale-110 transition-transform duration-1000">INSTA</span>
                    </div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 p-8 flex flex-col justify-between z-10">
                        <div class="w-12 h-12 bg-black flex items-center justify-center group-hover:bg-red-700 group-hover:rotate-12 transition-all duration-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
                            <svg class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4.162 4.162 0 1 1 0-8.324 4.162 4.162 0 0 1 0 8.324zM18.406 3.506a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"/>
                            </svg>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-mono text-2xl font-black uppercase tracking-tighter">INSTAGRAM_</h3>
                            <span class="inline-block pt-2 font-black text-lg border-b-4 border-black group-hover:border-red-700 transition-colors">S'ABONNER →</span>
                        </div>
                    </div>
                    <!-- Hover Visual -->
                    <div class="absolute inset-0 bg-red-700 translate-y-full group-hover:translate-y-0 transition-transform duration-700 opacity-5"></div>
                </a>

                <!-- Facebook Card -->
                <a href="https://www.facebook.com/axelenglebertbijoutier" target="_blank" rel="noopener noreferrer" 
                   class="group relative flex-1 min-h-[300px] overflow-hidden bg-white">
                    
                    <!-- Scanline Overlay Effect -->
                    <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] bg-[length:100%_2px,3px_100%]"></div>

                    <!-- Background Label -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="font-mono text-[10vw] font-black opacity-5 uppercase tracking-tighter group-hover:scale-110 transition-transform duration-1000">FACE</span>
                    </div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 p-8 flex flex-col justify-between z-10">
                        <div class="w-12 h-12 bg-black flex items-center justify-center group-hover:bg-red-700 group-hover:-rotate-12 transition-all duration-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)]">
                            <svg class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                                <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                            </svg>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-mono text-2xl font-black uppercase tracking-tighter">FACEBOOK_</h3>
                            <span class="inline-block pt-2 font-black text-lg border-b-4 border-black group-hover:border-red-700 transition-colors">NOUS SUIVRE →</span>
                        </div>
                    </div>
                    <!-- Hover Visual -->
                    <div class="absolute inset-0 bg-red-700 translate-y-full group-hover:translate-y-0 transition-transform duration-700 opacity-5"></div>
                </a>
            </div>
        </div>

        <!-- Contact Form Section (2/3 Width) -->
        <div class="w-full lg:w-2/3 bg-black p-8 md:p-12 lg:p-24 flex flex-col justify-center border-t-8 lg:border-t-0 border-white">
            <div class="mb-12">
                <span class="text-xs font-black uppercase tracking-[0.5em] text-red-700">Contact_Atelier</span>
                <h2 class="font-mono text-5xl md:text-7xl font-black text-white uppercase tracking-tighter mt-4 leading-none">DISCUTONS_ DE VOTRE PROJET_</h2>
            </div>
            <livewire:contactform />
        </div>
    </div>
</div>
