/** @type {import('tailwindcss').Config} config */
const config = {
  content: [
    './app/**/*.php',
    './resources/**/*.{php,vue,js}',
    // Include block-specific files for optimal purging
    './resources/views/blocks/**/*.php',
    './app/Blocks/**/*.php',
  ],
  theme: {
    extend: {
      colors: {}, // Extend Tailwind's default colors
    },
  },
  plugins: [],
  // Optimize for performance - only include used utilities
  corePlugins: {
    // Enable all core plugins by default
    // Disable specific ones if needed for smaller bundle
  },
};

export default config;
