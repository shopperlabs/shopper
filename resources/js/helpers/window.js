import Choices from 'choices.js'

/**
 * Create a multiselect element.
 *
 * @param {HTMLElement} element
 * @returns {Choices}
 */
window.choices = (element) => {
  return new Choices(element, { removeItemButton: true })
}

/**
 * Create smooth scroll animation
 *
 * @param {string} selector
 */
window.scrollToPosition = (selector) => document.querySelector(selector).scrollIntoView({behavior: 'smooth', block: 'end'})

/**
 * Turns the first character of a string into a capital letter
 *
 * @param string
 */
window.capitalize = (string) => string.replace(/^\w/, (c) => c.toUpperCase())
