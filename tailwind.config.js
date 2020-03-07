const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  theme: {
    extend: {
      colors: {
        brand: 'rgb(var(--color-brand))',
        secondary: 'rgb(var(--color-secondary))',
        'brand-hover': 'rgb(var(--color-brand-hover))',
      },
      boxShadow: {
        smooth: '0 2px 20px 0 rgba(0, 0, 0, 0.05)',
        bigger: '0 10px 20px 0 rgba(0, 0, 0, 0.01)',
      },
      spacing: {},
      fontFamily: {
        body: ["Inter var", ...defaultTheme.fontFamily.sans],
      },
    },
  },
  variants: {
    space: ['responsive'],
  },
  plugins: [
    require('@tailwindcss/ui'),
    require('./theme.config.js'),
    function ({
      addUtilities,
      theme,
      e,
      variants,
    }) {
      const spaceX = Object.fromEntries(
        Object.entries(theme('spacing')).map(([k, v]) => [
          `.${e(`space-x-${k}`)} > * + *`,
          { marginLeft: v },
        ]),
      );
      const spaceY = Object.fromEntries(
        Object.entries(theme('spacing')).map(([k, v]) => [
          `.${e(`space-y-${k}`)} > * + *`,
          { marginTop: v },
        ]),
      );

      addUtilities({ ...spaceX, ...spaceY }, variants('space'));
    },
  ],
}
