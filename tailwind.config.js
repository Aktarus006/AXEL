/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        red: {
          700: '#b91c1c', // Darker red as secondary color
        }
      }
    },
  },
  plugins: [],
}
