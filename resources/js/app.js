import "./bootstrap";

import barba from "@barba/core";
import gsap from "gsap";

// Initialize Barba
barba.init({
  transitions: [
    {
      name: "fade",
      leave({ current, next, trigger }) {
        // Animation for leaving the page
        return gsap.to(current.container, {
          opacity: 0,
          duration: 0.5,
          ease: "power2.inOut",
          onComplete: () => current.container.remove(),
        });
      },
      enter({ current, next, trigger }) {
        // Animation for entering the new page
        return gsap.from(next.container, {
          opacity: 0,
          duration: 0.5,
          ease: "power2.inOut",
        });
      },
    },
  ],
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
