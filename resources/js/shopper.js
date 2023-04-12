import Alpine from 'alpinejs'
import Sortable from 'sortablejs'
import * as FilePond from 'filepond'
import Tooltip from '@ryangjchandler/alpine-tooltip'

import '@helpers/window'
import '@helpers/darkMode'
import internationalNumber from './plugins/internationalNumber'
import mapBox from './plugins/mapBox'

Alpine.plugin(Tooltip)
Alpine.data('internationalNumber', internationalNumber)
Alpine.data('mapBox', mapBox)

window.Alpine = Alpine
window.Sortable = Sortable
window.FilePond = FilePond

Alpine.start()
