(() => {
  // packages/sidebar/resources/js/stores/sidebar.js
  var sidebar_default = (config = {}) => ({
    isOpen: false,
    isCollapsed: false,
    collapsedGroups: [],
    collapsible: true,
    currentPath: window.location.pathname,
    breakpoint: config.breakpoint ?? 1024,
    persistKeys: {
      isOpen: config.persistKeys?.isOpen ?? "sidebar-is-open",
      isCollapsed: config.persistKeys?.isCollapsed ?? "sidebar-is-collapsed",
      collapsedGroups: config.persistKeys?.collapsedGroups ?? "sidebar-collapsed-groups"
    },
    init() {
      this.loadConfigFromDOM();
      this.loadPersistedState();
      this.setupResizeObserver();
      this.setupNavigationListener();
      if (window.innerWidth >= this.breakpoint) {
        this.isOpen = true;
      }
    },
    loadConfigFromDOM() {
      const body = document.body;
      const breakpoint = body.dataset.sidebarBreakpoint;
      const collapsible = body.dataset.sidebarCollapsible;
      if (breakpoint) {
        this.breakpoint = parseInt(breakpoint, 10);
      }
      if (collapsible !== void 0) {
        this.collapsible = collapsible === "true";
      }
    },
    loadPersistedState() {
      const storedIsOpen = localStorage.getItem(this.persistKeys.isOpen);
      const storedIsCollapsed = localStorage.getItem(this.persistKeys.isCollapsed);
      const storedCollapsedGroups = localStorage.getItem(this.persistKeys.collapsedGroups);
      if (storedIsOpen !== null) {
        this.isOpen = JSON.parse(storedIsOpen);
      }
      if (storedIsCollapsed !== null) {
        this.isCollapsed = JSON.parse(storedIsCollapsed);
      }
      if (storedCollapsedGroups !== null) {
        this.collapsedGroups = JSON.parse(storedCollapsedGroups);
      }
    },
    persistState(key, value) {
      localStorage.setItem(key, JSON.stringify(value));
    },
    // Sidebar visibility (mobile)
    open() {
      this.isOpen = true;
      this.persistState(this.persistKeys.isOpen, true);
    },
    close() {
      this.isOpen = false;
      this.persistState(this.persistKeys.isOpen, false);
    },
    toggle() {
      if (window.innerWidth < this.breakpoint) {
        this.isOpen ? this.close() : this.open();
      } else {
        this.toggleCollapse();
      }
    },
    // Sidebar collapse (desktop)
    collapse() {
      if (!this.collapsible) return;
      this.isCollapsed = true;
      this.persistState(this.persistKeys.isCollapsed, true);
    },
    expand() {
      this.isCollapsed = false;
      this.persistState(this.persistKeys.isCollapsed, false);
    },
    toggleCollapse() {
      if (!this.collapsible) return;
      this.isCollapsed ? this.expand() : this.collapse();
    },
    // Group management
    isGroupCollapsed(label) {
      return this.collapsedGroups.includes(label);
    },
    collapseGroup(label) {
      if (!this.collapsedGroups.includes(label)) {
        this.collapsedGroups.push(label);
        this.persistState(this.persistKeys.collapsedGroups, this.collapsedGroups);
      }
    },
    expandGroup(label) {
      const index = this.collapsedGroups.indexOf(label);
      if (index !== -1) {
        this.collapsedGroups.splice(index, 1);
        this.persistState(this.persistKeys.collapsedGroups, this.collapsedGroups);
      }
    },
    toggleGroup(label) {
      this.isGroupCollapsed(label) ? this.expandGroup(label) : this.collapseGroup(label);
    },
    // Responsive handling
    setupResizeObserver() {
      let previousWidth = window.innerWidth;
      window.addEventListener("resize", () => {
        const currentWidth = window.innerWidth;
        if (previousWidth < this.breakpoint && currentWidth >= this.breakpoint) {
          this.isOpen = true;
        }
        if (previousWidth >= this.breakpoint && currentWidth < this.breakpoint) {
          this.isOpen = false;
        }
        previousWidth = currentWidth;
      });
    },
    // Livewire integration
    refresh() {
      if (window.Livewire) {
        window.Livewire.dispatch("sidebar:refresh");
      }
    },
    // Navigation tracking
    setupNavigationListener() {
      document.addEventListener("livewire:navigated", () => {
        this.currentPath = window.location.pathname;
      });
    },
    isActive(url) {
      if (!url) return false;
      try {
        const linkPath = new URL(url, window.location.origin).pathname;
        if (this.currentPath === linkPath) return true;
        if (linkPath !== "/" && linkPath.length > 1) {
          return this.currentPath.startsWith(linkPath + "/") || this.currentPath.startsWith(linkPath);
        }
        return false;
      } catch (e) {
        return false;
      }
    }
  });

  // packages/sidebar/resources/js/index.js
  function registerSidebarStore(config = {}) {
    if (typeof window.Alpine === "undefined") {
      console.error("[Sidebar] Alpine.js is required but not found.");
      return;
    }
    document.addEventListener("alpine:init", () => {
      window.Alpine.store("sidebar", sidebar_default(config));
    });
  }
  function initSidebarStore(config = {}) {
    if (typeof window.Alpine === "undefined") {
      console.error("[Sidebar] Alpine.js is required but not found.");
      return;
    }
    window.Alpine.store("sidebar", sidebar_default(config));
  }
  function getSidebarStore() {
    if (typeof window.Alpine === "undefined") {
      console.error("[Sidebar] Alpine.js is required but not found.");
      return null;
    }
    return window.Alpine.store("sidebar");
  }
  if (typeof window !== "undefined") {
    window.SidebarStore = {
      register: registerSidebarStore,
      init: initSidebarStore,
      get: getSidebarStore,
      store: sidebar_default
    };
  }
  var index_default = sidebar_default;
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vcmVzb3VyY2VzL2pzL3N0b3Jlcy9zaWRlYmFyLmpzIiwgIi4uL3Jlc291cmNlcy9qcy9pbmRleC5qcyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiZXhwb3J0IGRlZmF1bHQgKGNvbmZpZyA9IHt9KSA9PiAoe1xuICAgIGlzT3BlbjogZmFsc2UsXG4gICAgaXNDb2xsYXBzZWQ6IGZhbHNlLFxuICAgIGNvbGxhcHNlZEdyb3VwczogW10sXG4gICAgY29sbGFwc2libGU6IHRydWUsXG4gICAgY3VycmVudFBhdGg6IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZSxcblxuICAgIGJyZWFrcG9pbnQ6IGNvbmZpZy5icmVha3BvaW50ID8/IDEwMjQsXG4gICAgcGVyc2lzdEtleXM6IHtcbiAgICAgICAgaXNPcGVuOiBjb25maWcucGVyc2lzdEtleXM/LmlzT3BlbiA/PyAnc2lkZWJhci1pcy1vcGVuJyxcbiAgICAgICAgaXNDb2xsYXBzZWQ6IGNvbmZpZy5wZXJzaXN0S2V5cz8uaXNDb2xsYXBzZWQgPz8gJ3NpZGViYXItaXMtY29sbGFwc2VkJyxcbiAgICAgICAgY29sbGFwc2VkR3JvdXBzOiBjb25maWcucGVyc2lzdEtleXM/LmNvbGxhcHNlZEdyb3VwcyA/PyAnc2lkZWJhci1jb2xsYXBzZWQtZ3JvdXBzJyxcbiAgICB9LFxuXG4gICAgaW5pdCgpIHtcbiAgICAgICAgdGhpcy5sb2FkQ29uZmlnRnJvbURPTSgpXG4gICAgICAgIHRoaXMubG9hZFBlcnNpc3RlZFN0YXRlKClcbiAgICAgICAgdGhpcy5zZXR1cFJlc2l6ZU9ic2VydmVyKClcbiAgICAgICAgdGhpcy5zZXR1cE5hdmlnYXRpb25MaXN0ZW5lcigpXG5cbiAgICAgICAgaWYgKHdpbmRvdy5pbm5lcldpZHRoID49IHRoaXMuYnJlYWtwb2ludCkge1xuICAgICAgICAgICAgdGhpcy5pc09wZW4gPSB0cnVlXG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgbG9hZENvbmZpZ0Zyb21ET00oKSB7XG4gICAgICAgIGNvbnN0IGJvZHkgPSBkb2N1bWVudC5ib2R5XG4gICAgICAgIGNvbnN0IGJyZWFrcG9pbnQgPSBib2R5LmRhdGFzZXQuc2lkZWJhckJyZWFrcG9pbnRcbiAgICAgICAgY29uc3QgY29sbGFwc2libGUgPSBib2R5LmRhdGFzZXQuc2lkZWJhckNvbGxhcHNpYmxlXG5cbiAgICAgICAgaWYgKGJyZWFrcG9pbnQpIHtcbiAgICAgICAgICAgIHRoaXMuYnJlYWtwb2ludCA9IHBhcnNlSW50KGJyZWFrcG9pbnQsIDEwKVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKGNvbGxhcHNpYmxlICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIHRoaXMuY29sbGFwc2libGUgPSBjb2xsYXBzaWJsZSA9PT0gJ3RydWUnXG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgbG9hZFBlcnNpc3RlZFN0YXRlKCkge1xuICAgICAgICBjb25zdCBzdG9yZWRJc09wZW4gPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSh0aGlzLnBlcnNpc3RLZXlzLmlzT3BlbilcbiAgICAgICAgY29uc3Qgc3RvcmVkSXNDb2xsYXBzZWQgPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSh0aGlzLnBlcnNpc3RLZXlzLmlzQ29sbGFwc2VkKVxuICAgICAgICBjb25zdCBzdG9yZWRDb2xsYXBzZWRHcm91cHMgPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSh0aGlzLnBlcnNpc3RLZXlzLmNvbGxhcHNlZEdyb3VwcylcblxuICAgICAgICBpZiAoc3RvcmVkSXNPcGVuICE9PSBudWxsKSB7XG4gICAgICAgICAgICB0aGlzLmlzT3BlbiA9IEpTT04ucGFyc2Uoc3RvcmVkSXNPcGVuKVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHN0b3JlZElzQ29sbGFwc2VkICE9PSBudWxsKSB7XG4gICAgICAgICAgICB0aGlzLmlzQ29sbGFwc2VkID0gSlNPTi5wYXJzZShzdG9yZWRJc0NvbGxhcHNlZClcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChzdG9yZWRDb2xsYXBzZWRHcm91cHMgIT09IG51bGwpIHtcbiAgICAgICAgICAgIHRoaXMuY29sbGFwc2VkR3JvdXBzID0gSlNPTi5wYXJzZShzdG9yZWRDb2xsYXBzZWRHcm91cHMpXG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgcGVyc2lzdFN0YXRlKGtleSwgdmFsdWUpIHtcbiAgICAgICAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oa2V5LCBKU09OLnN0cmluZ2lmeSh2YWx1ZSkpXG4gICAgfSxcblxuICAgIC8vIFNpZGViYXIgdmlzaWJpbGl0eSAobW9iaWxlKVxuICAgIG9wZW4oKSB7XG4gICAgICAgIHRoaXMuaXNPcGVuID0gdHJ1ZVxuICAgICAgICB0aGlzLnBlcnNpc3RTdGF0ZSh0aGlzLnBlcnNpc3RLZXlzLmlzT3BlbiwgdHJ1ZSlcbiAgICB9LFxuXG4gICAgY2xvc2UoKSB7XG4gICAgICAgIHRoaXMuaXNPcGVuID0gZmFsc2VcbiAgICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5pc09wZW4sIGZhbHNlKVxuICAgIH0sXG5cbiAgICB0b2dnbGUoKSB7XG4gICAgICAgIGlmICh3aW5kb3cuaW5uZXJXaWR0aCA8IHRoaXMuYnJlYWtwb2ludCkge1xuICAgICAgICAgICAgdGhpcy5pc09wZW4gPyB0aGlzLmNsb3NlKCkgOiB0aGlzLm9wZW4oKVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGhpcy50b2dnbGVDb2xsYXBzZSgpXG4gICAgICAgIH1cbiAgICB9LFxuXG4gICAgLy8gU2lkZWJhciBjb2xsYXBzZSAoZGVza3RvcClcbiAgICBjb2xsYXBzZSgpIHtcbiAgICAgICAgaWYgKCF0aGlzLmNvbGxhcHNpYmxlKSByZXR1cm5cblxuICAgICAgICB0aGlzLmlzQ29sbGFwc2VkID0gdHJ1ZVxuICAgICAgICB0aGlzLnBlcnNpc3RTdGF0ZSh0aGlzLnBlcnNpc3RLZXlzLmlzQ29sbGFwc2VkLCB0cnVlKVxuICAgIH0sXG5cbiAgICBleHBhbmQoKSB7XG4gICAgICAgIHRoaXMuaXNDb2xsYXBzZWQgPSBmYWxzZVxuICAgICAgICB0aGlzLnBlcnNpc3RTdGF0ZSh0aGlzLnBlcnNpc3RLZXlzLmlzQ29sbGFwc2VkLCBmYWxzZSlcbiAgICB9LFxuXG4gICAgdG9nZ2xlQ29sbGFwc2UoKSB7XG4gICAgICAgIGlmICghdGhpcy5jb2xsYXBzaWJsZSkgcmV0dXJuXG5cbiAgICAgICAgdGhpcy5pc0NvbGxhcHNlZCA/IHRoaXMuZXhwYW5kKCkgOiB0aGlzLmNvbGxhcHNlKClcbiAgICB9LFxuXG4gICAgLy8gR3JvdXAgbWFuYWdlbWVudFxuICAgIGlzR3JvdXBDb2xsYXBzZWQobGFiZWwpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuY29sbGFwc2VkR3JvdXBzLmluY2x1ZGVzKGxhYmVsKVxuICAgIH0sXG5cbiAgICBjb2xsYXBzZUdyb3VwKGxhYmVsKSB7XG4gICAgICAgIGlmICghdGhpcy5jb2xsYXBzZWRHcm91cHMuaW5jbHVkZXMobGFiZWwpKSB7XG4gICAgICAgICAgICB0aGlzLmNvbGxhcHNlZEdyb3Vwcy5wdXNoKGxhYmVsKVxuICAgICAgICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5jb2xsYXBzZWRHcm91cHMsIHRoaXMuY29sbGFwc2VkR3JvdXBzKVxuICAgICAgICB9XG4gICAgfSxcblxuICAgIGV4cGFuZEdyb3VwKGxhYmVsKSB7XG4gICAgICAgIGNvbnN0IGluZGV4ID0gdGhpcy5jb2xsYXBzZWRHcm91cHMuaW5kZXhPZihsYWJlbClcbiAgICAgICAgaWYgKGluZGV4ICE9PSAtMSkge1xuICAgICAgICAgICAgdGhpcy5jb2xsYXBzZWRHcm91cHMuc3BsaWNlKGluZGV4LCAxKVxuICAgICAgICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5jb2xsYXBzZWRHcm91cHMsIHRoaXMuY29sbGFwc2VkR3JvdXBzKVxuICAgICAgICB9XG4gICAgfSxcblxuICAgIHRvZ2dsZUdyb3VwKGxhYmVsKSB7XG4gICAgICAgIHRoaXMuaXNHcm91cENvbGxhcHNlZChsYWJlbCkgPyB0aGlzLmV4cGFuZEdyb3VwKGxhYmVsKSA6IHRoaXMuY29sbGFwc2VHcm91cChsYWJlbClcbiAgICB9LFxuXG4gICAgLy8gUmVzcG9uc2l2ZSBoYW5kbGluZ1xuICAgIHNldHVwUmVzaXplT2JzZXJ2ZXIoKSB7XG4gICAgICAgIGxldCBwcmV2aW91c1dpZHRoID0gd2luZG93LmlubmVyV2lkdGhcblxuICAgICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgKCkgPT4ge1xuICAgICAgICAgICAgY29uc3QgY3VycmVudFdpZHRoID0gd2luZG93LmlubmVyV2lkdGhcblxuICAgICAgICAgICAgLy8gVHJhbnNpdGlvbmluZyBmcm9tIG1vYmlsZSB0byBkZXNrdG9wXG4gICAgICAgICAgICBpZiAocHJldmlvdXNXaWR0aCA8IHRoaXMuYnJlYWtwb2ludCAmJiBjdXJyZW50V2lkdGggPj0gdGhpcy5icmVha3BvaW50KSB7XG4gICAgICAgICAgICAgICAgdGhpcy5pc09wZW4gPSB0cnVlXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vIFRyYW5zaXRpb25pbmcgZnJvbSBkZXNrdG9wIHRvIG1vYmlsZVxuICAgICAgICAgICAgaWYgKHByZXZpb3VzV2lkdGggPj0gdGhpcy5icmVha3BvaW50ICYmIGN1cnJlbnRXaWR0aCA8IHRoaXMuYnJlYWtwb2ludCkge1xuICAgICAgICAgICAgICAgIHRoaXMuaXNPcGVuID0gZmFsc2VcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcHJldmlvdXNXaWR0aCA9IGN1cnJlbnRXaWR0aFxuICAgICAgICB9KVxuICAgIH0sXG5cbiAgICAvLyBMaXZld2lyZSBpbnRlZ3JhdGlvblxuICAgIHJlZnJlc2goKSB7XG4gICAgICAgIGlmICh3aW5kb3cuTGl2ZXdpcmUpIHtcbiAgICAgICAgICAgIHdpbmRvdy5MaXZld2lyZS5kaXNwYXRjaCgnc2lkZWJhcjpyZWZyZXNoJylcbiAgICAgICAgfVxuICAgIH0sXG5cbiAgICAvLyBOYXZpZ2F0aW9uIHRyYWNraW5nXG4gICAgc2V0dXBOYXZpZ2F0aW9uTGlzdGVuZXIoKSB7XG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2xpdmV3aXJlOm5hdmlnYXRlZCcsICgpID0+IHtcbiAgICAgICAgICAgIHRoaXMuY3VycmVudFBhdGggPSB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWVcbiAgICAgICAgfSlcbiAgICB9LFxuXG4gICAgaXNBY3RpdmUodXJsKSB7XG4gICAgICAgIGlmICghdXJsKSByZXR1cm4gZmFsc2VcblxuICAgICAgICB0cnkge1xuICAgICAgICAgICAgY29uc3QgbGlua1BhdGggPSBuZXcgVVJMKHVybCwgd2luZG93LmxvY2F0aW9uLm9yaWdpbikucGF0aG5hbWVcblxuICAgICAgICAgICAgLy8gRXhhY3QgbWF0Y2hcbiAgICAgICAgICAgIGlmICh0aGlzLmN1cnJlbnRQYXRoID09PSBsaW5rUGF0aCkgcmV0dXJuIHRydWVcblxuICAgICAgICAgICAgLy8gQ2hlY2sgaWYgY3VycmVudCBwYXRoIHN0YXJ0cyB3aXRoIGxpbmsgcGF0aCAoZm9yIG5lc3RlZCByb3V0ZXMpXG4gICAgICAgICAgICAvLyBCdXQgbm90IGZvciByb290IHBhdGhzIHRvIGF2b2lkIGZhbHNlIHBvc2l0aXZlc1xuICAgICAgICAgICAgaWYgKGxpbmtQYXRoICE9PSAnLycgJiYgbGlua1BhdGgubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmN1cnJlbnRQYXRoLnN0YXJ0c1dpdGgobGlua1BhdGggKyAnLycpIHx8IHRoaXMuY3VycmVudFBhdGguc3RhcnRzV2l0aChsaW5rUGF0aClcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlXG4gICAgICAgIH0gY2F0Y2ggKGUpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZVxuICAgICAgICB9XG4gICAgfSxcbn0pXG4iLCAiaW1wb3J0IHNpZGViYXJTdG9yZSBmcm9tICcuL3N0b3Jlcy9zaWRlYmFyLmpzJ1xuXG5leHBvcnQgZnVuY3Rpb24gcmVnaXN0ZXJTaWRlYmFyU3RvcmUoY29uZmlnID0ge30pIHtcbiAgICBpZiAodHlwZW9mIHdpbmRvdy5BbHBpbmUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIGNvbnNvbGUuZXJyb3IoJ1tTaWRlYmFyXSBBbHBpbmUuanMgaXMgcmVxdWlyZWQgYnV0IG5vdCBmb3VuZC4nKVxuICAgICAgICByZXR1cm5cbiAgICB9XG5cbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdhbHBpbmU6aW5pdCcsICgpID0+IHtcbiAgICAgICAgd2luZG93LkFscGluZS5zdG9yZSgnc2lkZWJhcicsIHNpZGViYXJTdG9yZShjb25maWcpKVxuICAgIH0pXG59XG5cbmV4cG9ydCBmdW5jdGlvbiBpbml0U2lkZWJhclN0b3JlKGNvbmZpZyA9IHt9KSB7XG4gICAgaWYgKHR5cGVvZiB3aW5kb3cuQWxwaW5lID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICBjb25zb2xlLmVycm9yKCdbU2lkZWJhcl0gQWxwaW5lLmpzIGlzIHJlcXVpcmVkIGJ1dCBub3QgZm91bmQuJylcbiAgICAgICAgcmV0dXJuXG4gICAgfVxuXG4gICAgd2luZG93LkFscGluZS5zdG9yZSgnc2lkZWJhcicsIHNpZGViYXJTdG9yZShjb25maWcpKVxufVxuXG5leHBvcnQgZnVuY3Rpb24gZ2V0U2lkZWJhclN0b3JlKCkge1xuICAgIGlmICh0eXBlb2Ygd2luZG93LkFscGluZSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgY29uc29sZS5lcnJvcignW1NpZGViYXJdIEFscGluZS5qcyBpcyByZXF1aXJlZCBidXQgbm90IGZvdW5kLicpXG4gICAgICAgIHJldHVybiBudWxsXG4gICAgfVxuXG4gICAgcmV0dXJuIHdpbmRvdy5BbHBpbmUuc3RvcmUoJ3NpZGViYXInKVxufVxuXG4vLyBBdXRvLXJlZ2lzdGVyIGlmIEFscGluZSBpcyBhdmFpbGFibGUgYW5kIGF1dG8taW5pdCBpcyBlbmFibGVkXG5pZiAodHlwZW9mIHdpbmRvdyAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICB3aW5kb3cuU2lkZWJhclN0b3JlID0ge1xuICAgICAgICByZWdpc3RlcjogcmVnaXN0ZXJTaWRlYmFyU3RvcmUsXG4gICAgICAgIGluaXQ6IGluaXRTaWRlYmFyU3RvcmUsXG4gICAgICAgIGdldDogZ2V0U2lkZWJhclN0b3JlLFxuICAgICAgICBzdG9yZTogc2lkZWJhclN0b3JlLFxuICAgIH1cbn1cblxuZXhwb3J0IHsgc2lkZWJhclN0b3JlIH1cbmV4cG9ydCBkZWZhdWx0IHNpZGViYXJTdG9yZVxuIl0sCiAgIm1hcHBpbmdzIjogIjs7QUFBQSxNQUFPLGtCQUFRLENBQUMsU0FBUyxDQUFDLE9BQU87QUFBQSxJQUM3QixRQUFRO0FBQUEsSUFDUixhQUFhO0FBQUEsSUFDYixpQkFBaUIsQ0FBQztBQUFBLElBQ2xCLGFBQWE7QUFBQSxJQUNiLGFBQWEsT0FBTyxTQUFTO0FBQUEsSUFFN0IsWUFBWSxPQUFPLGNBQWM7QUFBQSxJQUNqQyxhQUFhO0FBQUEsTUFDVCxRQUFRLE9BQU8sYUFBYSxVQUFVO0FBQUEsTUFDdEMsYUFBYSxPQUFPLGFBQWEsZUFBZTtBQUFBLE1BQ2hELGlCQUFpQixPQUFPLGFBQWEsbUJBQW1CO0FBQUEsSUFDNUQ7QUFBQSxJQUVBLE9BQU87QUFDSCxXQUFLLGtCQUFrQjtBQUN2QixXQUFLLG1CQUFtQjtBQUN4QixXQUFLLG9CQUFvQjtBQUN6QixXQUFLLHdCQUF3QjtBQUU3QixVQUFJLE9BQU8sY0FBYyxLQUFLLFlBQVk7QUFDdEMsYUFBSyxTQUFTO0FBQUEsTUFDbEI7QUFBQSxJQUNKO0FBQUEsSUFFQSxvQkFBb0I7QUFDaEIsWUFBTSxPQUFPLFNBQVM7QUFDdEIsWUFBTSxhQUFhLEtBQUssUUFBUTtBQUNoQyxZQUFNLGNBQWMsS0FBSyxRQUFRO0FBRWpDLFVBQUksWUFBWTtBQUNaLGFBQUssYUFBYSxTQUFTLFlBQVksRUFBRTtBQUFBLE1BQzdDO0FBRUEs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
