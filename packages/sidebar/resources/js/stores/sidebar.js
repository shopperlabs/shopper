export default (config = {}) => ({
    isOpen: false,
    isCollapsed: false,
    collapsedGroups: [],
    collapsible: true,
    currentPath: window.location.pathname,

    breakpoint: config.breakpoint ?? 1024,
    persistKeys: {
        isOpen: config.persistKeys?.isOpen ?? 'sidebar-is-open',
        isCollapsed: config.persistKeys?.isCollapsed ?? 'sidebar-is-collapsed',
        collapsedGroups: config.persistKeys?.collapsedGroups ?? 'sidebar-collapsed-groups',
    },

    init() {
        this.loadConfigFromDOM()
        this.loadPersistedState()
        this.setupResizeObserver()
        this.setupNavigationListener()

        if (window.innerWidth >= this.breakpoint) {
            this.isOpen = true
        }
    },

    loadConfigFromDOM() {
        const body = document.body
        const breakpoint = body.dataset.sidebarBreakpoint
        const collapsible = body.dataset.sidebarCollapsible

        if (breakpoint) {
            this.breakpoint = parseInt(breakpoint, 10)
        }

        if (collapsible !== undefined) {
            this.collapsible = collapsible === 'true'
        }
    },

    loadPersistedState() {
        const storedIsOpen = localStorage.getItem(this.persistKeys.isOpen)
        const storedIsCollapsed = localStorage.getItem(this.persistKeys.isCollapsed)
        const storedCollapsedGroups = localStorage.getItem(this.persistKeys.collapsedGroups)

        if (storedIsOpen !== null) {
            this.isOpen = JSON.parse(storedIsOpen)
        }

        if (storedIsCollapsed !== null) {
            this.isCollapsed = JSON.parse(storedIsCollapsed)
        }

        if (storedCollapsedGroups !== null) {
            this.collapsedGroups = JSON.parse(storedCollapsedGroups)
        }
    },

    persistState(key, value) {
        localStorage.setItem(key, JSON.stringify(value))
    },

    // Sidebar visibility (mobile)
    open() {
        this.isOpen = true
        this.persistState(this.persistKeys.isOpen, true)
    },

    close() {
        this.isOpen = false
        this.persistState(this.persistKeys.isOpen, false)
    },

    toggle() {
        if (window.innerWidth < this.breakpoint) {
            this.isOpen ? this.close() : this.open()
        } else {
            this.toggleCollapse()
        }
    },

    // Sidebar collapse (desktop)
    collapse() {
        if (!this.collapsible) return

        this.isCollapsed = true
        this.persistState(this.persistKeys.isCollapsed, true)
    },

    expand() {
        this.isCollapsed = false
        this.persistState(this.persistKeys.isCollapsed, false)
    },

    toggleCollapse() {
        if (!this.collapsible) return

        this.isCollapsed ? this.expand() : this.collapse()
    },

    // Group management
    isGroupCollapsed(label) {
        return this.collapsedGroups.includes(label)
    },

    collapseGroup(label) {
        if (!this.collapsedGroups.includes(label)) {
            this.collapsedGroups.push(label)
            this.persistState(this.persistKeys.collapsedGroups, this.collapsedGroups)
        }
    },

    expandGroup(label) {
        const index = this.collapsedGroups.indexOf(label)
        if (index !== -1) {
            this.collapsedGroups.splice(index, 1)
            this.persistState(this.persistKeys.collapsedGroups, this.collapsedGroups)
        }
    },

    toggleGroup(label) {
        this.isGroupCollapsed(label) ? this.expandGroup(label) : this.collapseGroup(label)
    },

    // Responsive handling
    setupResizeObserver() {
        let previousWidth = window.innerWidth

        window.addEventListener('resize', () => {
            const currentWidth = window.innerWidth

            // Transitioning from mobile to desktop
            if (previousWidth < this.breakpoint && currentWidth >= this.breakpoint) {
                this.isOpen = true
            }

            // Transitioning from desktop to mobile
            if (previousWidth >= this.breakpoint && currentWidth < this.breakpoint) {
                this.isOpen = false
            }

            previousWidth = currentWidth
        })
    },

    // Livewire integration
    refresh() {
        if (window.Livewire) {
            window.Livewire.dispatch('sidebar:refresh')
        }
    },

    // Navigation tracking
    setupNavigationListener() {
        document.addEventListener('livewire:navigated', () => {
            this.currentPath = window.location.pathname
        })
    },

    isActive(url) {
        if (!url) return false

        try {
            const linkPath = new URL(url, window.location.origin).pathname

            // Exact match
            if (this.currentPath === linkPath) return true

            // Check if current path starts with link path (for nested routes)
            // But not for root paths to avoid false positives
            if (linkPath !== '/' && linkPath.length > 1) {
                return this.currentPath.startsWith(linkPath + '/') || this.currentPath.startsWith(linkPath)
            }

            return false
        } catch (e) {
            return false
        }
    },
})
