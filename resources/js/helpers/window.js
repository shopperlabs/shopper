import Choices from 'choices.js'
import TomSelect from 'tom-select'

/**
 * Create a multiselect element.
 *
 * @param {HTMLElement} element
 * @returns {Choices}
 */
window.choices = (element) => {
  return new Choices(element, { removeItemButton: true })
}

window.tomSelect = (element, options = {}) => {
  return new TomSelect(element, options)
}

/**
 * Create smooth scroll animation
 *
 * @param {string} selector
 */
window.scrollToPosition = (selector) => document.querySelector(selector).scrollIntoView({behavior: 'smooth', block: 'end'})

