import "./bootstrap";



import Swup from 'swup';
import gsap from 'gsap';

const swup = new Swup({
  containers: ['#swup'],  // explicitly define container to be safe
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
