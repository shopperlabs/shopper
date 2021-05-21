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

function toggleDarkMode() {
  // On page load or when changing themes, best to add inline in `head` to avoid FOUC
  if (localStorage.theme === 'dark' || (!'theme' in localStorage && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.querySelector('html').classList.add('dark');
  } else if (localStorage.theme === 'light') {
    document.querySelector('html').classList.add('light');
  }
}

toggleDarkMode();
