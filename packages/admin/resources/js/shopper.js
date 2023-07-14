import Alpine from 'alpinejs'
import Focus from '@alpinejs/focus'
import Tooltip from '@ryangjchandler/alpine-tooltip'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Sortable from 'sortablejs'
import * as FilePond from 'filepond'
import NotificationsAlpinePlugin from '../../../../vendor/filament/notifications/dist/module.esm'

import './helpers/window'
import './helpers/trix'
import internationalNumber from './plugins/internationalNumber'
import mapBox from './plugins/mapBox'
import KeyPress from './plugins/keyPress'

Alpine.plugin(Tooltip)
Alpine.plugin(Focus)
Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(NotificationsAlpinePlugin)
Alpine.plugin(KeyPress)
Alpine.data('internationalNumber', internationalNumber)
Alpine.data('mapBox', mapBox)

Alpine.store(
  'theme',
  window.matchMedia('(prefers-color-scheme: dark)').matches
    ? 'dark'
    : 'light',
)

window.addEventListener('dark-mode-toggled', (event) => {
  Alpine.store('theme', event.detail)
})

window
  .matchMedia('(prefers-color-scheme: dark)')
  .addEventListener('change', (event) => {
    Alpine.store('theme', event.matches ? 'dark' : 'light')
  })

window.Alpine = Alpine
window.Sortable = Sortable
window.FilePond = FilePond

Alpine.start()
