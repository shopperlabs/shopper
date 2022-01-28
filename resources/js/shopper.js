/**
 * Shopper.js
 *
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using Laravel & React.
 *
 * @author Arthur Monney<arthur@shopperlabs.io>
 * @since May 2021
 */

import Alpine from 'alpinejs'
import Sortable from 'sortablejs'
import * as FilePond from 'filepond'

import './elements'
import '@helpers/window'
import '@helpers/darkMode'

import internationalNumber from './plugins/internationalNumber'
import mapBox from './plugins/mapBox'

window.Alpine = Alpine
window.Sortable = Sortable
window.FilePond = FilePond

Alpine.data('internationalNumber', internationalNumber)
Alpine.data('mapBox', mapBox)

Alpine.start()
