import colors from 'tailwindcss/colors'
import defaultTheme from 'tailwindcss/defaultTheme'

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  safelist: [
    {
      pattern: /max-w-(xl|2xl|3xl|4xl)/,
      variants: ['sm', 'md', 'lg', 'xl'],
    },
  ],
  content: [
    './packages/admin/resources/views/**/*.blade.php',
    './packages/admin/src/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        secondary: colors.slate,
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
        140: '35rem'
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        display: ['Inter', ...defaultTheme.fontFamily.mono],
      },
      fontSize: {
        xxs: ['0.625rem', { lineHeight: '1rem' }],
      },
      minHeight: {
        '(screen-content)': 'calc(100vh - 9.625rem)',
      },
      maxWidth: {
        '8xl': '88rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
