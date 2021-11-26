/**
 * Transform une chaine en élément DOM
 * @param {string} str
 * @return {DocumentFragment}
 */
export function strToDom(str) {
  return document.createRange().createContextualFragment(str).firstChild;
}

/**
 *
 * @param {HTMLElement|Document|Node} element
 * @param {string} selector
 * @return {null|HTMLElement}
 */
export function closest(element, selector) {
  for (; element && element !== document; element = element.parentNode) {
    if (element.matches(selector)) return element;
  }
  return null;
}

/**
 * @param {string} selector
 * @return {HTMLElement}
 */
export function $(selector) {
  return document.querySelector(selector);
}

/**
 * @param {string} selector
 * @return {HTMLElement[]}
 */
export function $$(selector) {
  return Array.from(document.querySelectorAll(selector));
}

/**
 * Génère une classe à partir de différentes variables
 *
 * @param  {...string|null} classnames
 */
export function classNames(...classnames) {
  return classnames.filter((classname) => classname !== null && classname !== false).join(" ");
}

/**
 * Convertit les données d'un formulaire en objet JavaScript
 *
 * @param {HTMLFormElement} form
 * @return {{[p: string]: string}}
 */
export function formDataToObj(form) {
  return Object.fromEntries(new FormData(form));
}
