import SlideOverPanel from './components/panel'
import CodePreview from './components/code-preview'
import NestedSortable from './components/nested-sortable'
import sidebarStore from '../../../sidebar/resources/js/stores/sidebar'

window.SlideOverPanel = SlideOverPanel
window.codePreview = CodePreview
window.nestedSortable = NestedSortable

document.addEventListener('alpine:init', () => {
  const theme = localStorage.getItem('theme') ?? 'system'

  window.Alpine.store(
    'theme',
    theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)
      ? 'dark'
      : 'light',
  )

  // Sidebar store
  window.Alpine.store('sidebar', sidebarStore())
  window.Alpine.store('sidebar').init()

  window.addEventListener('theme-changed', (event) => {
    let theme = event.detail

    localStorage.setItem('theme', theme)

    if (theme === 'system') {
      theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    window.Alpine.store('theme', theme)
  })

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    if (localStorage.getItem('theme') === 'system') {
      window.Alpine.store('theme', event.matches ? 'dark' : 'light')
    }
  })

  window.Alpine.effect(() => {
    const theme = window.Alpine.store('theme')

    theme === 'dark'
      ? document.documentElement.classList.add('dark')
      : document.documentElement.classList.remove('dark')
  })
})
