import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'
import defaultTheme from 'tailwindcss/defaultTheme'
import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  presets: [preset],
  safelist: [
    {
      pattern: /max-w-(xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
      variants: ['sm', 'md', 'lg', 'xl', '2xl'],
    },
  ],
  content: [
    './resources/views/**/*.blade.php',
    './src/**/*.php',
    './vendor/filament/**/*.blade.php',
    './vendor/wire-elements/modal/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        secondary: colors.gray,
        success: colors.emerald,
        warning: colors.amber,
        danger: colors.red,
        info: colors.sky,
      },
      animation: {
        progress: 'progress 2s ease-in-out infinite',
      },
      keyframes: {
        progress: {
          '0%': { backgroundPosition: '-150% 0,-150% 0' },
          '66%': { backgroundPosition: '250% 0,-150% 0' },
          '100%': { backgroundPosition: '250% 0, 250% 0' },
        },
      },
      inset: {
        '-0.5': '-0.125rem',
      },
      spacing: {
        44: '11rem',
        18: '4.5rem',
        70: '17.5rem',
        95: '23.75rem',
        125: '31.25rem',
        140: '35rem',
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        heading: ['Figtree', ...defaultTheme.fontFamily.mono],
      },
      fontSize: {
        xxs: ['0.625rem', { lineHeight: '1rem' }],
      },
      minHeight: {
        '(screen-content)': 'calc(100vh - 7.185rem)',
      },
    },
  },
  plugins: [forms, typography],
}
