/** @type {import('tailwindcss').Config} config */
const config = {
  content: [
    './app/**/*.php',
    './resources/**/*.{php,vue,js}',
    // Include block-specific files for optimal purging
    './resources/views/blocks/**/*.php',
    './resources/views/sections/**/*.php',
    './app/Blocks/**/*.php',
  ],
  theme: {
    extend: {
      colors: {},
    },
  },
  plugins: [],
  corePlugins: {
  },
};

export default config;
