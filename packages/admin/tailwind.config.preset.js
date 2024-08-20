import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'
import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  darkMode: 'class',
  safelist: [
    {
      pattern: /max-w-(md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
      variants: ['sm', 'md', 'lg', 'xl', '2xl'],
    },
  ],
  content: [
    './resources/views/**/*.blade.php',
    './src/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        success: colors.emerald,
        warning: colors.amber,
        danger: colors.red,
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        heading: ['Figtree', ...defaultTheme.fontFamily.mono],
      },
    },
  },
  plugins: [forms, typography],
}
