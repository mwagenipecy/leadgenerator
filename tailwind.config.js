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
          'brand-red': '#C40F12',
          'brand-dark-red': '#A00E11',
          'brand-green': '#19733B',
          'brand-green-light': '#1a7f40',
          'sidebar-green': '#1D753F',
          'sidebar-green-light': '#22864A',
          'sidebar-green-dark': '#186432',
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
  