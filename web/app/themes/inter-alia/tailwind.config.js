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
      screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1200px',
        '2xl': '1536px',
      },
      container: {
        center: true,
        padding: '1rem',
        screens: {
          'xl': '1200px',
        },
      },
    },
  },
  plugins: [],
  corePlugins: {
  },
};

export default config;
