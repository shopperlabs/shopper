import Alpine from 'alpinejs'
import Focus from '@alpinejs/focus'
import Sortable from 'sortablejs'
import * as FilePond from 'filepond'
import Tooltip from '@ryangjchandler/alpine-tooltip'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'

import '@helpers/window'
import '@helpers/darkMode'
import internationalNumber from './plugins/internationalNumber'
import mapBox from './plugins/mapBox'

Alpine.plugin(Tooltip)
Alpine.plugin(Focus)
Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(NotificationsAlpinePlugin)
Alpine.data('internationalNumber', internationalNumber)
Alpine.data('mapBox', mapBox)

window.Alpine = Alpine
window.Sortable = Sortable
window.FilePond = FilePond

Alpine.start()
