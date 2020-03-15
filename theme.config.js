const { ThemeBuilder, Theme } = require('tailwindcss-theming');

const lightTheme = new Theme()
  .name('light')
  .default()
  .assignable()
  .colors({
    // Default Theme
    'primary-light': '#048DDB',
    'primary-default': '#007cc3',
    'primary-dark': '#1d4670',
    'primary-text': '#4b5563',
    background: '#f4f5f7',

    white: "#ffffff",
  });

const darkTheme = new Theme()
  .name('dark')
  .colors({
    // Default Theme
    'primary-light': '#374151',
    'primary-default': '#252f3f',
    'primary-dark': '#161e2e',
    'primary-text': '#e2e8f0',
    background: '#202830',

    white: "#374151",
  });

module.exports = new ThemeBuilder()
  .asClass()
  .default(lightTheme)
  .dark(darkTheme);
