(() => {
  // resources/js/components/panel.js
  var SlideOverPanel = () => {
    return {
      open: false,
      showActiveComponent: true,
      activeComponent: false,
      componentHistory: [],
      panelWidth: null,
      listeners: [],
      getActiveComponentPanelAttribute(key) {
        if (this.$wire.get("components")[this.activeComponent] !== void 0) {
          return this.$wire.get("components")[this.activeComponent]["panelAttributes"][key];
        }
      },
      closePanelOnEscape(trigger) {
        if (this.getActiveComponentPanelAttribute("closeOnEscape") === false) {
          return;
        }
        let force = this.getActiveComponentPanelAttribute("closeOnEscapeIsForceful") === true;
        this.closePanel(force);
      },
      closePanelOnClickAway(trigger) {
        if (this.getActiveComponentPanelAttribute("closeOnClickAway") === false) {
          return;
        }
        this.closePanel(true);
      },
      closePanel(force = false, skipPreviousPanels = 0, destroySkipped = false) {
        if (this.show === false) {
          return;
        }
        if (this.getActiveComponentPanelAttribute("dispatchCloseEvent") === true) {
          const componentName = this.$wire.get("components")[this.activeComponent].name;
          Livewire.dispatch("panelClosed", { name: componentName });
        }
        if (this.getActiveComponentPanelAttribute("destroyOnClose") === true) {
          Livewire.dispatch("destroyComponent", { id: this.activeComponent });
        }
        if (skipPreviousPanels > 0) {
          for (let i = 0; i < skipPreviousPanels; i++) {
            if (destroySkipped) {
              const id2 = this.componentHistory[this.componentHistory.length - 1];
              Livewire.dispatch("destroyComponent", { id: id2 });
            }
            this.componentHistory.pop();
          }
        }
        const id = this.componentHistory.pop();
        if (id && !force) {
          if (id) {
            this.setActivePanelComponent(id, true);
          } else {
            this.setShowPropertyTo(false);
          }
        } else {
          this.setShowPropertyTo(false);
        }
      },
      setActivePanelComponent(id, skip = false) {
        this.setShowPropertyTo(true);
        if (this.activeComponent === id) {
          return;
        }
        if (this.activeComponent !== false && skip === false) {
          this.componentHistory.push(this.activeComponent);
        }
        let focusableTimeout = 50;
        if (this.activeComponent === false) {
          this.activeComponent = id;
          this.showActiveComponent = true;
          this.panelWidth = this.getActiveComponentPanelAttribute("maxWidthClass");
        } else {
          this.showActiveComponent = false;
          focusableTimeout = 400;
          setTimeout(() => {
            this.activeComponent = id;
            this.showActiveComponent = true;
            this.panelWidth = this.getActiveComponentPanelAttribute("maxWidthClass");
          }, 300);
        }
        this.$nextTick(() => {
          let focusable = this.$refs[id]?.querySelector("[autofocus]");
          if (focusable) {
            setTimeout(() => {
              focusable.focus();
            }, focusableTimeout);
          }
        });
      },
      focusables() {
        let selector = "a, button, input:not([type='hidden'], textarea, select, details, [tabindex]:not([tabindex='-1'])";
        return [...this.$el.querySelectorAll(selector)].filter((el) => !el.hasAttribute("disabled"));
      },
      firstFocusable() {
        return this.focusables()[0];
      },
      lastFocusable() {
        return this.focusables().slice(-1)[0];
      },
      nextFocusable() {
        return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable();
      },
      prevFocusable() {
        return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable();
      },
      nextFocusableIndex() {
        return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1);
      },
      prevFocusableIndex() {
        return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1;
      },
      setShowPropertyTo(open) {
        this.open = open;
        if (open) {
          document.body.classList.add("overflow-y-hidden");
        } else {
          document.body.classList.remove("overflow-y-hidden");
          setTimeout(() => {
            this.activeComponent = false;
            this.$wire.resetState();
          }, 300);
        }
      },
      init() {
        this.panelWidth = this.getActiveComponentPanelAttribute("maxWidthClass");
        this.listeners.push(
          Livewire.on("closePanel", (data) => {
            this.closePanel(data?.force ?? false, data?.skipPreviousPanels ?? 0, data?.destroySkipped ?? false);
          })
        );
        this.listeners.push(
          Livewire.on("activePanelComponentChanged", ({ id }) => {
            this.setActivePanelComponent(id);
          })
        );
      },
      destroy() {
        this.listeners.forEach((listener) => {
          listener();
        });
      }
    };
  };
  var panel_default = SlideOverPanel;

  // resources/js/index.js
  window.SlideOverPanel = panel_default;
  document.addEventListener("alpine:init", () => {
    const theme = localStorage.getItem("theme") ?? "light";
    window.Alpine.store(
      "theme",
      theme === "dark" || theme === "system" && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"
    );
    window.addEventListener("theme-changed", (event) => {
      let theme2 = event.detail;
      localStorage.setItem("theme", theme2);
      if (theme2 === "system") {
        theme2 = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      window.Alpine.store("theme", theme2);
    });
    window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (event) => {
      if (localStorage.getItem("theme") === "system") {
        window.Alpine.store("theme", event.matches ? "dark" : "light");
      }
    });
    window.Alpine.effect(() => {
      const theme2 = window.Alpine.store("theme");
      theme2 === "dark" ? document.documentElement.classList.add("dark") : document.documentElement.classList.remove("dark");
    });
  });
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvcGFuZWwuanMiLCAiLi4vcmVzb3VyY2VzL2pzL2luZGV4LmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBTbGlkZU92ZXJQYW5lbCA9ICgpID0+IHtcbiAgcmV0dXJuIHtcbiAgICBvcGVuOiBmYWxzZSxcbiAgICBzaG93QWN0aXZlQ29tcG9uZW50OiB0cnVlLFxuICAgIGFjdGl2ZUNvbXBvbmVudDogZmFsc2UsXG4gICAgY29tcG9uZW50SGlzdG9yeTogW10sXG4gICAgcGFuZWxXaWR0aDogbnVsbCxcbiAgICBsaXN0ZW5lcnM6IFtdLFxuICAgIGdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKGtleSkge1xuICAgICAgaWYgKHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdWydwYW5lbEF0dHJpYnV0ZXMnXVtrZXldO1xuICAgICAgfVxuICAgIH0sXG4gICAgY2xvc2VQYW5lbE9uRXNjYXBlKHRyaWdnZXIpIHtcbiAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdjbG9zZU9uRXNjYXBlJykgPT09IGZhbHNlKSB7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgbGV0IGZvcmNlID0gdGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnY2xvc2VPbkVzY2FwZUlzRm9yY2VmdWwnKSA9PT0gdHJ1ZTtcbiAgICAgIHRoaXMuY2xvc2VQYW5lbChmb3JjZSk7XG4gICAgfSxcbiAgICBjbG9zZVBhbmVsT25DbGlja0F3YXkodHJpZ2dlcikge1xuICAgICAgaWYgKHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ2Nsb3NlT25DbGlja0F3YXknKSA9PT0gZmFsc2UpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICB0aGlzLmNsb3NlUGFuZWwodHJ1ZSk7XG4gICAgfSxcbiAgICBjbG9zZVBhbmVsKGZvcmNlID0gZmFsc2UsIHNraXBQcmV2aW91c1BhbmVscyA9IDAsIGRlc3Ryb3lTa2lwcGVkID0gZmFsc2UpIHtcbiAgICAgIGlmKHRoaXMuc2hvdyA9PT0gZmFsc2UpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICBpZiAodGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnZGlzcGF0Y2hDbG9zZUV2ZW50JykgPT09IHRydWUpIHtcbiAgICAgICAgY29uc3QgY29tcG9uZW50TmFtZSA9IHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdLm5hbWU7XG4gICAgICAgIExpdmV3aXJlLmRpc3BhdGNoKCdwYW5lbENsb3NlZCcsIHsgbmFtZTogY29tcG9uZW50TmFtZSB9KTtcbiAgICAgIH1cblxuICAgICAgaWYgKHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ2Rlc3Ryb3lPbkNsb3NlJykgPT09IHRydWUpIHtcbiAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ2Rlc3Ryb3lDb21wb25lbnQnLCB7IGlkOiB0aGlzLmFjdGl2ZUNvbXBvbmVudCB9KTtcbiAgICAgIH1cblxuICAgICAgaWYgKHNraXBQcmV2aW91c1BhbmVscyA+IDApIHtcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBza2lwUHJldmlvdXNQYW5lbHM7IGkrKykge1xuICAgICAgICAgIGlmIChkZXN0cm95U2tpcHBlZCkge1xuICAgICAgICAgICAgY29uc3QgaWQgPSB0aGlzLmNvbXBvbmVudEhpc3RvcnlbdGhpcy5jb21wb25lbnRIaXN0b3J5Lmxlbmd0aCAtIDFdO1xuICAgICAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ2Rlc3Ryb3lDb21wb25lbnQnLCB7IGlkOiBpZCB9KTtcbiAgICAgICAgICB9XG4gICAgICAgICAgdGhpcy5jb21wb25lbnRIaXN0b3J5LnBvcCgpO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIGNvbnN0IGlkID0gdGhpcy5jb21wb25lbnRIaXN0b3J5LnBvcCgpO1xuXG4gICAgICBpZiAoaWQgJiYgIWZvcmNlKSB7XG4gICAgICAgIGlmIChpZCkge1xuICAgICAgICAgIHRoaXMuc2V0QWN0aXZlUGFuZWxDb21wb25lbnQoaWQsIHRydWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHRoaXMuc2V0U2hvd1Byb3BlcnR5VG8oZmFsc2UpO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLnNldFNob3dQcm9wZXJ0eVRvKGZhbHNlKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIHNldEFjdGl2ZVBhbmVsQ29tcG9uZW50KGlkLCBza2lwID0gZmFsc2UpIHtcbiAgICAgIHRoaXMuc2V0U2hvd1Byb3BlcnR5VG8odHJ1ZSk7XG5cbiAgICAgIGlmICh0aGlzLmFjdGl2ZUNvbXBvbmVudCA9PT0gaWQpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICBpZiAodGhpcy5hY3RpdmVDb21wb25lbnQgIT09IGZhbHNlICYmIHNraXAgPT09IGZhbHNlKSB7XG4gICAgICAgIHRoaXMuY29tcG9uZW50SGlzdG9yeS5wdXNoKHRoaXMuYWN0aXZlQ29tcG9uZW50KTtcbiAgICAgIH1cblxuICAgICAgbGV0IGZvY3VzYWJsZVRpbWVvdXQgPSA1MDtcblxuICAgICAgaWYgKHRoaXMuYWN0aXZlQ29tcG9uZW50ID09PSBmYWxzZSkge1xuICAgICAgICB0aGlzLmFjdGl2ZUNvbXBvbmVudCA9IGlkXG4gICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IHRydWU7XG4gICAgICAgIHRoaXMucGFuZWxXaWR0aCA9IHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ21heFdpZHRoQ2xhc3MnKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IGZhbHNlO1xuXG4gICAgICAgIGZvY3VzYWJsZVRpbWVvdXQgPSA0MDA7XG5cbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgdGhpcy5hY3RpdmVDb21wb25lbnQgPSBpZDtcbiAgICAgICAgICB0aGlzLnNob3dBY3RpdmVDb21wb25lbnQgPSB0cnVlO1xuICAgICAgICAgIHRoaXMucGFuZWxXaWR0aCA9IHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ21heFdpZHRoQ2xhc3MnKTtcbiAgICAgICAgfSwgMzAwKTtcbiAgICAgIH1cblxuICAgICAgdGhpcy4kbmV4dFRpY2soKCkgPT4ge1xuICAgICAgICBsZXQgZm9jdXNhYmxlID0gdGhpcy4kcmVmc1tpZF0/LnF1ZXJ5U2VsZWN0b3IoJ1thdXRvZm9jdXNdJyk7XG4gICAgICAgIGlmIChmb2N1c2FibGUpIHtcbiAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgIGZvY3VzYWJsZS5mb2N1cygpO1xuICAgICAgICAgIH0sIGZvY3VzYWJsZVRpbWVvdXQpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9LFxuICAgIGZvY3VzYWJsZXMoKSB7XG4gICAgICBsZXQgc2VsZWN0b3IgPSAnYSwgYnV0dG9uLCBpbnB1dDpub3QoW3R5cGU9XFwnaGlkZGVuXFwnXSwgdGV4dGFyZWEsIHNlbGVjdCwgZGV0YWlscywgW3RhYmluZGV4XTpub3QoW3RhYmluZGV4PVxcJy0xXFwnXSknXG5cbiAgICAgIHJldHVybiBbLi4udGhpcy4kZWwucXVlcnlTZWxlY3RvckFsbChzZWxlY3RvcildXG4gICAgICAgIC5maWx0ZXIoZWwgPT4gIWVsLmhhc0F0dHJpYnV0ZSgnZGlzYWJsZWQnKSlcbiAgICB9LFxuICAgIGZpcnN0Rm9jdXNhYmxlKCkge1xuICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpWzBdXG4gICAgfSxcbiAgICBsYXN0Rm9jdXNhYmxlKCkge1xuICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpLnNsaWNlKC0xKVswXVxuICAgIH0sXG4gICAgbmV4dEZvY3VzYWJsZSgpIHtcbiAgICAgIHJldHVybiB0aGlzLmZvY3VzYWJsZXMoKVt0aGlzLm5leHRGb2N1c2FibGVJbmRleCgpXSB8fCB0aGlzLmZpcnN0Rm9jdXNhYmxlKClcbiAgICB9LFxuICAgIHByZXZGb2N1c2FibGUoKSB7XG4gICAgICByZXR1cm4gdGhpcy5mb2N1c2FibGVzKClbdGhpcy5wcmV2Rm9jdXNhYmxlSW5kZXgoKV0gfHwgdGhpcy5sYXN0Rm9jdXNhYmxlKClcbiAgICB9LFxuICAgIG5leHRGb2N1c2FibGVJbmRleCgpIHtcbiAgICAgIHJldHVybiAodGhpcy5mb2N1c2FibGVzKCkuaW5kZXhPZihkb2N1bWVudC5hY3RpdmVFbGVtZW50KSArIDEpICUgKHRoaXMuZm9jdXNhYmxlcygpLmxlbmd0aCArIDEpXG4gICAgfSxcbiAgICBwcmV2Rm9jdXNhYmxlSW5kZXgoKSB7XG4gICAgICByZXR1cm4gTWF0aC5tYXgoMCwgdGhpcy5mb2N1c2FibGVzKCkuaW5kZXhPZihkb2N1bWVudC5hY3RpdmVFbGVtZW50KSkgLSAxXG4gICAgfSxcbiAgICBzZXRTaG93UHJvcGVydHlUbyhvcGVuKSB7XG4gICAgICB0aGlzLm9wZW4gPSBvcGVuO1xuXG4gICAgICBpZiAob3Blbikge1xuICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5hZGQoJ292ZXJmbG93LXktaGlkZGVuJyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ292ZXJmbG93LXktaGlkZGVuJyk7XG5cbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgdGhpcy5hY3RpdmVDb21wb25lbnQgPSBmYWxzZTtcbiAgICAgICAgICB0aGlzLiR3aXJlLnJlc2V0U3RhdGUoKTtcbiAgICAgICAgfSwgMzAwKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGluaXQoKSB7XG4gICAgICB0aGlzLnBhbmVsV2lkdGggPSB0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdtYXhXaWR0aENsYXNzJyk7XG5cbiAgICAgIHRoaXMubGlzdGVuZXJzLnB1c2goXG4gICAgICAgIExpdmV3aXJlLm9uKCdjbG9zZVBhbmVsJywgKGRhdGEpID0+IHtcbiAgICAgICAgICB0aGlzLmNsb3NlUGFuZWwoZGF0YT8uZm9yY2UgPz8gZmFsc2UsIGRhdGE/LnNraXBQcmV2aW91c1BhbmVscyA/PyAwLCBkYXRhPy5kZXN0cm95U2tpcHBlZCA/PyBmYWxzZSk7XG4gICAgICAgIH0pXG4gICAgICApO1xuXG4gICAgICB0aGlzLmxpc3RlbmVycy5wdXNoKFxuICAgICAgICBMaXZld2lyZS5vbignYWN0aXZlUGFuZWxDb21wb25lbnRDaGFuZ2VkJywgKHsgaWQgfSkgPT4ge1xuICAgICAgICAgIHRoaXMuc2V0QWN0aXZlUGFuZWxDb21wb25lbnQoaWQpO1xuICAgICAgICB9KVxuICAgICAgKTtcbiAgICB9LFxuICAgIGRlc3Ryb3koKSB7XG4gICAgICB0aGlzLmxpc3RlbmVycy5mb3JFYWNoKChsaXN0ZW5lcikgPT4ge1xuICAgICAgICBsaXN0ZW5lcigpO1xuICAgICAgfSk7XG4gICAgfVxuICB9XG59XG5cbmV4cG9ydCBkZWZhdWx0IFNsaWRlT3ZlclBhbmVsXG4iLCAiLypcbmltcG9ydCB7IEFscGluZSwgTGl2ZXdpcmUgfSBmcm9tICcuLi8uLi92ZW5kb3IvbGl2ZXdpcmUvbGl2ZXdpcmUvZGlzdC9saXZld2lyZS5lc20nXG5cbmltcG9ydCBpbnRlcm5hdGlvbmFsTnVtYmVyIGZyb20gJy4vcGx1Z2lucy9pbnRlcm5hdGlvbmFsTnVtYmVyJ1xuaW1wb3J0IEtleVByZXNzIGZyb20gJy4vcGx1Z2lucy9rZXlQcmVzcydcbmltcG9ydCAnLi9oZWxwZXJzL3dpbmRvdydcbmltcG9ydCAnLi9oZWxwZXJzL3RyaXgnXG5cbkFscGluZS5wbHVnaW4oS2V5UHJlc3MpXG5BbHBpbmUuZGF0YSgnaW50ZXJuYXRpb25hbE51bWJlcicsIGludGVybmF0aW9uYWxOdW1iZXIpXG5cbndpbmRvdy5pbnRlcm5hdGlvbmFsTnVtYmVyID0gaW50ZXJuYXRpb25hbE51bWJlclxuXG5jb25zdCB0aGVtZSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCd0aGVtZScpID8/ICdzeXN0ZW0nXG5cbndpbmRvdy5BbHBpbmUuc3RvcmUoXG4gICd0aGVtZScsXG4gIHRoZW1lID09PSAnZGFyaycgfHxcbiAgICAodGhlbWUgPT09ICdzeXN0ZW0nICYmXG4gICAgICB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLm1hdGNoZXMpXG4gICAgPyAnZGFyaydcbiAgICA6ICdsaWdodCcsXG4pXG5cbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCd0aGVtZS1jaGFuZ2VkJywgKGV2ZW50KSA9PiB7XG4gIGxldCB0aGVtZSA9IGV2ZW50LmRldGFpbFxuXG4gIGxvY2FsU3RvcmFnZS5zZXRJdGVtKCd0aGVtZScsIHRoZW1lKVxuXG4gIGlmICh0aGVtZSA9PT0gJ3N5c3RlbScpIHtcbiAgICB0aGVtZSA9IHdpbmRvdy5tYXRjaE1lZGlhKCcocHJlZmVycy1jb2xvci1zY2hlbWU6IGRhcmspJykubWF0Y2hlc1xuICAgICAgPyAnZGFyaydcbiAgICAgIDogJ2xpZ2h0J1xuICB9XG5cbiAgd2luZG93LkFscGluZS5zdG9yZSgndGhlbWUnLCB0aGVtZSlcbn0pXG5cbndpbmRvd1xuICAubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpXG4gIC5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2UnLCAoZXZlbnQpID0+IHtcbiAgICBpZiAobG9jYWxTdG9yYWdlLmdldEl0ZW0oJ3RoZW1lJykgPT09ICdzeXN0ZW0nKSB7XG4gICAgICB3aW5kb3cuQWxwaW5lLnN0b3JlKCd0aGVtZScsIGV2ZW50Lm1hdGNoZXMgPyAnZGFyaycgOiAnbGlnaHQnKVxuICAgIH1cbiAgfSlcblxud2luZG93LkFscGluZS5lZmZlY3QoKCkgPT4ge1xuICBjb25zdCB0aGVtZSA9IHdpbmRvdy5BbHBpbmUuc3RvcmUoJ3RoZW1lJylcblxuICB0aGVtZSA9PT0gJ2RhcmsnXG4gICAgPyBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGFyaycpXG4gICAgOiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGFyaycpXG59KVxuXG5MaXZld2lyZS5zdGFydCgpXG4qL1xuaW1wb3J0IFNsaWRlT3ZlclBhbmVsIGZyb20gJy4vY29tcG9uZW50cy9wYW5lbCdcblxud2luZG93LlNsaWRlT3ZlclBhbmVsID0gU2xpZGVPdmVyUGFuZWxcblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignYWxwaW5lOmluaXQnLCAoKSA9PiB7XG4gIGNvbnN0IHRoZW1lID0gbG9jYWxTdG9yYWdlLmdldEl0ZW0oJ3RoZW1lJykgPz8gJ2xpZ2h0J1xuXG4gIHdpbmRvdy5BbHBpbmUuc3RvcmUoXG4gICAgJ3RoZW1lJyxcbiAgICB0aGVtZSA9PT0gJ2RhcmsnIHx8XG4gICAgKHRoZW1lID09PSAnc3lzdGVtJyAmJlxuICAgICAgd2luZG93Lm1hdGNoTWVkaWEoJyhwcmVmZXJzLWNvbG9yLXNjaGVtZTogZGFyayknKS5tYXRjaGVzKVxuICAgICAgPyAnZGFyaydcbiAgICAgIDogJ2xpZ2h0JyxcbiAgKVxuXG4gIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCd0aGVtZS1jaGFuZ2VkJywgKGV2ZW50KSA9PiB7XG4gICAgbGV0IHRoZW1lID0gZXZlbnQuZGV0YWlsXG5cbiAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbSgndGhlbWUnLCB0aGVtZSlcblxuICAgIGlmICh0aGVtZSA9PT0gJ3N5c3RlbScpIHtcbiAgICAgIHRoZW1lID0gd2luZG93Lm1hdGNoTWVkaWEoJyhwcmVmZXJzLWNvbG9yLXNjaGVtZTogZGFyayknKS5tYXRjaGVzXG4gICAgICAgID8gJ2RhcmsnXG4gICAgICAgIDogJ2xpZ2h0J1xuICAgIH1cblxuICAgIHdpbmRvdy5BbHBpbmUuc3RvcmUoJ3RoZW1lJywgdGhlbWUpXG4gIH0pXG5cbiAgd2luZG93XG4gICAgLm1hdGNoTWVkaWEoJyhwcmVmZXJzLWNvbG9yLXNjaGVtZTogZGFyayknKVxuICAgIC5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2UnLCAoZXZlbnQpID0+IHtcbiAgICAgIGlmIChsb2NhbFN0b3JhZ2UuZ2V0SXRlbSgndGhlbWUnKSA9PT0gJ3N5c3RlbScpIHtcbiAgICAgICAgd2luZG93LkFscGluZS5zdG9yZSgndGhlbWUnLCBldmVudC5tYXRjaGVzID8gJ2RhcmsnIDogJ2xpZ2h0JylcbiAgICAgIH1cbiAgICB9KVxuXG4gIHdpbmRvdy5BbHBpbmUuZWZmZWN0KCgpID0+IHtcbiAgICBjb25zdCB0aGVtZSA9IHdpbmRvdy5BbHBpbmUuc3RvcmUoJ3RoZW1lJylcblxuICAgIHRoZW1lID09PSAnZGFyaydcbiAgICAgID8gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoJ2RhcmsnKVxuICAgICAgOiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZSgnZGFyaycpXG4gIH0pXG59KVxuIl0sCiAgIm1hcHBpbmdzIjogIjs7QUFBQSxNQUFNLGlCQUFpQixNQUFNO0FBQzNCLFdBQU87QUFBQSxNQUNMLE1BQU07QUFBQSxNQUNOLHFCQUFxQjtBQUFBLE1BQ3JCLGlCQUFpQjtBQUFBLE1BQ2pCLGtCQUFrQixDQUFDO0FBQUEsTUFDbkIsWUFBWTtBQUFBLE1BQ1osV0FBVyxDQUFDO0FBQUEsTUFDWixpQ0FBaUMsS0FBSztBQUNwQyxZQUFJLEtBQUssTUFBTSxJQUFJLFlBQVksRUFBRSxLQUFLLGVBQWUsTUFBTSxRQUFXO0FBQ3BFLGlCQUFPLEtBQUssTUFBTSxJQUFJLFlBQVksRUFBRSxL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TSxTQUFTLE9BQU8sTUFBTSxzQkFBc0IsR0FBRyxNQUFNLGtCQUFrQixLQUFLO0FBQUEsVUFDcEcsQ0FBQztBQUFBLFFBQ0g7QUFFQSxhQUFLLFVBQVU7QUFBQSxVQUNiLFNBQVMsR0FBRywrQkFBK0IsQ0FBQyxFQUFFLEdBQUcsTUFBTTtBQUNyRCxpQkFBSyx3QkFBd0IsRUFBRTtBQUFBLFVBQ2pDLENBQUM7QUFBQSxRQUNIO0FBQUEsTUFDRjtBQUFBLE1BQ0EsVUFBVTtBQUNSLGFBQUssVUFBVSxRQUFRLENBQUMsYUFBYTtBQUNuQyxtQkFBUztBQUFBLFFBQ1gsQ0FBQztBQUFBLE1BQ0g7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUVBLE1BQU8sZ0JBQVE7OztBQ3pHZixTQUFPLGlCQUFpQjtBQUV4QixXQUFTLGlCQUFpQixlQUFlLE1BQU07QUFDN0MsVUFBTSxRQUFRLGFBQWEsUUFBUSxPQUFPLEtBQUs7QUFFL0MsV0FBTyxPQUFPO0FBQUEsTUFDWjtBQUFBLE1BQ0EsVUFBVSxVQUNULFVBQVUsWUFDVCxPQUFPLFdBQVcsOEJBQThCLEVBQUUsVUFDaEQsU0FDQTtBQUFBLElBQ047QUFFQSxXQUFPLGlCQUFpQixpQkFBaUIsQ0FBQyxVQUFVO0FBQ2xELFVBQUlDLFNBQVEsTUFBTTtBQUVsQixtQkFBYSxRQUFRLFNBQVNBLE1BQUs7QUFFbkMsVUFBSUEsV0FBVSxVQUFVO0FBQ3RCLFFBQUFBLFNBQVEsT0FBTyxXQUFXLDhCQUE4QixFQUFFLFVBQ3RELFNBQ0E7QUFBQSxNQUNOO0FBRUEsYUFBTyxPQUFPLE1BQU0sU0FBU0EsTUFBSztBQUFBLElBQ3BDLENBQUM7QUFFRCxXQUNHLFdBQVcsOEJBQThCLEVBQ3pDLGlCQUFpQixVQUFVLENBQUMsVUFBVTtBQUNyQyxVQUFJLGFBQWEsUUFBUSxPQUFPLE1BQU0sVUFBVTtBQUM5QyxlQUFPLE9BQU8sTUFBTSxTQUFTLE1BQU0sVUFBVSxTQUFTLE9BQU87QUFBQSxNQUMvRDtBQUFBLElBQ0YsQ0FBQztBQUVILFdBQU8sT0FBTyxPQUFPLE1BQU07QUFDekIsWUFBTUEsU0FBUSxPQUFPLE9BQU8sTUFBTSxPQUFPO0FBRXpDLE1BQUFBLFdBQVUsU0FDTixTQUFTLGdCQUFnQixVQUFVLElBQUksTUFBTSxJQUM3QyxTQUFTLGdCQUFnQixVQUFVLE9BQU8sTUFBTTtBQUFBLElBQ3RELENBQUM7QUFBQSxFQUNILENBQUM7IiwKICAibmFtZXMiOiBbImlkIiwgInRoZW1lIl0KfQo=
