/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
    colors: {
      primary: "#1d4ed8",    // Bleu ATP
      secondary: "#7c3aed",  // Violet ITF
      accent: "#2dd4bf",     // Turquoise pour les accents
    }
  },
  plugins: [],
}
