import "./bootstrap";
import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import Swup from 'swup';
import gsap from 'gsap';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(intersect)
})

/**
 * Handle Livewire 419 (Page Expired) errors by reloading the page silently.
 * This avoids the ugly browser alert.
 */
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                console.warn('Page expired (419). Reloading...');
                preventDefault(); // Stop the default browser alert
                window.location.reload();
            }
        });
    });
});

const swup = new Swup({
  containers: ['#swup'],  // explicitly define container to be safe
});

// Update CSRF token on every navigation to prevent 419 Page Expired errors
swup.hooks.on('content:replace', () => {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (newToken) {
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
                // Also update Axios default header if used
                if (window.axios) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                }
            }
        });
});

swup.hooks.replace('animation:out:await', async () => {
  await gsap.to('.transition-brutal', { opacity: 0, duration: 0.25 });
});
swup.hooks.replace('animation:in:await', async () => {
  await gsap.fromTo('.transition-brutal', { opacity: 0 }, { opacity: 1, duration: 0.25 });
});

// Fix for marquee animation
document.addEventListener('DOMContentLoaded', function() {
    // Ensure any marquee elements are properly initialized
    const marqueeElements = document.querySelectorAll('[data-marquee]');
    if (marqueeElements.length > 0) {
        console.log('Found marquee elements:', marqueeElements.length);
        // You might need to reinitialize them here if they have a specific initialization
    }
    
    // Initialize contact forms
    console.log('Initializing contact forms');
});
