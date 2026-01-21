import sidebarStore from './stores/sidebar.js'

export function registerSidebarStore(config = {}) {
    if (typeof window.Alpine === 'undefined') {
        console.error('[Sidebar] Alpine.js is required but not found.')
        return
    }

    document.addEventListener('alpine:init', () => {
        window.Alpine.store('sidebar', sidebarStore(config))
    })
}

export function initSidebarStore(config = {}) {
    if (typeof window.Alpine === 'undefined') {
        console.error('[Sidebar] Alpine.js is required but not found.')
        return
    }

    window.Alpine.store('sidebar', sidebarStore(config))
}

export function getSidebarStore() {
    if (typeof window.Alpine === 'undefined') {
        console.error('[Sidebar] Alpine.js is required but not found.')
        return null
    }

    return window.Alpine.store('sidebar')
}

// Auto-register if Alpine is available and auto-init is enabled
if (typeof window !== 'undefined') {
    window.SidebarStore = {
        register: registerSidebarStore,
        init: initSidebarStore,
        get: getSidebarStore,
        store: sidebarStore,
    }
}

export { sidebarStore }
export default sidebarStore
