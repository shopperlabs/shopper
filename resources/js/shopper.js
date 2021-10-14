/**
 * Shopper.js
 *
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using Laravel & React.
 *
 * @author Arthur Monney<arthur@shopperlabs.io>
 * @version 2.0.0
 * @since May 2021
 */

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import Choices from 'choices.js';
import Sortable from 'sortablejs';

import internationalNumber from "./plugins/internationalNumber";

// Add Alpine to window object.
window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.Sortable = Sortable;

// Create a multiselect element.
window.choices = (element) => {
  return new Choices(element, { removeItemButton: true });
};

// Create smooth scroll animation
window.scrollToPosition = (selector) => document.querySelector(selector).scrollIntoView({behavior: 'smooth', block: 'end'});

Alpine.data('internationalNumber', internationalNumber)

Alpine.start();

require('./darkMode');
