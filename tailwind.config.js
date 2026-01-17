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
        primary: "#4f46e5",   // indigo
        secondary: "#22c55e", // green
        danger: "#ef4444",    // red
        warning: "#f59e0b",   // amber
        dark: "#111827",
      },
    },
  },
  plugins: [],
}
