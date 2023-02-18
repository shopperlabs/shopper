import Choices from 'choices.js'
import TomSelect from 'tom-select'

window.choices = element => {
  return new Choices(element, { removeItemButton: true })
}

window.tomSelect = (element, options = {}) => {
  return new TomSelect(element, options)
}

window.scrollToPosition = selector => document.querySelector(selector)
  .scrollIntoView({
    behavior: 'smooth',
    block: 'end',
  })

