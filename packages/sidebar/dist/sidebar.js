(() => {
  // packages/sidebar/resources/js/stores/sidebar.js
  var sidebar_default = (config = {}) => {
    const persistKeys = {
      isOpen: config.persistKeys?.isOpen ?? "sidebar-is-open",
      isCollapsed: config.persistKeys?.isCollapsed ?? "sidebar-is-collapsed",
      collapsedGroups: config.persistKeys?.collapsedGroups ?? "sidebar-collapsed-groups"
    };
    const readStored = (key, fallback) => {
      const raw = localStorage.getItem(key);
      return raw !== null ? JSON.parse(raw) : fallback;
    };
    return {
      isOpen: readStored(persistKeys.isOpen, false),
      isCollapsed: readStored(persistKeys.isCollapsed, false),
      collapsedGroups: readStored(persistKeys.collapsedGroups, []),
      collapsible: true,
      currentPath: window.location.pathname,
      breakpoint: config.breakpoint ?? 1024,
      persistKeys,
      init() {
        this.loadConfigFromDOM();
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
        document.documentElement.classList.add("sidebar-collapsed");
      },
      expand() {
        this.isCollapsed = false;
        this.persistState(this.persistKeys.isCollapsed, false);
        document.documentElement.classList.remove("sidebar-collapsed");
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
    };
  };

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
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vcmVzb3VyY2VzL2pzL3N0b3Jlcy9zaWRlYmFyLmpzIiwgIi4uL3Jlc291cmNlcy9qcy9pbmRleC5qcyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiZXhwb3J0IGRlZmF1bHQgKGNvbmZpZyA9IHt9KSA9PiB7XG4gIGNvbnN0IHBlcnNpc3RLZXlzID0ge1xuICAgIGlzT3BlbjogY29uZmlnLnBlcnNpc3RLZXlzPy5pc09wZW4gPz8gJ3NpZGViYXItaXMtb3BlbicsXG4gICAgaXNDb2xsYXBzZWQ6IGNvbmZpZy5wZXJzaXN0S2V5cz8uaXNDb2xsYXBzZWQgPz8gJ3NpZGViYXItaXMtY29sbGFwc2VkJyxcbiAgICBjb2xsYXBzZWRHcm91cHM6IGNvbmZpZy5wZXJzaXN0S2V5cz8uY29sbGFwc2VkR3JvdXBzID8/ICdzaWRlYmFyLWNvbGxhcHNlZC1ncm91cHMnLFxuICB9XG5cbiAgLy8gUmVhZCBzeW5jaHJvbm91c2x5IHNvIEFscGluZSBpbml0aWFsaXNlcyB0aGUgRE9NIHdpdGggdGhlIGNvcnJlY3QgdmFsdWVzIFx1MjAxNCBwcmV2ZW50cyB3aWR0aCBmbGFzaCBvbiBsb2FkXG4gIGNvbnN0IHJlYWRTdG9yZWQgPSAoa2V5LCBmYWxsYmFjaykgPT4ge1xuICAgIGNvbnN0IHJhdyA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKGtleSlcbiAgICByZXR1cm4gcmF3ICE9PSBudWxsID8gSlNPTi5wYXJzZShyYXcpIDogZmFsbGJhY2tcbiAgfVxuXG4gIHJldHVybiB7XG4gICAgaXNPcGVuOiByZWFkU3RvcmVkKHBlcnNpc3RLZXlzLmlzT3BlbiwgZmFsc2UpLFxuICAgIGlzQ29sbGFwc2VkOiByZWFkU3RvcmVkKHBlcnNpc3RLZXlzLmlzQ29sbGFwc2VkLCBmYWxzZSksXG4gICAgY29sbGFwc2VkR3JvdXBzOiByZWFkU3RvcmVkKHBlcnNpc3RLZXlzLmNvbGxhcHNlZEdyb3VwcywgW10pLFxuICAgIGNvbGxhcHNpYmxlOiB0cnVlLFxuICAgIGN1cnJlbnRQYXRoOiB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWUsXG5cbiAgICBicmVha3BvaW50OiBjb25maWcuYnJlYWtwb2ludCA/PyAxMDI0LFxuICAgIHBlcnNpc3RLZXlzLFxuXG4gICAgaW5pdCgpIHtcbiAgICAgIHRoaXMubG9hZENvbmZpZ0Zyb21ET00oKVxuICAgICAgdGhpcy5zZXR1cFJlc2l6ZU9ic2VydmVyKClcbiAgICAgIHRoaXMuc2V0dXBOYXZpZ2F0aW9uTGlzdGVuZXIoKVxuXG4gICAgICBpZiAod2luZG93LmlubmVyV2lkdGggPj0gdGhpcy5icmVha3BvaW50KSB7XG4gICAgICAgIHRoaXMuaXNPcGVuID0gdHJ1ZVxuICAgICAgfVxuICAgIH0sXG5cbiAgICBsb2FkQ29uZmlnRnJvbURPTSgpIHtcbiAgICAgIGNvbnN0IGJvZHkgPSBkb2N1bWVudC5ib2R5XG4gICAgICBjb25zdCBicmVha3BvaW50ID0gYm9keS5kYXRhc2V0LnNpZGViYXJCcmVha3BvaW50XG4gICAgICBjb25zdCBjb2xsYXBzaWJsZSA9IGJvZHkuZGF0YXNldC5zaWRlYmFyQ29sbGFwc2libGVcblxuICAgICAgaWYgKGJyZWFrcG9pbnQpIHtcbiAgICAgICAgdGhpcy5icmVha3BvaW50ID0gcGFyc2VJbnQoYnJlYWtwb2ludCwgMTApXG4gICAgICB9XG5cbiAgICAgIGlmIChjb2xsYXBzaWJsZSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIHRoaXMuY29sbGFwc2libGUgPSBjb2xsYXBzaWJsZSA9PT0gJ3RydWUnXG4gICAgICB9XG4gICAgfSxcblxuICAgIHBlcnNpc3RTdGF0ZShrZXksIHZhbHVlKSB7XG4gICAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbShrZXksIEpTT04uc3RyaW5naWZ5KHZhbHVlKSlcbiAgICB9LFxuXG4gICAgLy8gU2lkZWJhciB2aXNpYmlsaXR5IChtb2JpbGUpXG4gICAgb3BlbigpIHtcbiAgICAgIHRoaXMuaXNPcGVuID0gdHJ1ZVxuICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5pc09wZW4sIHRydWUpXG4gICAgfSxcblxuICAgIGNsb3NlKCkge1xuICAgICAgdGhpcy5pc09wZW4gPSBmYWxzZVxuICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5pc09wZW4sIGZhbHNlKVxuICAgIH0sXG5cbiAgICB0b2dnbGUoKSB7XG4gICAgICBpZiAod2luZG93LmlubmVyV2lkdGggPCB0aGlzLmJyZWFrcG9pbnQpIHtcbiAgICAgICAgdGhpcy5pc09wZW4gPyB0aGlzLmNsb3NlKCkgOiB0aGlzLm9wZW4oKVxuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGhpcy50b2dnbGVDb2xsYXBzZSgpXG4gICAgICB9XG4gICAgfSxcblxuICAgIC8vIFNpZGViYXIgY29sbGFwc2UgKGRlc2t0b3ApXG4gICAgY29sbGFwc2UoKSB7XG4gICAgICBpZiAoIXRoaXMuY29sbGFwc2libGUpIHJldHVyblxuXG4gICAgICB0aGlzLmlzQ29sbGFwc2VkID0gdHJ1ZVxuICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5pc0NvbGxhcHNlZCwgdHJ1ZSlcbiAgICAgIGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKCdzaWRlYmFyLWNvbGxhcHNlZCcpXG4gICAgfSxcblxuICAgIGV4cGFuZCgpIHtcbiAgICAgIHRoaXMuaXNDb2xsYXBzZWQgPSBmYWxzZVxuICAgICAgdGhpcy5wZXJzaXN0U3RhdGUodGhpcy5wZXJzaXN0S2V5cy5pc0NvbGxhcHNlZCwgZmFsc2UpXG4gICAgICBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnc2lkZWJhci1jb2xsYXBzZWQnKVxuICAgIH0sXG5cbiAgICB0b2dnbGVDb2xsYXBzZSgpIHtcbiAgICAgIGlmICghdGhpcy5jb2xsYXBzaWJsZSkgcmV0dXJuXG5cbiAgICAgIHRoaXMuaXNDb2xsYXBzZWQgPyB0aGlzLmV4cGFuZCgpIDogdGhpcy5jb2xsYXBzZSgpXG4gICAgfSxcblxuICAgIC8vIEdyb3VwIG1hbmFnZW1lbnRcbiAgICBpc0dyb3VwQ29sbGFwc2VkKGxhYmVsKSB7XG4gICAgICByZXR1cm4gdGhpcy5jb2xsYXBzZWRHcm91cHMuaW5jbHVkZXMobGFiZWwpXG4gICAgfSxcblxuICAgIGNvbGxhcHNlR3JvdXAobGFiZWwpIHtcbiAgICAgIGlmICghdGhpcy5jb2xsYXBzZWRHcm91cHMuaW5jbHVkZXMobGFiZWwpKSB7XG4gICAgICAgIHRoaXMuY29sbGFwc2VkR3JvdXBzLnB1c2gobGFiZWwpXG4gICAgICAgIHRoaXMucGVyc2lzdFN0YXRlKHRoaXMucGVyc2lzdEtleXMuY29sbGFwc2VkR3JvdXBzLCB0aGlzLmNvbGxhcHNlZEdyb3VwcylcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgZXhwYW5kR3JvdXAobGFiZWwpIHtcbiAgICAgIGNvbnN0IGluZGV4ID0gdGhpcy5jb2xsYXBzZWRHcm91cHMuaW5kZXhPZihsYWJlbClcbiAgICAgIGlmIChpbmRleCAhPT0gLTEpIHtcbiAgICAgICAgdGhpcy5jb2xsYXBzZWRHcm91cHMuc3BsaWNlKGluZGV4LCAxKVxuICAgICAgICB0aGlzLnBlcnNpc3RTdGF0ZSh0aGlzLnBlcnNpc3RLZXlzLmNvbGxhcHNlZEdyb3VwcywgdGhpcy5jb2xsYXBzZWRHcm91cHMpXG4gICAgICB9XG4gICAgfSxcblxuICAgIHRvZ2dsZUdyb3VwKGxhYmVsKSB7XG4gICAgICB0aGlzLmlzR3JvdXBDb2xsYXBzZWQobGFiZWwpID8gdGhpcy5leHBhbmRHcm91cChsYWJlbCkgOiB0aGlzLmNvbGxhcHNlR3JvdXAobGFiZWwpXG4gICAgfSxcblxuICAgIC8vIFJlc3BvbnNpdmUgaGFuZGxpbmdcbiAgICBzZXR1cFJlc2l6ZU9ic2VydmVyKCkge1xuICAgICAgbGV0IHByZXZpb3VzV2lkdGggPSB3aW5kb3cuaW5uZXJXaWR0aFxuXG4gICAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcigncmVzaXplJywgKCkgPT4ge1xuICAgICAgICBjb25zdCBjdXJyZW50V2lkdGggPSB3aW5kb3cuaW5uZXJXaWR0aFxuXG4gICAgICAgIC8vIFRyYW5zaXRpb25pbmcgZnJvbSBtb2JpbGUgdG8gZGVza3RvcFxuICAgICAgICBpZiAocHJldmlvdXNXaWR0aCA8IHRoaXMuYnJlYWtwb2ludCAmJiBjdXJyZW50V2lkdGggPj0gdGhpcy5icmVha3BvaW50KSB7XG4gICAgICAgICAgdGhpcy5pc09wZW4gPSB0cnVlXG4gICAgICAgIH1cblxuICAgICAgICAvLyBUcmFuc2l0aW9uaW5nIGZyb20gZGVza3RvcCB0byBtb2JpbGVcbiAgICAgICAgaWYgKHByZXZpb3VzV2lkdGggPj0gdGhpcy5icmVha3BvaW50ICYmIGN1cnJlbnRXaWR0aCA8IHRoaXMuYnJlYWtwb2ludCkge1xuICAgICAgICAgIHRoaXMuaXNPcGVuID0gZmFsc2VcbiAgICAgICAgfVxuXG4gICAgICAgIHByZXZpb3VzV2lkdGggPSBjdXJyZW50V2lkdGhcbiAgICAgIH0pXG4gICAgfSxcblxuICAgIC8vIExpdmV3aXJlIGludGVncmF0aW9uXG4gICAgcmVmcmVzaCgpIHtcbiAgICAgIGlmICh3aW5kb3cuTGl2ZXdpcmUpIHtcbiAgICAgICAgd2luZG93LkxpdmV3aXJlLmRpc3BhdGNoKCdzaWRlYmFyOnJlZnJlc2gnKVxuICAgICAgfVxuICAgIH0sXG5cbiAgICAvLyBOYXZpZ2F0aW9uIHRyYWNraW5nXG4gICAgc2V0dXBOYXZpZ2F0aW9uTGlzdGVuZXIoKSB7XG4gICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdsaXZld2lyZTpuYXZpZ2F0ZWQnLCAoKSA9PiB7XG4gICAgICAgIHRoaXMuY3VycmVudFBhdGggPSB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWVcbiAgICAgIH0pXG4gICAgfSxcblxuICAgIGlzQWN0aXZlKHVybCkge1xuICAgICAgaWYgKCF1cmwpIHJldHVybiBmYWxzZVxuXG4gICAgICB0cnkge1xuICAgICAgICBjb25zdCBsaW5rUGF0aCA9IG5ldyBVUkwodXJsLCB3aW5kb3cubG9jYXRpb24ub3JpZ2luKS5wYXRobmFtZVxuXG4gICAgICAgIC8vIEV4YWN0IG1hdGNoXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRQYXRoID09PSBsaW5rUGF0aCkgcmV0dXJuIHRydWVcblxuICAgICAgICAvLyBDaGVjayBpZiBjdXJyZW50IHBhdGggc3RhcnRzIHdpdGggbGluayBwYXRoIChmb3IgbmVzdGVkIHJvdXRlcylcbiAgICAgICAgLy8gQnV0IG5vdCBmb3Igcm9vdCBwYXRocyB0byBhdm9pZCBmYWxzZSBwb3NpdGl2ZXNcbiAgICAgICAgaWYgKGxpbmtQYXRoICE9PSAnLycgJiYgbGlua1BhdGgubGVuZ3RoID4gMSkge1xuICAgICAgICAgIHJldHVybiB0aGlzLmN1cnJlbnRQYXRoLnN0YXJ0c1dpdGgobGlua1BhdGggKyAnLycpIHx8IHRoaXMuY3VycmVudFBhdGguc3RhcnRzV2l0aChsaW5rUGF0aClcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiBmYWxzZVxuICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICByZXR1cm4gZmFsc2VcbiAgICAgIH1cbiAgICB9LFxuICB9XG59XG4iLCAiaW1wb3J0IHNpZGViYXJTdG9yZSBmcm9tICcuL3N0b3Jlcy9zaWRlYmFyLmpzJ1xuXG5leHBvcnQgZnVuY3Rpb24gcmVnaXN0ZXJTaWRlYmFyU3RvcmUoY29uZmlnID0ge30pIHtcbiAgICBpZiAodHlwZW9mIHdpbmRvdy5BbHBpbmUgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIGNvbnNvbGUuZXJyb3IoJ1tTaWRlYmFyXSBBbHBpbmUuanMgaXMgcmVxdWlyZWQgYnV0IG5vdCBmb3VuZC4nKVxuICAgICAgICByZXR1cm5cbiAgICB9XG5cbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdhbHBpbmU6aW5pdCcsICgpID0+IHtcbiAgICAgICAgd2luZG93LkFscGluZS5zdG9yZSgnc2lkZWJhcicsIHNpZGViYXJTdG9yZShjb25maWcpKVxuICAgIH0pXG59XG5cbmV4cG9ydCBmdW5jdGlvbiBpbml0U2lkZWJhclN0b3JlKGNvbmZpZyA9IHt9KSB7XG4gICAgaWYgKHR5cGVvZiB3aW5kb3cuQWxwaW5lID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICBjb25zb2xlLmVycm9yKCdbU2lkZWJhcl0gQWxwaW5lLmpzIGlzIHJlcXVpcmVkIGJ1dCBub3QgZm91bmQuJylcbiAgICAgICAgcmV0dXJuXG4gICAgfVxuXG4gICAgd2luZG93LkFscGluZS5zdG9yZSgnc2lkZWJhcicsIHNpZGViYXJTdG9yZShjb25maWcpKVxufVxuXG5leHBvcnQgZnVuY3Rpb24gZ2V0U2lkZWJhclN0b3JlKCkge1xuICAgIGlmICh0eXBlb2Ygd2luZG93LkFscGluZSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgY29uc29sZS5lcnJvcignW1NpZGViYXJdIEFscGluZS5qcyBpcyByZXF1aXJlZCBidXQgbm90IGZvdW5kLicpXG4gICAgICAgIHJldHVybiBudWxsXG4gICAgfVxuXG4gICAgcmV0dXJuIHdpbmRvdy5BbHBpbmUuc3RvcmUoJ3NpZGViYXInKVxufVxuXG4vLyBBdXRvLXJlZ2lzdGVyIGlmIEFscGluZSBpcyBhdmFpbGFibGUgYW5kIGF1dG8taW5pdCBpcyBlbmFibGVkXG5pZiAodHlwZW9mIHdpbmRvdyAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICB3aW5kb3cuU2lkZWJhclN0b3JlID0ge1xuICAgICAgICByZWdpc3RlcjogcmVnaXN0ZXJTaWRlYmFyU3RvcmUsXG4gICAgICAgIGluaXQ6IGluaXRTaWRlYmFyU3RvcmUsXG4gICAgICAgIGdldDogZ2V0U2lkZWJhclN0b3JlLFxuICAgICAgICBzdG9yZTogc2lkZWJhclN0b3JlLFxuICAgIH1cbn1cblxuZXhwb3J0IHsgc2lkZWJhclN0b3JlIH1cbmV4cG9ydCBkZWZhdWx0IHNpZGViYXJTdG9yZVxuIl0sCiAgIm1hcHBpbmdzIjogIjs7QUFBQSxNQUFPLGtCQUFRLENBQUMsU0FBUyxDQUFDLE1BQU07QUFDOUIsVUFBTSxjQUFjO0FBQUEsTUFDbEIsUUFBUSxPQUFPLGFBQWEsVUFBVTtBQUFBLE1BQ3RDLGFBQWEsT0FBTyxhQUFhLGVBQWU7QUFBQSxNQUNoRCxpQkFBaUIsT0FBTyxhQUFhLG1CQUFtQjtBQUFBLElBQzFEO0FBR0EsVUFBTSxhQUFhLENBQUMsS0FBSyxhQUFhO0FBQ3BDLFlBQU0sTUFBTSxhQUFhLFFBQVEsR0FBRztBQUNwQyxhQUFPLFFBQVEsT0FBTyxLQUFLLE1BQU0sR0FBRyxJQUFJO0FBQUEsSUFDMUM7QUFFQSxXQUFPO0FBQUEsTUFDTCxRQUFRLFdBQVcsWUFBWSxRQUFRLEtBQUs7QUFBQSxNQUM1QyxhQUFhLFdBQVcsWUFBWSxhQUFhLEtBQUs7QUFBQSxNQUN0RCxpQkFBaUIsV0FBVyxZQUFZLGlCQUFpQixDQUFDLENBQUM7QUFBQSxNQUMzRCxhQUFhO0FBQUEsTUFDYixhQUFhLE9BQU8sU0FBUztBQUFBLE1BRTdCLFlBQVksT0FBTyxjQUFjO0FBQUEsTUFDakM7QUFBQSxNQUVBLE9BQU87QUFDTCxhQUFLLGtCQUFrQjtBQUN2QixhQUFLLG9CQUFvQjtBQUN6QixhQUFLLHdCQUF3QjtBQUU3QixZQUFJLE9BQU8sY0FBYyxLQUFLLFlBQVk7QUFDeEMsZUFBSyxTQUFTO0FBQUEsUUFDaEI7QUFBQSxNQUNGO0FBQUEsTUFFQSxvQkFBb0I7QUFDbEIsY0FBTSxPQUFPLFNBQVM7QUFDdEIsY0FBTSxhQUFhLEtBQUssUUFBUTtBQUNoQyxjQUFNLGNBQWMsS0FBSyxRQUFRO0FBRWpDLFlBQUksWUFBWTtBQUNkLGVBQUssYUFBYSxTQUFTLFlBQVksRUFBRTtBQUFBLFFBQzNDO0FBRUEsWUFBSSxnQkFBZ0IsUUFBVztBQUM3QixlQUFLLGNBQWMsZ0JBQWdCO0FBQUEsUUFDckM7QUFBQSxNQUNGO0FBQUEsTUFFQSxhQUFhLEtBQUssT0FBTztBQUN2QixxQkFBYSxRQUFRLEtBQUssS0FBSyxVQUFVLEtBQUssQ0FBQztBQUFBLE1BQ2pEO0FBQUE7QUFBQSxNQUdBLE9BQU87QUFDTCxhQUFLLFNBQVM7QUFDZCxhQUFLLGFBQWEsS0FBSyxZQUFZLFFBQVEsSUFBSTtBQUFBLE1BQ2pEO0FBQUEsTUFFQSxRQUFRO0FBQ04sYUFBSyxTQUFTO0FBQ2QsYUFBSyxhQUFhLEtBQUssWUFBWSxRQUFRLEtBQUs7QUFBQSxNQUNsRDtBQUFBLE1BRUEsU0FBUztBQUNQLFlBQUksT0FBTyxhQUFhLEtBQUssWUFBWTtBQUN2QyxlQUFLLFNBQVMsS0FBSyxNQUFNLElBQUksS0FBSyxLQUFLO0FBQUEsUUFDekMsT0FBTztBQUNMLGVBQUssZUFBZTtBQUFBLFFBQ3RCO0FBQUEsTUFDRjtBQUFBO0FBQUEsTUFHQSxXQUFXO0FBQ1QsWUFBSSxDQUFDLEtBQUssWUFBYTtBQUV2QixhQUFLLGNBQWM7QUFDbkIsYUFBSyxhQUFhLEtBQUssWUFBWSxhQUFhLElBQUk7QUFDcEQsaUJBQVMsZ0JBQWdCLFVBQVUsSUFBSSxtQkFBbUI7QUFBQSxNQUM1RDtBQUFBLE1BRUEsU0FBUztBQUNQLGFBQUssY0FBYztBQUNuQixhQUFLLGFBQWEsS0FBSyxZQUFZLGFBQWEsS0FBSztBQUNyRCxpQkFBUyxnQkFBZ0IsVUFBVSxPQUFPLG1CQUFtQjtBQUFBLE1BQy9EO0FBQUEsTUFFQSxpQkFBaUI7QUFDZixZQUFJLENBQUMsS0FBSyxZQUFhO0FBRXZCLGFBQUssY0FBYyxLQUFLLE9BQU8sSUFBSSxLQUFLLFNBQVM7QUFBQSxNQUNuRDtBQUFBO0FBQUEsTUFHQSxpQkFBaUIsT0FBTztBQUN0QixlQUFPLEtBQUssZ0JBQWdCLFNBQVMsS0FBSztBQUFBLE1BQzVDO0FBQUEsTUFFQSxjQUFjLE9BQU87QUFDbkIsWUFBSSxDQUFDLEtBQUssZ0JBQWdCLFNBQVMsS0FBSyxHQUFHO0FBQ3pDLGVBQUssZ0JBQWdCLEtBQUssS0FBSztBQUMvQixlQUFLLGFBQWEsS0FBSyxZQUFZLGlCQUFpQixLQUFLLGVBQWU7QUFBQSxRQUMxRTtBQUFBLE1BQ0Y7QUFBQSxNQUVBLFlBQVksT0FBTztBQUNqQixjQUFNLFFBQVEsS0FBSyxnQkFBZ0IsUUFBUSxLQUFLO0FBQ2hELFlBQUksVUFBVSxJQUFJO0FBQ2hCLGVBQUssZ0JBQWdCLE9BQU8sT0FBTyxDQUFDO0FBQ3BDLGVBQUssYUFBYSxLQUFLLFlBQVksaUJBQWlCLEtBQUssZUFBZTtBQUFBLFFBQzFFO0FBQUEsTUFDRjtBQUFBLE1BRUEsWUFBWSxPQUFPO0FBQ2pCLGFBQUssaUJBQWlCLEtBQUssSUFBSSxLQUFLLFlBQVksS0FBSyxJQUFJLEtBQUssY0FBYyxLQUFLO0FBQUEsTUFDbkY7QUFBQTtBQUFBLE1BR0Esc0JBQXNCO0FBQ3BCLFlBQUksZ0JBQWdCLE9BQU87QUFFM0IsZUFBTyxpQkFBaUIsVUFBVSxNQUFNO0FBQ3RDLGdCQUFNLGVBQWUsT0FBTztBQUc1QixjQUFJLGdCQUFnQixLQUFLLGNBQWMsZ0JBQWdCLEtBQUssWUFBWTtBQUN0RSxpQkFBSyxTQUFTO0FBQUEsVUFDaEI7QUFHQSxjQUFJLGlCQUFpQixLQUFLLGNBQWMsZUFBZSxLQUFLLFlBQVk7QUFDdEUsaUJBQUssU0FBUztBQUFBLFVBQ2hCO0FBRUEsMEJBQWdCO0FBQUEsUUFDbEIsQ0FBQztBQUFBLE1BQ0g7QUFBQTtBQUFBLE1BR0EsVUFBVTtBQUNSLFlBQUksT0FBTyxVQUFVO0FBQ25CLGlCQUFPLFNBQVMsU0FBUyxpQkFBaUI7QUFBQSxRQUM1QztBQUFBLE1BQ0Y7QUFBQTtBQUFBLE1BR0EsMEJBQTBCO0FBQ3hCLGlCQUFTLGlCQUFpQixzQkFBc0IsTUFBTTtBQUNwRCxlQUFLLGNBQWMsT0FBTyxTQUFTO0FBQUEsUUFDckMsQ0FBQztBQUFBLE1BQ0g7QUFBQSxNQUVBLFNBQVMsS0FBSztBQUNaLFlBQUksQ0FBQyxJQUFLLFFBQU87QUFFakIsWUFBSTtBQUNGLGdCQUFNLFdBQVcsSUFBSSxJQUFJLEtBQUssT0FBTyxTQUFTLE1BQU0sRUFBRTtBQUd0RCxjQUFJLEtBQUssZ0JBQWdCLFNBQVUsUUFBTztBQUkxQyxjQUFJLGFBQWEsT0FBTyxTQUFTLFNBQVMsR0FBRztBQUMzQyxtQkFBTyxLQUFLLFlBQVksV0FBVyxXQUFXLEdBQUcsS0FBSyxLQUFLLFlBQVksV0FBVyxRQUFRO0FBQUEsVUFDNUY7QUFFQSxpQkFBTztBQUFBLFFBQ1QsU0FBUyxHQUFHO0FBQ1YsaUJBQU87QUFBQSxRQUNUO0FBQUEsTUFDRjtBQUFBLElBQ0Y7QUFBQSxFQUNGOzs7QUN6S08sV0FBUyxxQkFBcUIsU0FBUyxDQUFDLEdBQUc7QUFDOUMsUUFBSSxPQUFPLE9BQU8sV0FBVyxhQUFhO0FBQ3RDLGNBQVEsTUFBTSxnREFBZ0Q7QUFDOUQ7QUFBQSxJQUNKO0FBRUEsYUFBUyxpQkFBaUIsZUFBZSxNQUFNO0FBQzNDLGFBQU8sT0FBTyxNQUFNLFdBQVcsZ0JBQWEsTUFBTSxDQUFDO0FBQUEsSUFDdkQsQ0FBQztBQUFBLEVBQ0w7QUFFTyxXQUFTLGlCQUFpQixTQUFTLENBQUMsR0FBRztBQUMxQyxRQUFJLE9BQU8sT0FBTyxXQUFXLGFBQWE7QUFDdEMsY0FBUSxNQUFNLGdEQUFnRDtBQUM5RDtBQUFBLElBQ0o7QUFFQSxXQUFPLE9BQU8sTUFBTSxXQUFXLGdCQUFhLE1BQU0sQ0FBQztBQUFBLEVBQ3ZEO0FBRU8sV0FBUyxrQkFBa0I7QUFDOUIsUUFBSSxPQUFPLE9BQU8sV0FBVyxhQUFhO0FBQ3RDLGNBQVEsTUFBTSxnREFBZ0Q7QUFDOUQsYUFBTztBQUFBLElBQ1g7QUFFQSxXQUFPLE9BQU8sT0FBTyxNQUFNLFNBQVM7QUFBQSxFQUN4QztBQUdBLE1BQUksT0FBTyxXQUFXLGFBQWE7QUFDL0IsV0FBTyxlQUFlO0FBQUEsTUFDbEIsVUFBVTtBQUFBLE1BQ1YsTUFBTTtBQUFBLE1BQ04sS0FBSztBQUFBLE1BQ0wsT0FBTztBQUFBLElBQ1g7QUFBQSxFQUNKO0FBR0EsTUFBTyxnQkFBUTsiLAogICJuYW1lcyI6IFtdCn0K
