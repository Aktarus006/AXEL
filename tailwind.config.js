/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      keyframes: {
        typing: {
          "0%": {
            width: "0%",
            visibility: "hidden",
          },
          "100%": {
            width: "100%",
          },
        },
        blink: {
          "50%": {
            borderColor: "transparent",
          },
          "100%": {
            borderColor: "white",
          },
        },
        vanish: {
          "100%": {
            display: "none",
            visibility: "hidden",
          },
        },
        marquee: {
          "0%": { transform: "translateX(0%)" },
          "100%": { transform: "translateX(-100%)" },
        },
        marquee2: {
          "0%": { transform: "translateX(100%)" },
          "100%": { transform: "translateX(0%)" },
        },
      },
      animation: {
        typing: "typing 2s steps(20) alternate infinite, blink .7s infinite",
        vanish: "vanish 4s",
        marquee: "marquee 180s linear infinite",
        marquee2: "marquee2 180s linear infinite",
      },
    },
    variants: {
      extend: {
        skew: ['hover'],
      }
    }
  },
  plugins: [],
};
