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
