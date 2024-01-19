import Alpine from 'alpinejs'
import Focus from '@alpinejs/focus'
import Tooltip from '@ryangjchandler/alpine-tooltip'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Sortable from 'sortablejs'
import * as FilePond from 'filepond'

import './helpers/window'
import './helpers/trix'
import internationalNumber from './plugins/internationalNumber'
import mapBox from './plugins/mapBox'
import KeyPress from './plugins/keyPress'

Alpine.plugin(Tooltip)
Alpine.plugin(Focus)
Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(KeyPress)
Alpine.data('internationalNumber', internationalNumber)
Alpine.data('mapBox', mapBox)

window.Alpine = Alpine
window.Sortable = Sortable
window.FilePond = FilePond

const theme = localStorage.getItem('theme') ?? 'system'

window.Alpine.store(
  'theme',
  theme === 'dark' ||
    (theme === 'system' &&
      window.matchMedia('(prefers-color-scheme: dark)').matches)
    ? 'dark'
    : 'light',
)

window.addEventListener('theme-changed', (event) => {
  let theme = event.detail

  localStorage.setItem('theme', theme)

  if (theme === 'system') {
    theme = window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light'
  }

  window.Alpine.store('theme', theme)
})

window
  .matchMedia('(prefers-color-scheme: dark)')
  .addEventListener('change', (event) => {
    if (localStorage.getItem('theme') === 'system') {
      window.Alpine.store('theme', event.matches ? 'dark' : 'light')
    }
  })

window.Alpine.effect(() => {
  const theme = window.Alpine.store('theme')

  theme === 'dark'
    ? document.documentElement.classList.add('dark')
    : document.documentElement.classList.remove('dark')
})

Alpine.start()
