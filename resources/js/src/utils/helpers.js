/**
 * Helpers Function helpers.ts
 *
 * Useful functions who can used to make a simple or a short
 * implementation of a feature that can be reused on multiple
 * js or components files.
 */

/**
 * ScrollToPosition
 *
 * @param {string} selector
 */
export const scrollToPosition = (selector) => document.querySelector(selector).scrollIntoView({ behavior: 'smooth', block: 'end'});
