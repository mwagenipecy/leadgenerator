// tailwind.config.js
export default {
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './resources/**/*.vue',
    ],
    theme: {
      extend: {
        colors: {
          'brand-red': '#C40F11',
          'brand-dark-red': '#A00E11',
          // Legacy token kept for existing UI, mapped to new palette (no green)
          'brand-green': '#C40F11',        // red
          'brand-green-light': '#999999',  // gray
          'brand-gray': '#999999',
          // Sidebar theme color (requested change: green -> red)
          // Primary requested color: #C40F11
          'sidebar-green': '#C40F11',
          // Slightly lighter red for hover states
          'sidebar-green-light': '#D63A3C',
          // Darker red for gradient depth
          'sidebar-green-dark': '#A00E11',
          'sidebar-black': '#0A0A0A',
          'sidebar-gray': '#1F1F1F',
          'accent-gray': '#F8F9FA',
        },
        fontFamily: {
          inter: ['Inter', 'sans-serif'],
          poppins: ['Poppins', 'sans-serif'],
        },
      },
    },
    plugins: [],
  }
  