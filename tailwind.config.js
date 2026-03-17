/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./app/Filament/**/*.php",
    "./app/Livewire/**/*.php",
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
