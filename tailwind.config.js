const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
  darkMode: 'class',
  purge: [
    './resources/assets/js/**/*.js',
    './resources/views/**/*.php',
    './src/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        'light-blue': colors.lightBlue
      },
      inset: {
        '-0.5': '-0.125rem',
      },
      spacing: {
        44: '11rem',
        18: '4.5rem',
        95: "23.75rem",
        125: "31.25rem",
        140: "35rem"
      },
      opacity: {
        15: '.15',
        30: '0.3'
      },
      minHeight: {
        '(screen-content)': 'calc(100vh - 9.625rem)',
      },
      fontFamily: {
        sans: ['Manrope', ...defaultTheme.fontFamily.sans]
      }
    },
  },
  variants: {
    extend: {
      translate: ['group-hover'],
      backgroundColor: ['group-hover', 'focus-within', 'odd'],
      textColor: ['group-hover', 'focus-within', 'odd'],
      borderWidth: ['odd'],
      opacity: ['group-hover'],
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
  ]
};
