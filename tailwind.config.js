/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [{
      maintheme: {
        "primary": "#1d4ed8",
        "secondary": "#7c3aed",
        "accent": "#2dd4bf",
        "info": "#ddde10",
        "neutral": "#2a323c",
        "base-100": "#ffffff",
      },
    }],
  },
}
