import '../helpers/shortcut'

export default function (Alpine) {
  Alpine.directive('keypress', (el) => {
    // Display the list of shortcuts
    shortcut.add('Shift+F1', function () {
      window.dispatchEvent(new CustomEvent('shortcut-list', { bubbles: true }))
    })
    // Move to the documentation
    shortcut.add('Alt+D', function () {
      window.open('https://laravelshopper.dev', '_blank')
    })
  })
}
