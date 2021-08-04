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

import 'alpinejs';
import flatpickr from 'flatpickr';

window.flatpickr = flatpickr;

// Make dark mode switch.
const darkModeToggles = document.getElementsByClassName('darkModeToggle');

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
} else {
  document.documentElement.classList.remove('dark');
}

for (let i = 0; i < darkModeToggles.length; i++) {
  darkModeToggles[i].onclick = () => {
    if (localStorage.theme === 'light') {
      localStorage.theme = 'dark';
      document.querySelector('html').classList.add('dark');
      document.querySelector('html').classList.remove('light');
    } else {
      localStorage.theme = 'light';
      document.querySelector('html').classList.remove('dark');
      document.querySelector('html').classList.add('light');
    }
  };
}

window.scrollToPosition = (selector) => document.querySelector(selector).scrollIntoView({behavior: 'smooth', block: 'end'});
