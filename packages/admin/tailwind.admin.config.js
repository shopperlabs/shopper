const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  safelist: [
    {
      pattern: /max-w-(xl|2xl|3xl|4xl)/,
      variants: ['sm', 'md', 'lg', 'xl'],
    },
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        indigo: colors.blue,
        secondary: colors.slate,
        gray: colors.slate,
        orange: colors.orange,
        positive: colors.emerald,
        success: colors.emerald,
        warning: colors.amber,
        negative: colors.red,
        danger: colors.red,
        info: colors.sky,
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
      opacity: {
        15: '.15',
        30: '0.3',
        40: '0.4',
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        display: ['Mona Sans', ...defaultTheme.fontFamily.sans],
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
