import shopperPreset from './packages/admin/tailwind.config.preset'
import filamentPreset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
  presets: [filamentPreset, shopperPreset],
  content: [
    './packages/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
}
