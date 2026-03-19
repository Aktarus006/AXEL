<?php
use function Livewire\Volt\{state};
?>

<section id="contact" class="w-full bg-black border-t-8 border-white overflow-hidden">
    <div class="w-full">
        <!-- Title & Form Section (Centered) -->
        <div class="max-w-4xl mx-auto pt-24 pb-12 px-8 md:px-12">
            <div class="mb-16">
                <span class="text-xs font-black uppercase tracking-[0.5em] text-red-700">Contact_Atelier</span>
                <h2 class="font-mono text-4xl md:text-7xl font-black text-white uppercase tracking-tighter mt-4 leading-none">DISCUTONS_ DE VOTRE PROJET_</h2>
            </div>

            <div class="mb-20 contact-form-white-labels">
                <livewire:contactform />
            </div>
        </div>

        <style>
            .contact-form-white-labels label {
                color: rgba(255, 255, 255, 0.8) !important;
            }
            .contact-form-white-labels input, 
            .contact-form-white-labels textarea {
                color: white !important;
                border-color: white !important;
            }
            .contact-form-white-labels input::placeholder,
            .contact-form-white-labels textarea::placeholder {
                color: rgba(255, 255, 255, 0.3) !important;
            }
            .contact-form-white-labels button[type="submit"] {
                background-color: white !important;
                color: black !important;
            }
            .contact-form-white-labels button[type="submit"]:hover {
                background-color: #b91c1c !important;
                color: white !important;
            }
        </style>

        <!-- Social Row (Full Width Massive Blocks) -->
        <div class="flex flex-col md:flex-row w-full border-t-8 border-white">
            <!-- Instagram Block -->
            <a href="https://www.instagram.com/axel.englebert/" target="_blank" rel="noopener noreferrer" 
               class="group flex-1 flex flex-col md:flex-row items-center justify-center py-24 px-10 bg-red-700 border-b-8 md:border-b-0 md:border-r-8 border-white hover:bg-neutral-900 transition-all duration-500">

                <div class="w-24 h-24 bg-white flex items-center justify-center group-hover:bg-red-700 transition-all duration-500 border-4 border-black group-hover:border-white shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] group-hover:shadow-[10px_10px_0px_0px_rgba(255,255,255,1)]">
                    <svg class="w-12 h-12 fill-black group-hover:fill-white transition-colors" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4.162 4.162 0 1 1 0-8.324 4.162 4.162 0 0 1 0 8.324zM18.406 3.506a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z"/>
                    </svg>
                </div>
                <div class="mt-8 md:mt-0 md:ml-12 text-center md:text-left">
                    <h3 class="text-white font-mono font-black uppercase text-4xl lg:text-5xl tracking-tighter leading-none transition-colors">INSTAGRAM_</h3>
                    <span class="text-white/70 text-sm font-bold uppercase tracking-[0.3em] block mt-2 transition-colors">@axel.englebert</span>
                </div>
            </a>

            <!-- Facebook Block -->
            <a href="https://www.facebook.com/axelenglebertbijoutier" target="_blank" rel="noopener noreferrer" 
               class="group flex-1 flex flex-col md:flex-row items-center justify-center py-20 px-10 bg-white hover:bg-red-700 transition-all duration-500">
                <div class="w-24 h-24 bg-red-700 flex items-center justify-center group-hover:bg-white transition-all duration-500 border-4 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] group-hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                    <svg class="w-12 h-12 fill-white group-hover:fill-red-700 transition-colors" viewBox="0 0 24 24">
                        <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                    </svg>
                </div>

                <div class="mt-8 md:mt-0 md:ml-12 text-center md:text-left">
                    <h3 class="text-black group-hover:text-white font-mono font-black uppercase text-4xl lg:text-5xl tracking-tighter leading-none transition-colors">FACEBOOK_</h3>
                    <span class="text-black/60 group-hover:text-white/80 text-sm font-bold uppercase tracking-[0.3em] block mt-2 transition-colors">SUIVRE L'ACTUALITÉ</span>
                </div>
            </a>
        </div>
    </div>
</section>
