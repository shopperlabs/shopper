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

  // node_modules/treeselectjs/dist/treeselectjs.mjs
  var ri = Object.defineProperty;
  var ci = (l, e, t) => e in l ? ri(l, e, { enumerable: true, configurable: true, writable: true, value: t }) : l[e] = t;
  var c = (l, e, t) => (ci(l, typeof e != "symbol" ? e + "" : e, t), t);
  var kt = (l, e, t) => {
    if (!e.has(l))
      throw TypeError("Cannot " + t);
  };
  var n = (l, e, t) => (kt(l, e, "read from private field"), t ? t.call(l) : e.get(l));
  var r = (l, e, t) => {
    if (e.has(l))
      throw TypeError("Cannot add the same private member more than once");
    e instanceof WeakSet ? e.add(l) : e.set(l, t);
  };
  var m = (l, e, t, s) => (kt(l, e, "write to private field"), s ? s.call(l, t) : e.set(l, t), t);
  var o = (l, e, t) => (kt(l, e, "access private method"), t);
  var Pt = {
    arrowUp: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>',
    arrowDown: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>',
    arrowRight: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>',
    attention: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    clear: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
    cross: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
    check: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
    partialCheck: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 25 25" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>'
  };
  var I = (l, e) => {
    if (e.innerHTML = "", typeof l == "string")
      e.innerHTML = l;
    else {
      const t = l.cloneNode(true);
      e.appendChild(t);
    }
  };
  var Bt = (l) => {
    const e = l ? { ...l } : {};
    return Object.keys(Pt).forEach((t) => {
      e[t] || (e[t] = Pt[t]);
    }), e;
  };
  var hi = (l) => l.reduce((e, { name: t }, s) => (e += t, s < l.length - 1 && (e += ", "), e), "");
  var N;
  var E;
  var D;
  var v;
  var ue;
  var Ht;
  var H;
  var W;
  var pe;
  var Gt;
  var me;
  var Mt;
  var G;
  var U;
  var O;
  var V;
  var fe;
  var Ft;
  var be;
  var qt;
  var Ce;
  var jt;
  var ge;
  var Rt;
  var ke;
  var $t;
  var we;
  var Wt;
  var Ee;
  var Ut;
  var ve;
  var zt;
  var Le;
  var Yt;
  var ye;
  var Kt;
  var xe;
  var Xt;
  var Se;
  var Jt;
  var _e;
  var Zt;
  var Ae;
  var Qt;
  var Te;
  var es;
  var Ne;
  var ts;
  var z;
  var wt;
  var di = class {
    constructor({
      value: e,
      showTags: t,
      tagsCountText: s,
      clearable: i,
      isAlwaysOpened: a,
      searchable: h,
      placeholder: d,
      disabled: C,
      isSingleSelect: f,
      id: b,
      ariaLabel: g,
      iconElements: k,
      inputCallback: w,
      searchCallback: y,
      openCallback: x,
      closeCallback: $,
      keydownCallback: ae,
      focusCallback: Ct,
      blurCallback: gt,
      nameChangeCallback: oe
    }) {
      r(this, ue);
      r(this, H);
      r(this, pe);
      r(this, me);
      r(this, G);
      r(this, O);
      r(this, fe);
      r(this, be);
      r(this, Ce);
      r(this, ge);
      r(this, ke);
      r(this, we);
      r(this, Ee);
      r(this, ve);
      r(this, Le);
      r(this, ye);
      r(this, xe);
      r(this, Se);
      r(this, _e);
      r(this, Ae);
      r(this, Te);
      r(this, Ne);
      r(this, z);
      c(this, "value");
      c(this, "showTags");
      c(this, "tagsCountText");
      c(this, "clearable");
      c(this, "isAlwaysOpened");
      c(this, "searchable");
      c(this, "placeholder");
      c(this, "disabled");
      c(this, "isSingleSelect");
      c(this, "id");
      c(this, "ariaLabel");
      c(this, "iconElements");
      c(this, "isOpened");
      c(this, "searchText");
      c(this, "srcElement");
      r(this, N, void 0);
      r(this, E, void 0);
      r(this, D, void 0);
      r(this, v, void 0);
      c(this, "inputCallback");
      c(this, "searchCallback");
      c(this, "openCallback");
      c(this, "closeCallback");
      c(this, "keydownCallback");
      c(this, "focusCallback");
      c(this, "blurCallback");
      c(this, "nameChangeCallback");
      this.value = e, this.showTags = t, this.tagsCountText = s, this.searchable = h, this.placeholder = d, this.clearable = i, this.isAlwaysOpened = a, this.disabled = C, this.isSingleSelect = f, this.id = b, this.ariaLabel = g, this.iconElements = k, this.isOpened = false, this.searchText = "", m(this, N, o(this, Ce, jt).call(this)), m(this, E, o(this, Le, Yt).call(this)), m(this, D, o(this, Se, Jt).call(this)), m(this, v, null), this.inputCallback = w, this.searchCallback = y, this.openCallback = x, this.closeCallback = $, this.keydownCallback = ae, this.focusCallback = Ct, this.blurCallback = gt, this.nameChangeCallback = oe, this.srcElement = o(this, fe, Ft).call(this, n(this, N), n(this, E), n(this, D)), o(this, ue, Ht).call(this);
    }
    // Public methods
    focus() {
      setTimeout(() => n(this, E).focus(), 0);
    }
    blur() {
      this.isOpened && o(this, O, V).call(this), this.clearSearch(), n(this, E).blur();
    }
    updateValue(e) {
      this.value = e, o(this, H, W).call(this), o(this, G, U).call(this);
    }
    removeItem(e) {
      this.value = this.value.filter((t) => t.id !== e), o(this, z, wt).call(this), o(this, H, W).call(this), o(this, G, U).call(this);
    }
    clear() {
      this.value = [], o(this, z, wt).call(this), o(this, H, W).call(this), this.clearSearch();
    }
    openClose() {
      o(this, O, V).call(this);
    }
    clearSearch() {
      this.searchText = "", this.searchCallback(""), o(this, G, U).call(this);
    }
  };
  N = /* @__PURE__ */ new WeakMap(), E = /* @__PURE__ */ new WeakMap(), D = /* @__PURE__ */ new WeakMap(), v = /* @__PURE__ */ new WeakMap(), ue = /* @__PURE__ */ new WeakSet(), Ht = function() {
    o(this, H, W).call(this), o(this, G, U).call(this), o(this, pe, Gt).call(this);
  }, H = /* @__PURE__ */ new WeakSet(), W = function() {
    if (n(this, N).innerHTML = "", this.showTags) {
      n(this, N).append(...o(this, ge, Rt).call(this));
      const e = hi(this.value);
      this.nameChangeCallback(e);
    } else {
      const e = o(this, ve, zt).call(this);
      n(this, N).appendChild(e), this.nameChangeCallback(e.innerText);
    }
    n(this, N).appendChild(n(this, E));
  }, pe = /* @__PURE__ */ new WeakSet(), Gt = function() {
    const e = [];
    n(this, D).innerHTML = "", this.clearable && e.push(o(this, _e, Zt).call(this)), this.isAlwaysOpened || e.push(o(this, Te, es).call(this, this.isOpened)), e.length && n(this, D).append(...e);
  }, me = /* @__PURE__ */ new WeakSet(), Mt = function() {
    if (!this.isAlwaysOpened && n(this, v)) {
      const e = this.isOpened ? this.iconElements.arrowUp : this.iconElements.arrowDown;
      I(e, n(this, v));
    }
  }, G = /* @__PURE__ */ new WeakSet(), U = function() {
    var e;
    (e = this.value) != null && e.length ? (n(this, E).removeAttribute("placeholder"), this.srcElement.classList.remove("treeselect-input--value-not-selected")) : (n(this, E).setAttribute("placeholder", this.placeholder), this.srcElement.classList.add("treeselect-input--value-not-selected")), this.searchable ? this.srcElement.classList.remove("treeselect-input--unsearchable") : this.srcElement.classList.add("treeselect-input--unsearchable"), this.isSingleSelect ? this.srcElement.classList.add("treeselect-input--is-single-select") : this.srcElement.classList.remove("treeselect-input--is-single-select"), n(this, E).value = this.searchText;
  }, O = /* @__PURE__ */ new WeakSet(), V = function() {
    this.isOpened = !this.isOpened, o(this, me, Mt).call(this), this.isOpened ? this.openCallback() : this.closeCallback();
  }, fe = /* @__PURE__ */ new WeakSet(), Ft = function(e, t, s) {
    const i = document.createElement("div");
    return i.classList.add("treeselect-input"), i.setAttribute("tabindex", "-1"), i.addEventListener("mousedown", (a) => o(this, be, qt).call(this, a)), i.addEventListener("focus", () => this.focusCallback(), true), i.addEventListener("blur", () => this.blurCallback(), true), e.appendChild(t), i.append(e, s), i;
  }, be = /* @__PURE__ */ new WeakSet(), qt = function(e) {
    e.stopPropagation(), this.isOpened || o(this, O, V).call(this), this.focus();
  }, Ce = /* @__PURE__ */ new WeakSet(), jt = function() {
    const e = document.createElement("div");
    return e.classList.add("treeselect-input__tags"), e;
  }, ge = /* @__PURE__ */ new WeakSet(), Rt = function() {
    return this.value.map((e) => {
      const t = document.createElement("div");
      t.classList.add("treeselect-input__tags-element"), t.setAttribute("tabindex", "-1"), t.setAttribute("tag-id", e.id.toString()), t.setAttribute("title", e.name);
      const s = o(this, we, Wt).call(this, e.name), i = o(this, Ee, Ut).call(this);
      return t.addEventListener("mousedown", (a) => o(this, ke, $t).call(this, a, e.id)), t.append(s, i), t;
    });
  }, ke = /* @__PURE__ */ new WeakSet(), $t = function(e, t) {
    e.preventDefault(), e.stopPropagation(), this.removeItem(t), this.focus();
  }, we = /* @__PURE__ */ new WeakSet(), Wt = function(e) {
    const t = document.createElement("span");
    return t.classList.add("treeselect-input__tags-name"), t.textContent = e, t;
  }, Ee = /* @__PURE__ */ new WeakSet(), Ut = function() {
    const e = document.createElement("span");
    return e.classList.add("treeselect-input__tags-cross"), I(this.iconElements.cross, e), e;
  }, ve = /* @__PURE__ */ new WeakSet(), zt = function() {
    const e = document.createElement("span");
    if (e.classList.add("treeselect-input__tags-count"), !this.value.length)
      return e.textContent = "", e.setAttribute("title", ""), e;
    const t = this.value.length === 1 ? this.value[0].name : `${this.value.length} ${this.tagsCountText}`;
    return e.textContent = t, e.setAttribute("title", t), e;
  }, Le = /* @__PURE__ */ new WeakSet(), Yt = function() {
    const e = document.createElement("input");
    return e.classList.add("treeselect-input__edit"), this.id && e.setAttribute("id", this.id), (!this.searchable || this.disabled) && e.setAttribute("readonly", "readonly"), this.disabled && e.setAttribute("tabindex", "-1"), this.ariaLabel.length && e.setAttribute("aria-label", this.ariaLabel), e.addEventListener("keydown", (t) => o(this, ye, Kt).call(this, t)), e.addEventListener("input", (t) => o(this, xe, Xt).call(this, t, e)), e;
  }, ye = /* @__PURE__ */ new WeakSet(), Kt = function(e) {
    e.stopPropagation();
    const t = e.key;
    t === "Backspace" && !this.searchText.length && this.value.length && !this.showTags && this.clear(), t === "Backspace" && !this.searchText.length && this.value.length && this.removeItem(this.value[this.value.length - 1].id), e.code === "Space" && (!this.searchText || !this.searchable) && o(this, O, V).call(this), (t === "Enter" || t === "ArrowDown" || t === "ArrowUp") && e.preventDefault(), this.keydownCallback(e), t !== "Tab" && this.focus();
  }, xe = /* @__PURE__ */ new WeakSet(), Xt = function(e, t) {
    e.stopPropagation();
    const s = this.searchText, i = t.value.trim();
    if (s.length === 0 && i.length === 0) {
      t.value = "";
      return;
    }
    if (this.searchable) {
      const a = e.target.value;
      this.searchCallback(a), this.isOpened || o(this, O, V).call(this);
    } else
      t.value = "";
    this.searchText = t.value;
  }, Se = /* @__PURE__ */ new WeakSet(), Jt = function() {
    const e = document.createElement("div");
    return e.classList.add("treeselect-input__operators"), e;
  }, _e = /* @__PURE__ */ new WeakSet(), Zt = function() {
    const e = document.createElement("span");
    return e.classList.add("treeselect-input__clear"), e.setAttribute("tabindex", "-1"), I(this.iconElements.clear, e), e.addEventListener("mousedown", (t) => o(this, Ae, Qt).call(this, t)), e;
  }, Ae = /* @__PURE__ */ new WeakSet(), Qt = function(e) {
    e.preventDefault(), e.stopPropagation(), (this.searchText.length || this.value.length) && this.clear(), this.focus();
  }, Te = /* @__PURE__ */ new WeakSet(), es = function(e) {
    m(this, v, document.createElement("span")), n(this, v).classList.add("treeselect-input__arrow");
    const t = e ? this.iconElements.arrowUp : this.iconElements.arrowDown;
    return I(t, n(this, v)), n(this, v).addEventListener("mousedown", (s) => o(this, Ne, ts).call(this, s)), n(this, v);
  }, Ne = /* @__PURE__ */ new WeakSet(), ts = function(e) {
    e.stopPropagation(), e.preventDefault(), this.focus(), o(this, O, V).call(this);
  }, z = /* @__PURE__ */ new WeakSet(), wt = function() {
    this.inputCallback(this.value);
  };
  var ss = (l, e, t, s) => {
    fi(e);
    const i = e.filter((a) => !a.disabled && l.some((h) => h === a.id));
    if (t && i.length) {
      i[0].checked = true;
      return;
    }
    i.forEach((a) => {
      a.checked = true;
      const h = It(a, e, s);
      a.checked = h;
    });
  };
  var It = ({ id: l, checked: e }, t, s) => {
    const i = t.find((h) => h.id === l);
    if (!i)
      return false;
    if (s)
      return i.checked = i.disabled ? false : !!e, i.checked;
    const a = is(!!e, i, t);
    return ls(i, t), a;
  };
  var is = (l, e, t) => {
    if (!e.isGroup)
      return e.checked = e.disabled ? false : !!l, e.isPartialChecked = false, e.checked;
    const s = t.filter((d) => d.childOf === e.id);
    return !l || e.disabled || e.isPartialChecked ? (e.checked = false, e.isPartialChecked = false, Et(e, s, t), e.checked) : ns(s, t) ? as(s) ? (e.checked = false, e.isPartialChecked = false, e.disabled = true, e.checked) : (e.checked = false, e.isPartialChecked = true, s.forEach((d) => {
      is(l, d, t);
    }), e.checked) : (e.checked = true, e.isPartialChecked = false, Et(e, s, t), e.checked);
  };
  var ls = (l, e) => {
    const t = e.find((s) => s.id === l.childOf);
    t && (ui(t, e), ls(t, e));
  };
  var ui = (l, e) => {
    const t = ft(l, e);
    if (as(t)) {
      l.checked = false, l.isPartialChecked = false, l.disabled = true;
      return;
    }
    if (pi(t)) {
      l.checked = true, l.isPartialChecked = false;
      return;
    }
    if (mi(t)) {
      l.checked = false, l.isPartialChecked = true;
      return;
    }
    l.checked = false, l.isPartialChecked = false;
  };
  var Et = ({ checked: l, disabled: e }, t, s) => {
    t.forEach((i) => {
      i.disabled = !!e || !!i.disabled, i.checked = !!l && !i.disabled, i.isPartialChecked = false;
      const a = ft(i, s);
      Et({ checked: l, disabled: e }, a, s);
    });
  };
  var ns = (l, e) => l.some((i) => i.disabled) ? true : l.some((i) => {
    if (i.isGroup) {
      const a = ft(i, e);
      return ns(a, e);
    }
    return false;
  });
  var as = (l) => l.every((e) => !!e.disabled);
  var pi = (l) => l.every((e) => !!e.checked);
  var mi = (l) => l.some((e) => !!e.checked || !!e.isPartialChecked);
  var fi = (l) => {
    l.forEach((e) => {
      e.checked = false, e.isPartialChecked = false;
    });
  };
  var bi = (l, e, t) => {
    const s = { level: 0, groupId: "" }, i = os(l, e, s.groupId, s.level);
    return gi(i, t);
  };
  var os = (l, e, t, s) => l.reduce((i, a) => {
    var f;
    const h = !!((f = a.children) != null && f.length), d = s >= e && h, C = s > e;
    if (i.push({
      id: a.value,
      name: a.name,
      childOf: t,
      isGroup: h,
      checked: false,
      isPartialChecked: false,
      level: s,
      isClosed: d,
      hidden: C,
      disabled: a.disabled ?? false
    }), h) {
      const b = os(a.children, e, a.value, s + 1);
      i.push(...b);
    }
    return i;
  }, []);
  var ft = ({ id: l }, e) => e.filter((t) => t.childOf === l);
  var Ci = (l) => {
    const { ungroupedNodes: e, allGroupedNodes: t, allNodes: s } = l.reduce(
      (a, h) => (h.checked && (a.allNodes.push(h), h.isGroup ? a.allGroupedNodes.push(h) : a.ungroupedNodes.push(h)), a),
      {
        ungroupedNodes: [],
        allGroupedNodes: [],
        allNodes: []
      }
    ), i = s.filter((a) => !t.some(({ id: h }) => h === a.childOf));
    return { ungroupedNodes: e, groupedNodes: i, allNodes: s };
  };
  var gi = (l, e) => (l.filter((s) => !!s.disabled).forEach(
    ({ id: s }) => It({ id: s, checked: false }, l, e)
  ), l);
  var bt = (l, { id: e, isClosed: t }) => {
    ft({ id: e }, l).forEach((i) => {
      i.hidden = t ?? false, i.isGroup && !i.isClosed && bt(l, { id: i.id, isClosed: t });
    });
  };
  var ki = (l) => {
    l.filter((e) => e.isGroup && !e.disabled && (e.checked || e.isPartialChecked)).forEach((e) => {
      e.isClosed = false, bt(l, e);
    });
  };
  var wi = (l, e) => {
    const t = Ei(l, e);
    l.forEach((s) => {
      t.some(({ id: a }) => a === s.id) ? (s.isGroup && (s.isClosed = false, bt(l, s)), s.hidden = false) : s.hidden = true;
    });
  };
  var Ei = (l, e) => l.reduce((t, s) => {
    if (s.name.toLowerCase().includes(e.toLowerCase())) {
      if (t.push(s), s.isGroup) {
        const a = rs(s.id, l);
        t.push(...a);
      }
      if (s.childOf) {
        const a = cs(s.childOf, l);
        t.push(...a);
      }
    }
    return t;
  }, []);
  var rs = (l, e) => e.reduce((t, s) => (s.childOf === l && (t.push(s), s.isGroup && t.push(...rs(s.id, e))), t), []);
  var cs = (l, e) => e.reduce((t, s) => (s.id === l && (t.push(s), s.childOf && t.push(...cs(s.childOf, e))), t), []);
  var vi = (l) => {
    const { duplications: e } = l.reduce(
      (t, s) => (t.allItems.some((i) => i.toString() === s.id.toString()) && t.duplications.push(s.id), t.allItems.push(s.id), t),
      {
        duplications: [],
        allItems: []
      }
    );
    e.length && console.error(`Validation: You have duplicated values: ${e.join(", ")}! You should use unique values.`);
  };
  var Li = (l, e, t, s, i, a, h, d, C, f) => {
    ss(l, e, i, C), d && h && ki(e), ce(e, t, s, a, f);
  };
  var ce = (l, e, t, s, i) => {
    l.forEach((a) => {
      const h = e.querySelector(`[input-id="${a.id}"]`), d = T(h);
      h.checked = a.checked, yi(a, d, s), xi(a, d), Si(a, d), _i(a, d, t), Ai(a, d), Ni(a, d, l, i), Ti(a, h, t);
    }), Oi(l, e);
  };
  var yi = (l, e, t) => {
    l.checked ? e.classList.add("treeselect-list__item--checked") : e.classList.remove("treeselect-list__item--checked"), Array.isArray(t) && t[0] === l.id && !l.disabled ? e.classList.add("treeselect-list__item--single-selected") : e.classList.remove("treeselect-list__item--single-selected");
  };
  var xi = (l, e) => {
    l.isPartialChecked ? e.classList.add("treeselect-list__item--partial-checked") : e.classList.remove("treeselect-list__item--partial-checked");
  };
  var Si = (l, e) => {
    l.disabled ? e.classList.add("treeselect-list__item--disabled") : e.classList.remove("treeselect-list__item--disabled");
  };
  var _i = (l, e, t) => {
    if (l.isGroup) {
      const s = e.querySelector(".treeselect-list__item-icon"), i = l.isClosed ? t.arrowRight : t.arrowDown;
      I(i, s), l.isClosed ? e.classList.add("treeselect-list__item--closed") : e.classList.remove("treeselect-list__item--closed");
    }
  };
  var Ai = (l, e) => {
    l.hidden ? e.classList.add("treeselect-list__item--hidden") : e.classList.remove("treeselect-list__item--hidden");
  };
  var Ti = (l, e, t) => {
    const i = e.parentNode.querySelector(".treeselect-list__item-checkbox-icon");
    l.checked ? I(t.check, i) : l.isPartialChecked ? I(t.partialCheck, i) : i.innerHTML = "";
  };
  var Ni = (l, e, t, s) => {
    const i = l.level === 0, a = 20, h = 5;
    if (i) {
      const d = t.some((b) => b.isGroup && b.level === l.level), C = !l.isGroup && d ? `${a}px` : `${h}px`, f = l.isGroup ? "0" : C;
      s ? e.style.paddingRight = f : e.style.paddingLeft = f;
    } else {
      const d = l.isGroup ? `${l.level * a}px` : `${l.level * a + a}px`;
      s ? e.style.paddingRight = d : e.style.paddingLeft = d;
    }
    e.setAttribute("level", l.level.toString()), e.setAttribute("group", l.isGroup.toString());
  };
  var Oi = (l, e) => {
    const t = l.some((i) => !i.hidden), s = e.querySelector(".treeselect-list__empty");
    t ? s.classList.add("treeselect-list__empty--hidden") : s.classList.remove("treeselect-list__empty--hidden");
  };
  var T = (l) => l.parentNode.parentNode;
  var Vt = (l, e) => e.find((t) => t.id.toString() === l);
  var Ii = (l) => T(l).querySelector(".treeselect-list__item-icon");
  var Pi = (l, e) => {
    e && Object.keys(e).forEach((t) => {
      const s = e[t];
      typeof s == "string" && l.setAttribute(t, s);
    });
  };
  var M;
  var P;
  var S;
  var Y;
  var Oe;
  var hs;
  var Ie;
  var ds;
  var Pe;
  var us;
  var Be;
  var ps;
  var Ve;
  var ms;
  var De;
  var fs;
  var K;
  var vt;
  var He;
  var bs;
  var Ge;
  var Cs;
  var Me;
  var gs;
  var X;
  var Lt;
  var Fe;
  var ks;
  var qe;
  var ws;
  var je;
  var Es;
  var Re;
  var vs;
  var $e;
  var Ls;
  var We;
  var ys;
  var Ue;
  var xs;
  var ze;
  var Ss;
  var Ye;
  var _s;
  var Ke;
  var As;
  var Xe;
  var Ts;
  var J;
  var yt;
  var Z;
  var xt;
  var Je;
  var Ns;
  var Bi = class {
    constructor({
      options: e,
      value: t,
      openLevel: s,
      listSlotHtmlComponent: i,
      emptyText: a,
      isSingleSelect: h,
      iconElements: d,
      showCount: C,
      disabledBranchNode: f,
      expandSelected: b,
      isIndependentNodes: g,
      rtl: k,
      inputCallback: w,
      arrowClickCallback: y,
      mouseupCallback: x
    }) {
      r(this, Oe);
      r(this, Ie);
      r(this, Pe);
      r(this, Be);
      r(this, Ve);
      r(this, De);
      r(this, K);
      r(this, He);
      r(this, Ge);
      r(this, Me);
      r(this, X);
      r(this, Fe);
      r(this, qe);
      r(this, je);
      r(this, Re);
      r(this, $e);
      r(this, We);
      r(this, Ue);
      r(this, ze);
      r(this, Ye);
      r(this, Ke);
      r(this, Xe);
      r(this, J);
      r(this, Z);
      r(this, Je);
      c(this, "options");
      c(this, "value");
      c(this, "openLevel");
      c(this, "listSlotHtmlComponent");
      c(this, "emptyText");
      c(this, "isSingleSelect");
      c(this, "showCount");
      c(this, "disabledBranchNode");
      c(this, "expandSelected");
      c(this, "isIndependentNodes");
      c(this, "rtl");
      c(this, "iconElements");
      c(this, "searchText");
      c(this, "flattedOptions");
      c(this, "flattedOptionsBeforeSearch");
      c(this, "selectedNodes");
      c(this, "srcElement");
      c(this, "inputCallback");
      c(this, "arrowClickCallback");
      c(this, "mouseupCallback");
      r(this, M, null);
      r(this, P, true);
      r(this, S, []);
      r(this, Y, true);
      this.options = e, this.value = t, this.openLevel = s ?? 0, this.listSlotHtmlComponent = i ?? null, this.emptyText = a ?? "No results found...", this.isSingleSelect = h ?? false, this.showCount = C ?? false, this.disabledBranchNode = f ?? false, this.expandSelected = b ?? false, this.isIndependentNodes = g ?? false, this.rtl = k ?? false, this.iconElements = d, this.searchText = "", this.flattedOptions = bi(this.options, this.openLevel, this.isIndependentNodes), this.flattedOptionsBeforeSearch = this.flattedOptions, this.selectedNodes = { nodes: [], groupedNodes: [], allNodes: [] }, this.srcElement = o(this, Pe, us).call(this), this.inputCallback = w, this.arrowClickCallback = y, this.mouseupCallback = x, vi(this.flattedOptions);
    }
    // Public methods
    updateValue(e) {
      this.value = e, m(this, S, this.isSingleSelect ? this.value : []), Li(
        e,
        this.flattedOptions,
        this.srcElement,
        this.iconElements,
        this.isSingleSelect,
        n(this, S),
        this.expandSelected,
        n(this, Y),
        this.isIndependentNodes,
        this.rtl
      ), m(this, Y, false), o(this, Z, xt).call(this);
    }
    updateSearchValue(e) {
      if (e === this.searchText)
        return;
      const t = this.searchText === "" && e !== "";
      this.searchText = e, t && (this.flattedOptionsBeforeSearch = JSON.parse(JSON.stringify(this.flattedOptions))), this.searchText === "" && (this.flattedOptions = this.flattedOptionsBeforeSearch.map((s) => {
        const i = this.flattedOptions.find((a) => a.id === s.id);
        return i.isClosed = s.isClosed, i.hidden = s.hidden, i;
      }), this.flattedOptionsBeforeSearch = []), this.searchText && wi(this.flattedOptions, e), ce(this.flattedOptions, this.srcElement, this.iconElements, n(this, S), this.rtl), this.focusFirstListElement();
    }
    callKeyAction(e) {
      m(this, P, false);
      const t = this.srcElement.querySelector(".treeselect-list__item--focused");
      if (t == null ? void 0 : t.classList.contains("treeselect-list__item--hidden"))
        return;
      const i = e.key;
      i === "Enter" && t && t.dispatchEvent(new Event("mousedown")), (i === "ArrowLeft" || i === "ArrowRight") && o(this, Oe, hs).call(this, t, e), (i === "ArrowDown" || i === "ArrowUp") && o(this, Ie, ds).call(this, t, i);
    }
    focusFirstListElement() {
      const e = "treeselect-list__item--focused", t = this.srcElement.querySelector(`.${e}`), s = Array.from(this.srcElement.querySelectorAll(".treeselect-list__item-checkbox")).filter(
        (a) => window.getComputedStyle(T(a)).display !== "none"
      );
      if (!s.length)
        return;
      t && t.classList.remove(e), T(s[0]).classList.add(e);
    }
    isLastFocusedElementExist() {
      return !!n(this, M);
    }
  };
  M = /* @__PURE__ */ new WeakMap(), P = /* @__PURE__ */ new WeakMap(), S = /* @__PURE__ */ new WeakMap(), Y = /* @__PURE__ */ new WeakMap(), Oe = /* @__PURE__ */ new WeakSet(), hs = function(e, t) {
    if (!e)
      return;
    const s = t.key, a = e.querySelector(".treeselect-list__item-checkbox").getAttribute("input-id"), h = Vt(a, this.flattedOptions), d = e.querySelector(".treeselect-list__item-icon");
    s === "ArrowLeft" && !h.isClosed && h.isGroup && (d.dispatchEvent(new Event("mousedown")), t.preventDefault()), s === "ArrowRight" && h.isClosed && h.isGroup && (d.dispatchEvent(new Event("mousedown")), t.preventDefault());
  }, Ie = /* @__PURE__ */ new WeakSet(), ds = function(e, t) {
    var i;
    const s = Array.from(this.srcElement.querySelectorAll(".treeselect-list__item-checkbox")).filter(
      (a) => window.getComputedStyle(T(a)).display !== "none"
    );
    if (s.length)
      if (!e)
        T(s[0]).classList.add("treeselect-list__item--focused");
      else {
        const a = s.findIndex(
          (x) => T(x).classList.contains("treeselect-list__item--focused")
        );
        T(s[a]).classList.remove("treeselect-list__item--focused");
        const d = t === "ArrowDown" ? a + 1 : a - 1, C = t === "ArrowDown" ? 0 : s.length - 1, f = s[d] ?? s[C], b = !s[d], g = T(f);
        g.classList.add("treeselect-list__item--focused");
        const k = this.srcElement.getBoundingClientRect(), w = g.getBoundingClientRect();
        if (b && t === "ArrowDown") {
          this.srcElement.scroll(0, 0);
          return;
        }
        if (b && t === "ArrowUp") {
          this.srcElement.scroll(0, this.srcElement.scrollHeight);
          return;
        }
        const y = ((i = this.listSlotHtmlComponent) == null ? void 0 : i.clientHeight) ?? 0;
        if (k.y + k.height < w.y + w.height + y) {
          this.srcElement.scroll(0, this.srcElement.scrollTop + w.height);
          return;
        }
        if (k.y > w.y) {
          this.srcElement.scroll(0, this.srcElement.scrollTop - w.height);
          return;
        }
      }
  }, Pe = /* @__PURE__ */ new WeakSet(), us = function() {
    const e = o(this, Be, ps).call(this), t = o(this, K, vt).call(this, this.options);
    e.append(...t);
    const s = o(this, Ge, Cs).call(this);
    e.append(s);
    const i = o(this, He, bs).call(this);
    return i && e.append(i), e;
  }, Be = /* @__PURE__ */ new WeakSet(), ps = function() {
    const e = document.createElement("div");
    return e.classList.add("treeselect-list"), this.isSingleSelect && e.classList.add("treeselect-list--single-select"), this.disabledBranchNode && e.classList.add("treeselect-list--disabled-branch-node"), e.addEventListener("mouseout", (t) => o(this, Ve, ms).call(this, t)), e.addEventListener("mousemove", () => o(this, De, fs).call(this)), e.addEventListener("mouseup", () => this.mouseupCallback(), true), e;
  }, Ve = /* @__PURE__ */ new WeakSet(), ms = function(e) {
    e.stopPropagation(), n(this, M) && n(this, P) && n(this, M).classList.add("treeselect-list__item--focused");
  }, De = /* @__PURE__ */ new WeakSet(), fs = function() {
    m(this, P, true);
  }, K = /* @__PURE__ */ new WeakSet(), vt = function(e) {
    return e.reduce((t, s) => {
      var a;
      if ((a = s.children) != null && a.length) {
        const h = o(this, Me, gs).call(this, s), d = o(this, K, vt).call(this, s.children);
        return h.append(...d), t.push(h), t;
      }
      const i = o(this, X, Lt).call(this, s, false);
      return t.push(i), t;
    }, []);
  }, He = /* @__PURE__ */ new WeakSet(), bs = function() {
    if (!this.listSlotHtmlComponent)
      return null;
    const e = document.createElement("div");
    return e.classList.add("treeselect-list__slot"), e.appendChild(this.listSlotHtmlComponent), e;
  }, Ge = /* @__PURE__ */ new WeakSet(), Cs = function() {
    const e = document.createElement("div");
    e.classList.add("treeselect-list__empty"), e.setAttribute("title", this.emptyText);
    const t = document.createElement("span");
    t.classList.add("treeselect-list__empty-icon"), I(this.iconElements.attention, t);
    const s = document.createElement("span");
    return s.classList.add("treeselect-list__empty-text"), s.textContent = this.emptyText, e.append(t, s), e;
  }, Me = /* @__PURE__ */ new WeakSet(), gs = function(e) {
    const t = document.createElement("div");
    t.setAttribute("group-container-id", e.value.toString()), t.classList.add("treeselect-list__group-container");
    const s = o(this, X, Lt).call(this, e, true);
    return t.appendChild(s), t;
  }, X = /* @__PURE__ */ new WeakSet(), Lt = function(e, t) {
    const s = o(this, Fe, ks).call(this, e);
    if (t) {
      const h = o(this, $e, Ls).call(this);
      s.appendChild(h), s.classList.add("treeselect-list__item--group");
    }
    const i = o(this, Ue, xs).call(this, e), a = o(this, ze, Ss).call(this, e, t);
    return s.append(i, a), s;
  }, Fe = /* @__PURE__ */ new WeakSet(), ks = function(e) {
    const t = document.createElement("div");
    return Pi(t, e.htmlAttr), t.setAttribute("tabindex", "-1"), t.setAttribute("title", e.name), t.classList.add("treeselect-list__item"), t.addEventListener("mouseover", () => o(this, qe, ws).call(this, t), true), t.addEventListener("mouseout", () => o(this, je, Es).call(this, t), true), t.addEventListener("mousedown", (s) => o(this, Re, vs).call(this, s, e)), t;
  }, qe = /* @__PURE__ */ new WeakSet(), ws = function(e) {
    n(this, P) && o(this, J, yt).call(this, true, e);
  }, je = /* @__PURE__ */ new WeakSet(), Es = function(e) {
    n(this, P) && (o(this, J, yt).call(this, false, e), m(this, M, e));
  }, Re = /* @__PURE__ */ new WeakSet(), vs = function(e, t) {
    var a;
    if (e.preventDefault(), e.stopPropagation(), (a = this.flattedOptions.find((h) => h.id === t.value)) == null ? void 0 : a.disabled)
      return;
    const i = e.target.querySelector(".treeselect-list__item-checkbox");
    i.checked = !i.checked, o(this, Ke, As).call(this, i, t);
  }, $e = /* @__PURE__ */ new WeakSet(), Ls = function() {
    const e = document.createElement("span");
    return e.setAttribute("tabindex", "-1"), e.classList.add("treeselect-list__item-icon"), I(this.iconElements.arrowDown, e), e.addEventListener("mousedown", (t) => o(this, We, ys).call(this, t)), e;
  }, We = /* @__PURE__ */ new WeakSet(), ys = function(e) {
    e.preventDefault(), e.stopPropagation(), o(this, Xe, Ts).call(this, e);
  }, Ue = /* @__PURE__ */ new WeakSet(), xs = function(e) {
    const t = document.createElement("div");
    t.classList.add("treeselect-list__item-checkbox-container");
    const s = document.createElement("span");
    s.classList.add("treeselect-list__item-checkbox-icon"), s.innerHTML = "";
    const i = document.createElement("input");
    return i.setAttribute("tabindex", "-1"), i.setAttribute("type", "checkbox"), i.setAttribute("input-id", e.value.toString()), i.classList.add("treeselect-list__item-checkbox"), t.append(s, i), t;
  }, ze = /* @__PURE__ */ new WeakSet(), Ss = function(e, t) {
    const s = document.createElement("label");
    if (s.textContent = e.name, s.classList.add("treeselect-list__item-label"), t && this.showCount) {
      const i = o(this, Ye, _s).call(this, e);
      s.appendChild(i);
    }
    return s;
  }, Ye = /* @__PURE__ */ new WeakSet(), _s = function(e) {
    const t = document.createElement("span"), s = this.flattedOptions.filter((i) => i.childOf === e.value);
    return t.textContent = `(${s.length})`, t.classList.add("treeselect-list__item-label-counter"), t;
  }, Ke = /* @__PURE__ */ new WeakSet(), As = function(e, t) {
    const s = this.flattedOptions.find((i) => i.id === t.value);
    if (s) {
      if (s != null && s.isGroup && this.disabledBranchNode) {
        const i = Ii(e);
        i == null || i.dispatchEvent(new Event("mousedown"));
        return;
      }
      if (this.isSingleSelect) {
        const [i] = n(this, S);
        if (s.id === i)
          return;
        m(this, S, [s.id]), ss([s.id], this.flattedOptions, this.isSingleSelect, this.isIndependentNodes);
      } else {
        s.checked = e.checked;
        const i = It(s, this.flattedOptions, this.isIndependentNodes);
        e.checked = i;
      }
      ce(this.flattedOptions, this.srcElement, this.iconElements, n(this, S), this.rtl), o(this, Je, Ns).call(this);
    }
  }, Xe = /* @__PURE__ */ new WeakSet(), Ts = function(e) {
    var a, h;
    const t = (h = (a = e.target) == null ? void 0 : a.parentNode) == null ? void 0 : h.querySelector("[input-id]"), s = (t == null ? void 0 : t.getAttribute("input-id")) ?? null, i = Vt(s, this.flattedOptions);
    i && (i.isClosed = !i.isClosed, bt(this.flattedOptions, i), ce(this.flattedOptions, this.srcElement, this.iconElements, n(this, S), this.rtl), this.arrowClickCallback(i.id, i.isClosed));
  }, J = /* @__PURE__ */ new WeakSet(), yt = function(e, t) {
    const s = "treeselect-list__item--focused";
    if (e) {
      const i = Array.from(this.srcElement.querySelectorAll(`.${s}`));
      i.length && i.forEach((a) => a.classList.remove(s)), t.classList.add(s);
    } else
      t.classList.remove(s);
  }, Z = /* @__PURE__ */ new WeakSet(), xt = function() {
    const { ungroupedNodes: e, groupedNodes: t, allNodes: s } = Ci(this.flattedOptions);
    this.selectedNodes = { nodes: e, groupedNodes: t, allNodes: s };
  }, Je = /* @__PURE__ */ new WeakSet(), Ns = function() {
    o(this, Z, xt).call(this), this.inputCallback(this.selectedNodes), this.value = this.selectedNodes.nodes.map((e) => e.id);
  };
  var Dt = ({
    parentHtmlContainer: l,
    staticList: e,
    appendToBody: t,
    isSingleSelect: s,
    value: i,
    direction: a
  }) => {
    l || console.error("Validation: parentHtmlContainer prop is required!"), e && t && console.error("Validation: You should set staticList to false if you use appendToBody!"), s && Array.isArray(i) && console.error("Validation: if you use isSingleSelect prop, you should pass a single value!"), !s && !Array.isArray(i) && console.error("Validation: you should pass an array as a value!"), a && a !== "auto" && a !== "bottom" && a !== "top" && console.error("Validation: you should pass (auto | top | bottom | undefined) as a value for the direction prop!");
  };
  var re = (l) => l.map((e) => e.id);
  var Vi = (l) => l ? Array.isArray(l) ? l : [l] : [];
  var Di = (l, e) => {
    if (e) {
      const [t] = l;
      return t ?? null;
    }
    return l;
  };
  var u;
  var p;
  var F;
  var Q;
  var q;
  var _;
  var A;
  var L;
  var B;
  var ee;
  var St;
  var te;
  var _t;
  var Ze;
  var Os;
  var Qe;
  var Is;
  var et;
  var Ps;
  var tt;
  var Bs;
  var st;
  var Vs;
  var it;
  var Ds;
  var se;
  var At;
  var lt;
  var Hs;
  var nt;
  var Gs;
  var at;
  var Ms;
  var ot;
  var Fs;
  var ie;
  var Tt;
  var rt;
  var qs;
  var j;
  var he;
  var le;
  var Nt;
  var R;
  var de;
  var ct;
  var js;
  var ne;
  var Ot;
  var ht;
  var Rs;
  var dt;
  var $s;
  var ut;
  var Ws;
  var pt;
  var Us;
  var mt;
  var zs;
  var Gi = class {
    constructor({
      parentHtmlContainer: e,
      value: t,
      options: s,
      openLevel: i,
      appendToBody: a,
      alwaysOpen: h,
      showTags: d,
      tagsCountText: C,
      clearable: f,
      searchable: b,
      placeholder: g,
      grouped: k,
      isGroupedValue: w,
      listSlotHtmlComponent: y,
      disabled: x,
      emptyText: $,
      staticList: ae,
      id: Ct,
      ariaLabel: gt,
      isSingleSelect: oe,
      showCount: Ys,
      disabledBranchNode: Ks,
      direction: Xs,
      expandSelected: Js,
      saveScrollPosition: Zs,
      isIndependentNodes: Qs,
      rtl: ei,
      iconElements: ti,
      inputCallback: si,
      openCallback: ii,
      closeCallback: li,
      nameChangeCallback: ni,
      searchCallback: ai,
      openCloseGroupCallback: oi
    }) {
      r(this, ee);
      r(this, te);
      r(this, Ze);
      r(this, Qe);
      r(this, et);
      r(this, tt);
      r(this, st);
      r(this, it);
      r(this, se);
      r(this, lt);
      r(this, nt);
      r(this, at);
      r(this, ot);
      r(this, ie);
      r(this, rt);
      r(this, j);
      r(this, le);
      r(this, R);
      r(this, ct);
      r(this, ne);
      r(this, ht);
      r(this, dt);
      r(this, ut);
      r(this, pt);
      r(this, mt);
      c(this, "parentHtmlContainer");
      c(this, "value");
      c(this, "options");
      c(this, "openLevel");
      c(this, "appendToBody");
      c(this, "alwaysOpen");
      c(this, "showTags");
      c(this, "tagsCountText");
      c(this, "clearable");
      c(this, "searchable");
      c(this, "placeholder");
      c(this, "grouped");
      c(this, "isGroupedValue");
      c(this, "listSlotHtmlComponent");
      c(this, "disabled");
      c(this, "emptyText");
      c(this, "staticList");
      c(this, "id");
      c(this, "ariaLabel");
      c(this, "isSingleSelect");
      c(this, "showCount");
      c(this, "disabledBranchNode");
      c(this, "direction");
      c(this, "expandSelected");
      c(this, "saveScrollPosition");
      c(this, "isIndependentNodes");
      c(this, "rtl");
      c(this, "iconElements");
      c(this, "inputCallback");
      c(this, "openCallback");
      c(this, "closeCallback");
      c(this, "nameChangeCallback");
      c(this, "searchCallback");
      c(this, "openCloseGroupCallback");
      c(this, "ungroupedValue");
      c(this, "groupedValue");
      c(this, "allValue");
      c(this, "isListOpened");
      c(this, "selectedName");
      c(this, "srcElement");
      r(this, u, null);
      r(this, p, null);
      r(this, F, null);
      r(this, Q, 0);
      r(this, q, 0);
      r(this, _, null);
      r(this, A, null);
      r(this, L, null);
      r(this, B, null);
      Dt({
        parentHtmlContainer: e,
        value: t,
        staticList: ae,
        appendToBody: a,
        isSingleSelect: oe
      }), this.parentHtmlContainer = e, this.value = [], this.options = s ?? [], this.openLevel = i ?? 0, this.appendToBody = a ?? false, this.alwaysOpen = !!(h && !x), this.showTags = d ?? true, this.tagsCountText = C ?? "elements selected", this.clearable = f ?? true, this.searchable = b ?? true, this.placeholder = g ?? "Search...", this.grouped = k ?? true, this.isGroupedValue = w ?? false, this.listSlotHtmlComponent = y ?? null, this.disabled = x ?? false, this.emptyText = $ ?? "No results found...", this.staticList = !!(ae && !this.appendToBody), this.id = Ct ?? "", this.ariaLabel = gt ?? "", this.isSingleSelect = oe ?? false, this.showCount = Ys ?? false, this.disabledBranchNode = Ks ?? false, this.direction = Xs ?? "auto", this.expandSelected = Js ?? false, this.saveScrollPosition = Zs ?? true, this.isIndependentNodes = Qs ?? false, this.rtl = ei ?? false, this.iconElements = Bt(ti), this.inputCallback = si, this.openCallback = ii, this.closeCallback = li, this.nameChangeCallback = ni, this.searchCallback = ai, this.openCloseGroupCallback = oi, this.ungroupedValue = [], this.groupedValue = [], this.allValue = [], this.isListOpened = false, this.selectedName = "", this.srcElement = null, o(this, ee, St).call(this, t);
    }
    mount() {
      Dt({
        parentHtmlContainer: this.parentHtmlContainer,
        value: this.value,
        staticList: this.staticList,
        appendToBody: this.appendToBody,
        isSingleSelect: this.isSingleSelect
      }), this.iconElements = Bt(this.iconElements), o(this, ee, St).call(this, this.value);
    }
    updateValue(e) {
      const t = Vi(e), s = n(this, u);
      s && (s.updateValue(t), o(this, se, At).call(this, s == null ? void 0 : s.selectedNodes));
    }
    destroy() {
      this.srcElement && (o(this, ie, Tt).call(this), this.srcElement.innerHTML = "", this.srcElement = null, o(this, R, de).call(this, true));
    }
    focus() {
      n(this, p) && n(this, p).focus();
    }
    toggleOpenClose() {
      n(this, p) && (n(this, p).openClose(), n(this, p).focus());
    }
    // Outside Listeners
    scrollWindowHandler() {
      this.updateListPosition();
    }
    focusWindowHandler(e) {
      var s, i, a;
      ((s = this.srcElement) == null ? void 0 : s.contains(e.target)) || ((i = n(this, u)) == null ? void 0 : i.srcElement.contains(e.target)) || ((a = n(this, p)) == null || a.blur(), o(this, R, de).call(this, false), o(this, j, he).call(this, false));
    }
    blurWindowHandler() {
      var e;
      (e = n(this, p)) == null || e.blur(), o(this, R, de).call(this, false), o(this, j, he).call(this, false);
    }
    // Update direction of the list. Support appendToBody and standard mode with absolute
    updateListPosition() {
      var y;
      const e = this.srcElement, t = (y = n(this, u)) == null ? void 0 : y.srcElement;
      if (!e || !t)
        return;
      const { height: s } = t.getBoundingClientRect(), {
        x: i,
        y: a,
        height: h,
        width: d
      } = e.getBoundingClientRect(), C = window.innerHeight, f = a, b = C - a - h;
      let g = f > b && f >= s && b < s;
      if (this.direction !== "auto" && (g = this.direction === "top"), this.appendToBody) {
        (t.style.top !== "0px" || t.style.left !== "0px") && (t.style.top = "0px", t.style.left = "0px");
        const x = i + window.scrollX, $ = g ? a + window.scrollY - s : a + window.scrollY + h;
        t.style.transform = `translate(${x}px,${$}px)`, t.style.width = `${d}px`;
      }
      const k = g ? "top" : "bottom";
      t.getAttribute("direction") !== k && (t.setAttribute("direction", k), o(this, rt, qs).call(this, g, this.appendToBody));
    }
  };
  u = /* @__PURE__ */ new WeakMap(), p = /* @__PURE__ */ new WeakMap(), F = /* @__PURE__ */ new WeakMap(), Q = /* @__PURE__ */ new WeakMap(), q = /* @__PURE__ */ new WeakMap(), _ = /* @__PURE__ */ new WeakMap(), A = /* @__PURE__ */ new WeakMap(), L = /* @__PURE__ */ new WeakMap(), B = /* @__PURE__ */ new WeakMap(), ee = /* @__PURE__ */ new WeakSet(), St = function(e) {
    var a;
    this.destroy();
    const { container: t, list: s, input: i } = o(this, Ze, Os).call(this);
    this.srcElement = t, m(this, u, s), m(this, p, i), m(this, _, this.scrollWindowHandler.bind(this)), m(this, A, this.scrollWindowHandler.bind(this)), m(this, L, this.focusWindowHandler.bind(this)), m(this, B, this.blurWindowHandler.bind(this)), this.alwaysOpen && ((a = n(this, p)) == null || a.openClose()), this.disabled ? this.srcElement.classList.add("treeselect--disabled") : this.srcElement.classList.remove("treeselect--disabled"), this.updateValue(e ?? this.value);
  }, te = /* @__PURE__ */ new WeakSet(), _t = function({
    groupedNodes: e,
    nodes: t,
    allNodes: s
  }) {
    this.ungroupedValue = t ? re(t) : [], this.groupedValue = e ? re(e) : [], this.allValue = s ? re(s) : [];
    let i = [];
    this.isIndependentNodes || this.isSingleSelect ? i = this.allValue : this.isGroupedValue ? i = this.groupedValue : i = this.ungroupedValue, this.value = Di(i, this.isSingleSelect);
  }, Ze = /* @__PURE__ */ new WeakSet(), Os = function() {
    const e = this.parentHtmlContainer;
    e.classList.add("treeselect"), this.rtl && e.setAttribute("dir", "rtl");
    const t = new Bi({
      value: [],
      // updateValue method calls in initMount method to set actual value
      options: this.options,
      openLevel: this.openLevel,
      listSlotHtmlComponent: this.listSlotHtmlComponent,
      emptyText: this.emptyText,
      isSingleSelect: this.isSingleSelect,
      showCount: this.showCount,
      disabledBranchNode: this.disabledBranchNode,
      expandSelected: this.expandSelected,
      isIndependentNodes: this.isIndependentNodes,
      rtl: this.rtl,
      iconElements: this.iconElements,
      inputCallback: (i) => o(this, lt, Hs).call(this, i),
      arrowClickCallback: (i, a) => o(this, nt, Gs).call(this, i, a),
      mouseupCallback: () => {
        var i;
        return (i = n(this, p)) == null ? void 0 : i.focus();
      }
    }), s = new di({
      value: [],
      // updateValue method calls in initMount method to set actual value
      showTags: this.showTags,
      tagsCountText: this.tagsCountText,
      clearable: this.clearable,
      isAlwaysOpened: this.alwaysOpen,
      searchable: this.searchable,
      placeholder: this.placeholder,
      disabled: this.disabled,
      isSingleSelect: this.isSingleSelect,
      id: this.id,
      ariaLabel: this.ariaLabel,
      iconElements: this.iconElements,
      inputCallback: (i) => o(this, Qe, Is).call(this, i),
      searchCallback: (i) => o(this, tt, Bs).call(this, i),
      openCallback: () => o(this, ot, Fs).call(this),
      closeCallback: () => o(this, ie, Tt).call(this),
      keydownCallback: (i) => o(this, et, Ps).call(this, i),
      focusCallback: () => o(this, st, Vs).call(this),
      blurCallback: () => o(this, it, Ds).call(this),
      nameChangeCallback: (i) => o(this, at, Ms).call(this, i)
    });
    return this.appendToBody && m(this, F, new ResizeObserver(() => this.updateListPosition())), e.append(s.srcElement), { container: e, list: t, input: s };
  }, Qe = /* @__PURE__ */ new WeakSet(), Is = function(e) {
    var i, a;
    const t = re(e);
    (i = n(this, u)) == null || i.updateValue(t);
    const s = ((a = n(this, u)) == null ? void 0 : a.selectedNodes) ?? {};
    o(this, te, _t).call(this, s), o(this, ne, Ot).call(this);
  }, et = /* @__PURE__ */ new WeakSet(), Ps = function(e) {
    var t;
    this.isListOpened && ((t = n(this, u)) == null || t.callKeyAction(e));
  }, tt = /* @__PURE__ */ new WeakSet(), Bs = function(e) {
    n(this, q) && clearTimeout(n(this, q)), m(this, q, window.setTimeout(() => {
      var t;
      (t = n(this, u)) == null || t.updateSearchValue(e), this.updateListPosition();
    }, 350)), o(this, pt, Us).call(this, e);
  }, st = /* @__PURE__ */ new WeakSet(), Vs = function() {
    o(this, j, he).call(this, true), n(this, L) && n(this, L) && n(this, B) && (document.addEventListener("mousedown", n(this, L), true), document.addEventListener("focus", n(this, L), true), window.addEventListener("blur", n(this, B)));
  }, it = /* @__PURE__ */ new WeakSet(), Ds = function() {
    setTimeout(() => {
      var s, i;
      const e = (s = n(this, p)) == null ? void 0 : s.srcElement.contains(document.activeElement), t = (i = n(this, u)) == null ? void 0 : i.srcElement.contains(document.activeElement);
      !e && !t && this.blurWindowHandler();
    }, 1);
  }, se = /* @__PURE__ */ new WeakSet(), At = function(e) {
    var s;
    if (!e)
      return;
    let t = [];
    this.isIndependentNodes || this.isSingleSelect ? t = e.allNodes : this.grouped ? t = e.groupedNodes : t = e.nodes, (s = n(this, p)) == null || s.updateValue(t), o(this, te, _t).call(this, e);
  }, lt = /* @__PURE__ */ new WeakSet(), Hs = function(e) {
    var t, s, i;
    o(this, se, At).call(this, e), this.isSingleSelect && !this.alwaysOpen && ((t = n(this, p)) == null || t.openClose(), (s = n(this, p)) == null || s.clearSearch()), (i = n(this, p)) == null || i.focus(), o(this, ne, Ot).call(this);
  }, nt = /* @__PURE__ */ new WeakSet(), Gs = function(e, t) {
    var s;
    (s = n(this, p)) == null || s.focus(), this.updateListPosition(), o(this, mt, zs).call(this, e, t);
  }, at = /* @__PURE__ */ new WeakSet(), Ms = function(e) {
    this.selectedName !== e && (this.selectedName = e, o(this, ht, Rs).call(this));
  }, ot = /* @__PURE__ */ new WeakSet(), Fs = function() {
    var e;
    this.isListOpened = true, n(this, _) && n(this, A) && (window.addEventListener("scroll", n(this, _), true), window.addEventListener("resize", n(this, A))), !(!n(this, u) || !this.srcElement) && (this.appendToBody ? (document.body.appendChild(n(this, u).srcElement), (e = n(this, F)) == null || e.observe(this.srcElement)) : this.srcElement.appendChild(n(this, u).srcElement), this.updateListPosition(), o(this, le, Nt).call(this, true), o(this, ct, js).call(this), o(this, dt, $s).call(this));
  }, ie = /* @__PURE__ */ new WeakSet(), Tt = function() {
    var t;
    this.alwaysOpen || (this.isListOpened = false, n(this, _) && n(this, A) && (window.removeEventListener("scroll", n(this, _), true), window.removeEventListener("resize", n(this, A))), !n(this, u) || !this.srcElement) || !(this.appendToBody ? document.body.contains(n(this, u).srcElement) : this.srcElement.contains(n(this, u).srcElement)) || (m(this, Q, n(this, u).srcElement.scrollTop), this.appendToBody ? (document.body.removeChild(n(this, u).srcElement), (t = n(this, F)) == null || t.disconnect()) : this.srcElement.removeChild(n(this, u).srcElement), o(this, le, Nt).call(this, false), o(this, ut, Ws).call(this));
  }, rt = /* @__PURE__ */ new WeakSet(), qs = function(e, t) {
    if (!n(this, u) || !n(this, p))
      return;
    const s = t ? "treeselect-list--top-to-body" : "treeselect-list--top", i = t ? "treeselect-list--bottom-to-body" : "treeselect-list--bottom";
    e ? (n(this, u).srcElement.classList.add(s), n(this, u).srcElement.classList.remove(i), n(this, p).srcElement.classList.add("treeselect-input--top"), n(this, p).srcElement.classList.remove("treeselect-input--bottom")) : (n(this, u).srcElement.classList.remove(s), n(this, u).srcElement.classList.add(i), n(this, p).srcElement.classList.remove("treeselect-input--top"), n(this, p).srcElement.classList.add("treeselect-input--bottom"));
  }, j = /* @__PURE__ */ new WeakSet(), he = function(e) {
    !n(this, p) || !n(this, u) || (e ? (n(this, p).srcElement.classList.add("treeselect-input--focused"), n(this, u).srcElement.classList.add("treeselect-list--focused")) : (n(this, p).srcElement.classList.remove("treeselect-input--focused"), n(this, u).srcElement.classList.remove("treeselect-list--focused")));
  }, le = /* @__PURE__ */ new WeakSet(), Nt = function(e) {
    var t, s, i, a;
    e ? (t = n(this, p)) == null || t.srcElement.classList.add("treeselect-input--opened") : (s = n(this, p)) == null || s.srcElement.classList.remove("treeselect-input--opened"), this.staticList ? (i = n(this, u)) == null || i.srcElement.classList.add("treeselect-list--static") : (a = n(this, u)) == null || a.srcElement.classList.remove("treeselect-list--static");
  }, R = /* @__PURE__ */ new WeakSet(), de = function(e) {
    !n(this, _) || !n(this, A) || !n(this, L) || !n(this, B) || ((!this.alwaysOpen || e) && (window.removeEventListener("scroll", n(this, _), true), window.removeEventListener("resize", n(this, A))), document.removeEventListener("mousedown", n(this, L), true), document.removeEventListener("focus", n(this, L), true), window.removeEventListener("blur", n(this, B)));
  }, ct = /* @__PURE__ */ new WeakSet(), js = function() {
    var t, s, i;
    const e = (t = n(this, u)) == null ? void 0 : t.isLastFocusedElementExist();
    this.saveScrollPosition && e ? (s = n(this, u)) == null || s.srcElement.scroll(0, n(this, Q)) : (i = n(this, u)) == null || i.focusFirstListElement();
  }, ne = /* @__PURE__ */ new WeakSet(), Ot = function() {
    var e;
    (e = this.srcElement) == null || e.dispatchEvent(new CustomEvent("input", { detail: this.value })), this.inputCallback && this.inputCallback(this.value);
  }, ht = /* @__PURE__ */ new WeakSet(), Rs = function() {
    var e;
    (e = this.srcElement) == null || e.dispatchEvent(new CustomEvent("name-change", { detail: this.selectedName })), this.nameChangeCallback && this.nameChangeCallback(this.selectedName);
  }, dt = /* @__PURE__ */ new WeakSet(), $s = function() {
    var e;
    this.alwaysOpen || ((e = this.srcElement) == null || e.dispatchEvent(new CustomEvent("open", { detail: this.value })), this.openCallback && this.openCallback(this.value));
  }, ut = /* @__PURE__ */ new WeakSet(), Ws = function() {
    var e;
    this.alwaysOpen || ((e = this.srcElement) == null || e.dispatchEvent(new CustomEvent("close", { detail: this.value })), this.closeCallback && this.closeCallback(this.value));
  }, pt = /* @__PURE__ */ new WeakSet(), Us = function(e) {
    var s;
    const t = (e == null ? void 0 : e.trim()) ?? "";
    (s = this.srcElement) == null || s.dispatchEvent(new CustomEvent("search", { detail: t })), this.searchCallback && this.searchCallback(t);
  }, mt = /* @__PURE__ */ new WeakSet(), zs = function(e, t) {
    var s;
    (s = this.srcElement) == null || s.dispatchEvent(new CustomEvent("open-close-group", { detail: { groupId: e, isClosed: t } })), this.openCloseGroupCallback && this.openCloseGroupCallback(e, t);
  };

  // resources/js/components/select-tree.js
  function selectTree({
    state,
    name,
    options,
    searchable,
    showCount,
    placeholder,
    rtl,
    disabledBranchNode = true,
    disabled = false,
    isSingleSelect = true,
    showTags = true,
    clearable = true,
    isIndependentNodes = true,
    alwaysOpen = false,
    emptyText,
    expandSelected = true,
    grouped = true,
    openLevel = 0,
    direction = "auto"
  }) {
    return {
      state,
      /** @type Treeselect */
      tree: null,
      init() {
        this.tree = new Gi({
          id: `tree-${name}-id`,
          ariaLabel: `tree-${name}-label`,
          parentHtmlContainer: this.$refs.tree,
          value: this.state ?? [],
          options,
          searchable,
          showCount,
          placeholder,
          disabledBranchNode,
          disabled,
          isSingleSelect,
          showTags,
          clearable,
          isIndependentNodes,
          alwaysOpen,
          emptyText,
          expandSelected,
          grouped,
          openLevel,
          direction,
          rtl
        });
        this.tree.srcElement.addEventListener("input", (e) => {
          this.state = e.detail;
        });
      }
    };
  }

  // node_modules/sortablejs/modular/sortable.esm.js
  function ownKeys(object, enumerableOnly) {
    var keys = Object.keys(object);
    if (Object.getOwnPropertySymbols) {
      var symbols = Object.getOwnPropertySymbols(object);
      if (enumerableOnly) {
        symbols = symbols.filter(function(sym) {
          return Object.getOwnPropertyDescriptor(object, sym).enumerable;
        });
      }
      keys.push.apply(keys, symbols);
    }
    return keys;
  }
  function _objectSpread2(target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i] != null ? arguments[i] : {};
      if (i % 2) {
        ownKeys(Object(source), true).forEach(function(key) {
          _defineProperty(target, key, source[key]);
        });
      } else if (Object.getOwnPropertyDescriptors) {
        Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
      } else {
        ownKeys(Object(source)).forEach(function(key) {
          Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
        });
      }
    }
    return target;
  }
  function _typeof(obj) {
    "@babel/helpers - typeof";
    if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
      _typeof = function(obj2) {
        return typeof obj2;
      };
    } else {
      _typeof = function(obj2) {
        return obj2 && typeof Symbol === "function" && obj2.constructor === Symbol && obj2 !== Symbol.prototype ? "symbol" : typeof obj2;
      };
    }
    return _typeof(obj);
  }
  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }
    return obj;
  }
  function _extends() {
    _extends = Object.assign || function(target) {
      for (var i = 1; i < arguments.length; i++) {
        var source = arguments[i];
        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }
      return target;
    };
    return _extends.apply(this, arguments);
  }
  function _objectWithoutPropertiesLoose(source, excluded) {
    if (source == null)
      return {};
    var target = {};
    var sourceKeys = Object.keys(source);
    var key, i;
    for (i = 0; i < sourceKeys.length; i++) {
      key = sourceKeys[i];
      if (excluded.indexOf(key) >= 0)
        continue;
      target[key] = source[key];
    }
    return target;
  }
  function _objectWithoutProperties(source, excluded) {
    if (source == null)
      return {};
    var target = _objectWithoutPropertiesLoose(source, excluded);
    var key, i;
    if (Object.getOwnPropertySymbols) {
      var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
      for (i = 0; i < sourceSymbolKeys.length; i++) {
        key = sourceSymbolKeys[i];
        if (excluded.indexOf(key) >= 0)
          continue;
        if (!Object.prototype.propertyIsEnumerable.call(source, key))
          continue;
        target[key] = source[key];
      }
    }
    return target;
  }
  var version = "1.15.2";
  function userAgent(pattern) {
    if (typeof window !== "undefined" && window.navigator) {
      return !!/* @__PURE__ */ navigator.userAgent.match(pattern);
    }
  }
  var IE11OrLess = userAgent(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i);
  var Edge = userAgent(/Edge/i);
  var FireFox = userAgent(/firefox/i);
  var Safari = userAgent(/safari/i) && !userAgent(/chrome/i) && !userAgent(/android/i);
  var IOS = userAgent(/iP(ad|od|hone)/i);
  var ChromeForAndroid = userAgent(/chrome/i) && userAgent(/android/i);
  var captureMode = {
    capture: false,
    passive: false
  };
  function on(el, event, fn) {
    el.addEventListener(event, fn, !IE11OrLess && captureMode);
  }
  function off(el, event, fn) {
    el.removeEventListener(event, fn, !IE11OrLess && captureMode);
  }
  function matches(el, selector) {
    if (!selector)
      return;
    selector[0] === ">" && (selector = selector.substring(1));
    if (el) {
      try {
        if (el.matches) {
          return el.matches(selector);
        } else if (el.msMatchesSelector) {
          return el.msMatchesSelector(selector);
        } else if (el.webkitMatchesSelector) {
          return el.webkitMatchesSelector(selector);
        }
      } catch (_2) {
        return false;
      }
    }
    return false;
  }
  function getParentOrHost(el) {
    return el.host && el !== document && el.host.nodeType ? el.host : el.parentNode;
  }
  function closest(el, selector, ctx, includeCTX) {
    if (el) {
      ctx = ctx || document;
      do {
        if (selector != null && (selector[0] === ">" ? el.parentNode === ctx && matches(el, selector) : matches(el, selector)) || includeCTX && el === ctx) {
          return el;
        }
        if (el === ctx)
          break;
      } while (el = getParentOrHost(el));
    }
    return null;
  }
  var R_SPACE = /\s+/g;
  function toggleClass(el, name, state) {
    if (el && name) {
      if (el.classList) {
        el.classList[state ? "add" : "remove"](name);
      } else {
        var className = (" " + el.className + " ").replace(R_SPACE, " ").replace(" " + name + " ", " ");
        el.className = (className + (state ? " " + name : "")).replace(R_SPACE, " ");
      }
    }
  }
  function css(el, prop, val) {
    var style = el && el.style;
    if (style) {
      if (val === void 0) {
        if (document.defaultView && document.defaultView.getComputedStyle) {
          val = document.defaultView.getComputedStyle(el, "");
        } else if (el.currentStyle) {
          val = el.currentStyle;
        }
        return prop === void 0 ? val : val[prop];
      } else {
        if (!(prop in style) && prop.indexOf("webkit") === -1) {
          prop = "-webkit-" + prop;
        }
        style[prop] = val + (typeof val === "string" ? "" : "px");
      }
    }
  }
  function matrix(el, selfOnly) {
    var appliedTransforms = "";
    if (typeof el === "string") {
      appliedTransforms = el;
    } else {
      do {
        var transform = css(el, "transform");
        if (transform && transform !== "none") {
          appliedTransforms = transform + " " + appliedTransforms;
        }
      } while (!selfOnly && (el = el.parentNode));
    }
    var matrixFn = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
    return matrixFn && new matrixFn(appliedTransforms);
  }
  function find(ctx, tagName, iterator) {
    if (ctx) {
      var list = ctx.getElementsByTagName(tagName), i = 0, n2 = list.length;
      if (iterator) {
        for (; i < n2; i++) {
          iterator(list[i], i);
        }
      }
      return list;
    }
    return [];
  }
  function getWindowScrollingElement() {
    var scrollingElement = document.scrollingElement;
    if (scrollingElement) {
      return scrollingElement;
    } else {
      return document.documentElement;
    }
  }
  function getRect(el, relativeToContainingBlock, relativeToNonStaticParent, undoScale, container) {
    if (!el.getBoundingClientRect && el !== window)
      return;
    var elRect, top, left, bottom, right, height, width;
    if (el !== window && el.parentNode && el !== getWindowScrollingElement()) {
      elRect = el.getBoundingClientRect();
      top = elRect.top;
      left = elRect.left;
      bottom = elRect.bottom;
      right = elRect.right;
      height = elRect.height;
      width = elRect.width;
    } else {
      top = 0;
      left = 0;
      bottom = window.innerHeight;
      right = window.innerWidth;
      height = window.innerHeight;
      width = window.innerWidth;
    }
    if ((relativeToContainingBlock || relativeToNonStaticParent) && el !== window) {
      container = container || el.parentNode;
      if (!IE11OrLess) {
        do {
          if (container && container.getBoundingClientRect && (css(container, "transform") !== "none" || relativeToNonStaticParent && css(container, "position") !== "static")) {
            var containerRect = container.getBoundingClientRect();
            top -= containerRect.top + parseInt(css(container, "border-top-width"));
            left -= containerRect.left + parseInt(css(container, "border-left-width"));
            bottom = top + elRect.height;
            right = left + elRect.width;
            break;
          }
        } while (container = container.parentNode);
      }
    }
    if (undoScale && el !== window) {
      var elMatrix = matrix(container || el), scaleX = elMatrix && elMatrix.a, scaleY = elMatrix && elMatrix.d;
      if (elMatrix) {
        top /= scaleY;
        left /= scaleX;
        width /= scaleX;
        height /= scaleY;
        bottom = top + height;
        right = left + width;
      }
    }
    return {
      top,
      left,
      bottom,
      right,
      width,
      height
    };
  }
  function isScrolledPast(el, elSide, parentSide) {
    var parent = getParentAutoScrollElement(el, true), elSideVal = getRect(el)[elSide];
    while (parent) {
      var parentSideVal = getRect(parent)[parentSide], visible = void 0;
      if (parentSide === "top" || parentSide === "left") {
        visible = elSideVal >= parentSideVal;
      } else {
        visible = elSideVal <= parentSideVal;
      }
      if (!visible)
        return parent;
      if (parent === getWindowScrollingElement())
        break;
      parent = getParentAutoScrollElement(parent, false);
    }
    return false;
  }
  function getChild(el, childNum, options, includeDragEl) {
    var currentChild = 0, i = 0, children = el.children;
    while (i < children.length) {
      if (children[i].style.display !== "none" && children[i] !== Sortable.ghost && (includeDragEl || children[i] !== Sortable.dragged) && closest(children[i], options.draggable, el, false)) {
        if (currentChild === childNum) {
          return children[i];
        }
        currentChild++;
      }
      i++;
    }
    return null;
  }
  function lastChild(el, selector) {
    var last = el.lastElementChild;
    while (last && (last === Sortable.ghost || css(last, "display") === "none" || selector && !matches(last, selector))) {
      last = last.previousElementSibling;
    }
    return last || null;
  }
  function index(el, selector) {
    var index2 = 0;
    if (!el || !el.parentNode) {
      return -1;
    }
    while (el = el.previousElementSibling) {
      if (el.nodeName.toUpperCase() !== "TEMPLATE" && el !== Sortable.clone && (!selector || matches(el, selector))) {
        index2++;
      }
    }
    return index2;
  }
  function getRelativeScrollOffset(el) {
    var offsetLeft = 0, offsetTop = 0, winScroller = getWindowScrollingElement();
    if (el) {
      do {
        var elMatrix = matrix(el), scaleX = elMatrix.a, scaleY = elMatrix.d;
        offsetLeft += el.scrollLeft * scaleX;
        offsetTop += el.scrollTop * scaleY;
      } while (el !== winScroller && (el = el.parentNode));
    }
    return [offsetLeft, offsetTop];
  }
  function indexOfObject(arr, obj) {
    for (var i in arr) {
      if (!arr.hasOwnProperty(i))
        continue;
      for (var key in obj) {
        if (obj.hasOwnProperty(key) && obj[key] === arr[i][key])
          return Number(i);
      }
    }
    return -1;
  }
  function getParentAutoScrollElement(el, includeSelf) {
    if (!el || !el.getBoundingClientRect)
      return getWindowScrollingElement();
    var elem = el;
    var gotSelf = false;
    do {
      if (elem.clientWidth < elem.scrollWidth || elem.clientHeight < elem.scrollHeight) {
        var elemCSS = css(elem);
        if (elem.clientWidth < elem.scrollWidth && (elemCSS.overflowX == "auto" || elemCSS.overflowX == "scroll") || elem.clientHeight < elem.scrollHeight && (elemCSS.overflowY == "auto" || elemCSS.overflowY == "scroll")) {
          if (!elem.getBoundingClientRect || elem === document.body)
            return getWindowScrollingElement();
          if (gotSelf || includeSelf)
            return elem;
          gotSelf = true;
        }
      }
    } while (elem = elem.parentNode);
    return getWindowScrollingElement();
  }
  function extend(dst, src) {
    if (dst && src) {
      for (var key in src) {
        if (src.hasOwnProperty(key)) {
          dst[key] = src[key];
        }
      }
    }
    return dst;
  }
  function isRectEqual(rect1, rect2) {
    return Math.round(rect1.top) === Math.round(rect2.top) && Math.round(rect1.left) === Math.round(rect2.left) && Math.round(rect1.height) === Math.round(rect2.height) && Math.round(rect1.width) === Math.round(rect2.width);
  }
  var _throttleTimeout;
  function throttle(callback, ms2) {
    return function() {
      if (!_throttleTimeout) {
        var args = arguments, _this = this;
        if (args.length === 1) {
          callback.call(_this, args[0]);
        } else {
          callback.apply(_this, args);
        }
        _throttleTimeout = setTimeout(function() {
          _throttleTimeout = void 0;
        }, ms2);
      }
    };
  }
  function cancelThrottle() {
    clearTimeout(_throttleTimeout);
    _throttleTimeout = void 0;
  }
  function scrollBy(el, x, y) {
    el.scrollLeft += x;
    el.scrollTop += y;
  }
  function clone(el) {
    var Polymer = window.Polymer;
    var $ = window.jQuery || window.Zepto;
    if (Polymer && Polymer.dom) {
      return Polymer.dom(el).cloneNode(true);
    } else if ($) {
      return $(el).clone(true)[0];
    } else {
      return el.cloneNode(true);
    }
  }
  function getChildContainingRectFromElement(container, options, ghostEl2) {
    var rect = {};
    Array.from(container.children).forEach(function(child) {
      var _rect$left, _rect$top, _rect$right, _rect$bottom;
      if (!closest(child, options.draggable, container, false) || child.animated || child === ghostEl2)
        return;
      var childRect = getRect(child);
      rect.left = Math.min((_rect$left = rect.left) !== null && _rect$left !== void 0 ? _rect$left : Infinity, childRect.left);
      rect.top = Math.min((_rect$top = rect.top) !== null && _rect$top !== void 0 ? _rect$top : Infinity, childRect.top);
      rect.right = Math.max((_rect$right = rect.right) !== null && _rect$right !== void 0 ? _rect$right : -Infinity, childRect.right);
      rect.bottom = Math.max((_rect$bottom = rect.bottom) !== null && _rect$bottom !== void 0 ? _rect$bottom : -Infinity, childRect.bottom);
    });
    rect.width = rect.right - rect.left;
    rect.height = rect.bottom - rect.top;
    rect.x = rect.left;
    rect.y = rect.top;
    return rect;
  }
  var expando = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
  function AnimationStateManager() {
    var animationStates = [], animationCallbackId;
    return {
      captureAnimationState: function captureAnimationState() {
        animationStates = [];
        if (!this.options.animation)
          return;
        var children = [].slice.call(this.el.children);
        children.forEach(function(child) {
          if (css(child, "display") === "none" || child === Sortable.ghost)
            return;
          animationStates.push({
            target: child,
            rect: getRect(child)
          });
          var fromRect = _objectSpread2({}, animationStates[animationStates.length - 1].rect);
          if (child.thisAnimationDuration) {
            var childMatrix = matrix(child, true);
            if (childMatrix) {
              fromRect.top -= childMatrix.f;
              fromRect.left -= childMatrix.e;
            }
          }
          child.fromRect = fromRect;
        });
      },
      addAnimationState: function addAnimationState(state) {
        animationStates.push(state);
      },
      removeAnimationState: function removeAnimationState(target) {
        animationStates.splice(indexOfObject(animationStates, {
          target
        }), 1);
      },
      animateAll: function animateAll(callback) {
        var _this = this;
        if (!this.options.animation) {
          clearTimeout(animationCallbackId);
          if (typeof callback === "function")
            callback();
          return;
        }
        var animating = false, animationTime = 0;
        animationStates.forEach(function(state) {
          var time = 0, target = state.target, fromRect = target.fromRect, toRect = getRect(target), prevFromRect = target.prevFromRect, prevToRect = target.prevToRect, animatingRect = state.rect, targetMatrix = matrix(target, true);
          if (targetMatrix) {
            toRect.top -= targetMatrix.f;
            toRect.left -= targetMatrix.e;
          }
          target.toRect = toRect;
          if (target.thisAnimationDuration) {
            if (isRectEqual(prevFromRect, toRect) && !isRectEqual(fromRect, toRect) && // Make sure animatingRect is on line between toRect & fromRect
            (animatingRect.top - toRect.top) / (animatingRect.left - toRect.left) === (fromRect.top - toRect.top) / (fromRect.left - toRect.left)) {
              time = calculateRealTime(animatingRect, prevFromRect, prevToRect, _this.options);
            }
          }
          if (!isRectEqual(toRect, fromRect)) {
            target.prevFromRect = fromRect;
            target.prevToRect = toRect;
            if (!time) {
              time = _this.options.animation;
            }
            _this.animate(target, animatingRect, toRect, time);
          }
          if (time) {
            animating = true;
            animationTime = Math.max(animationTime, time);
            clearTimeout(target.animationResetTimer);
            target.animationResetTimer = setTimeout(function() {
              target.animationTime = 0;
              target.prevFromRect = null;
              target.fromRect = null;
              target.prevToRect = null;
              target.thisAnimationDuration = null;
            }, time);
            target.thisAnimationDuration = time;
          }
        });
        clearTimeout(animationCallbackId);
        if (!animating) {
          if (typeof callback === "function")
            callback();
        } else {
          animationCallbackId = setTimeout(function() {
            if (typeof callback === "function")
              callback();
          }, animationTime);
        }
        animationStates = [];
      },
      animate: function animate(target, currentRect, toRect, duration) {
        if (duration) {
          css(target, "transition", "");
          css(target, "transform", "");
          var elMatrix = matrix(this.el), scaleX = elMatrix && elMatrix.a, scaleY = elMatrix && elMatrix.d, translateX = (currentRect.left - toRect.left) / (scaleX || 1), translateY = (currentRect.top - toRect.top) / (scaleY || 1);
          target.animatingX = !!translateX;
          target.animatingY = !!translateY;
          css(target, "transform", "translate3d(" + translateX + "px," + translateY + "px,0)");
          this.forRepaintDummy = repaint(target);
          css(target, "transition", "transform " + duration + "ms" + (this.options.easing ? " " + this.options.easing : ""));
          css(target, "transform", "translate3d(0,0,0)");
          typeof target.animated === "number" && clearTimeout(target.animated);
          target.animated = setTimeout(function() {
            css(target, "transition", "");
            css(target, "transform", "");
            target.animated = false;
            target.animatingX = false;
            target.animatingY = false;
          }, duration);
        }
      }
    };
  }
  function repaint(target) {
    return target.offsetWidth;
  }
  function calculateRealTime(animatingRect, fromRect, toRect, options) {
    return Math.sqrt(Math.pow(fromRect.top - animatingRect.top, 2) + Math.pow(fromRect.left - animatingRect.left, 2)) / Math.sqrt(Math.pow(fromRect.top - toRect.top, 2) + Math.pow(fromRect.left - toRect.left, 2)) * options.animation;
  }
  var plugins = [];
  var defaults = {
    initializeByDefault: true
  };
  var PluginManager = {
    mount: function mount(plugin) {
      for (var option2 in defaults) {
        if (defaults.hasOwnProperty(option2) && !(option2 in plugin)) {
          plugin[option2] = defaults[option2];
        }
      }
      plugins.forEach(function(p2) {
        if (p2.pluginName === plugin.pluginName) {
          throw "Sortable: Cannot mount plugin ".concat(plugin.pluginName, " more than once");
        }
      });
      plugins.push(plugin);
    },
    pluginEvent: function pluginEvent(eventName, sortable, evt) {
      var _this = this;
      this.eventCanceled = false;
      evt.cancel = function() {
        _this.eventCanceled = true;
      };
      var eventNameGlobal = eventName + "Global";
      plugins.forEach(function(plugin) {
        if (!sortable[plugin.pluginName])
          return;
        if (sortable[plugin.pluginName][eventNameGlobal]) {
          sortable[plugin.pluginName][eventNameGlobal](_objectSpread2({
            sortable
          }, evt));
        }
        if (sortable.options[plugin.pluginName] && sortable[plugin.pluginName][eventName]) {
          sortable[plugin.pluginName][eventName](_objectSpread2({
            sortable
          }, evt));
        }
      });
    },
    initializePlugins: function initializePlugins(sortable, el, defaults2, options) {
      plugins.forEach(function(plugin) {
        var pluginName = plugin.pluginName;
        if (!sortable.options[pluginName] && !plugin.initializeByDefault)
          return;
        var initialized = new plugin(sortable, el, sortable.options);
        initialized.sortable = sortable;
        initialized.options = sortable.options;
        sortable[pluginName] = initialized;
        _extends(defaults2, initialized.defaults);
      });
      for (var option2 in sortable.options) {
        if (!sortable.options.hasOwnProperty(option2))
          continue;
        var modified = this.modifyOption(sortable, option2, sortable.options[option2]);
        if (typeof modified !== "undefined") {
          sortable.options[option2] = modified;
        }
      }
    },
    getEventProperties: function getEventProperties(name, sortable) {
      var eventProperties = {};
      plugins.forEach(function(plugin) {
        if (typeof plugin.eventProperties !== "function")
          return;
        _extends(eventProperties, plugin.eventProperties.call(sortable[plugin.pluginName], name));
      });
      return eventProperties;
    },
    modifyOption: function modifyOption(sortable, name, value) {
      var modifiedValue;
      plugins.forEach(function(plugin) {
        if (!sortable[plugin.pluginName])
          return;
        if (plugin.optionListeners && typeof plugin.optionListeners[name] === "function") {
          modifiedValue = plugin.optionListeners[name].call(sortable[plugin.pluginName], value);
        }
      });
      return modifiedValue;
    }
  };
  function dispatchEvent(_ref) {
    var sortable = _ref.sortable, rootEl2 = _ref.rootEl, name = _ref.name, targetEl = _ref.targetEl, cloneEl2 = _ref.cloneEl, toEl = _ref.toEl, fromEl = _ref.fromEl, oldIndex2 = _ref.oldIndex, newIndex2 = _ref.newIndex, oldDraggableIndex2 = _ref.oldDraggableIndex, newDraggableIndex2 = _ref.newDraggableIndex, originalEvent = _ref.originalEvent, putSortable2 = _ref.putSortable, extraEventProperties = _ref.extraEventProperties;
    sortable = sortable || rootEl2 && rootEl2[expando];
    if (!sortable)
      return;
    var evt, options = sortable.options, onName = "on" + name.charAt(0).toUpperCase() + name.substr(1);
    if (window.CustomEvent && !IE11OrLess && !Edge) {
      evt = new CustomEvent(name, {
        bubbles: true,
        cancelable: true
      });
    } else {
      evt = document.createEvent("Event");
      evt.initEvent(name, true, true);
    }
    evt.to = toEl || rootEl2;
    evt.from = fromEl || rootEl2;
    evt.item = targetEl || rootEl2;
    evt.clone = cloneEl2;
    evt.oldIndex = oldIndex2;
    evt.newIndex = newIndex2;
    evt.oldDraggableIndex = oldDraggableIndex2;
    evt.newDraggableIndex = newDraggableIndex2;
    evt.originalEvent = originalEvent;
    evt.pullMode = putSortable2 ? putSortable2.lastPutMode : void 0;
    var allEventProperties = _objectSpread2(_objectSpread2({}, extraEventProperties), PluginManager.getEventProperties(name, sortable));
    for (var option2 in allEventProperties) {
      evt[option2] = allEventProperties[option2];
    }
    if (rootEl2) {
      rootEl2.dispatchEvent(evt);
    }
    if (options[onName]) {
      options[onName].call(sortable, evt);
    }
  }
  var _excluded = ["evt"];
  var pluginEvent2 = function pluginEvent3(eventName, sortable) {
    var _ref = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, originalEvent = _ref.evt, data = _objectWithoutProperties(_ref, _excluded);
    PluginManager.pluginEvent.bind(Sortable)(eventName, sortable, _objectSpread2({
      dragEl,
      parentEl,
      ghostEl,
      rootEl,
      nextEl,
      lastDownEl,
      cloneEl,
      cloneHidden,
      dragStarted: moved,
      putSortable,
      activeSortable: Sortable.active,
      originalEvent,
      oldIndex,
      oldDraggableIndex,
      newIndex,
      newDraggableIndex,
      hideGhostForTarget: _hideGhostForTarget,
      unhideGhostForTarget: _unhideGhostForTarget,
      cloneNowHidden: function cloneNowHidden() {
        cloneHidden = true;
      },
      cloneNowShown: function cloneNowShown() {
        cloneHidden = false;
      },
      dispatchSortableEvent: function dispatchSortableEvent(name) {
        _dispatchEvent({
          sortable,
          name,
          originalEvent
        });
      }
    }, data));
  };
  function _dispatchEvent(info) {
    dispatchEvent(_objectSpread2({
      putSortable,
      cloneEl,
      targetEl: dragEl,
      rootEl,
      oldIndex,
      oldDraggableIndex,
      newIndex,
      newDraggableIndex
    }, info));
  }
  var dragEl;
  var parentEl;
  var ghostEl;
  var rootEl;
  var nextEl;
  var lastDownEl;
  var cloneEl;
  var cloneHidden;
  var oldIndex;
  var newIndex;
  var oldDraggableIndex;
  var newDraggableIndex;
  var activeGroup;
  var putSortable;
  var awaitingDragStarted = false;
  var ignoreNextClick = false;
  var sortables = [];
  var tapEvt;
  var touchEvt;
  var lastDx;
  var lastDy;
  var tapDistanceLeft;
  var tapDistanceTop;
  var moved;
  var lastTarget;
  var lastDirection;
  var pastFirstInvertThresh = false;
  var isCircumstantialInvert = false;
  var targetMoveDistance;
  var ghostRelativeParent;
  var ghostRelativeParentInitialScroll = [];
  var _silent = false;
  var savedInputChecked = [];
  var documentExists = typeof document !== "undefined";
  var PositionGhostAbsolutely = IOS;
  var CSSFloatProperty = Edge || IE11OrLess ? "cssFloat" : "float";
  var supportDraggable = documentExists && !ChromeForAndroid && !IOS && "draggable" in document.createElement("div");
  var supportCssPointerEvents = function() {
    if (!documentExists)
      return;
    if (IE11OrLess) {
      return false;
    }
    var el = document.createElement("x");
    el.style.cssText = "pointer-events:auto";
    return el.style.pointerEvents === "auto";
  }();
  var _detectDirection = function _detectDirection2(el, options) {
    var elCSS = css(el), elWidth = parseInt(elCSS.width) - parseInt(elCSS.paddingLeft) - parseInt(elCSS.paddingRight) - parseInt(elCSS.borderLeftWidth) - parseInt(elCSS.borderRightWidth), child1 = getChild(el, 0, options), child2 = getChild(el, 1, options), firstChildCSS = child1 && css(child1), secondChildCSS = child2 && css(child2), firstChildWidth = firstChildCSS && parseInt(firstChildCSS.marginLeft) + parseInt(firstChildCSS.marginRight) + getRect(child1).width, secondChildWidth = secondChildCSS && parseInt(secondChildCSS.marginLeft) + parseInt(secondChildCSS.marginRight) + getRect(child2).width;
    if (elCSS.display === "flex") {
      return elCSS.flexDirection === "column" || elCSS.flexDirection === "column-reverse" ? "vertical" : "horizontal";
    }
    if (elCSS.display === "grid") {
      return elCSS.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
    }
    if (child1 && firstChildCSS["float"] && firstChildCSS["float"] !== "none") {
      var touchingSideChild2 = firstChildCSS["float"] === "left" ? "left" : "right";
      return child2 && (secondChildCSS.clear === "both" || secondChildCSS.clear === touchingSideChild2) ? "vertical" : "horizontal";
    }
    return child1 && (firstChildCSS.display === "block" || firstChildCSS.display === "flex" || firstChildCSS.display === "table" || firstChildCSS.display === "grid" || firstChildWidth >= elWidth && elCSS[CSSFloatProperty] === "none" || child2 && elCSS[CSSFloatProperty] === "none" && firstChildWidth + secondChildWidth > elWidth) ? "vertical" : "horizontal";
  };
  var _dragElInRowColumn = function _dragElInRowColumn2(dragRect, targetRect, vertical) {
    var dragElS1Opp = vertical ? dragRect.left : dragRect.top, dragElS2Opp = vertical ? dragRect.right : dragRect.bottom, dragElOppLength = vertical ? dragRect.width : dragRect.height, targetS1Opp = vertical ? targetRect.left : targetRect.top, targetS2Opp = vertical ? targetRect.right : targetRect.bottom, targetOppLength = vertical ? targetRect.width : targetRect.height;
    return dragElS1Opp === targetS1Opp || dragElS2Opp === targetS2Opp || dragElS1Opp + dragElOppLength / 2 === targetS1Opp + targetOppLength / 2;
  };
  var _detectNearestEmptySortable = function _detectNearestEmptySortable2(x, y) {
    var ret;
    sortables.some(function(sortable) {
      var threshold = sortable[expando].options.emptyInsertThreshold;
      if (!threshold || lastChild(sortable))
        return;
      var rect = getRect(sortable), insideHorizontally = x >= rect.left - threshold && x <= rect.right + threshold, insideVertically = y >= rect.top - threshold && y <= rect.bottom + threshold;
      if (insideHorizontally && insideVertically) {
        return ret = sortable;
      }
    });
    return ret;
  };
  var _prepareGroup = function _prepareGroup2(options) {
    function toFn(value, pull) {
      return function(to, from, dragEl2, evt) {
        var sameGroup = to.options.group.name && from.options.group.name && to.options.group.name === from.options.group.name;
        if (value == null && (pull || sameGroup)) {
          return true;
        } else if (value == null || value === false) {
          return false;
        } else if (pull && value === "clone") {
          return value;
        } else if (typeof value === "function") {
          return toFn(value(to, from, dragEl2, evt), pull)(to, from, dragEl2, evt);
        } else {
          var otherGroup = (pull ? to : from).options.group.name;
          return value === true || typeof value === "string" && value === otherGroup || value.join && value.indexOf(otherGroup) > -1;
        }
      };
    }
    var group = {};
    var originalGroup = options.group;
    if (!originalGroup || _typeof(originalGroup) != "object") {
      originalGroup = {
        name: originalGroup
      };
    }
    group.name = originalGroup.name;
    group.checkPull = toFn(originalGroup.pull, true);
    group.checkPut = toFn(originalGroup.put);
    group.revertClone = originalGroup.revertClone;
    options.group = group;
  };
  var _hideGhostForTarget = function _hideGhostForTarget2() {
    if (!supportCssPointerEvents && ghostEl) {
      css(ghostEl, "display", "none");
    }
  };
  var _unhideGhostForTarget = function _unhideGhostForTarget2() {
    if (!supportCssPointerEvents && ghostEl) {
      css(ghostEl, "display", "");
    }
  };
  if (documentExists && !ChromeForAndroid) {
    document.addEventListener("click", function(evt) {
      if (ignoreNextClick) {
        evt.preventDefault();
        evt.stopPropagation && evt.stopPropagation();
        evt.stopImmediatePropagation && evt.stopImmediatePropagation();
        ignoreNextClick = false;
        return false;
      }
    }, true);
  }
  var nearestEmptyInsertDetectEvent = function nearestEmptyInsertDetectEvent2(evt) {
    if (dragEl) {
      evt = evt.touches ? evt.touches[0] : evt;
      var nearest = _detectNearestEmptySortable(evt.clientX, evt.clientY);
      if (nearest) {
        var event = {};
        for (var i in evt) {
          if (evt.hasOwnProperty(i)) {
            event[i] = evt[i];
          }
        }
        event.target = event.rootEl = nearest;
        event.preventDefault = void 0;
        event.stopPropagation = void 0;
        nearest[expando]._onDragOver(event);
      }
    }
  };
  var _checkOutsideTargetEl = function _checkOutsideTargetEl2(evt) {
    if (dragEl) {
      dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
    }
  };
  function Sortable(el, options) {
    if (!(el && el.nodeType && el.nodeType === 1)) {
      throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(el));
    }
    this.el = el;
    this.options = options = _extends({}, options);
    el[expando] = this;
    var defaults2 = {
      group: null,
      sort: true,
      disabled: false,
      store: null,
      handle: null,
      draggable: /^[uo]l$/i.test(el.nodeName) ? ">li" : ">*",
      swapThreshold: 1,
      // percentage; 0 <= x <= 1
      invertSwap: false,
      // invert always
      invertedSwapThreshold: null,
      // will be set to same as swapThreshold if default
      removeCloneOnHide: true,
      direction: function direction() {
        return _detectDirection(el, this.options);
      },
      ghostClass: "sortable-ghost",
      chosenClass: "sortable-chosen",
      dragClass: "sortable-drag",
      ignore: "a, img",
      filter: null,
      preventOnFilter: true,
      animation: 0,
      easing: null,
      setData: function setData(dataTransfer, dragEl2) {
        dataTransfer.setData("Text", dragEl2.textContent);
      },
      dropBubble: false,
      dragoverBubble: false,
      dataIdAttr: "data-id",
      delay: 0,
      delayOnTouchOnly: false,
      touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
      forceFallback: false,
      fallbackClass: "sortable-fallback",
      fallbackOnBody: false,
      fallbackTolerance: 0,
      fallbackOffset: {
        x: 0,
        y: 0
      },
      supportPointer: Sortable.supportPointer !== false && "PointerEvent" in window && !Safari,
      emptyInsertThreshold: 5
    };
    PluginManager.initializePlugins(this, el, defaults2);
    for (var name in defaults2) {
      !(name in options) && (options[name] = defaults2[name]);
    }
    _prepareGroup(options);
    for (var fn in this) {
      if (fn.charAt(0) === "_" && typeof this[fn] === "function") {
        this[fn] = this[fn].bind(this);
      }
    }
    this.nativeDraggable = options.forceFallback ? false : supportDraggable;
    if (this.nativeDraggable) {
      this.options.touchStartThreshold = 1;
    }
    if (options.supportPointer) {
      on(el, "pointerdown", this._onTapStart);
    } else {
      on(el, "mousedown", this._onTapStart);
      on(el, "touchstart", this._onTapStart);
    }
    if (this.nativeDraggable) {
      on(el, "dragover", this);
      on(el, "dragenter", this);
    }
    sortables.push(this.el);
    options.store && options.store.get && this.sort(options.store.get(this) || []);
    _extends(this, AnimationStateManager());
  }
  Sortable.prototype = /** @lends Sortable.prototype */
  {
    constructor: Sortable,
    _isOutsideThisEl: function _isOutsideThisEl(target) {
      if (!this.el.contains(target) && target !== this.el) {
        lastTarget = null;
      }
    },
    _getDirection: function _getDirection(evt, target) {
      return typeof this.options.direction === "function" ? this.options.direction.call(this, evt, target, dragEl) : this.options.direction;
    },
    _onTapStart: function _onTapStart(evt) {
      if (!evt.cancelable)
        return;
      var _this = this, el = this.el, options = this.options, preventOnFilter = options.preventOnFilter, type = evt.type, touch = evt.touches && evt.touches[0] || evt.pointerType && evt.pointerType === "touch" && evt, target = (touch || evt).target, originalTarget = evt.target.shadowRoot && (evt.path && evt.path[0] || evt.composedPath && evt.composedPath()[0]) || target, filter = options.filter;
      _saveInputCheckedState(el);
      if (dragEl) {
        return;
      }
      if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
        return;
      }
      if (originalTarget.isContentEditable) {
        return;
      }
      if (!this.nativeDraggable && Safari && target && target.tagName.toUpperCase() === "SELECT") {
        return;
      }
      target = closest(target, options.draggable, el, false);
      if (target && target.animated) {
        return;
      }
      if (lastDownEl === target) {
        return;
      }
      oldIndex = index(target);
      oldDraggableIndex = index(target, options.draggable);
      if (typeof filter === "function") {
        if (filter.call(this, evt, target, this)) {
          _dispatchEvent({
            sortable: _this,
            rootEl: originalTarget,
            name: "filter",
            targetEl: target,
            toEl: el,
            fromEl: el
          });
          pluginEvent2("filter", _this, {
            evt
          });
          preventOnFilter && evt.cancelable && evt.preventDefault();
          return;
        }
      } else if (filter) {
        filter = filter.split(",").some(function(criteria) {
          criteria = closest(originalTarget, criteria.trim(), el, false);
          if (criteria) {
            _dispatchEvent({
              sortable: _this,
              rootEl: criteria,
              name: "filter",
              targetEl: target,
              fromEl: el,
              toEl: el
            });
            pluginEvent2("filter", _this, {
              evt
            });
            return true;
          }
        });
        if (filter) {
          preventOnFilter && evt.cancelable && evt.preventDefault();
          return;
        }
      }
      if (options.handle && !closest(originalTarget, options.handle, el, false)) {
        return;
      }
      this._prepareDragStart(evt, touch, target);
    },
    _prepareDragStart: function _prepareDragStart(evt, touch, target) {
      var _this = this, el = _this.el, options = _this.options, ownerDocument = el.ownerDocument, dragStartFn;
      if (target && !dragEl && target.parentNode === el) {
        var dragRect = getRect(target);
        rootEl = el;
        dragEl = target;
        parentEl = dragEl.parentNode;
        nextEl = dragEl.nextSibling;
        lastDownEl = target;
        activeGroup = options.group;
        Sortable.dragged = dragEl;
        tapEvt = {
          target: dragEl,
          clientX: (touch || evt).clientX,
          clientY: (touch || evt).clientY
        };
        tapDistanceLeft = tapEvt.clientX - dragRect.left;
        tapDistanceTop = tapEvt.clientY - dragRect.top;
        this._lastX = (touch || evt).clientX;
        this._lastY = (touch || evt).clientY;
        dragEl.style["will-change"] = "all";
        dragStartFn = function dragStartFn2() {
          pluginEvent2("delayEnded", _this, {
            evt
          });
          if (Sortable.eventCanceled) {
            _this._onDrop();
            return;
          }
          _this._disableDelayedDragEvents();
          if (!FireFox && _this.nativeDraggable) {
            dragEl.draggable = true;
          }
          _this._triggerDragStart(evt, touch);
          _dispatchEvent({
            sortable: _this,
            name: "choose",
            originalEvent: evt
          });
          toggleClass(dragEl, options.chosenClass, true);
        };
        options.ignore.split(",").forEach(function(criteria) {
          find(dragEl, criteria.trim(), _disableDraggable);
        });
        on(ownerDocument, "dragover", nearestEmptyInsertDetectEvent);
        on(ownerDocument, "mousemove", nearestEmptyInsertDetectEvent);
        on(ownerDocument, "touchmove", nearestEmptyInsertDetectEvent);
        on(ownerDocument, "mouseup", _this._onDrop);
        on(ownerDocument, "touchend", _this._onDrop);
        on(ownerDocument, "touchcancel", _this._onDrop);
        if (FireFox && this.nativeDraggable) {
          this.options.touchStartThreshold = 4;
          dragEl.draggable = true;
        }
        pluginEvent2("delayStart", this, {
          evt
        });
        if (options.delay && (!options.delayOnTouchOnly || touch) && (!this.nativeDraggable || !(Edge || IE11OrLess))) {
          if (Sortable.eventCanceled) {
            this._onDrop();
            return;
          }
          on(ownerDocument, "mouseup", _this._disableDelayedDrag);
          on(ownerDocument, "touchend", _this._disableDelayedDrag);
          on(ownerDocument, "touchcancel", _this._disableDelayedDrag);
          on(ownerDocument, "mousemove", _this._delayedDragTouchMoveHandler);
          on(ownerDocument, "touchmove", _this._delayedDragTouchMoveHandler);
          options.supportPointer && on(ownerDocument, "pointermove", _this._delayedDragTouchMoveHandler);
          _this._dragStartTimer = setTimeout(dragStartFn, options.delay);
        } else {
          dragStartFn();
        }
      }
    },
    _delayedDragTouchMoveHandler: function _delayedDragTouchMoveHandler(e) {
      var touch = e.touches ? e.touches[0] : e;
      if (Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1))) {
        this._disableDelayedDrag();
      }
    },
    _disableDelayedDrag: function _disableDelayedDrag() {
      dragEl && _disableDraggable(dragEl);
      clearTimeout(this._dragStartTimer);
      this._disableDelayedDragEvents();
    },
    _disableDelayedDragEvents: function _disableDelayedDragEvents() {
      var ownerDocument = this.el.ownerDocument;
      off(ownerDocument, "mouseup", this._disableDelayedDrag);
      off(ownerDocument, "touchend", this._disableDelayedDrag);
      off(ownerDocument, "touchcancel", this._disableDelayedDrag);
      off(ownerDocument, "mousemove", this._delayedDragTouchMoveHandler);
      off(ownerDocument, "touchmove", this._delayedDragTouchMoveHandler);
      off(ownerDocument, "pointermove", this._delayedDragTouchMoveHandler);
    },
    _triggerDragStart: function _triggerDragStart(evt, touch) {
      touch = touch || evt.pointerType == "touch" && evt;
      if (!this.nativeDraggable || touch) {
        if (this.options.supportPointer) {
          on(document, "pointermove", this._onTouchMove);
        } else if (touch) {
          on(document, "touchmove", this._onTouchMove);
        } else {
          on(document, "mousemove", this._onTouchMove);
        }
      } else {
        on(dragEl, "dragend", this);
        on(rootEl, "dragstart", this._onDragStart);
      }
      try {
        if (document.selection) {
          _nextTick(function() {
            document.selection.empty();
          });
        } else {
          window.getSelection().removeAllRanges();
        }
      } catch (err) {
      }
    },
    _dragStarted: function _dragStarted(fallback, evt) {
      awaitingDragStarted = false;
      if (rootEl && dragEl) {
        pluginEvent2("dragStarted", this, {
          evt
        });
        if (this.nativeDraggable) {
          on(document, "dragover", _checkOutsideTargetEl);
        }
        var options = this.options;
        !fallback && toggleClass(dragEl, options.dragClass, false);
        toggleClass(dragEl, options.ghostClass, true);
        Sortable.active = this;
        fallback && this._appendGhost();
        _dispatchEvent({
          sortable: this,
          name: "start",
          originalEvent: evt
        });
      } else {
        this._nulling();
      }
    },
    _emulateDragOver: function _emulateDragOver() {
      if (touchEvt) {
        this._lastX = touchEvt.clientX;
        this._lastY = touchEvt.clientY;
        _hideGhostForTarget();
        var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
        var parent = target;
        while (target && target.shadowRoot) {
          target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
          if (target === parent)
            break;
          parent = target;
        }
        dragEl.parentNode[expando]._isOutsideThisEl(target);
        if (parent) {
          do {
            if (parent[expando]) {
              var inserted = void 0;
              inserted = parent[expando]._onDragOver({
                clientX: touchEvt.clientX,
                clientY: touchEvt.clientY,
                target,
                rootEl: parent
              });
              if (inserted && !this.options.dragoverBubble) {
                break;
              }
            }
            target = parent;
          } while (parent = parent.parentNode);
        }
        _unhideGhostForTarget();
      }
    },
    _onTouchMove: function _onTouchMove(evt) {
      if (tapEvt) {
        var options = this.options, fallbackTolerance = options.fallbackTolerance, fallbackOffset = options.fallbackOffset, touch = evt.touches ? evt.touches[0] : evt, ghostMatrix = ghostEl && matrix(ghostEl, true), scaleX = ghostEl && ghostMatrix && ghostMatrix.a, scaleY = ghostEl && ghostMatrix && ghostMatrix.d, relativeScrollOffset = PositionGhostAbsolutely && ghostRelativeParent && getRelativeScrollOffset(ghostRelativeParent), dx = (touch.clientX - tapEvt.clientX + fallbackOffset.x) / (scaleX || 1) + (relativeScrollOffset ? relativeScrollOffset[0] - ghostRelativeParentInitialScroll[0] : 0) / (scaleX || 1), dy = (touch.clientY - tapEvt.clientY + fallbackOffset.y) / (scaleY || 1) + (relativeScrollOffset ? relativeScrollOffset[1] - ghostRelativeParentInitialScroll[1] : 0) / (scaleY || 1);
        if (!Sortable.active && !awaitingDragStarted) {
          if (fallbackTolerance && Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) < fallbackTolerance) {
            return;
          }
          this._onDragStart(evt, true);
        }
        if (ghostEl) {
          if (ghostMatrix) {
            ghostMatrix.e += dx - (lastDx || 0);
            ghostMatrix.f += dy - (lastDy || 0);
          } else {
            ghostMatrix = {
              a: 1,
              b: 0,
              c: 0,
              d: 1,
              e: dx,
              f: dy
            };
          }
          var cssMatrix = "matrix(".concat(ghostMatrix.a, ",").concat(ghostMatrix.b, ",").concat(ghostMatrix.c, ",").concat(ghostMatrix.d, ",").concat(ghostMatrix.e, ",").concat(ghostMatrix.f, ")");
          css(ghostEl, "webkitTransform", cssMatrix);
          css(ghostEl, "mozTransform", cssMatrix);
          css(ghostEl, "msTransform", cssMatrix);
          css(ghostEl, "transform", cssMatrix);
          lastDx = dx;
          lastDy = dy;
          touchEvt = touch;
        }
        evt.cancelable && evt.preventDefault();
      }
    },
    _appendGhost: function _appendGhost() {
      if (!ghostEl) {
        var container = this.options.fallbackOnBody ? document.body : rootEl, rect = getRect(dragEl, true, PositionGhostAbsolutely, true, container), options = this.options;
        if (PositionGhostAbsolutely) {
          ghostRelativeParent = container;
          while (css(ghostRelativeParent, "position") === "static" && css(ghostRelativeParent, "transform") === "none" && ghostRelativeParent !== document) {
            ghostRelativeParent = ghostRelativeParent.parentNode;
          }
          if (ghostRelativeParent !== document.body && ghostRelativeParent !== document.documentElement) {
            if (ghostRelativeParent === document)
              ghostRelativeParent = getWindowScrollingElement();
            rect.top += ghostRelativeParent.scrollTop;
            rect.left += ghostRelativeParent.scrollLeft;
          } else {
            ghostRelativeParent = getWindowScrollingElement();
          }
          ghostRelativeParentInitialScroll = getRelativeScrollOffset(ghostRelativeParent);
        }
        ghostEl = dragEl.cloneNode(true);
        toggleClass(ghostEl, options.ghostClass, false);
        toggleClass(ghostEl, options.fallbackClass, true);
        toggleClass(ghostEl, options.dragClass, true);
        css(ghostEl, "transition", "");
        css(ghostEl, "transform", "");
        css(ghostEl, "box-sizing", "border-box");
        css(ghostEl, "margin", 0);
        css(ghostEl, "top", rect.top);
        css(ghostEl, "left", rect.left);
        css(ghostEl, "width", rect.width);
        css(ghostEl, "height", rect.height);
        css(ghostEl, "opacity", "0.8");
        css(ghostEl, "position", PositionGhostAbsolutely ? "absolute" : "fixed");
        css(ghostEl, "zIndex", "100000");
        css(ghostEl, "pointerEvents", "none");
        Sortable.ghost = ghostEl;
        container.appendChild(ghostEl);
        css(ghostEl, "transform-origin", tapDistanceLeft / parseInt(ghostEl.style.width) * 100 + "% " + tapDistanceTop / parseInt(ghostEl.style.height) * 100 + "%");
      }
    },
    _onDragStart: function _onDragStart(evt, fallback) {
      var _this = this;
      var dataTransfer = evt.dataTransfer;
      var options = _this.options;
      pluginEvent2("dragStart", this, {
        evt
      });
      if (Sortable.eventCanceled) {
        this._onDrop();
        return;
      }
      pluginEvent2("setupClone", this);
      if (!Sortable.eventCanceled) {
        cloneEl = clone(dragEl);
        cloneEl.removeAttribute("id");
        cloneEl.draggable = false;
        cloneEl.style["will-change"] = "";
        this._hideClone();
        toggleClass(cloneEl, this.options.chosenClass, false);
        Sortable.clone = cloneEl;
      }
      _this.cloneId = _nextTick(function() {
        pluginEvent2("clone", _this);
        if (Sortable.eventCanceled)
          return;
        if (!_this.options.removeCloneOnHide) {
          rootEl.insertBefore(cloneEl, dragEl);
        }
        _this._hideClone();
        _dispatchEvent({
          sortable: _this,
          name: "clone"
        });
      });
      !fallback && toggleClass(dragEl, options.dragClass, true);
      if (fallback) {
        ignoreNextClick = true;
        _this._loopId = setInterval(_this._emulateDragOver, 50);
      } else {
        off(document, "mouseup", _this._onDrop);
        off(document, "touchend", _this._onDrop);
        off(document, "touchcancel", _this._onDrop);
        if (dataTransfer) {
          dataTransfer.effectAllowed = "move";
          options.setData && options.setData.call(_this, dataTransfer, dragEl);
        }
        on(document, "drop", _this);
        css(dragEl, "transform", "translateZ(0)");
      }
      awaitingDragStarted = true;
      _this._dragStartId = _nextTick(_this._dragStarted.bind(_this, fallback, evt));
      on(document, "selectstart", _this);
      moved = true;
      if (Safari) {
        css(document.body, "user-select", "none");
      }
    },
    // Returns true - if no further action is needed (either inserted or another condition)
    _onDragOver: function _onDragOver(evt) {
      var el = this.el, target = evt.target, dragRect, targetRect, revert, options = this.options, group = options.group, activeSortable = Sortable.active, isOwner = activeGroup === group, canSort = options.sort, fromSortable = putSortable || activeSortable, vertical, _this = this, completedFired = false;
      if (_silent)
        return;
      function dragOverEvent(name, extra) {
        pluginEvent2(name, _this, _objectSpread2({
          evt,
          isOwner,
          axis: vertical ? "vertical" : "horizontal",
          revert,
          dragRect,
          targetRect,
          canSort,
          fromSortable,
          target,
          completed,
          onMove: function onMove(target2, after2) {
            return _onMove(rootEl, el, dragEl, dragRect, target2, getRect(target2), evt, after2);
          },
          changed
        }, extra));
      }
      function capture() {
        dragOverEvent("dragOverAnimationCapture");
        _this.captureAnimationState();
        if (_this !== fromSortable) {
          fromSortable.captureAnimationState();
        }
      }
      function completed(insertion) {
        dragOverEvent("dragOverCompleted", {
          insertion
        });
        if (insertion) {
          if (isOwner) {
            activeSortable._hideClone();
          } else {
            activeSortable._showClone(_this);
          }
          if (_this !== fromSortable) {
            toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : activeSortable.options.ghostClass, false);
            toggleClass(dragEl, options.ghostClass, true);
          }
          if (putSortable !== _this && _this !== Sortable.active) {
            putSortable = _this;
          } else if (_this === Sortable.active && putSortable) {
            putSortable = null;
          }
          if (fromSortable === _this) {
            _this._ignoreWhileAnimating = target;
          }
          _this.animateAll(function() {
            dragOverEvent("dragOverAnimationComplete");
            _this._ignoreWhileAnimating = null;
          });
          if (_this !== fromSortable) {
            fromSortable.animateAll();
            fromSortable._ignoreWhileAnimating = null;
          }
        }
        if (target === dragEl && !dragEl.animated || target === el && !target.animated) {
          lastTarget = null;
        }
        if (!options.dragoverBubble && !evt.rootEl && target !== document) {
          dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
          !insertion && nearestEmptyInsertDetectEvent(evt);
        }
        !options.dragoverBubble && evt.stopPropagation && evt.stopPropagation();
        return completedFired = true;
      }
      function changed() {
        newIndex = index(dragEl);
        newDraggableIndex = index(dragEl, options.draggable);
        _dispatchEvent({
          sortable: _this,
          name: "change",
          toEl: el,
          newIndex,
          newDraggableIndex,
          originalEvent: evt
        });
      }
      if (evt.preventDefault !== void 0) {
        evt.cancelable && evt.preventDefault();
      }
      target = closest(target, options.draggable, el, true);
      dragOverEvent("dragOver");
      if (Sortable.eventCanceled)
        return completedFired;
      if (dragEl.contains(evt.target) || target.animated && target.animatingX && target.animatingY || _this._ignoreWhileAnimating === target) {
        return completed(false);
      }
      ignoreNextClick = false;
      if (activeSortable && !options.disabled && (isOwner ? canSort || (revert = parentEl !== rootEl) : putSortable === this || (this.lastPutMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) && group.checkPut(this, activeSortable, dragEl, evt))) {
        vertical = this._getDirection(evt, target) === "vertical";
        dragRect = getRect(dragEl);
        dragOverEvent("dragOverValid");
        if (Sortable.eventCanceled)
          return completedFired;
        if (revert) {
          parentEl = rootEl;
          capture();
          this._hideClone();
          dragOverEvent("revert");
          if (!Sortable.eventCanceled) {
            if (nextEl) {
              rootEl.insertBefore(dragEl, nextEl);
            } else {
              rootEl.appendChild(dragEl);
            }
          }
          return completed(true);
        }
        var elLastChild = lastChild(el, options.draggable);
        if (!elLastChild || _ghostIsLast(evt, vertical, this) && !elLastChild.animated) {
          if (elLastChild === dragEl) {
            return completed(false);
          }
          if (elLastChild && el === evt.target) {
            target = elLastChild;
          }
          if (target) {
            targetRect = getRect(target);
          }
          if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, !!target) !== false) {
            capture();
            if (elLastChild && elLastChild.nextSibling) {
              el.insertBefore(dragEl, elLastChild.nextSibling);
            } else {
              el.appendChild(dragEl);
            }
            parentEl = el;
            changed();
            return completed(true);
          }
        } else if (elLastChild && _ghostIsFirst(evt, vertical, this)) {
          var firstChild = getChild(el, 0, options, true);
          if (firstChild === dragEl) {
            return completed(false);
          }
          target = firstChild;
          targetRect = getRect(target);
          if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, false) !== false) {
            capture();
            el.insertBefore(dragEl, firstChild);
            parentEl = el;
            changed();
            return completed(true);
          }
        } else if (target.parentNode === el) {
          targetRect = getRect(target);
          var direction = 0, targetBeforeFirstSwap, differentLevel = dragEl.parentNode !== el, differentRowCol = !_dragElInRowColumn(dragEl.animated && dragEl.toRect || dragRect, target.animated && target.toRect || targetRect, vertical), side1 = vertical ? "top" : "left", scrolledPastTop = isScrolledPast(target, "top", "top") || isScrolledPast(dragEl, "top", "top"), scrollBefore = scrolledPastTop ? scrolledPastTop.scrollTop : void 0;
          if (lastTarget !== target) {
            targetBeforeFirstSwap = targetRect[side1];
            pastFirstInvertThresh = false;
            isCircumstantialInvert = !differentRowCol && options.invertSwap || differentLevel;
          }
          direction = _getSwapDirection(evt, target, targetRect, vertical, differentRowCol ? 1 : options.swapThreshold, options.invertedSwapThreshold == null ? options.swapThreshold : options.invertedSwapThreshold, isCircumstantialInvert, lastTarget === target);
          var sibling;
          if (direction !== 0) {
            var dragIndex = index(dragEl);
            do {
              dragIndex -= direction;
              sibling = parentEl.children[dragIndex];
            } while (sibling && (css(sibling, "display") === "none" || sibling === ghostEl));
          }
          if (direction === 0 || sibling === target) {
            return completed(false);
          }
          lastTarget = target;
          lastDirection = direction;
          var nextSibling = target.nextElementSibling, after = false;
          after = direction === 1;
          var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);
          if (moveVector !== false) {
            if (moveVector === 1 || moveVector === -1) {
              after = moveVector === 1;
            }
            _silent = true;
            setTimeout(_unsilent, 30);
            capture();
            if (after && !nextSibling) {
              el.appendChild(dragEl);
            } else {
              target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
            }
            if (scrolledPastTop) {
              scrollBy(scrolledPastTop, 0, scrollBefore - scrolledPastTop.scrollTop);
            }
            parentEl = dragEl.parentNode;
            if (targetBeforeFirstSwap !== void 0 && !isCircumstantialInvert) {
              targetMoveDistance = Math.abs(targetBeforeFirstSwap - getRect(target)[side1]);
            }
            changed();
            return completed(true);
          }
        }
        if (el.contains(dragEl)) {
          return completed(false);
        }
      }
      return false;
    },
    _ignoreWhileAnimating: null,
    _offMoveEvents: function _offMoveEvents() {
      off(document, "mousemove", this._onTouchMove);
      off(document, "touchmove", this._onTouchMove);
      off(document, "pointermove", this._onTouchMove);
      off(document, "dragover", nearestEmptyInsertDetectEvent);
      off(document, "mousemove", nearestEmptyInsertDetectEvent);
      off(document, "touchmove", nearestEmptyInsertDetectEvent);
    },
    _offUpEvents: function _offUpEvents() {
      var ownerDocument = this.el.ownerDocument;
      off(ownerDocument, "mouseup", this._onDrop);
      off(ownerDocument, "touchend", this._onDrop);
      off(ownerDocument, "pointerup", this._onDrop);
      off(ownerDocument, "touchcancel", this._onDrop);
      off(document, "selectstart", this);
    },
    _onDrop: function _onDrop(evt) {
      var el = this.el, options = this.options;
      newIndex = index(dragEl);
      newDraggableIndex = index(dragEl, options.draggable);
      pluginEvent2("drop", this, {
        evt
      });
      parentEl = dragEl && dragEl.parentNode;
      newIndex = index(dragEl);
      newDraggableIndex = index(dragEl, options.draggable);
      if (Sortable.eventCanceled) {
        this._nulling();
        return;
      }
      awaitingDragStarted = false;
      isCircumstantialInvert = false;
      pastFirstInvertThresh = false;
      clearInterval(this._loopId);
      clearTimeout(this._dragStartTimer);
      _cancelNextTick(this.cloneId);
      _cancelNextTick(this._dragStartId);
      if (this.nativeDraggable) {
        off(document, "drop", this);
        off(el, "dragstart", this._onDragStart);
      }
      this._offMoveEvents();
      this._offUpEvents();
      if (Safari) {
        css(document.body, "user-select", "");
      }
      css(dragEl, "transform", "");
      if (evt) {
        if (moved) {
          evt.cancelable && evt.preventDefault();
          !options.dropBubble && evt.stopPropagation();
        }
        ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);
        if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== "clone") {
          cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
        }
        if (dragEl) {
          if (this.nativeDraggable) {
            off(dragEl, "dragend", this);
          }
          _disableDraggable(dragEl);
          dragEl.style["will-change"] = "";
          if (moved && !awaitingDragStarted) {
            toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : this.options.ghostClass, false);
          }
          toggleClass(dragEl, this.options.chosenClass, false);
          _dispatchEvent({
            sortable: this,
            name: "unchoose",
            toEl: parentEl,
            newIndex: null,
            newDraggableIndex: null,
            originalEvent: evt
          });
          if (rootEl !== parentEl) {
            if (newIndex >= 0) {
              _dispatchEvent({
                rootEl: parentEl,
                name: "add",
                toEl: parentEl,
                fromEl: rootEl,
                originalEvent: evt
              });
              _dispatchEvent({
                sortable: this,
                name: "remove",
                toEl: parentEl,
                originalEvent: evt
              });
              _dispatchEvent({
                rootEl: parentEl,
                name: "sort",
                toEl: parentEl,
                fromEl: rootEl,
                originalEvent: evt
              });
              _dispatchEvent({
                sortable: this,
                name: "sort",
                toEl: parentEl,
                originalEvent: evt
              });
            }
            putSortable && putSortable.save();
          } else {
            if (newIndex !== oldIndex) {
              if (newIndex >= 0) {
                _dispatchEvent({
                  sortable: this,
                  name: "update",
                  toEl: parentEl,
                  originalEvent: evt
                });
                _dispatchEvent({
                  sortable: this,
                  name: "sort",
                  toEl: parentEl,
                  originalEvent: evt
                });
              }
            }
          }
          if (Sortable.active) {
            if (newIndex == null || newIndex === -1) {
              newIndex = oldIndex;
              newDraggableIndex = oldDraggableIndex;
            }
            _dispatchEvent({
              sortable: this,
              name: "end",
              toEl: parentEl,
              originalEvent: evt
            });
            this.save();
          }
        }
      }
      this._nulling();
    },
    _nulling: function _nulling() {
      pluginEvent2("nulling", this);
      rootEl = dragEl = parentEl = ghostEl = nextEl = cloneEl = lastDownEl = cloneHidden = tapEvt = touchEvt = moved = newIndex = newDraggableIndex = oldIndex = oldDraggableIndex = lastTarget = lastDirection = putSortable = activeGroup = Sortable.dragged = Sortable.ghost = Sortable.clone = Sortable.active = null;
      savedInputChecked.forEach(function(el) {
        el.checked = true;
      });
      savedInputChecked.length = lastDx = lastDy = 0;
    },
    handleEvent: function handleEvent(evt) {
      switch (evt.type) {
        case "drop":
        case "dragend":
          this._onDrop(evt);
          break;
        case "dragenter":
        case "dragover":
          if (dragEl) {
            this._onDragOver(evt);
            _globalDragOver(evt);
          }
          break;
        case "selectstart":
          evt.preventDefault();
          break;
      }
    },
    /**
     * Serializes the item into an array of string.
     * @returns {String[]}
     */
    toArray: function toArray() {
      var order = [], el, children = this.el.children, i = 0, n2 = children.length, options = this.options;
      for (; i < n2; i++) {
        el = children[i];
        if (closest(el, options.draggable, this.el, false)) {
          order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
        }
      }
      return order;
    },
    /**
     * Sorts the elements according to the array.
     * @param  {String[]}  order  order of the items
     */
    sort: function sort(order, useAnimation) {
      var items = {}, rootEl2 = this.el;
      this.toArray().forEach(function(id, i) {
        var el = rootEl2.children[i];
        if (closest(el, this.options.draggable, rootEl2, false)) {
          items[id] = el;
        }
      }, this);
      useAnimation && this.captureAnimationState();
      order.forEach(function(id) {
        if (items[id]) {
          rootEl2.removeChild(items[id]);
          rootEl2.appendChild(items[id]);
        }
      });
      useAnimation && this.animateAll();
    },
    /**
     * Save the current sorting
     */
    save: function save() {
      var store = this.options.store;
      store && store.set && store.set(this);
    },
    /**
     * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
     * @param   {HTMLElement}  el
     * @param   {String}       [selector]  default: `options.draggable`
     * @returns {HTMLElement|null}
     */
    closest: function closest$1(el, selector) {
      return closest(el, selector || this.options.draggable, this.el, false);
    },
    /**
     * Set/get option
     * @param   {string} name
     * @param   {*}      [value]
     * @returns {*}
     */
    option: function option(name, value) {
      var options = this.options;
      if (value === void 0) {
        return options[name];
      } else {
        var modifiedValue = PluginManager.modifyOption(this, name, value);
        if (typeof modifiedValue !== "undefined") {
          options[name] = modifiedValue;
        } else {
          options[name] = value;
        }
        if (name === "group") {
          _prepareGroup(options);
        }
      }
    },
    /**
     * Destroy
     */
    destroy: function destroy() {
      pluginEvent2("destroy", this);
      var el = this.el;
      el[expando] = null;
      off(el, "mousedown", this._onTapStart);
      off(el, "touchstart", this._onTapStart);
      off(el, "pointerdown", this._onTapStart);
      if (this.nativeDraggable) {
        off(el, "dragover", this);
        off(el, "dragenter", this);
      }
      Array.prototype.forEach.call(el.querySelectorAll("[draggable]"), function(el2) {
        el2.removeAttribute("draggable");
      });
      this._onDrop();
      this._disableDelayedDragEvents();
      sortables.splice(sortables.indexOf(this.el), 1);
      this.el = el = null;
    },
    _hideClone: function _hideClone() {
      if (!cloneHidden) {
        pluginEvent2("hideClone", this);
        if (Sortable.eventCanceled)
          return;
        css(cloneEl, "display", "none");
        if (this.options.removeCloneOnHide && cloneEl.parentNode) {
          cloneEl.parentNode.removeChild(cloneEl);
        }
        cloneHidden = true;
      }
    },
    _showClone: function _showClone(putSortable2) {
      if (putSortable2.lastPutMode !== "clone") {
        this._hideClone();
        return;
      }
      if (cloneHidden) {
        pluginEvent2("showClone", this);
        if (Sortable.eventCanceled)
          return;
        if (dragEl.parentNode == rootEl && !this.options.group.revertClone) {
          rootEl.insertBefore(cloneEl, dragEl);
        } else if (nextEl) {
          rootEl.insertBefore(cloneEl, nextEl);
        } else {
          rootEl.appendChild(cloneEl);
        }
        if (this.options.group.revertClone) {
          this.animate(dragEl, cloneEl);
        }
        css(cloneEl, "display", "");
        cloneHidden = false;
      }
    }
  };
  function _globalDragOver(evt) {
    if (evt.dataTransfer) {
      evt.dataTransfer.dropEffect = "move";
    }
    evt.cancelable && evt.preventDefault();
  }
  function _onMove(fromEl, toEl, dragEl2, dragRect, targetEl, targetRect, originalEvent, willInsertAfter) {
    var evt, sortable = fromEl[expando], onMoveFn = sortable.options.onMove, retVal;
    if (window.CustomEvent && !IE11OrLess && !Edge) {
      evt = new CustomEvent("move", {
        bubbles: true,
        cancelable: true
      });
    } else {
      evt = document.createEvent("Event");
      evt.initEvent("move", true, true);
    }
    evt.to = toEl;
    evt.from = fromEl;
    evt.dragged = dragEl2;
    evt.draggedRect = dragRect;
    evt.related = targetEl || toEl;
    evt.relatedRect = targetRect || getRect(toEl);
    evt.willInsertAfter = willInsertAfter;
    evt.originalEvent = originalEvent;
    fromEl.dispatchEvent(evt);
    if (onMoveFn) {
      retVal = onMoveFn.call(sortable, evt, originalEvent);
    }
    return retVal;
  }
  function _disableDraggable(el) {
    el.draggable = false;
  }
  function _unsilent() {
    _silent = false;
  }
  function _ghostIsFirst(evt, vertical, sortable) {
    var firstElRect = getRect(getChild(sortable.el, 0, sortable.options, true));
    var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
    var spacer = 10;
    return vertical ? evt.clientX < childContainingRect.left - spacer || evt.clientY < firstElRect.top && evt.clientX < firstElRect.right : evt.clientY < childContainingRect.top - spacer || evt.clientY < firstElRect.bottom && evt.clientX < firstElRect.left;
  }
  function _ghostIsLast(evt, vertical, sortable) {
    var lastElRect = getRect(lastChild(sortable.el, sortable.options.draggable));
    var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
    var spacer = 10;
    return vertical ? evt.clientX > childContainingRect.right + spacer || evt.clientY > lastElRect.bottom && evt.clientX > lastElRect.left : evt.clientY > childContainingRect.bottom + spacer || evt.clientX > lastElRect.right && evt.clientY > lastElRect.top;
  }
  function _getSwapDirection(evt, target, targetRect, vertical, swapThreshold, invertedSwapThreshold, invertSwap, isLastTarget) {
    var mouseOnAxis = vertical ? evt.clientY : evt.clientX, targetLength = vertical ? targetRect.height : targetRect.width, targetS1 = vertical ? targetRect.top : targetRect.left, targetS2 = vertical ? targetRect.bottom : targetRect.right, invert = false;
    if (!invertSwap) {
      if (isLastTarget && targetMoveDistance < targetLength * swapThreshold) {
        if (!pastFirstInvertThresh && (lastDirection === 1 ? mouseOnAxis > targetS1 + targetLength * invertedSwapThreshold / 2 : mouseOnAxis < targetS2 - targetLength * invertedSwapThreshold / 2)) {
          pastFirstInvertThresh = true;
        }
        if (!pastFirstInvertThresh) {
          if (lastDirection === 1 ? mouseOnAxis < targetS1 + targetMoveDistance : mouseOnAxis > targetS2 - targetMoveDistance) {
            return -lastDirection;
          }
        } else {
          invert = true;
        }
      } else {
        if (mouseOnAxis > targetS1 + targetLength * (1 - swapThreshold) / 2 && mouseOnAxis < targetS2 - targetLength * (1 - swapThreshold) / 2) {
          return _getInsertDirection(target);
        }
      }
    }
    invert = invert || invertSwap;
    if (invert) {
      if (mouseOnAxis < targetS1 + targetLength * invertedSwapThreshold / 2 || mouseOnAxis > targetS2 - targetLength * invertedSwapThreshold / 2) {
        return mouseOnAxis > targetS1 + targetLength / 2 ? 1 : -1;
      }
    }
    return 0;
  }
  function _getInsertDirection(target) {
    if (index(dragEl) < index(target)) {
      return 1;
    } else {
      return -1;
    }
  }
  function _generateId(el) {
    var str = el.tagName + el.className + el.src + el.href + el.textContent, i = str.length, sum = 0;
    while (i--) {
      sum += str.charCodeAt(i);
    }
    return sum.toString(36);
  }
  function _saveInputCheckedState(root) {
    savedInputChecked.length = 0;
    var inputs = root.getElementsByTagName("input");
    var idx = inputs.length;
    while (idx--) {
      var el = inputs[idx];
      el.checked && savedInputChecked.push(el);
    }
  }
  function _nextTick(fn) {
    return setTimeout(fn, 0);
  }
  function _cancelNextTick(id) {
    return clearTimeout(id);
  }
  if (documentExists) {
    on(document, "touchmove", function(evt) {
      if ((Sortable.active || awaitingDragStarted) && evt.cancelable) {
        evt.preventDefault();
      }
    });
  }
  Sortable.utils = {
    on,
    off,
    css,
    find,
    is: function is2(el, selector) {
      return !!closest(el, selector, el, false);
    },
    extend,
    throttle,
    closest,
    toggleClass,
    clone,
    index,
    nextTick: _nextTick,
    cancelNextTick: _cancelNextTick,
    detectDirection: _detectDirection,
    getChild
  };
  Sortable.get = function(element) {
    return element[expando];
  };
  Sortable.mount = function() {
    for (var _len = arguments.length, plugins2 = new Array(_len), _key = 0; _key < _len; _key++) {
      plugins2[_key] = arguments[_key];
    }
    if (plugins2[0].constructor === Array)
      plugins2 = plugins2[0];
    plugins2.forEach(function(plugin) {
      if (!plugin.prototype || !plugin.prototype.constructor) {
        throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(plugin));
      }
      if (plugin.utils)
        Sortable.utils = _objectSpread2(_objectSpread2({}, Sortable.utils), plugin.utils);
      PluginManager.mount(plugin);
    });
  };
  Sortable.create = function(el, options) {
    return new Sortable(el, options);
  };
  Sortable.version = version;
  var autoScrolls = [];
  var scrollEl;
  var scrollRootEl;
  var scrolling = false;
  var lastAutoScrollX;
  var lastAutoScrollY;
  var touchEvt$1;
  var pointerElemChangedInterval;
  function AutoScrollPlugin() {
    function AutoScroll() {
      this.defaults = {
        scroll: true,
        forceAutoScrollFallback: false,
        scrollSensitivity: 30,
        scrollSpeed: 10,
        bubbleScroll: true
      };
      for (var fn in this) {
        if (fn.charAt(0) === "_" && typeof this[fn] === "function") {
          this[fn] = this[fn].bind(this);
        }
      }
    }
    AutoScroll.prototype = {
      dragStarted: function dragStarted(_ref) {
        var originalEvent = _ref.originalEvent;
        if (this.sortable.nativeDraggable) {
          on(document, "dragover", this._handleAutoScroll);
        } else {
          if (this.options.supportPointer) {
            on(document, "pointermove", this._handleFallbackAutoScroll);
          } else if (originalEvent.touches) {
            on(document, "touchmove", this._handleFallbackAutoScroll);
          } else {
            on(document, "mousemove", this._handleFallbackAutoScroll);
          }
        }
      },
      dragOverCompleted: function dragOverCompleted(_ref2) {
        var originalEvent = _ref2.originalEvent;
        if (!this.options.dragOverBubble && !originalEvent.rootEl) {
          this._handleAutoScroll(originalEvent);
        }
      },
      drop: function drop3() {
        if (this.sortable.nativeDraggable) {
          off(document, "dragover", this._handleAutoScroll);
        } else {
          off(document, "pointermove", this._handleFallbackAutoScroll);
          off(document, "touchmove", this._handleFallbackAutoScroll);
          off(document, "mousemove", this._handleFallbackAutoScroll);
        }
        clearPointerElemChangedInterval();
        clearAutoScrolls();
        cancelThrottle();
      },
      nulling: function nulling() {
        touchEvt$1 = scrollRootEl = scrollEl = scrolling = pointerElemChangedInterval = lastAutoScrollX = lastAutoScrollY = null;
        autoScrolls.length = 0;
      },
      _handleFallbackAutoScroll: function _handleFallbackAutoScroll(evt) {
        this._handleAutoScroll(evt, true);
      },
      _handleAutoScroll: function _handleAutoScroll(evt, fallback) {
        var _this = this;
        var x = (evt.touches ? evt.touches[0] : evt).clientX, y = (evt.touches ? evt.touches[0] : evt).clientY, elem = document.elementFromPoint(x, y);
        touchEvt$1 = evt;
        if (fallback || this.options.forceAutoScrollFallback || Edge || IE11OrLess || Safari) {
          autoScroll(evt, this.options, elem, fallback);
          var ogElemScroller = getParentAutoScrollElement(elem, true);
          if (scrolling && (!pointerElemChangedInterval || x !== lastAutoScrollX || y !== lastAutoScrollY)) {
            pointerElemChangedInterval && clearPointerElemChangedInterval();
            pointerElemChangedInterval = setInterval(function() {
              var newElem = getParentAutoScrollElement(document.elementFromPoint(x, y), true);
              if (newElem !== ogElemScroller) {
                ogElemScroller = newElem;
                clearAutoScrolls();
              }
              autoScroll(evt, _this.options, newElem, fallback);
            }, 10);
            lastAutoScrollX = x;
            lastAutoScrollY = y;
          }
        } else {
          if (!this.options.bubbleScroll || getParentAutoScrollElement(elem, true) === getWindowScrollingElement()) {
            clearAutoScrolls();
            return;
          }
          autoScroll(evt, this.options, getParentAutoScrollElement(elem, false), false);
        }
      }
    };
    return _extends(AutoScroll, {
      pluginName: "scroll",
      initializeByDefault: true
    });
  }
  function clearAutoScrolls() {
    autoScrolls.forEach(function(autoScroll2) {
      clearInterval(autoScroll2.pid);
    });
    autoScrolls = [];
  }
  function clearPointerElemChangedInterval() {
    clearInterval(pointerElemChangedInterval);
  }
  var autoScroll = throttle(function(evt, options, rootEl2, isFallback) {
    if (!options.scroll)
      return;
    var x = (evt.touches ? evt.touches[0] : evt).clientX, y = (evt.touches ? evt.touches[0] : evt).clientY, sens = options.scrollSensitivity, speed = options.scrollSpeed, winScroller = getWindowScrollingElement();
    var scrollThisInstance = false, scrollCustomFn;
    if (scrollRootEl !== rootEl2) {
      scrollRootEl = rootEl2;
      clearAutoScrolls();
      scrollEl = options.scroll;
      scrollCustomFn = options.scrollFn;
      if (scrollEl === true) {
        scrollEl = getParentAutoScrollElement(rootEl2, true);
      }
    }
    var layersOut = 0;
    var currentParent = scrollEl;
    do {
      var el = currentParent, rect = getRect(el), top = rect.top, bottom = rect.bottom, left = rect.left, right = rect.right, width = rect.width, height = rect.height, canScrollX = void 0, canScrollY = void 0, scrollWidth = el.scrollWidth, scrollHeight = el.scrollHeight, elCSS = css(el), scrollPosX = el.scrollLeft, scrollPosY = el.scrollTop;
      if (el === winScroller) {
        canScrollX = width < scrollWidth && (elCSS.overflowX === "auto" || elCSS.overflowX === "scroll" || elCSS.overflowX === "visible");
        canScrollY = height < scrollHeight && (elCSS.overflowY === "auto" || elCSS.overflowY === "scroll" || elCSS.overflowY === "visible");
      } else {
        canScrollX = width < scrollWidth && (elCSS.overflowX === "auto" || elCSS.overflowX === "scroll");
        canScrollY = height < scrollHeight && (elCSS.overflowY === "auto" || elCSS.overflowY === "scroll");
      }
      var vx = canScrollX && (Math.abs(right - x) <= sens && scrollPosX + width < scrollWidth) - (Math.abs(left - x) <= sens && !!scrollPosX);
      var vy = canScrollY && (Math.abs(bottom - y) <= sens && scrollPosY + height < scrollHeight) - (Math.abs(top - y) <= sens && !!scrollPosY);
      if (!autoScrolls[layersOut]) {
        for (var i = 0; i <= layersOut; i++) {
          if (!autoScrolls[i]) {
            autoScrolls[i] = {};
          }
        }
      }
      if (autoScrolls[layersOut].vx != vx || autoScrolls[layersOut].vy != vy || autoScrolls[layersOut].el !== el) {
        autoScrolls[layersOut].el = el;
        autoScrolls[layersOut].vx = vx;
        autoScrolls[layersOut].vy = vy;
        clearInterval(autoScrolls[layersOut].pid);
        if (vx != 0 || vy != 0) {
          scrollThisInstance = true;
          autoScrolls[layersOut].pid = setInterval(function() {
            if (isFallback && this.layer === 0) {
              Sortable.active._onTouchMove(touchEvt$1);
            }
            var scrollOffsetY = autoScrolls[this.layer].vy ? autoScrolls[this.layer].vy * speed : 0;
            var scrollOffsetX = autoScrolls[this.layer].vx ? autoScrolls[this.layer].vx * speed : 0;
            if (typeof scrollCustomFn === "function") {
              if (scrollCustomFn.call(Sortable.dragged.parentNode[expando], scrollOffsetX, scrollOffsetY, evt, touchEvt$1, autoScrolls[this.layer].el) !== "continue") {
                return;
              }
            }
            scrollBy(autoScrolls[this.layer].el, scrollOffsetX, scrollOffsetY);
          }.bind({
            layer: layersOut
          }), 24);
        }
      }
      layersOut++;
    } while (options.bubbleScroll && currentParent !== winScroller && (currentParent = getParentAutoScrollElement(currentParent, false)));
    scrolling = scrollThisInstance;
  }, 30);
  var drop = function drop2(_ref) {
    var originalEvent = _ref.originalEvent, putSortable2 = _ref.putSortable, dragEl2 = _ref.dragEl, activeSortable = _ref.activeSortable, dispatchSortableEvent = _ref.dispatchSortableEvent, hideGhostForTarget = _ref.hideGhostForTarget, unhideGhostForTarget = _ref.unhideGhostForTarget;
    if (!originalEvent)
      return;
    var toSortable = putSortable2 || activeSortable;
    hideGhostForTarget();
    var touch = originalEvent.changedTouches && originalEvent.changedTouches.length ? originalEvent.changedTouches[0] : originalEvent;
    var target = document.elementFromPoint(touch.clientX, touch.clientY);
    unhideGhostForTarget();
    if (toSortable && !toSortable.el.contains(target)) {
      dispatchSortableEvent("spill");
      this.onSpill({
        dragEl: dragEl2,
        putSortable: putSortable2
      });
    }
  };
  function Revert() {
  }
  Revert.prototype = {
    startIndex: null,
    dragStart: function dragStart(_ref2) {
      var oldDraggableIndex2 = _ref2.oldDraggableIndex;
      this.startIndex = oldDraggableIndex2;
    },
    onSpill: function onSpill(_ref3) {
      var dragEl2 = _ref3.dragEl, putSortable2 = _ref3.putSortable;
      this.sortable.captureAnimationState();
      if (putSortable2) {
        putSortable2.captureAnimationState();
      }
      var nextSibling = getChild(this.sortable.el, this.startIndex, this.options);
      if (nextSibling) {
        this.sortable.el.insertBefore(dragEl2, nextSibling);
      } else {
        this.sortable.el.appendChild(dragEl2);
      }
      this.sortable.animateAll();
      if (putSortable2) {
        putSortable2.animateAll();
      }
    },
    drop
  };
  _extends(Revert, {
    pluginName: "revertOnSpill"
  });
  function Remove() {
  }
  Remove.prototype = {
    onSpill: function onSpill2(_ref4) {
      var dragEl2 = _ref4.dragEl, putSortable2 = _ref4.putSortable;
      var parentSortable = putSortable2 || this.sortable;
      parentSortable.captureAnimationState();
      dragEl2.parentNode && dragEl2.parentNode.removeChild(dragEl2);
      parentSortable.animateAll();
    },
    drop
  };
  _extends(Remove, {
    pluginName: "removeOnSpill"
  });
  Sortable.mount(new AutoScrollPlugin());
  Sortable.mount(Remove, Revert);
  var sortable_esm_default = Sortable;

  // resources/js/components/sortable.js
  window.Sortable = sortable_esm_default;
  if (typeof window.Livewire === "undefined") {
    throw "Livewire Sortable Plugin: window.Livewire is undefined. Make sure @livewireScripts is placed above this script include";
  }
  var moveEndMorphMarker = (el) => {
    const endMorphMarker = Array.from(el.childNodes).filter((childNode) => {
      return childNode.nodeType === 8 && ["[if ENDBLOCK]><![endif]", "__ENDBLOCK__"].includes(childNode.nodeValue?.trim());
    })[0];
    if (endMorphMarker) {
      el.appendChild(endMorphMarker);
    }
  };
  Livewire.directive("sortable", ({ el, directive, component }) => {
    if (directive.modifiers.length > 0) {
      return;
    }
    let options = {};
    if (el.hasAttribute("wire:sortable.options")) {
      options = new Function(`return ${el.getAttribute("wire:sortable.options")};`)();
    }
    el.livewire_sortable = window.Sortable.create(el, {
      sort: true,
      ...options,
      draggable: "[wire\\:sortable\\.item]",
      handle: el.querySelector("[wire\\:sortable\\.handle]") ? "[wire\\:sortable\\.handle]" : null,
      dataIdAttr: "wire:sortable.item",
      group: {
        pull: false,
        put: false,
        ...options.group,
        name: el.getAttribute("wire:sortable")
      },
      store: {
        ...options.store,
        set: function(sortable) {
          let items = sortable.toArray().map((value, index2) => {
            return {
              order: index2 + 1,
              value
            };
          });
          moveEndMorphMarker(el);
          component.$wire.call(directive.method, items);
        }
      }
    });
    let hasSetHandleCorrectly = el.querySelector("[wire\\:sortable\\.item]") !== null;
    if (hasSetHandleCorrectly) {
      return;
    }
    const currentComponent = component;
    Livewire.hook("commit", ({ component: component2, succeed }) => {
      if (component2.id !== currentComponent.id) {
        return;
      }
      if (hasSetHandleCorrectly) {
        return;
      }
      succeed(() => {
        queueMicrotask(() => {
          el.livewire_sortable.option("handle", el.querySelector("[wire\\:sortable\\.handle]") ? "[wire\\:sortable\\.handle]" : null);
          hasSetHandleCorrectly = el.querySelector("[wire\\:sortable\\.item]") !== null;
        });
      });
    });
  });
  Livewire.directive("sortable-group", ({ el, directive, component }) => {
    if (!directive.modifiers.includes("item-group")) {
      return;
    }
    let options = {};
    if (el.hasAttribute("wire:sortable-group.options")) {
      options = new Function(`return ${el.getAttribute("wire:sortable-group.options")};`)();
    }
    el.livewire_sortable = window.Sortable.create(el, {
      sort: true,
      ...options,
      draggable: "[wire\\:sortable-group\\.item]",
      handle: "[wire\\:sortable-group\\.handle]",
      dataIdAttr: "wire:sortable-group.item",
      group: {
        pull: true,
        put: true,
        ...options.group,
        name: el.closest("[wire\\:sortable-group]").getAttribute("wire:sortable-group")
      },
      onSort: (evt) => {
        if (evt.to !== evt.from && el === evt.from) {
          return;
        }
        let masterEl = el.closest("[wire\\:sortable-group]");
        let groups = Array.from(masterEl.querySelectorAll("[wire\\:sortable-group\\.item-group]")).map((el2, index2) => {
          moveEndMorphMarker(el2);
          return {
            order: index2 + 1,
            value: el2.getAttribute("wire:sortable-group.item-group"),
            items: el2.livewire_sortable.toArray().map((value, index3) => {
              return {
                order: index3 + 1,
                value
              };
            })
          };
        });
        masterEl.closest("[wire\\:id]").__livewire.$wire.call(masterEl.getAttribute("wire:sortable-group"), groups);
      }
    });
  });

  // resources/js/index.js
  window.SlideOverPanel = panel_default;
  window.selectTree = selectTree;
  document.addEventListener("alpine:init", () => {
    const theme = localStorage.getItem("theme") ?? "system";
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
/*! Bundled license information:

sortablejs/modular/sortable.esm.js:
  (**!
   * Sortable 1.15.2
   * @author	RubaXa   <trash@rubaxa.org>
   * @author	owenm    <owen23355@gmail.com>
   * @license MIT
   *)
*/
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvcGFuZWwuanMiLCAiLi4vbm9kZV9tb2R1bGVzL3RyZWVzZWxlY3Rqcy9kaXN0L3RyZWVzZWxlY3Rqcy5tanMiLCAiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvc2VsZWN0LXRyZWUuanMiLCAiLi4vbm9kZV9tb2R1bGVzL3NvcnRhYmxlanMvbW9kdWxhci9zb3J0YWJsZS5lc20uanMiLCAiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvc29ydGFibGUuanMiLCAiLi4vcmVzb3VyY2VzL2pzL2luZGV4LmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBTbGlkZU92ZXJQYW5lbCA9ICgpID0+IHtcbiAgcmV0dXJuIHtcbiAgICBvcGVuOiBmYWxzZSxcbiAgICBzaG93QWN0aXZlQ29tcG9uZW50OiB0cnVlLFxuICAgIGFjdGl2ZUNvbXBvbmVudDogZmFsc2UsXG4gICAgY29tcG9uZW50SGlzdG9yeTogW10sXG4gICAgcGFuZWxXaWR0aDogbnVsbCxcbiAgICBsaXN0ZW5lcnM6IFtdLFxuICAgIGdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKGtleSkge1xuICAgICAgaWYgKHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdWydwYW5lbEF0dHJpYnV0ZXMnXVtrZXldO1xuICAgICAgfVxuICAgIH0sXG4gICAgY2xvc2VQYW5lbE9uRXNjYXBlKHRyaWdnZXIpIHtcbiAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdjbG9zZU9uRXNjYXBlJykgPT09IGZhbHNlKSB7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgbGV0IGZvcmNlID0gdGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnY2xvc2VPbkVzY2FwZUlzRm9yY2VmdWwnKSA9PT0gdHJ1ZTtcbiAgICAgIHRoaXMuY2xvc2VQYW5lbChmb3JjZSk7XG4gICAgfSxcbiAgICBjbG9zZVBhbmVsT25DbGlja0F3YXkodHJpZ2dlcikge1xuICAgICAgaWYgKHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ2Nsb3NlT25DbGlja0F3YXknKSA9PT0gZmFsc2UpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICB0aGlzLmNsb3NlUGFuZWwodHJ1ZSk7XG4gICAgfSxcbiAgICBjbG9zZVBhbmVsKGZvcmNlID0gZmFsc2UsIHNraXBQcmV2aW91c1BhbmVscyA9IDAsIGRlc3Ryb3lTa2lwcGVkID0gZmFsc2UpIHtcbiAgICAgIGlmKHRoaXMuc2hvdyA9PT0gZmFsc2UpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICBpZiAodGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnZGlzcGF0Y2hDbG9zZUV2ZW50JykgPT09IHRydWUpIHtcbiAgICAgICAgY29uc3QgY29tcG9uZW50TmFtZSA9IHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdLm5hbWU7XG4gICAgICAgIExpdmV3aXJlLmRpc3BhdGNoKCdwYW5lbENsb3NlZCcsIHsgbmFtZTogY29tcG9uZW50TmFtZSB9KTtcbiAgICAgIH1cblxuICAgICAgaWYgKHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ2Rlc3Ryb3lPbkNsb3NlJykgPT09IHRydWUpIHtcbiAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ2Rlc3Ryb3lDb21wb25lbnQnLCB7IGlkOiB0aGlzLmFjdGl2ZUNvbXBvbmVudCB9KTtcbiAgICAgIH1cblxuICAgICAgaWYgKHNraXBQcmV2aW91c1BhbmVscyA+IDApIHtcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBza2lwUHJldmlvdXNQYW5lbHM7IGkrKykge1xuICAgICAgICAgIGlmIChkZXN0cm95U2tpcHBlZCkge1xuICAgICAgICAgICAgY29uc3QgaWQgPSB0aGlzLmNvbXBvbmVudEhpc3RvcnlbdGhpcy5jb21wb25lbnRIaXN0b3J5Lmxlbmd0aCAtIDFdO1xuICAgICAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ2Rlc3Ryb3lDb21wb25lbnQnLCB7IGlkOiBpZCB9KTtcbiAgICAgICAgICB9XG4gICAgICAgICAgdGhpcy5jb21wb25lbnRIaXN0b3J5LnBvcCgpO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIGNvbnN0IGlkID0gdGhpcy5jb21wb25lbnRIaXN0b3J5LnBvcCgpO1xuXG4gICAgICBpZiAoaWQgJiYgIWZvcmNlKSB7XG4gICAgICAgIGlmIChpZCkge1xuICAgICAgICAgIHRoaXMuc2V0QWN0aXZlUGFuZWxDb21wb25lbnQoaWQsIHRydWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHRoaXMuc2V0U2hvd1Byb3BlcnR5VG8oZmFsc2UpO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLnNldFNob3dQcm9wZXJ0eVRvKGZhbHNlKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIHNldEFjdGl2ZVBhbmVsQ29tcG9uZW50KGlkLCBza2lwID0gZmFsc2UpIHtcbiAgICAgIHRoaXMuc2V0U2hvd1Byb3BlcnR5VG8odHJ1ZSk7XG5cbiAgICAgIGlmICh0aGlzLmFjdGl2ZUNvbXBvbmVudCA9PT0gaWQpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICBpZiAodGhpcy5hY3RpdmVDb21wb25lbnQgIT09IGZhbHNlICYmIHNraXAgPT09IGZhbHNlKSB7XG4gICAgICAgIHRoaXMuY29tcG9uZW50SGlzdG9yeS5wdXNoKHRoaXMuYWN0aXZlQ29tcG9uZW50KTtcbiAgICAgIH1cblxuICAgICAgbGV0IGZvY3VzYWJsZVRpbWVvdXQgPSA1MDtcblxuICAgICAgaWYgKHRoaXMuYWN0aXZlQ29tcG9uZW50ID09PSBmYWxzZSkge1xuICAgICAgICB0aGlzLmFjdGl2ZUNvbXBvbmVudCA9IGlkXG4gICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IHRydWU7XG4gICAgICAgIHRoaXMucGFuZWxXaWR0aCA9IHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ21heFdpZHRoQ2xhc3MnKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IGZhbHNlO1xuXG4gICAgICAgIGZvY3VzYWJsZVRpbWVvdXQgPSA0MDA7XG5cbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgdGhpcy5hY3RpdmVDb21wb25lbnQgPSBpZDtcbiAgICAgICAgICB0aGlzLnNob3dBY3RpdmVDb21wb25lbnQgPSB0cnVlO1xuICAgICAgICAgIHRoaXMucGFuZWxXaWR0aCA9IHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ21heFdpZHRoQ2xhc3MnKTtcbiAgICAgICAgfSwgMzAwKTtcbiAgICAgIH1cblxuICAgICAgdGhpcy4kbmV4dFRpY2soKCkgPT4ge1xuICAgICAgICBsZXQgZm9jdXNhYmxlID0gdGhpcy4kcmVmc1tpZF0/LnF1ZXJ5U2VsZWN0b3IoJ1thdXRvZm9jdXNdJyk7XG4gICAgICAgIGlmIChmb2N1c2FibGUpIHtcbiAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgIGZvY3VzYWJsZS5mb2N1cygpO1xuICAgICAgICAgIH0sIGZvY3VzYWJsZVRpbWVvdXQpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9LFxuICAgIGZvY3VzYWJsZXMoKSB7XG4gICAgICBsZXQgc2VsZWN0b3IgPSAnYSwgYnV0dG9uLCBpbnB1dDpub3QoW3R5cGU9XFwnaGlkZGVuXFwnXSwgdGV4dGFyZWEsIHNlbGVjdCwgZGV0YWlscywgW3RhYmluZGV4XTpub3QoW3RhYmluZGV4PVxcJy0xXFwnXSknXG5cbiAgICAgIHJldHVybiBbLi4udGhpcy4kZWwucXVlcnlTZWxlY3RvckFsbChzZWxlY3RvcildXG4gICAgICAgIC5maWx0ZXIoZWwgPT4gIWVsLmhhc0F0dHJpYnV0ZSgnZGlzYWJsZWQnKSlcbiAgICB9LFxuICAgIGZpcnN0Rm9jdXNhYmxlKCkge1xuICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpWzBdXG4gICAgfSxcbiAgICBsYXN0Rm9jdXNhYmxlKCkge1xuICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpLnNsaWNlKC0xKVswXVxuICAgIH0sXG4gICAgbmV4dEZvY3VzYWJsZSgpIHtcbiAgICAgIHJldHVybiB0aGlzLmZvY3VzYWJsZXMoKVt0aGlzLm5leHRGb2N1c2FibGVJbmRleCgpXSB8fCB0aGlzLmZpcnN0Rm9jdXNhYmxlKClcbiAgICB9LFxuICAgIHByZXZGb2N1c2FibGUoKSB7XG4gICAgICByZXR1cm4gdGhpcy5mb2N1c2FibGVzKClbdGhpcy5wcmV2Rm9jdXNhYmxlSW5kZXgoKV0gfHwgdGhpcy5sYXN0Rm9jdXNhYmxlKClcbiAgICB9LFxuICAgIG5leHRGb2N1c2FibGVJbmRleCgpIHtcbiAgICAgIHJldHVybiAodGhpcy5mb2N1c2FibGVzKCkuaW5kZXhPZihkb2N1bWVudC5hY3RpdmVFbGVtZW50KSArIDEpICUgKHRoaXMuZm9jdXNhYmxlcygpLmxlbmd0aCArIDEpXG4gICAgfSxcbiAgICBwcmV2Rm9jdXNhYmxlSW5kZXgoKSB7XG4gICAgICByZXR1cm4gTWF0aC5tYXgoMCwgdGhpcy5mb2N1c2FibGVzKCkuaW5kZXhPZihkb2N1bWVudC5hY3RpdmVFbGVtZW50KSkgLSAxXG4gICAgfSxcbiAgICBzZXRTaG93UHJvcGVydHlUbyhvcGVuKSB7XG4gICAgICB0aGlzLm9wZW4gPSBvcGVuO1xuXG4gICAgICBpZiAob3Blbikge1xuICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5hZGQoJ292ZXJmbG93LXktaGlkZGVuJyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ292ZXJmbG93LXktaGlkZGVuJyk7XG5cbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgdGhpcy5hY3RpdmVDb21wb25lbnQgPSBmYWxzZTtcbiAgICAgICAgICB0aGlzLiR3aXJlLnJlc2V0U3RhdGUoKTtcbiAgICAgICAgfSwgMzAwKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGluaXQoKSB7XG4gICAgICB0aGlzLnBhbmVsV2lkdGggPSB0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdtYXhXaWR0aENsYXNzJyk7XG5cbiAgICAgIHRoaXMubGlzdGVuZXJzLnB1c2goXG4gICAgICAgIExpdmV3aXJlLm9uKCdjbG9zZVBhbmVsJywgKGRhdGEpID0+IHtcbiAgICAgICAgICB0aGlzLmNsb3NlUGFuZWwoZGF0YT8uZm9yY2UgPz8gZmFsc2UsIGRhdGE/LnNraXBQcmV2aW91c1BhbmVscyA/PyAwLCBkYXRhPy5kZXN0cm95U2tpcHBlZCA/PyBmYWxzZSk7XG4gICAgICAgIH0pXG4gICAgICApO1xuXG4gICAgICB0aGlzLmxpc3RlbmVycy5wdXNoKFxuICAgICAgICBMaXZld2lyZS5vbignYWN0aXZlUGFuZWxDb21wb25lbnRDaGFuZ2VkJywgKHsgaWQgfSkgPT4ge1xuICAgICAgICAgIHRoaXMuc2V0QWN0aXZlUGFuZWxDb21wb25lbnQoaWQpO1xuICAgICAgICB9KVxuICAgICAgKTtcbiAgICB9LFxuICAgIGRlc3Ryb3koKSB7XG4gICAgICB0aGlzLmxpc3RlbmVycy5mb3JFYWNoKChsaXN0ZW5lcikgPT4ge1xuICAgICAgICBsaXN0ZW5lcigpO1xuICAgICAgfSk7XG4gICAgfVxuICB9XG59XG5cbmV4cG9ydCBkZWZhdWx0IFNsaWRlT3ZlclBhbmVsXG4iLCAidmFyIHJpID0gT2JqZWN0LmRlZmluZVByb3BlcnR5O1xudmFyIGNpID0gKGwsIGUsIHQpID0+IGUgaW4gbCA/IHJpKGwsIGUsIHsgZW51bWVyYWJsZTogITAsIGNvbmZpZ3VyYWJsZTogITAsIHdyaXRhYmxlOiAhMCwgdmFsdWU6IHQgfSkgOiBsW2VdID0gdDtcbnZhciBjID0gKGwsIGUsIHQpID0+IChjaShsLCB0eXBlb2YgZSAhPSBcInN5bWJvbFwiID8gZSArIFwiXCIgOiBlLCB0KSwgdCksIGt0ID0gKGwsIGUsIHQpID0+IHtcbiAgaWYgKCFlLmhhcyhsKSlcbiAgICB0aHJvdyBUeXBlRXJyb3IoXCJDYW5ub3QgXCIgKyB0KTtcbn07XG52YXIgbiA9IChsLCBlLCB0KSA9PiAoa3QobCwgZSwgXCJyZWFkIGZyb20gcHJpdmF0ZSBmaWVsZFwiKSwgdCA/IHQuY2FsbChsKSA6IGUuZ2V0KGwpKSwgciA9IChsLCBlLCB0KSA9PiB7XG4gIGlmIChlLmhhcyhsKSlcbiAgICB0aHJvdyBUeXBlRXJyb3IoXCJDYW5ub3QgYWRkIHRoZSBzYW1lIHByaXZhdGUgbWVtYmVyIG1vcmUgdGhhbiBvbmNlXCIpO1xuICBlIGluc3RhbmNlb2YgV2Vha1NldCA/IGUuYWRkKGwpIDogZS5zZXQobCwgdCk7XG59LCBtID0gKGwsIGUsIHQsIHMpID0+IChrdChsLCBlLCBcIndyaXRlIHRvIHByaXZhdGUgZmllbGRcIiksIHMgPyBzLmNhbGwobCwgdCkgOiBlLnNldChsLCB0KSwgdCk7XG52YXIgbyA9IChsLCBlLCB0KSA9PiAoa3QobCwgZSwgXCJhY2Nlc3MgcHJpdmF0ZSBtZXRob2RcIiksIHQpO1xuY29uc3QgUHQgPSB7XG4gIGFycm93VXA6ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxwYXRoIGQ9XCJNMTggMTVsLTYtNi02IDZcIi8+PC9zdmc+JyxcbiAgYXJyb3dEb3duOiAnPHN2ZyB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgd2lkdGg9XCIxNVwiIGhlaWdodD1cIjE1XCIgdmlld0JveD1cIjAgMCAyNSAyNVwiIGZpbGw9XCJub25lXCIgc3Ryb2tlPVwiIzAwMDAwMFwiIHN0cm9rZS13aWR0aD1cIjJcIiBzdHJva2UtbGluZWNhcD1cInJvdW5kXCIgc3Ryb2tlLWxpbmVqb2luPVwicm91bmRcIj48cGF0aCBkPVwiTTYgOWw2IDYgNi02XCIvPjwvc3ZnPicsXG4gIGFycm93UmlnaHQ6ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxwYXRoIGQ9XCJNOSAxOGw2LTYtNi02XCIvPjwvc3ZnPicsXG4gIGF0dGVudGlvbjogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PHBhdGggZD1cIk0xMC4yOSAzLjg2TDEuODIgMThhMiAyIDAgMCAwIDEuNzEgM2gxNi45NGEyIDIgMCAwIDAgMS43MS0zTDEzLjcxIDMuODZhMiAyIDAgMCAwLTMuNDIgMHpcIj48L3BhdGg+PGxpbmUgeDE9XCIxMlwiIHkxPVwiOVwiIHgyPVwiMTJcIiB5Mj1cIjEzXCI+PC9saW5lPjxsaW5lIHgxPVwiMTJcIiB5MT1cIjE3XCIgeDI9XCIxMi4wMVwiIHkyPVwiMTdcIj48L2xpbmU+PC9zdmc+JyxcbiAgY2xlYXI6ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxjaXJjbGUgY3g9XCIxMlwiIGN5PVwiMTJcIiByPVwiMTBcIj48L2NpcmNsZT48bGluZSB4MT1cIjE1XCIgeTE9XCI5XCIgeDI9XCI5XCIgeTI9XCIxNVwiPjwvbGluZT48bGluZSB4MT1cIjlcIiB5MT1cIjlcIiB4Mj1cIjE1XCIgeTI9XCIxNVwiPjwvbGluZT48L3N2Zz4nLFxuICBjcm9zczogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PGxpbmUgeDE9XCIxOFwiIHkxPVwiNlwiIHgyPVwiNlwiIHkyPVwiMThcIj48L2xpbmU+PGxpbmUgeDE9XCI2XCIgeTE9XCI2XCIgeDI9XCIxOFwiIHkyPVwiMThcIj48L2xpbmU+PC9zdmc+JyxcbiAgY2hlY2s6ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxwb2x5bGluZSBwb2ludHM9XCIyMCA2IDkgMTcgNCAxMlwiPjwvcG9seWxpbmU+PC9zdmc+JyxcbiAgcGFydGlhbENoZWNrOiAnPHN2ZyB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgd2lkdGg9XCIxNVwiIGhlaWdodD1cIjE1XCIgdmlld0JveD1cIjAgMCAyNSAyNVwiIGZpbGw9XCJub25lXCIgc3Ryb2tlPVwiIzAwMDAwMFwiIHN0cm9rZS13aWR0aD1cIjJcIiBzdHJva2UtbGluZWNhcD1cInJvdW5kXCIgc3Ryb2tlLWxpbmVqb2luPVwicm91bmRcIj48bGluZSB4MT1cIjVcIiB5MT1cIjEyXCIgeDI9XCIxOVwiIHkyPVwiMTJcIj48L2xpbmU+PC9zdmc+J1xufSwgSSA9IChsLCBlKSA9PiB7XG4gIGlmIChlLmlubmVySFRNTCA9IFwiXCIsIHR5cGVvZiBsID09IFwic3RyaW5nXCIpXG4gICAgZS5pbm5lckhUTUwgPSBsO1xuICBlbHNlIHtcbiAgICBjb25zdCB0ID0gbC5jbG9uZU5vZGUoITApO1xuICAgIGUuYXBwZW5kQ2hpbGQodCk7XG4gIH1cbn0sIEJ0ID0gKGwpID0+IHtcbiAgY29uc3QgZSA9IGwgPyB7IC4uLmwgfSA6IHt9O1xuICByZXR1cm4gT2JqZWN0LmtleXMoUHQpLmZvckVhY2goKHQpID0+IHtcbiAgICBlW3RdIHx8IChlW3RdID0gUHRbdF0pO1xuICB9KSwgZTtcbn0sIGhpID0gKGwpID0+IGwucmVkdWNlKChlLCB7IG5hbWU6IHQgfSwgcykgPT4gKGUgKz0gdCwgcyA8IGwubGVuZ3RoIC0gMSAmJiAoZSArPSBcIiwgXCIpLCBlKSwgXCJcIik7XG52YXIgTiwgRSwgRCwgdiwgdWUsIEh0LCBILCBXLCBwZSwgR3QsIG1lLCBNdCwgRywgVSwgTywgViwgZmUsIEZ0LCBiZSwgcXQsIENlLCBqdCwgZ2UsIFJ0LCBrZSwgJHQsIHdlLCBXdCwgRWUsIFV0LCB2ZSwgenQsIExlLCBZdCwgeWUsIEt0LCB4ZSwgWHQsIFNlLCBKdCwgX2UsIFp0LCBBZSwgUXQsIFRlLCBlcywgTmUsIHRzLCB6LCB3dDtcbmNsYXNzIGRpIHtcbiAgY29uc3RydWN0b3Ioe1xuICAgIHZhbHVlOiBlLFxuICAgIHNob3dUYWdzOiB0LFxuICAgIHRhZ3NDb3VudFRleHQ6IHMsXG4gICAgY2xlYXJhYmxlOiBpLFxuICAgIGlzQWx3YXlzT3BlbmVkOiBhLFxuICAgIHNlYXJjaGFibGU6IGgsXG4gICAgcGxhY2Vob2xkZXI6IGQsXG4gICAgZGlzYWJsZWQ6IEMsXG4gICAgaXNTaW5nbGVTZWxlY3Q6IGYsXG4gICAgaWQ6IGIsXG4gICAgYXJpYUxhYmVsOiBnLFxuICAgIGljb25FbGVtZW50czogayxcbiAgICBpbnB1dENhbGxiYWNrOiB3LFxuICAgIHNlYXJjaENhbGxiYWNrOiB5LFxuICAgIG9wZW5DYWxsYmFjazogeCxcbiAgICBjbG9zZUNhbGxiYWNrOiAkLFxuICAgIGtleWRvd25DYWxsYmFjazogYWUsXG4gICAgZm9jdXNDYWxsYmFjazogQ3QsXG4gICAgYmx1ckNhbGxiYWNrOiBndCxcbiAgICBuYW1lQ2hhbmdlQ2FsbGJhY2s6IG9lXG4gIH0pIHtcbiAgICAvLyBQcml2YXRlIG1ldGhvZHNcbiAgICByKHRoaXMsIHVlKTtcbiAgICByKHRoaXMsIEgpO1xuICAgIHIodGhpcywgcGUpO1xuICAgIHIodGhpcywgbWUpO1xuICAgIHIodGhpcywgRyk7XG4gICAgcih0aGlzLCBPKTtcbiAgICByKHRoaXMsIGZlKTtcbiAgICByKHRoaXMsIGJlKTtcbiAgICByKHRoaXMsIENlKTtcbiAgICByKHRoaXMsIGdlKTtcbiAgICByKHRoaXMsIGtlKTtcbiAgICByKHRoaXMsIHdlKTtcbiAgICByKHRoaXMsIEVlKTtcbiAgICByKHRoaXMsIHZlKTtcbiAgICByKHRoaXMsIExlKTtcbiAgICByKHRoaXMsIHllKTtcbiAgICByKHRoaXMsIHhlKTtcbiAgICByKHRoaXMsIFNlKTtcbiAgICByKHRoaXMsIF9lKTtcbiAgICByKHRoaXMsIEFlKTtcbiAgICByKHRoaXMsIFRlKTtcbiAgICByKHRoaXMsIE5lKTtcbiAgICAvLyBFbWl0c1xuICAgIHIodGhpcywgeik7XG4gICAgLy8gUHJvcHNcbiAgICBjKHRoaXMsIFwidmFsdWVcIik7XG4gICAgYyh0aGlzLCBcInNob3dUYWdzXCIpO1xuICAgIGModGhpcywgXCJ0YWdzQ291bnRUZXh0XCIpO1xuICAgIGModGhpcywgXCJjbGVhcmFibGVcIik7XG4gICAgYyh0aGlzLCBcImlzQWx3YXlzT3BlbmVkXCIpO1xuICAgIGModGhpcywgXCJzZWFyY2hhYmxlXCIpO1xuICAgIGModGhpcywgXCJwbGFjZWhvbGRlclwiKTtcbiAgICBjKHRoaXMsIFwiZGlzYWJsZWRcIik7XG4gICAgYyh0aGlzLCBcImlzU2luZ2xlU2VsZWN0XCIpO1xuICAgIGModGhpcywgXCJpZFwiKTtcbiAgICBjKHRoaXMsIFwiYXJpYUxhYmVsXCIpO1xuICAgIGModGhpcywgXCJpY29uRWxlbWVudHNcIik7XG4gICAgLy8gSW5uZXJTdGF0ZVxuICAgIGModGhpcywgXCJpc09wZW5lZFwiKTtcbiAgICBjKHRoaXMsIFwic2VhcmNoVGV4dFwiKTtcbiAgICBjKHRoaXMsIFwic3JjRWxlbWVudFwiKTtcbiAgICAvLyBQcml2YXRlSW5uZXJTdGF0ZVxuICAgIHIodGhpcywgTiwgdm9pZCAwKTtcbiAgICByKHRoaXMsIEUsIHZvaWQgMCk7XG4gICAgcih0aGlzLCBELCB2b2lkIDApO1xuICAgIHIodGhpcywgdiwgdm9pZCAwKTtcbiAgICAvLyBDYWxsYmFja3NcbiAgICBjKHRoaXMsIFwiaW5wdXRDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwic2VhcmNoQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm9wZW5DYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwiY2xvc2VDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwia2V5ZG93bkNhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJmb2N1c0NhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJibHVyQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm5hbWVDaGFuZ2VDYWxsYmFja1wiKTtcbiAgICB0aGlzLnZhbHVlID0gZSwgdGhpcy5zaG93VGFncyA9IHQsIHRoaXMudGFnc0NvdW50VGV4dCA9IHMsIHRoaXMuc2VhcmNoYWJsZSA9IGgsIHRoaXMucGxhY2Vob2xkZXIgPSBkLCB0aGlzLmNsZWFyYWJsZSA9IGksIHRoaXMuaXNBbHdheXNPcGVuZWQgPSBhLCB0aGlzLmRpc2FibGVkID0gQywgdGhpcy5pc1NpbmdsZVNlbGVjdCA9IGYsIHRoaXMuaWQgPSBiLCB0aGlzLmFyaWFMYWJlbCA9IGcsIHRoaXMuaWNvbkVsZW1lbnRzID0gaywgdGhpcy5pc09wZW5lZCA9ICExLCB0aGlzLnNlYXJjaFRleHQgPSBcIlwiLCBtKHRoaXMsIE4sIG8odGhpcywgQ2UsIGp0KS5jYWxsKHRoaXMpKSwgbSh0aGlzLCBFLCBvKHRoaXMsIExlLCBZdCkuY2FsbCh0aGlzKSksIG0odGhpcywgRCwgbyh0aGlzLCBTZSwgSnQpLmNhbGwodGhpcykpLCBtKHRoaXMsIHYsIG51bGwpLCB0aGlzLmlucHV0Q2FsbGJhY2sgPSB3LCB0aGlzLnNlYXJjaENhbGxiYWNrID0geSwgdGhpcy5vcGVuQ2FsbGJhY2sgPSB4LCB0aGlzLmNsb3NlQ2FsbGJhY2sgPSAkLCB0aGlzLmtleWRvd25DYWxsYmFjayA9IGFlLCB0aGlzLmZvY3VzQ2FsbGJhY2sgPSBDdCwgdGhpcy5ibHVyQ2FsbGJhY2sgPSBndCwgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2sgPSBvZSwgdGhpcy5zcmNFbGVtZW50ID0gbyh0aGlzLCBmZSwgRnQpLmNhbGwodGhpcywgbih0aGlzLCBOKSwgbih0aGlzLCBFKSwgbih0aGlzLCBEKSksIG8odGhpcywgdWUsIEh0KS5jYWxsKHRoaXMpO1xuICB9XG4gIC8vIFB1YmxpYyBtZXRob2RzXG4gIGZvY3VzKCkge1xuICAgIHNldFRpbWVvdXQoKCkgPT4gbih0aGlzLCBFKS5mb2N1cygpLCAwKTtcbiAgfVxuICBibHVyKCkge1xuICAgIHRoaXMuaXNPcGVuZWQgJiYgbyh0aGlzLCBPLCBWKS5jYWxsKHRoaXMpLCB0aGlzLmNsZWFyU2VhcmNoKCksIG4odGhpcywgRSkuYmx1cigpO1xuICB9XG4gIHVwZGF0ZVZhbHVlKGUpIHtcbiAgICB0aGlzLnZhbHVlID0gZSwgbyh0aGlzLCBILCBXKS5jYWxsKHRoaXMpLCBvKHRoaXMsIEcsIFUpLmNhbGwodGhpcyk7XG4gIH1cbiAgcmVtb3ZlSXRlbShlKSB7XG4gICAgdGhpcy52YWx1ZSA9IHRoaXMudmFsdWUuZmlsdGVyKCh0KSA9PiB0LmlkICE9PSBlKSwgbyh0aGlzLCB6LCB3dCkuY2FsbCh0aGlzKSwgbyh0aGlzLCBILCBXKS5jYWxsKHRoaXMpLCBvKHRoaXMsIEcsIFUpLmNhbGwodGhpcyk7XG4gIH1cbiAgY2xlYXIoKSB7XG4gICAgdGhpcy52YWx1ZSA9IFtdLCBvKHRoaXMsIHosIHd0KS5jYWxsKHRoaXMpLCBvKHRoaXMsIEgsIFcpLmNhbGwodGhpcyksIHRoaXMuY2xlYXJTZWFyY2goKTtcbiAgfVxuICBvcGVuQ2xvc2UoKSB7XG4gICAgbyh0aGlzLCBPLCBWKS5jYWxsKHRoaXMpO1xuICB9XG4gIGNsZWFyU2VhcmNoKCkge1xuICAgIHRoaXMuc2VhcmNoVGV4dCA9IFwiXCIsIHRoaXMuc2VhcmNoQ2FsbGJhY2soXCJcIiksIG8odGhpcywgRywgVSkuY2FsbCh0aGlzKTtcbiAgfVxufVxuTiA9IG5ldyBXZWFrTWFwKCksIEUgPSBuZXcgV2Vha01hcCgpLCBEID0gbmV3IFdlYWtNYXAoKSwgdiA9IG5ldyBXZWFrTWFwKCksIHVlID0gbmV3IFdlYWtTZXQoKSwgSHQgPSBmdW5jdGlvbigpIHtcbiAgbyh0aGlzLCBILCBXKS5jYWxsKHRoaXMpLCBvKHRoaXMsIEcsIFUpLmNhbGwodGhpcyksIG8odGhpcywgcGUsIEd0KS5jYWxsKHRoaXMpO1xufSwgSCA9IG5ldyBXZWFrU2V0KCksIFcgPSBmdW5jdGlvbigpIHtcbiAgaWYgKG4odGhpcywgTikuaW5uZXJIVE1MID0gXCJcIiwgdGhpcy5zaG93VGFncykge1xuICAgIG4odGhpcywgTikuYXBwZW5kKC4uLm8odGhpcywgZ2UsIFJ0KS5jYWxsKHRoaXMpKTtcbiAgICBjb25zdCBlID0gaGkodGhpcy52YWx1ZSk7XG4gICAgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2soZSk7XG4gIH0gZWxzZSB7XG4gICAgY29uc3QgZSA9IG8odGhpcywgdmUsIHp0KS5jYWxsKHRoaXMpO1xuICAgIG4odGhpcywgTikuYXBwZW5kQ2hpbGQoZSksIHRoaXMubmFtZUNoYW5nZUNhbGxiYWNrKGUuaW5uZXJUZXh0KTtcbiAgfVxuICBuKHRoaXMsIE4pLmFwcGVuZENoaWxkKG4odGhpcywgRSkpO1xufSwgcGUgPSBuZXcgV2Vha1NldCgpLCBHdCA9IGZ1bmN0aW9uKCkge1xuICBjb25zdCBlID0gW107XG4gIG4odGhpcywgRCkuaW5uZXJIVE1MID0gXCJcIiwgdGhpcy5jbGVhcmFibGUgJiYgZS5wdXNoKG8odGhpcywgX2UsIFp0KS5jYWxsKHRoaXMpKSwgdGhpcy5pc0Fsd2F5c09wZW5lZCB8fCBlLnB1c2gobyh0aGlzLCBUZSwgZXMpLmNhbGwodGhpcywgdGhpcy5pc09wZW5lZCkpLCBlLmxlbmd0aCAmJiBuKHRoaXMsIEQpLmFwcGVuZCguLi5lKTtcbn0sIG1lID0gbmV3IFdlYWtTZXQoKSwgTXQgPSBmdW5jdGlvbigpIHtcbiAgaWYgKCF0aGlzLmlzQWx3YXlzT3BlbmVkICYmIG4odGhpcywgdikpIHtcbiAgICBjb25zdCBlID0gdGhpcy5pc09wZW5lZCA/IHRoaXMuaWNvbkVsZW1lbnRzLmFycm93VXAgOiB0aGlzLmljb25FbGVtZW50cy5hcnJvd0Rvd247XG4gICAgSShlLCBuKHRoaXMsIHYpKTtcbiAgfVxufSwgRyA9IG5ldyBXZWFrU2V0KCksIFUgPSBmdW5jdGlvbigpIHtcbiAgdmFyIGU7XG4gIChlID0gdGhpcy52YWx1ZSkgIT0gbnVsbCAmJiBlLmxlbmd0aCA/IChuKHRoaXMsIEUpLnJlbW92ZUF0dHJpYnV0ZShcInBsYWNlaG9sZGVyXCIpLCB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLXZhbHVlLW5vdC1zZWxlY3RlZFwiKSkgOiAobih0aGlzLCBFKS5zZXRBdHRyaWJ1dGUoXCJwbGFjZWhvbGRlclwiLCB0aGlzLnBsYWNlaG9sZGVyKSwgdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS12YWx1ZS1ub3Qtc2VsZWN0ZWRcIikpLCB0aGlzLnNlYXJjaGFibGUgPyB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLXVuc2VhcmNoYWJsZVwiKSA6IHRoaXMuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dC0tdW5zZWFyY2hhYmxlXCIpLCB0aGlzLmlzU2luZ2xlU2VsZWN0ID8gdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS1pcy1zaW5nbGUtc2VsZWN0XCIpIDogdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWlucHV0LS1pcy1zaW5nbGUtc2VsZWN0XCIpLCBuKHRoaXMsIEUpLnZhbHVlID0gdGhpcy5zZWFyY2hUZXh0O1xufSwgTyA9IG5ldyBXZWFrU2V0KCksIFYgPSBmdW5jdGlvbigpIHtcbiAgdGhpcy5pc09wZW5lZCA9ICF0aGlzLmlzT3BlbmVkLCBvKHRoaXMsIG1lLCBNdCkuY2FsbCh0aGlzKSwgdGhpcy5pc09wZW5lZCA/IHRoaXMub3BlbkNhbGxiYWNrKCkgOiB0aGlzLmNsb3NlQ2FsbGJhY2soKTtcbn0sIGZlID0gbmV3IFdlYWtTZXQoKSwgRnQgPSBmdW5jdGlvbihlLCB0LCBzKSB7XG4gIGNvbnN0IGkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICByZXR1cm4gaS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dFwiKSwgaS5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpLCBpLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgKGEpID0+IG8odGhpcywgYmUsIHF0KS5jYWxsKHRoaXMsIGEpKSwgaS5hZGRFdmVudExpc3RlbmVyKFwiZm9jdXNcIiwgKCkgPT4gdGhpcy5mb2N1c0NhbGxiYWNrKCksICEwKSwgaS5hZGRFdmVudExpc3RlbmVyKFwiYmx1clwiLCAoKSA9PiB0aGlzLmJsdXJDYWxsYmFjaygpLCAhMCksIGUuYXBwZW5kQ2hpbGQodCksIGkuYXBwZW5kKGUsIHMpLCBpO1xufSwgYmUgPSBuZXcgV2Vha1NldCgpLCBxdCA9IGZ1bmN0aW9uKGUpIHtcbiAgZS5zdG9wUHJvcGFnYXRpb24oKSwgdGhpcy5pc09wZW5lZCB8fCBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyksIHRoaXMuZm9jdXMoKTtcbn0sIENlID0gbmV3IFdlYWtTZXQoKSwganQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHJldHVybiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X190YWdzXCIpLCBlO1xufSwgZ2UgPSBuZXcgV2Vha1NldCgpLCBSdCA9IGZ1bmN0aW9uKCkge1xuICByZXR1cm4gdGhpcy52YWx1ZS5tYXAoKGUpID0+IHtcbiAgICBjb25zdCB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgICB0LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X190YWdzLWVsZW1lbnRcIiksIHQuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgdC5zZXRBdHRyaWJ1dGUoXCJ0YWctaWRcIiwgZS5pZC50b1N0cmluZygpKSwgdC5zZXRBdHRyaWJ1dGUoXCJ0aXRsZVwiLCBlLm5hbWUpO1xuICAgIGNvbnN0IHMgPSBvKHRoaXMsIHdlLCBXdCkuY2FsbCh0aGlzLCBlLm5hbWUpLCBpID0gbyh0aGlzLCBFZSwgVXQpLmNhbGwodGhpcyk7XG4gICAgcmV0dXJuIHQuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCAoYSkgPT4gbyh0aGlzLCBrZSwgJHQpLmNhbGwodGhpcywgYSwgZS5pZCkpLCB0LmFwcGVuZChzLCBpKSwgdDtcbiAgfSk7XG59LCBrZSA9IG5ldyBXZWFrU2V0KCksICR0ID0gZnVuY3Rpb24oZSwgdCkge1xuICBlLnByZXZlbnREZWZhdWx0KCksIGUuc3RvcFByb3BhZ2F0aW9uKCksIHRoaXMucmVtb3ZlSXRlbSh0KSwgdGhpcy5mb2N1cygpO1xufSwgd2UgPSBuZXcgV2Vha1NldCgpLCBXdCA9IGZ1bmN0aW9uKGUpIHtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICByZXR1cm4gdC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dF9fdGFncy1uYW1lXCIpLCB0LnRleHRDb250ZW50ID0gZSwgdDtcbn0sIEVlID0gbmV3IFdlYWtTZXQoKSwgVXQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICByZXR1cm4gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dF9fdGFncy1jcm9zc1wiKSwgSSh0aGlzLmljb25FbGVtZW50cy5jcm9zcywgZSksIGU7XG59LCB2ZSA9IG5ldyBXZWFrU2V0KCksIHp0ID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKTtcbiAgaWYgKGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX3RhZ3MtY291bnRcIiksICF0aGlzLnZhbHVlLmxlbmd0aClcbiAgICByZXR1cm4gZS50ZXh0Q29udGVudCA9IFwiXCIsIGUuc2V0QXR0cmlidXRlKFwidGl0bGVcIiwgXCJcIiksIGU7XG4gIGNvbnN0IHQgPSB0aGlzLnZhbHVlLmxlbmd0aCA9PT0gMSA/IHRoaXMudmFsdWVbMF0ubmFtZSA6IGAke3RoaXMudmFsdWUubGVuZ3RofSAke3RoaXMudGFnc0NvdW50VGV4dH1gO1xuICByZXR1cm4gZS50ZXh0Q29udGVudCA9IHQsIGUuc2V0QXR0cmlidXRlKFwidGl0bGVcIiwgdCksIGU7XG59LCBMZSA9IG5ldyBXZWFrU2V0KCksIFl0ID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiaW5wdXRcIik7XG4gIHJldHVybiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X19lZGl0XCIpLCB0aGlzLmlkICYmIGUuc2V0QXR0cmlidXRlKFwiaWRcIiwgdGhpcy5pZCksICghdGhpcy5zZWFyY2hhYmxlIHx8IHRoaXMuZGlzYWJsZWQpICYmIGUuc2V0QXR0cmlidXRlKFwicmVhZG9ubHlcIiwgXCJyZWFkb25seVwiKSwgdGhpcy5kaXNhYmxlZCAmJiBlLnNldEF0dHJpYnV0ZShcInRhYmluZGV4XCIsIFwiLTFcIiksIHRoaXMuYXJpYUxhYmVsLmxlbmd0aCAmJiBlLnNldEF0dHJpYnV0ZShcImFyaWEtbGFiZWxcIiwgdGhpcy5hcmlhTGFiZWwpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsICh0KSA9PiBvKHRoaXMsIHllLCBLdCkuY2FsbCh0aGlzLCB0KSksIGUuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICh0KSA9PiBvKHRoaXMsIHhlLCBYdCkuY2FsbCh0aGlzLCB0LCBlKSksIGU7XG59LCB5ZSA9IG5ldyBXZWFrU2V0KCksIEt0ID0gZnVuY3Rpb24oZSkge1xuICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICBjb25zdCB0ID0gZS5rZXk7XG4gIHQgPT09IFwiQmFja3NwYWNlXCIgJiYgIXRoaXMuc2VhcmNoVGV4dC5sZW5ndGggJiYgdGhpcy52YWx1ZS5sZW5ndGggJiYgIXRoaXMuc2hvd1RhZ3MgJiYgdGhpcy5jbGVhcigpLCB0ID09PSBcIkJhY2tzcGFjZVwiICYmICF0aGlzLnNlYXJjaFRleHQubGVuZ3RoICYmIHRoaXMudmFsdWUubGVuZ3RoICYmIHRoaXMucmVtb3ZlSXRlbSh0aGlzLnZhbHVlW3RoaXMudmFsdWUubGVuZ3RoIC0gMV0uaWQpLCBlLmNvZGUgPT09IFwiU3BhY2VcIiAmJiAoIXRoaXMuc2VhcmNoVGV4dCB8fCAhdGhpcy5zZWFyY2hhYmxlKSAmJiBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyksICh0ID09PSBcIkVudGVyXCIgfHwgdCA9PT0gXCJBcnJvd0Rvd25cIiB8fCB0ID09PSBcIkFycm93VXBcIikgJiYgZS5wcmV2ZW50RGVmYXVsdCgpLCB0aGlzLmtleWRvd25DYWxsYmFjayhlKSwgdCAhPT0gXCJUYWJcIiAmJiB0aGlzLmZvY3VzKCk7XG59LCB4ZSA9IG5ldyBXZWFrU2V0KCksIFh0ID0gZnVuY3Rpb24oZSwgdCkge1xuICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICBjb25zdCBzID0gdGhpcy5zZWFyY2hUZXh0LCBpID0gdC52YWx1ZS50cmltKCk7XG4gIGlmIChzLmxlbmd0aCA9PT0gMCAmJiBpLmxlbmd0aCA9PT0gMCkge1xuICAgIHQudmFsdWUgPSBcIlwiO1xuICAgIHJldHVybjtcbiAgfVxuICBpZiAodGhpcy5zZWFyY2hhYmxlKSB7XG4gICAgY29uc3QgYSA9IGUudGFyZ2V0LnZhbHVlO1xuICAgIHRoaXMuc2VhcmNoQ2FsbGJhY2soYSksIHRoaXMuaXNPcGVuZWQgfHwgbyh0aGlzLCBPLCBWKS5jYWxsKHRoaXMpO1xuICB9IGVsc2VcbiAgICB0LnZhbHVlID0gXCJcIjtcbiAgdGhpcy5zZWFyY2hUZXh0ID0gdC52YWx1ZTtcbn0sIFNlID0gbmV3IFdlYWtTZXQoKSwgSnQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHJldHVybiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X19vcGVyYXRvcnNcIiksIGU7XG59LCBfZSA9IG5ldyBXZWFrU2V0KCksIFp0ID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKTtcbiAgcmV0dXJuIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX2NsZWFyXCIpLCBlLnNldEF0dHJpYnV0ZShcInRhYmluZGV4XCIsIFwiLTFcIiksIEkodGhpcy5pY29uRWxlbWVudHMuY2xlYXIsIGUpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgKHQpID0+IG8odGhpcywgQWUsIFF0KS5jYWxsKHRoaXMsIHQpKSwgZTtcbn0sIEFlID0gbmV3IFdlYWtTZXQoKSwgUXQgPSBmdW5jdGlvbihlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKSwgZS5zdG9wUHJvcGFnYXRpb24oKSwgKHRoaXMuc2VhcmNoVGV4dC5sZW5ndGggfHwgdGhpcy52YWx1ZS5sZW5ndGgpICYmIHRoaXMuY2xlYXIoKSwgdGhpcy5mb2N1cygpO1xufSwgVGUgPSBuZXcgV2Vha1NldCgpLCBlcyA9IGZ1bmN0aW9uKGUpIHtcbiAgbSh0aGlzLCB2LCBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKSksIG4odGhpcywgdikuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX2Fycm93XCIpO1xuICBjb25zdCB0ID0gZSA/IHRoaXMuaWNvbkVsZW1lbnRzLmFycm93VXAgOiB0aGlzLmljb25FbGVtZW50cy5hcnJvd0Rvd247XG4gIHJldHVybiBJKHQsIG4odGhpcywgdikpLCBuKHRoaXMsIHYpLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgKHMpID0+IG8odGhpcywgTmUsIHRzKS5jYWxsKHRoaXMsIHMpKSwgbih0aGlzLCB2KTtcbn0sIE5lID0gbmV3IFdlYWtTZXQoKSwgdHMgPSBmdW5jdGlvbihlKSB7XG4gIGUuc3RvcFByb3BhZ2F0aW9uKCksIGUucHJldmVudERlZmF1bHQoKSwgdGhpcy5mb2N1cygpLCBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyk7XG59LCB6ID0gbmV3IFdlYWtTZXQoKSwgd3QgPSBmdW5jdGlvbigpIHtcbiAgdGhpcy5pbnB1dENhbGxiYWNrKHRoaXMudmFsdWUpO1xufTtcbmNvbnN0IHNzID0gKGwsIGUsIHQsIHMpID0+IHtcbiAgZmkoZSk7XG4gIGNvbnN0IGkgPSBlLmZpbHRlcigoYSkgPT4gIWEuZGlzYWJsZWQgJiYgbC5zb21lKChoKSA9PiBoID09PSBhLmlkKSk7XG4gIGlmICh0ICYmIGkubGVuZ3RoKSB7XG4gICAgaVswXS5jaGVja2VkID0gITA7XG4gICAgcmV0dXJuO1xuICB9XG4gIGkuZm9yRWFjaCgoYSkgPT4ge1xuICAgIGEuY2hlY2tlZCA9ICEwO1xuICAgIGNvbnN0IGggPSBJdChhLCBlLCBzKTtcbiAgICBhLmNoZWNrZWQgPSBoO1xuICB9KTtcbn0sIEl0ID0gKHsgaWQ6IGwsIGNoZWNrZWQ6IGUgfSwgdCwgcykgPT4ge1xuICBjb25zdCBpID0gdC5maW5kKChoKSA9PiBoLmlkID09PSBsKTtcbiAgaWYgKCFpKVxuICAgIHJldHVybiAhMTtcbiAgaWYgKHMpXG4gICAgcmV0dXJuIGkuY2hlY2tlZCA9IGkuZGlzYWJsZWQgPyAhMSA6ICEhZSwgaS5jaGVja2VkO1xuICBjb25zdCBhID0gaXMoISFlLCBpLCB0KTtcbiAgcmV0dXJuIGxzKGksIHQpLCBhO1xufSwgaXMgPSAobCwgZSwgdCkgPT4ge1xuICBpZiAoIWUuaXNHcm91cClcbiAgICByZXR1cm4gZS5jaGVja2VkID0gZS5kaXNhYmxlZCA/ICExIDogISFsLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMSwgZS5jaGVja2VkO1xuICBjb25zdCBzID0gdC5maWx0ZXIoKGQpID0+IGQuY2hpbGRPZiA9PT0gZS5pZCk7XG4gIHJldHVybiAhbCB8fCBlLmRpc2FibGVkIHx8IGUuaXNQYXJ0aWFsQ2hlY2tlZCA/IChlLmNoZWNrZWQgPSAhMSwgZS5pc1BhcnRpYWxDaGVja2VkID0gITEsIEV0KGUsIHMsIHQpLCBlLmNoZWNrZWQpIDogbnMocywgdCkgPyBhcyhzKSA/IChlLmNoZWNrZWQgPSAhMSwgZS5pc1BhcnRpYWxDaGVja2VkID0gITEsIGUuZGlzYWJsZWQgPSAhMCwgZS5jaGVja2VkKSA6IChlLmNoZWNrZWQgPSAhMSwgZS5pc1BhcnRpYWxDaGVja2VkID0gITAsIHMuZm9yRWFjaCgoZCkgPT4ge1xuICAgIGlzKGwsIGQsIHQpO1xuICB9KSwgZS5jaGVja2VkKSA6IChlLmNoZWNrZWQgPSAhMCwgZS5pc1BhcnRpYWxDaGVja2VkID0gITEsIEV0KGUsIHMsIHQpLCBlLmNoZWNrZWQpO1xufSwgbHMgPSAobCwgZSkgPT4ge1xuICBjb25zdCB0ID0gZS5maW5kKChzKSA9PiBzLmlkID09PSBsLmNoaWxkT2YpO1xuICB0ICYmICh1aSh0LCBlKSwgbHModCwgZSkpO1xufSwgdWkgPSAobCwgZSkgPT4ge1xuICBjb25zdCB0ID0gZnQobCwgZSk7XG4gIGlmIChhcyh0KSkge1xuICAgIGwuY2hlY2tlZCA9ICExLCBsLmlzUGFydGlhbENoZWNrZWQgPSAhMSwgbC5kaXNhYmxlZCA9ICEwO1xuICAgIHJldHVybjtcbiAgfVxuICBpZiAocGkodCkpIHtcbiAgICBsLmNoZWNrZWQgPSAhMCwgbC5pc1BhcnRpYWxDaGVja2VkID0gITE7XG4gICAgcmV0dXJuO1xuICB9XG4gIGlmIChtaSh0KSkge1xuICAgIGwuY2hlY2tlZCA9ICExLCBsLmlzUGFydGlhbENoZWNrZWQgPSAhMDtcbiAgICByZXR1cm47XG4gIH1cbiAgbC5jaGVja2VkID0gITEsIGwuaXNQYXJ0aWFsQ2hlY2tlZCA9ICExO1xufSwgRXQgPSAoeyBjaGVja2VkOiBsLCBkaXNhYmxlZDogZSB9LCB0LCBzKSA9PiB7XG4gIHQuZm9yRWFjaCgoaSkgPT4ge1xuICAgIGkuZGlzYWJsZWQgPSAhIWUgfHwgISFpLmRpc2FibGVkLCBpLmNoZWNrZWQgPSAhIWwgJiYgIWkuZGlzYWJsZWQsIGkuaXNQYXJ0aWFsQ2hlY2tlZCA9ICExO1xuICAgIGNvbnN0IGEgPSBmdChpLCBzKTtcbiAgICBFdCh7IGNoZWNrZWQ6IGwsIGRpc2FibGVkOiBlIH0sIGEsIHMpO1xuICB9KTtcbn0sIG5zID0gKGwsIGUpID0+IGwuc29tZSgoaSkgPT4gaS5kaXNhYmxlZCkgPyAhMCA6IGwuc29tZSgoaSkgPT4ge1xuICBpZiAoaS5pc0dyb3VwKSB7XG4gICAgY29uc3QgYSA9IGZ0KGksIGUpO1xuICAgIHJldHVybiBucyhhLCBlKTtcbiAgfVxuICByZXR1cm4gITE7XG59KSwgYXMgPSAobCkgPT4gbC5ldmVyeSgoZSkgPT4gISFlLmRpc2FibGVkKSwgcGkgPSAobCkgPT4gbC5ldmVyeSgoZSkgPT4gISFlLmNoZWNrZWQpLCBtaSA9IChsKSA9PiBsLnNvbWUoKGUpID0+ICEhZS5jaGVja2VkIHx8ICEhZS5pc1BhcnRpYWxDaGVja2VkKSwgZmkgPSAobCkgPT4ge1xuICBsLmZvckVhY2goKGUpID0+IHtcbiAgICBlLmNoZWNrZWQgPSAhMSwgZS5pc1BhcnRpYWxDaGVja2VkID0gITE7XG4gIH0pO1xufSwgYmkgPSAobCwgZSwgdCkgPT4ge1xuICBjb25zdCBzID0geyBsZXZlbDogMCwgZ3JvdXBJZDogXCJcIiB9LCBpID0gb3MobCwgZSwgcy5ncm91cElkLCBzLmxldmVsKTtcbiAgcmV0dXJuIGdpKGksIHQpO1xufSwgb3MgPSAobCwgZSwgdCwgcykgPT4gbC5yZWR1Y2UoKGksIGEpID0+IHtcbiAgdmFyIGY7XG4gIGNvbnN0IGggPSAhISgoZiA9IGEuY2hpbGRyZW4pICE9IG51bGwgJiYgZi5sZW5ndGgpLCBkID0gcyA+PSBlICYmIGgsIEMgPSBzID4gZTtcbiAgaWYgKGkucHVzaCh7XG4gICAgaWQ6IGEudmFsdWUsXG4gICAgbmFtZTogYS5uYW1lLFxuICAgIGNoaWxkT2Y6IHQsXG4gICAgaXNHcm91cDogaCxcbiAgICBjaGVja2VkOiAhMSxcbiAgICBpc1BhcnRpYWxDaGVja2VkOiAhMSxcbiAgICBsZXZlbDogcyxcbiAgICBpc0Nsb3NlZDogZCxcbiAgICBoaWRkZW46IEMsXG4gICAgZGlzYWJsZWQ6IGEuZGlzYWJsZWQgPz8gITFcbiAgfSksIGgpIHtcbiAgICBjb25zdCBiID0gb3MoYS5jaGlsZHJlbiwgZSwgYS52YWx1ZSwgcyArIDEpO1xuICAgIGkucHVzaCguLi5iKTtcbiAgfVxuICByZXR1cm4gaTtcbn0sIFtdKSwgZnQgPSAoeyBpZDogbCB9LCBlKSA9PiBlLmZpbHRlcigodCkgPT4gdC5jaGlsZE9mID09PSBsKSwgQ2kgPSAobCkgPT4ge1xuICBjb25zdCB7IHVuZ3JvdXBlZE5vZGVzOiBlLCBhbGxHcm91cGVkTm9kZXM6IHQsIGFsbE5vZGVzOiBzIH0gPSBsLnJlZHVjZShcbiAgICAoYSwgaCkgPT4gKGguY2hlY2tlZCAmJiAoYS5hbGxOb2Rlcy5wdXNoKGgpLCBoLmlzR3JvdXAgPyBhLmFsbEdyb3VwZWROb2Rlcy5wdXNoKGgpIDogYS51bmdyb3VwZWROb2Rlcy5wdXNoKGgpKSwgYSksXG4gICAge1xuICAgICAgdW5ncm91cGVkTm9kZXM6IFtdLFxuICAgICAgYWxsR3JvdXBlZE5vZGVzOiBbXSxcbiAgICAgIGFsbE5vZGVzOiBbXVxuICAgIH1cbiAgKSwgaSA9IHMuZmlsdGVyKChhKSA9PiAhdC5zb21lKCh7IGlkOiBoIH0pID0+IGggPT09IGEuY2hpbGRPZikpO1xuICByZXR1cm4geyB1bmdyb3VwZWROb2RlczogZSwgZ3JvdXBlZE5vZGVzOiBpLCBhbGxOb2RlczogcyB9O1xufSwgZ2kgPSAobCwgZSkgPT4gKGwuZmlsdGVyKChzKSA9PiAhIXMuZGlzYWJsZWQpLmZvckVhY2goXG4gICh7IGlkOiBzIH0pID0+IEl0KHsgaWQ6IHMsIGNoZWNrZWQ6ICExIH0sIGwsIGUpXG4pLCBsKSwgYnQgPSAobCwgeyBpZDogZSwgaXNDbG9zZWQ6IHQgfSkgPT4ge1xuICBmdCh7IGlkOiBlIH0sIGwpLmZvckVhY2goKGkpID0+IHtcbiAgICBpLmhpZGRlbiA9IHQgPz8gITEsIGkuaXNHcm91cCAmJiAhaS5pc0Nsb3NlZCAmJiBidChsLCB7IGlkOiBpLmlkLCBpc0Nsb3NlZDogdCB9KTtcbiAgfSk7XG59LCBraSA9IChsKSA9PiB7XG4gIGwuZmlsdGVyKChlKSA9PiBlLmlzR3JvdXAgJiYgIWUuZGlzYWJsZWQgJiYgKGUuY2hlY2tlZCB8fCBlLmlzUGFydGlhbENoZWNrZWQpKS5mb3JFYWNoKChlKSA9PiB7XG4gICAgZS5pc0Nsb3NlZCA9ICExLCBidChsLCBlKTtcbiAgfSk7XG59LCB3aSA9IChsLCBlKSA9PiB7XG4gIGNvbnN0IHQgPSBFaShsLCBlKTtcbiAgbC5mb3JFYWNoKChzKSA9PiB7XG4gICAgdC5zb21lKCh7IGlkOiBhIH0pID0+IGEgPT09IHMuaWQpID8gKHMuaXNHcm91cCAmJiAocy5pc0Nsb3NlZCA9ICExLCBidChsLCBzKSksIHMuaGlkZGVuID0gITEpIDogcy5oaWRkZW4gPSAhMDtcbiAgfSk7XG59LCBFaSA9IChsLCBlKSA9PiBsLnJlZHVjZSgodCwgcykgPT4ge1xuICBpZiAocy5uYW1lLnRvTG93ZXJDYXNlKCkuaW5jbHVkZXMoZS50b0xvd2VyQ2FzZSgpKSkge1xuICAgIGlmICh0LnB1c2gocyksIHMuaXNHcm91cCkge1xuICAgICAgY29uc3QgYSA9IHJzKHMuaWQsIGwpO1xuICAgICAgdC5wdXNoKC4uLmEpO1xuICAgIH1cbiAgICBpZiAocy5jaGlsZE9mKSB7XG4gICAgICBjb25zdCBhID0gY3Mocy5jaGlsZE9mLCBsKTtcbiAgICAgIHQucHVzaCguLi5hKTtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIHQ7XG59LCBbXSksIHJzID0gKGwsIGUpID0+IGUucmVkdWNlKCh0LCBzKSA9PiAocy5jaGlsZE9mID09PSBsICYmICh0LnB1c2gocyksIHMuaXNHcm91cCAmJiB0LnB1c2goLi4ucnMocy5pZCwgZSkpKSwgdCksIFtdKSwgY3MgPSAobCwgZSkgPT4gZS5yZWR1Y2UoKHQsIHMpID0+IChzLmlkID09PSBsICYmICh0LnB1c2gocyksIHMuY2hpbGRPZiAmJiB0LnB1c2goLi4uY3Mocy5jaGlsZE9mLCBlKSkpLCB0KSwgW10pLCB2aSA9IChsKSA9PiB7XG4gIGNvbnN0IHsgZHVwbGljYXRpb25zOiBlIH0gPSBsLnJlZHVjZShcbiAgICAodCwgcykgPT4gKHQuYWxsSXRlbXMuc29tZSgoaSkgPT4gaS50b1N0cmluZygpID09PSBzLmlkLnRvU3RyaW5nKCkpICYmIHQuZHVwbGljYXRpb25zLnB1c2gocy5pZCksIHQuYWxsSXRlbXMucHVzaChzLmlkKSwgdCksXG4gICAge1xuICAgICAgZHVwbGljYXRpb25zOiBbXSxcbiAgICAgIGFsbEl0ZW1zOiBbXVxuICAgIH1cbiAgKTtcbiAgZS5sZW5ndGggJiYgY29uc29sZS5lcnJvcihgVmFsaWRhdGlvbjogWW91IGhhdmUgZHVwbGljYXRlZCB2YWx1ZXM6ICR7ZS5qb2luKFwiLCBcIil9ISBZb3Ugc2hvdWxkIHVzZSB1bmlxdWUgdmFsdWVzLmApO1xufSwgTGkgPSAobCwgZSwgdCwgcywgaSwgYSwgaCwgZCwgQywgZikgPT4ge1xuICBzcyhsLCBlLCBpLCBDKSwgZCAmJiBoICYmIGtpKGUpLCBjZShlLCB0LCBzLCBhLCBmKTtcbn0sIGNlID0gKGwsIGUsIHQsIHMsIGkpID0+IHtcbiAgbC5mb3JFYWNoKChhKSA9PiB7XG4gICAgY29uc3QgaCA9IGUucXVlcnlTZWxlY3RvcihgW2lucHV0LWlkPVwiJHthLmlkfVwiXWApLCBkID0gVChoKTtcbiAgICBoLmNoZWNrZWQgPSBhLmNoZWNrZWQsIHlpKGEsIGQsIHMpLCB4aShhLCBkKSwgU2koYSwgZCksIF9pKGEsIGQsIHQpLCBBaShhLCBkKSwgTmkoYSwgZCwgbCwgaSksIFRpKGEsIGgsIHQpO1xuICB9KSwgT2kobCwgZSk7XG59LCB5aSA9IChsLCBlLCB0KSA9PiB7XG4gIGwuY2hlY2tlZCA/IGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tY2hlY2tlZFwiKSA6IGUuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tY2hlY2tlZFwiKSwgQXJyYXkuaXNBcnJheSh0KSAmJiB0WzBdID09PSBsLmlkICYmICFsLmRpc2FibGVkID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1zaW5nbGUtc2VsZWN0ZWRcIikgOiBlLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLXNpbmdsZS1zZWxlY3RlZFwiKTtcbn0sIHhpID0gKGwsIGUpID0+IHtcbiAgbC5pc1BhcnRpYWxDaGVja2VkID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1wYXJ0aWFsLWNoZWNrZWRcIikgOiBlLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLXBhcnRpYWwtY2hlY2tlZFwiKTtcbn0sIFNpID0gKGwsIGUpID0+IHtcbiAgbC5kaXNhYmxlZCA/IGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZGlzYWJsZWRcIikgOiBlLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWRpc2FibGVkXCIpO1xufSwgX2kgPSAobCwgZSwgdCkgPT4ge1xuICBpZiAobC5pc0dyb3VwKSB7XG4gICAgY29uc3QgcyA9IGUucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0taWNvblwiKSwgaSA9IGwuaXNDbG9zZWQgPyB0LmFycm93UmlnaHQgOiB0LmFycm93RG93bjtcbiAgICBJKGksIHMpLCBsLmlzQ2xvc2VkID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1jbG9zZWRcIikgOiBlLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWNsb3NlZFwiKTtcbiAgfVxufSwgQWkgPSAobCwgZSkgPT4ge1xuICBsLmhpZGRlbiA/IGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0taGlkZGVuXCIpIDogZS5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1oaWRkZW5cIik7XG59LCBUaSA9IChsLCBlLCB0KSA9PiB7XG4gIGNvbnN0IGkgPSBlLnBhcmVudE5vZGUucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3gtaWNvblwiKTtcbiAgbC5jaGVja2VkID8gSSh0LmNoZWNrLCBpKSA6IGwuaXNQYXJ0aWFsQ2hlY2tlZCA/IEkodC5wYXJ0aWFsQ2hlY2ssIGkpIDogaS5pbm5lckhUTUwgPSBcIlwiO1xufSwgTmkgPSAobCwgZSwgdCwgcykgPT4ge1xuICBjb25zdCBpID0gbC5sZXZlbCA9PT0gMCwgYSA9IDIwLCBoID0gNTtcbiAgaWYgKGkpIHtcbiAgICBjb25zdCBkID0gdC5zb21lKChiKSA9PiBiLmlzR3JvdXAgJiYgYi5sZXZlbCA9PT0gbC5sZXZlbCksIEMgPSAhbC5pc0dyb3VwICYmIGQgPyBgJHthfXB4YCA6IGAke2h9cHhgLCBmID0gbC5pc0dyb3VwID8gXCIwXCIgOiBDO1xuICAgIHMgPyBlLnN0eWxlLnBhZGRpbmdSaWdodCA9IGYgOiBlLnN0eWxlLnBhZGRpbmdMZWZ0ID0gZjtcbiAgfSBlbHNlIHtcbiAgICBjb25zdCBkID0gbC5pc0dyb3VwID8gYCR7bC5sZXZlbCAqIGF9cHhgIDogYCR7bC5sZXZlbCAqIGEgKyBhfXB4YDtcbiAgICBzID8gZS5zdHlsZS5wYWRkaW5nUmlnaHQgPSBkIDogZS5zdHlsZS5wYWRkaW5nTGVmdCA9IGQ7XG4gIH1cbiAgZS5zZXRBdHRyaWJ1dGUoXCJsZXZlbFwiLCBsLmxldmVsLnRvU3RyaW5nKCkpLCBlLnNldEF0dHJpYnV0ZShcImdyb3VwXCIsIGwuaXNHcm91cC50b1N0cmluZygpKTtcbn0sIE9pID0gKGwsIGUpID0+IHtcbiAgY29uc3QgdCA9IGwuc29tZSgoaSkgPT4gIWkuaGlkZGVuKSwgcyA9IGUucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2VtcHR5XCIpO1xuICB0ID8gcy5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19lbXB0eS0taGlkZGVuXCIpIDogcy5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0X19lbXB0eS0taGlkZGVuXCIpO1xufSwgVCA9IChsKSA9PiBsLnBhcmVudE5vZGUucGFyZW50Tm9kZSwgVnQgPSAobCwgZSkgPT4gZS5maW5kKCh0KSA9PiB0LmlkLnRvU3RyaW5nKCkgPT09IGwpLCBJaSA9IChsKSA9PiBUKGwpLnF1ZXJ5U2VsZWN0b3IoXCIudHJlZXNlbGVjdC1saXN0X19pdGVtLWljb25cIiksIFBpID0gKGwsIGUpID0+IHtcbiAgZSAmJiBPYmplY3Qua2V5cyhlKS5mb3JFYWNoKCh0KSA9PiB7XG4gICAgY29uc3QgcyA9IGVbdF07XG4gICAgdHlwZW9mIHMgPT0gXCJzdHJpbmdcIiAmJiBsLnNldEF0dHJpYnV0ZSh0LCBzKTtcbiAgfSk7XG59O1xudmFyIE0sIFAsIFMsIFksIE9lLCBocywgSWUsIGRzLCBQZSwgdXMsIEJlLCBwcywgVmUsIG1zLCBEZSwgZnMsIEssIHZ0LCBIZSwgYnMsIEdlLCBDcywgTWUsIGdzLCBYLCBMdCwgRmUsIGtzLCBxZSwgd3MsIGplLCBFcywgUmUsIHZzLCAkZSwgTHMsIFdlLCB5cywgVWUsIHhzLCB6ZSwgU3MsIFllLCBfcywgS2UsIEFzLCBYZSwgVHMsIEosIHl0LCBaLCB4dCwgSmUsIE5zO1xuY2xhc3MgQmkge1xuICBjb25zdHJ1Y3Rvcih7XG4gICAgb3B0aW9uczogZSxcbiAgICB2YWx1ZTogdCxcbiAgICBvcGVuTGV2ZWw6IHMsXG4gICAgbGlzdFNsb3RIdG1sQ29tcG9uZW50OiBpLFxuICAgIGVtcHR5VGV4dDogYSxcbiAgICBpc1NpbmdsZVNlbGVjdDogaCxcbiAgICBpY29uRWxlbWVudHM6IGQsXG4gICAgc2hvd0NvdW50OiBDLFxuICAgIGRpc2FibGVkQnJhbmNoTm9kZTogZixcbiAgICBleHBhbmRTZWxlY3RlZDogYixcbiAgICBpc0luZGVwZW5kZW50Tm9kZXM6IGcsXG4gICAgcnRsOiBrLFxuICAgIGlucHV0Q2FsbGJhY2s6IHcsXG4gICAgYXJyb3dDbGlja0NhbGxiYWNrOiB5LFxuICAgIG1vdXNldXBDYWxsYmFjazogeFxuICB9KSB7XG4gICAgLy8gUHJpdmF0ZSBtZXRob2RzXG4gICAgcih0aGlzLCBPZSk7XG4gICAgcih0aGlzLCBJZSk7XG4gICAgcih0aGlzLCBQZSk7XG4gICAgcih0aGlzLCBCZSk7XG4gICAgcih0aGlzLCBWZSk7XG4gICAgcih0aGlzLCBEZSk7XG4gICAgcih0aGlzLCBLKTtcbiAgICByKHRoaXMsIEhlKTtcbiAgICByKHRoaXMsIEdlKTtcbiAgICByKHRoaXMsIE1lKTtcbiAgICByKHRoaXMsIFgpO1xuICAgIHIodGhpcywgRmUpO1xuICAgIHIodGhpcywgcWUpO1xuICAgIHIodGhpcywgamUpO1xuICAgIHIodGhpcywgUmUpO1xuICAgIHIodGhpcywgJGUpO1xuICAgIHIodGhpcywgV2UpO1xuICAgIHIodGhpcywgVWUpO1xuICAgIHIodGhpcywgemUpO1xuICAgIHIodGhpcywgWWUpO1xuICAgIC8vIEFjdGlvbnNcbiAgICByKHRoaXMsIEtlKTtcbiAgICByKHRoaXMsIFhlKTtcbiAgICByKHRoaXMsIEopO1xuICAgIHIodGhpcywgWik7XG4gICAgLy8gRW1pdHNcbiAgICByKHRoaXMsIEplKTtcbiAgICAvLyBQcm9wc1xuICAgIGModGhpcywgXCJvcHRpb25zXCIpO1xuICAgIGModGhpcywgXCJ2YWx1ZVwiKTtcbiAgICBjKHRoaXMsIFwib3BlbkxldmVsXCIpO1xuICAgIGModGhpcywgXCJsaXN0U2xvdEh0bWxDb21wb25lbnRcIik7XG4gICAgYyh0aGlzLCBcImVtcHR5VGV4dFwiKTtcbiAgICBjKHRoaXMsIFwiaXNTaW5nbGVTZWxlY3RcIik7XG4gICAgYyh0aGlzLCBcInNob3dDb3VudFwiKTtcbiAgICBjKHRoaXMsIFwiZGlzYWJsZWRCcmFuY2hOb2RlXCIpO1xuICAgIGModGhpcywgXCJleHBhbmRTZWxlY3RlZFwiKTtcbiAgICBjKHRoaXMsIFwiaXNJbmRlcGVuZGVudE5vZGVzXCIpO1xuICAgIGModGhpcywgXCJydGxcIik7XG4gICAgYyh0aGlzLCBcImljb25FbGVtZW50c1wiKTtcbiAgICAvLyBJbm5lclN0YXRlXG4gICAgYyh0aGlzLCBcInNlYXJjaFRleHRcIik7XG4gICAgYyh0aGlzLCBcImZsYXR0ZWRPcHRpb25zXCIpO1xuICAgIGModGhpcywgXCJmbGF0dGVkT3B0aW9uc0JlZm9yZVNlYXJjaFwiKTtcbiAgICBjKHRoaXMsIFwic2VsZWN0ZWROb2Rlc1wiKTtcbiAgICBjKHRoaXMsIFwic3JjRWxlbWVudFwiKTtcbiAgICAvLyBDYWxsYmFja3NcbiAgICBjKHRoaXMsIFwiaW5wdXRDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwiYXJyb3dDbGlja0NhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJtb3VzZXVwQ2FsbGJhY2tcIik7XG4gICAgLy8gUHJpdmF0ZUlubmVyU3RhdGVcbiAgICByKHRoaXMsIE0sIG51bGwpO1xuICAgIHIodGhpcywgUCwgITApO1xuICAgIHIodGhpcywgUywgW10pO1xuICAgIHIodGhpcywgWSwgITApO1xuICAgIHRoaXMub3B0aW9ucyA9IGUsIHRoaXMudmFsdWUgPSB0LCB0aGlzLm9wZW5MZXZlbCA9IHMgPz8gMCwgdGhpcy5saXN0U2xvdEh0bWxDb21wb25lbnQgPSBpID8/IG51bGwsIHRoaXMuZW1wdHlUZXh0ID0gYSA/PyBcIk5vIHJlc3VsdHMgZm91bmQuLi5cIiwgdGhpcy5pc1NpbmdsZVNlbGVjdCA9IGggPz8gITEsIHRoaXMuc2hvd0NvdW50ID0gQyA/PyAhMSwgdGhpcy5kaXNhYmxlZEJyYW5jaE5vZGUgPSBmID8/ICExLCB0aGlzLmV4cGFuZFNlbGVjdGVkID0gYiA/PyAhMSwgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMgPSBnID8/ICExLCB0aGlzLnJ0bCA9IGsgPz8gITEsIHRoaXMuaWNvbkVsZW1lbnRzID0gZCwgdGhpcy5zZWFyY2hUZXh0ID0gXCJcIiwgdGhpcy5mbGF0dGVkT3B0aW9ucyA9IGJpKHRoaXMub3B0aW9ucywgdGhpcy5vcGVuTGV2ZWwsIHRoaXMuaXNJbmRlcGVuZGVudE5vZGVzKSwgdGhpcy5mbGF0dGVkT3B0aW9uc0JlZm9yZVNlYXJjaCA9IHRoaXMuZmxhdHRlZE9wdGlvbnMsIHRoaXMuc2VsZWN0ZWROb2RlcyA9IHsgbm9kZXM6IFtdLCBncm91cGVkTm9kZXM6IFtdLCBhbGxOb2RlczogW10gfSwgdGhpcy5zcmNFbGVtZW50ID0gbyh0aGlzLCBQZSwgdXMpLmNhbGwodGhpcyksIHRoaXMuaW5wdXRDYWxsYmFjayA9IHcsIHRoaXMuYXJyb3dDbGlja0NhbGxiYWNrID0geSwgdGhpcy5tb3VzZXVwQ2FsbGJhY2sgPSB4LCB2aSh0aGlzLmZsYXR0ZWRPcHRpb25zKTtcbiAgfVxuICAvLyBQdWJsaWMgbWV0aG9kc1xuICB1cGRhdGVWYWx1ZShlKSB7XG4gICAgdGhpcy52YWx1ZSA9IGUsIG0odGhpcywgUywgdGhpcy5pc1NpbmdsZVNlbGVjdCA/IHRoaXMudmFsdWUgOiBbXSksIExpKFxuICAgICAgZSxcbiAgICAgIHRoaXMuZmxhdHRlZE9wdGlvbnMsXG4gICAgICB0aGlzLnNyY0VsZW1lbnQsXG4gICAgICB0aGlzLmljb25FbGVtZW50cyxcbiAgICAgIHRoaXMuaXNTaW5nbGVTZWxlY3QsXG4gICAgICBuKHRoaXMsIFMpLFxuICAgICAgdGhpcy5leHBhbmRTZWxlY3RlZCxcbiAgICAgIG4odGhpcywgWSksXG4gICAgICB0aGlzLmlzSW5kZXBlbmRlbnROb2RlcyxcbiAgICAgIHRoaXMucnRsXG4gICAgKSwgbSh0aGlzLCBZLCAhMSksIG8odGhpcywgWiwgeHQpLmNhbGwodGhpcyk7XG4gIH1cbiAgdXBkYXRlU2VhcmNoVmFsdWUoZSkge1xuICAgIGlmIChlID09PSB0aGlzLnNlYXJjaFRleHQpXG4gICAgICByZXR1cm47XG4gICAgY29uc3QgdCA9IHRoaXMuc2VhcmNoVGV4dCA9PT0gXCJcIiAmJiBlICE9PSBcIlwiO1xuICAgIHRoaXMuc2VhcmNoVGV4dCA9IGUsIHQgJiYgKHRoaXMuZmxhdHRlZE9wdGlvbnNCZWZvcmVTZWFyY2ggPSBKU09OLnBhcnNlKEpTT04uc3RyaW5naWZ5KHRoaXMuZmxhdHRlZE9wdGlvbnMpKSksIHRoaXMuc2VhcmNoVGV4dCA9PT0gXCJcIiAmJiAodGhpcy5mbGF0dGVkT3B0aW9ucyA9IHRoaXMuZmxhdHRlZE9wdGlvbnNCZWZvcmVTZWFyY2gubWFwKChzKSA9PiB7XG4gICAgICBjb25zdCBpID0gdGhpcy5mbGF0dGVkT3B0aW9ucy5maW5kKChhKSA9PiBhLmlkID09PSBzLmlkKTtcbiAgICAgIHJldHVybiBpLmlzQ2xvc2VkID0gcy5pc0Nsb3NlZCwgaS5oaWRkZW4gPSBzLmhpZGRlbiwgaTtcbiAgICB9KSwgdGhpcy5mbGF0dGVkT3B0aW9uc0JlZm9yZVNlYXJjaCA9IFtdKSwgdGhpcy5zZWFyY2hUZXh0ICYmIHdpKHRoaXMuZmxhdHRlZE9wdGlvbnMsIGUpLCBjZSh0aGlzLmZsYXR0ZWRPcHRpb25zLCB0aGlzLnNyY0VsZW1lbnQsIHRoaXMuaWNvbkVsZW1lbnRzLCBuKHRoaXMsIFMpLCB0aGlzLnJ0bCksIHRoaXMuZm9jdXNGaXJzdExpc3RFbGVtZW50KCk7XG4gIH1cbiAgY2FsbEtleUFjdGlvbihlKSB7XG4gICAgbSh0aGlzLCBQLCAhMSk7XG4gICAgY29uc3QgdCA9IHRoaXMuc3JjRWxlbWVudC5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiKTtcbiAgICBpZiAodCA9PSBudWxsID8gdm9pZCAwIDogdC5jbGFzc0xpc3QuY29udGFpbnMoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWhpZGRlblwiKSlcbiAgICAgIHJldHVybjtcbiAgICBjb25zdCBpID0gZS5rZXk7XG4gICAgaSA9PT0gXCJFbnRlclwiICYmIHQgJiYgdC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudChcIm1vdXNlZG93blwiKSksIChpID09PSBcIkFycm93TGVmdFwiIHx8IGkgPT09IFwiQXJyb3dSaWdodFwiKSAmJiBvKHRoaXMsIE9lLCBocykuY2FsbCh0aGlzLCB0LCBlKSwgKGkgPT09IFwiQXJyb3dEb3duXCIgfHwgaSA9PT0gXCJBcnJvd1VwXCIpICYmIG8odGhpcywgSWUsIGRzKS5jYWxsKHRoaXMsIHQsIGkpO1xuICB9XG4gIGZvY3VzRmlyc3RMaXN0RWxlbWVudCgpIHtcbiAgICBjb25zdCBlID0gXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWZvY3VzZWRcIiwgdCA9IHRoaXMuc3JjRWxlbWVudC5xdWVyeVNlbGVjdG9yKGAuJHtlfWApLCBzID0gQXJyYXkuZnJvbSh0aGlzLnNyY0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3hcIikpLmZpbHRlcihcbiAgICAgIChhKSA9PiB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShUKGEpKS5kaXNwbGF5ICE9PSBcIm5vbmVcIlxuICAgICk7XG4gICAgaWYgKCFzLmxlbmd0aClcbiAgICAgIHJldHVybjtcbiAgICB0ICYmIHQuY2xhc3NMaXN0LnJlbW92ZShlKSwgVChzWzBdKS5jbGFzc0xpc3QuYWRkKGUpO1xuICB9XG4gIGlzTGFzdEZvY3VzZWRFbGVtZW50RXhpc3QoKSB7XG4gICAgcmV0dXJuICEhbih0aGlzLCBNKTtcbiAgfVxufVxuTSA9IG5ldyBXZWFrTWFwKCksIFAgPSBuZXcgV2Vha01hcCgpLCBTID0gbmV3IFdlYWtNYXAoKSwgWSA9IG5ldyBXZWFrTWFwKCksIE9lID0gbmV3IFdlYWtTZXQoKSwgaHMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGlmICghZSlcbiAgICByZXR1cm47XG4gIGNvbnN0IHMgPSB0LmtleSwgYSA9IGUucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3hcIikuZ2V0QXR0cmlidXRlKFwiaW5wdXQtaWRcIiksIGggPSBWdChhLCB0aGlzLmZsYXR0ZWRPcHRpb25zKSwgZCA9IGUucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0taWNvblwiKTtcbiAgcyA9PT0gXCJBcnJvd0xlZnRcIiAmJiAhaC5pc0Nsb3NlZCAmJiBoLmlzR3JvdXAgJiYgKGQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoXCJtb3VzZWRvd25cIikpLCB0LnByZXZlbnREZWZhdWx0KCkpLCBzID09PSBcIkFycm93UmlnaHRcIiAmJiBoLmlzQ2xvc2VkICYmIGguaXNHcm91cCAmJiAoZC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudChcIm1vdXNlZG93blwiKSksIHQucHJldmVudERlZmF1bHQoKSk7XG59LCBJZSA9IG5ldyBXZWFrU2V0KCksIGRzID0gZnVuY3Rpb24oZSwgdCkge1xuICB2YXIgaTtcbiAgY29uc3QgcyA9IEFycmF5LmZyb20odGhpcy5zcmNFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIudHJlZXNlbGVjdC1saXN0X19pdGVtLWNoZWNrYm94XCIpKS5maWx0ZXIoXG4gICAgKGEpID0+IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKFQoYSkpLmRpc3BsYXkgIT09IFwibm9uZVwiXG4gICk7XG4gIGlmIChzLmxlbmd0aClcbiAgICBpZiAoIWUpXG4gICAgICBUKHNbMF0pLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWZvY3VzZWRcIik7XG4gICAgZWxzZSB7XG4gICAgICBjb25zdCBhID0gcy5maW5kSW5kZXgoXG4gICAgICAgICh4KSA9PiBUKHgpLmNsYXNzTGlzdC5jb250YWlucyhcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiKVxuICAgICAgKTtcbiAgICAgIFQoc1thXSkuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiKTtcbiAgICAgIGNvbnN0IGQgPSB0ID09PSBcIkFycm93RG93blwiID8gYSArIDEgOiBhIC0gMSwgQyA9IHQgPT09IFwiQXJyb3dEb3duXCIgPyAwIDogcy5sZW5ndGggLSAxLCBmID0gc1tkXSA/PyBzW0NdLCBiID0gIXNbZF0sIGcgPSBUKGYpO1xuICAgICAgZy5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCIpO1xuICAgICAgY29uc3QgayA9IHRoaXMuc3JjRWxlbWVudC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSwgdyA9IGcuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgICBpZiAoYiAmJiB0ID09PSBcIkFycm93RG93blwiKSB7XG4gICAgICAgIHRoaXMuc3JjRWxlbWVudC5zY3JvbGwoMCwgMCk7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICAgIGlmIChiICYmIHQgPT09IFwiQXJyb3dVcFwiKSB7XG4gICAgICAgIHRoaXMuc3JjRWxlbWVudC5zY3JvbGwoMCwgdGhpcy5zcmNFbGVtZW50LnNjcm9sbEhlaWdodCk7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICAgIGNvbnN0IHkgPSAoKGkgPSB0aGlzLmxpc3RTbG90SHRtbENvbXBvbmVudCkgPT0gbnVsbCA/IHZvaWQgMCA6IGkuY2xpZW50SGVpZ2h0KSA/PyAwO1xuICAgICAgaWYgKGsueSArIGsuaGVpZ2h0IDwgdy55ICsgdy5oZWlnaHQgKyB5KSB7XG4gICAgICAgIHRoaXMuc3JjRWxlbWVudC5zY3JvbGwoMCwgdGhpcy5zcmNFbGVtZW50LnNjcm9sbFRvcCArIHcuaGVpZ2h0KTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgaWYgKGsueSA+IHcueSkge1xuICAgICAgICB0aGlzLnNyY0VsZW1lbnQuc2Nyb2xsKDAsIHRoaXMuc3JjRWxlbWVudC5zY3JvbGxUb3AgLSB3LmhlaWdodCk7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICB9XG59LCBQZSA9IG5ldyBXZWFrU2V0KCksIHVzID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBvKHRoaXMsIEJlLCBwcykuY2FsbCh0aGlzKSwgdCA9IG8odGhpcywgSywgdnQpLmNhbGwodGhpcywgdGhpcy5vcHRpb25zKTtcbiAgZS5hcHBlbmQoLi4udCk7XG4gIGNvbnN0IHMgPSBvKHRoaXMsIEdlLCBDcykuY2FsbCh0aGlzKTtcbiAgZS5hcHBlbmQocyk7XG4gIGNvbnN0IGkgPSBvKHRoaXMsIEhlLCBicykuY2FsbCh0aGlzKTtcbiAgcmV0dXJuIGkgJiYgZS5hcHBlbmQoaSksIGU7XG59LCBCZSA9IG5ldyBXZWFrU2V0KCksIHBzID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICByZXR1cm4gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0XCIpLCB0aGlzLmlzU2luZ2xlU2VsZWN0ICYmIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdC0tc2luZ2xlLXNlbGVjdFwiKSwgdGhpcy5kaXNhYmxlZEJyYW5jaE5vZGUgJiYgZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0LS1kaXNhYmxlZC1icmFuY2gtbm9kZVwiKSwgZS5hZGRFdmVudExpc3RlbmVyKFwibW91c2VvdXRcIiwgKHQpID0+IG8odGhpcywgVmUsIG1zKS5jYWxsKHRoaXMsIHQpKSwgZS5hZGRFdmVudExpc3RlbmVyKFwibW91c2Vtb3ZlXCIsICgpID0+IG8odGhpcywgRGUsIGZzKS5jYWxsKHRoaXMpKSwgZS5hZGRFdmVudExpc3RlbmVyKFwibW91c2V1cFwiLCAoKSA9PiB0aGlzLm1vdXNldXBDYWxsYmFjaygpLCAhMCksIGU7XG59LCBWZSA9IG5ldyBXZWFrU2V0KCksIG1zID0gZnVuY3Rpb24oZSkge1xuICBlLnN0b3BQcm9wYWdhdGlvbigpLCBuKHRoaXMsIE0pICYmIG4odGhpcywgUCkgJiYgbih0aGlzLCBNKS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCIpO1xufSwgRGUgPSBuZXcgV2Vha1NldCgpLCBmcyA9IGZ1bmN0aW9uKCkge1xuICBtKHRoaXMsIFAsICEwKTtcbn0sIEsgPSBuZXcgV2Vha1NldCgpLCB2dCA9IGZ1bmN0aW9uKGUpIHtcbiAgcmV0dXJuIGUucmVkdWNlKCh0LCBzKSA9PiB7XG4gICAgdmFyIGE7XG4gICAgaWYgKChhID0gcy5jaGlsZHJlbikgIT0gbnVsbCAmJiBhLmxlbmd0aCkge1xuICAgICAgY29uc3QgaCA9IG8odGhpcywgTWUsIGdzKS5jYWxsKHRoaXMsIHMpLCBkID0gbyh0aGlzLCBLLCB2dCkuY2FsbCh0aGlzLCBzLmNoaWxkcmVuKTtcbiAgICAgIHJldHVybiBoLmFwcGVuZCguLi5kKSwgdC5wdXNoKGgpLCB0O1xuICAgIH1cbiAgICBjb25zdCBpID0gbyh0aGlzLCBYLCBMdCkuY2FsbCh0aGlzLCBzLCAhMSk7XG4gICAgcmV0dXJuIHQucHVzaChpKSwgdDtcbiAgfSwgW10pO1xufSwgSGUgPSBuZXcgV2Vha1NldCgpLCBicyA9IGZ1bmN0aW9uKCkge1xuICBpZiAoIXRoaXMubGlzdFNsb3RIdG1sQ29tcG9uZW50KVxuICAgIHJldHVybiBudWxsO1xuICBjb25zdCBlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgcmV0dXJuIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9fc2xvdFwiKSwgZS5hcHBlbmRDaGlsZCh0aGlzLmxpc3RTbG90SHRtbENvbXBvbmVudCksIGU7XG59LCBHZSA9IG5ldyBXZWFrU2V0KCksIENzID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2VtcHR5XCIpLCBlLnNldEF0dHJpYnV0ZShcInRpdGxlXCIsIHRoaXMuZW1wdHlUZXh0KTtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICB0LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2VtcHR5LWljb25cIiksIEkodGhpcy5pY29uRWxlbWVudHMuYXR0ZW50aW9uLCB0KTtcbiAgY29uc3QgcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICByZXR1cm4gcy5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19lbXB0eS10ZXh0XCIpLCBzLnRleHRDb250ZW50ID0gdGhpcy5lbXB0eVRleHQsIGUuYXBwZW5kKHQsIHMpLCBlO1xufSwgTWUgPSBuZXcgV2Vha1NldCgpLCBncyA9IGZ1bmN0aW9uKGUpIHtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHQuc2V0QXR0cmlidXRlKFwiZ3JvdXAtY29udGFpbmVyLWlkXCIsIGUudmFsdWUudG9TdHJpbmcoKSksIHQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9fZ3JvdXAtY29udGFpbmVyXCIpO1xuICBjb25zdCBzID0gbyh0aGlzLCBYLCBMdCkuY2FsbCh0aGlzLCBlLCAhMCk7XG4gIHJldHVybiB0LmFwcGVuZENoaWxkKHMpLCB0O1xufSwgWCA9IG5ldyBXZWFrU2V0KCksIEx0ID0gZnVuY3Rpb24oZSwgdCkge1xuICBjb25zdCBzID0gbyh0aGlzLCBGZSwga3MpLmNhbGwodGhpcywgZSk7XG4gIGlmICh0KSB7XG4gICAgY29uc3QgaCA9IG8odGhpcywgJGUsIExzKS5jYWxsKHRoaXMpO1xuICAgIHMuYXBwZW5kQ2hpbGQoaCksIHMuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZ3JvdXBcIik7XG4gIH1cbiAgY29uc3QgaSA9IG8odGhpcywgVWUsIHhzKS5jYWxsKHRoaXMsIGUpLCBhID0gbyh0aGlzLCB6ZSwgU3MpLmNhbGwodGhpcywgZSwgdCk7XG4gIHJldHVybiBzLmFwcGVuZChpLCBhKSwgcztcbn0sIEZlID0gbmV3IFdlYWtTZXQoKSwga3MgPSBmdW5jdGlvbihlKSB7XG4gIGNvbnN0IHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICByZXR1cm4gUGkodCwgZS5odG1sQXR0ciksIHQuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgdC5zZXRBdHRyaWJ1dGUoXCJ0aXRsZVwiLCBlLm5hbWUpLCB0LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW1cIiksIHQuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlb3ZlclwiLCAoKSA9PiBvKHRoaXMsIHFlLCB3cykuY2FsbCh0aGlzLCB0KSwgITApLCB0LmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZW91dFwiLCAoKSA9PiBvKHRoaXMsIGplLCBFcykuY2FsbCh0aGlzLCB0KSwgITApLCB0LmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgKHMpID0+IG8odGhpcywgUmUsIHZzKS5jYWxsKHRoaXMsIHMsIGUpKSwgdDtcbn0sIHFlID0gbmV3IFdlYWtTZXQoKSwgd3MgPSBmdW5jdGlvbihlKSB7XG4gIG4odGhpcywgUCkgJiYgbyh0aGlzLCBKLCB5dCkuY2FsbCh0aGlzLCAhMCwgZSk7XG59LCBqZSA9IG5ldyBXZWFrU2V0KCksIEVzID0gZnVuY3Rpb24oZSkge1xuICBuKHRoaXMsIFApICYmIChvKHRoaXMsIEosIHl0KS5jYWxsKHRoaXMsICExLCBlKSwgbSh0aGlzLCBNLCBlKSk7XG59LCBSZSA9IG5ldyBXZWFrU2V0KCksIHZzID0gZnVuY3Rpb24oZSwgdCkge1xuICB2YXIgYTtcbiAgaWYgKGUucHJldmVudERlZmF1bHQoKSwgZS5zdG9wUHJvcGFnYXRpb24oKSwgKGEgPSB0aGlzLmZsYXR0ZWRPcHRpb25zLmZpbmQoKGgpID0+IGguaWQgPT09IHQudmFsdWUpKSA9PSBudWxsID8gdm9pZCAwIDogYS5kaXNhYmxlZClcbiAgICByZXR1cm47XG4gIGNvbnN0IGkgPSBlLnRhcmdldC5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1jaGVja2JveFwiKTtcbiAgaS5jaGVja2VkID0gIWkuY2hlY2tlZCwgbyh0aGlzLCBLZSwgQXMpLmNhbGwodGhpcywgaSwgdCk7XG59LCAkZSA9IG5ldyBXZWFrU2V0KCksIExzID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKTtcbiAgcmV0dXJuIGUuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLWljb25cIiksIEkodGhpcy5pY29uRWxlbWVudHMuYXJyb3dEb3duLCBlKSwgZS5hZGRFdmVudExpc3RlbmVyKFwibW91c2Vkb3duXCIsICh0KSA9PiBvKHRoaXMsIFdlLCB5cykuY2FsbCh0aGlzLCB0KSksIGU7XG59LCBXZSA9IG5ldyBXZWFrU2V0KCksIHlzID0gZnVuY3Rpb24oZSkge1xuICBlLnByZXZlbnREZWZhdWx0KCksIGUuc3RvcFByb3BhZ2F0aW9uKCksIG8odGhpcywgWGUsIFRzKS5jYWxsKHRoaXMsIGUpO1xufSwgVWUgPSBuZXcgV2Vha1NldCgpLCB4cyA9IGZ1bmN0aW9uKGUpIHtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS1jaGVja2JveC1jb250YWluZXJcIik7XG4gIGNvbnN0IHMgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKTtcbiAgcy5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLWNoZWNrYm94LWljb25cIiksIHMuaW5uZXJIVE1MID0gXCJcIjtcbiAgY29uc3QgaSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJpbnB1dFwiKTtcbiAgcmV0dXJuIGkuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgaS5zZXRBdHRyaWJ1dGUoXCJ0eXBlXCIsIFwiY2hlY2tib3hcIiksIGkuc2V0QXR0cmlidXRlKFwiaW5wdXQtaWRcIiwgZS52YWx1ZS50b1N0cmluZygpKSwgaS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLWNoZWNrYm94XCIpLCB0LmFwcGVuZChzLCBpKSwgdDtcbn0sIHplID0gbmV3IFdlYWtTZXQoKSwgU3MgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGNvbnN0IHMgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwibGFiZWxcIik7XG4gIGlmIChzLnRleHRDb250ZW50ID0gZS5uYW1lLCBzLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tbGFiZWxcIiksIHQgJiYgdGhpcy5zaG93Q291bnQpIHtcbiAgICBjb25zdCBpID0gbyh0aGlzLCBZZSwgX3MpLmNhbGwodGhpcywgZSk7XG4gICAgcy5hcHBlbmRDaGlsZChpKTtcbiAgfVxuICByZXR1cm4gcztcbn0sIFllID0gbmV3IFdlYWtTZXQoKSwgX3MgPSBmdW5jdGlvbihlKSB7XG4gIGNvbnN0IHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic3BhblwiKSwgcyA9IHRoaXMuZmxhdHRlZE9wdGlvbnMuZmlsdGVyKChpKSA9PiBpLmNoaWxkT2YgPT09IGUudmFsdWUpO1xuICByZXR1cm4gdC50ZXh0Q29udGVudCA9IGAoJHtzLmxlbmd0aH0pYCwgdC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLWxhYmVsLWNvdW50ZXJcIiksIHQ7XG59LCBLZSA9IG5ldyBXZWFrU2V0KCksIEFzID0gZnVuY3Rpb24oZSwgdCkge1xuICBjb25zdCBzID0gdGhpcy5mbGF0dGVkT3B0aW9ucy5maW5kKChpKSA9PiBpLmlkID09PSB0LnZhbHVlKTtcbiAgaWYgKHMpIHtcbiAgICBpZiAocyAhPSBudWxsICYmIHMuaXNHcm91cCAmJiB0aGlzLmRpc2FibGVkQnJhbmNoTm9kZSkge1xuICAgICAgY29uc3QgaSA9IElpKGUpO1xuICAgICAgaSA9PSBudWxsIHx8IGkuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoXCJtb3VzZWRvd25cIikpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBpZiAodGhpcy5pc1NpbmdsZVNlbGVjdCkge1xuICAgICAgY29uc3QgW2ldID0gbih0aGlzLCBTKTtcbiAgICAgIGlmIChzLmlkID09PSBpKVxuICAgICAgICByZXR1cm47XG4gICAgICBtKHRoaXMsIFMsIFtzLmlkXSksIHNzKFtzLmlkXSwgdGhpcy5mbGF0dGVkT3B0aW9ucywgdGhpcy5pc1NpbmdsZVNlbGVjdCwgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMpO1xuICAgIH0gZWxzZSB7XG4gICAgICBzLmNoZWNrZWQgPSBlLmNoZWNrZWQ7XG4gICAgICBjb25zdCBpID0gSXQocywgdGhpcy5mbGF0dGVkT3B0aW9ucywgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMpO1xuICAgICAgZS5jaGVja2VkID0gaTtcbiAgICB9XG4gICAgY2UodGhpcy5mbGF0dGVkT3B0aW9ucywgdGhpcy5zcmNFbGVtZW50LCB0aGlzLmljb25FbGVtZW50cywgbih0aGlzLCBTKSwgdGhpcy5ydGwpLCBvKHRoaXMsIEplLCBOcykuY2FsbCh0aGlzKTtcbiAgfVxufSwgWGUgPSBuZXcgV2Vha1NldCgpLCBUcyA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIGEsIGg7XG4gIGNvbnN0IHQgPSAoaCA9IChhID0gZS50YXJnZXQpID09IG51bGwgPyB2b2lkIDAgOiBhLnBhcmVudE5vZGUpID09IG51bGwgPyB2b2lkIDAgOiBoLnF1ZXJ5U2VsZWN0b3IoXCJbaW5wdXQtaWRdXCIpLCBzID0gKHQgPT0gbnVsbCA/IHZvaWQgMCA6IHQuZ2V0QXR0cmlidXRlKFwiaW5wdXQtaWRcIikpID8/IG51bGwsIGkgPSBWdChzLCB0aGlzLmZsYXR0ZWRPcHRpb25zKTtcbiAgaSAmJiAoaS5pc0Nsb3NlZCA9ICFpLmlzQ2xvc2VkLCBidCh0aGlzLmZsYXR0ZWRPcHRpb25zLCBpKSwgY2UodGhpcy5mbGF0dGVkT3B0aW9ucywgdGhpcy5zcmNFbGVtZW50LCB0aGlzLmljb25FbGVtZW50cywgbih0aGlzLCBTKSwgdGhpcy5ydGwpLCB0aGlzLmFycm93Q2xpY2tDYWxsYmFjayhpLmlkLCBpLmlzQ2xvc2VkKSk7XG59LCBKID0gbmV3IFdlYWtTZXQoKSwgeXQgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGNvbnN0IHMgPSBcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiO1xuICBpZiAoZSkge1xuICAgIGNvbnN0IGkgPSBBcnJheS5mcm9tKHRoaXMuc3JjRWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKGAuJHtzfWApKTtcbiAgICBpLmxlbmd0aCAmJiBpLmZvckVhY2goKGEpID0+IGEuY2xhc3NMaXN0LnJlbW92ZShzKSksIHQuY2xhc3NMaXN0LmFkZChzKTtcbiAgfSBlbHNlXG4gICAgdC5jbGFzc0xpc3QucmVtb3ZlKHMpO1xufSwgWiA9IG5ldyBXZWFrU2V0KCksIHh0ID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IHsgdW5ncm91cGVkTm9kZXM6IGUsIGdyb3VwZWROb2RlczogdCwgYWxsTm9kZXM6IHMgfSA9IENpKHRoaXMuZmxhdHRlZE9wdGlvbnMpO1xuICB0aGlzLnNlbGVjdGVkTm9kZXMgPSB7IG5vZGVzOiBlLCBncm91cGVkTm9kZXM6IHQsIGFsbE5vZGVzOiBzIH07XG59LCBKZSA9IG5ldyBXZWFrU2V0KCksIE5zID0gZnVuY3Rpb24oKSB7XG4gIG8odGhpcywgWiwgeHQpLmNhbGwodGhpcyksIHRoaXMuaW5wdXRDYWxsYmFjayh0aGlzLnNlbGVjdGVkTm9kZXMpLCB0aGlzLnZhbHVlID0gdGhpcy5zZWxlY3RlZE5vZGVzLm5vZGVzLm1hcCgoZSkgPT4gZS5pZCk7XG59O1xuY29uc3QgRHQgPSAoe1xuICBwYXJlbnRIdG1sQ29udGFpbmVyOiBsLFxuICBzdGF0aWNMaXN0OiBlLFxuICBhcHBlbmRUb0JvZHk6IHQsXG4gIGlzU2luZ2xlU2VsZWN0OiBzLFxuICB2YWx1ZTogaSxcbiAgZGlyZWN0aW9uOiBhXG59KSA9PiB7XG4gIGwgfHwgY29uc29sZS5lcnJvcihcIlZhbGlkYXRpb246IHBhcmVudEh0bWxDb250YWluZXIgcHJvcCBpcyByZXF1aXJlZCFcIiksIGUgJiYgdCAmJiBjb25zb2xlLmVycm9yKFwiVmFsaWRhdGlvbjogWW91IHNob3VsZCBzZXQgc3RhdGljTGlzdCB0byBmYWxzZSBpZiB5b3UgdXNlIGFwcGVuZFRvQm9keSFcIiksIHMgJiYgQXJyYXkuaXNBcnJheShpKSAmJiBjb25zb2xlLmVycm9yKFwiVmFsaWRhdGlvbjogaWYgeW91IHVzZSBpc1NpbmdsZVNlbGVjdCBwcm9wLCB5b3Ugc2hvdWxkIHBhc3MgYSBzaW5nbGUgdmFsdWUhXCIpLCAhcyAmJiAhQXJyYXkuaXNBcnJheShpKSAmJiBjb25zb2xlLmVycm9yKFwiVmFsaWRhdGlvbjogeW91IHNob3VsZCBwYXNzIGFuIGFycmF5IGFzIGEgdmFsdWUhXCIpLCBhICYmIGEgIT09IFwiYXV0b1wiICYmIGEgIT09IFwiYm90dG9tXCIgJiYgYSAhPT0gXCJ0b3BcIiAmJiBjb25zb2xlLmVycm9yKFwiVmFsaWRhdGlvbjogeW91IHNob3VsZCBwYXNzIChhdXRvIHwgdG9wIHwgYm90dG9tIHwgdW5kZWZpbmVkKSBhcyBhIHZhbHVlIGZvciB0aGUgZGlyZWN0aW9uIHByb3AhXCIpO1xufSwgcmUgPSAobCkgPT4gbC5tYXAoKGUpID0+IGUuaWQpLCBWaSA9IChsKSA9PiBsID8gQXJyYXkuaXNBcnJheShsKSA/IGwgOiBbbF0gOiBbXSwgRGkgPSAobCwgZSkgPT4ge1xuICBpZiAoZSkge1xuICAgIGNvbnN0IFt0XSA9IGw7XG4gICAgcmV0dXJuIHQgPz8gbnVsbDtcbiAgfVxuICByZXR1cm4gbDtcbn07XG52YXIgdSwgcCwgRiwgUSwgcSwgXywgQSwgTCwgQiwgZWUsIFN0LCB0ZSwgX3QsIFplLCBPcywgUWUsIElzLCBldCwgUHMsIHR0LCBCcywgc3QsIFZzLCBpdCwgRHMsIHNlLCBBdCwgbHQsIEhzLCBudCwgR3MsIGF0LCBNcywgb3QsIEZzLCBpZSwgVHQsIHJ0LCBxcywgaiwgaGUsIGxlLCBOdCwgUiwgZGUsIGN0LCBqcywgbmUsIE90LCBodCwgUnMsIGR0LCAkcywgdXQsIFdzLCBwdCwgVXMsIG10LCB6cztcbmNsYXNzIEdpIHtcbiAgY29uc3RydWN0b3Ioe1xuICAgIHBhcmVudEh0bWxDb250YWluZXI6IGUsXG4gICAgdmFsdWU6IHQsXG4gICAgb3B0aW9uczogcyxcbiAgICBvcGVuTGV2ZWw6IGksXG4gICAgYXBwZW5kVG9Cb2R5OiBhLFxuICAgIGFsd2F5c09wZW46IGgsXG4gICAgc2hvd1RhZ3M6IGQsXG4gICAgdGFnc0NvdW50VGV4dDogQyxcbiAgICBjbGVhcmFibGU6IGYsXG4gICAgc2VhcmNoYWJsZTogYixcbiAgICBwbGFjZWhvbGRlcjogZyxcbiAgICBncm91cGVkOiBrLFxuICAgIGlzR3JvdXBlZFZhbHVlOiB3LFxuICAgIGxpc3RTbG90SHRtbENvbXBvbmVudDogeSxcbiAgICBkaXNhYmxlZDogeCxcbiAgICBlbXB0eVRleHQ6ICQsXG4gICAgc3RhdGljTGlzdDogYWUsXG4gICAgaWQ6IEN0LFxuICAgIGFyaWFMYWJlbDogZ3QsXG4gICAgaXNTaW5nbGVTZWxlY3Q6IG9lLFxuICAgIHNob3dDb3VudDogWXMsXG4gICAgZGlzYWJsZWRCcmFuY2hOb2RlOiBLcyxcbiAgICBkaXJlY3Rpb246IFhzLFxuICAgIGV4cGFuZFNlbGVjdGVkOiBKcyxcbiAgICBzYXZlU2Nyb2xsUG9zaXRpb246IFpzLFxuICAgIGlzSW5kZXBlbmRlbnROb2RlczogUXMsXG4gICAgcnRsOiBlaSxcbiAgICBpY29uRWxlbWVudHM6IHRpLFxuICAgIGlucHV0Q2FsbGJhY2s6IHNpLFxuICAgIG9wZW5DYWxsYmFjazogaWksXG4gICAgY2xvc2VDYWxsYmFjazogbGksXG4gICAgbmFtZUNoYW5nZUNhbGxiYWNrOiBuaSxcbiAgICBzZWFyY2hDYWxsYmFjazogYWksXG4gICAgb3BlbkNsb3NlR3JvdXBDYWxsYmFjazogb2lcbiAgfSkge1xuICAgIHIodGhpcywgZWUpO1xuICAgIHIodGhpcywgdGUpO1xuICAgIHIodGhpcywgWmUpO1xuICAgIHIodGhpcywgUWUpO1xuICAgIHIodGhpcywgZXQpO1xuICAgIHIodGhpcywgdHQpO1xuICAgIHIodGhpcywgc3QpO1xuICAgIHIodGhpcywgaXQpO1xuICAgIHIodGhpcywgc2UpO1xuICAgIHIodGhpcywgbHQpO1xuICAgIHIodGhpcywgbnQpO1xuICAgIHIodGhpcywgYXQpO1xuICAgIHIodGhpcywgb3QpO1xuICAgIHIodGhpcywgaWUpO1xuICAgIHIodGhpcywgcnQpO1xuICAgIHIodGhpcywgaik7XG4gICAgcih0aGlzLCBsZSk7XG4gICAgcih0aGlzLCBSKTtcbiAgICByKHRoaXMsIGN0KTtcbiAgICAvLyBFbWl0c1xuICAgIHIodGhpcywgbmUpO1xuICAgIHIodGhpcywgaHQpO1xuICAgIHIodGhpcywgZHQpO1xuICAgIHIodGhpcywgdXQpO1xuICAgIHIodGhpcywgcHQpO1xuICAgIHIodGhpcywgbXQpO1xuICAgIC8vIFByb3BzXG4gICAgYyh0aGlzLCBcInBhcmVudEh0bWxDb250YWluZXJcIik7XG4gICAgYyh0aGlzLCBcInZhbHVlXCIpO1xuICAgIGModGhpcywgXCJvcHRpb25zXCIpO1xuICAgIGModGhpcywgXCJvcGVuTGV2ZWxcIik7XG4gICAgYyh0aGlzLCBcImFwcGVuZFRvQm9keVwiKTtcbiAgICBjKHRoaXMsIFwiYWx3YXlzT3BlblwiKTtcbiAgICBjKHRoaXMsIFwic2hvd1RhZ3NcIik7XG4gICAgYyh0aGlzLCBcInRhZ3NDb3VudFRleHRcIik7XG4gICAgYyh0aGlzLCBcImNsZWFyYWJsZVwiKTtcbiAgICBjKHRoaXMsIFwic2VhcmNoYWJsZVwiKTtcbiAgICBjKHRoaXMsIFwicGxhY2Vob2xkZXJcIik7XG4gICAgYyh0aGlzLCBcImdyb3VwZWRcIik7XG4gICAgYyh0aGlzLCBcImlzR3JvdXBlZFZhbHVlXCIpO1xuICAgIGModGhpcywgXCJsaXN0U2xvdEh0bWxDb21wb25lbnRcIik7XG4gICAgYyh0aGlzLCBcImRpc2FibGVkXCIpO1xuICAgIGModGhpcywgXCJlbXB0eVRleHRcIik7XG4gICAgYyh0aGlzLCBcInN0YXRpY0xpc3RcIik7XG4gICAgYyh0aGlzLCBcImlkXCIpO1xuICAgIGModGhpcywgXCJhcmlhTGFiZWxcIik7XG4gICAgYyh0aGlzLCBcImlzU2luZ2xlU2VsZWN0XCIpO1xuICAgIGModGhpcywgXCJzaG93Q291bnRcIik7XG4gICAgYyh0aGlzLCBcImRpc2FibGVkQnJhbmNoTm9kZVwiKTtcbiAgICBjKHRoaXMsIFwiZGlyZWN0aW9uXCIpO1xuICAgIGModGhpcywgXCJleHBhbmRTZWxlY3RlZFwiKTtcbiAgICBjKHRoaXMsIFwic2F2ZVNjcm9sbFBvc2l0aW9uXCIpO1xuICAgIGModGhpcywgXCJpc0luZGVwZW5kZW50Tm9kZXNcIik7XG4gICAgYyh0aGlzLCBcInJ0bFwiKTtcbiAgICBjKHRoaXMsIFwiaWNvbkVsZW1lbnRzXCIpO1xuICAgIGModGhpcywgXCJpbnB1dENhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJvcGVuQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcImNsb3NlQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm5hbWVDaGFuZ2VDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwic2VhcmNoQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm9wZW5DbG9zZUdyb3VwQ2FsbGJhY2tcIik7XG4gICAgLy8gSW5uZXJTdGF0ZVxuICAgIGModGhpcywgXCJ1bmdyb3VwZWRWYWx1ZVwiKTtcbiAgICBjKHRoaXMsIFwiZ3JvdXBlZFZhbHVlXCIpO1xuICAgIGModGhpcywgXCJhbGxWYWx1ZVwiKTtcbiAgICBjKHRoaXMsIFwiaXNMaXN0T3BlbmVkXCIpO1xuICAgIGModGhpcywgXCJzZWxlY3RlZE5hbWVcIik7XG4gICAgYyh0aGlzLCBcInNyY0VsZW1lbnRcIik7XG4gICAgLy8gQ29tcG9uZW50c1xuICAgIHIodGhpcywgdSwgbnVsbCk7XG4gICAgcih0aGlzLCBwLCBudWxsKTtcbiAgICAvLyBSZXNpemUgcHJvcHNcbiAgICByKHRoaXMsIEYsIG51bGwpO1xuICAgIC8vIExpc3QgcG9zaXRpb24gc2Nyb2xsXG4gICAgcih0aGlzLCBRLCAwKTtcbiAgICAvLyBUaW1lciBmb3Igc2VhcmNoIHRleHRcbiAgICByKHRoaXMsIHEsIDApO1xuICAgIC8vIE91dHNpZGUgbGlzdGVuZXJzXG4gICAgcih0aGlzLCBfLCBudWxsKTtcbiAgICByKHRoaXMsIEEsIG51bGwpO1xuICAgIHIodGhpcywgTCwgbnVsbCk7XG4gICAgcih0aGlzLCBCLCBudWxsKTtcbiAgICBEdCh7XG4gICAgICBwYXJlbnRIdG1sQ29udGFpbmVyOiBlLFxuICAgICAgdmFsdWU6IHQsXG4gICAgICBzdGF0aWNMaXN0OiBhZSxcbiAgICAgIGFwcGVuZFRvQm9keTogYSxcbiAgICAgIGlzU2luZ2xlU2VsZWN0OiBvZVxuICAgIH0pLCB0aGlzLnBhcmVudEh0bWxDb250YWluZXIgPSBlLCB0aGlzLnZhbHVlID0gW10sIHRoaXMub3B0aW9ucyA9IHMgPz8gW10sIHRoaXMub3BlbkxldmVsID0gaSA/PyAwLCB0aGlzLmFwcGVuZFRvQm9keSA9IGEgPz8gITEsIHRoaXMuYWx3YXlzT3BlbiA9ICEhKGggJiYgIXgpLCB0aGlzLnNob3dUYWdzID0gZCA/PyAhMCwgdGhpcy50YWdzQ291bnRUZXh0ID0gQyA/PyBcImVsZW1lbnRzIHNlbGVjdGVkXCIsIHRoaXMuY2xlYXJhYmxlID0gZiA/PyAhMCwgdGhpcy5zZWFyY2hhYmxlID0gYiA/PyAhMCwgdGhpcy5wbGFjZWhvbGRlciA9IGcgPz8gXCJTZWFyY2guLi5cIiwgdGhpcy5ncm91cGVkID0gayA/PyAhMCwgdGhpcy5pc0dyb3VwZWRWYWx1ZSA9IHcgPz8gITEsIHRoaXMubGlzdFNsb3RIdG1sQ29tcG9uZW50ID0geSA/PyBudWxsLCB0aGlzLmRpc2FibGVkID0geCA/PyAhMSwgdGhpcy5lbXB0eVRleHQgPSAkID8/IFwiTm8gcmVzdWx0cyBmb3VuZC4uLlwiLCB0aGlzLnN0YXRpY0xpc3QgPSAhIShhZSAmJiAhdGhpcy5hcHBlbmRUb0JvZHkpLCB0aGlzLmlkID0gQ3QgPz8gXCJcIiwgdGhpcy5hcmlhTGFiZWwgPSBndCA/PyBcIlwiLCB0aGlzLmlzU2luZ2xlU2VsZWN0ID0gb2UgPz8gITEsIHRoaXMuc2hvd0NvdW50ID0gWXMgPz8gITEsIHRoaXMuZGlzYWJsZWRCcmFuY2hOb2RlID0gS3MgPz8gITEsIHRoaXMuZGlyZWN0aW9uID0gWHMgPz8gXCJhdXRvXCIsIHRoaXMuZXhwYW5kU2VsZWN0ZWQgPSBKcyA/PyAhMSwgdGhpcy5zYXZlU2Nyb2xsUG9zaXRpb24gPSBacyA/PyAhMCwgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMgPSBRcyA/PyAhMSwgdGhpcy5ydGwgPSBlaSA/PyAhMSwgdGhpcy5pY29uRWxlbWVudHMgPSBCdCh0aSksIHRoaXMuaW5wdXRDYWxsYmFjayA9IHNpLCB0aGlzLm9wZW5DYWxsYmFjayA9IGlpLCB0aGlzLmNsb3NlQ2FsbGJhY2sgPSBsaSwgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2sgPSBuaSwgdGhpcy5zZWFyY2hDYWxsYmFjayA9IGFpLCB0aGlzLm9wZW5DbG9zZUdyb3VwQ2FsbGJhY2sgPSBvaSwgdGhpcy51bmdyb3VwZWRWYWx1ZSA9IFtdLCB0aGlzLmdyb3VwZWRWYWx1ZSA9IFtdLCB0aGlzLmFsbFZhbHVlID0gW10sIHRoaXMuaXNMaXN0T3BlbmVkID0gITEsIHRoaXMuc2VsZWN0ZWROYW1lID0gXCJcIiwgdGhpcy5zcmNFbGVtZW50ID0gbnVsbCwgbyh0aGlzLCBlZSwgU3QpLmNhbGwodGhpcywgdCk7XG4gIH1cbiAgbW91bnQoKSB7XG4gICAgRHQoe1xuICAgICAgcGFyZW50SHRtbENvbnRhaW5lcjogdGhpcy5wYXJlbnRIdG1sQ29udGFpbmVyLFxuICAgICAgdmFsdWU6IHRoaXMudmFsdWUsXG4gICAgICBzdGF0aWNMaXN0OiB0aGlzLnN0YXRpY0xpc3QsXG4gICAgICBhcHBlbmRUb0JvZHk6IHRoaXMuYXBwZW5kVG9Cb2R5LFxuICAgICAgaXNTaW5nbGVTZWxlY3Q6IHRoaXMuaXNTaW5nbGVTZWxlY3RcbiAgICB9KSwgdGhpcy5pY29uRWxlbWVudHMgPSBCdCh0aGlzLmljb25FbGVtZW50cyksIG8odGhpcywgZWUsIFN0KS5jYWxsKHRoaXMsIHRoaXMudmFsdWUpO1xuICB9XG4gIHVwZGF0ZVZhbHVlKGUpIHtcbiAgICBjb25zdCB0ID0gVmkoZSksIHMgPSBuKHRoaXMsIHUpO1xuICAgIHMgJiYgKHMudXBkYXRlVmFsdWUodCksIG8odGhpcywgc2UsIEF0KS5jYWxsKHRoaXMsIHMgPT0gbnVsbCA/IHZvaWQgMCA6IHMuc2VsZWN0ZWROb2RlcykpO1xuICB9XG4gIGRlc3Ryb3koKSB7XG4gICAgdGhpcy5zcmNFbGVtZW50ICYmIChvKHRoaXMsIGllLCBUdCkuY2FsbCh0aGlzKSwgdGhpcy5zcmNFbGVtZW50LmlubmVySFRNTCA9IFwiXCIsIHRoaXMuc3JjRWxlbWVudCA9IG51bGwsIG8odGhpcywgUiwgZGUpLmNhbGwodGhpcywgITApKTtcbiAgfVxuICBmb2N1cygpIHtcbiAgICBuKHRoaXMsIHApICYmIG4odGhpcywgcCkuZm9jdXMoKTtcbiAgfVxuICB0b2dnbGVPcGVuQ2xvc2UoKSB7XG4gICAgbih0aGlzLCBwKSAmJiAobih0aGlzLCBwKS5vcGVuQ2xvc2UoKSwgbih0aGlzLCBwKS5mb2N1cygpKTtcbiAgfVxuICAvLyBPdXRzaWRlIExpc3RlbmVyc1xuICBzY3JvbGxXaW5kb3dIYW5kbGVyKCkge1xuICAgIHRoaXMudXBkYXRlTGlzdFBvc2l0aW9uKCk7XG4gIH1cbiAgZm9jdXNXaW5kb3dIYW5kbGVyKGUpIHtcbiAgICB2YXIgcywgaSwgYTtcbiAgICAoKHMgPSB0aGlzLnNyY0VsZW1lbnQpID09IG51bGwgPyB2b2lkIDAgOiBzLmNvbnRhaW5zKGUudGFyZ2V0KSkgfHwgKChpID0gbih0aGlzLCB1KSkgPT0gbnVsbCA/IHZvaWQgMCA6IGkuc3JjRWxlbWVudC5jb250YWlucyhlLnRhcmdldCkpIHx8ICgoYSA9IG4odGhpcywgcCkpID09IG51bGwgfHwgYS5ibHVyKCksIG8odGhpcywgUiwgZGUpLmNhbGwodGhpcywgITEpLCBvKHRoaXMsIGosIGhlKS5jYWxsKHRoaXMsICExKSk7XG4gIH1cbiAgYmx1cldpbmRvd0hhbmRsZXIoKSB7XG4gICAgdmFyIGU7XG4gICAgKGUgPSBuKHRoaXMsIHApKSA9PSBudWxsIHx8IGUuYmx1cigpLCBvKHRoaXMsIFIsIGRlKS5jYWxsKHRoaXMsICExKSwgbyh0aGlzLCBqLCBoZSkuY2FsbCh0aGlzLCAhMSk7XG4gIH1cbiAgLy8gVXBkYXRlIGRpcmVjdGlvbiBvZiB0aGUgbGlzdC4gU3VwcG9ydCBhcHBlbmRUb0JvZHkgYW5kIHN0YW5kYXJkIG1vZGUgd2l0aCBhYnNvbHV0ZVxuICB1cGRhdGVMaXN0UG9zaXRpb24oKSB7XG4gICAgdmFyIHk7XG4gICAgY29uc3QgZSA9IHRoaXMuc3JjRWxlbWVudCwgdCA9ICh5ID0gbih0aGlzLCB1KSkgPT0gbnVsbCA/IHZvaWQgMCA6IHkuc3JjRWxlbWVudDtcbiAgICBpZiAoIWUgfHwgIXQpXG4gICAgICByZXR1cm47XG4gICAgY29uc3QgeyBoZWlnaHQ6IHMgfSA9IHQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCksIHtcbiAgICAgIHg6IGksXG4gICAgICB5OiBhLFxuICAgICAgaGVpZ2h0OiBoLFxuICAgICAgd2lkdGg6IGRcbiAgICB9ID0gZS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSwgQyA9IHdpbmRvdy5pbm5lckhlaWdodCwgZiA9IGEsIGIgPSBDIC0gYSAtIGg7XG4gICAgbGV0IGcgPSBmID4gYiAmJiBmID49IHMgJiYgYiA8IHM7XG4gICAgaWYgKHRoaXMuZGlyZWN0aW9uICE9PSBcImF1dG9cIiAmJiAoZyA9IHRoaXMuZGlyZWN0aW9uID09PSBcInRvcFwiKSwgdGhpcy5hcHBlbmRUb0JvZHkpIHtcbiAgICAgICh0LnN0eWxlLnRvcCAhPT0gXCIwcHhcIiB8fCB0LnN0eWxlLmxlZnQgIT09IFwiMHB4XCIpICYmICh0LnN0eWxlLnRvcCA9IFwiMHB4XCIsIHQuc3R5bGUubGVmdCA9IFwiMHB4XCIpO1xuICAgICAgY29uc3QgeCA9IGkgKyB3aW5kb3cuc2Nyb2xsWCwgJCA9IGcgPyBhICsgd2luZG93LnNjcm9sbFkgLSBzIDogYSArIHdpbmRvdy5zY3JvbGxZICsgaDtcbiAgICAgIHQuc3R5bGUudHJhbnNmb3JtID0gYHRyYW5zbGF0ZSgke3h9cHgsJHskfXB4KWAsIHQuc3R5bGUud2lkdGggPSBgJHtkfXB4YDtcbiAgICB9XG4gICAgY29uc3QgayA9IGcgPyBcInRvcFwiIDogXCJib3R0b21cIjtcbiAgICB0LmdldEF0dHJpYnV0ZShcImRpcmVjdGlvblwiKSAhPT0gayAmJiAodC5zZXRBdHRyaWJ1dGUoXCJkaXJlY3Rpb25cIiwgayksIG8odGhpcywgcnQsIHFzKS5jYWxsKHRoaXMsIGcsIHRoaXMuYXBwZW5kVG9Cb2R5KSk7XG4gIH1cbn1cbnUgPSBuZXcgV2Vha01hcCgpLCBwID0gbmV3IFdlYWtNYXAoKSwgRiA9IG5ldyBXZWFrTWFwKCksIFEgPSBuZXcgV2Vha01hcCgpLCBxID0gbmV3IFdlYWtNYXAoKSwgXyA9IG5ldyBXZWFrTWFwKCksIEEgPSBuZXcgV2Vha01hcCgpLCBMID0gbmV3IFdlYWtNYXAoKSwgQiA9IG5ldyBXZWFrTWFwKCksIGVlID0gbmV3IFdlYWtTZXQoKSwgU3QgPSBmdW5jdGlvbihlKSB7XG4gIHZhciBhO1xuICB0aGlzLmRlc3Ryb3koKTtcbiAgY29uc3QgeyBjb250YWluZXI6IHQsIGxpc3Q6IHMsIGlucHV0OiBpIH0gPSBvKHRoaXMsIFplLCBPcykuY2FsbCh0aGlzKTtcbiAgdGhpcy5zcmNFbGVtZW50ID0gdCwgbSh0aGlzLCB1LCBzKSwgbSh0aGlzLCBwLCBpKSwgbSh0aGlzLCBfLCB0aGlzLnNjcm9sbFdpbmRvd0hhbmRsZXIuYmluZCh0aGlzKSksIG0odGhpcywgQSwgdGhpcy5zY3JvbGxXaW5kb3dIYW5kbGVyLmJpbmQodGhpcykpLCBtKHRoaXMsIEwsIHRoaXMuZm9jdXNXaW5kb3dIYW5kbGVyLmJpbmQodGhpcykpLCBtKHRoaXMsIEIsIHRoaXMuYmx1cldpbmRvd0hhbmRsZXIuYmluZCh0aGlzKSksIHRoaXMuYWx3YXlzT3BlbiAmJiAoKGEgPSBuKHRoaXMsIHApKSA9PSBudWxsIHx8IGEub3BlbkNsb3NlKCkpLCB0aGlzLmRpc2FibGVkID8gdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LS1kaXNhYmxlZFwiKSA6IHRoaXMuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC0tZGlzYWJsZWRcIiksIHRoaXMudXBkYXRlVmFsdWUoZSA/PyB0aGlzLnZhbHVlKTtcbn0sIHRlID0gbmV3IFdlYWtTZXQoKSwgX3QgPSBmdW5jdGlvbih7XG4gIGdyb3VwZWROb2RlczogZSxcbiAgbm9kZXM6IHQsXG4gIGFsbE5vZGVzOiBzXG59KSB7XG4gIHRoaXMudW5ncm91cGVkVmFsdWUgPSB0ID8gcmUodCkgOiBbXSwgdGhpcy5ncm91cGVkVmFsdWUgPSBlID8gcmUoZSkgOiBbXSwgdGhpcy5hbGxWYWx1ZSA9IHMgPyByZShzKSA6IFtdO1xuICBsZXQgaSA9IFtdO1xuICB0aGlzLmlzSW5kZXBlbmRlbnROb2RlcyB8fCB0aGlzLmlzU2luZ2xlU2VsZWN0ID8gaSA9IHRoaXMuYWxsVmFsdWUgOiB0aGlzLmlzR3JvdXBlZFZhbHVlID8gaSA9IHRoaXMuZ3JvdXBlZFZhbHVlIDogaSA9IHRoaXMudW5ncm91cGVkVmFsdWUsIHRoaXMudmFsdWUgPSBEaShpLCB0aGlzLmlzU2luZ2xlU2VsZWN0KTtcbn0sIFplID0gbmV3IFdlYWtTZXQoKSwgT3MgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IHRoaXMucGFyZW50SHRtbENvbnRhaW5lcjtcbiAgZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdFwiKSwgdGhpcy5ydGwgJiYgZS5zZXRBdHRyaWJ1dGUoXCJkaXJcIiwgXCJydGxcIik7XG4gIGNvbnN0IHQgPSBuZXcgQmkoe1xuICAgIHZhbHVlOiBbXSxcbiAgICAvLyB1cGRhdGVWYWx1ZSBtZXRob2QgY2FsbHMgaW4gaW5pdE1vdW50IG1ldGhvZCB0byBzZXQgYWN0dWFsIHZhbHVlXG4gICAgb3B0aW9uczogdGhpcy5vcHRpb25zLFxuICAgIG9wZW5MZXZlbDogdGhpcy5vcGVuTGV2ZWwsXG4gICAgbGlzdFNsb3RIdG1sQ29tcG9uZW50OiB0aGlzLmxpc3RTbG90SHRtbENvbXBvbmVudCxcbiAgICBlbXB0eVRleHQ6IHRoaXMuZW1wdHlUZXh0LFxuICAgIGlzU2luZ2xlU2VsZWN0OiB0aGlzLmlzU2luZ2xlU2VsZWN0LFxuICAgIHNob3dDb3VudDogdGhpcy5zaG93Q291bnQsXG4gICAgZGlzYWJsZWRCcmFuY2hOb2RlOiB0aGlzLmRpc2FibGVkQnJhbmNoTm9kZSxcbiAgICBleHBhbmRTZWxlY3RlZDogdGhpcy5leHBhbmRTZWxlY3RlZCxcbiAgICBpc0luZGVwZW5kZW50Tm9kZXM6IHRoaXMuaXNJbmRlcGVuZGVudE5vZGVzLFxuICAgIHJ0bDogdGhpcy5ydGwsXG4gICAgaWNvbkVsZW1lbnRzOiB0aGlzLmljb25FbGVtZW50cyxcbiAgICBpbnB1dENhbGxiYWNrOiAoaSkgPT4gbyh0aGlzLCBsdCwgSHMpLmNhbGwodGhpcywgaSksXG4gICAgYXJyb3dDbGlja0NhbGxiYWNrOiAoaSwgYSkgPT4gbyh0aGlzLCBudCwgR3MpLmNhbGwodGhpcywgaSwgYSksXG4gICAgbW91c2V1cENhbGxiYWNrOiAoKSA9PiB7XG4gICAgICB2YXIgaTtcbiAgICAgIHJldHVybiAoaSA9IG4odGhpcywgcCkpID09IG51bGwgPyB2b2lkIDAgOiBpLmZvY3VzKCk7XG4gICAgfVxuICB9KSwgcyA9IG5ldyBkaSh7XG4gICAgdmFsdWU6IFtdLFxuICAgIC8vIHVwZGF0ZVZhbHVlIG1ldGhvZCBjYWxscyBpbiBpbml0TW91bnQgbWV0aG9kIHRvIHNldCBhY3R1YWwgdmFsdWVcbiAgICBzaG93VGFnczogdGhpcy5zaG93VGFncyxcbiAgICB0YWdzQ291bnRUZXh0OiB0aGlzLnRhZ3NDb3VudFRleHQsXG4gICAgY2xlYXJhYmxlOiB0aGlzLmNsZWFyYWJsZSxcbiAgICBpc0Fsd2F5c09wZW5lZDogdGhpcy5hbHdheXNPcGVuLFxuICAgIHNlYXJjaGFibGU6IHRoaXMuc2VhcmNoYWJsZSxcbiAgICBwbGFjZWhvbGRlcjogdGhpcy5wbGFjZWhvbGRlcixcbiAgICBkaXNhYmxlZDogdGhpcy5kaXNhYmxlZCxcbiAgICBpc1NpbmdsZVNlbGVjdDogdGhpcy5pc1NpbmdsZVNlbGVjdCxcbiAgICBpZDogdGhpcy5pZCxcbiAgICBhcmlhTGFiZWw6IHRoaXMuYXJpYUxhYmVsLFxuICAgIGljb25FbGVtZW50czogdGhpcy5pY29uRWxlbWVudHMsXG4gICAgaW5wdXRDYWxsYmFjazogKGkpID0+IG8odGhpcywgUWUsIElzKS5jYWxsKHRoaXMsIGkpLFxuICAgIHNlYXJjaENhbGxiYWNrOiAoaSkgPT4gbyh0aGlzLCB0dCwgQnMpLmNhbGwodGhpcywgaSksXG4gICAgb3BlbkNhbGxiYWNrOiAoKSA9PiBvKHRoaXMsIG90LCBGcykuY2FsbCh0aGlzKSxcbiAgICBjbG9zZUNhbGxiYWNrOiAoKSA9PiBvKHRoaXMsIGllLCBUdCkuY2FsbCh0aGlzKSxcbiAgICBrZXlkb3duQ2FsbGJhY2s6IChpKSA9PiBvKHRoaXMsIGV0LCBQcykuY2FsbCh0aGlzLCBpKSxcbiAgICBmb2N1c0NhbGxiYWNrOiAoKSA9PiBvKHRoaXMsIHN0LCBWcykuY2FsbCh0aGlzKSxcbiAgICBibHVyQ2FsbGJhY2s6ICgpID0+IG8odGhpcywgaXQsIERzKS5jYWxsKHRoaXMpLFxuICAgIG5hbWVDaGFuZ2VDYWxsYmFjazogKGkpID0+IG8odGhpcywgYXQsIE1zKS5jYWxsKHRoaXMsIGkpXG4gIH0pO1xuICByZXR1cm4gdGhpcy5hcHBlbmRUb0JvZHkgJiYgbSh0aGlzLCBGLCBuZXcgUmVzaXplT2JzZXJ2ZXIoKCkgPT4gdGhpcy51cGRhdGVMaXN0UG9zaXRpb24oKSkpLCBlLmFwcGVuZChzLnNyY0VsZW1lbnQpLCB7IGNvbnRhaW5lcjogZSwgbGlzdDogdCwgaW5wdXQ6IHMgfTtcbn0sIFFlID0gbmV3IFdlYWtTZXQoKSwgSXMgPSBmdW5jdGlvbihlKSB7XG4gIHZhciBpLCBhO1xuICBjb25zdCB0ID0gcmUoZSk7XG4gIChpID0gbih0aGlzLCB1KSkgPT0gbnVsbCB8fCBpLnVwZGF0ZVZhbHVlKHQpO1xuICBjb25zdCBzID0gKChhID0gbih0aGlzLCB1KSkgPT0gbnVsbCA/IHZvaWQgMCA6IGEuc2VsZWN0ZWROb2RlcykgPz8ge307XG4gIG8odGhpcywgdGUsIF90KS5jYWxsKHRoaXMsIHMpLCBvKHRoaXMsIG5lLCBPdCkuY2FsbCh0aGlzKTtcbn0sIGV0ID0gbmV3IFdlYWtTZXQoKSwgUHMgPSBmdW5jdGlvbihlKSB7XG4gIHZhciB0O1xuICB0aGlzLmlzTGlzdE9wZW5lZCAmJiAoKHQgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IHQuY2FsbEtleUFjdGlvbihlKSk7XG59LCB0dCA9IG5ldyBXZWFrU2V0KCksIEJzID0gZnVuY3Rpb24oZSkge1xuICBuKHRoaXMsIHEpICYmIGNsZWFyVGltZW91dChuKHRoaXMsIHEpKSwgbSh0aGlzLCBxLCB3aW5kb3cuc2V0VGltZW91dCgoKSA9PiB7XG4gICAgdmFyIHQ7XG4gICAgKHQgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IHQudXBkYXRlU2VhcmNoVmFsdWUoZSksIHRoaXMudXBkYXRlTGlzdFBvc2l0aW9uKCk7XG4gIH0sIDM1MCkpLCBvKHRoaXMsIHB0LCBVcykuY2FsbCh0aGlzLCBlKTtcbn0sIHN0ID0gbmV3IFdlYWtTZXQoKSwgVnMgPSBmdW5jdGlvbigpIHtcbiAgbyh0aGlzLCBqLCBoZSkuY2FsbCh0aGlzLCAhMCksIG4odGhpcywgTCkgJiYgbih0aGlzLCBMKSAmJiBuKHRoaXMsIEIpICYmIChkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwibW91c2Vkb3duXCIsIG4odGhpcywgTCksICEwKSwgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImZvY3VzXCIsIG4odGhpcywgTCksICEwKSwgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXCJibHVyXCIsIG4odGhpcywgQikpKTtcbn0sIGl0ID0gbmV3IFdlYWtTZXQoKSwgRHMgPSBmdW5jdGlvbigpIHtcbiAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgdmFyIHMsIGk7XG4gICAgY29uc3QgZSA9IChzID0gbih0aGlzLCBwKSkgPT0gbnVsbCA/IHZvaWQgMCA6IHMuc3JjRWxlbWVudC5jb250YWlucyhkb2N1bWVudC5hY3RpdmVFbGVtZW50KSwgdCA9IChpID0gbih0aGlzLCB1KSkgPT0gbnVsbCA/IHZvaWQgMCA6IGkuc3JjRWxlbWVudC5jb250YWlucyhkb2N1bWVudC5hY3RpdmVFbGVtZW50KTtcbiAgICAhZSAmJiAhdCAmJiB0aGlzLmJsdXJXaW5kb3dIYW5kbGVyKCk7XG4gIH0sIDEpO1xufSwgc2UgPSBuZXcgV2Vha1NldCgpLCBBdCA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIHM7XG4gIGlmICghZSlcbiAgICByZXR1cm47XG4gIGxldCB0ID0gW107XG4gIHRoaXMuaXNJbmRlcGVuZGVudE5vZGVzIHx8IHRoaXMuaXNTaW5nbGVTZWxlY3QgPyB0ID0gZS5hbGxOb2RlcyA6IHRoaXMuZ3JvdXBlZCA/IHQgPSBlLmdyb3VwZWROb2RlcyA6IHQgPSBlLm5vZGVzLCAocyA9IG4odGhpcywgcCkpID09IG51bGwgfHwgcy51cGRhdGVWYWx1ZSh0KSwgbyh0aGlzLCB0ZSwgX3QpLmNhbGwodGhpcywgZSk7XG59LCBsdCA9IG5ldyBXZWFrU2V0KCksIEhzID0gZnVuY3Rpb24oZSkge1xuICB2YXIgdCwgcywgaTtcbiAgbyh0aGlzLCBzZSwgQXQpLmNhbGwodGhpcywgZSksIHRoaXMuaXNTaW5nbGVTZWxlY3QgJiYgIXRoaXMuYWx3YXlzT3BlbiAmJiAoKHQgPSBuKHRoaXMsIHApKSA9PSBudWxsIHx8IHQub3BlbkNsb3NlKCksIChzID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBzLmNsZWFyU2VhcmNoKCkpLCAoaSA9IG4odGhpcywgcCkpID09IG51bGwgfHwgaS5mb2N1cygpLCBvKHRoaXMsIG5lLCBPdCkuY2FsbCh0aGlzKTtcbn0sIG50ID0gbmV3IFdlYWtTZXQoKSwgR3MgPSBmdW5jdGlvbihlLCB0KSB7XG4gIHZhciBzO1xuICAocyA9IG4odGhpcywgcCkpID09IG51bGwgfHwgcy5mb2N1cygpLCB0aGlzLnVwZGF0ZUxpc3RQb3NpdGlvbigpLCBvKHRoaXMsIG10LCB6cykuY2FsbCh0aGlzLCBlLCB0KTtcbn0sIGF0ID0gbmV3IFdlYWtTZXQoKSwgTXMgPSBmdW5jdGlvbihlKSB7XG4gIHRoaXMuc2VsZWN0ZWROYW1lICE9PSBlICYmICh0aGlzLnNlbGVjdGVkTmFtZSA9IGUsIG8odGhpcywgaHQsIFJzKS5jYWxsKHRoaXMpKTtcbn0sIG90ID0gbmV3IFdlYWtTZXQoKSwgRnMgPSBmdW5jdGlvbigpIHtcbiAgdmFyIGU7XG4gIHRoaXMuaXNMaXN0T3BlbmVkID0gITAsIG4odGhpcywgXykgJiYgbih0aGlzLCBBKSAmJiAod2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXCJzY3JvbGxcIiwgbih0aGlzLCBfKSwgITApLCB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLCBuKHRoaXMsIEEpKSksICEoIW4odGhpcywgdSkgfHwgIXRoaXMuc3JjRWxlbWVudCkgJiYgKHRoaXMuYXBwZW5kVG9Cb2R5ID8gKGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQobih0aGlzLCB1KS5zcmNFbGVtZW50KSwgKGUgPSBuKHRoaXMsIEYpKSA9PSBudWxsIHx8IGUub2JzZXJ2ZSh0aGlzLnNyY0VsZW1lbnQpKSA6IHRoaXMuc3JjRWxlbWVudC5hcHBlbmRDaGlsZChuKHRoaXMsIHUpLnNyY0VsZW1lbnQpLCB0aGlzLnVwZGF0ZUxpc3RQb3NpdGlvbigpLCBvKHRoaXMsIGxlLCBOdCkuY2FsbCh0aGlzLCAhMCksIG8odGhpcywgY3QsIGpzKS5jYWxsKHRoaXMpLCBvKHRoaXMsIGR0LCAkcykuY2FsbCh0aGlzKSk7XG59LCBpZSA9IG5ldyBXZWFrU2V0KCksIFR0ID0gZnVuY3Rpb24oKSB7XG4gIHZhciB0O1xuICB0aGlzLmFsd2F5c09wZW4gfHwgKHRoaXMuaXNMaXN0T3BlbmVkID0gITEsIG4odGhpcywgXykgJiYgbih0aGlzLCBBKSAmJiAod2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJzY3JvbGxcIiwgbih0aGlzLCBfKSwgITApLCB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLCBuKHRoaXMsIEEpKSksICFuKHRoaXMsIHUpIHx8ICF0aGlzLnNyY0VsZW1lbnQpIHx8ICEodGhpcy5hcHBlbmRUb0JvZHkgPyBkb2N1bWVudC5ib2R5LmNvbnRhaW5zKG4odGhpcywgdSkuc3JjRWxlbWVudCkgOiB0aGlzLnNyY0VsZW1lbnQuY29udGFpbnMobih0aGlzLCB1KS5zcmNFbGVtZW50KSkgfHwgKG0odGhpcywgUSwgbih0aGlzLCB1KS5zcmNFbGVtZW50LnNjcm9sbFRvcCksIHRoaXMuYXBwZW5kVG9Cb2R5ID8gKGRvY3VtZW50LmJvZHkucmVtb3ZlQ2hpbGQobih0aGlzLCB1KS5zcmNFbGVtZW50KSwgKHQgPSBuKHRoaXMsIEYpKSA9PSBudWxsIHx8IHQuZGlzY29ubmVjdCgpKSA6IHRoaXMuc3JjRWxlbWVudC5yZW1vdmVDaGlsZChuKHRoaXMsIHUpLnNyY0VsZW1lbnQpLCBvKHRoaXMsIGxlLCBOdCkuY2FsbCh0aGlzLCAhMSksIG8odGhpcywgdXQsIFdzKS5jYWxsKHRoaXMpKTtcbn0sIHJ0ID0gbmV3IFdlYWtTZXQoKSwgcXMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGlmICghbih0aGlzLCB1KSB8fCAhbih0aGlzLCBwKSlcbiAgICByZXR1cm47XG4gIGNvbnN0IHMgPSB0ID8gXCJ0cmVlc2VsZWN0LWxpc3QtLXRvcC10by1ib2R5XCIgOiBcInRyZWVzZWxlY3QtbGlzdC0tdG9wXCIsIGkgPSB0ID8gXCJ0cmVlc2VsZWN0LWxpc3QtLWJvdHRvbS10by1ib2R5XCIgOiBcInRyZWVzZWxlY3QtbGlzdC0tYm90dG9tXCI7XG4gIGUgPyAobih0aGlzLCB1KS5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQocyksIG4odGhpcywgdSkuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKGkpLCBuKHRoaXMsIHApLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXQtLXRvcFwiKSwgbih0aGlzLCBwKS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWlucHV0LS1ib3R0b21cIikpIDogKG4odGhpcywgdSkuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKHMpLCBuKHRoaXMsIHUpLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChpKSwgbih0aGlzLCBwKS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWlucHV0LS10b3BcIiksIG4odGhpcywgcCkuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dC0tYm90dG9tXCIpKTtcbn0sIGogPSBuZXcgV2Vha1NldCgpLCBoZSA9IGZ1bmN0aW9uKGUpIHtcbiAgIW4odGhpcywgcCkgfHwgIW4odGhpcywgdSkgfHwgKGUgPyAobih0aGlzLCBwKS5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS1mb2N1c2VkXCIpLCBuKHRoaXMsIHUpLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdC0tZm9jdXNlZFwiKSkgOiAobih0aGlzLCBwKS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWlucHV0LS1mb2N1c2VkXCIpLCBuKHRoaXMsIHUpLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdC0tZm9jdXNlZFwiKSkpO1xufSwgbGUgPSBuZXcgV2Vha1NldCgpLCBOdCA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIHQsIHMsIGksIGE7XG4gIGUgPyAodCA9IG4odGhpcywgcCkpID09IG51bGwgfHwgdC5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS1vcGVuZWRcIikgOiAocyA9IG4odGhpcywgcCkpID09IG51bGwgfHwgcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWlucHV0LS1vcGVuZWRcIiksIHRoaXMuc3RhdGljTGlzdCA/IChpID0gbih0aGlzLCB1KSkgPT0gbnVsbCB8fCBpLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdC0tc3RhdGljXCIpIDogKGEgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IGEuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0LS1zdGF0aWNcIik7XG59LCBSID0gbmV3IFdlYWtTZXQoKSwgZGUgPSBmdW5jdGlvbihlKSB7XG4gICFuKHRoaXMsIF8pIHx8ICFuKHRoaXMsIEEpIHx8ICFuKHRoaXMsIEwpIHx8ICFuKHRoaXMsIEIpIHx8ICgoIXRoaXMuYWx3YXlzT3BlbiB8fCBlKSAmJiAod2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJzY3JvbGxcIiwgbih0aGlzLCBfKSwgITApLCB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInJlc2l6ZVwiLCBuKHRoaXMsIEEpKSksIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgbih0aGlzLCBMKSwgITApLCBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiZm9jdXNcIiwgbih0aGlzLCBMKSwgITApLCB3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImJsdXJcIiwgbih0aGlzLCBCKSkpO1xufSwgY3QgPSBuZXcgV2Vha1NldCgpLCBqcyA9IGZ1bmN0aW9uKCkge1xuICB2YXIgdCwgcywgaTtcbiAgY29uc3QgZSA9ICh0ID0gbih0aGlzLCB1KSkgPT0gbnVsbCA/IHZvaWQgMCA6IHQuaXNMYXN0Rm9jdXNlZEVsZW1lbnRFeGlzdCgpO1xuICB0aGlzLnNhdmVTY3JvbGxQb3NpdGlvbiAmJiBlID8gKHMgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IHMuc3JjRWxlbWVudC5zY3JvbGwoMCwgbih0aGlzLCBRKSkgOiAoaSA9IG4odGhpcywgdSkpID09IG51bGwgfHwgaS5mb2N1c0ZpcnN0TGlzdEVsZW1lbnQoKTtcbn0sIG5lID0gbmV3IFdlYWtTZXQoKSwgT3QgPSBmdW5jdGlvbigpIHtcbiAgdmFyIGU7XG4gIChlID0gdGhpcy5zcmNFbGVtZW50KSA9PSBudWxsIHx8IGUuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoXCJpbnB1dFwiLCB7IGRldGFpbDogdGhpcy52YWx1ZSB9KSksIHRoaXMuaW5wdXRDYWxsYmFjayAmJiB0aGlzLmlucHV0Q2FsbGJhY2sodGhpcy52YWx1ZSk7XG59LCBodCA9IG5ldyBXZWFrU2V0KCksIFJzID0gZnVuY3Rpb24oKSB7XG4gIHZhciBlO1xuICAoZSA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCB8fCBlLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFwibmFtZS1jaGFuZ2VcIiwgeyBkZXRhaWw6IHRoaXMuc2VsZWN0ZWROYW1lIH0pKSwgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2sgJiYgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2sodGhpcy5zZWxlY3RlZE5hbWUpO1xufSwgZHQgPSBuZXcgV2Vha1NldCgpLCAkcyA9IGZ1bmN0aW9uKCkge1xuICB2YXIgZTtcbiAgdGhpcy5hbHdheXNPcGVuIHx8ICgoZSA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCB8fCBlLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFwib3BlblwiLCB7IGRldGFpbDogdGhpcy52YWx1ZSB9KSksIHRoaXMub3BlbkNhbGxiYWNrICYmIHRoaXMub3BlbkNhbGxiYWNrKHRoaXMudmFsdWUpKTtcbn0sIHV0ID0gbmV3IFdlYWtTZXQoKSwgV3MgPSBmdW5jdGlvbigpIHtcbiAgdmFyIGU7XG4gIHRoaXMuYWx3YXlzT3BlbiB8fCAoKGUgPSB0aGlzLnNyY0VsZW1lbnQpID09IG51bGwgfHwgZS5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChcImNsb3NlXCIsIHsgZGV0YWlsOiB0aGlzLnZhbHVlIH0pKSwgdGhpcy5jbG9zZUNhbGxiYWNrICYmIHRoaXMuY2xvc2VDYWxsYmFjayh0aGlzLnZhbHVlKSk7XG59LCBwdCA9IG5ldyBXZWFrU2V0KCksIFVzID0gZnVuY3Rpb24oZSkge1xuICB2YXIgcztcbiAgY29uc3QgdCA9IChlID09IG51bGwgPyB2b2lkIDAgOiBlLnRyaW0oKSkgPz8gXCJcIjtcbiAgKHMgPSB0aGlzLnNyY0VsZW1lbnQpID09IG51bGwgfHwgcy5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChcInNlYXJjaFwiLCB7IGRldGFpbDogdCB9KSksIHRoaXMuc2VhcmNoQ2FsbGJhY2sgJiYgdGhpcy5zZWFyY2hDYWxsYmFjayh0KTtcbn0sIG10ID0gbmV3IFdlYWtTZXQoKSwgenMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIHZhciBzO1xuICAocyA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCB8fCBzLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFwib3Blbi1jbG9zZS1ncm91cFwiLCB7IGRldGFpbDogeyBncm91cElkOiBlLCBpc0Nsb3NlZDogdCB9IH0pKSwgdGhpcy5vcGVuQ2xvc2VHcm91cENhbGxiYWNrICYmIHRoaXMub3BlbkNsb3NlR3JvdXBDYWxsYmFjayhlLCB0KTtcbn07XG5leHBvcnQge1xuICBHaSBhcyBkZWZhdWx0XG59O1xuIiwgImltcG9ydCBUcmVlc2VsZWN0IGZyb20gJ3RyZWVzZWxlY3RqcydcblxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gc2VsZWN0VHJlZSh7XG4gICBzdGF0ZSxcbiAgIG5hbWUsXG4gICBvcHRpb25zLFxuICAgc2VhcmNoYWJsZSxcbiAgIHNob3dDb3VudCxcbiAgIHBsYWNlaG9sZGVyLFxuICAgcnRsLFxuICAgZGlzYWJsZWRCcmFuY2hOb2RlID0gdHJ1ZSxcbiAgIGRpc2FibGVkID0gZmFsc2UsXG4gICBpc1NpbmdsZVNlbGVjdCA9IHRydWUsXG4gICBzaG93VGFncyA9IHRydWUsXG4gICBjbGVhcmFibGUgPSB0cnVlLFxuICAgaXNJbmRlcGVuZGVudE5vZGVzID0gdHJ1ZSxcbiAgIGFsd2F5c09wZW4gPSBmYWxzZSxcbiAgIGVtcHR5VGV4dCxcbiAgIGV4cGFuZFNlbGVjdGVkID0gdHJ1ZSxcbiAgIGdyb3VwZWQgPSB0cnVlLFxuICAgb3BlbkxldmVsID0gMCxcbiAgIGRpcmVjdGlvbiA9ICdhdXRvJ1xuIH0pIHtcbiAgcmV0dXJuIHtcbiAgICBzdGF0ZSxcblxuICAgIC8qKiBAdHlwZSBUcmVlc2VsZWN0ICovXG4gICAgdHJlZTogbnVsbCxcblxuICAgIGluaXQoKSB7XG4gICAgICB0aGlzLnRyZWUgPSBuZXcgVHJlZXNlbGVjdCh7XG4gICAgICAgIGlkOiBgdHJlZS0ke25hbWV9LWlkYCxcbiAgICAgICAgYXJpYUxhYmVsOiBgdHJlZS0ke25hbWV9LWxhYmVsYCxcbiAgICAgICAgcGFyZW50SHRtbENvbnRhaW5lcjogdGhpcy4kcmVmcy50cmVlLFxuICAgICAgICB2YWx1ZTogdGhpcy5zdGF0ZSA/PyBbXSxcbiAgICAgICAgb3B0aW9ucyxcbiAgICAgICAgc2VhcmNoYWJsZSxcbiAgICAgICAgc2hvd0NvdW50LFxuICAgICAgICBwbGFjZWhvbGRlcixcbiAgICAgICAgZGlzYWJsZWRCcmFuY2hOb2RlLFxuICAgICAgICBkaXNhYmxlZCxcbiAgICAgICAgaXNTaW5nbGVTZWxlY3QsXG4gICAgICAgIHNob3dUYWdzLFxuICAgICAgICBjbGVhcmFibGUsXG4gICAgICAgIGlzSW5kZXBlbmRlbnROb2RlcyxcbiAgICAgICAgYWx3YXlzT3BlbixcbiAgICAgICAgZW1wdHlUZXh0LFxuICAgICAgICBleHBhbmRTZWxlY3RlZCxcbiAgICAgICAgZ3JvdXBlZCxcbiAgICAgICAgb3BlbkxldmVsLFxuICAgICAgICBkaXJlY3Rpb24sXG4gICAgICAgIHJ0bFxuICAgICAgfSk7XG5cbiAgICAgIHRoaXMudHJlZS5zcmNFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2lucHV0JywgKGUpID0+IHtcbiAgICAgICAgdGhpcy5zdGF0ZSA9IGUuZGV0YWlsO1xuICAgICAgfSk7XG4gICAgfVxuICB9XG59XG4iLCAiLyoqIVxuICogU29ydGFibGUgMS4xNS4yXG4gKiBAYXV0aG9yXHRSdWJhWGEgICA8dHJhc2hAcnViYXhhLm9yZz5cbiAqIEBhdXRob3JcdG93ZW5tICAgIDxvd2VuMjMzNTVAZ21haWwuY29tPlxuICogQGxpY2Vuc2UgTUlUXG4gKi9cbmZ1bmN0aW9uIG93bktleXMob2JqZWN0LCBlbnVtZXJhYmxlT25seSkge1xuICB2YXIga2V5cyA9IE9iamVjdC5rZXlzKG9iamVjdCk7XG4gIGlmIChPYmplY3QuZ2V0T3duUHJvcGVydHlTeW1ib2xzKSB7XG4gICAgdmFyIHN5bWJvbHMgPSBPYmplY3QuZ2V0T3duUHJvcGVydHlTeW1ib2xzKG9iamVjdCk7XG4gICAgaWYgKGVudW1lcmFibGVPbmx5KSB7XG4gICAgICBzeW1ib2xzID0gc3ltYm9scy5maWx0ZXIoZnVuY3Rpb24gKHN5bSkge1xuICAgICAgICByZXR1cm4gT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcihvYmplY3QsIHN5bSkuZW51bWVyYWJsZTtcbiAgICAgIH0pO1xuICAgIH1cbiAgICBrZXlzLnB1c2guYXBwbHkoa2V5cywgc3ltYm9scyk7XG4gIH1cbiAgcmV0dXJuIGtleXM7XG59XG5mdW5jdGlvbiBfb2JqZWN0U3ByZWFkMih0YXJnZXQpIHtcbiAgZm9yICh2YXIgaSA9IDE7IGkgPCBhcmd1bWVudHMubGVuZ3RoOyBpKyspIHtcbiAgICB2YXIgc291cmNlID0gYXJndW1lbnRzW2ldICE9IG51bGwgPyBhcmd1bWVudHNbaV0gOiB7fTtcbiAgICBpZiAoaSAlIDIpIHtcbiAgICAgIG93bktleXMoT2JqZWN0KHNvdXJjZSksIHRydWUpLmZvckVhY2goZnVuY3Rpb24gKGtleSkge1xuICAgICAgICBfZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBrZXksIHNvdXJjZVtrZXldKTtcbiAgICAgIH0pO1xuICAgIH0gZWxzZSBpZiAoT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcnMpIHtcbiAgICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcnMoc291cmNlKSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIG93bktleXMoT2JqZWN0KHNvdXJjZSkpLmZvckVhY2goZnVuY3Rpb24gKGtleSkge1xuICAgICAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBrZXksIE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Ioc291cmNlLCBrZXkpKTtcbiAgICAgIH0pO1xuICAgIH1cbiAgfVxuICByZXR1cm4gdGFyZ2V0O1xufVxuZnVuY3Rpb24gX3R5cGVvZihvYmopIHtcbiAgXCJAYmFiZWwvaGVscGVycyAtIHR5cGVvZlwiO1xuXG4gIGlmICh0eXBlb2YgU3ltYm9sID09PSBcImZ1bmN0aW9uXCIgJiYgdHlwZW9mIFN5bWJvbC5pdGVyYXRvciA9PT0gXCJzeW1ib2xcIikge1xuICAgIF90eXBlb2YgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgICByZXR1cm4gdHlwZW9mIG9iajtcbiAgICB9O1xuICB9IGVsc2Uge1xuICAgIF90eXBlb2YgPSBmdW5jdGlvbiAob2JqKSB7XG4gICAgICByZXR1cm4gb2JqICYmIHR5cGVvZiBTeW1ib2wgPT09IFwiZnVuY3Rpb25cIiAmJiBvYmouY29uc3RydWN0b3IgPT09IFN5bWJvbCAmJiBvYmogIT09IFN5bWJvbC5wcm90b3R5cGUgPyBcInN5bWJvbFwiIDogdHlwZW9mIG9iajtcbiAgICB9O1xuICB9XG4gIHJldHVybiBfdHlwZW9mKG9iaik7XG59XG5mdW5jdGlvbiBfZGVmaW5lUHJvcGVydHkob2JqLCBrZXksIHZhbHVlKSB7XG4gIGlmIChrZXkgaW4gb2JqKSB7XG4gICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KG9iaiwga2V5LCB7XG4gICAgICB2YWx1ZTogdmFsdWUsXG4gICAgICBlbnVtZXJhYmxlOiB0cnVlLFxuICAgICAgY29uZmlndXJhYmxlOiB0cnVlLFxuICAgICAgd3JpdGFibGU6IHRydWVcbiAgICB9KTtcbiAgfSBlbHNlIHtcbiAgICBvYmpba2V5XSA9IHZhbHVlO1xuICB9XG4gIHJldHVybiBvYmo7XG59XG5mdW5jdGlvbiBfZXh0ZW5kcygpIHtcbiAgX2V4dGVuZHMgPSBPYmplY3QuYXNzaWduIHx8IGZ1bmN0aW9uICh0YXJnZXQpIHtcbiAgICBmb3IgKHZhciBpID0gMTsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xuICAgICAgdmFyIHNvdXJjZSA9IGFyZ3VtZW50c1tpXTtcbiAgICAgIGZvciAodmFyIGtleSBpbiBzb3VyY2UpIHtcbiAgICAgICAgaWYgKE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChzb3VyY2UsIGtleSkpIHtcbiAgICAgICAgICB0YXJnZXRba2V5XSA9IHNvdXJjZVtrZXldO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiB0YXJnZXQ7XG4gIH07XG4gIHJldHVybiBfZXh0ZW5kcy5hcHBseSh0aGlzLCBhcmd1bWVudHMpO1xufVxuZnVuY3Rpb24gX29iamVjdFdpdGhvdXRQcm9wZXJ0aWVzTG9vc2Uoc291cmNlLCBleGNsdWRlZCkge1xuICBpZiAoc291cmNlID09IG51bGwpIHJldHVybiB7fTtcbiAgdmFyIHRhcmdldCA9IHt9O1xuICB2YXIgc291cmNlS2V5cyA9IE9iamVjdC5rZXlzKHNvdXJjZSk7XG4gIHZhciBrZXksIGk7XG4gIGZvciAoaSA9IDA7IGkgPCBzb3VyY2VLZXlzLmxlbmd0aDsgaSsrKSB7XG4gICAga2V5ID0gc291cmNlS2V5c1tpXTtcbiAgICBpZiAoZXhjbHVkZWQuaW5kZXhPZihrZXkpID49IDApIGNvbnRpbnVlO1xuICAgIHRhcmdldFtrZXldID0gc291cmNlW2tleV07XG4gIH1cbiAgcmV0dXJuIHRhcmdldDtcbn1cbmZ1bmN0aW9uIF9vYmplY3RXaXRob3V0UHJvcGVydGllcyhzb3VyY2UsIGV4Y2x1ZGVkKSB7XG4gIGlmIChzb3VyY2UgPT0gbnVsbCkgcmV0dXJuIHt9O1xuICB2YXIgdGFyZ2V0ID0gX29iamVjdFdpdGhvdXRQcm9wZXJ0aWVzTG9vc2Uoc291cmNlLCBleGNsdWRlZCk7XG4gIHZhciBrZXksIGk7XG4gIGlmIChPYmplY3QuZ2V0T3duUHJvcGVydHlTeW1ib2xzKSB7XG4gICAgdmFyIHNvdXJjZVN5bWJvbEtleXMgPSBPYmplY3QuZ2V0T3duUHJvcGVydHlTeW1ib2xzKHNvdXJjZSk7XG4gICAgZm9yIChpID0gMDsgaSA8IHNvdXJjZVN5bWJvbEtleXMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGtleSA9IHNvdXJjZVN5bWJvbEtleXNbaV07XG4gICAgICBpZiAoZXhjbHVkZWQuaW5kZXhPZihrZXkpID49IDApIGNvbnRpbnVlO1xuICAgICAgaWYgKCFPYmplY3QucHJvdG90eXBlLnByb3BlcnR5SXNFbnVtZXJhYmxlLmNhbGwoc291cmNlLCBrZXkpKSBjb250aW51ZTtcbiAgICAgIHRhcmdldFtrZXldID0gc291cmNlW2tleV07XG4gICAgfVxuICB9XG4gIHJldHVybiB0YXJnZXQ7XG59XG5mdW5jdGlvbiBfdG9Db25zdW1hYmxlQXJyYXkoYXJyKSB7XG4gIHJldHVybiBfYXJyYXlXaXRob3V0SG9sZXMoYXJyKSB8fCBfaXRlcmFibGVUb0FycmF5KGFycikgfHwgX3Vuc3VwcG9ydGVkSXRlcmFibGVUb0FycmF5KGFycikgfHwgX25vbkl0ZXJhYmxlU3ByZWFkKCk7XG59XG5mdW5jdGlvbiBfYXJyYXlXaXRob3V0SG9sZXMoYXJyKSB7XG4gIGlmIChBcnJheS5pc0FycmF5KGFycikpIHJldHVybiBfYXJyYXlMaWtlVG9BcnJheShhcnIpO1xufVxuZnVuY3Rpb24gX2l0ZXJhYmxlVG9BcnJheShpdGVyKSB7XG4gIGlmICh0eXBlb2YgU3ltYm9sICE9PSBcInVuZGVmaW5lZFwiICYmIGl0ZXJbU3ltYm9sLml0ZXJhdG9yXSAhPSBudWxsIHx8IGl0ZXJbXCJAQGl0ZXJhdG9yXCJdICE9IG51bGwpIHJldHVybiBBcnJheS5mcm9tKGl0ZXIpO1xufVxuZnVuY3Rpb24gX3Vuc3VwcG9ydGVkSXRlcmFibGVUb0FycmF5KG8sIG1pbkxlbikge1xuICBpZiAoIW8pIHJldHVybjtcbiAgaWYgKHR5cGVvZiBvID09PSBcInN0cmluZ1wiKSByZXR1cm4gX2FycmF5TGlrZVRvQXJyYXkobywgbWluTGVuKTtcbiAgdmFyIG4gPSBPYmplY3QucHJvdG90eXBlLnRvU3RyaW5nLmNhbGwobykuc2xpY2UoOCwgLTEpO1xuICBpZiAobiA9PT0gXCJPYmplY3RcIiAmJiBvLmNvbnN0cnVjdG9yKSBuID0gby5jb25zdHJ1Y3Rvci5uYW1lO1xuICBpZiAobiA9PT0gXCJNYXBcIiB8fCBuID09PSBcIlNldFwiKSByZXR1cm4gQXJyYXkuZnJvbShvKTtcbiAgaWYgKG4gPT09IFwiQXJndW1lbnRzXCIgfHwgL14oPzpVaXxJKW50KD86OHwxNnwzMikoPzpDbGFtcGVkKT9BcnJheSQvLnRlc3QobikpIHJldHVybiBfYXJyYXlMaWtlVG9BcnJheShvLCBtaW5MZW4pO1xufVxuZnVuY3Rpb24gX2FycmF5TGlrZVRvQXJyYXkoYXJyLCBsZW4pIHtcbiAgaWYgKGxlbiA9PSBudWxsIHx8IGxlbiA+IGFyci5sZW5ndGgpIGxlbiA9IGFyci5sZW5ndGg7XG4gIGZvciAodmFyIGkgPSAwLCBhcnIyID0gbmV3IEFycmF5KGxlbik7IGkgPCBsZW47IGkrKykgYXJyMltpXSA9IGFycltpXTtcbiAgcmV0dXJuIGFycjI7XG59XG5mdW5jdGlvbiBfbm9uSXRlcmFibGVTcHJlYWQoKSB7XG4gIHRocm93IG5ldyBUeXBlRXJyb3IoXCJJbnZhbGlkIGF0dGVtcHQgdG8gc3ByZWFkIG5vbi1pdGVyYWJsZSBpbnN0YW5jZS5cXG5JbiBvcmRlciB0byBiZSBpdGVyYWJsZSwgbm9uLWFycmF5IG9iamVjdHMgbXVzdCBoYXZlIGEgW1N5bWJvbC5pdGVyYXRvcl0oKSBtZXRob2QuXCIpO1xufVxuXG52YXIgdmVyc2lvbiA9IFwiMS4xNS4yXCI7XG5cbmZ1bmN0aW9uIHVzZXJBZ2VudChwYXR0ZXJuKSB7XG4gIGlmICh0eXBlb2Ygd2luZG93ICE9PSAndW5kZWZpbmVkJyAmJiB3aW5kb3cubmF2aWdhdG9yKSB7XG4gICAgcmV0dXJuICEhIC8qQF9fUFVSRV9fKi9uYXZpZ2F0b3IudXNlckFnZW50Lm1hdGNoKHBhdHRlcm4pO1xuICB9XG59XG52YXIgSUUxMU9yTGVzcyA9IHVzZXJBZ2VudCgvKD86VHJpZGVudC4qcnZbIDpdPzExXFwufG1zaWV8aWVtb2JpbGV8V2luZG93cyBQaG9uZSkvaSk7XG52YXIgRWRnZSA9IHVzZXJBZ2VudCgvRWRnZS9pKTtcbnZhciBGaXJlRm94ID0gdXNlckFnZW50KC9maXJlZm94L2kpO1xudmFyIFNhZmFyaSA9IHVzZXJBZ2VudCgvc2FmYXJpL2kpICYmICF1c2VyQWdlbnQoL2Nocm9tZS9pKSAmJiAhdXNlckFnZW50KC9hbmRyb2lkL2kpO1xudmFyIElPUyA9IHVzZXJBZ2VudCgvaVAoYWR8b2R8aG9uZSkvaSk7XG52YXIgQ2hyb21lRm9yQW5kcm9pZCA9IHVzZXJBZ2VudCgvY2hyb21lL2kpICYmIHVzZXJBZ2VudCgvYW5kcm9pZC9pKTtcblxudmFyIGNhcHR1cmVNb2RlID0ge1xuICBjYXB0dXJlOiBmYWxzZSxcbiAgcGFzc2l2ZTogZmFsc2Vcbn07XG5mdW5jdGlvbiBvbihlbCwgZXZlbnQsIGZuKSB7XG4gIGVsLmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGZuLCAhSUUxMU9yTGVzcyAmJiBjYXB0dXJlTW9kZSk7XG59XG5mdW5jdGlvbiBvZmYoZWwsIGV2ZW50LCBmbikge1xuICBlbC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBmbiwgIUlFMTFPckxlc3MgJiYgY2FwdHVyZU1vZGUpO1xufVxuZnVuY3Rpb24gbWF0Y2hlcyggLyoqSFRNTEVsZW1lbnQqL2VsLCAvKipTdHJpbmcqL3NlbGVjdG9yKSB7XG4gIGlmICghc2VsZWN0b3IpIHJldHVybjtcbiAgc2VsZWN0b3JbMF0gPT09ICc+JyAmJiAoc2VsZWN0b3IgPSBzZWxlY3Rvci5zdWJzdHJpbmcoMSkpO1xuICBpZiAoZWwpIHtcbiAgICB0cnkge1xuICAgICAgaWYgKGVsLm1hdGNoZXMpIHtcbiAgICAgICAgcmV0dXJuIGVsLm1hdGNoZXMoc2VsZWN0b3IpO1xuICAgICAgfSBlbHNlIGlmIChlbC5tc01hdGNoZXNTZWxlY3Rvcikge1xuICAgICAgICByZXR1cm4gZWwubXNNYXRjaGVzU2VsZWN0b3Ioc2VsZWN0b3IpO1xuICAgICAgfSBlbHNlIGlmIChlbC53ZWJraXRNYXRjaGVzU2VsZWN0b3IpIHtcbiAgICAgICAgcmV0dXJuIGVsLndlYmtpdE1hdGNoZXNTZWxlY3RvcihzZWxlY3Rvcik7XG4gICAgICB9XG4gICAgfSBjYXRjaCAoXykge1xuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbiAgfVxuICByZXR1cm4gZmFsc2U7XG59XG5mdW5jdGlvbiBnZXRQYXJlbnRPckhvc3QoZWwpIHtcbiAgcmV0dXJuIGVsLmhvc3QgJiYgZWwgIT09IGRvY3VtZW50ICYmIGVsLmhvc3Qubm9kZVR5cGUgPyBlbC5ob3N0IDogZWwucGFyZW50Tm9kZTtcbn1cbmZ1bmN0aW9uIGNsb3Nlc3QoIC8qKkhUTUxFbGVtZW50Ki9lbCwgLyoqU3RyaW5nKi9zZWxlY3RvciwgLyoqSFRNTEVsZW1lbnQqL2N0eCwgaW5jbHVkZUNUWCkge1xuICBpZiAoZWwpIHtcbiAgICBjdHggPSBjdHggfHwgZG9jdW1lbnQ7XG4gICAgZG8ge1xuICAgICAgaWYgKHNlbGVjdG9yICE9IG51bGwgJiYgKHNlbGVjdG9yWzBdID09PSAnPicgPyBlbC5wYXJlbnROb2RlID09PSBjdHggJiYgbWF0Y2hlcyhlbCwgc2VsZWN0b3IpIDogbWF0Y2hlcyhlbCwgc2VsZWN0b3IpKSB8fCBpbmNsdWRlQ1RYICYmIGVsID09PSBjdHgpIHtcbiAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgfVxuICAgICAgaWYgKGVsID09PSBjdHgpIGJyZWFrO1xuICAgICAgLyoganNoaW50IGJvc3M6dHJ1ZSAqL1xuICAgIH0gd2hpbGUgKGVsID0gZ2V0UGFyZW50T3JIb3N0KGVsKSk7XG4gIH1cbiAgcmV0dXJuIG51bGw7XG59XG52YXIgUl9TUEFDRSA9IC9cXHMrL2c7XG5mdW5jdGlvbiB0b2dnbGVDbGFzcyhlbCwgbmFtZSwgc3RhdGUpIHtcbiAgaWYgKGVsICYmIG5hbWUpIHtcbiAgICBpZiAoZWwuY2xhc3NMaXN0KSB7XG4gICAgICBlbC5jbGFzc0xpc3Rbc3RhdGUgPyAnYWRkJyA6ICdyZW1vdmUnXShuYW1lKTtcbiAgICB9IGVsc2Uge1xuICAgICAgdmFyIGNsYXNzTmFtZSA9ICgnICcgKyBlbC5jbGFzc05hbWUgKyAnICcpLnJlcGxhY2UoUl9TUEFDRSwgJyAnKS5yZXBsYWNlKCcgJyArIG5hbWUgKyAnICcsICcgJyk7XG4gICAgICBlbC5jbGFzc05hbWUgPSAoY2xhc3NOYW1lICsgKHN0YXRlID8gJyAnICsgbmFtZSA6ICcnKSkucmVwbGFjZShSX1NQQUNFLCAnICcpO1xuICAgIH1cbiAgfVxufVxuZnVuY3Rpb24gY3NzKGVsLCBwcm9wLCB2YWwpIHtcbiAgdmFyIHN0eWxlID0gZWwgJiYgZWwuc3R5bGU7XG4gIGlmIChzdHlsZSkge1xuICAgIGlmICh2YWwgPT09IHZvaWQgMCkge1xuICAgICAgaWYgKGRvY3VtZW50LmRlZmF1bHRWaWV3ICYmIGRvY3VtZW50LmRlZmF1bHRWaWV3LmdldENvbXB1dGVkU3R5bGUpIHtcbiAgICAgICAgdmFsID0gZG9jdW1lbnQuZGVmYXVsdFZpZXcuZ2V0Q29tcHV0ZWRTdHlsZShlbCwgJycpO1xuICAgICAgfSBlbHNlIGlmIChlbC5jdXJyZW50U3R5bGUpIHtcbiAgICAgICAgdmFsID0gZWwuY3VycmVudFN0eWxlO1xuICAgICAgfVxuICAgICAgcmV0dXJuIHByb3AgPT09IHZvaWQgMCA/IHZhbCA6IHZhbFtwcm9wXTtcbiAgICB9IGVsc2Uge1xuICAgICAgaWYgKCEocHJvcCBpbiBzdHlsZSkgJiYgcHJvcC5pbmRleE9mKCd3ZWJraXQnKSA9PT0gLTEpIHtcbiAgICAgICAgcHJvcCA9ICctd2Via2l0LScgKyBwcm9wO1xuICAgICAgfVxuICAgICAgc3R5bGVbcHJvcF0gPSB2YWwgKyAodHlwZW9mIHZhbCA9PT0gJ3N0cmluZycgPyAnJyA6ICdweCcpO1xuICAgIH1cbiAgfVxufVxuZnVuY3Rpb24gbWF0cml4KGVsLCBzZWxmT25seSkge1xuICB2YXIgYXBwbGllZFRyYW5zZm9ybXMgPSAnJztcbiAgaWYgKHR5cGVvZiBlbCA9PT0gJ3N0cmluZycpIHtcbiAgICBhcHBsaWVkVHJhbnNmb3JtcyA9IGVsO1xuICB9IGVsc2Uge1xuICAgIGRvIHtcbiAgICAgIHZhciB0cmFuc2Zvcm0gPSBjc3MoZWwsICd0cmFuc2Zvcm0nKTtcbiAgICAgIGlmICh0cmFuc2Zvcm0gJiYgdHJhbnNmb3JtICE9PSAnbm9uZScpIHtcbiAgICAgICAgYXBwbGllZFRyYW5zZm9ybXMgPSB0cmFuc2Zvcm0gKyAnICcgKyBhcHBsaWVkVHJhbnNmb3JtcztcbiAgICAgIH1cbiAgICAgIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgICB9IHdoaWxlICghc2VsZk9ubHkgJiYgKGVsID0gZWwucGFyZW50Tm9kZSkpO1xuICB9XG4gIHZhciBtYXRyaXhGbiA9IHdpbmRvdy5ET01NYXRyaXggfHwgd2luZG93LldlYktpdENTU01hdHJpeCB8fCB3aW5kb3cuQ1NTTWF0cml4IHx8IHdpbmRvdy5NU0NTU01hdHJpeDtcbiAgLypqc2hpbnQgLVcwNTYgKi9cbiAgcmV0dXJuIG1hdHJpeEZuICYmIG5ldyBtYXRyaXhGbihhcHBsaWVkVHJhbnNmb3Jtcyk7XG59XG5mdW5jdGlvbiBmaW5kKGN0eCwgdGFnTmFtZSwgaXRlcmF0b3IpIHtcbiAgaWYgKGN0eCkge1xuICAgIHZhciBsaXN0ID0gY3R4LmdldEVsZW1lbnRzQnlUYWdOYW1lKHRhZ05hbWUpLFxuICAgICAgaSA9IDAsXG4gICAgICBuID0gbGlzdC5sZW5ndGg7XG4gICAgaWYgKGl0ZXJhdG9yKSB7XG4gICAgICBmb3IgKDsgaSA8IG47IGkrKykge1xuICAgICAgICBpdGVyYXRvcihsaXN0W2ldLCBpKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIGxpc3Q7XG4gIH1cbiAgcmV0dXJuIFtdO1xufVxuZnVuY3Rpb24gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpIHtcbiAgdmFyIHNjcm9sbGluZ0VsZW1lbnQgPSBkb2N1bWVudC5zY3JvbGxpbmdFbGVtZW50O1xuICBpZiAoc2Nyb2xsaW5nRWxlbWVudCkge1xuICAgIHJldHVybiBzY3JvbGxpbmdFbGVtZW50O1xuICB9IGVsc2Uge1xuICAgIHJldHVybiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQ7XG4gIH1cbn1cblxuLyoqXHJcbiAqIFJldHVybnMgdGhlIFwiYm91bmRpbmcgY2xpZW50IHJlY3RcIiBvZiBnaXZlbiBlbGVtZW50XHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSBlbCAgICAgICAgICAgICAgICAgICAgICAgVGhlIGVsZW1lbnQgd2hvc2UgYm91bmRpbmdDbGllbnRSZWN0IGlzIHdhbnRlZFxyXG4gKiBAcGFyYW0gIHtbQm9vbGVhbl19IHJlbGF0aXZlVG9Db250YWluaW5nQmxvY2sgIFdoZXRoZXIgdGhlIHJlY3Qgc2hvdWxkIGJlIHJlbGF0aXZlIHRvIHRoZSBjb250YWluaW5nIGJsb2NrIG9mIChpbmNsdWRpbmcpIHRoZSBjb250YWluZXJcclxuICogQHBhcmFtICB7W0Jvb2xlYW5dfSByZWxhdGl2ZVRvTm9uU3RhdGljUGFyZW50ICBXaGV0aGVyIHRoZSByZWN0IHNob3VsZCBiZSByZWxhdGl2ZSB0byB0aGUgcmVsYXRpdmUgcGFyZW50IG9mIChpbmNsdWRpbmcpIHRoZSBjb250YWllbnJcclxuICogQHBhcmFtICB7W0Jvb2xlYW5dfSB1bmRvU2NhbGUgICAgICAgICAgICAgICAgICBXaGV0aGVyIHRoZSBjb250YWluZXIncyBzY2FsZSgpIHNob3VsZCBiZSB1bmRvbmVcclxuICogQHBhcmFtICB7W0hUTUxFbGVtZW50XX0gY29udGFpbmVyICAgICAgICAgICAgICBUaGUgcGFyZW50IHRoZSBlbGVtZW50IHdpbGwgYmUgcGxhY2VkIGluXHJcbiAqIEByZXR1cm4ge09iamVjdH0gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgVGhlIGJvdW5kaW5nQ2xpZW50UmVjdCBvZiBlbCwgd2l0aCBzcGVjaWZpZWQgYWRqdXN0bWVudHNcclxuICovXG5mdW5jdGlvbiBnZXRSZWN0KGVsLCByZWxhdGl2ZVRvQ29udGFpbmluZ0Jsb2NrLCByZWxhdGl2ZVRvTm9uU3RhdGljUGFyZW50LCB1bmRvU2NhbGUsIGNvbnRhaW5lcikge1xuICBpZiAoIWVsLmdldEJvdW5kaW5nQ2xpZW50UmVjdCAmJiBlbCAhPT0gd2luZG93KSByZXR1cm47XG4gIHZhciBlbFJlY3QsIHRvcCwgbGVmdCwgYm90dG9tLCByaWdodCwgaGVpZ2h0LCB3aWR0aDtcbiAgaWYgKGVsICE9PSB3aW5kb3cgJiYgZWwucGFyZW50Tm9kZSAmJiBlbCAhPT0gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpKSB7XG4gICAgZWxSZWN0ID0gZWwuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgdG9wID0gZWxSZWN0LnRvcDtcbiAgICBsZWZ0ID0gZWxSZWN0LmxlZnQ7XG4gICAgYm90dG9tID0gZWxSZWN0LmJvdHRvbTtcbiAgICByaWdodCA9IGVsUmVjdC5yaWdodDtcbiAgICBoZWlnaHQgPSBlbFJlY3QuaGVpZ2h0O1xuICAgIHdpZHRoID0gZWxSZWN0LndpZHRoO1xuICB9IGVsc2Uge1xuICAgIHRvcCA9IDA7XG4gICAgbGVmdCA9IDA7XG4gICAgYm90dG9tID0gd2luZG93LmlubmVySGVpZ2h0O1xuICAgIHJpZ2h0ID0gd2luZG93LmlubmVyV2lkdGg7XG4gICAgaGVpZ2h0ID0gd2luZG93LmlubmVySGVpZ2h0O1xuICAgIHdpZHRoID0gd2luZG93LmlubmVyV2lkdGg7XG4gIH1cbiAgaWYgKChyZWxhdGl2ZVRvQ29udGFpbmluZ0Jsb2NrIHx8IHJlbGF0aXZlVG9Ob25TdGF0aWNQYXJlbnQpICYmIGVsICE9PSB3aW5kb3cpIHtcbiAgICAvLyBBZGp1c3QgZm9yIHRyYW5zbGF0ZSgpXG4gICAgY29udGFpbmVyID0gY29udGFpbmVyIHx8IGVsLnBhcmVudE5vZGU7XG5cbiAgICAvLyBzb2x2ZXMgIzExMjMgKHNlZTogaHR0cHM6Ly9zdGFja292ZXJmbG93LmNvbS9hLzM3OTUzODA2LzYwODgzMTIpXG4gICAgLy8gTm90IG5lZWRlZCBvbiA8PSBJRTExXG4gICAgaWYgKCFJRTExT3JMZXNzKSB7XG4gICAgICBkbyB7XG4gICAgICAgIGlmIChjb250YWluZXIgJiYgY29udGFpbmVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCAmJiAoY3NzKGNvbnRhaW5lciwgJ3RyYW5zZm9ybScpICE9PSAnbm9uZScgfHwgcmVsYXRpdmVUb05vblN0YXRpY1BhcmVudCAmJiBjc3MoY29udGFpbmVyLCAncG9zaXRpb24nKSAhPT0gJ3N0YXRpYycpKSB7XG4gICAgICAgICAgdmFyIGNvbnRhaW5lclJlY3QgPSBjb250YWluZXIuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG5cbiAgICAgICAgICAvLyBTZXQgcmVsYXRpdmUgdG8gZWRnZXMgb2YgcGFkZGluZyBib3ggb2YgY29udGFpbmVyXG4gICAgICAgICAgdG9wIC09IGNvbnRhaW5lclJlY3QudG9wICsgcGFyc2VJbnQoY3NzKGNvbnRhaW5lciwgJ2JvcmRlci10b3Atd2lkdGgnKSk7XG4gICAgICAgICAgbGVmdCAtPSBjb250YWluZXJSZWN0LmxlZnQgKyBwYXJzZUludChjc3MoY29udGFpbmVyLCAnYm9yZGVyLWxlZnQtd2lkdGgnKSk7XG4gICAgICAgICAgYm90dG9tID0gdG9wICsgZWxSZWN0LmhlaWdodDtcbiAgICAgICAgICByaWdodCA9IGxlZnQgKyBlbFJlY3Qud2lkdGg7XG4gICAgICAgICAgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgLyoganNoaW50IGJvc3M6dHJ1ZSAqL1xuICAgICAgfSB3aGlsZSAoY29udGFpbmVyID0gY29udGFpbmVyLnBhcmVudE5vZGUpO1xuICAgIH1cbiAgfVxuICBpZiAodW5kb1NjYWxlICYmIGVsICE9PSB3aW5kb3cpIHtcbiAgICAvLyBBZGp1c3QgZm9yIHNjYWxlKClcbiAgICB2YXIgZWxNYXRyaXggPSBtYXRyaXgoY29udGFpbmVyIHx8IGVsKSxcbiAgICAgIHNjYWxlWCA9IGVsTWF0cml4ICYmIGVsTWF0cml4LmEsXG4gICAgICBzY2FsZVkgPSBlbE1hdHJpeCAmJiBlbE1hdHJpeC5kO1xuICAgIGlmIChlbE1hdHJpeCkge1xuICAgICAgdG9wIC89IHNjYWxlWTtcbiAgICAgIGxlZnQgLz0gc2NhbGVYO1xuICAgICAgd2lkdGggLz0gc2NhbGVYO1xuICAgICAgaGVpZ2h0IC89IHNjYWxlWTtcbiAgICAgIGJvdHRvbSA9IHRvcCArIGhlaWdodDtcbiAgICAgIHJpZ2h0ID0gbGVmdCArIHdpZHRoO1xuICAgIH1cbiAgfVxuICByZXR1cm4ge1xuICAgIHRvcDogdG9wLFxuICAgIGxlZnQ6IGxlZnQsXG4gICAgYm90dG9tOiBib3R0b20sXG4gICAgcmlnaHQ6IHJpZ2h0LFxuICAgIHdpZHRoOiB3aWR0aCxcbiAgICBoZWlnaHQ6IGhlaWdodFxuICB9O1xufVxuXG4vKipcclxuICogQ2hlY2tzIGlmIGEgc2lkZSBvZiBhbiBlbGVtZW50IGlzIHNjcm9sbGVkIHBhc3QgYSBzaWRlIG9mIGl0cyBwYXJlbnRzXHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSAgZWwgICAgICAgICAgIFRoZSBlbGVtZW50IHdobydzIHNpZGUgYmVpbmcgc2Nyb2xsZWQgb3V0IG9mIHZpZXcgaXMgaW4gcXVlc3Rpb25cclxuICogQHBhcmFtICB7U3RyaW5nfSAgICAgICBlbFNpZGUgICAgICAgU2lkZSBvZiB0aGUgZWxlbWVudCBpbiBxdWVzdGlvbiAoJ3RvcCcsICdsZWZ0JywgJ3JpZ2h0JywgJ2JvdHRvbScpXHJcbiAqIEBwYXJhbSAge1N0cmluZ30gICAgICAgcGFyZW50U2lkZSAgIFNpZGUgb2YgdGhlIHBhcmVudCBpbiBxdWVzdGlvbiAoJ3RvcCcsICdsZWZ0JywgJ3JpZ2h0JywgJ2JvdHRvbScpXHJcbiAqIEByZXR1cm4ge0hUTUxFbGVtZW50fSAgICAgICAgICAgICAgIFRoZSBwYXJlbnQgc2Nyb2xsIGVsZW1lbnQgdGhhdCB0aGUgZWwncyBzaWRlIGlzIHNjcm9sbGVkIHBhc3QsIG9yIG51bGwgaWYgdGhlcmUgaXMgbm8gc3VjaCBlbGVtZW50XHJcbiAqL1xuZnVuY3Rpb24gaXNTY3JvbGxlZFBhc3QoZWwsIGVsU2lkZSwgcGFyZW50U2lkZSkge1xuICB2YXIgcGFyZW50ID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZWwsIHRydWUpLFxuICAgIGVsU2lkZVZhbCA9IGdldFJlY3QoZWwpW2VsU2lkZV07XG5cbiAgLyoganNoaW50IGJvc3M6dHJ1ZSAqL1xuICB3aGlsZSAocGFyZW50KSB7XG4gICAgdmFyIHBhcmVudFNpZGVWYWwgPSBnZXRSZWN0KHBhcmVudClbcGFyZW50U2lkZV0sXG4gICAgICB2aXNpYmxlID0gdm9pZCAwO1xuICAgIGlmIChwYXJlbnRTaWRlID09PSAndG9wJyB8fCBwYXJlbnRTaWRlID09PSAnbGVmdCcpIHtcbiAgICAgIHZpc2libGUgPSBlbFNpZGVWYWwgPj0gcGFyZW50U2lkZVZhbDtcbiAgICB9IGVsc2Uge1xuICAgICAgdmlzaWJsZSA9IGVsU2lkZVZhbCA8PSBwYXJlbnRTaWRlVmFsO1xuICAgIH1cbiAgICBpZiAoIXZpc2libGUpIHJldHVybiBwYXJlbnQ7XG4gICAgaWYgKHBhcmVudCA9PT0gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpKSBicmVhaztcbiAgICBwYXJlbnQgPSBnZXRQYXJlbnRBdXRvU2Nyb2xsRWxlbWVudChwYXJlbnQsIGZhbHNlKTtcbiAgfVxuICByZXR1cm4gZmFsc2U7XG59XG5cbi8qKlxyXG4gKiBHZXRzIG50aCBjaGlsZCBvZiBlbCwgaWdub3JpbmcgaGlkZGVuIGNoaWxkcmVuLCBzb3J0YWJsZSdzIGVsZW1lbnRzIChkb2VzIG5vdCBpZ25vcmUgY2xvbmUgaWYgaXQncyB2aXNpYmxlKVxyXG4gKiBhbmQgbm9uLWRyYWdnYWJsZSBlbGVtZW50c1xyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWwgICAgICAgVGhlIHBhcmVudCBlbGVtZW50XHJcbiAqIEBwYXJhbSAge051bWJlcn0gY2hpbGROdW0gICAgICBUaGUgaW5kZXggb2YgdGhlIGNoaWxkXHJcbiAqIEBwYXJhbSAge09iamVjdH0gb3B0aW9ucyAgICAgICBQYXJlbnQgU29ydGFibGUncyBvcHRpb25zXHJcbiAqIEByZXR1cm4ge0hUTUxFbGVtZW50fSAgICAgICAgICBUaGUgY2hpbGQgYXQgaW5kZXggY2hpbGROdW0sIG9yIG51bGwgaWYgbm90IGZvdW5kXHJcbiAqL1xuZnVuY3Rpb24gZ2V0Q2hpbGQoZWwsIGNoaWxkTnVtLCBvcHRpb25zLCBpbmNsdWRlRHJhZ0VsKSB7XG4gIHZhciBjdXJyZW50Q2hpbGQgPSAwLFxuICAgIGkgPSAwLFxuICAgIGNoaWxkcmVuID0gZWwuY2hpbGRyZW47XG4gIHdoaWxlIChpIDwgY2hpbGRyZW4ubGVuZ3RoKSB7XG4gICAgaWYgKGNoaWxkcmVuW2ldLnN0eWxlLmRpc3BsYXkgIT09ICdub25lJyAmJiBjaGlsZHJlbltpXSAhPT0gU29ydGFibGUuZ2hvc3QgJiYgKGluY2x1ZGVEcmFnRWwgfHwgY2hpbGRyZW5baV0gIT09IFNvcnRhYmxlLmRyYWdnZWQpICYmIGNsb3Nlc3QoY2hpbGRyZW5baV0sIG9wdGlvbnMuZHJhZ2dhYmxlLCBlbCwgZmFsc2UpKSB7XG4gICAgICBpZiAoY3VycmVudENoaWxkID09PSBjaGlsZE51bSkge1xuICAgICAgICByZXR1cm4gY2hpbGRyZW5baV07XG4gICAgICB9XG4gICAgICBjdXJyZW50Q2hpbGQrKztcbiAgICB9XG4gICAgaSsrO1xuICB9XG4gIHJldHVybiBudWxsO1xufVxuXG4vKipcclxuICogR2V0cyB0aGUgbGFzdCBjaGlsZCBpbiB0aGUgZWwsIGlnbm9yaW5nIGdob3N0RWwgb3IgaW52aXNpYmxlIGVsZW1lbnRzIChjbG9uZXMpXHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSBlbCAgICAgICBQYXJlbnQgZWxlbWVudFxyXG4gKiBAcGFyYW0gIHtzZWxlY3Rvcn0gc2VsZWN0b3IgICAgQW55IG90aGVyIGVsZW1lbnRzIHRoYXQgc2hvdWxkIGJlIGlnbm9yZWRcclxuICogQHJldHVybiB7SFRNTEVsZW1lbnR9ICAgICAgICAgIFRoZSBsYXN0IGNoaWxkLCBpZ25vcmluZyBnaG9zdEVsXHJcbiAqL1xuZnVuY3Rpb24gbGFzdENoaWxkKGVsLCBzZWxlY3Rvcikge1xuICB2YXIgbGFzdCA9IGVsLmxhc3RFbGVtZW50Q2hpbGQ7XG4gIHdoaWxlIChsYXN0ICYmIChsYXN0ID09PSBTb3J0YWJsZS5naG9zdCB8fCBjc3MobGFzdCwgJ2Rpc3BsYXknKSA9PT0gJ25vbmUnIHx8IHNlbGVjdG9yICYmICFtYXRjaGVzKGxhc3QsIHNlbGVjdG9yKSkpIHtcbiAgICBsYXN0ID0gbGFzdC5wcmV2aW91c0VsZW1lbnRTaWJsaW5nO1xuICB9XG4gIHJldHVybiBsYXN0IHx8IG51bGw7XG59XG5cbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBpbmRleCBvZiBhbiBlbGVtZW50IHdpdGhpbiBpdHMgcGFyZW50IGZvciBhIHNlbGVjdGVkIHNldCBvZlxyXG4gKiBlbGVtZW50c1xyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWxcclxuICogQHBhcmFtICB7c2VsZWN0b3J9IHNlbGVjdG9yXHJcbiAqIEByZXR1cm4ge251bWJlcn1cclxuICovXG5mdW5jdGlvbiBpbmRleChlbCwgc2VsZWN0b3IpIHtcbiAgdmFyIGluZGV4ID0gMDtcbiAgaWYgKCFlbCB8fCAhZWwucGFyZW50Tm9kZSkge1xuICAgIHJldHVybiAtMTtcbiAgfVxuXG4gIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgd2hpbGUgKGVsID0gZWwucHJldmlvdXNFbGVtZW50U2libGluZykge1xuICAgIGlmIChlbC5ub2RlTmFtZS50b1VwcGVyQ2FzZSgpICE9PSAnVEVNUExBVEUnICYmIGVsICE9PSBTb3J0YWJsZS5jbG9uZSAmJiAoIXNlbGVjdG9yIHx8IG1hdGNoZXMoZWwsIHNlbGVjdG9yKSkpIHtcbiAgICAgIGluZGV4Kys7XG4gICAgfVxuICB9XG4gIHJldHVybiBpbmRleDtcbn1cblxuLyoqXHJcbiAqIFJldHVybnMgdGhlIHNjcm9sbCBvZmZzZXQgb2YgdGhlIGdpdmVuIGVsZW1lbnQsIGFkZGVkIHdpdGggYWxsIHRoZSBzY3JvbGwgb2Zmc2V0cyBvZiBwYXJlbnQgZWxlbWVudHMuXHJcbiAqIFRoZSB2YWx1ZSBpcyByZXR1cm5lZCBpbiByZWFsIHBpeGVscy5cclxuICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IGVsXHJcbiAqIEByZXR1cm4ge0FycmF5fSAgICAgICAgICAgICBPZmZzZXRzIGluIHRoZSBmb3JtYXQgb2YgW2xlZnQsIHRvcF1cclxuICovXG5mdW5jdGlvbiBnZXRSZWxhdGl2ZVNjcm9sbE9mZnNldChlbCkge1xuICB2YXIgb2Zmc2V0TGVmdCA9IDAsXG4gICAgb2Zmc2V0VG9wID0gMCxcbiAgICB3aW5TY3JvbGxlciA9IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKTtcbiAgaWYgKGVsKSB7XG4gICAgZG8ge1xuICAgICAgdmFyIGVsTWF0cml4ID0gbWF0cml4KGVsKSxcbiAgICAgICAgc2NhbGVYID0gZWxNYXRyaXguYSxcbiAgICAgICAgc2NhbGVZID0gZWxNYXRyaXguZDtcbiAgICAgIG9mZnNldExlZnQgKz0gZWwuc2Nyb2xsTGVmdCAqIHNjYWxlWDtcbiAgICAgIG9mZnNldFRvcCArPSBlbC5zY3JvbGxUb3AgKiBzY2FsZVk7XG4gICAgfSB3aGlsZSAoZWwgIT09IHdpblNjcm9sbGVyICYmIChlbCA9IGVsLnBhcmVudE5vZGUpKTtcbiAgfVxuICByZXR1cm4gW29mZnNldExlZnQsIG9mZnNldFRvcF07XG59XG5cbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBpbmRleCBvZiB0aGUgb2JqZWN0IHdpdGhpbiB0aGUgZ2l2ZW4gYXJyYXlcclxuICogQHBhcmFtICB7QXJyYXl9IGFyciAgIEFycmF5IHRoYXQgbWF5IG9yIG1heSBub3QgaG9sZCB0aGUgb2JqZWN0XHJcbiAqIEBwYXJhbSAge09iamVjdH0gb2JqICBBbiBvYmplY3QgdGhhdCBoYXMgYSBrZXktdmFsdWUgcGFpciB1bmlxdWUgdG8gYW5kIGlkZW50aWNhbCB0byBhIGtleS12YWx1ZSBwYWlyIGluIHRoZSBvYmplY3QgeW91IHdhbnQgdG8gZmluZFxyXG4gKiBAcmV0dXJuIHtOdW1iZXJ9ICAgICAgVGhlIGluZGV4IG9mIHRoZSBvYmplY3QgaW4gdGhlIGFycmF5LCBvciAtMVxyXG4gKi9cbmZ1bmN0aW9uIGluZGV4T2ZPYmplY3QoYXJyLCBvYmopIHtcbiAgZm9yICh2YXIgaSBpbiBhcnIpIHtcbiAgICBpZiAoIWFyci5oYXNPd25Qcm9wZXJ0eShpKSkgY29udGludWU7XG4gICAgZm9yICh2YXIga2V5IGluIG9iaikge1xuICAgICAgaWYgKG9iai5oYXNPd25Qcm9wZXJ0eShrZXkpICYmIG9ialtrZXldID09PSBhcnJbaV1ba2V5XSkgcmV0dXJuIE51bWJlcihpKTtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIC0xO1xufVxuZnVuY3Rpb24gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZWwsIGluY2x1ZGVTZWxmKSB7XG4gIC8vIHNraXAgdG8gd2luZG93XG4gIGlmICghZWwgfHwgIWVsLmdldEJvdW5kaW5nQ2xpZW50UmVjdCkgcmV0dXJuIGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKTtcbiAgdmFyIGVsZW0gPSBlbDtcbiAgdmFyIGdvdFNlbGYgPSBmYWxzZTtcbiAgZG8ge1xuICAgIC8vIHdlIGRvbid0IG5lZWQgdG8gZ2V0IGVsZW0gY3NzIGlmIGl0IGlzbid0IGV2ZW4gb3ZlcmZsb3dpbmcgaW4gdGhlIGZpcnN0IHBsYWNlIChwZXJmb3JtYW5jZSlcbiAgICBpZiAoZWxlbS5jbGllbnRXaWR0aCA8IGVsZW0uc2Nyb2xsV2lkdGggfHwgZWxlbS5jbGllbnRIZWlnaHQgPCBlbGVtLnNjcm9sbEhlaWdodCkge1xuICAgICAgdmFyIGVsZW1DU1MgPSBjc3MoZWxlbSk7XG4gICAgICBpZiAoZWxlbS5jbGllbnRXaWR0aCA8IGVsZW0uc2Nyb2xsV2lkdGggJiYgKGVsZW1DU1Mub3ZlcmZsb3dYID09ICdhdXRvJyB8fCBlbGVtQ1NTLm92ZXJmbG93WCA9PSAnc2Nyb2xsJykgfHwgZWxlbS5jbGllbnRIZWlnaHQgPCBlbGVtLnNjcm9sbEhlaWdodCAmJiAoZWxlbUNTUy5vdmVyZmxvd1kgPT0gJ2F1dG8nIHx8IGVsZW1DU1Mub3ZlcmZsb3dZID09ICdzY3JvbGwnKSkge1xuICAgICAgICBpZiAoIWVsZW0uZ2V0Qm91bmRpbmdDbGllbnRSZWN0IHx8IGVsZW0gPT09IGRvY3VtZW50LmJvZHkpIHJldHVybiBnZXRXaW5kb3dTY3JvbGxpbmdFbGVtZW50KCk7XG4gICAgICAgIGlmIChnb3RTZWxmIHx8IGluY2x1ZGVTZWxmKSByZXR1cm4gZWxlbTtcbiAgICAgICAgZ290U2VsZiA9IHRydWU7XG4gICAgICB9XG4gICAgfVxuICAgIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgfSB3aGlsZSAoZWxlbSA9IGVsZW0ucGFyZW50Tm9kZSk7XG4gIHJldHVybiBnZXRXaW5kb3dTY3JvbGxpbmdFbGVtZW50KCk7XG59XG5mdW5jdGlvbiBleHRlbmQoZHN0LCBzcmMpIHtcbiAgaWYgKGRzdCAmJiBzcmMpIHtcbiAgICBmb3IgKHZhciBrZXkgaW4gc3JjKSB7XG4gICAgICBpZiAoc3JjLmhhc093blByb3BlcnR5KGtleSkpIHtcbiAgICAgICAgZHN0W2tleV0gPSBzcmNba2V5XTtcbiAgICAgIH1cbiAgICB9XG4gIH1cbiAgcmV0dXJuIGRzdDtcbn1cbmZ1bmN0aW9uIGlzUmVjdEVxdWFsKHJlY3QxLCByZWN0Mikge1xuICByZXR1cm4gTWF0aC5yb3VuZChyZWN0MS50b3ApID09PSBNYXRoLnJvdW5kKHJlY3QyLnRvcCkgJiYgTWF0aC5yb3VuZChyZWN0MS5sZWZ0KSA9PT0gTWF0aC5yb3VuZChyZWN0Mi5sZWZ0KSAmJiBNYXRoLnJvdW5kKHJlY3QxLmhlaWdodCkgPT09IE1hdGgucm91bmQocmVjdDIuaGVpZ2h0KSAmJiBNYXRoLnJvdW5kKHJlY3QxLndpZHRoKSA9PT0gTWF0aC5yb3VuZChyZWN0Mi53aWR0aCk7XG59XG52YXIgX3Rocm90dGxlVGltZW91dDtcbmZ1bmN0aW9uIHRocm90dGxlKGNhbGxiYWNrLCBtcykge1xuICByZXR1cm4gZnVuY3Rpb24gKCkge1xuICAgIGlmICghX3Rocm90dGxlVGltZW91dCkge1xuICAgICAgdmFyIGFyZ3MgPSBhcmd1bWVudHMsXG4gICAgICAgIF90aGlzID0gdGhpcztcbiAgICAgIGlmIChhcmdzLmxlbmd0aCA9PT0gMSkge1xuICAgICAgICBjYWxsYmFjay5jYWxsKF90aGlzLCBhcmdzWzBdKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGNhbGxiYWNrLmFwcGx5KF90aGlzLCBhcmdzKTtcbiAgICAgIH1cbiAgICAgIF90aHJvdHRsZVRpbWVvdXQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgX3Rocm90dGxlVGltZW91dCA9IHZvaWQgMDtcbiAgICAgIH0sIG1zKTtcbiAgICB9XG4gIH07XG59XG5mdW5jdGlvbiBjYW5jZWxUaHJvdHRsZSgpIHtcbiAgY2xlYXJUaW1lb3V0KF90aHJvdHRsZVRpbWVvdXQpO1xuICBfdGhyb3R0bGVUaW1lb3V0ID0gdm9pZCAwO1xufVxuZnVuY3Rpb24gc2Nyb2xsQnkoZWwsIHgsIHkpIHtcbiAgZWwuc2Nyb2xsTGVmdCArPSB4O1xuICBlbC5zY3JvbGxUb3AgKz0geTtcbn1cbmZ1bmN0aW9uIGNsb25lKGVsKSB7XG4gIHZhciBQb2x5bWVyID0gd2luZG93LlBvbHltZXI7XG4gIHZhciAkID0gd2luZG93LmpRdWVyeSB8fCB3aW5kb3cuWmVwdG87XG4gIGlmIChQb2x5bWVyICYmIFBvbHltZXIuZG9tKSB7XG4gICAgcmV0dXJuIFBvbHltZXIuZG9tKGVsKS5jbG9uZU5vZGUodHJ1ZSk7XG4gIH0gZWxzZSBpZiAoJCkge1xuICAgIHJldHVybiAkKGVsKS5jbG9uZSh0cnVlKVswXTtcbiAgfSBlbHNlIHtcbiAgICByZXR1cm4gZWwuY2xvbmVOb2RlKHRydWUpO1xuICB9XG59XG5mdW5jdGlvbiBzZXRSZWN0KGVsLCByZWN0KSB7XG4gIGNzcyhlbCwgJ3Bvc2l0aW9uJywgJ2Fic29sdXRlJyk7XG4gIGNzcyhlbCwgJ3RvcCcsIHJlY3QudG9wKTtcbiAgY3NzKGVsLCAnbGVmdCcsIHJlY3QubGVmdCk7XG4gIGNzcyhlbCwgJ3dpZHRoJywgcmVjdC53aWR0aCk7XG4gIGNzcyhlbCwgJ2hlaWdodCcsIHJlY3QuaGVpZ2h0KTtcbn1cbmZ1bmN0aW9uIHVuc2V0UmVjdChlbCkge1xuICBjc3MoZWwsICdwb3NpdGlvbicsICcnKTtcbiAgY3NzKGVsLCAndG9wJywgJycpO1xuICBjc3MoZWwsICdsZWZ0JywgJycpO1xuICBjc3MoZWwsICd3aWR0aCcsICcnKTtcbiAgY3NzKGVsLCAnaGVpZ2h0JywgJycpO1xufVxuZnVuY3Rpb24gZ2V0Q2hpbGRDb250YWluaW5nUmVjdEZyb21FbGVtZW50KGNvbnRhaW5lciwgb3B0aW9ucywgZ2hvc3RFbCkge1xuICB2YXIgcmVjdCA9IHt9O1xuICBBcnJheS5mcm9tKGNvbnRhaW5lci5jaGlsZHJlbikuZm9yRWFjaChmdW5jdGlvbiAoY2hpbGQpIHtcbiAgICB2YXIgX3JlY3QkbGVmdCwgX3JlY3QkdG9wLCBfcmVjdCRyaWdodCwgX3JlY3QkYm90dG9tO1xuICAgIGlmICghY2xvc2VzdChjaGlsZCwgb3B0aW9ucy5kcmFnZ2FibGUsIGNvbnRhaW5lciwgZmFsc2UpIHx8IGNoaWxkLmFuaW1hdGVkIHx8IGNoaWxkID09PSBnaG9zdEVsKSByZXR1cm47XG4gICAgdmFyIGNoaWxkUmVjdCA9IGdldFJlY3QoY2hpbGQpO1xuICAgIHJlY3QubGVmdCA9IE1hdGgubWluKChfcmVjdCRsZWZ0ID0gcmVjdC5sZWZ0KSAhPT0gbnVsbCAmJiBfcmVjdCRsZWZ0ICE9PSB2b2lkIDAgPyBfcmVjdCRsZWZ0IDogSW5maW5pdHksIGNoaWxkUmVjdC5sZWZ0KTtcbiAgICByZWN0LnRvcCA9IE1hdGgubWluKChfcmVjdCR0b3AgPSByZWN0LnRvcCkgIT09IG51bGwgJiYgX3JlY3QkdG9wICE9PSB2b2lkIDAgPyBfcmVjdCR0b3AgOiBJbmZpbml0eSwgY2hpbGRSZWN0LnRvcCk7XG4gICAgcmVjdC5yaWdodCA9IE1hdGgubWF4KChfcmVjdCRyaWdodCA9IHJlY3QucmlnaHQpICE9PSBudWxsICYmIF9yZWN0JHJpZ2h0ICE9PSB2b2lkIDAgPyBfcmVjdCRyaWdodCA6IC1JbmZpbml0eSwgY2hpbGRSZWN0LnJpZ2h0KTtcbiAgICByZWN0LmJvdHRvbSA9IE1hdGgubWF4KChfcmVjdCRib3R0b20gPSByZWN0LmJvdHRvbSkgIT09IG51bGwgJiYgX3JlY3QkYm90dG9tICE9PSB2b2lkIDAgPyBfcmVjdCRib3R0b20gOiAtSW5maW5pdHksIGNoaWxkUmVjdC5ib3R0b20pO1xuICB9KTtcbiAgcmVjdC53aWR0aCA9IHJlY3QucmlnaHQgLSByZWN0LmxlZnQ7XG4gIHJlY3QuaGVpZ2h0ID0gcmVjdC5ib3R0b20gLSByZWN0LnRvcDtcbiAgcmVjdC54ID0gcmVjdC5sZWZ0O1xuICByZWN0LnkgPSByZWN0LnRvcDtcbiAgcmV0dXJuIHJlY3Q7XG59XG52YXIgZXhwYW5kbyA9ICdTb3J0YWJsZScgKyBuZXcgRGF0ZSgpLmdldFRpbWUoKTtcblxuZnVuY3Rpb24gQW5pbWF0aW9uU3RhdGVNYW5hZ2VyKCkge1xuICB2YXIgYW5pbWF0aW9uU3RhdGVzID0gW10sXG4gICAgYW5pbWF0aW9uQ2FsbGJhY2tJZDtcbiAgcmV0dXJuIHtcbiAgICBjYXB0dXJlQW5pbWF0aW9uU3RhdGU6IGZ1bmN0aW9uIGNhcHR1cmVBbmltYXRpb25TdGF0ZSgpIHtcbiAgICAgIGFuaW1hdGlvblN0YXRlcyA9IFtdO1xuICAgICAgaWYgKCF0aGlzLm9wdGlvbnMuYW5pbWF0aW9uKSByZXR1cm47XG4gICAgICB2YXIgY2hpbGRyZW4gPSBbXS5zbGljZS5jYWxsKHRoaXMuZWwuY2hpbGRyZW4pO1xuICAgICAgY2hpbGRyZW4uZm9yRWFjaChmdW5jdGlvbiAoY2hpbGQpIHtcbiAgICAgICAgaWYgKGNzcyhjaGlsZCwgJ2Rpc3BsYXknKSA9PT0gJ25vbmUnIHx8IGNoaWxkID09PSBTb3J0YWJsZS5naG9zdCkgcmV0dXJuO1xuICAgICAgICBhbmltYXRpb25TdGF0ZXMucHVzaCh7XG4gICAgICAgICAgdGFyZ2V0OiBjaGlsZCxcbiAgICAgICAgICByZWN0OiBnZXRSZWN0KGNoaWxkKVxuICAgICAgICB9KTtcbiAgICAgICAgdmFyIGZyb21SZWN0ID0gX29iamVjdFNwcmVhZDIoe30sIGFuaW1hdGlvblN0YXRlc1thbmltYXRpb25TdGF0ZXMubGVuZ3RoIC0gMV0ucmVjdCk7XG5cbiAgICAgICAgLy8gSWYgYW5pbWF0aW5nOiBjb21wZW5zYXRlIGZvciBjdXJyZW50IGFuaW1hdGlvblxuICAgICAgICBpZiAoY2hpbGQudGhpc0FuaW1hdGlvbkR1cmF0aW9uKSB7XG4gICAgICAgICAgdmFyIGNoaWxkTWF0cml4ID0gbWF0cml4KGNoaWxkLCB0cnVlKTtcbiAgICAgICAgICBpZiAoY2hpbGRNYXRyaXgpIHtcbiAgICAgICAgICAgIGZyb21SZWN0LnRvcCAtPSBjaGlsZE1hdHJpeC5mO1xuICAgICAgICAgICAgZnJvbVJlY3QubGVmdCAtPSBjaGlsZE1hdHJpeC5lO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBjaGlsZC5mcm9tUmVjdCA9IGZyb21SZWN0O1xuICAgICAgfSk7XG4gICAgfSxcbiAgICBhZGRBbmltYXRpb25TdGF0ZTogZnVuY3Rpb24gYWRkQW5pbWF0aW9uU3RhdGUoc3RhdGUpIHtcbiAgICAgIGFuaW1hdGlvblN0YXRlcy5wdXNoKHN0YXRlKTtcbiAgICB9LFxuICAgIHJlbW92ZUFuaW1hdGlvblN0YXRlOiBmdW5jdGlvbiByZW1vdmVBbmltYXRpb25TdGF0ZSh0YXJnZXQpIHtcbiAgICAgIGFuaW1hdGlvblN0YXRlcy5zcGxpY2UoaW5kZXhPZk9iamVjdChhbmltYXRpb25TdGF0ZXMsIHtcbiAgICAgICAgdGFyZ2V0OiB0YXJnZXRcbiAgICAgIH0pLCAxKTtcbiAgICB9LFxuICAgIGFuaW1hdGVBbGw6IGZ1bmN0aW9uIGFuaW1hdGVBbGwoY2FsbGJhY2spIHtcbiAgICAgIHZhciBfdGhpcyA9IHRoaXM7XG4gICAgICBpZiAoIXRoaXMub3B0aW9ucy5hbmltYXRpb24pIHtcbiAgICAgICAgY2xlYXJUaW1lb3V0KGFuaW1hdGlvbkNhbGxiYWNrSWQpO1xuICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSBjYWxsYmFjaygpO1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICB2YXIgYW5pbWF0aW5nID0gZmFsc2UsXG4gICAgICAgIGFuaW1hdGlvblRpbWUgPSAwO1xuICAgICAgYW5pbWF0aW9uU3RhdGVzLmZvckVhY2goZnVuY3Rpb24gKHN0YXRlKSB7XG4gICAgICAgIHZhciB0aW1lID0gMCxcbiAgICAgICAgICB0YXJnZXQgPSBzdGF0ZS50YXJnZXQsXG4gICAgICAgICAgZnJvbVJlY3QgPSB0YXJnZXQuZnJvbVJlY3QsXG4gICAgICAgICAgdG9SZWN0ID0gZ2V0UmVjdCh0YXJnZXQpLFxuICAgICAgICAgIHByZXZGcm9tUmVjdCA9IHRhcmdldC5wcmV2RnJvbVJlY3QsXG4gICAgICAgICAgcHJldlRvUmVjdCA9IHRhcmdldC5wcmV2VG9SZWN0LFxuICAgICAgICAgIGFuaW1hdGluZ1JlY3QgPSBzdGF0ZS5yZWN0LFxuICAgICAgICAgIHRhcmdldE1hdHJpeCA9IG1hdHJpeCh0YXJnZXQsIHRydWUpO1xuICAgICAgICBpZiAodGFyZ2V0TWF0cml4KSB7XG4gICAgICAgICAgLy8gQ29tcGVuc2F0ZSBmb3IgY3VycmVudCBhbmltYXRpb25cbiAgICAgICAgICB0b1JlY3QudG9wIC09IHRhcmdldE1hdHJpeC5mO1xuICAgICAgICAgIHRvUmVjdC5sZWZ0IC09IHRhcmdldE1hdHJpeC5lO1xuICAgICAgICB9XG4gICAgICAgIHRhcmdldC50b1JlY3QgPSB0b1JlY3Q7XG4gICAgICAgIGlmICh0YXJnZXQudGhpc0FuaW1hdGlvbkR1cmF0aW9uKSB7XG4gICAgICAgICAgLy8gQ291bGQgYWxzbyBjaGVjayBpZiBhbmltYXRpbmdSZWN0IGlzIGJldHdlZW4gZnJvbVJlY3QgYW5kIHRvUmVjdFxuICAgICAgICAgIGlmIChpc1JlY3RFcXVhbChwcmV2RnJvbVJlY3QsIHRvUmVjdCkgJiYgIWlzUmVjdEVxdWFsKGZyb21SZWN0LCB0b1JlY3QpICYmXG4gICAgICAgICAgLy8gTWFrZSBzdXJlIGFuaW1hdGluZ1JlY3QgaXMgb24gbGluZSBiZXR3ZWVuIHRvUmVjdCAmIGZyb21SZWN0XG4gICAgICAgICAgKGFuaW1hdGluZ1JlY3QudG9wIC0gdG9SZWN0LnRvcCkgLyAoYW5pbWF0aW5nUmVjdC5sZWZ0IC0gdG9SZWN0LmxlZnQpID09PSAoZnJvbVJlY3QudG9wIC0gdG9SZWN0LnRvcCkgLyAoZnJvbVJlY3QubGVmdCAtIHRvUmVjdC5sZWZ0KSkge1xuICAgICAgICAgICAgLy8gSWYgcmV0dXJuaW5nIHRvIHNhbWUgcGxhY2UgYXMgc3RhcnRlZCBmcm9tIGFuaW1hdGlvbiBhbmQgb24gc2FtZSBheGlzXG4gICAgICAgICAgICB0aW1lID0gY2FsY3VsYXRlUmVhbFRpbWUoYW5pbWF0aW5nUmVjdCwgcHJldkZyb21SZWN0LCBwcmV2VG9SZWN0LCBfdGhpcy5vcHRpb25zKTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICAvLyBpZiBmcm9tUmVjdCAhPSB0b1JlY3Q6IGFuaW1hdGVcbiAgICAgICAgaWYgKCFpc1JlY3RFcXVhbCh0b1JlY3QsIGZyb21SZWN0KSkge1xuICAgICAgICAgIHRhcmdldC5wcmV2RnJvbVJlY3QgPSBmcm9tUmVjdDtcbiAgICAgICAgICB0YXJnZXQucHJldlRvUmVjdCA9IHRvUmVjdDtcbiAgICAgICAgICBpZiAoIXRpbWUpIHtcbiAgICAgICAgICAgIHRpbWUgPSBfdGhpcy5vcHRpb25zLmFuaW1hdGlvbjtcbiAgICAgICAgICB9XG4gICAgICAgICAgX3RoaXMuYW5pbWF0ZSh0YXJnZXQsIGFuaW1hdGluZ1JlY3QsIHRvUmVjdCwgdGltZSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRpbWUpIHtcbiAgICAgICAgICBhbmltYXRpbmcgPSB0cnVlO1xuICAgICAgICAgIGFuaW1hdGlvblRpbWUgPSBNYXRoLm1heChhbmltYXRpb25UaW1lLCB0aW1lKTtcbiAgICAgICAgICBjbGVhclRpbWVvdXQodGFyZ2V0LmFuaW1hdGlvblJlc2V0VGltZXIpO1xuICAgICAgICAgIHRhcmdldC5hbmltYXRpb25SZXNldFRpbWVyID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0YXJnZXQuYW5pbWF0aW9uVGltZSA9IDA7XG4gICAgICAgICAgICB0YXJnZXQucHJldkZyb21SZWN0ID0gbnVsbDtcbiAgICAgICAgICAgIHRhcmdldC5mcm9tUmVjdCA9IG51bGw7XG4gICAgICAgICAgICB0YXJnZXQucHJldlRvUmVjdCA9IG51bGw7XG4gICAgICAgICAgICB0YXJnZXQudGhpc0FuaW1hdGlvbkR1cmF0aW9uID0gbnVsbDtcbiAgICAgICAgICB9LCB0aW1lKTtcbiAgICAgICAgICB0YXJnZXQudGhpc0FuaW1hdGlvbkR1cmF0aW9uID0gdGltZTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgICBjbGVhclRpbWVvdXQoYW5pbWF0aW9uQ2FsbGJhY2tJZCk7XG4gICAgICBpZiAoIWFuaW1hdGluZykge1xuICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSBjYWxsYmFjaygpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgYW5pbWF0aW9uQ2FsbGJhY2tJZCA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGlmICh0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIGNhbGxiYWNrKCk7XG4gICAgICAgIH0sIGFuaW1hdGlvblRpbWUpO1xuICAgICAgfVxuICAgICAgYW5pbWF0aW9uU3RhdGVzID0gW107XG4gICAgfSxcbiAgICBhbmltYXRlOiBmdW5jdGlvbiBhbmltYXRlKHRhcmdldCwgY3VycmVudFJlY3QsIHRvUmVjdCwgZHVyYXRpb24pIHtcbiAgICAgIGlmIChkdXJhdGlvbikge1xuICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNpdGlvbicsICcnKTtcbiAgICAgICAgY3NzKHRhcmdldCwgJ3RyYW5zZm9ybScsICcnKTtcbiAgICAgICAgdmFyIGVsTWF0cml4ID0gbWF0cml4KHRoaXMuZWwpLFxuICAgICAgICAgIHNjYWxlWCA9IGVsTWF0cml4ICYmIGVsTWF0cml4LmEsXG4gICAgICAgICAgc2NhbGVZID0gZWxNYXRyaXggJiYgZWxNYXRyaXguZCxcbiAgICAgICAgICB0cmFuc2xhdGVYID0gKGN1cnJlbnRSZWN0LmxlZnQgLSB0b1JlY3QubGVmdCkgLyAoc2NhbGVYIHx8IDEpLFxuICAgICAgICAgIHRyYW5zbGF0ZVkgPSAoY3VycmVudFJlY3QudG9wIC0gdG9SZWN0LnRvcCkgLyAoc2NhbGVZIHx8IDEpO1xuICAgICAgICB0YXJnZXQuYW5pbWF0aW5nWCA9ICEhdHJhbnNsYXRlWDtcbiAgICAgICAgdGFyZ2V0LmFuaW1hdGluZ1kgPSAhIXRyYW5zbGF0ZVk7XG4gICAgICAgIGNzcyh0YXJnZXQsICd0cmFuc2Zvcm0nLCAndHJhbnNsYXRlM2QoJyArIHRyYW5zbGF0ZVggKyAncHgsJyArIHRyYW5zbGF0ZVkgKyAncHgsMCknKTtcbiAgICAgICAgdGhpcy5mb3JSZXBhaW50RHVtbXkgPSByZXBhaW50KHRhcmdldCk7IC8vIHJlcGFpbnRcblxuICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNpdGlvbicsICd0cmFuc2Zvcm0gJyArIGR1cmF0aW9uICsgJ21zJyArICh0aGlzLm9wdGlvbnMuZWFzaW5nID8gJyAnICsgdGhpcy5vcHRpb25zLmVhc2luZyA6ICcnKSk7XG4gICAgICAgIGNzcyh0YXJnZXQsICd0cmFuc2Zvcm0nLCAndHJhbnNsYXRlM2QoMCwwLDApJyk7XG4gICAgICAgIHR5cGVvZiB0YXJnZXQuYW5pbWF0ZWQgPT09ICdudW1iZXInICYmIGNsZWFyVGltZW91dCh0YXJnZXQuYW5pbWF0ZWQpO1xuICAgICAgICB0YXJnZXQuYW5pbWF0ZWQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNpdGlvbicsICcnKTtcbiAgICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNmb3JtJywgJycpO1xuICAgICAgICAgIHRhcmdldC5hbmltYXRlZCA9IGZhbHNlO1xuICAgICAgICAgIHRhcmdldC5hbmltYXRpbmdYID0gZmFsc2U7XG4gICAgICAgICAgdGFyZ2V0LmFuaW1hdGluZ1kgPSBmYWxzZTtcbiAgICAgICAgfSwgZHVyYXRpb24pO1xuICAgICAgfVxuICAgIH1cbiAgfTtcbn1cbmZ1bmN0aW9uIHJlcGFpbnQodGFyZ2V0KSB7XG4gIHJldHVybiB0YXJnZXQub2Zmc2V0V2lkdGg7XG59XG5mdW5jdGlvbiBjYWxjdWxhdGVSZWFsVGltZShhbmltYXRpbmdSZWN0LCBmcm9tUmVjdCwgdG9SZWN0LCBvcHRpb25zKSB7XG4gIHJldHVybiBNYXRoLnNxcnQoTWF0aC5wb3coZnJvbVJlY3QudG9wIC0gYW5pbWF0aW5nUmVjdC50b3AsIDIpICsgTWF0aC5wb3coZnJvbVJlY3QubGVmdCAtIGFuaW1hdGluZ1JlY3QubGVmdCwgMikpIC8gTWF0aC5zcXJ0KE1hdGgucG93KGZyb21SZWN0LnRvcCAtIHRvUmVjdC50b3AsIDIpICsgTWF0aC5wb3coZnJvbVJlY3QubGVmdCAtIHRvUmVjdC5sZWZ0LCAyKSkgKiBvcHRpb25zLmFuaW1hdGlvbjtcbn1cblxudmFyIHBsdWdpbnMgPSBbXTtcbnZhciBkZWZhdWx0cyA9IHtcbiAgaW5pdGlhbGl6ZUJ5RGVmYXVsdDogdHJ1ZVxufTtcbnZhciBQbHVnaW5NYW5hZ2VyID0ge1xuICBtb3VudDogZnVuY3Rpb24gbW91bnQocGx1Z2luKSB7XG4gICAgLy8gU2V0IGRlZmF1bHQgc3RhdGljIHByb3BlcnRpZXNcbiAgICBmb3IgKHZhciBvcHRpb24gaW4gZGVmYXVsdHMpIHtcbiAgICAgIGlmIChkZWZhdWx0cy5oYXNPd25Qcm9wZXJ0eShvcHRpb24pICYmICEob3B0aW9uIGluIHBsdWdpbikpIHtcbiAgICAgICAgcGx1Z2luW29wdGlvbl0gPSBkZWZhdWx0c1tvcHRpb25dO1xuICAgICAgfVxuICAgIH1cbiAgICBwbHVnaW5zLmZvckVhY2goZnVuY3Rpb24gKHApIHtcbiAgICAgIGlmIChwLnBsdWdpbk5hbWUgPT09IHBsdWdpbi5wbHVnaW5OYW1lKSB7XG4gICAgICAgIHRocm93IFwiU29ydGFibGU6IENhbm5vdCBtb3VudCBwbHVnaW4gXCIuY29uY2F0KHBsdWdpbi5wbHVnaW5OYW1lLCBcIiBtb3JlIHRoYW4gb25jZVwiKTtcbiAgICAgIH1cbiAgICB9KTtcbiAgICBwbHVnaW5zLnB1c2gocGx1Z2luKTtcbiAgfSxcbiAgcGx1Z2luRXZlbnQ6IGZ1bmN0aW9uIHBsdWdpbkV2ZW50KGV2ZW50TmFtZSwgc29ydGFibGUsIGV2dCkge1xuICAgIHZhciBfdGhpcyA9IHRoaXM7XG4gICAgdGhpcy5ldmVudENhbmNlbGVkID0gZmFsc2U7XG4gICAgZXZ0LmNhbmNlbCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgIF90aGlzLmV2ZW50Q2FuY2VsZWQgPSB0cnVlO1xuICAgIH07XG4gICAgdmFyIGV2ZW50TmFtZUdsb2JhbCA9IGV2ZW50TmFtZSArICdHbG9iYWwnO1xuICAgIHBsdWdpbnMuZm9yRWFjaChmdW5jdGlvbiAocGx1Z2luKSB7XG4gICAgICBpZiAoIXNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXSkgcmV0dXJuO1xuICAgICAgLy8gRmlyZSBnbG9iYWwgZXZlbnRzIGlmIGl0IGV4aXN0cyBpbiB0aGlzIHNvcnRhYmxlXG4gICAgICBpZiAoc29ydGFibGVbcGx1Z2luLnBsdWdpbk5hbWVdW2V2ZW50TmFtZUdsb2JhbF0pIHtcbiAgICAgICAgc29ydGFibGVbcGx1Z2luLnBsdWdpbk5hbWVdW2V2ZW50TmFtZUdsb2JhbF0oX29iamVjdFNwcmVhZDIoe1xuICAgICAgICAgIHNvcnRhYmxlOiBzb3J0YWJsZVxuICAgICAgICB9LCBldnQpKTtcbiAgICAgIH1cblxuICAgICAgLy8gT25seSBmaXJlIHBsdWdpbiBldmVudCBpZiBwbHVnaW4gaXMgZW5hYmxlZCBpbiB0aGlzIHNvcnRhYmxlLFxuICAgICAgLy8gYW5kIHBsdWdpbiBoYXMgZXZlbnQgZGVmaW5lZFxuICAgICAgaWYgKHNvcnRhYmxlLm9wdGlvbnNbcGx1Z2luLnBsdWdpbk5hbWVdICYmIHNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXVtldmVudE5hbWVdKSB7XG4gICAgICAgIHNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXVtldmVudE5hbWVdKF9vYmplY3RTcHJlYWQyKHtcbiAgICAgICAgICBzb3J0YWJsZTogc29ydGFibGVcbiAgICAgICAgfSwgZXZ0KSk7XG4gICAgICB9XG4gICAgfSk7XG4gIH0sXG4gIGluaXRpYWxpemVQbHVnaW5zOiBmdW5jdGlvbiBpbml0aWFsaXplUGx1Z2lucyhzb3J0YWJsZSwgZWwsIGRlZmF1bHRzLCBvcHRpb25zKSB7XG4gICAgcGx1Z2lucy5mb3JFYWNoKGZ1bmN0aW9uIChwbHVnaW4pIHtcbiAgICAgIHZhciBwbHVnaW5OYW1lID0gcGx1Z2luLnBsdWdpbk5hbWU7XG4gICAgICBpZiAoIXNvcnRhYmxlLm9wdGlvbnNbcGx1Z2luTmFtZV0gJiYgIXBsdWdpbi5pbml0aWFsaXplQnlEZWZhdWx0KSByZXR1cm47XG4gICAgICB2YXIgaW5pdGlhbGl6ZWQgPSBuZXcgcGx1Z2luKHNvcnRhYmxlLCBlbCwgc29ydGFibGUub3B0aW9ucyk7XG4gICAgICBpbml0aWFsaXplZC5zb3J0YWJsZSA9IHNvcnRhYmxlO1xuICAgICAgaW5pdGlhbGl6ZWQub3B0aW9ucyA9IHNvcnRhYmxlLm9wdGlvbnM7XG4gICAgICBzb3J0YWJsZVtwbHVnaW5OYW1lXSA9IGluaXRpYWxpemVkO1xuXG4gICAgICAvLyBBZGQgZGVmYXVsdCBvcHRpb25zIGZyb20gcGx1Z2luXG4gICAgICBfZXh0ZW5kcyhkZWZhdWx0cywgaW5pdGlhbGl6ZWQuZGVmYXVsdHMpO1xuICAgIH0pO1xuICAgIGZvciAodmFyIG9wdGlvbiBpbiBzb3J0YWJsZS5vcHRpb25zKSB7XG4gICAgICBpZiAoIXNvcnRhYmxlLm9wdGlvbnMuaGFzT3duUHJvcGVydHkob3B0aW9uKSkgY29udGludWU7XG4gICAgICB2YXIgbW9kaWZpZWQgPSB0aGlzLm1vZGlmeU9wdGlvbihzb3J0YWJsZSwgb3B0aW9uLCBzb3J0YWJsZS5vcHRpb25zW29wdGlvbl0pO1xuICAgICAgaWYgKHR5cGVvZiBtb2RpZmllZCAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgc29ydGFibGUub3B0aW9uc1tvcHRpb25dID0gbW9kaWZpZWQ7XG4gICAgICB9XG4gICAgfVxuICB9LFxuICBnZXRFdmVudFByb3BlcnRpZXM6IGZ1bmN0aW9uIGdldEV2ZW50UHJvcGVydGllcyhuYW1lLCBzb3J0YWJsZSkge1xuICAgIHZhciBldmVudFByb3BlcnRpZXMgPSB7fTtcbiAgICBwbHVnaW5zLmZvckVhY2goZnVuY3Rpb24gKHBsdWdpbikge1xuICAgICAgaWYgKHR5cGVvZiBwbHVnaW4uZXZlbnRQcm9wZXJ0aWVzICE9PSAnZnVuY3Rpb24nKSByZXR1cm47XG4gICAgICBfZXh0ZW5kcyhldmVudFByb3BlcnRpZXMsIHBsdWdpbi5ldmVudFByb3BlcnRpZXMuY2FsbChzb3J0YWJsZVtwbHVnaW4ucGx1Z2luTmFtZV0sIG5hbWUpKTtcbiAgICB9KTtcbiAgICByZXR1cm4gZXZlbnRQcm9wZXJ0aWVzO1xuICB9LFxuICBtb2RpZnlPcHRpb246IGZ1bmN0aW9uIG1vZGlmeU9wdGlvbihzb3J0YWJsZSwgbmFtZSwgdmFsdWUpIHtcbiAgICB2YXIgbW9kaWZpZWRWYWx1ZTtcbiAgICBwbHVnaW5zLmZvckVhY2goZnVuY3Rpb24gKHBsdWdpbikge1xuICAgICAgLy8gUGx1Z2luIG11c3QgZXhpc3Qgb24gdGhlIFNvcnRhYmxlXG4gICAgICBpZiAoIXNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXSkgcmV0dXJuO1xuXG4gICAgICAvLyBJZiBzdGF0aWMgb3B0aW9uIGxpc3RlbmVyIGV4aXN0cyBmb3IgdGhpcyBvcHRpb24sIGNhbGwgaW4gdGhlIGNvbnRleHQgb2YgdGhlIFNvcnRhYmxlJ3MgaW5zdGFuY2Ugb2YgdGhpcyBwbHVnaW5cbiAgICAgIGlmIChwbHVnaW4ub3B0aW9uTGlzdGVuZXJzICYmIHR5cGVvZiBwbHVnaW4ub3B0aW9uTGlzdGVuZXJzW25hbWVdID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgIG1vZGlmaWVkVmFsdWUgPSBwbHVnaW4ub3B0aW9uTGlzdGVuZXJzW25hbWVdLmNhbGwoc29ydGFibGVbcGx1Z2luLnBsdWdpbk5hbWVdLCB2YWx1ZSk7XG4gICAgICB9XG4gICAgfSk7XG4gICAgcmV0dXJuIG1vZGlmaWVkVmFsdWU7XG4gIH1cbn07XG5cbmZ1bmN0aW9uIGRpc3BhdGNoRXZlbnQoX3JlZikge1xuICB2YXIgc29ydGFibGUgPSBfcmVmLnNvcnRhYmxlLFxuICAgIHJvb3RFbCA9IF9yZWYucm9vdEVsLFxuICAgIG5hbWUgPSBfcmVmLm5hbWUsXG4gICAgdGFyZ2V0RWwgPSBfcmVmLnRhcmdldEVsLFxuICAgIGNsb25lRWwgPSBfcmVmLmNsb25lRWwsXG4gICAgdG9FbCA9IF9yZWYudG9FbCxcbiAgICBmcm9tRWwgPSBfcmVmLmZyb21FbCxcbiAgICBvbGRJbmRleCA9IF9yZWYub2xkSW5kZXgsXG4gICAgbmV3SW5kZXggPSBfcmVmLm5ld0luZGV4LFxuICAgIG9sZERyYWdnYWJsZUluZGV4ID0gX3JlZi5vbGREcmFnZ2FibGVJbmRleCxcbiAgICBuZXdEcmFnZ2FibGVJbmRleCA9IF9yZWYubmV3RHJhZ2dhYmxlSW5kZXgsXG4gICAgb3JpZ2luYWxFdmVudCA9IF9yZWYub3JpZ2luYWxFdmVudCxcbiAgICBwdXRTb3J0YWJsZSA9IF9yZWYucHV0U29ydGFibGUsXG4gICAgZXh0cmFFdmVudFByb3BlcnRpZXMgPSBfcmVmLmV4dHJhRXZlbnRQcm9wZXJ0aWVzO1xuICBzb3J0YWJsZSA9IHNvcnRhYmxlIHx8IHJvb3RFbCAmJiByb290RWxbZXhwYW5kb107XG4gIGlmICghc29ydGFibGUpIHJldHVybjtcbiAgdmFyIGV2dCxcbiAgICBvcHRpb25zID0gc29ydGFibGUub3B0aW9ucyxcbiAgICBvbk5hbWUgPSAnb24nICsgbmFtZS5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIG5hbWUuc3Vic3RyKDEpO1xuICAvLyBTdXBwb3J0IGZvciBuZXcgQ3VzdG9tRXZlbnQgZmVhdHVyZVxuICBpZiAod2luZG93LkN1c3RvbUV2ZW50ICYmICFJRTExT3JMZXNzICYmICFFZGdlKSB7XG4gICAgZXZ0ID0gbmV3IEN1c3RvbUV2ZW50KG5hbWUsIHtcbiAgICAgIGJ1YmJsZXM6IHRydWUsXG4gICAgICBjYW5jZWxhYmxlOiB0cnVlXG4gICAgfSk7XG4gIH0gZWxzZSB7XG4gICAgZXZ0ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ0V2ZW50Jyk7XG4gICAgZXZ0LmluaXRFdmVudChuYW1lLCB0cnVlLCB0cnVlKTtcbiAgfVxuICBldnQudG8gPSB0b0VsIHx8IHJvb3RFbDtcbiAgZXZ0LmZyb20gPSBmcm9tRWwgfHwgcm9vdEVsO1xuICBldnQuaXRlbSA9IHRhcmdldEVsIHx8IHJvb3RFbDtcbiAgZXZ0LmNsb25lID0gY2xvbmVFbDtcbiAgZXZ0Lm9sZEluZGV4ID0gb2xkSW5kZXg7XG4gIGV2dC5uZXdJbmRleCA9IG5ld0luZGV4O1xuICBldnQub2xkRHJhZ2dhYmxlSW5kZXggPSBvbGREcmFnZ2FibGVJbmRleDtcbiAgZXZ0Lm5ld0RyYWdnYWJsZUluZGV4ID0gbmV3RHJhZ2dhYmxlSW5kZXg7XG4gIGV2dC5vcmlnaW5hbEV2ZW50ID0gb3JpZ2luYWxFdmVudDtcbiAgZXZ0LnB1bGxNb2RlID0gcHV0U29ydGFibGUgPyBwdXRTb3J0YWJsZS5sYXN0UHV0TW9kZSA6IHVuZGVmaW5lZDtcbiAgdmFyIGFsbEV2ZW50UHJvcGVydGllcyA9IF9vYmplY3RTcHJlYWQyKF9vYmplY3RTcHJlYWQyKHt9LCBleHRyYUV2ZW50UHJvcGVydGllcyksIFBsdWdpbk1hbmFnZXIuZ2V0RXZlbnRQcm9wZXJ0aWVzKG5hbWUsIHNvcnRhYmxlKSk7XG4gIGZvciAodmFyIG9wdGlvbiBpbiBhbGxFdmVudFByb3BlcnRpZXMpIHtcbiAgICBldnRbb3B0aW9uXSA9IGFsbEV2ZW50UHJvcGVydGllc1tvcHRpb25dO1xuICB9XG4gIGlmIChyb290RWwpIHtcbiAgICByb290RWwuZGlzcGF0Y2hFdmVudChldnQpO1xuICB9XG4gIGlmIChvcHRpb25zW29uTmFtZV0pIHtcbiAgICBvcHRpb25zW29uTmFtZV0uY2FsbChzb3J0YWJsZSwgZXZ0KTtcbiAgfVxufVxuXG52YXIgX2V4Y2x1ZGVkID0gW1wiZXZ0XCJdO1xudmFyIHBsdWdpbkV2ZW50ID0gZnVuY3Rpb24gcGx1Z2luRXZlbnQoZXZlbnROYW1lLCBzb3J0YWJsZSkge1xuICB2YXIgX3JlZiA9IGFyZ3VtZW50cy5sZW5ndGggPiAyICYmIGFyZ3VtZW50c1syXSAhPT0gdW5kZWZpbmVkID8gYXJndW1lbnRzWzJdIDoge30sXG4gICAgb3JpZ2luYWxFdmVudCA9IF9yZWYuZXZ0LFxuICAgIGRhdGEgPSBfb2JqZWN0V2l0aG91dFByb3BlcnRpZXMoX3JlZiwgX2V4Y2x1ZGVkKTtcbiAgUGx1Z2luTWFuYWdlci5wbHVnaW5FdmVudC5iaW5kKFNvcnRhYmxlKShldmVudE5hbWUsIHNvcnRhYmxlLCBfb2JqZWN0U3ByZWFkMih7XG4gICAgZHJhZ0VsOiBkcmFnRWwsXG4gICAgcGFyZW50RWw6IHBhcmVudEVsLFxuICAgIGdob3N0RWw6IGdob3N0RWwsXG4gICAgcm9vdEVsOiByb290RWwsXG4gICAgbmV4dEVsOiBuZXh0RWwsXG4gICAgbGFzdERvd25FbDogbGFzdERvd25FbCxcbiAgICBjbG9uZUVsOiBjbG9uZUVsLFxuICAgIGNsb25lSGlkZGVuOiBjbG9uZUhpZGRlbixcbiAgICBkcmFnU3RhcnRlZDogbW92ZWQsXG4gICAgcHV0U29ydGFibGU6IHB1dFNvcnRhYmxlLFxuICAgIGFjdGl2ZVNvcnRhYmxlOiBTb3J0YWJsZS5hY3RpdmUsXG4gICAgb3JpZ2luYWxFdmVudDogb3JpZ2luYWxFdmVudCxcbiAgICBvbGRJbmRleDogb2xkSW5kZXgsXG4gICAgb2xkRHJhZ2dhYmxlSW5kZXg6IG9sZERyYWdnYWJsZUluZGV4LFxuICAgIG5ld0luZGV4OiBuZXdJbmRleCxcbiAgICBuZXdEcmFnZ2FibGVJbmRleDogbmV3RHJhZ2dhYmxlSW5kZXgsXG4gICAgaGlkZUdob3N0Rm9yVGFyZ2V0OiBfaGlkZUdob3N0Rm9yVGFyZ2V0LFxuICAgIHVuaGlkZUdob3N0Rm9yVGFyZ2V0OiBfdW5oaWRlR2hvc3RGb3JUYXJnZXQsXG4gICAgY2xvbmVOb3dIaWRkZW46IGZ1bmN0aW9uIGNsb25lTm93SGlkZGVuKCkge1xuICAgICAgY2xvbmVIaWRkZW4gPSB0cnVlO1xuICAgIH0sXG4gICAgY2xvbmVOb3dTaG93bjogZnVuY3Rpb24gY2xvbmVOb3dTaG93bigpIHtcbiAgICAgIGNsb25lSGlkZGVuID0gZmFsc2U7XG4gICAgfSxcbiAgICBkaXNwYXRjaFNvcnRhYmxlRXZlbnQ6IGZ1bmN0aW9uIGRpc3BhdGNoU29ydGFibGVFdmVudChuYW1lKSB7XG4gICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgIHNvcnRhYmxlOiBzb3J0YWJsZSxcbiAgICAgICAgbmFtZTogbmFtZSxcbiAgICAgICAgb3JpZ2luYWxFdmVudDogb3JpZ2luYWxFdmVudFxuICAgICAgfSk7XG4gICAgfVxuICB9LCBkYXRhKSk7XG59O1xuZnVuY3Rpb24gX2Rpc3BhdGNoRXZlbnQoaW5mbykge1xuICBkaXNwYXRjaEV2ZW50KF9vYmplY3RTcHJlYWQyKHtcbiAgICBwdXRTb3J0YWJsZTogcHV0U29ydGFibGUsXG4gICAgY2xvbmVFbDogY2xvbmVFbCxcbiAgICB0YXJnZXRFbDogZHJhZ0VsLFxuICAgIHJvb3RFbDogcm9vdEVsLFxuICAgIG9sZEluZGV4OiBvbGRJbmRleCxcbiAgICBvbGREcmFnZ2FibGVJbmRleDogb2xkRHJhZ2dhYmxlSW5kZXgsXG4gICAgbmV3SW5kZXg6IG5ld0luZGV4LFxuICAgIG5ld0RyYWdnYWJsZUluZGV4OiBuZXdEcmFnZ2FibGVJbmRleFxuICB9LCBpbmZvKSk7XG59XG52YXIgZHJhZ0VsLFxuICBwYXJlbnRFbCxcbiAgZ2hvc3RFbCxcbiAgcm9vdEVsLFxuICBuZXh0RWwsXG4gIGxhc3REb3duRWwsXG4gIGNsb25lRWwsXG4gIGNsb25lSGlkZGVuLFxuICBvbGRJbmRleCxcbiAgbmV3SW5kZXgsXG4gIG9sZERyYWdnYWJsZUluZGV4LFxuICBuZXdEcmFnZ2FibGVJbmRleCxcbiAgYWN0aXZlR3JvdXAsXG4gIHB1dFNvcnRhYmxlLFxuICBhd2FpdGluZ0RyYWdTdGFydGVkID0gZmFsc2UsXG4gIGlnbm9yZU5leHRDbGljayA9IGZhbHNlLFxuICBzb3J0YWJsZXMgPSBbXSxcbiAgdGFwRXZ0LFxuICB0b3VjaEV2dCxcbiAgbGFzdER4LFxuICBsYXN0RHksXG4gIHRhcERpc3RhbmNlTGVmdCxcbiAgdGFwRGlzdGFuY2VUb3AsXG4gIG1vdmVkLFxuICBsYXN0VGFyZ2V0LFxuICBsYXN0RGlyZWN0aW9uLFxuICBwYXN0Rmlyc3RJbnZlcnRUaHJlc2ggPSBmYWxzZSxcbiAgaXNDaXJjdW1zdGFudGlhbEludmVydCA9IGZhbHNlLFxuICB0YXJnZXRNb3ZlRGlzdGFuY2UsXG4gIC8vIEZvciBwb3NpdGlvbmluZyBnaG9zdCBhYnNvbHV0ZWx5XG4gIGdob3N0UmVsYXRpdmVQYXJlbnQsXG4gIGdob3N0UmVsYXRpdmVQYXJlbnRJbml0aWFsU2Nyb2xsID0gW10sXG4gIC8vIChsZWZ0LCB0b3ApXG5cbiAgX3NpbGVudCA9IGZhbHNlLFxuICBzYXZlZElucHV0Q2hlY2tlZCA9IFtdO1xuXG4vKiogQGNvbnN0ICovXG52YXIgZG9jdW1lbnRFeGlzdHMgPSB0eXBlb2YgZG9jdW1lbnQgIT09ICd1bmRlZmluZWQnLFxuICBQb3NpdGlvbkdob3N0QWJzb2x1dGVseSA9IElPUyxcbiAgQ1NTRmxvYXRQcm9wZXJ0eSA9IEVkZ2UgfHwgSUUxMU9yTGVzcyA/ICdjc3NGbG9hdCcgOiAnZmxvYXQnLFxuICAvLyBUaGlzIHdpbGwgbm90IHBhc3MgZm9yIElFOSwgYmVjYXVzZSBJRTkgRG5EIG9ubHkgd29ya3Mgb24gYW5jaG9yc1xuICBzdXBwb3J0RHJhZ2dhYmxlID0gZG9jdW1lbnRFeGlzdHMgJiYgIUNocm9tZUZvckFuZHJvaWQgJiYgIUlPUyAmJiAnZHJhZ2dhYmxlJyBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKSxcbiAgc3VwcG9ydENzc1BvaW50ZXJFdmVudHMgPSBmdW5jdGlvbiAoKSB7XG4gICAgaWYgKCFkb2N1bWVudEV4aXN0cykgcmV0dXJuO1xuICAgIC8vIGZhbHNlIHdoZW4gPD0gSUUxMVxuICAgIGlmIChJRTExT3JMZXNzKSB7XG4gICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuICAgIHZhciBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3gnKTtcbiAgICBlbC5zdHlsZS5jc3NUZXh0ID0gJ3BvaW50ZXItZXZlbnRzOmF1dG8nO1xuICAgIHJldHVybiBlbC5zdHlsZS5wb2ludGVyRXZlbnRzID09PSAnYXV0byc7XG4gIH0oKSxcbiAgX2RldGVjdERpcmVjdGlvbiA9IGZ1bmN0aW9uIF9kZXRlY3REaXJlY3Rpb24oZWwsIG9wdGlvbnMpIHtcbiAgICB2YXIgZWxDU1MgPSBjc3MoZWwpLFxuICAgICAgZWxXaWR0aCA9IHBhcnNlSW50KGVsQ1NTLndpZHRoKSAtIHBhcnNlSW50KGVsQ1NTLnBhZGRpbmdMZWZ0KSAtIHBhcnNlSW50KGVsQ1NTLnBhZGRpbmdSaWdodCkgLSBwYXJzZUludChlbENTUy5ib3JkZXJMZWZ0V2lkdGgpIC0gcGFyc2VJbnQoZWxDU1MuYm9yZGVyUmlnaHRXaWR0aCksXG4gICAgICBjaGlsZDEgPSBnZXRDaGlsZChlbCwgMCwgb3B0aW9ucyksXG4gICAgICBjaGlsZDIgPSBnZXRDaGlsZChlbCwgMSwgb3B0aW9ucyksXG4gICAgICBmaXJzdENoaWxkQ1NTID0gY2hpbGQxICYmIGNzcyhjaGlsZDEpLFxuICAgICAgc2Vjb25kQ2hpbGRDU1MgPSBjaGlsZDIgJiYgY3NzKGNoaWxkMiksXG4gICAgICBmaXJzdENoaWxkV2lkdGggPSBmaXJzdENoaWxkQ1NTICYmIHBhcnNlSW50KGZpcnN0Q2hpbGRDU1MubWFyZ2luTGVmdCkgKyBwYXJzZUludChmaXJzdENoaWxkQ1NTLm1hcmdpblJpZ2h0KSArIGdldFJlY3QoY2hpbGQxKS53aWR0aCxcbiAgICAgIHNlY29uZENoaWxkV2lkdGggPSBzZWNvbmRDaGlsZENTUyAmJiBwYXJzZUludChzZWNvbmRDaGlsZENTUy5tYXJnaW5MZWZ0KSArIHBhcnNlSW50KHNlY29uZENoaWxkQ1NTLm1hcmdpblJpZ2h0KSArIGdldFJlY3QoY2hpbGQyKS53aWR0aDtcbiAgICBpZiAoZWxDU1MuZGlzcGxheSA9PT0gJ2ZsZXgnKSB7XG4gICAgICByZXR1cm4gZWxDU1MuZmxleERpcmVjdGlvbiA9PT0gJ2NvbHVtbicgfHwgZWxDU1MuZmxleERpcmVjdGlvbiA9PT0gJ2NvbHVtbi1yZXZlcnNlJyA/ICd2ZXJ0aWNhbCcgOiAnaG9yaXpvbnRhbCc7XG4gICAgfVxuICAgIGlmIChlbENTUy5kaXNwbGF5ID09PSAnZ3JpZCcpIHtcbiAgICAgIHJldHVybiBlbENTUy5ncmlkVGVtcGxhdGVDb2x1bW5zLnNwbGl0KCcgJykubGVuZ3RoIDw9IDEgPyAndmVydGljYWwnIDogJ2hvcml6b250YWwnO1xuICAgIH1cbiAgICBpZiAoY2hpbGQxICYmIGZpcnN0Q2hpbGRDU1NbXCJmbG9hdFwiXSAmJiBmaXJzdENoaWxkQ1NTW1wiZmxvYXRcIl0gIT09ICdub25lJykge1xuICAgICAgdmFyIHRvdWNoaW5nU2lkZUNoaWxkMiA9IGZpcnN0Q2hpbGRDU1NbXCJmbG9hdFwiXSA9PT0gJ2xlZnQnID8gJ2xlZnQnIDogJ3JpZ2h0JztcbiAgICAgIHJldHVybiBjaGlsZDIgJiYgKHNlY29uZENoaWxkQ1NTLmNsZWFyID09PSAnYm90aCcgfHwgc2Vjb25kQ2hpbGRDU1MuY2xlYXIgPT09IHRvdWNoaW5nU2lkZUNoaWxkMikgPyAndmVydGljYWwnIDogJ2hvcml6b250YWwnO1xuICAgIH1cbiAgICByZXR1cm4gY2hpbGQxICYmIChmaXJzdENoaWxkQ1NTLmRpc3BsYXkgPT09ICdibG9jaycgfHwgZmlyc3RDaGlsZENTUy5kaXNwbGF5ID09PSAnZmxleCcgfHwgZmlyc3RDaGlsZENTUy5kaXNwbGF5ID09PSAndGFibGUnIHx8IGZpcnN0Q2hpbGRDU1MuZGlzcGxheSA9PT0gJ2dyaWQnIHx8IGZpcnN0Q2hpbGRXaWR0aCA+PSBlbFdpZHRoICYmIGVsQ1NTW0NTU0Zsb2F0UHJvcGVydHldID09PSAnbm9uZScgfHwgY2hpbGQyICYmIGVsQ1NTW0NTU0Zsb2F0UHJvcGVydHldID09PSAnbm9uZScgJiYgZmlyc3RDaGlsZFdpZHRoICsgc2Vjb25kQ2hpbGRXaWR0aCA+IGVsV2lkdGgpID8gJ3ZlcnRpY2FsJyA6ICdob3Jpem9udGFsJztcbiAgfSxcbiAgX2RyYWdFbEluUm93Q29sdW1uID0gZnVuY3Rpb24gX2RyYWdFbEluUm93Q29sdW1uKGRyYWdSZWN0LCB0YXJnZXRSZWN0LCB2ZXJ0aWNhbCkge1xuICAgIHZhciBkcmFnRWxTMU9wcCA9IHZlcnRpY2FsID8gZHJhZ1JlY3QubGVmdCA6IGRyYWdSZWN0LnRvcCxcbiAgICAgIGRyYWdFbFMyT3BwID0gdmVydGljYWwgPyBkcmFnUmVjdC5yaWdodCA6IGRyYWdSZWN0LmJvdHRvbSxcbiAgICAgIGRyYWdFbE9wcExlbmd0aCA9IHZlcnRpY2FsID8gZHJhZ1JlY3Qud2lkdGggOiBkcmFnUmVjdC5oZWlnaHQsXG4gICAgICB0YXJnZXRTMU9wcCA9IHZlcnRpY2FsID8gdGFyZ2V0UmVjdC5sZWZ0IDogdGFyZ2V0UmVjdC50b3AsXG4gICAgICB0YXJnZXRTMk9wcCA9IHZlcnRpY2FsID8gdGFyZ2V0UmVjdC5yaWdodCA6IHRhcmdldFJlY3QuYm90dG9tLFxuICAgICAgdGFyZ2V0T3BwTGVuZ3RoID0gdmVydGljYWwgPyB0YXJnZXRSZWN0LndpZHRoIDogdGFyZ2V0UmVjdC5oZWlnaHQ7XG4gICAgcmV0dXJuIGRyYWdFbFMxT3BwID09PSB0YXJnZXRTMU9wcCB8fCBkcmFnRWxTMk9wcCA9PT0gdGFyZ2V0UzJPcHAgfHwgZHJhZ0VsUzFPcHAgKyBkcmFnRWxPcHBMZW5ndGggLyAyID09PSB0YXJnZXRTMU9wcCArIHRhcmdldE9wcExlbmd0aCAvIDI7XG4gIH0sXG4gIC8qKlxyXG4gICAqIERldGVjdHMgZmlyc3QgbmVhcmVzdCBlbXB0eSBzb3J0YWJsZSB0byBYIGFuZCBZIHBvc2l0aW9uIHVzaW5nIGVtcHR5SW5zZXJ0VGhyZXNob2xkLlxyXG4gICAqIEBwYXJhbSAge051bWJlcn0geCAgICAgIFggcG9zaXRpb25cclxuICAgKiBAcGFyYW0gIHtOdW1iZXJ9IHkgICAgICBZIHBvc2l0aW9uXHJcbiAgICogQHJldHVybiB7SFRNTEVsZW1lbnR9ICAgRWxlbWVudCBvZiB0aGUgZmlyc3QgZm91bmQgbmVhcmVzdCBTb3J0YWJsZVxyXG4gICAqL1xuICBfZGV0ZWN0TmVhcmVzdEVtcHR5U29ydGFibGUgPSBmdW5jdGlvbiBfZGV0ZWN0TmVhcmVzdEVtcHR5U29ydGFibGUoeCwgeSkge1xuICAgIHZhciByZXQ7XG4gICAgc29ydGFibGVzLnNvbWUoZnVuY3Rpb24gKHNvcnRhYmxlKSB7XG4gICAgICB2YXIgdGhyZXNob2xkID0gc29ydGFibGVbZXhwYW5kb10ub3B0aW9ucy5lbXB0eUluc2VydFRocmVzaG9sZDtcbiAgICAgIGlmICghdGhyZXNob2xkIHx8IGxhc3RDaGlsZChzb3J0YWJsZSkpIHJldHVybjtcbiAgICAgIHZhciByZWN0ID0gZ2V0UmVjdChzb3J0YWJsZSksXG4gICAgICAgIGluc2lkZUhvcml6b250YWxseSA9IHggPj0gcmVjdC5sZWZ0IC0gdGhyZXNob2xkICYmIHggPD0gcmVjdC5yaWdodCArIHRocmVzaG9sZCxcbiAgICAgICAgaW5zaWRlVmVydGljYWxseSA9IHkgPj0gcmVjdC50b3AgLSB0aHJlc2hvbGQgJiYgeSA8PSByZWN0LmJvdHRvbSArIHRocmVzaG9sZDtcbiAgICAgIGlmIChpbnNpZGVIb3Jpem9udGFsbHkgJiYgaW5zaWRlVmVydGljYWxseSkge1xuICAgICAgICByZXR1cm4gcmV0ID0gc29ydGFibGU7XG4gICAgICB9XG4gICAgfSk7XG4gICAgcmV0dXJuIHJldDtcbiAgfSxcbiAgX3ByZXBhcmVHcm91cCA9IGZ1bmN0aW9uIF9wcmVwYXJlR3JvdXAob3B0aW9ucykge1xuICAgIGZ1bmN0aW9uIHRvRm4odmFsdWUsIHB1bGwpIHtcbiAgICAgIHJldHVybiBmdW5jdGlvbiAodG8sIGZyb20sIGRyYWdFbCwgZXZ0KSB7XG4gICAgICAgIHZhciBzYW1lR3JvdXAgPSB0by5vcHRpb25zLmdyb3VwLm5hbWUgJiYgZnJvbS5vcHRpb25zLmdyb3VwLm5hbWUgJiYgdG8ub3B0aW9ucy5ncm91cC5uYW1lID09PSBmcm9tLm9wdGlvbnMuZ3JvdXAubmFtZTtcbiAgICAgICAgaWYgKHZhbHVlID09IG51bGwgJiYgKHB1bGwgfHwgc2FtZUdyb3VwKSkge1xuICAgICAgICAgIC8vIERlZmF1bHQgcHVsbCB2YWx1ZVxuICAgICAgICAgIC8vIERlZmF1bHQgcHVsbCBhbmQgcHV0IHZhbHVlIGlmIHNhbWUgZ3JvdXBcbiAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfSBlbHNlIGlmICh2YWx1ZSA9PSBudWxsIHx8IHZhbHVlID09PSBmYWxzZSkge1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfSBlbHNlIGlmIChwdWxsICYmIHZhbHVlID09PSAnY2xvbmUnKSB7XG4gICAgICAgICAgcmV0dXJuIHZhbHVlO1xuICAgICAgICB9IGVsc2UgaWYgKHR5cGVvZiB2YWx1ZSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgIHJldHVybiB0b0ZuKHZhbHVlKHRvLCBmcm9tLCBkcmFnRWwsIGV2dCksIHB1bGwpKHRvLCBmcm9tLCBkcmFnRWwsIGV2dCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgdmFyIG90aGVyR3JvdXAgPSAocHVsbCA/IHRvIDogZnJvbSkub3B0aW9ucy5ncm91cC5uYW1lO1xuICAgICAgICAgIHJldHVybiB2YWx1ZSA9PT0gdHJ1ZSB8fCB0eXBlb2YgdmFsdWUgPT09ICdzdHJpbmcnICYmIHZhbHVlID09PSBvdGhlckdyb3VwIHx8IHZhbHVlLmpvaW4gJiYgdmFsdWUuaW5kZXhPZihvdGhlckdyb3VwKSA+IC0xO1xuICAgICAgICB9XG4gICAgICB9O1xuICAgIH1cbiAgICB2YXIgZ3JvdXAgPSB7fTtcbiAgICB2YXIgb3JpZ2luYWxHcm91cCA9IG9wdGlvbnMuZ3JvdXA7XG4gICAgaWYgKCFvcmlnaW5hbEdyb3VwIHx8IF90eXBlb2Yob3JpZ2luYWxHcm91cCkgIT0gJ29iamVjdCcpIHtcbiAgICAgIG9yaWdpbmFsR3JvdXAgPSB7XG4gICAgICAgIG5hbWU6IG9yaWdpbmFsR3JvdXBcbiAgICAgIH07XG4gICAgfVxuICAgIGdyb3VwLm5hbWUgPSBvcmlnaW5hbEdyb3VwLm5hbWU7XG4gICAgZ3JvdXAuY2hlY2tQdWxsID0gdG9GbihvcmlnaW5hbEdyb3VwLnB1bGwsIHRydWUpO1xuICAgIGdyb3VwLmNoZWNrUHV0ID0gdG9GbihvcmlnaW5hbEdyb3VwLnB1dCk7XG4gICAgZ3JvdXAucmV2ZXJ0Q2xvbmUgPSBvcmlnaW5hbEdyb3VwLnJldmVydENsb25lO1xuICAgIG9wdGlvbnMuZ3JvdXAgPSBncm91cDtcbiAgfSxcbiAgX2hpZGVHaG9zdEZvclRhcmdldCA9IGZ1bmN0aW9uIF9oaWRlR2hvc3RGb3JUYXJnZXQoKSB7XG4gICAgaWYgKCFzdXBwb3J0Q3NzUG9pbnRlckV2ZW50cyAmJiBnaG9zdEVsKSB7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ2Rpc3BsYXknLCAnbm9uZScpO1xuICAgIH1cbiAgfSxcbiAgX3VuaGlkZUdob3N0Rm9yVGFyZ2V0ID0gZnVuY3Rpb24gX3VuaGlkZUdob3N0Rm9yVGFyZ2V0KCkge1xuICAgIGlmICghc3VwcG9ydENzc1BvaW50ZXJFdmVudHMgJiYgZ2hvc3RFbCkge1xuICAgICAgY3NzKGdob3N0RWwsICdkaXNwbGF5JywgJycpO1xuICAgIH1cbiAgfTtcblxuLy8gIzExODQgZml4IC0gUHJldmVudCBjbGljayBldmVudCBvbiBmYWxsYmFjayBpZiBkcmFnZ2VkIGJ1dCBpdGVtIG5vdCBjaGFuZ2VkIHBvc2l0aW9uXG5pZiAoZG9jdW1lbnRFeGlzdHMgJiYgIUNocm9tZUZvckFuZHJvaWQpIHtcbiAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoZXZ0KSB7XG4gICAgaWYgKGlnbm9yZU5leHRDbGljaykge1xuICAgICAgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICBldnQuc3RvcFByb3BhZ2F0aW9uICYmIGV2dC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgIGV2dC5zdG9wSW1tZWRpYXRlUHJvcGFnYXRpb24gJiYgZXZ0LnN0b3BJbW1lZGlhdGVQcm9wYWdhdGlvbigpO1xuICAgICAgaWdub3JlTmV4dENsaWNrID0gZmFsc2U7XG4gICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuICB9LCB0cnVlKTtcbn1cbnZhciBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudCA9IGZ1bmN0aW9uIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KGV2dCkge1xuICBpZiAoZHJhZ0VsKSB7XG4gICAgZXZ0ID0gZXZ0LnRvdWNoZXMgPyBldnQudG91Y2hlc1swXSA6IGV2dDtcbiAgICB2YXIgbmVhcmVzdCA9IF9kZXRlY3ROZWFyZXN0RW1wdHlTb3J0YWJsZShldnQuY2xpZW50WCwgZXZ0LmNsaWVudFkpO1xuICAgIGlmIChuZWFyZXN0KSB7XG4gICAgICAvLyBDcmVhdGUgaW1pdGF0aW9uIGV2ZW50XG4gICAgICB2YXIgZXZlbnQgPSB7fTtcbiAgICAgIGZvciAodmFyIGkgaW4gZXZ0KSB7XG4gICAgICAgIGlmIChldnQuaGFzT3duUHJvcGVydHkoaSkpIHtcbiAgICAgICAgICBldmVudFtpXSA9IGV2dFtpXTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgZXZlbnQudGFyZ2V0ID0gZXZlbnQucm9vdEVsID0gbmVhcmVzdDtcbiAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0ID0gdm9pZCAwO1xuICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uID0gdm9pZCAwO1xuICAgICAgbmVhcmVzdFtleHBhbmRvXS5fb25EcmFnT3ZlcihldmVudCk7XG4gICAgfVxuICB9XG59O1xudmFyIF9jaGVja091dHNpZGVUYXJnZXRFbCA9IGZ1bmN0aW9uIF9jaGVja091dHNpZGVUYXJnZXRFbChldnQpIHtcbiAgaWYgKGRyYWdFbCkge1xuICAgIGRyYWdFbC5wYXJlbnROb2RlW2V4cGFuZG9dLl9pc091dHNpZGVUaGlzRWwoZXZ0LnRhcmdldCk7XG4gIH1cbn07XG5cbi8qKlxyXG4gKiBAY2xhc3MgIFNvcnRhYmxlXHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSAgZWxcclxuICogQHBhcmFtICB7T2JqZWN0fSAgICAgICBbb3B0aW9uc11cclxuICovXG5mdW5jdGlvbiBTb3J0YWJsZShlbCwgb3B0aW9ucykge1xuICBpZiAoIShlbCAmJiBlbC5ub2RlVHlwZSAmJiBlbC5ub2RlVHlwZSA9PT0gMSkpIHtcbiAgICB0aHJvdyBcIlNvcnRhYmxlOiBgZWxgIG11c3QgYmUgYW4gSFRNTEVsZW1lbnQsIG5vdCBcIi5jb25jYXQoe30udG9TdHJpbmcuY2FsbChlbCkpO1xuICB9XG4gIHRoaXMuZWwgPSBlbDsgLy8gcm9vdCBlbGVtZW50XG4gIHRoaXMub3B0aW9ucyA9IG9wdGlvbnMgPSBfZXh0ZW5kcyh7fSwgb3B0aW9ucyk7XG5cbiAgLy8gRXhwb3J0IGluc3RhbmNlXG4gIGVsW2V4cGFuZG9dID0gdGhpcztcbiAgdmFyIGRlZmF1bHRzID0ge1xuICAgIGdyb3VwOiBudWxsLFxuICAgIHNvcnQ6IHRydWUsXG4gICAgZGlzYWJsZWQ6IGZhbHNlLFxuICAgIHN0b3JlOiBudWxsLFxuICAgIGhhbmRsZTogbnVsbCxcbiAgICBkcmFnZ2FibGU6IC9eW3VvXWwkL2kudGVzdChlbC5ub2RlTmFtZSkgPyAnPmxpJyA6ICc+KicsXG4gICAgc3dhcFRocmVzaG9sZDogMSxcbiAgICAvLyBwZXJjZW50YWdlOyAwIDw9IHggPD0gMVxuICAgIGludmVydFN3YXA6IGZhbHNlLFxuICAgIC8vIGludmVydCBhbHdheXNcbiAgICBpbnZlcnRlZFN3YXBUaHJlc2hvbGQ6IG51bGwsXG4gICAgLy8gd2lsbCBiZSBzZXQgdG8gc2FtZSBhcyBzd2FwVGhyZXNob2xkIGlmIGRlZmF1bHRcbiAgICByZW1vdmVDbG9uZU9uSGlkZTogdHJ1ZSxcbiAgICBkaXJlY3Rpb246IGZ1bmN0aW9uIGRpcmVjdGlvbigpIHtcbiAgICAgIHJldHVybiBfZGV0ZWN0RGlyZWN0aW9uKGVsLCB0aGlzLm9wdGlvbnMpO1xuICAgIH0sXG4gICAgZ2hvc3RDbGFzczogJ3NvcnRhYmxlLWdob3N0JyxcbiAgICBjaG9zZW5DbGFzczogJ3NvcnRhYmxlLWNob3NlbicsXG4gICAgZHJhZ0NsYXNzOiAnc29ydGFibGUtZHJhZycsXG4gICAgaWdub3JlOiAnYSwgaW1nJyxcbiAgICBmaWx0ZXI6IG51bGwsXG4gICAgcHJldmVudE9uRmlsdGVyOiB0cnVlLFxuICAgIGFuaW1hdGlvbjogMCxcbiAgICBlYXNpbmc6IG51bGwsXG4gICAgc2V0RGF0YTogZnVuY3Rpb24gc2V0RGF0YShkYXRhVHJhbnNmZXIsIGRyYWdFbCkge1xuICAgICAgZGF0YVRyYW5zZmVyLnNldERhdGEoJ1RleHQnLCBkcmFnRWwudGV4dENvbnRlbnQpO1xuICAgIH0sXG4gICAgZHJvcEJ1YmJsZTogZmFsc2UsXG4gICAgZHJhZ292ZXJCdWJibGU6IGZhbHNlLFxuICAgIGRhdGFJZEF0dHI6ICdkYXRhLWlkJyxcbiAgICBkZWxheTogMCxcbiAgICBkZWxheU9uVG91Y2hPbmx5OiBmYWxzZSxcbiAgICB0b3VjaFN0YXJ0VGhyZXNob2xkOiAoTnVtYmVyLnBhcnNlSW50ID8gTnVtYmVyIDogd2luZG93KS5wYXJzZUludCh3aW5kb3cuZGV2aWNlUGl4ZWxSYXRpbywgMTApIHx8IDEsXG4gICAgZm9yY2VGYWxsYmFjazogZmFsc2UsXG4gICAgZmFsbGJhY2tDbGFzczogJ3NvcnRhYmxlLWZhbGxiYWNrJyxcbiAgICBmYWxsYmFja09uQm9keTogZmFsc2UsXG4gICAgZmFsbGJhY2tUb2xlcmFuY2U6IDAsXG4gICAgZmFsbGJhY2tPZmZzZXQ6IHtcbiAgICAgIHg6IDAsXG4gICAgICB5OiAwXG4gICAgfSxcbiAgICBzdXBwb3J0UG9pbnRlcjogU29ydGFibGUuc3VwcG9ydFBvaW50ZXIgIT09IGZhbHNlICYmICdQb2ludGVyRXZlbnQnIGluIHdpbmRvdyAmJiAhU2FmYXJpLFxuICAgIGVtcHR5SW5zZXJ0VGhyZXNob2xkOiA1XG4gIH07XG4gIFBsdWdpbk1hbmFnZXIuaW5pdGlhbGl6ZVBsdWdpbnModGhpcywgZWwsIGRlZmF1bHRzKTtcblxuICAvLyBTZXQgZGVmYXVsdCBvcHRpb25zXG4gIGZvciAodmFyIG5hbWUgaW4gZGVmYXVsdHMpIHtcbiAgICAhKG5hbWUgaW4gb3B0aW9ucykgJiYgKG9wdGlvbnNbbmFtZV0gPSBkZWZhdWx0c1tuYW1lXSk7XG4gIH1cbiAgX3ByZXBhcmVHcm91cChvcHRpb25zKTtcblxuICAvLyBCaW5kIGFsbCBwcml2YXRlIG1ldGhvZHNcbiAgZm9yICh2YXIgZm4gaW4gdGhpcykge1xuICAgIGlmIChmbi5jaGFyQXQoMCkgPT09ICdfJyAmJiB0eXBlb2YgdGhpc1tmbl0gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgIHRoaXNbZm5dID0gdGhpc1tmbl0uYmluZCh0aGlzKTtcbiAgICB9XG4gIH1cblxuICAvLyBTZXR1cCBkcmFnIG1vZGVcbiAgdGhpcy5uYXRpdmVEcmFnZ2FibGUgPSBvcHRpb25zLmZvcmNlRmFsbGJhY2sgPyBmYWxzZSA6IHN1cHBvcnREcmFnZ2FibGU7XG4gIGlmICh0aGlzLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgIC8vIFRvdWNoIHN0YXJ0IHRocmVzaG9sZCBjYW5ub3QgYmUgZ3JlYXRlciB0aGFuIHRoZSBuYXRpdmUgZHJhZ3N0YXJ0IHRocmVzaG9sZFxuICAgIHRoaXMub3B0aW9ucy50b3VjaFN0YXJ0VGhyZXNob2xkID0gMTtcbiAgfVxuXG4gIC8vIEJpbmQgZXZlbnRzXG4gIGlmIChvcHRpb25zLnN1cHBvcnRQb2ludGVyKSB7XG4gICAgb24oZWwsICdwb2ludGVyZG93bicsIHRoaXMuX29uVGFwU3RhcnQpO1xuICB9IGVsc2Uge1xuICAgIG9uKGVsLCAnbW91c2Vkb3duJywgdGhpcy5fb25UYXBTdGFydCk7XG4gICAgb24oZWwsICd0b3VjaHN0YXJ0JywgdGhpcy5fb25UYXBTdGFydCk7XG4gIH1cbiAgaWYgKHRoaXMubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgb24oZWwsICdkcmFnb3ZlcicsIHRoaXMpO1xuICAgIG9uKGVsLCAnZHJhZ2VudGVyJywgdGhpcyk7XG4gIH1cbiAgc29ydGFibGVzLnB1c2godGhpcy5lbCk7XG5cbiAgLy8gUmVzdG9yZSBzb3J0aW5nXG4gIG9wdGlvbnMuc3RvcmUgJiYgb3B0aW9ucy5zdG9yZS5nZXQgJiYgdGhpcy5zb3J0KG9wdGlvbnMuc3RvcmUuZ2V0KHRoaXMpIHx8IFtdKTtcblxuICAvLyBBZGQgYW5pbWF0aW9uIHN0YXRlIG1hbmFnZXJcbiAgX2V4dGVuZHModGhpcywgQW5pbWF0aW9uU3RhdGVNYW5hZ2VyKCkpO1xufVxuU29ydGFibGUucHJvdG90eXBlID0gLyoqIEBsZW5kcyBTb3J0YWJsZS5wcm90b3R5cGUgKi97XG4gIGNvbnN0cnVjdG9yOiBTb3J0YWJsZSxcbiAgX2lzT3V0c2lkZVRoaXNFbDogZnVuY3Rpb24gX2lzT3V0c2lkZVRoaXNFbCh0YXJnZXQpIHtcbiAgICBpZiAoIXRoaXMuZWwuY29udGFpbnModGFyZ2V0KSAmJiB0YXJnZXQgIT09IHRoaXMuZWwpIHtcbiAgICAgIGxhc3RUYXJnZXQgPSBudWxsO1xuICAgIH1cbiAgfSxcbiAgX2dldERpcmVjdGlvbjogZnVuY3Rpb24gX2dldERpcmVjdGlvbihldnQsIHRhcmdldCkge1xuICAgIHJldHVybiB0eXBlb2YgdGhpcy5vcHRpb25zLmRpcmVjdGlvbiA9PT0gJ2Z1bmN0aW9uJyA/IHRoaXMub3B0aW9ucy5kaXJlY3Rpb24uY2FsbCh0aGlzLCBldnQsIHRhcmdldCwgZHJhZ0VsKSA6IHRoaXMub3B0aW9ucy5kaXJlY3Rpb247XG4gIH0sXG4gIF9vblRhcFN0YXJ0OiBmdW5jdGlvbiBfb25UYXBTdGFydCggLyoqIEV2ZW50fFRvdWNoRXZlbnQgKi9ldnQpIHtcbiAgICBpZiAoIWV2dC5jYW5jZWxhYmxlKSByZXR1cm47XG4gICAgdmFyIF90aGlzID0gdGhpcyxcbiAgICAgIGVsID0gdGhpcy5lbCxcbiAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnMsXG4gICAgICBwcmV2ZW50T25GaWx0ZXIgPSBvcHRpb25zLnByZXZlbnRPbkZpbHRlcixcbiAgICAgIHR5cGUgPSBldnQudHlwZSxcbiAgICAgIHRvdWNoID0gZXZ0LnRvdWNoZXMgJiYgZXZ0LnRvdWNoZXNbMF0gfHwgZXZ0LnBvaW50ZXJUeXBlICYmIGV2dC5wb2ludGVyVHlwZSA9PT0gJ3RvdWNoJyAmJiBldnQsXG4gICAgICB0YXJnZXQgPSAodG91Y2ggfHwgZXZ0KS50YXJnZXQsXG4gICAgICBvcmlnaW5hbFRhcmdldCA9IGV2dC50YXJnZXQuc2hhZG93Um9vdCAmJiAoZXZ0LnBhdGggJiYgZXZ0LnBhdGhbMF0gfHwgZXZ0LmNvbXBvc2VkUGF0aCAmJiBldnQuY29tcG9zZWRQYXRoKClbMF0pIHx8IHRhcmdldCxcbiAgICAgIGZpbHRlciA9IG9wdGlvbnMuZmlsdGVyO1xuICAgIF9zYXZlSW5wdXRDaGVja2VkU3RhdGUoZWwpO1xuXG4gICAgLy8gRG9uJ3QgdHJpZ2dlciBzdGFydCBldmVudCB3aGVuIGFuIGVsZW1lbnQgaXMgYmVlbiBkcmFnZ2VkLCBvdGhlcndpc2UgdGhlIGV2dC5vbGRpbmRleCBhbHdheXMgd3Jvbmcgd2hlbiBzZXQgb3B0aW9uLmdyb3VwLlxuICAgIGlmIChkcmFnRWwpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgaWYgKC9tb3VzZWRvd258cG9pbnRlcmRvd24vLnRlc3QodHlwZSkgJiYgZXZ0LmJ1dHRvbiAhPT0gMCB8fCBvcHRpb25zLmRpc2FibGVkKSB7XG4gICAgICByZXR1cm47IC8vIG9ubHkgbGVmdCBidXR0b24gYW5kIGVuYWJsZWRcbiAgICB9XG5cbiAgICAvLyBjYW5jZWwgZG5kIGlmIG9yaWdpbmFsIHRhcmdldCBpcyBjb250ZW50IGVkaXRhYmxlXG4gICAgaWYgKG9yaWdpbmFsVGFyZ2V0LmlzQ29udGVudEVkaXRhYmxlKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgLy8gU2FmYXJpIGlnbm9yZXMgZnVydGhlciBldmVudCBoYW5kbGluZyBhZnRlciBtb3VzZWRvd25cbiAgICBpZiAoIXRoaXMubmF0aXZlRHJhZ2dhYmxlICYmIFNhZmFyaSAmJiB0YXJnZXQgJiYgdGFyZ2V0LnRhZ05hbWUudG9VcHBlckNhc2UoKSA9PT0gJ1NFTEVDVCcpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgdGFyZ2V0ID0gY2xvc2VzdCh0YXJnZXQsIG9wdGlvbnMuZHJhZ2dhYmxlLCBlbCwgZmFsc2UpO1xuICAgIGlmICh0YXJnZXQgJiYgdGFyZ2V0LmFuaW1hdGVkKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIGlmIChsYXN0RG93bkVsID09PSB0YXJnZXQpIHtcbiAgICAgIC8vIElnbm9yaW5nIGR1cGxpY2F0ZSBgZG93bmBcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICAvLyBHZXQgdGhlIGluZGV4IG9mIHRoZSBkcmFnZ2VkIGVsZW1lbnQgd2l0aGluIGl0cyBwYXJlbnRcbiAgICBvbGRJbmRleCA9IGluZGV4KHRhcmdldCk7XG4gICAgb2xkRHJhZ2dhYmxlSW5kZXggPSBpbmRleCh0YXJnZXQsIG9wdGlvbnMuZHJhZ2dhYmxlKTtcblxuICAgIC8vIENoZWNrIGZpbHRlclxuICAgIGlmICh0eXBlb2YgZmlsdGVyID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICBpZiAoZmlsdGVyLmNhbGwodGhpcywgZXZ0LCB0YXJnZXQsIHRoaXMpKSB7XG4gICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICBzb3J0YWJsZTogX3RoaXMsXG4gICAgICAgICAgcm9vdEVsOiBvcmlnaW5hbFRhcmdldCxcbiAgICAgICAgICBuYW1lOiAnZmlsdGVyJyxcbiAgICAgICAgICB0YXJnZXRFbDogdGFyZ2V0LFxuICAgICAgICAgIHRvRWw6IGVsLFxuICAgICAgICAgIGZyb21FbDogZWxcbiAgICAgICAgfSk7XG4gICAgICAgIHBsdWdpbkV2ZW50KCdmaWx0ZXInLCBfdGhpcywge1xuICAgICAgICAgIGV2dDogZXZ0XG4gICAgICAgIH0pO1xuICAgICAgICBwcmV2ZW50T25GaWx0ZXIgJiYgZXZ0LmNhbmNlbGFibGUgJiYgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHJldHVybjsgLy8gY2FuY2VsIGRuZFxuICAgICAgfVxuICAgIH0gZWxzZSBpZiAoZmlsdGVyKSB7XG4gICAgICBmaWx0ZXIgPSBmaWx0ZXIuc3BsaXQoJywnKS5zb21lKGZ1bmN0aW9uIChjcml0ZXJpYSkge1xuICAgICAgICBjcml0ZXJpYSA9IGNsb3Nlc3Qob3JpZ2luYWxUYXJnZXQsIGNyaXRlcmlhLnRyaW0oKSwgZWwsIGZhbHNlKTtcbiAgICAgICAgaWYgKGNyaXRlcmlhKSB7XG4gICAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgc29ydGFibGU6IF90aGlzLFxuICAgICAgICAgICAgcm9vdEVsOiBjcml0ZXJpYSxcbiAgICAgICAgICAgIG5hbWU6ICdmaWx0ZXInLFxuICAgICAgICAgICAgdGFyZ2V0RWw6IHRhcmdldCxcbiAgICAgICAgICAgIGZyb21FbDogZWwsXG4gICAgICAgICAgICB0b0VsOiBlbFxuICAgICAgICAgIH0pO1xuICAgICAgICAgIHBsdWdpbkV2ZW50KCdmaWx0ZXInLCBfdGhpcywge1xuICAgICAgICAgICAgZXZ0OiBldnRcbiAgICAgICAgICB9KTtcbiAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgICBpZiAoZmlsdGVyKSB7XG4gICAgICAgIHByZXZlbnRPbkZpbHRlciAmJiBldnQuY2FuY2VsYWJsZSAmJiBldnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgcmV0dXJuOyAvLyBjYW5jZWwgZG5kXG4gICAgICB9XG4gICAgfVxuICAgIGlmIChvcHRpb25zLmhhbmRsZSAmJiAhY2xvc2VzdChvcmlnaW5hbFRhcmdldCwgb3B0aW9ucy5oYW5kbGUsIGVsLCBmYWxzZSkpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICAvLyBQcmVwYXJlIGBkcmFnc3RhcnRgXG4gICAgdGhpcy5fcHJlcGFyZURyYWdTdGFydChldnQsIHRvdWNoLCB0YXJnZXQpO1xuICB9LFxuICBfcHJlcGFyZURyYWdTdGFydDogZnVuY3Rpb24gX3ByZXBhcmVEcmFnU3RhcnQoIC8qKiBFdmVudCAqL2V2dCwgLyoqIFRvdWNoICovdG91Y2gsIC8qKiBIVE1MRWxlbWVudCAqL3RhcmdldCkge1xuICAgIHZhciBfdGhpcyA9IHRoaXMsXG4gICAgICBlbCA9IF90aGlzLmVsLFxuICAgICAgb3B0aW9ucyA9IF90aGlzLm9wdGlvbnMsXG4gICAgICBvd25lckRvY3VtZW50ID0gZWwub3duZXJEb2N1bWVudCxcbiAgICAgIGRyYWdTdGFydEZuO1xuICAgIGlmICh0YXJnZXQgJiYgIWRyYWdFbCAmJiB0YXJnZXQucGFyZW50Tm9kZSA9PT0gZWwpIHtcbiAgICAgIHZhciBkcmFnUmVjdCA9IGdldFJlY3QodGFyZ2V0KTtcbiAgICAgIHJvb3RFbCA9IGVsO1xuICAgICAgZHJhZ0VsID0gdGFyZ2V0O1xuICAgICAgcGFyZW50RWwgPSBkcmFnRWwucGFyZW50Tm9kZTtcbiAgICAgIG5leHRFbCA9IGRyYWdFbC5uZXh0U2libGluZztcbiAgICAgIGxhc3REb3duRWwgPSB0YXJnZXQ7XG4gICAgICBhY3RpdmVHcm91cCA9IG9wdGlvbnMuZ3JvdXA7XG4gICAgICBTb3J0YWJsZS5kcmFnZ2VkID0gZHJhZ0VsO1xuICAgICAgdGFwRXZ0ID0ge1xuICAgICAgICB0YXJnZXQ6IGRyYWdFbCxcbiAgICAgICAgY2xpZW50WDogKHRvdWNoIHx8IGV2dCkuY2xpZW50WCxcbiAgICAgICAgY2xpZW50WTogKHRvdWNoIHx8IGV2dCkuY2xpZW50WVxuICAgICAgfTtcbiAgICAgIHRhcERpc3RhbmNlTGVmdCA9IHRhcEV2dC5jbGllbnRYIC0gZHJhZ1JlY3QubGVmdDtcbiAgICAgIHRhcERpc3RhbmNlVG9wID0gdGFwRXZ0LmNsaWVudFkgLSBkcmFnUmVjdC50b3A7XG4gICAgICB0aGlzLl9sYXN0WCA9ICh0b3VjaCB8fCBldnQpLmNsaWVudFg7XG4gICAgICB0aGlzLl9sYXN0WSA9ICh0b3VjaCB8fCBldnQpLmNsaWVudFk7XG4gICAgICBkcmFnRWwuc3R5bGVbJ3dpbGwtY2hhbmdlJ10gPSAnYWxsJztcbiAgICAgIGRyYWdTdGFydEZuID0gZnVuY3Rpb24gZHJhZ1N0YXJ0Rm4oKSB7XG4gICAgICAgIHBsdWdpbkV2ZW50KCdkZWxheUVuZGVkJywgX3RoaXMsIHtcbiAgICAgICAgICBldnQ6IGV2dFxuICAgICAgICB9KTtcbiAgICAgICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHtcbiAgICAgICAgICBfdGhpcy5fb25Ecm9wKCk7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIC8vIERlbGF5ZWQgZHJhZyBoYXMgYmVlbiB0cmlnZ2VyZWRcbiAgICAgICAgLy8gd2UgY2FuIHJlLWVuYWJsZSB0aGUgZXZlbnRzOiB0b3VjaG1vdmUvbW91c2Vtb3ZlXG4gICAgICAgIF90aGlzLl9kaXNhYmxlRGVsYXllZERyYWdFdmVudHMoKTtcbiAgICAgICAgaWYgKCFGaXJlRm94ICYmIF90aGlzLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgICAgICAgIGRyYWdFbC5kcmFnZ2FibGUgPSB0cnVlO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gQmluZCB0aGUgZXZlbnRzOiBkcmFnc3RhcnQvZHJhZ2VuZFxuICAgICAgICBfdGhpcy5fdHJpZ2dlckRyYWdTdGFydChldnQsIHRvdWNoKTtcblxuICAgICAgICAvLyBEcmFnIHN0YXJ0IGV2ZW50XG4gICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICBzb3J0YWJsZTogX3RoaXMsXG4gICAgICAgICAgbmFtZTogJ2Nob29zZScsXG4gICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgIH0pO1xuXG4gICAgICAgIC8vIENob3NlbiBpdGVtXG4gICAgICAgIHRvZ2dsZUNsYXNzKGRyYWdFbCwgb3B0aW9ucy5jaG9zZW5DbGFzcywgdHJ1ZSk7XG4gICAgICB9O1xuXG4gICAgICAvLyBEaXNhYmxlIFwiZHJhZ2dhYmxlXCJcbiAgICAgIG9wdGlvbnMuaWdub3JlLnNwbGl0KCcsJykuZm9yRWFjaChmdW5jdGlvbiAoY3JpdGVyaWEpIHtcbiAgICAgICAgZmluZChkcmFnRWwsIGNyaXRlcmlhLnRyaW0oKSwgX2Rpc2FibGVEcmFnZ2FibGUpO1xuICAgICAgfSk7XG4gICAgICBvbihvd25lckRvY3VtZW50LCAnZHJhZ292ZXInLCBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudCk7XG4gICAgICBvbihvd25lckRvY3VtZW50LCAnbW91c2Vtb3ZlJywgbmVhcmVzdEVtcHR5SW5zZXJ0RGV0ZWN0RXZlbnQpO1xuICAgICAgb24ob3duZXJEb2N1bWVudCwgJ3RvdWNobW92ZScsIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KTtcbiAgICAgIG9uKG93bmVyRG9jdW1lbnQsICdtb3VzZXVwJywgX3RoaXMuX29uRHJvcCk7XG4gICAgICBvbihvd25lckRvY3VtZW50LCAndG91Y2hlbmQnLCBfdGhpcy5fb25Ecm9wKTtcbiAgICAgIG9uKG93bmVyRG9jdW1lbnQsICd0b3VjaGNhbmNlbCcsIF90aGlzLl9vbkRyb3ApO1xuXG4gICAgICAvLyBNYWtlIGRyYWdFbCBkcmFnZ2FibGUgKG11c3QgYmUgYmVmb3JlIGRlbGF5IGZvciBGaXJlRm94KVxuICAgICAgaWYgKEZpcmVGb3ggJiYgdGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgdGhpcy5vcHRpb25zLnRvdWNoU3RhcnRUaHJlc2hvbGQgPSA0O1xuICAgICAgICBkcmFnRWwuZHJhZ2dhYmxlID0gdHJ1ZTtcbiAgICAgIH1cbiAgICAgIHBsdWdpbkV2ZW50KCdkZWxheVN0YXJ0JywgdGhpcywge1xuICAgICAgICBldnQ6IGV2dFxuICAgICAgfSk7XG5cbiAgICAgIC8vIERlbGF5IGlzIGltcG9zc2libGUgZm9yIG5hdGl2ZSBEbkQgaW4gRWRnZSBvciBJRVxuICAgICAgaWYgKG9wdGlvbnMuZGVsYXkgJiYgKCFvcHRpb25zLmRlbGF5T25Ub3VjaE9ubHkgfHwgdG91Y2gpICYmICghdGhpcy5uYXRpdmVEcmFnZ2FibGUgfHwgIShFZGdlIHx8IElFMTFPckxlc3MpKSkge1xuICAgICAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkge1xuICAgICAgICAgIHRoaXMuX29uRHJvcCgpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICAvLyBJZiB0aGUgdXNlciBtb3ZlcyB0aGUgcG9pbnRlciBvciBsZXQgZ28gdGhlIGNsaWNrIG9yIHRvdWNoXG4gICAgICAgIC8vIGJlZm9yZSB0aGUgZGVsYXkgaGFzIGJlZW4gcmVhY2hlZDpcbiAgICAgICAgLy8gZGlzYWJsZSB0aGUgZGVsYXllZCBkcmFnXG4gICAgICAgIG9uKG93bmVyRG9jdW1lbnQsICdtb3VzZXVwJywgX3RoaXMuX2Rpc2FibGVEZWxheWVkRHJhZyk7XG4gICAgICAgIG9uKG93bmVyRG9jdW1lbnQsICd0b3VjaGVuZCcsIF90aGlzLl9kaXNhYmxlRGVsYXllZERyYWcpO1xuICAgICAgICBvbihvd25lckRvY3VtZW50LCAndG91Y2hjYW5jZWwnLCBfdGhpcy5fZGlzYWJsZURlbGF5ZWREcmFnKTtcbiAgICAgICAgb24ob3duZXJEb2N1bWVudCwgJ21vdXNlbW92ZScsIF90aGlzLl9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXIpO1xuICAgICAgICBvbihvd25lckRvY3VtZW50LCAndG91Y2htb3ZlJywgX3RoaXMuX2RlbGF5ZWREcmFnVG91Y2hNb3ZlSGFuZGxlcik7XG4gICAgICAgIG9wdGlvbnMuc3VwcG9ydFBvaW50ZXIgJiYgb24ob3duZXJEb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgX3RoaXMuX2RlbGF5ZWREcmFnVG91Y2hNb3ZlSGFuZGxlcik7XG4gICAgICAgIF90aGlzLl9kcmFnU3RhcnRUaW1lciA9IHNldFRpbWVvdXQoZHJhZ1N0YXJ0Rm4sIG9wdGlvbnMuZGVsYXkpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgZHJhZ1N0YXJ0Rm4oKTtcbiAgICAgIH1cbiAgICB9XG4gIH0sXG4gIF9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXI6IGZ1bmN0aW9uIF9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXIoIC8qKiBUb3VjaEV2ZW50fFBvaW50ZXJFdmVudCAqKi9lKSB7XG4gICAgdmFyIHRvdWNoID0gZS50b3VjaGVzID8gZS50b3VjaGVzWzBdIDogZTtcbiAgICBpZiAoTWF0aC5tYXgoTWF0aC5hYnModG91Y2guY2xpZW50WCAtIHRoaXMuX2xhc3RYKSwgTWF0aC5hYnModG91Y2guY2xpZW50WSAtIHRoaXMuX2xhc3RZKSkgPj0gTWF0aC5mbG9vcih0aGlzLm9wdGlvbnMudG91Y2hTdGFydFRocmVzaG9sZCAvICh0aGlzLm5hdGl2ZURyYWdnYWJsZSAmJiB3aW5kb3cuZGV2aWNlUGl4ZWxSYXRpbyB8fCAxKSkpIHtcbiAgICAgIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZygpO1xuICAgIH1cbiAgfSxcbiAgX2Rpc2FibGVEZWxheWVkRHJhZzogZnVuY3Rpb24gX2Rpc2FibGVEZWxheWVkRHJhZygpIHtcbiAgICBkcmFnRWwgJiYgX2Rpc2FibGVEcmFnZ2FibGUoZHJhZ0VsKTtcbiAgICBjbGVhclRpbWVvdXQodGhpcy5fZHJhZ1N0YXJ0VGltZXIpO1xuICAgIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZ0V2ZW50cygpO1xuICB9LFxuICBfZGlzYWJsZURlbGF5ZWREcmFnRXZlbnRzOiBmdW5jdGlvbiBfZGlzYWJsZURlbGF5ZWREcmFnRXZlbnRzKCkge1xuICAgIHZhciBvd25lckRvY3VtZW50ID0gdGhpcy5lbC5vd25lckRvY3VtZW50O1xuICAgIG9mZihvd25lckRvY3VtZW50LCAnbW91c2V1cCcsIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZyk7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICd0b3VjaGVuZCcsIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZyk7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICd0b3VjaGNhbmNlbCcsIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZyk7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICdtb3VzZW1vdmUnLCB0aGlzLl9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXIpO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAndG91Y2htb3ZlJywgdGhpcy5fZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyKTtcbiAgICBvZmYob3duZXJEb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgdGhpcy5fZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyKTtcbiAgfSxcbiAgX3RyaWdnZXJEcmFnU3RhcnQ6IGZ1bmN0aW9uIF90cmlnZ2VyRHJhZ1N0YXJ0KCAvKiogRXZlbnQgKi9ldnQsIC8qKiBUb3VjaCAqL3RvdWNoKSB7XG4gICAgdG91Y2ggPSB0b3VjaCB8fCBldnQucG9pbnRlclR5cGUgPT0gJ3RvdWNoJyAmJiBldnQ7XG4gICAgaWYgKCF0aGlzLm5hdGl2ZURyYWdnYWJsZSB8fCB0b3VjaCkge1xuICAgICAgaWYgKHRoaXMub3B0aW9ucy5zdXBwb3J0UG9pbnRlcikge1xuICAgICAgICBvbihkb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgdGhpcy5fb25Ub3VjaE1vdmUpO1xuICAgICAgfSBlbHNlIGlmICh0b3VjaCkge1xuICAgICAgICBvbihkb2N1bWVudCwgJ3RvdWNobW92ZScsIHRoaXMuX29uVG91Y2hNb3ZlKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIG9uKGRvY3VtZW50LCAnbW91c2Vtb3ZlJywgdGhpcy5fb25Ub3VjaE1vdmUpO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBvbihkcmFnRWwsICdkcmFnZW5kJywgdGhpcyk7XG4gICAgICBvbihyb290RWwsICdkcmFnc3RhcnQnLCB0aGlzLl9vbkRyYWdTdGFydCk7XG4gICAgfVxuICAgIHRyeSB7XG4gICAgICBpZiAoZG9jdW1lbnQuc2VsZWN0aW9uKSB7XG4gICAgICAgIC8vIFRpbWVvdXQgbmVjY2Vzc2FyeSBmb3IgSUU5XG4gICAgICAgIF9uZXh0VGljayhmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgZG9jdW1lbnQuc2VsZWN0aW9uLmVtcHR5KCk7XG4gICAgICAgIH0pO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgd2luZG93LmdldFNlbGVjdGlvbigpLnJlbW92ZUFsbFJhbmdlcygpO1xuICAgICAgfVxuICAgIH0gY2F0Y2ggKGVycikge31cbiAgfSxcbiAgX2RyYWdTdGFydGVkOiBmdW5jdGlvbiBfZHJhZ1N0YXJ0ZWQoZmFsbGJhY2ssIGV2dCkge1xuICAgIGF3YWl0aW5nRHJhZ1N0YXJ0ZWQgPSBmYWxzZTtcbiAgICBpZiAocm9vdEVsICYmIGRyYWdFbCkge1xuICAgICAgcGx1Z2luRXZlbnQoJ2RyYWdTdGFydGVkJywgdGhpcywge1xuICAgICAgICBldnQ6IGV2dFxuICAgICAgfSk7XG4gICAgICBpZiAodGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICdkcmFnb3ZlcicsIF9jaGVja091dHNpZGVUYXJnZXRFbCk7XG4gICAgICB9XG4gICAgICB2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcblxuICAgICAgLy8gQXBwbHkgZWZmZWN0XG4gICAgICAhZmFsbGJhY2sgJiYgdG9nZ2xlQ2xhc3MoZHJhZ0VsLCBvcHRpb25zLmRyYWdDbGFzcywgZmFsc2UpO1xuICAgICAgdG9nZ2xlQ2xhc3MoZHJhZ0VsLCBvcHRpb25zLmdob3N0Q2xhc3MsIHRydWUpO1xuICAgICAgU29ydGFibGUuYWN0aXZlID0gdGhpcztcbiAgICAgIGZhbGxiYWNrICYmIHRoaXMuX2FwcGVuZEdob3N0KCk7XG5cbiAgICAgIC8vIERyYWcgc3RhcnQgZXZlbnRcbiAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgIG5hbWU6ICdzdGFydCcsXG4gICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgfSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRoaXMuX251bGxpbmcoKTtcbiAgICB9XG4gIH0sXG4gIF9lbXVsYXRlRHJhZ092ZXI6IGZ1bmN0aW9uIF9lbXVsYXRlRHJhZ092ZXIoKSB7XG4gICAgaWYgKHRvdWNoRXZ0KSB7XG4gICAgICB0aGlzLl9sYXN0WCA9IHRvdWNoRXZ0LmNsaWVudFg7XG4gICAgICB0aGlzLl9sYXN0WSA9IHRvdWNoRXZ0LmNsaWVudFk7XG4gICAgICBfaGlkZUdob3N0Rm9yVGFyZ2V0KCk7XG4gICAgICB2YXIgdGFyZ2V0ID0gZG9jdW1lbnQuZWxlbWVudEZyb21Qb2ludCh0b3VjaEV2dC5jbGllbnRYLCB0b3VjaEV2dC5jbGllbnRZKTtcbiAgICAgIHZhciBwYXJlbnQgPSB0YXJnZXQ7XG4gICAgICB3aGlsZSAodGFyZ2V0ICYmIHRhcmdldC5zaGFkb3dSb290KSB7XG4gICAgICAgIHRhcmdldCA9IHRhcmdldC5zaGFkb3dSb290LmVsZW1lbnRGcm9tUG9pbnQodG91Y2hFdnQuY2xpZW50WCwgdG91Y2hFdnQuY2xpZW50WSk7XG4gICAgICAgIGlmICh0YXJnZXQgPT09IHBhcmVudCkgYnJlYWs7XG4gICAgICAgIHBhcmVudCA9IHRhcmdldDtcbiAgICAgIH1cbiAgICAgIGRyYWdFbC5wYXJlbnROb2RlW2V4cGFuZG9dLl9pc091dHNpZGVUaGlzRWwodGFyZ2V0KTtcbiAgICAgIGlmIChwYXJlbnQpIHtcbiAgICAgICAgZG8ge1xuICAgICAgICAgIGlmIChwYXJlbnRbZXhwYW5kb10pIHtcbiAgICAgICAgICAgIHZhciBpbnNlcnRlZCA9IHZvaWQgMDtcbiAgICAgICAgICAgIGluc2VydGVkID0gcGFyZW50W2V4cGFuZG9dLl9vbkRyYWdPdmVyKHtcbiAgICAgICAgICAgICAgY2xpZW50WDogdG91Y2hFdnQuY2xpZW50WCxcbiAgICAgICAgICAgICAgY2xpZW50WTogdG91Y2hFdnQuY2xpZW50WSxcbiAgICAgICAgICAgICAgdGFyZ2V0OiB0YXJnZXQsXG4gICAgICAgICAgICAgIHJvb3RFbDogcGFyZW50XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIGlmIChpbnNlcnRlZCAmJiAhdGhpcy5vcHRpb25zLmRyYWdvdmVyQnViYmxlKSB7XG4gICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICB0YXJnZXQgPSBwYXJlbnQ7IC8vIHN0b3JlIGxhc3QgZWxlbWVudFxuICAgICAgICB9XG4gICAgICAgIC8qIGpzaGludCBib3NzOnRydWUgKi8gd2hpbGUgKHBhcmVudCA9IHBhcmVudC5wYXJlbnROb2RlKTtcbiAgICAgIH1cbiAgICAgIF91bmhpZGVHaG9zdEZvclRhcmdldCgpO1xuICAgIH1cbiAgfSxcbiAgX29uVG91Y2hNb3ZlOiBmdW5jdGlvbiBfb25Ub3VjaE1vdmUoIC8qKlRvdWNoRXZlbnQqL2V2dCkge1xuICAgIGlmICh0YXBFdnQpIHtcbiAgICAgIHZhciBvcHRpb25zID0gdGhpcy5vcHRpb25zLFxuICAgICAgICBmYWxsYmFja1RvbGVyYW5jZSA9IG9wdGlvbnMuZmFsbGJhY2tUb2xlcmFuY2UsXG4gICAgICAgIGZhbGxiYWNrT2Zmc2V0ID0gb3B0aW9ucy5mYWxsYmFja09mZnNldCxcbiAgICAgICAgdG91Y2ggPSBldnQudG91Y2hlcyA/IGV2dC50b3VjaGVzWzBdIDogZXZ0LFxuICAgICAgICBnaG9zdE1hdHJpeCA9IGdob3N0RWwgJiYgbWF0cml4KGdob3N0RWwsIHRydWUpLFxuICAgICAgICBzY2FsZVggPSBnaG9zdEVsICYmIGdob3N0TWF0cml4ICYmIGdob3N0TWF0cml4LmEsXG4gICAgICAgIHNjYWxlWSA9IGdob3N0RWwgJiYgZ2hvc3RNYXRyaXggJiYgZ2hvc3RNYXRyaXguZCxcbiAgICAgICAgcmVsYXRpdmVTY3JvbGxPZmZzZXQgPSBQb3NpdGlvbkdob3N0QWJzb2x1dGVseSAmJiBnaG9zdFJlbGF0aXZlUGFyZW50ICYmIGdldFJlbGF0aXZlU2Nyb2xsT2Zmc2V0KGdob3N0UmVsYXRpdmVQYXJlbnQpLFxuICAgICAgICBkeCA9ICh0b3VjaC5jbGllbnRYIC0gdGFwRXZ0LmNsaWVudFggKyBmYWxsYmFja09mZnNldC54KSAvIChzY2FsZVggfHwgMSkgKyAocmVsYXRpdmVTY3JvbGxPZmZzZXQgPyByZWxhdGl2ZVNjcm9sbE9mZnNldFswXSAtIGdob3N0UmVsYXRpdmVQYXJlbnRJbml0aWFsU2Nyb2xsWzBdIDogMCkgLyAoc2NhbGVYIHx8IDEpLFxuICAgICAgICBkeSA9ICh0b3VjaC5jbGllbnRZIC0gdGFwRXZ0LmNsaWVudFkgKyBmYWxsYmFja09mZnNldC55KSAvIChzY2FsZVkgfHwgMSkgKyAocmVsYXRpdmVTY3JvbGxPZmZzZXQgPyByZWxhdGl2ZVNjcm9sbE9mZnNldFsxXSAtIGdob3N0UmVsYXRpdmVQYXJlbnRJbml0aWFsU2Nyb2xsWzFdIDogMCkgLyAoc2NhbGVZIHx8IDEpO1xuXG4gICAgICAvLyBvbmx5IHNldCB0aGUgc3RhdHVzIHRvIGRyYWdnaW5nLCB3aGVuIHdlIGFyZSBhY3R1YWxseSBkcmFnZ2luZ1xuICAgICAgaWYgKCFTb3J0YWJsZS5hY3RpdmUgJiYgIWF3YWl0aW5nRHJhZ1N0YXJ0ZWQpIHtcbiAgICAgICAgaWYgKGZhbGxiYWNrVG9sZXJhbmNlICYmIE1hdGgubWF4KE1hdGguYWJzKHRvdWNoLmNsaWVudFggLSB0aGlzLl9sYXN0WCksIE1hdGguYWJzKHRvdWNoLmNsaWVudFkgLSB0aGlzLl9sYXN0WSkpIDwgZmFsbGJhY2tUb2xlcmFuY2UpIHtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5fb25EcmFnU3RhcnQoZXZ0LCB0cnVlKTtcbiAgICAgIH1cbiAgICAgIGlmIChnaG9zdEVsKSB7XG4gICAgICAgIGlmIChnaG9zdE1hdHJpeCkge1xuICAgICAgICAgIGdob3N0TWF0cml4LmUgKz0gZHggLSAobGFzdER4IHx8IDApO1xuICAgICAgICAgIGdob3N0TWF0cml4LmYgKz0gZHkgLSAobGFzdER5IHx8IDApO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGdob3N0TWF0cml4ID0ge1xuICAgICAgICAgICAgYTogMSxcbiAgICAgICAgICAgIGI6IDAsXG4gICAgICAgICAgICBjOiAwLFxuICAgICAgICAgICAgZDogMSxcbiAgICAgICAgICAgIGU6IGR4LFxuICAgICAgICAgICAgZjogZHlcbiAgICAgICAgICB9O1xuICAgICAgICB9XG4gICAgICAgIHZhciBjc3NNYXRyaXggPSBcIm1hdHJpeChcIi5jb25jYXQoZ2hvc3RNYXRyaXguYSwgXCIsXCIpLmNvbmNhdChnaG9zdE1hdHJpeC5iLCBcIixcIikuY29uY2F0KGdob3N0TWF0cml4LmMsIFwiLFwiKS5jb25jYXQoZ2hvc3RNYXRyaXguZCwgXCIsXCIpLmNvbmNhdChnaG9zdE1hdHJpeC5lLCBcIixcIikuY29uY2F0KGdob3N0TWF0cml4LmYsIFwiKVwiKTtcbiAgICAgICAgY3NzKGdob3N0RWwsICd3ZWJraXRUcmFuc2Zvcm0nLCBjc3NNYXRyaXgpO1xuICAgICAgICBjc3MoZ2hvc3RFbCwgJ21velRyYW5zZm9ybScsIGNzc01hdHJpeCk7XG4gICAgICAgIGNzcyhnaG9zdEVsLCAnbXNUcmFuc2Zvcm0nLCBjc3NNYXRyaXgpO1xuICAgICAgICBjc3MoZ2hvc3RFbCwgJ3RyYW5zZm9ybScsIGNzc01hdHJpeCk7XG4gICAgICAgIGxhc3REeCA9IGR4O1xuICAgICAgICBsYXN0RHkgPSBkeTtcbiAgICAgICAgdG91Y2hFdnQgPSB0b3VjaDtcbiAgICAgIH1cbiAgICAgIGV2dC5jYW5jZWxhYmxlICYmIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIH1cbiAgfSxcbiAgX2FwcGVuZEdob3N0OiBmdW5jdGlvbiBfYXBwZW5kR2hvc3QoKSB7XG4gICAgLy8gQnVnIGlmIHVzaW5nIHNjYWxlKCk6IGh0dHBzOi8vc3RhY2tvdmVyZmxvdy5jb20vcXVlc3Rpb25zLzI2MzcwNThcbiAgICAvLyBOb3QgYmVpbmcgYWRqdXN0ZWQgZm9yXG4gICAgaWYgKCFnaG9zdEVsKSB7XG4gICAgICB2YXIgY29udGFpbmVyID0gdGhpcy5vcHRpb25zLmZhbGxiYWNrT25Cb2R5ID8gZG9jdW1lbnQuYm9keSA6IHJvb3RFbCxcbiAgICAgICAgcmVjdCA9IGdldFJlY3QoZHJhZ0VsLCB0cnVlLCBQb3NpdGlvbkdob3N0QWJzb2x1dGVseSwgdHJ1ZSwgY29udGFpbmVyKSxcbiAgICAgICAgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcblxuICAgICAgLy8gUG9zaXRpb24gYWJzb2x1dGVseVxuICAgICAgaWYgKFBvc2l0aW9uR2hvc3RBYnNvbHV0ZWx5KSB7XG4gICAgICAgIC8vIEdldCByZWxhdGl2ZWx5IHBvc2l0aW9uZWQgcGFyZW50XG4gICAgICAgIGdob3N0UmVsYXRpdmVQYXJlbnQgPSBjb250YWluZXI7XG4gICAgICAgIHdoaWxlIChjc3MoZ2hvc3RSZWxhdGl2ZVBhcmVudCwgJ3Bvc2l0aW9uJykgPT09ICdzdGF0aWMnICYmIGNzcyhnaG9zdFJlbGF0aXZlUGFyZW50LCAndHJhbnNmb3JtJykgPT09ICdub25lJyAmJiBnaG9zdFJlbGF0aXZlUGFyZW50ICE9PSBkb2N1bWVudCkge1xuICAgICAgICAgIGdob3N0UmVsYXRpdmVQYXJlbnQgPSBnaG9zdFJlbGF0aXZlUGFyZW50LnBhcmVudE5vZGU7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGdob3N0UmVsYXRpdmVQYXJlbnQgIT09IGRvY3VtZW50LmJvZHkgJiYgZ2hvc3RSZWxhdGl2ZVBhcmVudCAhPT0gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50KSB7XG4gICAgICAgICAgaWYgKGdob3N0UmVsYXRpdmVQYXJlbnQgPT09IGRvY3VtZW50KSBnaG9zdFJlbGF0aXZlUGFyZW50ID0gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpO1xuICAgICAgICAgIHJlY3QudG9wICs9IGdob3N0UmVsYXRpdmVQYXJlbnQuc2Nyb2xsVG9wO1xuICAgICAgICAgIHJlY3QubGVmdCArPSBnaG9zdFJlbGF0aXZlUGFyZW50LnNjcm9sbExlZnQ7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgZ2hvc3RSZWxhdGl2ZVBhcmVudCA9IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKTtcbiAgICAgICAgfVxuICAgICAgICBnaG9zdFJlbGF0aXZlUGFyZW50SW5pdGlhbFNjcm9sbCA9IGdldFJlbGF0aXZlU2Nyb2xsT2Zmc2V0KGdob3N0UmVsYXRpdmVQYXJlbnQpO1xuICAgICAgfVxuICAgICAgZ2hvc3RFbCA9IGRyYWdFbC5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgICB0b2dnbGVDbGFzcyhnaG9zdEVsLCBvcHRpb25zLmdob3N0Q2xhc3MsIGZhbHNlKTtcbiAgICAgIHRvZ2dsZUNsYXNzKGdob3N0RWwsIG9wdGlvbnMuZmFsbGJhY2tDbGFzcywgdHJ1ZSk7XG4gICAgICB0b2dnbGVDbGFzcyhnaG9zdEVsLCBvcHRpb25zLmRyYWdDbGFzcywgdHJ1ZSk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ3RyYW5zaXRpb24nLCAnJyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ3RyYW5zZm9ybScsICcnKTtcbiAgICAgIGNzcyhnaG9zdEVsLCAnYm94LXNpemluZycsICdib3JkZXItYm94Jyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ21hcmdpbicsIDApO1xuICAgICAgY3NzKGdob3N0RWwsICd0b3AnLCByZWN0LnRvcCk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ2xlZnQnLCByZWN0LmxlZnQpO1xuICAgICAgY3NzKGdob3N0RWwsICd3aWR0aCcsIHJlY3Qud2lkdGgpO1xuICAgICAgY3NzKGdob3N0RWwsICdoZWlnaHQnLCByZWN0LmhlaWdodCk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ29wYWNpdHknLCAnMC44Jyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ3Bvc2l0aW9uJywgUG9zaXRpb25HaG9zdEFic29sdXRlbHkgPyAnYWJzb2x1dGUnIDogJ2ZpeGVkJyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ3pJbmRleCcsICcxMDAwMDAnKTtcbiAgICAgIGNzcyhnaG9zdEVsLCAncG9pbnRlckV2ZW50cycsICdub25lJyk7XG4gICAgICBTb3J0YWJsZS5naG9zdCA9IGdob3N0RWw7XG4gICAgICBjb250YWluZXIuYXBwZW5kQ2hpbGQoZ2hvc3RFbCk7XG5cbiAgICAgIC8vIFNldCB0cmFuc2Zvcm0tb3JpZ2luXG4gICAgICBjc3MoZ2hvc3RFbCwgJ3RyYW5zZm9ybS1vcmlnaW4nLCB0YXBEaXN0YW5jZUxlZnQgLyBwYXJzZUludChnaG9zdEVsLnN0eWxlLndpZHRoKSAqIDEwMCArICclICcgKyB0YXBEaXN0YW5jZVRvcCAvIHBhcnNlSW50KGdob3N0RWwuc3R5bGUuaGVpZ2h0KSAqIDEwMCArICclJyk7XG4gICAgfVxuICB9LFxuICBfb25EcmFnU3RhcnQ6IGZ1bmN0aW9uIF9vbkRyYWdTdGFydCggLyoqRXZlbnQqL2V2dCwgLyoqYm9vbGVhbiovZmFsbGJhY2spIHtcbiAgICB2YXIgX3RoaXMgPSB0aGlzO1xuICAgIHZhciBkYXRhVHJhbnNmZXIgPSBldnQuZGF0YVRyYW5zZmVyO1xuICAgIHZhciBvcHRpb25zID0gX3RoaXMub3B0aW9ucztcbiAgICBwbHVnaW5FdmVudCgnZHJhZ1N0YXJ0JywgdGhpcywge1xuICAgICAgZXZ0OiBldnRcbiAgICB9KTtcbiAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkge1xuICAgICAgdGhpcy5fb25Ecm9wKCk7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIHBsdWdpbkV2ZW50KCdzZXR1cENsb25lJywgdGhpcyk7XG4gICAgaWYgKCFTb3J0YWJsZS5ldmVudENhbmNlbGVkKSB7XG4gICAgICBjbG9uZUVsID0gY2xvbmUoZHJhZ0VsKTtcbiAgICAgIGNsb25lRWwucmVtb3ZlQXR0cmlidXRlKFwiaWRcIik7XG4gICAgICBjbG9uZUVsLmRyYWdnYWJsZSA9IGZhbHNlO1xuICAgICAgY2xvbmVFbC5zdHlsZVsnd2lsbC1jaGFuZ2UnXSA9ICcnO1xuICAgICAgdGhpcy5faGlkZUNsb25lKCk7XG4gICAgICB0b2dnbGVDbGFzcyhjbG9uZUVsLCB0aGlzLm9wdGlvbnMuY2hvc2VuQ2xhc3MsIGZhbHNlKTtcbiAgICAgIFNvcnRhYmxlLmNsb25lID0gY2xvbmVFbDtcbiAgICB9XG5cbiAgICAvLyAjMTE0MzogSUZyYW1lIHN1cHBvcnQgd29ya2Fyb3VuZFxuICAgIF90aGlzLmNsb25lSWQgPSBfbmV4dFRpY2soZnVuY3Rpb24gKCkge1xuICAgICAgcGx1Z2luRXZlbnQoJ2Nsb25lJywgX3RoaXMpO1xuICAgICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHJldHVybjtcbiAgICAgIGlmICghX3RoaXMub3B0aW9ucy5yZW1vdmVDbG9uZU9uSGlkZSkge1xuICAgICAgICByb290RWwuaW5zZXJ0QmVmb3JlKGNsb25lRWwsIGRyYWdFbCk7XG4gICAgICB9XG4gICAgICBfdGhpcy5faGlkZUNsb25lKCk7XG4gICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgIHNvcnRhYmxlOiBfdGhpcyxcbiAgICAgICAgbmFtZTogJ2Nsb25lJ1xuICAgICAgfSk7XG4gICAgfSk7XG4gICAgIWZhbGxiYWNrICYmIHRvZ2dsZUNsYXNzKGRyYWdFbCwgb3B0aW9ucy5kcmFnQ2xhc3MsIHRydWUpO1xuXG4gICAgLy8gU2V0IHByb3BlciBkcm9wIGV2ZW50c1xuICAgIGlmIChmYWxsYmFjaykge1xuICAgICAgaWdub3JlTmV4dENsaWNrID0gdHJ1ZTtcbiAgICAgIF90aGlzLl9sb29wSWQgPSBzZXRJbnRlcnZhbChfdGhpcy5fZW11bGF0ZURyYWdPdmVyLCA1MCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIC8vIFVuZG8gd2hhdCB3YXMgc2V0IGluIF9wcmVwYXJlRHJhZ1N0YXJ0IGJlZm9yZSBkcmFnIHN0YXJ0ZWRcbiAgICAgIG9mZihkb2N1bWVudCwgJ21vdXNldXAnLCBfdGhpcy5fb25Ecm9wKTtcbiAgICAgIG9mZihkb2N1bWVudCwgJ3RvdWNoZW5kJywgX3RoaXMuX29uRHJvcCk7XG4gICAgICBvZmYoZG9jdW1lbnQsICd0b3VjaGNhbmNlbCcsIF90aGlzLl9vbkRyb3ApO1xuICAgICAgaWYgKGRhdGFUcmFuc2Zlcikge1xuICAgICAgICBkYXRhVHJhbnNmZXIuZWZmZWN0QWxsb3dlZCA9ICdtb3ZlJztcbiAgICAgICAgb3B0aW9ucy5zZXREYXRhICYmIG9wdGlvbnMuc2V0RGF0YS5jYWxsKF90aGlzLCBkYXRhVHJhbnNmZXIsIGRyYWdFbCk7XG4gICAgICB9XG4gICAgICBvbihkb2N1bWVudCwgJ2Ryb3AnLCBfdGhpcyk7XG5cbiAgICAgIC8vICMxMjc2IGZpeDpcbiAgICAgIGNzcyhkcmFnRWwsICd0cmFuc2Zvcm0nLCAndHJhbnNsYXRlWigwKScpO1xuICAgIH1cbiAgICBhd2FpdGluZ0RyYWdTdGFydGVkID0gdHJ1ZTtcbiAgICBfdGhpcy5fZHJhZ1N0YXJ0SWQgPSBfbmV4dFRpY2soX3RoaXMuX2RyYWdTdGFydGVkLmJpbmQoX3RoaXMsIGZhbGxiYWNrLCBldnQpKTtcbiAgICBvbihkb2N1bWVudCwgJ3NlbGVjdHN0YXJ0JywgX3RoaXMpO1xuICAgIG1vdmVkID0gdHJ1ZTtcbiAgICBpZiAoU2FmYXJpKSB7XG4gICAgICBjc3MoZG9jdW1lbnQuYm9keSwgJ3VzZXItc2VsZWN0JywgJ25vbmUnKTtcbiAgICB9XG4gIH0sXG4gIC8vIFJldHVybnMgdHJ1ZSAtIGlmIG5vIGZ1cnRoZXIgYWN0aW9uIGlzIG5lZWRlZCAoZWl0aGVyIGluc2VydGVkIG9yIGFub3RoZXIgY29uZGl0aW9uKVxuICBfb25EcmFnT3ZlcjogZnVuY3Rpb24gX29uRHJhZ092ZXIoIC8qKkV2ZW50Ki9ldnQpIHtcbiAgICB2YXIgZWwgPSB0aGlzLmVsLFxuICAgICAgdGFyZ2V0ID0gZXZ0LnRhcmdldCxcbiAgICAgIGRyYWdSZWN0LFxuICAgICAgdGFyZ2V0UmVjdCxcbiAgICAgIHJldmVydCxcbiAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnMsXG4gICAgICBncm91cCA9IG9wdGlvbnMuZ3JvdXAsXG4gICAgICBhY3RpdmVTb3J0YWJsZSA9IFNvcnRhYmxlLmFjdGl2ZSxcbiAgICAgIGlzT3duZXIgPSBhY3RpdmVHcm91cCA9PT0gZ3JvdXAsXG4gICAgICBjYW5Tb3J0ID0gb3B0aW9ucy5zb3J0LFxuICAgICAgZnJvbVNvcnRhYmxlID0gcHV0U29ydGFibGUgfHwgYWN0aXZlU29ydGFibGUsXG4gICAgICB2ZXJ0aWNhbCxcbiAgICAgIF90aGlzID0gdGhpcyxcbiAgICAgIGNvbXBsZXRlZEZpcmVkID0gZmFsc2U7XG4gICAgaWYgKF9zaWxlbnQpIHJldHVybjtcbiAgICBmdW5jdGlvbiBkcmFnT3ZlckV2ZW50KG5hbWUsIGV4dHJhKSB7XG4gICAgICBwbHVnaW5FdmVudChuYW1lLCBfdGhpcywgX29iamVjdFNwcmVhZDIoe1xuICAgICAgICBldnQ6IGV2dCxcbiAgICAgICAgaXNPd25lcjogaXNPd25lcixcbiAgICAgICAgYXhpczogdmVydGljYWwgPyAndmVydGljYWwnIDogJ2hvcml6b250YWwnLFxuICAgICAgICByZXZlcnQ6IHJldmVydCxcbiAgICAgICAgZHJhZ1JlY3Q6IGRyYWdSZWN0LFxuICAgICAgICB0YXJnZXRSZWN0OiB0YXJnZXRSZWN0LFxuICAgICAgICBjYW5Tb3J0OiBjYW5Tb3J0LFxuICAgICAgICBmcm9tU29ydGFibGU6IGZyb21Tb3J0YWJsZSxcbiAgICAgICAgdGFyZ2V0OiB0YXJnZXQsXG4gICAgICAgIGNvbXBsZXRlZDogY29tcGxldGVkLFxuICAgICAgICBvbk1vdmU6IGZ1bmN0aW9uIG9uTW92ZSh0YXJnZXQsIGFmdGVyKSB7XG4gICAgICAgICAgcmV0dXJuIF9vbk1vdmUocm9vdEVsLCBlbCwgZHJhZ0VsLCBkcmFnUmVjdCwgdGFyZ2V0LCBnZXRSZWN0KHRhcmdldCksIGV2dCwgYWZ0ZXIpO1xuICAgICAgICB9LFxuICAgICAgICBjaGFuZ2VkOiBjaGFuZ2VkXG4gICAgICB9LCBleHRyYSkpO1xuICAgIH1cblxuICAgIC8vIENhcHR1cmUgYW5pbWF0aW9uIHN0YXRlXG4gICAgZnVuY3Rpb24gY2FwdHVyZSgpIHtcbiAgICAgIGRyYWdPdmVyRXZlbnQoJ2RyYWdPdmVyQW5pbWF0aW9uQ2FwdHVyZScpO1xuICAgICAgX3RoaXMuY2FwdHVyZUFuaW1hdGlvblN0YXRlKCk7XG4gICAgICBpZiAoX3RoaXMgIT09IGZyb21Tb3J0YWJsZSkge1xuICAgICAgICBmcm9tU29ydGFibGUuY2FwdHVyZUFuaW1hdGlvblN0YXRlKCk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgLy8gUmV0dXJuIGludm9jYXRpb24gd2hlbiBkcmFnRWwgaXMgaW5zZXJ0ZWQgKG9yIGNvbXBsZXRlZClcbiAgICBmdW5jdGlvbiBjb21wbGV0ZWQoaW5zZXJ0aW9uKSB7XG4gICAgICBkcmFnT3ZlckV2ZW50KCdkcmFnT3ZlckNvbXBsZXRlZCcsIHtcbiAgICAgICAgaW5zZXJ0aW9uOiBpbnNlcnRpb25cbiAgICAgIH0pO1xuICAgICAgaWYgKGluc2VydGlvbikge1xuICAgICAgICAvLyBDbG9uZXMgbXVzdCBiZSBoaWRkZW4gYmVmb3JlIGZvbGRpbmcgYW5pbWF0aW9uIHRvIGNhcHR1cmUgZHJhZ1JlY3RBYnNvbHV0ZSBwcm9wZXJseVxuICAgICAgICBpZiAoaXNPd25lcikge1xuICAgICAgICAgIGFjdGl2ZVNvcnRhYmxlLl9oaWRlQ2xvbmUoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBhY3RpdmVTb3J0YWJsZS5fc2hvd0Nsb25lKF90aGlzKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoX3RoaXMgIT09IGZyb21Tb3J0YWJsZSkge1xuICAgICAgICAgIC8vIFNldCBnaG9zdCBjbGFzcyB0byBuZXcgc29ydGFibGUncyBnaG9zdCBjbGFzc1xuICAgICAgICAgIHRvZ2dsZUNsYXNzKGRyYWdFbCwgcHV0U29ydGFibGUgPyBwdXRTb3J0YWJsZS5vcHRpb25zLmdob3N0Q2xhc3MgOiBhY3RpdmVTb3J0YWJsZS5vcHRpb25zLmdob3N0Q2xhc3MsIGZhbHNlKTtcbiAgICAgICAgICB0b2dnbGVDbGFzcyhkcmFnRWwsIG9wdGlvbnMuZ2hvc3RDbGFzcywgdHJ1ZSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHB1dFNvcnRhYmxlICE9PSBfdGhpcyAmJiBfdGhpcyAhPT0gU29ydGFibGUuYWN0aXZlKSB7XG4gICAgICAgICAgcHV0U29ydGFibGUgPSBfdGhpcztcbiAgICAgICAgfSBlbHNlIGlmIChfdGhpcyA9PT0gU29ydGFibGUuYWN0aXZlICYmIHB1dFNvcnRhYmxlKSB7XG4gICAgICAgICAgcHV0U29ydGFibGUgPSBudWxsO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gQW5pbWF0aW9uXG4gICAgICAgIGlmIChmcm9tU29ydGFibGUgPT09IF90aGlzKSB7XG4gICAgICAgICAgX3RoaXMuX2lnbm9yZVdoaWxlQW5pbWF0aW5nID0gdGFyZ2V0O1xuICAgICAgICB9XG4gICAgICAgIF90aGlzLmFuaW1hdGVBbGwoZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGRyYWdPdmVyRXZlbnQoJ2RyYWdPdmVyQW5pbWF0aW9uQ29tcGxldGUnKTtcbiAgICAgICAgICBfdGhpcy5faWdub3JlV2hpbGVBbmltYXRpbmcgPSBudWxsO1xuICAgICAgICB9KTtcbiAgICAgICAgaWYgKF90aGlzICE9PSBmcm9tU29ydGFibGUpIHtcbiAgICAgICAgICBmcm9tU29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgICAgICAgIGZyb21Tb3J0YWJsZS5faWdub3JlV2hpbGVBbmltYXRpbmcgPSBudWxsO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIC8vIE51bGwgbGFzdFRhcmdldCBpZiBpdCBpcyBub3QgaW5zaWRlIGEgcHJldmlvdXNseSBzd2FwcGVkIGVsZW1lbnRcbiAgICAgIGlmICh0YXJnZXQgPT09IGRyYWdFbCAmJiAhZHJhZ0VsLmFuaW1hdGVkIHx8IHRhcmdldCA9PT0gZWwgJiYgIXRhcmdldC5hbmltYXRlZCkge1xuICAgICAgICBsYXN0VGFyZ2V0ID0gbnVsbDtcbiAgICAgIH1cblxuICAgICAgLy8gbm8gYnViYmxpbmcgYW5kIG5vdCBmYWxsYmFja1xuICAgICAgaWYgKCFvcHRpb25zLmRyYWdvdmVyQnViYmxlICYmICFldnQucm9vdEVsICYmIHRhcmdldCAhPT0gZG9jdW1lbnQpIHtcbiAgICAgICAgZHJhZ0VsLnBhcmVudE5vZGVbZXhwYW5kb10uX2lzT3V0c2lkZVRoaXNFbChldnQudGFyZ2V0KTtcblxuICAgICAgICAvLyBEbyBub3QgZGV0ZWN0IGZvciBlbXB0eSBpbnNlcnQgaWYgYWxyZWFkeSBpbnNlcnRlZFxuICAgICAgICAhaW5zZXJ0aW9uICYmIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KGV2dCk7XG4gICAgICB9XG4gICAgICAhb3B0aW9ucy5kcmFnb3ZlckJ1YmJsZSAmJiBldnQuc3RvcFByb3BhZ2F0aW9uICYmIGV2dC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgIHJldHVybiBjb21wbGV0ZWRGaXJlZCA9IHRydWU7XG4gICAgfVxuXG4gICAgLy8gQ2FsbCB3aGVuIGRyYWdFbCBoYXMgYmVlbiBpbnNlcnRlZFxuICAgIGZ1bmN0aW9uIGNoYW5nZWQoKSB7XG4gICAgICBuZXdJbmRleCA9IGluZGV4KGRyYWdFbCk7XG4gICAgICBuZXdEcmFnZ2FibGVJbmRleCA9IGluZGV4KGRyYWdFbCwgb3B0aW9ucy5kcmFnZ2FibGUpO1xuICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICBzb3J0YWJsZTogX3RoaXMsXG4gICAgICAgIG5hbWU6ICdjaGFuZ2UnLFxuICAgICAgICB0b0VsOiBlbCxcbiAgICAgICAgbmV3SW5kZXg6IG5ld0luZGV4LFxuICAgICAgICBuZXdEcmFnZ2FibGVJbmRleDogbmV3RHJhZ2dhYmxlSW5kZXgsXG4gICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgfSk7XG4gICAgfVxuICAgIGlmIChldnQucHJldmVudERlZmF1bHQgIT09IHZvaWQgMCkge1xuICAgICAgZXZ0LmNhbmNlbGFibGUgJiYgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgfVxuICAgIHRhcmdldCA9IGNsb3Nlc3QodGFyZ2V0LCBvcHRpb25zLmRyYWdnYWJsZSwgZWwsIHRydWUpO1xuICAgIGRyYWdPdmVyRXZlbnQoJ2RyYWdPdmVyJyk7XG4gICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHJldHVybiBjb21wbGV0ZWRGaXJlZDtcbiAgICBpZiAoZHJhZ0VsLmNvbnRhaW5zKGV2dC50YXJnZXQpIHx8IHRhcmdldC5hbmltYXRlZCAmJiB0YXJnZXQuYW5pbWF0aW5nWCAmJiB0YXJnZXQuYW5pbWF0aW5nWSB8fCBfdGhpcy5faWdub3JlV2hpbGVBbmltYXRpbmcgPT09IHRhcmdldCkge1xuICAgICAgcmV0dXJuIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgfVxuICAgIGlnbm9yZU5leHRDbGljayA9IGZhbHNlO1xuICAgIGlmIChhY3RpdmVTb3J0YWJsZSAmJiAhb3B0aW9ucy5kaXNhYmxlZCAmJiAoaXNPd25lciA/IGNhblNvcnQgfHwgKHJldmVydCA9IHBhcmVudEVsICE9PSByb290RWwpIC8vIFJldmVydGluZyBpdGVtIGludG8gdGhlIG9yaWdpbmFsIGxpc3RcbiAgICA6IHB1dFNvcnRhYmxlID09PSB0aGlzIHx8ICh0aGlzLmxhc3RQdXRNb2RlID0gYWN0aXZlR3JvdXAuY2hlY2tQdWxsKHRoaXMsIGFjdGl2ZVNvcnRhYmxlLCBkcmFnRWwsIGV2dCkpICYmIGdyb3VwLmNoZWNrUHV0KHRoaXMsIGFjdGl2ZVNvcnRhYmxlLCBkcmFnRWwsIGV2dCkpKSB7XG4gICAgICB2ZXJ0aWNhbCA9IHRoaXMuX2dldERpcmVjdGlvbihldnQsIHRhcmdldCkgPT09ICd2ZXJ0aWNhbCc7XG4gICAgICBkcmFnUmVjdCA9IGdldFJlY3QoZHJhZ0VsKTtcbiAgICAgIGRyYWdPdmVyRXZlbnQoJ2RyYWdPdmVyVmFsaWQnKTtcbiAgICAgIGlmIChTb3J0YWJsZS5ldmVudENhbmNlbGVkKSByZXR1cm4gY29tcGxldGVkRmlyZWQ7XG4gICAgICBpZiAocmV2ZXJ0KSB7XG4gICAgICAgIHBhcmVudEVsID0gcm9vdEVsOyAvLyBhY3R1YWxpemF0aW9uXG4gICAgICAgIGNhcHR1cmUoKTtcbiAgICAgICAgdGhpcy5faGlkZUNsb25lKCk7XG4gICAgICAgIGRyYWdPdmVyRXZlbnQoJ3JldmVydCcpO1xuICAgICAgICBpZiAoIVNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHtcbiAgICAgICAgICBpZiAobmV4dEVsKSB7XG4gICAgICAgICAgICByb290RWwuaW5zZXJ0QmVmb3JlKGRyYWdFbCwgbmV4dEVsKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgcm9vdEVsLmFwcGVuZENoaWxkKGRyYWdFbCk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiBjb21wbGV0ZWQodHJ1ZSk7XG4gICAgICB9XG4gICAgICB2YXIgZWxMYXN0Q2hpbGQgPSBsYXN0Q2hpbGQoZWwsIG9wdGlvbnMuZHJhZ2dhYmxlKTtcbiAgICAgIGlmICghZWxMYXN0Q2hpbGQgfHwgX2dob3N0SXNMYXN0KGV2dCwgdmVydGljYWwsIHRoaXMpICYmICFlbExhc3RDaGlsZC5hbmltYXRlZCkge1xuICAgICAgICAvLyBJbnNlcnQgdG8gZW5kIG9mIGxpc3RcblxuICAgICAgICAvLyBJZiBhbHJlYWR5IGF0IGVuZCBvZiBsaXN0OiBEbyBub3QgaW5zZXJ0XG4gICAgICAgIGlmIChlbExhc3RDaGlsZCA9PT0gZHJhZ0VsKSB7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBpZiB0aGVyZSBpcyBhIGxhc3QgZWxlbWVudCwgaXQgaXMgdGhlIHRhcmdldFxuICAgICAgICBpZiAoZWxMYXN0Q2hpbGQgJiYgZWwgPT09IGV2dC50YXJnZXQpIHtcbiAgICAgICAgICB0YXJnZXQgPSBlbExhc3RDaGlsZDtcbiAgICAgICAgfVxuICAgICAgICBpZiAodGFyZ2V0KSB7XG4gICAgICAgICAgdGFyZ2V0UmVjdCA9IGdldFJlY3QodGFyZ2V0KTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoX29uTW92ZShyb290RWwsIGVsLCBkcmFnRWwsIGRyYWdSZWN0LCB0YXJnZXQsIHRhcmdldFJlY3QsIGV2dCwgISF0YXJnZXQpICE9PSBmYWxzZSkge1xuICAgICAgICAgIGNhcHR1cmUoKTtcbiAgICAgICAgICBpZiAoZWxMYXN0Q2hpbGQgJiYgZWxMYXN0Q2hpbGQubmV4dFNpYmxpbmcpIHtcbiAgICAgICAgICAgIC8vIHRoZSBsYXN0IGRyYWdnYWJsZSBlbGVtZW50IGlzIG5vdCB0aGUgbGFzdCBub2RlXG4gICAgICAgICAgICBlbC5pbnNlcnRCZWZvcmUoZHJhZ0VsLCBlbExhc3RDaGlsZC5uZXh0U2libGluZyk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGVsLmFwcGVuZENoaWxkKGRyYWdFbCk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHBhcmVudEVsID0gZWw7IC8vIGFjdHVhbGl6YXRpb25cblxuICAgICAgICAgIGNoYW5nZWQoKTtcbiAgICAgICAgICByZXR1cm4gY29tcGxldGVkKHRydWUpO1xuICAgICAgICB9XG4gICAgICB9IGVsc2UgaWYgKGVsTGFzdENoaWxkICYmIF9naG9zdElzRmlyc3QoZXZ0LCB2ZXJ0aWNhbCwgdGhpcykpIHtcbiAgICAgICAgLy8gSW5zZXJ0IHRvIHN0YXJ0IG9mIGxpc3RcbiAgICAgICAgdmFyIGZpcnN0Q2hpbGQgPSBnZXRDaGlsZChlbCwgMCwgb3B0aW9ucywgdHJ1ZSk7XG4gICAgICAgIGlmIChmaXJzdENoaWxkID09PSBkcmFnRWwpIHtcbiAgICAgICAgICByZXR1cm4gY29tcGxldGVkKGZhbHNlKTtcbiAgICAgICAgfVxuICAgICAgICB0YXJnZXQgPSBmaXJzdENoaWxkO1xuICAgICAgICB0YXJnZXRSZWN0ID0gZ2V0UmVjdCh0YXJnZXQpO1xuICAgICAgICBpZiAoX29uTW92ZShyb290RWwsIGVsLCBkcmFnRWwsIGRyYWdSZWN0LCB0YXJnZXQsIHRhcmdldFJlY3QsIGV2dCwgZmFsc2UpICE9PSBmYWxzZSkge1xuICAgICAgICAgIGNhcHR1cmUoKTtcbiAgICAgICAgICBlbC5pbnNlcnRCZWZvcmUoZHJhZ0VsLCBmaXJzdENoaWxkKTtcbiAgICAgICAgICBwYXJlbnRFbCA9IGVsOyAvLyBhY3R1YWxpemF0aW9uXG5cbiAgICAgICAgICBjaGFuZ2VkKCk7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZCh0cnVlKTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmICh0YXJnZXQucGFyZW50Tm9kZSA9PT0gZWwpIHtcbiAgICAgICAgdGFyZ2V0UmVjdCA9IGdldFJlY3QodGFyZ2V0KTtcbiAgICAgICAgdmFyIGRpcmVjdGlvbiA9IDAsXG4gICAgICAgICAgdGFyZ2V0QmVmb3JlRmlyc3RTd2FwLFxuICAgICAgICAgIGRpZmZlcmVudExldmVsID0gZHJhZ0VsLnBhcmVudE5vZGUgIT09IGVsLFxuICAgICAgICAgIGRpZmZlcmVudFJvd0NvbCA9ICFfZHJhZ0VsSW5Sb3dDb2x1bW4oZHJhZ0VsLmFuaW1hdGVkICYmIGRyYWdFbC50b1JlY3QgfHwgZHJhZ1JlY3QsIHRhcmdldC5hbmltYXRlZCAmJiB0YXJnZXQudG9SZWN0IHx8IHRhcmdldFJlY3QsIHZlcnRpY2FsKSxcbiAgICAgICAgICBzaWRlMSA9IHZlcnRpY2FsID8gJ3RvcCcgOiAnbGVmdCcsXG4gICAgICAgICAgc2Nyb2xsZWRQYXN0VG9wID0gaXNTY3JvbGxlZFBhc3QodGFyZ2V0LCAndG9wJywgJ3RvcCcpIHx8IGlzU2Nyb2xsZWRQYXN0KGRyYWdFbCwgJ3RvcCcsICd0b3AnKSxcbiAgICAgICAgICBzY3JvbGxCZWZvcmUgPSBzY3JvbGxlZFBhc3RUb3AgPyBzY3JvbGxlZFBhc3RUb3Auc2Nyb2xsVG9wIDogdm9pZCAwO1xuICAgICAgICBpZiAobGFzdFRhcmdldCAhPT0gdGFyZ2V0KSB7XG4gICAgICAgICAgdGFyZ2V0QmVmb3JlRmlyc3RTd2FwID0gdGFyZ2V0UmVjdFtzaWRlMV07XG4gICAgICAgICAgcGFzdEZpcnN0SW52ZXJ0VGhyZXNoID0gZmFsc2U7XG4gICAgICAgICAgaXNDaXJjdW1zdGFudGlhbEludmVydCA9ICFkaWZmZXJlbnRSb3dDb2wgJiYgb3B0aW9ucy5pbnZlcnRTd2FwIHx8IGRpZmZlcmVudExldmVsO1xuICAgICAgICB9XG4gICAgICAgIGRpcmVjdGlvbiA9IF9nZXRTd2FwRGlyZWN0aW9uKGV2dCwgdGFyZ2V0LCB0YXJnZXRSZWN0LCB2ZXJ0aWNhbCwgZGlmZmVyZW50Um93Q29sID8gMSA6IG9wdGlvbnMuc3dhcFRocmVzaG9sZCwgb3B0aW9ucy5pbnZlcnRlZFN3YXBUaHJlc2hvbGQgPT0gbnVsbCA/IG9wdGlvbnMuc3dhcFRocmVzaG9sZCA6IG9wdGlvbnMuaW52ZXJ0ZWRTd2FwVGhyZXNob2xkLCBpc0NpcmN1bXN0YW50aWFsSW52ZXJ0LCBsYXN0VGFyZ2V0ID09PSB0YXJnZXQpO1xuICAgICAgICB2YXIgc2libGluZztcbiAgICAgICAgaWYgKGRpcmVjdGlvbiAhPT0gMCkge1xuICAgICAgICAgIC8vIENoZWNrIGlmIHRhcmdldCBpcyBiZXNpZGUgZHJhZ0VsIGluIHJlc3BlY3RpdmUgZGlyZWN0aW9uIChpZ25vcmluZyBoaWRkZW4gZWxlbWVudHMpXG4gICAgICAgICAgdmFyIGRyYWdJbmRleCA9IGluZGV4KGRyYWdFbCk7XG4gICAgICAgICAgZG8ge1xuICAgICAgICAgICAgZHJhZ0luZGV4IC09IGRpcmVjdGlvbjtcbiAgICAgICAgICAgIHNpYmxpbmcgPSBwYXJlbnRFbC5jaGlsZHJlbltkcmFnSW5kZXhdO1xuICAgICAgICAgIH0gd2hpbGUgKHNpYmxpbmcgJiYgKGNzcyhzaWJsaW5nLCAnZGlzcGxheScpID09PSAnbm9uZScgfHwgc2libGluZyA9PT0gZ2hvc3RFbCkpO1xuICAgICAgICB9XG4gICAgICAgIC8vIElmIGRyYWdFbCBpcyBhbHJlYWR5IGJlc2lkZSB0YXJnZXQ6IERvIG5vdCBpbnNlcnRcbiAgICAgICAgaWYgKGRpcmVjdGlvbiA9PT0gMCB8fCBzaWJsaW5nID09PSB0YXJnZXQpIHtcbiAgICAgICAgICByZXR1cm4gY29tcGxldGVkKGZhbHNlKTtcbiAgICAgICAgfVxuICAgICAgICBsYXN0VGFyZ2V0ID0gdGFyZ2V0O1xuICAgICAgICBsYXN0RGlyZWN0aW9uID0gZGlyZWN0aW9uO1xuICAgICAgICB2YXIgbmV4dFNpYmxpbmcgPSB0YXJnZXQubmV4dEVsZW1lbnRTaWJsaW5nLFxuICAgICAgICAgIGFmdGVyID0gZmFsc2U7XG4gICAgICAgIGFmdGVyID0gZGlyZWN0aW9uID09PSAxO1xuICAgICAgICB2YXIgbW92ZVZlY3RvciA9IF9vbk1vdmUocm9vdEVsLCBlbCwgZHJhZ0VsLCBkcmFnUmVjdCwgdGFyZ2V0LCB0YXJnZXRSZWN0LCBldnQsIGFmdGVyKTtcbiAgICAgICAgaWYgKG1vdmVWZWN0b3IgIT09IGZhbHNlKSB7XG4gICAgICAgICAgaWYgKG1vdmVWZWN0b3IgPT09IDEgfHwgbW92ZVZlY3RvciA9PT0gLTEpIHtcbiAgICAgICAgICAgIGFmdGVyID0gbW92ZVZlY3RvciA9PT0gMTtcbiAgICAgICAgICB9XG4gICAgICAgICAgX3NpbGVudCA9IHRydWU7XG4gICAgICAgICAgc2V0VGltZW91dChfdW5zaWxlbnQsIDMwKTtcbiAgICAgICAgICBjYXB0dXJlKCk7XG4gICAgICAgICAgaWYgKGFmdGVyICYmICFuZXh0U2libGluZykge1xuICAgICAgICAgICAgZWwuYXBwZW5kQ2hpbGQoZHJhZ0VsKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdGFyZ2V0LnBhcmVudE5vZGUuaW5zZXJ0QmVmb3JlKGRyYWdFbCwgYWZ0ZXIgPyBuZXh0U2libGluZyA6IHRhcmdldCk7XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgLy8gVW5kbyBjaHJvbWUncyBzY3JvbGwgYWRqdXN0bWVudCAoaGFzIG5vIGVmZmVjdCBvbiBvdGhlciBicm93c2VycylcbiAgICAgICAgICBpZiAoc2Nyb2xsZWRQYXN0VG9wKSB7XG4gICAgICAgICAgICBzY3JvbGxCeShzY3JvbGxlZFBhc3RUb3AsIDAsIHNjcm9sbEJlZm9yZSAtIHNjcm9sbGVkUGFzdFRvcC5zY3JvbGxUb3ApO1xuICAgICAgICAgIH1cbiAgICAgICAgICBwYXJlbnRFbCA9IGRyYWdFbC5wYXJlbnROb2RlOyAvLyBhY3R1YWxpemF0aW9uXG5cbiAgICAgICAgICAvLyBtdXN0IGJlIGRvbmUgYmVmb3JlIGFuaW1hdGlvblxuICAgICAgICAgIGlmICh0YXJnZXRCZWZvcmVGaXJzdFN3YXAgIT09IHVuZGVmaW5lZCAmJiAhaXNDaXJjdW1zdGFudGlhbEludmVydCkge1xuICAgICAgICAgICAgdGFyZ2V0TW92ZURpc3RhbmNlID0gTWF0aC5hYnModGFyZ2V0QmVmb3JlRmlyc3RTd2FwIC0gZ2V0UmVjdCh0YXJnZXQpW3NpZGUxXSk7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNoYW5nZWQoKTtcbiAgICAgICAgICByZXR1cm4gY29tcGxldGVkKHRydWUpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICBpZiAoZWwuY29udGFpbnMoZHJhZ0VsKSkge1xuICAgICAgICByZXR1cm4gY29tcGxldGVkKGZhbHNlKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9LFxuICBfaWdub3JlV2hpbGVBbmltYXRpbmc6IG51bGwsXG4gIF9vZmZNb3ZlRXZlbnRzOiBmdW5jdGlvbiBfb2ZmTW92ZUV2ZW50cygpIHtcbiAgICBvZmYoZG9jdW1lbnQsICdtb3VzZW1vdmUnLCB0aGlzLl9vblRvdWNoTW92ZSk7XG4gICAgb2ZmKGRvY3VtZW50LCAndG91Y2htb3ZlJywgdGhpcy5fb25Ub3VjaE1vdmUpO1xuICAgIG9mZihkb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgdGhpcy5fb25Ub3VjaE1vdmUpO1xuICAgIG9mZihkb2N1bWVudCwgJ2RyYWdvdmVyJywgbmVhcmVzdEVtcHR5SW5zZXJ0RGV0ZWN0RXZlbnQpO1xuICAgIG9mZihkb2N1bWVudCwgJ21vdXNlbW92ZScsIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KTtcbiAgICBvZmYoZG9jdW1lbnQsICd0b3VjaG1vdmUnLCBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudCk7XG4gIH0sXG4gIF9vZmZVcEV2ZW50czogZnVuY3Rpb24gX29mZlVwRXZlbnRzKCkge1xuICAgIHZhciBvd25lckRvY3VtZW50ID0gdGhpcy5lbC5vd25lckRvY3VtZW50O1xuICAgIG9mZihvd25lckRvY3VtZW50LCAnbW91c2V1cCcsIHRoaXMuX29uRHJvcCk7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICd0b3VjaGVuZCcsIHRoaXMuX29uRHJvcCk7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICdwb2ludGVydXAnLCB0aGlzLl9vbkRyb3ApO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAndG91Y2hjYW5jZWwnLCB0aGlzLl9vbkRyb3ApO1xuICAgIG9mZihkb2N1bWVudCwgJ3NlbGVjdHN0YXJ0JywgdGhpcyk7XG4gIH0sXG4gIF9vbkRyb3A6IGZ1bmN0aW9uIF9vbkRyb3AoIC8qKkV2ZW50Ki9ldnQpIHtcbiAgICB2YXIgZWwgPSB0aGlzLmVsLFxuICAgICAgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcblxuICAgIC8vIEdldCB0aGUgaW5kZXggb2YgdGhlIGRyYWdnZWQgZWxlbWVudCB3aXRoaW4gaXRzIHBhcmVudFxuICAgIG5ld0luZGV4ID0gaW5kZXgoZHJhZ0VsKTtcbiAgICBuZXdEcmFnZ2FibGVJbmRleCA9IGluZGV4KGRyYWdFbCwgb3B0aW9ucy5kcmFnZ2FibGUpO1xuICAgIHBsdWdpbkV2ZW50KCdkcm9wJywgdGhpcywge1xuICAgICAgZXZ0OiBldnRcbiAgICB9KTtcbiAgICBwYXJlbnRFbCA9IGRyYWdFbCAmJiBkcmFnRWwucGFyZW50Tm9kZTtcblxuICAgIC8vIEdldCBhZ2FpbiBhZnRlciBwbHVnaW4gZXZlbnRcbiAgICBuZXdJbmRleCA9IGluZGV4KGRyYWdFbCk7XG4gICAgbmV3RHJhZ2dhYmxlSW5kZXggPSBpbmRleChkcmFnRWwsIG9wdGlvbnMuZHJhZ2dhYmxlKTtcbiAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkge1xuICAgICAgdGhpcy5fbnVsbGluZygpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBhd2FpdGluZ0RyYWdTdGFydGVkID0gZmFsc2U7XG4gICAgaXNDaXJjdW1zdGFudGlhbEludmVydCA9IGZhbHNlO1xuICAgIHBhc3RGaXJzdEludmVydFRocmVzaCA9IGZhbHNlO1xuICAgIGNsZWFySW50ZXJ2YWwodGhpcy5fbG9vcElkKTtcbiAgICBjbGVhclRpbWVvdXQodGhpcy5fZHJhZ1N0YXJ0VGltZXIpO1xuICAgIF9jYW5jZWxOZXh0VGljayh0aGlzLmNsb25lSWQpO1xuICAgIF9jYW5jZWxOZXh0VGljayh0aGlzLl9kcmFnU3RhcnRJZCk7XG5cbiAgICAvLyBVbmJpbmQgZXZlbnRzXG4gICAgaWYgKHRoaXMubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgICBvZmYoZG9jdW1lbnQsICdkcm9wJywgdGhpcyk7XG4gICAgICBvZmYoZWwsICdkcmFnc3RhcnQnLCB0aGlzLl9vbkRyYWdTdGFydCk7XG4gICAgfVxuICAgIHRoaXMuX29mZk1vdmVFdmVudHMoKTtcbiAgICB0aGlzLl9vZmZVcEV2ZW50cygpO1xuICAgIGlmIChTYWZhcmkpIHtcbiAgICAgIGNzcyhkb2N1bWVudC5ib2R5LCAndXNlci1zZWxlY3QnLCAnJyk7XG4gICAgfVxuICAgIGNzcyhkcmFnRWwsICd0cmFuc2Zvcm0nLCAnJyk7XG4gICAgaWYgKGV2dCkge1xuICAgICAgaWYgKG1vdmVkKSB7XG4gICAgICAgIGV2dC5jYW5jZWxhYmxlICYmIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAhb3B0aW9ucy5kcm9wQnViYmxlICYmIGV2dC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgIH1cbiAgICAgIGdob3N0RWwgJiYgZ2hvc3RFbC5wYXJlbnROb2RlICYmIGdob3N0RWwucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChnaG9zdEVsKTtcbiAgICAgIGlmIChyb290RWwgPT09IHBhcmVudEVsIHx8IHB1dFNvcnRhYmxlICYmIHB1dFNvcnRhYmxlLmxhc3RQdXRNb2RlICE9PSAnY2xvbmUnKSB7XG4gICAgICAgIC8vIFJlbW92ZSBjbG9uZShzKVxuICAgICAgICBjbG9uZUVsICYmIGNsb25lRWwucGFyZW50Tm9kZSAmJiBjbG9uZUVsLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY2xvbmVFbCk7XG4gICAgICB9XG4gICAgICBpZiAoZHJhZ0VsKSB7XG4gICAgICAgIGlmICh0aGlzLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgICAgICAgIG9mZihkcmFnRWwsICdkcmFnZW5kJywgdGhpcyk7XG4gICAgICAgIH1cbiAgICAgICAgX2Rpc2FibGVEcmFnZ2FibGUoZHJhZ0VsKTtcbiAgICAgICAgZHJhZ0VsLnN0eWxlWyd3aWxsLWNoYW5nZSddID0gJyc7XG5cbiAgICAgICAgLy8gUmVtb3ZlIGNsYXNzZXNcbiAgICAgICAgLy8gZ2hvc3RDbGFzcyBpcyBhZGRlZCBpbiBkcmFnU3RhcnRlZFxuICAgICAgICBpZiAobW92ZWQgJiYgIWF3YWl0aW5nRHJhZ1N0YXJ0ZWQpIHtcbiAgICAgICAgICB0b2dnbGVDbGFzcyhkcmFnRWwsIHB1dFNvcnRhYmxlID8gcHV0U29ydGFibGUub3B0aW9ucy5naG9zdENsYXNzIDogdGhpcy5vcHRpb25zLmdob3N0Q2xhc3MsIGZhbHNlKTtcbiAgICAgICAgfVxuICAgICAgICB0b2dnbGVDbGFzcyhkcmFnRWwsIHRoaXMub3B0aW9ucy5jaG9zZW5DbGFzcywgZmFsc2UpO1xuXG4gICAgICAgIC8vIERyYWcgc3RvcCBldmVudFxuICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgICAgbmFtZTogJ3VuY2hvb3NlJyxcbiAgICAgICAgICB0b0VsOiBwYXJlbnRFbCxcbiAgICAgICAgICBuZXdJbmRleDogbnVsbCxcbiAgICAgICAgICBuZXdEcmFnZ2FibGVJbmRleDogbnVsbCxcbiAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgfSk7XG4gICAgICAgIGlmIChyb290RWwgIT09IHBhcmVudEVsKSB7XG4gICAgICAgICAgaWYgKG5ld0luZGV4ID49IDApIHtcbiAgICAgICAgICAgIC8vIEFkZCBldmVudFxuICAgICAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgICByb290RWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICBuYW1lOiAnYWRkJyxcbiAgICAgICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgICAgIGZyb21FbDogcm9vdEVsLFxuICAgICAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvLyBSZW1vdmUgZXZlbnRcbiAgICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgICAgICAgIG5hbWU6ICdyZW1vdmUnLFxuICAgICAgICAgICAgICB0b0VsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy8gZHJhZyBmcm9tIG9uZSBsaXN0IGFuZCBkcm9wIGludG8gYW5vdGhlclxuICAgICAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgICByb290RWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICBuYW1lOiAnc29ydCcsXG4gICAgICAgICAgICAgIHRvRWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICBmcm9tRWw6IHJvb3RFbCxcbiAgICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgICAgICAgIG5hbWU6ICdzb3J0JyxcbiAgICAgICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHB1dFNvcnRhYmxlICYmIHB1dFNvcnRhYmxlLnNhdmUoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBpZiAobmV3SW5kZXggIT09IG9sZEluZGV4KSB7XG4gICAgICAgICAgICBpZiAobmV3SW5kZXggPj0gMCkge1xuICAgICAgICAgICAgICAvLyBkcmFnICYgZHJvcCB3aXRoaW4gdGhlIHNhbWUgbGlzdFxuICAgICAgICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgICAgICAgICAgbmFtZTogJ3VwZGF0ZScsXG4gICAgICAgICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICAgICAgc29ydGFibGU6IHRoaXMsXG4gICAgICAgICAgICAgICAgbmFtZTogJ3NvcnQnLFxuICAgICAgICAgICAgICAgIHRvRWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKFNvcnRhYmxlLmFjdGl2ZSkge1xuICAgICAgICAgIC8qIGpzaGludCBlcW51bGw6dHJ1ZSAqL1xuICAgICAgICAgIGlmIChuZXdJbmRleCA9PSBudWxsIHx8IG5ld0luZGV4ID09PSAtMSkge1xuICAgICAgICAgICAgbmV3SW5kZXggPSBvbGRJbmRleDtcbiAgICAgICAgICAgIG5ld0RyYWdnYWJsZUluZGV4ID0gb2xkRHJhZ2dhYmxlSW5kZXg7XG4gICAgICAgICAgfVxuICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgICAgbmFtZTogJ2VuZCcsXG4gICAgICAgICAgICB0b0VsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgLy8gU2F2ZSBzb3J0aW5nXG4gICAgICAgICAgdGhpcy5zYXZlKCk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9XG4gICAgdGhpcy5fbnVsbGluZygpO1xuICB9LFxuICBfbnVsbGluZzogZnVuY3Rpb24gX251bGxpbmcoKSB7XG4gICAgcGx1Z2luRXZlbnQoJ251bGxpbmcnLCB0aGlzKTtcbiAgICByb290RWwgPSBkcmFnRWwgPSBwYXJlbnRFbCA9IGdob3N0RWwgPSBuZXh0RWwgPSBjbG9uZUVsID0gbGFzdERvd25FbCA9IGNsb25lSGlkZGVuID0gdGFwRXZ0ID0gdG91Y2hFdnQgPSBtb3ZlZCA9IG5ld0luZGV4ID0gbmV3RHJhZ2dhYmxlSW5kZXggPSBvbGRJbmRleCA9IG9sZERyYWdnYWJsZUluZGV4ID0gbGFzdFRhcmdldCA9IGxhc3REaXJlY3Rpb24gPSBwdXRTb3J0YWJsZSA9IGFjdGl2ZUdyb3VwID0gU29ydGFibGUuZHJhZ2dlZCA9IFNvcnRhYmxlLmdob3N0ID0gU29ydGFibGUuY2xvbmUgPSBTb3J0YWJsZS5hY3RpdmUgPSBudWxsO1xuICAgIHNhdmVkSW5wdXRDaGVja2VkLmZvckVhY2goZnVuY3Rpb24gKGVsKSB7XG4gICAgICBlbC5jaGVja2VkID0gdHJ1ZTtcbiAgICB9KTtcbiAgICBzYXZlZElucHV0Q2hlY2tlZC5sZW5ndGggPSBsYXN0RHggPSBsYXN0RHkgPSAwO1xuICB9LFxuICBoYW5kbGVFdmVudDogZnVuY3Rpb24gaGFuZGxlRXZlbnQoIC8qKkV2ZW50Ki9ldnQpIHtcbiAgICBzd2l0Y2ggKGV2dC50eXBlKSB7XG4gICAgICBjYXNlICdkcm9wJzpcbiAgICAgIGNhc2UgJ2RyYWdlbmQnOlxuICAgICAgICB0aGlzLl9vbkRyb3AoZXZ0KTtcbiAgICAgICAgYnJlYWs7XG4gICAgICBjYXNlICdkcmFnZW50ZXInOlxuICAgICAgY2FzZSAnZHJhZ292ZXInOlxuICAgICAgICBpZiAoZHJhZ0VsKSB7XG4gICAgICAgICAgdGhpcy5fb25EcmFnT3ZlcihldnQpO1xuICAgICAgICAgIF9nbG9iYWxEcmFnT3ZlcihldnQpO1xuICAgICAgICB9XG4gICAgICAgIGJyZWFrO1xuICAgICAgY2FzZSAnc2VsZWN0c3RhcnQnOlxuICAgICAgICBldnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgYnJlYWs7XG4gICAgfVxuICB9LFxuICAvKipcclxuICAgKiBTZXJpYWxpemVzIHRoZSBpdGVtIGludG8gYW4gYXJyYXkgb2Ygc3RyaW5nLlxyXG4gICAqIEByZXR1cm5zIHtTdHJpbmdbXX1cclxuICAgKi9cbiAgdG9BcnJheTogZnVuY3Rpb24gdG9BcnJheSgpIHtcbiAgICB2YXIgb3JkZXIgPSBbXSxcbiAgICAgIGVsLFxuICAgICAgY2hpbGRyZW4gPSB0aGlzLmVsLmNoaWxkcmVuLFxuICAgICAgaSA9IDAsXG4gICAgICBuID0gY2hpbGRyZW4ubGVuZ3RoLFxuICAgICAgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcbiAgICBmb3IgKDsgaSA8IG47IGkrKykge1xuICAgICAgZWwgPSBjaGlsZHJlbltpXTtcbiAgICAgIGlmIChjbG9zZXN0KGVsLCBvcHRpb25zLmRyYWdnYWJsZSwgdGhpcy5lbCwgZmFsc2UpKSB7XG4gICAgICAgIG9yZGVyLnB1c2goZWwuZ2V0QXR0cmlidXRlKG9wdGlvbnMuZGF0YUlkQXR0cikgfHwgX2dlbmVyYXRlSWQoZWwpKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIG9yZGVyO1xuICB9LFxuICAvKipcclxuICAgKiBTb3J0cyB0aGUgZWxlbWVudHMgYWNjb3JkaW5nIHRvIHRoZSBhcnJheS5cclxuICAgKiBAcGFyYW0gIHtTdHJpbmdbXX0gIG9yZGVyICBvcmRlciBvZiB0aGUgaXRlbXNcclxuICAgKi9cbiAgc29ydDogZnVuY3Rpb24gc29ydChvcmRlciwgdXNlQW5pbWF0aW9uKSB7XG4gICAgdmFyIGl0ZW1zID0ge30sXG4gICAgICByb290RWwgPSB0aGlzLmVsO1xuICAgIHRoaXMudG9BcnJheSgpLmZvckVhY2goZnVuY3Rpb24gKGlkLCBpKSB7XG4gICAgICB2YXIgZWwgPSByb290RWwuY2hpbGRyZW5baV07XG4gICAgICBpZiAoY2xvc2VzdChlbCwgdGhpcy5vcHRpb25zLmRyYWdnYWJsZSwgcm9vdEVsLCBmYWxzZSkpIHtcbiAgICAgICAgaXRlbXNbaWRdID0gZWw7XG4gICAgICB9XG4gICAgfSwgdGhpcyk7XG4gICAgdXNlQW5pbWF0aW9uICYmIHRoaXMuY2FwdHVyZUFuaW1hdGlvblN0YXRlKCk7XG4gICAgb3JkZXIuZm9yRWFjaChmdW5jdGlvbiAoaWQpIHtcbiAgICAgIGlmIChpdGVtc1tpZF0pIHtcbiAgICAgICAgcm9vdEVsLnJlbW92ZUNoaWxkKGl0ZW1zW2lkXSk7XG4gICAgICAgIHJvb3RFbC5hcHBlbmRDaGlsZChpdGVtc1tpZF0pO1xuICAgICAgfVxuICAgIH0pO1xuICAgIHVzZUFuaW1hdGlvbiAmJiB0aGlzLmFuaW1hdGVBbGwoKTtcbiAgfSxcbiAgLyoqXHJcbiAgICogU2F2ZSB0aGUgY3VycmVudCBzb3J0aW5nXHJcbiAgICovXG4gIHNhdmU6IGZ1bmN0aW9uIHNhdmUoKSB7XG4gICAgdmFyIHN0b3JlID0gdGhpcy5vcHRpb25zLnN0b3JlO1xuICAgIHN0b3JlICYmIHN0b3JlLnNldCAmJiBzdG9yZS5zZXQodGhpcyk7XG4gIH0sXG4gIC8qKlxyXG4gICAqIEZvciBlYWNoIGVsZW1lbnQgaW4gdGhlIHNldCwgZ2V0IHRoZSBmaXJzdCBlbGVtZW50IHRoYXQgbWF0Y2hlcyB0aGUgc2VsZWN0b3IgYnkgdGVzdGluZyB0aGUgZWxlbWVudCBpdHNlbGYgYW5kIHRyYXZlcnNpbmcgdXAgdGhyb3VnaCBpdHMgYW5jZXN0b3JzIGluIHRoZSBET00gdHJlZS5cclxuICAgKiBAcGFyYW0gICB7SFRNTEVsZW1lbnR9ICBlbFxyXG4gICAqIEBwYXJhbSAgIHtTdHJpbmd9ICAgICAgIFtzZWxlY3Rvcl0gIGRlZmF1bHQ6IGBvcHRpb25zLmRyYWdnYWJsZWBcclxuICAgKiBAcmV0dXJucyB7SFRNTEVsZW1lbnR8bnVsbH1cclxuICAgKi9cbiAgY2xvc2VzdDogZnVuY3Rpb24gY2xvc2VzdCQxKGVsLCBzZWxlY3Rvcikge1xuICAgIHJldHVybiBjbG9zZXN0KGVsLCBzZWxlY3RvciB8fCB0aGlzLm9wdGlvbnMuZHJhZ2dhYmxlLCB0aGlzLmVsLCBmYWxzZSk7XG4gIH0sXG4gIC8qKlxyXG4gICAqIFNldC9nZXQgb3B0aW9uXHJcbiAgICogQHBhcmFtICAge3N0cmluZ30gbmFtZVxyXG4gICAqIEBwYXJhbSAgIHsqfSAgICAgIFt2YWx1ZV1cclxuICAgKiBAcmV0dXJucyB7Kn1cclxuICAgKi9cbiAgb3B0aW9uOiBmdW5jdGlvbiBvcHRpb24obmFtZSwgdmFsdWUpIHtcbiAgICB2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcbiAgICBpZiAodmFsdWUgPT09IHZvaWQgMCkge1xuICAgICAgcmV0dXJuIG9wdGlvbnNbbmFtZV07XG4gICAgfSBlbHNlIHtcbiAgICAgIHZhciBtb2RpZmllZFZhbHVlID0gUGx1Z2luTWFuYWdlci5tb2RpZnlPcHRpb24odGhpcywgbmFtZSwgdmFsdWUpO1xuICAgICAgaWYgKHR5cGVvZiBtb2RpZmllZFZhbHVlICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICBvcHRpb25zW25hbWVdID0gbW9kaWZpZWRWYWx1ZTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIG9wdGlvbnNbbmFtZV0gPSB2YWx1ZTtcbiAgICAgIH1cbiAgICAgIGlmIChuYW1lID09PSAnZ3JvdXAnKSB7XG4gICAgICAgIF9wcmVwYXJlR3JvdXAob3B0aW9ucyk7XG4gICAgICB9XG4gICAgfVxuICB9LFxuICAvKipcclxuICAgKiBEZXN0cm95XHJcbiAgICovXG4gIGRlc3Ryb3k6IGZ1bmN0aW9uIGRlc3Ryb3koKSB7XG4gICAgcGx1Z2luRXZlbnQoJ2Rlc3Ryb3knLCB0aGlzKTtcbiAgICB2YXIgZWwgPSB0aGlzLmVsO1xuICAgIGVsW2V4cGFuZG9dID0gbnVsbDtcbiAgICBvZmYoZWwsICdtb3VzZWRvd24nLCB0aGlzLl9vblRhcFN0YXJ0KTtcbiAgICBvZmYoZWwsICd0b3VjaHN0YXJ0JywgdGhpcy5fb25UYXBTdGFydCk7XG4gICAgb2ZmKGVsLCAncG9pbnRlcmRvd24nLCB0aGlzLl9vblRhcFN0YXJ0KTtcbiAgICBpZiAodGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgIG9mZihlbCwgJ2RyYWdvdmVyJywgdGhpcyk7XG4gICAgICBvZmYoZWwsICdkcmFnZW50ZXInLCB0aGlzKTtcbiAgICB9XG4gICAgLy8gUmVtb3ZlIGRyYWdnYWJsZSBhdHRyaWJ1dGVzXG4gICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChlbC5xdWVyeVNlbGVjdG9yQWxsKCdbZHJhZ2dhYmxlXScpLCBmdW5jdGlvbiAoZWwpIHtcbiAgICAgIGVsLnJlbW92ZUF0dHJpYnV0ZSgnZHJhZ2dhYmxlJyk7XG4gICAgfSk7XG4gICAgdGhpcy5fb25Ecm9wKCk7XG4gICAgdGhpcy5fZGlzYWJsZURlbGF5ZWREcmFnRXZlbnRzKCk7XG4gICAgc29ydGFibGVzLnNwbGljZShzb3J0YWJsZXMuaW5kZXhPZih0aGlzLmVsKSwgMSk7XG4gICAgdGhpcy5lbCA9IGVsID0gbnVsbDtcbiAgfSxcbiAgX2hpZGVDbG9uZTogZnVuY3Rpb24gX2hpZGVDbG9uZSgpIHtcbiAgICBpZiAoIWNsb25lSGlkZGVuKSB7XG4gICAgICBwbHVnaW5FdmVudCgnaGlkZUNsb25lJywgdGhpcyk7XG4gICAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkgcmV0dXJuO1xuICAgICAgY3NzKGNsb25lRWwsICdkaXNwbGF5JywgJ25vbmUnKTtcbiAgICAgIGlmICh0aGlzLm9wdGlvbnMucmVtb3ZlQ2xvbmVPbkhpZGUgJiYgY2xvbmVFbC5wYXJlbnROb2RlKSB7XG4gICAgICAgIGNsb25lRWwucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChjbG9uZUVsKTtcbiAgICAgIH1cbiAgICAgIGNsb25lSGlkZGVuID0gdHJ1ZTtcbiAgICB9XG4gIH0sXG4gIF9zaG93Q2xvbmU6IGZ1bmN0aW9uIF9zaG93Q2xvbmUocHV0U29ydGFibGUpIHtcbiAgICBpZiAocHV0U29ydGFibGUubGFzdFB1dE1vZGUgIT09ICdjbG9uZScpIHtcbiAgICAgIHRoaXMuX2hpZGVDbG9uZSgpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBpZiAoY2xvbmVIaWRkZW4pIHtcbiAgICAgIHBsdWdpbkV2ZW50KCdzaG93Q2xvbmUnLCB0aGlzKTtcbiAgICAgIGlmIChTb3J0YWJsZS5ldmVudENhbmNlbGVkKSByZXR1cm47XG5cbiAgICAgIC8vIHNob3cgY2xvbmUgYXQgZHJhZ0VsIG9yIG9yaWdpbmFsIHBvc2l0aW9uXG4gICAgICBpZiAoZHJhZ0VsLnBhcmVudE5vZGUgPT0gcm9vdEVsICYmICF0aGlzLm9wdGlvbnMuZ3JvdXAucmV2ZXJ0Q2xvbmUpIHtcbiAgICAgICAgcm9vdEVsLmluc2VydEJlZm9yZShjbG9uZUVsLCBkcmFnRWwpO1xuICAgICAgfSBlbHNlIGlmIChuZXh0RWwpIHtcbiAgICAgICAgcm9vdEVsLmluc2VydEJlZm9yZShjbG9uZUVsLCBuZXh0RWwpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcm9vdEVsLmFwcGVuZENoaWxkKGNsb25lRWwpO1xuICAgICAgfVxuICAgICAgaWYgKHRoaXMub3B0aW9ucy5ncm91cC5yZXZlcnRDbG9uZSkge1xuICAgICAgICB0aGlzLmFuaW1hdGUoZHJhZ0VsLCBjbG9uZUVsKTtcbiAgICAgIH1cbiAgICAgIGNzcyhjbG9uZUVsLCAnZGlzcGxheScsICcnKTtcbiAgICAgIGNsb25lSGlkZGVuID0gZmFsc2U7XG4gICAgfVxuICB9XG59O1xuZnVuY3Rpb24gX2dsb2JhbERyYWdPdmVyKCAvKipFdmVudCovZXZ0KSB7XG4gIGlmIChldnQuZGF0YVRyYW5zZmVyKSB7XG4gICAgZXZ0LmRhdGFUcmFuc2Zlci5kcm9wRWZmZWN0ID0gJ21vdmUnO1xuICB9XG4gIGV2dC5jYW5jZWxhYmxlICYmIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xufVxuZnVuY3Rpb24gX29uTW92ZShmcm9tRWwsIHRvRWwsIGRyYWdFbCwgZHJhZ1JlY3QsIHRhcmdldEVsLCB0YXJnZXRSZWN0LCBvcmlnaW5hbEV2ZW50LCB3aWxsSW5zZXJ0QWZ0ZXIpIHtcbiAgdmFyIGV2dCxcbiAgICBzb3J0YWJsZSA9IGZyb21FbFtleHBhbmRvXSxcbiAgICBvbk1vdmVGbiA9IHNvcnRhYmxlLm9wdGlvbnMub25Nb3ZlLFxuICAgIHJldFZhbDtcbiAgLy8gU3VwcG9ydCBmb3IgbmV3IEN1c3RvbUV2ZW50IGZlYXR1cmVcbiAgaWYgKHdpbmRvdy5DdXN0b21FdmVudCAmJiAhSUUxMU9yTGVzcyAmJiAhRWRnZSkge1xuICAgIGV2dCA9IG5ldyBDdXN0b21FdmVudCgnbW92ZScsIHtcbiAgICAgIGJ1YmJsZXM6IHRydWUsXG4gICAgICBjYW5jZWxhYmxlOiB0cnVlXG4gICAgfSk7XG4gIH0gZWxzZSB7XG4gICAgZXZ0ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ0V2ZW50Jyk7XG4gICAgZXZ0LmluaXRFdmVudCgnbW92ZScsIHRydWUsIHRydWUpO1xuICB9XG4gIGV2dC50byA9IHRvRWw7XG4gIGV2dC5mcm9tID0gZnJvbUVsO1xuICBldnQuZHJhZ2dlZCA9IGRyYWdFbDtcbiAgZXZ0LmRyYWdnZWRSZWN0ID0gZHJhZ1JlY3Q7XG4gIGV2dC5yZWxhdGVkID0gdGFyZ2V0RWwgfHwgdG9FbDtcbiAgZXZ0LnJlbGF0ZWRSZWN0ID0gdGFyZ2V0UmVjdCB8fCBnZXRSZWN0KHRvRWwpO1xuICBldnQud2lsbEluc2VydEFmdGVyID0gd2lsbEluc2VydEFmdGVyO1xuICBldnQub3JpZ2luYWxFdmVudCA9IG9yaWdpbmFsRXZlbnQ7XG4gIGZyb21FbC5kaXNwYXRjaEV2ZW50KGV2dCk7XG4gIGlmIChvbk1vdmVGbikge1xuICAgIHJldFZhbCA9IG9uTW92ZUZuLmNhbGwoc29ydGFibGUsIGV2dCwgb3JpZ2luYWxFdmVudCk7XG4gIH1cbiAgcmV0dXJuIHJldFZhbDtcbn1cbmZ1bmN0aW9uIF9kaXNhYmxlRHJhZ2dhYmxlKGVsKSB7XG4gIGVsLmRyYWdnYWJsZSA9IGZhbHNlO1xufVxuZnVuY3Rpb24gX3Vuc2lsZW50KCkge1xuICBfc2lsZW50ID0gZmFsc2U7XG59XG5mdW5jdGlvbiBfZ2hvc3RJc0ZpcnN0KGV2dCwgdmVydGljYWwsIHNvcnRhYmxlKSB7XG4gIHZhciBmaXJzdEVsUmVjdCA9IGdldFJlY3QoZ2V0Q2hpbGQoc29ydGFibGUuZWwsIDAsIHNvcnRhYmxlLm9wdGlvbnMsIHRydWUpKTtcbiAgdmFyIGNoaWxkQ29udGFpbmluZ1JlY3QgPSBnZXRDaGlsZENvbnRhaW5pbmdSZWN0RnJvbUVsZW1lbnQoc29ydGFibGUuZWwsIHNvcnRhYmxlLm9wdGlvbnMsIGdob3N0RWwpO1xuICB2YXIgc3BhY2VyID0gMTA7XG4gIHJldHVybiB2ZXJ0aWNhbCA/IGV2dC5jbGllbnRYIDwgY2hpbGRDb250YWluaW5nUmVjdC5sZWZ0IC0gc3BhY2VyIHx8IGV2dC5jbGllbnRZIDwgZmlyc3RFbFJlY3QudG9wICYmIGV2dC5jbGllbnRYIDwgZmlyc3RFbFJlY3QucmlnaHQgOiBldnQuY2xpZW50WSA8IGNoaWxkQ29udGFpbmluZ1JlY3QudG9wIC0gc3BhY2VyIHx8IGV2dC5jbGllbnRZIDwgZmlyc3RFbFJlY3QuYm90dG9tICYmIGV2dC5jbGllbnRYIDwgZmlyc3RFbFJlY3QubGVmdDtcbn1cbmZ1bmN0aW9uIF9naG9zdElzTGFzdChldnQsIHZlcnRpY2FsLCBzb3J0YWJsZSkge1xuICB2YXIgbGFzdEVsUmVjdCA9IGdldFJlY3QobGFzdENoaWxkKHNvcnRhYmxlLmVsLCBzb3J0YWJsZS5vcHRpb25zLmRyYWdnYWJsZSkpO1xuICB2YXIgY2hpbGRDb250YWluaW5nUmVjdCA9IGdldENoaWxkQ29udGFpbmluZ1JlY3RGcm9tRWxlbWVudChzb3J0YWJsZS5lbCwgc29ydGFibGUub3B0aW9ucywgZ2hvc3RFbCk7XG4gIHZhciBzcGFjZXIgPSAxMDtcbiAgcmV0dXJuIHZlcnRpY2FsID8gZXZ0LmNsaWVudFggPiBjaGlsZENvbnRhaW5pbmdSZWN0LnJpZ2h0ICsgc3BhY2VyIHx8IGV2dC5jbGllbnRZID4gbGFzdEVsUmVjdC5ib3R0b20gJiYgZXZ0LmNsaWVudFggPiBsYXN0RWxSZWN0LmxlZnQgOiBldnQuY2xpZW50WSA+IGNoaWxkQ29udGFpbmluZ1JlY3QuYm90dG9tICsgc3BhY2VyIHx8IGV2dC5jbGllbnRYID4gbGFzdEVsUmVjdC5yaWdodCAmJiBldnQuY2xpZW50WSA+IGxhc3RFbFJlY3QudG9wO1xufVxuZnVuY3Rpb24gX2dldFN3YXBEaXJlY3Rpb24oZXZ0LCB0YXJnZXQsIHRhcmdldFJlY3QsIHZlcnRpY2FsLCBzd2FwVGhyZXNob2xkLCBpbnZlcnRlZFN3YXBUaHJlc2hvbGQsIGludmVydFN3YXAsIGlzTGFzdFRhcmdldCkge1xuICB2YXIgbW91c2VPbkF4aXMgPSB2ZXJ0aWNhbCA/IGV2dC5jbGllbnRZIDogZXZ0LmNsaWVudFgsXG4gICAgdGFyZ2V0TGVuZ3RoID0gdmVydGljYWwgPyB0YXJnZXRSZWN0LmhlaWdodCA6IHRhcmdldFJlY3Qud2lkdGgsXG4gICAgdGFyZ2V0UzEgPSB2ZXJ0aWNhbCA/IHRhcmdldFJlY3QudG9wIDogdGFyZ2V0UmVjdC5sZWZ0LFxuICAgIHRhcmdldFMyID0gdmVydGljYWwgPyB0YXJnZXRSZWN0LmJvdHRvbSA6IHRhcmdldFJlY3QucmlnaHQsXG4gICAgaW52ZXJ0ID0gZmFsc2U7XG4gIGlmICghaW52ZXJ0U3dhcCkge1xuICAgIC8vIE5ldmVyIGludmVydCBvciBjcmVhdGUgZHJhZ0VsIHNoYWRvdyB3aGVuIHRhcmdldCBtb3ZlbWVuZXQgY2F1c2VzIG1vdXNlIHRvIG1vdmUgcGFzdCB0aGUgZW5kIG9mIHJlZ3VsYXIgc3dhcFRocmVzaG9sZFxuICAgIGlmIChpc0xhc3RUYXJnZXQgJiYgdGFyZ2V0TW92ZURpc3RhbmNlIDwgdGFyZ2V0TGVuZ3RoICogc3dhcFRocmVzaG9sZCkge1xuICAgICAgLy8gbXVsdGlwbGllZCBvbmx5IGJ5IHN3YXBUaHJlc2hvbGQgYmVjYXVzZSBtb3VzZSB3aWxsIGFscmVhZHkgYmUgaW5zaWRlIHRhcmdldCBieSAoMSAtIHRocmVzaG9sZCkgKiB0YXJnZXRMZW5ndGggLyAyXG4gICAgICAvLyBjaGVjayBpZiBwYXN0IGZpcnN0IGludmVydCB0aHJlc2hvbGQgb24gc2lkZSBvcHBvc2l0ZSBvZiBsYXN0RGlyZWN0aW9uXG4gICAgICBpZiAoIXBhc3RGaXJzdEludmVydFRocmVzaCAmJiAobGFzdERpcmVjdGlvbiA9PT0gMSA/IG1vdXNlT25BeGlzID4gdGFyZ2V0UzEgKyB0YXJnZXRMZW5ndGggKiBpbnZlcnRlZFN3YXBUaHJlc2hvbGQgLyAyIDogbW91c2VPbkF4aXMgPCB0YXJnZXRTMiAtIHRhcmdldExlbmd0aCAqIGludmVydGVkU3dhcFRocmVzaG9sZCAvIDIpKSB7XG4gICAgICAgIC8vIHBhc3QgZmlyc3QgaW52ZXJ0IHRocmVzaG9sZCwgZG8gbm90IHJlc3RyaWN0IGludmVydGVkIHRocmVzaG9sZCB0byBkcmFnRWwgc2hhZG93XG4gICAgICAgIHBhc3RGaXJzdEludmVydFRocmVzaCA9IHRydWU7XG4gICAgICB9XG4gICAgICBpZiAoIXBhc3RGaXJzdEludmVydFRocmVzaCkge1xuICAgICAgICAvLyBkcmFnRWwgc2hhZG93ICh0YXJnZXQgbW92ZSBkaXN0YW5jZSBzaGFkb3cpXG4gICAgICAgIGlmIChsYXN0RGlyZWN0aW9uID09PSAxID8gbW91c2VPbkF4aXMgPCB0YXJnZXRTMSArIHRhcmdldE1vdmVEaXN0YW5jZSAvLyBvdmVyIGRyYWdFbCBzaGFkb3dcbiAgICAgICAgOiBtb3VzZU9uQXhpcyA+IHRhcmdldFMyIC0gdGFyZ2V0TW92ZURpc3RhbmNlKSB7XG4gICAgICAgICAgcmV0dXJuIC1sYXN0RGlyZWN0aW9uO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBpbnZlcnQgPSB0cnVlO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICAvLyBSZWd1bGFyXG4gICAgICBpZiAobW91c2VPbkF4aXMgPiB0YXJnZXRTMSArIHRhcmdldExlbmd0aCAqICgxIC0gc3dhcFRocmVzaG9sZCkgLyAyICYmIG1vdXNlT25BeGlzIDwgdGFyZ2V0UzIgLSB0YXJnZXRMZW5ndGggKiAoMSAtIHN3YXBUaHJlc2hvbGQpIC8gMikge1xuICAgICAgICByZXR1cm4gX2dldEluc2VydERpcmVjdGlvbih0YXJnZXQpO1xuICAgICAgfVxuICAgIH1cbiAgfVxuICBpbnZlcnQgPSBpbnZlcnQgfHwgaW52ZXJ0U3dhcDtcbiAgaWYgKGludmVydCkge1xuICAgIC8vIEludmVydCBvZiByZWd1bGFyXG4gICAgaWYgKG1vdXNlT25BeGlzIDwgdGFyZ2V0UzEgKyB0YXJnZXRMZW5ndGggKiBpbnZlcnRlZFN3YXBUaHJlc2hvbGQgLyAyIHx8IG1vdXNlT25BeGlzID4gdGFyZ2V0UzIgLSB0YXJnZXRMZW5ndGggKiBpbnZlcnRlZFN3YXBUaHJlc2hvbGQgLyAyKSB7XG4gICAgICByZXR1cm4gbW91c2VPbkF4aXMgPiB0YXJnZXRTMSArIHRhcmdldExlbmd0aCAvIDIgPyAxIDogLTE7XG4gICAgfVxuICB9XG4gIHJldHVybiAwO1xufVxuXG4vKipcclxuICogR2V0cyB0aGUgZGlyZWN0aW9uIGRyYWdFbCBtdXN0IGJlIHN3YXBwZWQgcmVsYXRpdmUgdG8gdGFyZ2V0IGluIG9yZGVyIHRvIG1ha2UgaXRcclxuICogc2VlbSB0aGF0IGRyYWdFbCBoYXMgYmVlbiBcImluc2VydGVkXCIgaW50byB0aGF0IGVsZW1lbnQncyBwb3NpdGlvblxyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gdGFyZ2V0ICAgICAgIFRoZSB0YXJnZXQgd2hvc2UgcG9zaXRpb24gZHJhZ0VsIGlzIGJlaW5nIGluc2VydGVkIGF0XHJcbiAqIEByZXR1cm4ge051bWJlcn0gICAgICAgICAgICAgICAgICAgRGlyZWN0aW9uIGRyYWdFbCBtdXN0IGJlIHN3YXBwZWRcclxuICovXG5mdW5jdGlvbiBfZ2V0SW5zZXJ0RGlyZWN0aW9uKHRhcmdldCkge1xuICBpZiAoaW5kZXgoZHJhZ0VsKSA8IGluZGV4KHRhcmdldCkpIHtcbiAgICByZXR1cm4gMTtcbiAgfSBlbHNlIHtcbiAgICByZXR1cm4gLTE7XG4gIH1cbn1cblxuLyoqXHJcbiAqIEdlbmVyYXRlIGlkXHJcbiAqIEBwYXJhbSAgIHtIVE1MRWxlbWVudH0gZWxcclxuICogQHJldHVybnMge1N0cmluZ31cclxuICogQHByaXZhdGVcclxuICovXG5mdW5jdGlvbiBfZ2VuZXJhdGVJZChlbCkge1xuICB2YXIgc3RyID0gZWwudGFnTmFtZSArIGVsLmNsYXNzTmFtZSArIGVsLnNyYyArIGVsLmhyZWYgKyBlbC50ZXh0Q29udGVudCxcbiAgICBpID0gc3RyLmxlbmd0aCxcbiAgICBzdW0gPSAwO1xuICB3aGlsZSAoaS0tKSB7XG4gICAgc3VtICs9IHN0ci5jaGFyQ29kZUF0KGkpO1xuICB9XG4gIHJldHVybiBzdW0udG9TdHJpbmcoMzYpO1xufVxuZnVuY3Rpb24gX3NhdmVJbnB1dENoZWNrZWRTdGF0ZShyb290KSB7XG4gIHNhdmVkSW5wdXRDaGVja2VkLmxlbmd0aCA9IDA7XG4gIHZhciBpbnB1dHMgPSByb290LmdldEVsZW1lbnRzQnlUYWdOYW1lKCdpbnB1dCcpO1xuICB2YXIgaWR4ID0gaW5wdXRzLmxlbmd0aDtcbiAgd2hpbGUgKGlkeC0tKSB7XG4gICAgdmFyIGVsID0gaW5wdXRzW2lkeF07XG4gICAgZWwuY2hlY2tlZCAmJiBzYXZlZElucHV0Q2hlY2tlZC5wdXNoKGVsKTtcbiAgfVxufVxuZnVuY3Rpb24gX25leHRUaWNrKGZuKSB7XG4gIHJldHVybiBzZXRUaW1lb3V0KGZuLCAwKTtcbn1cbmZ1bmN0aW9uIF9jYW5jZWxOZXh0VGljayhpZCkge1xuICByZXR1cm4gY2xlYXJUaW1lb3V0KGlkKTtcbn1cblxuLy8gRml4ZWQgIzk3MzpcbmlmIChkb2N1bWVudEV4aXN0cykge1xuICBvbihkb2N1bWVudCwgJ3RvdWNobW92ZScsIGZ1bmN0aW9uIChldnQpIHtcbiAgICBpZiAoKFNvcnRhYmxlLmFjdGl2ZSB8fCBhd2FpdGluZ0RyYWdTdGFydGVkKSAmJiBldnQuY2FuY2VsYWJsZSkge1xuICAgICAgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgfVxuICB9KTtcbn1cblxuLy8gRXhwb3J0IHV0aWxzXG5Tb3J0YWJsZS51dGlscyA9IHtcbiAgb246IG9uLFxuICBvZmY6IG9mZixcbiAgY3NzOiBjc3MsXG4gIGZpbmQ6IGZpbmQsXG4gIGlzOiBmdW5jdGlvbiBpcyhlbCwgc2VsZWN0b3IpIHtcbiAgICByZXR1cm4gISFjbG9zZXN0KGVsLCBzZWxlY3RvciwgZWwsIGZhbHNlKTtcbiAgfSxcbiAgZXh0ZW5kOiBleHRlbmQsXG4gIHRocm90dGxlOiB0aHJvdHRsZSxcbiAgY2xvc2VzdDogY2xvc2VzdCxcbiAgdG9nZ2xlQ2xhc3M6IHRvZ2dsZUNsYXNzLFxuICBjbG9uZTogY2xvbmUsXG4gIGluZGV4OiBpbmRleCxcbiAgbmV4dFRpY2s6IF9uZXh0VGljayxcbiAgY2FuY2VsTmV4dFRpY2s6IF9jYW5jZWxOZXh0VGljayxcbiAgZGV0ZWN0RGlyZWN0aW9uOiBfZGV0ZWN0RGlyZWN0aW9uLFxuICBnZXRDaGlsZDogZ2V0Q2hpbGRcbn07XG5cbi8qKlxyXG4gKiBHZXQgdGhlIFNvcnRhYmxlIGluc3RhbmNlIG9mIGFuIGVsZW1lbnRcclxuICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IGVsZW1lbnQgVGhlIGVsZW1lbnRcclxuICogQHJldHVybiB7U29ydGFibGV8dW5kZWZpbmVkfSAgICAgICAgIFRoZSBpbnN0YW5jZSBvZiBTb3J0YWJsZVxyXG4gKi9cblNvcnRhYmxlLmdldCA9IGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gIHJldHVybiBlbGVtZW50W2V4cGFuZG9dO1xufTtcblxuLyoqXHJcbiAqIE1vdW50IGEgcGx1Z2luIHRvIFNvcnRhYmxlXHJcbiAqIEBwYXJhbSAgey4uLlNvcnRhYmxlUGx1Z2lufFNvcnRhYmxlUGx1Z2luW119IHBsdWdpbnMgICAgICAgUGx1Z2lucyBiZWluZyBtb3VudGVkXHJcbiAqL1xuU29ydGFibGUubW91bnQgPSBmdW5jdGlvbiAoKSB7XG4gIGZvciAodmFyIF9sZW4gPSBhcmd1bWVudHMubGVuZ3RoLCBwbHVnaW5zID0gbmV3IEFycmF5KF9sZW4pLCBfa2V5ID0gMDsgX2tleSA8IF9sZW47IF9rZXkrKykge1xuICAgIHBsdWdpbnNbX2tleV0gPSBhcmd1bWVudHNbX2tleV07XG4gIH1cbiAgaWYgKHBsdWdpbnNbMF0uY29uc3RydWN0b3IgPT09IEFycmF5KSBwbHVnaW5zID0gcGx1Z2luc1swXTtcbiAgcGx1Z2lucy5mb3JFYWNoKGZ1bmN0aW9uIChwbHVnaW4pIHtcbiAgICBpZiAoIXBsdWdpbi5wcm90b3R5cGUgfHwgIXBsdWdpbi5wcm90b3R5cGUuY29uc3RydWN0b3IpIHtcbiAgICAgIHRocm93IFwiU29ydGFibGU6IE1vdW50ZWQgcGx1Z2luIG11c3QgYmUgYSBjb25zdHJ1Y3RvciBmdW5jdGlvbiwgbm90IFwiLmNvbmNhdCh7fS50b1N0cmluZy5jYWxsKHBsdWdpbikpO1xuICAgIH1cbiAgICBpZiAocGx1Z2luLnV0aWxzKSBTb3J0YWJsZS51dGlscyA9IF9vYmplY3RTcHJlYWQyKF9vYmplY3RTcHJlYWQyKHt9LCBTb3J0YWJsZS51dGlscyksIHBsdWdpbi51dGlscyk7XG4gICAgUGx1Z2luTWFuYWdlci5tb3VudChwbHVnaW4pO1xuICB9KTtcbn07XG5cbi8qKlxyXG4gKiBDcmVhdGUgc29ydGFibGUgaW5zdGFuY2VcclxuICogQHBhcmFtIHtIVE1MRWxlbWVudH0gIGVsXHJcbiAqIEBwYXJhbSB7T2JqZWN0fSAgICAgIFtvcHRpb25zXVxyXG4gKi9cblNvcnRhYmxlLmNyZWF0ZSA9IGZ1bmN0aW9uIChlbCwgb3B0aW9ucykge1xuICByZXR1cm4gbmV3IFNvcnRhYmxlKGVsLCBvcHRpb25zKTtcbn07XG5cbi8vIEV4cG9ydFxuU29ydGFibGUudmVyc2lvbiA9IHZlcnNpb247XG5cbnZhciBhdXRvU2Nyb2xscyA9IFtdLFxuICBzY3JvbGxFbCxcbiAgc2Nyb2xsUm9vdEVsLFxuICBzY3JvbGxpbmcgPSBmYWxzZSxcbiAgbGFzdEF1dG9TY3JvbGxYLFxuICBsYXN0QXV0b1Njcm9sbFksXG4gIHRvdWNoRXZ0JDEsXG4gIHBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsO1xuZnVuY3Rpb24gQXV0b1Njcm9sbFBsdWdpbigpIHtcbiAgZnVuY3Rpb24gQXV0b1Njcm9sbCgpIHtcbiAgICB0aGlzLmRlZmF1bHRzID0ge1xuICAgICAgc2Nyb2xsOiB0cnVlLFxuICAgICAgZm9yY2VBdXRvU2Nyb2xsRmFsbGJhY2s6IGZhbHNlLFxuICAgICAgc2Nyb2xsU2Vuc2l0aXZpdHk6IDMwLFxuICAgICAgc2Nyb2xsU3BlZWQ6IDEwLFxuICAgICAgYnViYmxlU2Nyb2xsOiB0cnVlXG4gICAgfTtcblxuICAgIC8vIEJpbmQgYWxsIHByaXZhdGUgbWV0aG9kc1xuICAgIGZvciAodmFyIGZuIGluIHRoaXMpIHtcbiAgICAgIGlmIChmbi5jaGFyQXQoMCkgPT09ICdfJyAmJiB0eXBlb2YgdGhpc1tmbl0gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgdGhpc1tmbl0gPSB0aGlzW2ZuXS5iaW5kKHRoaXMpO1xuICAgICAgfVxuICAgIH1cbiAgfVxuICBBdXRvU2Nyb2xsLnByb3RvdHlwZSA9IHtcbiAgICBkcmFnU3RhcnRlZDogZnVuY3Rpb24gZHJhZ1N0YXJ0ZWQoX3JlZikge1xuICAgICAgdmFyIG9yaWdpbmFsRXZlbnQgPSBfcmVmLm9yaWdpbmFsRXZlbnQ7XG4gICAgICBpZiAodGhpcy5zb3J0YWJsZS5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICdkcmFnb3ZlcicsIHRoaXMuX2hhbmRsZUF1dG9TY3JvbGwpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5zdXBwb3J0UG9pbnRlcikge1xuICAgICAgICAgIG9uKGRvY3VtZW50LCAncG9pbnRlcm1vdmUnLCB0aGlzLl9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwpO1xuICAgICAgICB9IGVsc2UgaWYgKG9yaWdpbmFsRXZlbnQudG91Y2hlcykge1xuICAgICAgICAgIG9uKGRvY3VtZW50LCAndG91Y2htb3ZlJywgdGhpcy5faGFuZGxlRmFsbGJhY2tBdXRvU2Nyb2xsKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBvbihkb2N1bWVudCwgJ21vdXNlbW92ZScsIHRoaXMuX2hhbmRsZUZhbGxiYWNrQXV0b1Njcm9sbCk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9LFxuICAgIGRyYWdPdmVyQ29tcGxldGVkOiBmdW5jdGlvbiBkcmFnT3ZlckNvbXBsZXRlZChfcmVmMikge1xuICAgICAgdmFyIG9yaWdpbmFsRXZlbnQgPSBfcmVmMi5vcmlnaW5hbEV2ZW50O1xuICAgICAgLy8gRm9yIHdoZW4gYnViYmxpbmcgaXMgY2FuY2VsZWQgYW5kIHVzaW5nIGZhbGxiYWNrIChmYWxsYmFjayAndG91Y2htb3ZlJyBhbHdheXMgcmVhY2hlZClcbiAgICAgIGlmICghdGhpcy5vcHRpb25zLmRyYWdPdmVyQnViYmxlICYmICFvcmlnaW5hbEV2ZW50LnJvb3RFbCkge1xuICAgICAgICB0aGlzLl9oYW5kbGVBdXRvU2Nyb2xsKG9yaWdpbmFsRXZlbnQpO1xuICAgICAgfVxuICAgIH0sXG4gICAgZHJvcDogZnVuY3Rpb24gZHJvcCgpIHtcbiAgICAgIGlmICh0aGlzLnNvcnRhYmxlLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgICAgICBvZmYoZG9jdW1lbnQsICdkcmFnb3ZlcicsIHRoaXMuX2hhbmRsZUF1dG9TY3JvbGwpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgb2ZmKGRvY3VtZW50LCAncG9pbnRlcm1vdmUnLCB0aGlzLl9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwpO1xuICAgICAgICBvZmYoZG9jdW1lbnQsICd0b3VjaG1vdmUnLCB0aGlzLl9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwpO1xuICAgICAgICBvZmYoZG9jdW1lbnQsICdtb3VzZW1vdmUnLCB0aGlzLl9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwpO1xuICAgICAgfVxuICAgICAgY2xlYXJQb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCgpO1xuICAgICAgY2xlYXJBdXRvU2Nyb2xscygpO1xuICAgICAgY2FuY2VsVGhyb3R0bGUoKTtcbiAgICB9LFxuICAgIG51bGxpbmc6IGZ1bmN0aW9uIG51bGxpbmcoKSB7XG4gICAgICB0b3VjaEV2dCQxID0gc2Nyb2xsUm9vdEVsID0gc2Nyb2xsRWwgPSBzY3JvbGxpbmcgPSBwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCA9IGxhc3RBdXRvU2Nyb2xsWCA9IGxhc3RBdXRvU2Nyb2xsWSA9IG51bGw7XG4gICAgICBhdXRvU2Nyb2xscy5sZW5ndGggPSAwO1xuICAgIH0sXG4gICAgX2hhbmRsZUZhbGxiYWNrQXV0b1Njcm9sbDogZnVuY3Rpb24gX2hhbmRsZUZhbGxiYWNrQXV0b1Njcm9sbChldnQpIHtcbiAgICAgIHRoaXMuX2hhbmRsZUF1dG9TY3JvbGwoZXZ0LCB0cnVlKTtcbiAgICB9LFxuICAgIF9oYW5kbGVBdXRvU2Nyb2xsOiBmdW5jdGlvbiBfaGFuZGxlQXV0b1Njcm9sbChldnQsIGZhbGxiYWNrKSB7XG4gICAgICB2YXIgX3RoaXMgPSB0aGlzO1xuICAgICAgdmFyIHggPSAoZXZ0LnRvdWNoZXMgPyBldnQudG91Y2hlc1swXSA6IGV2dCkuY2xpZW50WCxcbiAgICAgICAgeSA9IChldnQudG91Y2hlcyA/IGV2dC50b3VjaGVzWzBdIDogZXZ0KS5jbGllbnRZLFxuICAgICAgICBlbGVtID0gZG9jdW1lbnQuZWxlbWVudEZyb21Qb2ludCh4LCB5KTtcbiAgICAgIHRvdWNoRXZ0JDEgPSBldnQ7XG5cbiAgICAgIC8vIElFIGRvZXMgbm90IHNlZW0gdG8gaGF2ZSBuYXRpdmUgYXV0b3Njcm9sbCxcbiAgICAgIC8vIEVkZ2UncyBhdXRvc2Nyb2xsIHNlZW1zIHRvbyBjb25kaXRpb25hbCxcbiAgICAgIC8vIE1BQ09TIFNhZmFyaSBkb2VzIG5vdCBoYXZlIGF1dG9zY3JvbGwsXG4gICAgICAvLyBGaXJlZm94IGFuZCBDaHJvbWUgYXJlIGdvb2RcbiAgICAgIGlmIChmYWxsYmFjayB8fCB0aGlzLm9wdGlvbnMuZm9yY2VBdXRvU2Nyb2xsRmFsbGJhY2sgfHwgRWRnZSB8fCBJRTExT3JMZXNzIHx8IFNhZmFyaSkge1xuICAgICAgICBhdXRvU2Nyb2xsKGV2dCwgdGhpcy5vcHRpb25zLCBlbGVtLCBmYWxsYmFjayk7XG5cbiAgICAgICAgLy8gTGlzdGVuZXIgZm9yIHBvaW50ZXIgZWxlbWVudCBjaGFuZ2VcbiAgICAgICAgdmFyIG9nRWxlbVNjcm9sbGVyID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZWxlbSwgdHJ1ZSk7XG4gICAgICAgIGlmIChzY3JvbGxpbmcgJiYgKCFwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCB8fCB4ICE9PSBsYXN0QXV0b1Njcm9sbFggfHwgeSAhPT0gbGFzdEF1dG9TY3JvbGxZKSkge1xuICAgICAgICAgIHBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsICYmIGNsZWFyUG9pbnRlckVsZW1DaGFuZ2VkSW50ZXJ2YWwoKTtcbiAgICAgICAgICAvLyBEZXRlY3QgZm9yIHBvaW50ZXIgZWxlbSBjaGFuZ2UsIGVtdWxhdGluZyBuYXRpdmUgRG5EIGJlaGF2aW91clxuICAgICAgICAgIHBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsID0gc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIG5ld0VsZW0gPSBnZXRQYXJlbnRBdXRvU2Nyb2xsRWxlbWVudChkb2N1bWVudC5lbGVtZW50RnJvbVBvaW50KHgsIHkpLCB0cnVlKTtcbiAgICAgICAgICAgIGlmIChuZXdFbGVtICE9PSBvZ0VsZW1TY3JvbGxlcikge1xuICAgICAgICAgICAgICBvZ0VsZW1TY3JvbGxlciA9IG5ld0VsZW07XG4gICAgICAgICAgICAgIGNsZWFyQXV0b1Njcm9sbHMoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGF1dG9TY3JvbGwoZXZ0LCBfdGhpcy5vcHRpb25zLCBuZXdFbGVtLCBmYWxsYmFjayk7XG4gICAgICAgICAgfSwgMTApO1xuICAgICAgICAgIGxhc3RBdXRvU2Nyb2xsWCA9IHg7XG4gICAgICAgICAgbGFzdEF1dG9TY3JvbGxZID0geTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIHtcbiAgICAgICAgLy8gaWYgRG5EIGlzIGVuYWJsZWQgKGFuZCBicm93c2VyIGhhcyBnb29kIGF1dG9zY3JvbGxpbmcpLCBmaXJzdCBhdXRvc2Nyb2xsIHdpbGwgYWxyZWFkeSBzY3JvbGwsIHNvIGdldCBwYXJlbnQgYXV0b3Njcm9sbCBvZiBmaXJzdCBhdXRvc2Nyb2xsXG4gICAgICAgIGlmICghdGhpcy5vcHRpb25zLmJ1YmJsZVNjcm9sbCB8fCBnZXRQYXJlbnRBdXRvU2Nyb2xsRWxlbWVudChlbGVtLCB0cnVlKSA9PT0gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpKSB7XG4gICAgICAgICAgY2xlYXJBdXRvU2Nyb2xscygpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBhdXRvU2Nyb2xsKGV2dCwgdGhpcy5vcHRpb25zLCBnZXRQYXJlbnRBdXRvU2Nyb2xsRWxlbWVudChlbGVtLCBmYWxzZSksIGZhbHNlKTtcbiAgICAgIH1cbiAgICB9XG4gIH07XG4gIHJldHVybiBfZXh0ZW5kcyhBdXRvU2Nyb2xsLCB7XG4gICAgcGx1Z2luTmFtZTogJ3Njcm9sbCcsXG4gICAgaW5pdGlhbGl6ZUJ5RGVmYXVsdDogdHJ1ZVxuICB9KTtcbn1cbmZ1bmN0aW9uIGNsZWFyQXV0b1Njcm9sbHMoKSB7XG4gIGF1dG9TY3JvbGxzLmZvckVhY2goZnVuY3Rpb24gKGF1dG9TY3JvbGwpIHtcbiAgICBjbGVhckludGVydmFsKGF1dG9TY3JvbGwucGlkKTtcbiAgfSk7XG4gIGF1dG9TY3JvbGxzID0gW107XG59XG5mdW5jdGlvbiBjbGVhclBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsKCkge1xuICBjbGVhckludGVydmFsKHBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsKTtcbn1cbnZhciBhdXRvU2Nyb2xsID0gdGhyb3R0bGUoZnVuY3Rpb24gKGV2dCwgb3B0aW9ucywgcm9vdEVsLCBpc0ZhbGxiYWNrKSB7XG4gIC8vIEJ1ZzogaHR0cHM6Ly9idWd6aWxsYS5tb3ppbGxhLm9yZy9zaG93X2J1Zy5jZ2k/aWQ9NTA1NTIxXG4gIGlmICghb3B0aW9ucy5zY3JvbGwpIHJldHVybjtcbiAgdmFyIHggPSAoZXZ0LnRvdWNoZXMgPyBldnQudG91Y2hlc1swXSA6IGV2dCkuY2xpZW50WCxcbiAgICB5ID0gKGV2dC50b3VjaGVzID8gZXZ0LnRvdWNoZXNbMF0gOiBldnQpLmNsaWVudFksXG4gICAgc2VucyA9IG9wdGlvbnMuc2Nyb2xsU2Vuc2l0aXZpdHksXG4gICAgc3BlZWQgPSBvcHRpb25zLnNjcm9sbFNwZWVkLFxuICAgIHdpblNjcm9sbGVyID0gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpO1xuICB2YXIgc2Nyb2xsVGhpc0luc3RhbmNlID0gZmFsc2UsXG4gICAgc2Nyb2xsQ3VzdG9tRm47XG5cbiAgLy8gTmV3IHNjcm9sbCByb290LCBzZXQgc2Nyb2xsRWxcbiAgaWYgKHNjcm9sbFJvb3RFbCAhPT0gcm9vdEVsKSB7XG4gICAgc2Nyb2xsUm9vdEVsID0gcm9vdEVsO1xuICAgIGNsZWFyQXV0b1Njcm9sbHMoKTtcbiAgICBzY3JvbGxFbCA9IG9wdGlvbnMuc2Nyb2xsO1xuICAgIHNjcm9sbEN1c3RvbUZuID0gb3B0aW9ucy5zY3JvbGxGbjtcbiAgICBpZiAoc2Nyb2xsRWwgPT09IHRydWUpIHtcbiAgICAgIHNjcm9sbEVsID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQocm9vdEVsLCB0cnVlKTtcbiAgICB9XG4gIH1cbiAgdmFyIGxheWVyc091dCA9IDA7XG4gIHZhciBjdXJyZW50UGFyZW50ID0gc2Nyb2xsRWw7XG4gIGRvIHtcbiAgICB2YXIgZWwgPSBjdXJyZW50UGFyZW50LFxuICAgICAgcmVjdCA9IGdldFJlY3QoZWwpLFxuICAgICAgdG9wID0gcmVjdC50b3AsXG4gICAgICBib3R0b20gPSByZWN0LmJvdHRvbSxcbiAgICAgIGxlZnQgPSByZWN0LmxlZnQsXG4gICAgICByaWdodCA9IHJlY3QucmlnaHQsXG4gICAgICB3aWR0aCA9IHJlY3Qud2lkdGgsXG4gICAgICBoZWlnaHQgPSByZWN0LmhlaWdodCxcbiAgICAgIGNhblNjcm9sbFggPSB2b2lkIDAsXG4gICAgICBjYW5TY3JvbGxZID0gdm9pZCAwLFxuICAgICAgc2Nyb2xsV2lkdGggPSBlbC5zY3JvbGxXaWR0aCxcbiAgICAgIHNjcm9sbEhlaWdodCA9IGVsLnNjcm9sbEhlaWdodCxcbiAgICAgIGVsQ1NTID0gY3NzKGVsKSxcbiAgICAgIHNjcm9sbFBvc1ggPSBlbC5zY3JvbGxMZWZ0LFxuICAgICAgc2Nyb2xsUG9zWSA9IGVsLnNjcm9sbFRvcDtcbiAgICBpZiAoZWwgPT09IHdpblNjcm9sbGVyKSB7XG4gICAgICBjYW5TY3JvbGxYID0gd2lkdGggPCBzY3JvbGxXaWR0aCAmJiAoZWxDU1Mub3ZlcmZsb3dYID09PSAnYXV0bycgfHwgZWxDU1Mub3ZlcmZsb3dYID09PSAnc2Nyb2xsJyB8fCBlbENTUy5vdmVyZmxvd1ggPT09ICd2aXNpYmxlJyk7XG4gICAgICBjYW5TY3JvbGxZID0gaGVpZ2h0IDwgc2Nyb2xsSGVpZ2h0ICYmIChlbENTUy5vdmVyZmxvd1kgPT09ICdhdXRvJyB8fCBlbENTUy5vdmVyZmxvd1kgPT09ICdzY3JvbGwnIHx8IGVsQ1NTLm92ZXJmbG93WSA9PT0gJ3Zpc2libGUnKTtcbiAgICB9IGVsc2Uge1xuICAgICAgY2FuU2Nyb2xsWCA9IHdpZHRoIDwgc2Nyb2xsV2lkdGggJiYgKGVsQ1NTLm92ZXJmbG93WCA9PT0gJ2F1dG8nIHx8IGVsQ1NTLm92ZXJmbG93WCA9PT0gJ3Njcm9sbCcpO1xuICAgICAgY2FuU2Nyb2xsWSA9IGhlaWdodCA8IHNjcm9sbEhlaWdodCAmJiAoZWxDU1Mub3ZlcmZsb3dZID09PSAnYXV0bycgfHwgZWxDU1Mub3ZlcmZsb3dZID09PSAnc2Nyb2xsJyk7XG4gICAgfVxuICAgIHZhciB2eCA9IGNhblNjcm9sbFggJiYgKE1hdGguYWJzKHJpZ2h0IC0geCkgPD0gc2VucyAmJiBzY3JvbGxQb3NYICsgd2lkdGggPCBzY3JvbGxXaWR0aCkgLSAoTWF0aC5hYnMobGVmdCAtIHgpIDw9IHNlbnMgJiYgISFzY3JvbGxQb3NYKTtcbiAgICB2YXIgdnkgPSBjYW5TY3JvbGxZICYmIChNYXRoLmFicyhib3R0b20gLSB5KSA8PSBzZW5zICYmIHNjcm9sbFBvc1kgKyBoZWlnaHQgPCBzY3JvbGxIZWlnaHQpIC0gKE1hdGguYWJzKHRvcCAtIHkpIDw9IHNlbnMgJiYgISFzY3JvbGxQb3NZKTtcbiAgICBpZiAoIWF1dG9TY3JvbGxzW2xheWVyc091dF0pIHtcbiAgICAgIGZvciAodmFyIGkgPSAwOyBpIDw9IGxheWVyc091dDsgaSsrKSB7XG4gICAgICAgIGlmICghYXV0b1Njcm9sbHNbaV0pIHtcbiAgICAgICAgICBhdXRvU2Nyb2xsc1tpXSA9IHt9O1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICAgIGlmIChhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLnZ4ICE9IHZ4IHx8IGF1dG9TY3JvbGxzW2xheWVyc091dF0udnkgIT0gdnkgfHwgYXV0b1Njcm9sbHNbbGF5ZXJzT3V0XS5lbCAhPT0gZWwpIHtcbiAgICAgIGF1dG9TY3JvbGxzW2xheWVyc091dF0uZWwgPSBlbDtcbiAgICAgIGF1dG9TY3JvbGxzW2xheWVyc091dF0udnggPSB2eDtcbiAgICAgIGF1dG9TY3JvbGxzW2xheWVyc091dF0udnkgPSB2eTtcbiAgICAgIGNsZWFySW50ZXJ2YWwoYXV0b1Njcm9sbHNbbGF5ZXJzT3V0XS5waWQpO1xuICAgICAgaWYgKHZ4ICE9IDAgfHwgdnkgIT0gMCkge1xuICAgICAgICBzY3JvbGxUaGlzSW5zdGFuY2UgPSB0cnVlO1xuICAgICAgICAvKiBqc2hpbnQgbG9vcGZ1bmM6dHJ1ZSAqL1xuICAgICAgICBhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLnBpZCA9IHNldEludGVydmFsKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAvLyBlbXVsYXRlIGRyYWcgb3ZlciBkdXJpbmcgYXV0b3Njcm9sbCAoZmFsbGJhY2spLCBlbXVsYXRpbmcgbmF0aXZlIERuRCBiZWhhdmlvdXJcbiAgICAgICAgICBpZiAoaXNGYWxsYmFjayAmJiB0aGlzLmxheWVyID09PSAwKSB7XG4gICAgICAgICAgICBTb3J0YWJsZS5hY3RpdmUuX29uVG91Y2hNb3ZlKHRvdWNoRXZ0JDEpOyAvLyBUbyBtb3ZlIGdob3N0IGlmIGl0IGlzIHBvc2l0aW9uZWQgYWJzb2x1dGVseVxuICAgICAgICAgIH1cbiAgICAgICAgICB2YXIgc2Nyb2xsT2Zmc2V0WSA9IGF1dG9TY3JvbGxzW3RoaXMubGF5ZXJdLnZ5ID8gYXV0b1Njcm9sbHNbdGhpcy5sYXllcl0udnkgKiBzcGVlZCA6IDA7XG4gICAgICAgICAgdmFyIHNjcm9sbE9mZnNldFggPSBhdXRvU2Nyb2xsc1t0aGlzLmxheWVyXS52eCA/IGF1dG9TY3JvbGxzW3RoaXMubGF5ZXJdLnZ4ICogc3BlZWQgOiAwO1xuICAgICAgICAgIGlmICh0eXBlb2Ygc2Nyb2xsQ3VzdG9tRm4gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgIGlmIChzY3JvbGxDdXN0b21Gbi5jYWxsKFNvcnRhYmxlLmRyYWdnZWQucGFyZW50Tm9kZVtleHBhbmRvXSwgc2Nyb2xsT2Zmc2V0WCwgc2Nyb2xsT2Zmc2V0WSwgZXZ0LCB0b3VjaEV2dCQxLCBhdXRvU2Nyb2xsc1t0aGlzLmxheWVyXS5lbCkgIT09ICdjb250aW51ZScpIHtcbiAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICBzY3JvbGxCeShhdXRvU2Nyb2xsc1t0aGlzLmxheWVyXS5lbCwgc2Nyb2xsT2Zmc2V0WCwgc2Nyb2xsT2Zmc2V0WSk7XG4gICAgICAgIH0uYmluZCh7XG4gICAgICAgICAgbGF5ZXI6IGxheWVyc091dFxuICAgICAgICB9KSwgMjQpO1xuICAgICAgfVxuICAgIH1cbiAgICBsYXllcnNPdXQrKztcbiAgfSB3aGlsZSAob3B0aW9ucy5idWJibGVTY3JvbGwgJiYgY3VycmVudFBhcmVudCAhPT0gd2luU2Nyb2xsZXIgJiYgKGN1cnJlbnRQYXJlbnQgPSBnZXRQYXJlbnRBdXRvU2Nyb2xsRWxlbWVudChjdXJyZW50UGFyZW50LCBmYWxzZSkpKTtcbiAgc2Nyb2xsaW5nID0gc2Nyb2xsVGhpc0luc3RhbmNlOyAvLyBpbiBjYXNlIGFub3RoZXIgZnVuY3Rpb24gY2F0Y2hlcyBzY3JvbGxpbmcgYXMgZmFsc2UgaW4gYmV0d2VlbiB3aGVuIGl0IGlzIG5vdFxufSwgMzApO1xuXG52YXIgZHJvcCA9IGZ1bmN0aW9uIGRyb3AoX3JlZikge1xuICB2YXIgb3JpZ2luYWxFdmVudCA9IF9yZWYub3JpZ2luYWxFdmVudCxcbiAgICBwdXRTb3J0YWJsZSA9IF9yZWYucHV0U29ydGFibGUsXG4gICAgZHJhZ0VsID0gX3JlZi5kcmFnRWwsXG4gICAgYWN0aXZlU29ydGFibGUgPSBfcmVmLmFjdGl2ZVNvcnRhYmxlLFxuICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCA9IF9yZWYuZGlzcGF0Y2hTb3J0YWJsZUV2ZW50LFxuICAgIGhpZGVHaG9zdEZvclRhcmdldCA9IF9yZWYuaGlkZUdob3N0Rm9yVGFyZ2V0LFxuICAgIHVuaGlkZUdob3N0Rm9yVGFyZ2V0ID0gX3JlZi51bmhpZGVHaG9zdEZvclRhcmdldDtcbiAgaWYgKCFvcmlnaW5hbEV2ZW50KSByZXR1cm47XG4gIHZhciB0b1NvcnRhYmxlID0gcHV0U29ydGFibGUgfHwgYWN0aXZlU29ydGFibGU7XG4gIGhpZGVHaG9zdEZvclRhcmdldCgpO1xuICB2YXIgdG91Y2ggPSBvcmlnaW5hbEV2ZW50LmNoYW5nZWRUb3VjaGVzICYmIG9yaWdpbmFsRXZlbnQuY2hhbmdlZFRvdWNoZXMubGVuZ3RoID8gb3JpZ2luYWxFdmVudC5jaGFuZ2VkVG91Y2hlc1swXSA6IG9yaWdpbmFsRXZlbnQ7XG4gIHZhciB0YXJnZXQgPSBkb2N1bWVudC5lbGVtZW50RnJvbVBvaW50KHRvdWNoLmNsaWVudFgsIHRvdWNoLmNsaWVudFkpO1xuICB1bmhpZGVHaG9zdEZvclRhcmdldCgpO1xuICBpZiAodG9Tb3J0YWJsZSAmJiAhdG9Tb3J0YWJsZS5lbC5jb250YWlucyh0YXJnZXQpKSB7XG4gICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50KCdzcGlsbCcpO1xuICAgIHRoaXMub25TcGlsbCh7XG4gICAgICBkcmFnRWw6IGRyYWdFbCxcbiAgICAgIHB1dFNvcnRhYmxlOiBwdXRTb3J0YWJsZVxuICAgIH0pO1xuICB9XG59O1xuZnVuY3Rpb24gUmV2ZXJ0KCkge31cblJldmVydC5wcm90b3R5cGUgPSB7XG4gIHN0YXJ0SW5kZXg6IG51bGwsXG4gIGRyYWdTdGFydDogZnVuY3Rpb24gZHJhZ1N0YXJ0KF9yZWYyKSB7XG4gICAgdmFyIG9sZERyYWdnYWJsZUluZGV4ID0gX3JlZjIub2xkRHJhZ2dhYmxlSW5kZXg7XG4gICAgdGhpcy5zdGFydEluZGV4ID0gb2xkRHJhZ2dhYmxlSW5kZXg7XG4gIH0sXG4gIG9uU3BpbGw6IGZ1bmN0aW9uIG9uU3BpbGwoX3JlZjMpIHtcbiAgICB2YXIgZHJhZ0VsID0gX3JlZjMuZHJhZ0VsLFxuICAgICAgcHV0U29ydGFibGUgPSBfcmVmMy5wdXRTb3J0YWJsZTtcbiAgICB0aGlzLnNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgIGlmIChwdXRTb3J0YWJsZSkge1xuICAgICAgcHV0U29ydGFibGUuY2FwdHVyZUFuaW1hdGlvblN0YXRlKCk7XG4gICAgfVxuICAgIHZhciBuZXh0U2libGluZyA9IGdldENoaWxkKHRoaXMuc29ydGFibGUuZWwsIHRoaXMuc3RhcnRJbmRleCwgdGhpcy5vcHRpb25zKTtcbiAgICBpZiAobmV4dFNpYmxpbmcpIHtcbiAgICAgIHRoaXMuc29ydGFibGUuZWwuaW5zZXJ0QmVmb3JlKGRyYWdFbCwgbmV4dFNpYmxpbmcpO1xuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLnNvcnRhYmxlLmVsLmFwcGVuZENoaWxkKGRyYWdFbCk7XG4gICAgfVxuICAgIHRoaXMuc29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgIGlmIChwdXRTb3J0YWJsZSkge1xuICAgICAgcHV0U29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgIH1cbiAgfSxcbiAgZHJvcDogZHJvcFxufTtcbl9leHRlbmRzKFJldmVydCwge1xuICBwbHVnaW5OYW1lOiAncmV2ZXJ0T25TcGlsbCdcbn0pO1xuZnVuY3Rpb24gUmVtb3ZlKCkge31cblJlbW92ZS5wcm90b3R5cGUgPSB7XG4gIG9uU3BpbGw6IGZ1bmN0aW9uIG9uU3BpbGwoX3JlZjQpIHtcbiAgICB2YXIgZHJhZ0VsID0gX3JlZjQuZHJhZ0VsLFxuICAgICAgcHV0U29ydGFibGUgPSBfcmVmNC5wdXRTb3J0YWJsZTtcbiAgICB2YXIgcGFyZW50U29ydGFibGUgPSBwdXRTb3J0YWJsZSB8fCB0aGlzLnNvcnRhYmxlO1xuICAgIHBhcmVudFNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgIGRyYWdFbC5wYXJlbnROb2RlICYmIGRyYWdFbC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGRyYWdFbCk7XG4gICAgcGFyZW50U29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICB9LFxuICBkcm9wOiBkcm9wXG59O1xuX2V4dGVuZHMoUmVtb3ZlLCB7XG4gIHBsdWdpbk5hbWU6ICdyZW1vdmVPblNwaWxsJ1xufSk7XG5cbnZhciBsYXN0U3dhcEVsO1xuZnVuY3Rpb24gU3dhcFBsdWdpbigpIHtcbiAgZnVuY3Rpb24gU3dhcCgpIHtcbiAgICB0aGlzLmRlZmF1bHRzID0ge1xuICAgICAgc3dhcENsYXNzOiAnc29ydGFibGUtc3dhcC1oaWdobGlnaHQnXG4gICAgfTtcbiAgfVxuICBTd2FwLnByb3RvdHlwZSA9IHtcbiAgICBkcmFnU3RhcnQ6IGZ1bmN0aW9uIGRyYWdTdGFydChfcmVmKSB7XG4gICAgICB2YXIgZHJhZ0VsID0gX3JlZi5kcmFnRWw7XG4gICAgICBsYXN0U3dhcEVsID0gZHJhZ0VsO1xuICAgIH0sXG4gICAgZHJhZ092ZXJWYWxpZDogZnVuY3Rpb24gZHJhZ092ZXJWYWxpZChfcmVmMikge1xuICAgICAgdmFyIGNvbXBsZXRlZCA9IF9yZWYyLmNvbXBsZXRlZCxcbiAgICAgICAgdGFyZ2V0ID0gX3JlZjIudGFyZ2V0LFxuICAgICAgICBvbk1vdmUgPSBfcmVmMi5vbk1vdmUsXG4gICAgICAgIGFjdGl2ZVNvcnRhYmxlID0gX3JlZjIuYWN0aXZlU29ydGFibGUsXG4gICAgICAgIGNoYW5nZWQgPSBfcmVmMi5jaGFuZ2VkLFxuICAgICAgICBjYW5jZWwgPSBfcmVmMi5jYW5jZWw7XG4gICAgICBpZiAoIWFjdGl2ZVNvcnRhYmxlLm9wdGlvbnMuc3dhcCkgcmV0dXJuO1xuICAgICAgdmFyIGVsID0gdGhpcy5zb3J0YWJsZS5lbCxcbiAgICAgICAgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcbiAgICAgIGlmICh0YXJnZXQgJiYgdGFyZ2V0ICE9PSBlbCkge1xuICAgICAgICB2YXIgcHJldlN3YXBFbCA9IGxhc3RTd2FwRWw7XG4gICAgICAgIGlmIChvbk1vdmUodGFyZ2V0KSAhPT0gZmFsc2UpIHtcbiAgICAgICAgICB0b2dnbGVDbGFzcyh0YXJnZXQsIG9wdGlvbnMuc3dhcENsYXNzLCB0cnVlKTtcbiAgICAgICAgICBsYXN0U3dhcEVsID0gdGFyZ2V0O1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGxhc3RTd2FwRWwgPSBudWxsO1xuICAgICAgICB9XG4gICAgICAgIGlmIChwcmV2U3dhcEVsICYmIHByZXZTd2FwRWwgIT09IGxhc3RTd2FwRWwpIHtcbiAgICAgICAgICB0b2dnbGVDbGFzcyhwcmV2U3dhcEVsLCBvcHRpb25zLnN3YXBDbGFzcywgZmFsc2UpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICBjaGFuZ2VkKCk7XG4gICAgICBjb21wbGV0ZWQodHJ1ZSk7XG4gICAgICBjYW5jZWwoKTtcbiAgICB9LFxuICAgIGRyb3A6IGZ1bmN0aW9uIGRyb3AoX3JlZjMpIHtcbiAgICAgIHZhciBhY3RpdmVTb3J0YWJsZSA9IF9yZWYzLmFjdGl2ZVNvcnRhYmxlLFxuICAgICAgICBwdXRTb3J0YWJsZSA9IF9yZWYzLnB1dFNvcnRhYmxlLFxuICAgICAgICBkcmFnRWwgPSBfcmVmMy5kcmFnRWw7XG4gICAgICB2YXIgdG9Tb3J0YWJsZSA9IHB1dFNvcnRhYmxlIHx8IHRoaXMuc29ydGFibGU7XG4gICAgICB2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcbiAgICAgIGxhc3RTd2FwRWwgJiYgdG9nZ2xlQ2xhc3MobGFzdFN3YXBFbCwgb3B0aW9ucy5zd2FwQ2xhc3MsIGZhbHNlKTtcbiAgICAgIGlmIChsYXN0U3dhcEVsICYmIChvcHRpb25zLnN3YXAgfHwgcHV0U29ydGFibGUgJiYgcHV0U29ydGFibGUub3B0aW9ucy5zd2FwKSkge1xuICAgICAgICBpZiAoZHJhZ0VsICE9PSBsYXN0U3dhcEVsKSB7XG4gICAgICAgICAgdG9Tb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICAgICAgICBpZiAodG9Tb3J0YWJsZSAhPT0gYWN0aXZlU29ydGFibGUpIGFjdGl2ZVNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgICAgICAgIHN3YXBOb2RlcyhkcmFnRWwsIGxhc3RTd2FwRWwpO1xuICAgICAgICAgIHRvU29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgICAgICAgIGlmICh0b1NvcnRhYmxlICE9PSBhY3RpdmVTb3J0YWJsZSkgYWN0aXZlU29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfSxcbiAgICBudWxsaW5nOiBmdW5jdGlvbiBudWxsaW5nKCkge1xuICAgICAgbGFzdFN3YXBFbCA9IG51bGw7XG4gICAgfVxuICB9O1xuICByZXR1cm4gX2V4dGVuZHMoU3dhcCwge1xuICAgIHBsdWdpbk5hbWU6ICdzd2FwJyxcbiAgICBldmVudFByb3BlcnRpZXM6IGZ1bmN0aW9uIGV2ZW50UHJvcGVydGllcygpIHtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIHN3YXBJdGVtOiBsYXN0U3dhcEVsXG4gICAgICB9O1xuICAgIH1cbiAgfSk7XG59XG5mdW5jdGlvbiBzd2FwTm9kZXMobjEsIG4yKSB7XG4gIHZhciBwMSA9IG4xLnBhcmVudE5vZGUsXG4gICAgcDIgPSBuMi5wYXJlbnROb2RlLFxuICAgIGkxLFxuICAgIGkyO1xuICBpZiAoIXAxIHx8ICFwMiB8fCBwMS5pc0VxdWFsTm9kZShuMikgfHwgcDIuaXNFcXVhbE5vZGUobjEpKSByZXR1cm47XG4gIGkxID0gaW5kZXgobjEpO1xuICBpMiA9IGluZGV4KG4yKTtcbiAgaWYgKHAxLmlzRXF1YWxOb2RlKHAyKSAmJiBpMSA8IGkyKSB7XG4gICAgaTIrKztcbiAgfVxuICBwMS5pbnNlcnRCZWZvcmUobjIsIHAxLmNoaWxkcmVuW2kxXSk7XG4gIHAyLmluc2VydEJlZm9yZShuMSwgcDIuY2hpbGRyZW5baTJdKTtcbn1cblxudmFyIG11bHRpRHJhZ0VsZW1lbnRzID0gW10sXG4gIG11bHRpRHJhZ0Nsb25lcyA9IFtdLFxuICBsYXN0TXVsdGlEcmFnU2VsZWN0LFxuICAvLyBmb3Igc2VsZWN0aW9uIHdpdGggbW9kaWZpZXIga2V5IGRvd24gKFNISUZUKVxuICBtdWx0aURyYWdTb3J0YWJsZSxcbiAgaW5pdGlhbEZvbGRpbmcgPSBmYWxzZSxcbiAgLy8gSW5pdGlhbCBtdWx0aS1kcmFnIGZvbGQgd2hlbiBkcmFnIHN0YXJ0ZWRcbiAgZm9sZGluZyA9IGZhbHNlLFxuICAvLyBGb2xkaW5nIGFueSBvdGhlciB0aW1lXG4gIGRyYWdTdGFydGVkID0gZmFsc2UsXG4gIGRyYWdFbCQxLFxuICBjbG9uZXNGcm9tUmVjdCxcbiAgY2xvbmVzSGlkZGVuO1xuZnVuY3Rpb24gTXVsdGlEcmFnUGx1Z2luKCkge1xuICBmdW5jdGlvbiBNdWx0aURyYWcoc29ydGFibGUpIHtcbiAgICAvLyBCaW5kIGFsbCBwcml2YXRlIG1ldGhvZHNcbiAgICBmb3IgKHZhciBmbiBpbiB0aGlzKSB7XG4gICAgICBpZiAoZm4uY2hhckF0KDApID09PSAnXycgJiYgdHlwZW9mIHRoaXNbZm5dID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgIHRoaXNbZm5dID0gdGhpc1tmbl0uYmluZCh0aGlzKTtcbiAgICAgIH1cbiAgICB9XG4gICAgaWYgKCFzb3J0YWJsZS5vcHRpb25zLmF2b2lkSW1wbGljaXREZXNlbGVjdCkge1xuICAgICAgaWYgKHNvcnRhYmxlLm9wdGlvbnMuc3VwcG9ydFBvaW50ZXIpIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICdwb2ludGVydXAnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBvbihkb2N1bWVudCwgJ21vdXNldXAnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICAgIG9uKGRvY3VtZW50LCAndG91Y2hlbmQnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICB9XG4gICAgfVxuICAgIG9uKGRvY3VtZW50LCAna2V5ZG93bicsIHRoaXMuX2NoZWNrS2V5RG93bik7XG4gICAgb24oZG9jdW1lbnQsICdrZXl1cCcsIHRoaXMuX2NoZWNrS2V5VXApO1xuICAgIHRoaXMuZGVmYXVsdHMgPSB7XG4gICAgICBzZWxlY3RlZENsYXNzOiAnc29ydGFibGUtc2VsZWN0ZWQnLFxuICAgICAgbXVsdGlEcmFnS2V5OiBudWxsLFxuICAgICAgYXZvaWRJbXBsaWNpdERlc2VsZWN0OiBmYWxzZSxcbiAgICAgIHNldERhdGE6IGZ1bmN0aW9uIHNldERhdGEoZGF0YVRyYW5zZmVyLCBkcmFnRWwpIHtcbiAgICAgICAgdmFyIGRhdGEgPSAnJztcbiAgICAgICAgaWYgKG11bHRpRHJhZ0VsZW1lbnRzLmxlbmd0aCAmJiBtdWx0aURyYWdTb3J0YWJsZSA9PT0gc29ydGFibGUpIHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50LCBpKSB7XG4gICAgICAgICAgICBkYXRhICs9ICghaSA/ICcnIDogJywgJykgKyBtdWx0aURyYWdFbGVtZW50LnRleHRDb250ZW50O1xuICAgICAgICAgIH0pO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGRhdGEgPSBkcmFnRWwudGV4dENvbnRlbnQ7XG4gICAgICAgIH1cbiAgICAgICAgZGF0YVRyYW5zZmVyLnNldERhdGEoJ1RleHQnLCBkYXRhKTtcbiAgICAgIH1cbiAgICB9O1xuICB9XG4gIE11bHRpRHJhZy5wcm90b3R5cGUgPSB7XG4gICAgbXVsdGlEcmFnS2V5RG93bjogZmFsc2UsXG4gICAgaXNNdWx0aURyYWc6IGZhbHNlLFxuICAgIGRlbGF5U3RhcnRHbG9iYWw6IGZ1bmN0aW9uIGRlbGF5U3RhcnRHbG9iYWwoX3JlZikge1xuICAgICAgdmFyIGRyYWdnZWQgPSBfcmVmLmRyYWdFbDtcbiAgICAgIGRyYWdFbCQxID0gZHJhZ2dlZDtcbiAgICB9LFxuICAgIGRlbGF5RW5kZWQ6IGZ1bmN0aW9uIGRlbGF5RW5kZWQoKSB7XG4gICAgICB0aGlzLmlzTXVsdGlEcmFnID0gfm11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YoZHJhZ0VsJDEpO1xuICAgIH0sXG4gICAgc2V0dXBDbG9uZTogZnVuY3Rpb24gc2V0dXBDbG9uZShfcmVmMikge1xuICAgICAgdmFyIHNvcnRhYmxlID0gX3JlZjIuc29ydGFibGUsXG4gICAgICAgIGNhbmNlbCA9IF9yZWYyLmNhbmNlbDtcbiAgICAgIGlmICghdGhpcy5pc011bHRpRHJhZykgcmV0dXJuO1xuICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBtdWx0aURyYWdFbGVtZW50cy5sZW5ndGg7IGkrKykge1xuICAgICAgICBtdWx0aURyYWdDbG9uZXMucHVzaChjbG9uZShtdWx0aURyYWdFbGVtZW50c1tpXSkpO1xuICAgICAgICBtdWx0aURyYWdDbG9uZXNbaV0uc29ydGFibGVJbmRleCA9IG11bHRpRHJhZ0VsZW1lbnRzW2ldLnNvcnRhYmxlSW5kZXg7XG4gICAgICAgIG11bHRpRHJhZ0Nsb25lc1tpXS5kcmFnZ2FibGUgPSBmYWxzZTtcbiAgICAgICAgbXVsdGlEcmFnQ2xvbmVzW2ldLnN0eWxlWyd3aWxsLWNoYW5nZSddID0gJyc7XG4gICAgICAgIHRvZ2dsZUNsYXNzKG11bHRpRHJhZ0Nsb25lc1tpXSwgdGhpcy5vcHRpb25zLnNlbGVjdGVkQ2xhc3MsIGZhbHNlKTtcbiAgICAgICAgbXVsdGlEcmFnRWxlbWVudHNbaV0gPT09IGRyYWdFbCQxICYmIHRvZ2dsZUNsYXNzKG11bHRpRHJhZ0Nsb25lc1tpXSwgdGhpcy5vcHRpb25zLmNob3NlbkNsYXNzLCBmYWxzZSk7XG4gICAgICB9XG4gICAgICBzb3J0YWJsZS5faGlkZUNsb25lKCk7XG4gICAgICBjYW5jZWwoKTtcbiAgICB9LFxuICAgIGNsb25lOiBmdW5jdGlvbiBjbG9uZShfcmVmMykge1xuICAgICAgdmFyIHNvcnRhYmxlID0gX3JlZjMuc29ydGFibGUsXG4gICAgICAgIHJvb3RFbCA9IF9yZWYzLnJvb3RFbCxcbiAgICAgICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50ID0gX3JlZjMuZGlzcGF0Y2hTb3J0YWJsZUV2ZW50LFxuICAgICAgICBjYW5jZWwgPSBfcmVmMy5jYW5jZWw7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcpIHJldHVybjtcbiAgICAgIGlmICghdGhpcy5vcHRpb25zLnJlbW92ZUNsb25lT25IaWRlKSB7XG4gICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50cy5sZW5ndGggJiYgbXVsdGlEcmFnU29ydGFibGUgPT09IHNvcnRhYmxlKSB7XG4gICAgICAgICAgaW5zZXJ0TXVsdGlEcmFnQ2xvbmVzKHRydWUsIHJvb3RFbCk7XG4gICAgICAgICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50KCdjbG9uZScpO1xuICAgICAgICAgIGNhbmNlbCgpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfSxcbiAgICBzaG93Q2xvbmU6IGZ1bmN0aW9uIHNob3dDbG9uZShfcmVmNCkge1xuICAgICAgdmFyIGNsb25lTm93U2hvd24gPSBfcmVmNC5jbG9uZU5vd1Nob3duLFxuICAgICAgICByb290RWwgPSBfcmVmNC5yb290RWwsXG4gICAgICAgIGNhbmNlbCA9IF9yZWY0LmNhbmNlbDtcbiAgICAgIGlmICghdGhpcy5pc011bHRpRHJhZykgcmV0dXJuO1xuICAgICAgaW5zZXJ0TXVsdGlEcmFnQ2xvbmVzKGZhbHNlLCByb290RWwpO1xuICAgICAgbXVsdGlEcmFnQ2xvbmVzLmZvckVhY2goZnVuY3Rpb24gKGNsb25lKSB7XG4gICAgICAgIGNzcyhjbG9uZSwgJ2Rpc3BsYXknLCAnJyk7XG4gICAgICB9KTtcbiAgICAgIGNsb25lTm93U2hvd24oKTtcbiAgICAgIGNsb25lc0hpZGRlbiA9IGZhbHNlO1xuICAgICAgY2FuY2VsKCk7XG4gICAgfSxcbiAgICBoaWRlQ2xvbmU6IGZ1bmN0aW9uIGhpZGVDbG9uZShfcmVmNSkge1xuICAgICAgdmFyIF90aGlzID0gdGhpcztcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWY1LnNvcnRhYmxlLFxuICAgICAgICBjbG9uZU5vd0hpZGRlbiA9IF9yZWY1LmNsb25lTm93SGlkZGVuLFxuICAgICAgICBjYW5jZWwgPSBfcmVmNS5jYW5jZWw7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcpIHJldHVybjtcbiAgICAgIG11bHRpRHJhZ0Nsb25lcy5mb3JFYWNoKGZ1bmN0aW9uIChjbG9uZSkge1xuICAgICAgICBjc3MoY2xvbmUsICdkaXNwbGF5JywgJ25vbmUnKTtcbiAgICAgICAgaWYgKF90aGlzLm9wdGlvbnMucmVtb3ZlQ2xvbmVPbkhpZGUgJiYgY2xvbmUucGFyZW50Tm9kZSkge1xuICAgICAgICAgIGNsb25lLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY2xvbmUpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICAgIGNsb25lTm93SGlkZGVuKCk7XG4gICAgICBjbG9uZXNIaWRkZW4gPSB0cnVlO1xuICAgICAgY2FuY2VsKCk7XG4gICAgfSxcbiAgICBkcmFnU3RhcnRHbG9iYWw6IGZ1bmN0aW9uIGRyYWdTdGFydEdsb2JhbChfcmVmNikge1xuICAgICAgdmFyIHNvcnRhYmxlID0gX3JlZjYuc29ydGFibGU7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcgJiYgbXVsdGlEcmFnU29ydGFibGUpIHtcbiAgICAgICAgbXVsdGlEcmFnU29ydGFibGUubXVsdGlEcmFnLl9kZXNlbGVjdE11bHRpRHJhZygpO1xuICAgICAgfVxuICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICBtdWx0aURyYWdFbGVtZW50LnNvcnRhYmxlSW5kZXggPSBpbmRleChtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgIH0pO1xuXG4gICAgICAvLyBTb3J0IG11bHRpLWRyYWcgZWxlbWVudHNcbiAgICAgIG11bHRpRHJhZ0VsZW1lbnRzID0gbXVsdGlEcmFnRWxlbWVudHMuc29ydChmdW5jdGlvbiAoYSwgYikge1xuICAgICAgICByZXR1cm4gYS5zb3J0YWJsZUluZGV4IC0gYi5zb3J0YWJsZUluZGV4O1xuICAgICAgfSk7XG4gICAgICBkcmFnU3RhcnRlZCA9IHRydWU7XG4gICAgfSxcbiAgICBkcmFnU3RhcnRlZDogZnVuY3Rpb24gZHJhZ1N0YXJ0ZWQoX3JlZjcpIHtcbiAgICAgIHZhciBfdGhpczIgPSB0aGlzO1xuICAgICAgdmFyIHNvcnRhYmxlID0gX3JlZjcuc29ydGFibGU7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcpIHJldHVybjtcbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuc29ydCkge1xuICAgICAgICAvLyBDYXB0dXJlIHJlY3RzLFxuICAgICAgICAvLyBoaWRlIG11bHRpIGRyYWcgZWxlbWVudHMgKGJ5IHBvc2l0aW9uaW5nIHRoZW0gYWJzb2x1dGUpLFxuICAgICAgICAvLyBzZXQgbXVsdGkgZHJhZyBlbGVtZW50cyByZWN0cyB0byBkcmFnUmVjdCxcbiAgICAgICAgLy8gc2hvdyBtdWx0aSBkcmFnIGVsZW1lbnRzLFxuICAgICAgICAvLyBhbmltYXRlIHRvIHJlY3RzLFxuICAgICAgICAvLyB1bnNldCByZWN0cyAmIHJlbW92ZSBmcm9tIERPTVxuXG4gICAgICAgIHNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmFuaW1hdGlvbikge1xuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50ID09PSBkcmFnRWwkMSkgcmV0dXJuO1xuICAgICAgICAgICAgY3NzKG11bHRpRHJhZ0VsZW1lbnQsICdwb3NpdGlvbicsICdhYnNvbHV0ZScpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAgIHZhciBkcmFnUmVjdCA9IGdldFJlY3QoZHJhZ0VsJDEsIGZhbHNlLCB0cnVlLCB0cnVlKTtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICBpZiAobXVsdGlEcmFnRWxlbWVudCA9PT0gZHJhZ0VsJDEpIHJldHVybjtcbiAgICAgICAgICAgIHNldFJlY3QobXVsdGlEcmFnRWxlbWVudCwgZHJhZ1JlY3QpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAgIGZvbGRpbmcgPSB0cnVlO1xuICAgICAgICAgIGluaXRpYWxGb2xkaW5nID0gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgc29ydGFibGUuYW5pbWF0ZUFsbChmdW5jdGlvbiAoKSB7XG4gICAgICAgIGZvbGRpbmcgPSBmYWxzZTtcbiAgICAgICAgaW5pdGlhbEZvbGRpbmcgPSBmYWxzZTtcbiAgICAgICAgaWYgKF90aGlzMi5vcHRpb25zLmFuaW1hdGlvbikge1xuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgICAgIHVuc2V0UmVjdChtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIFJlbW92ZSBhbGwgYXV4aWxpYXJ5IG11bHRpZHJhZyBpdGVtcyBmcm9tIGVsLCBpZiBzb3J0aW5nIGVuYWJsZWRcbiAgICAgICAgaWYgKF90aGlzMi5vcHRpb25zLnNvcnQpIHtcbiAgICAgICAgICByZW1vdmVNdWx0aURyYWdFbGVtZW50cygpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9LFxuICAgIGRyYWdPdmVyOiBmdW5jdGlvbiBkcmFnT3ZlcihfcmVmOCkge1xuICAgICAgdmFyIHRhcmdldCA9IF9yZWY4LnRhcmdldCxcbiAgICAgICAgY29tcGxldGVkID0gX3JlZjguY29tcGxldGVkLFxuICAgICAgICBjYW5jZWwgPSBfcmVmOC5jYW5jZWw7XG4gICAgICBpZiAoZm9sZGluZyAmJiB+bXVsdGlEcmFnRWxlbWVudHMuaW5kZXhPZih0YXJnZXQpKSB7XG4gICAgICAgIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgICAgIGNhbmNlbCgpO1xuICAgICAgfVxuICAgIH0sXG4gICAgcmV2ZXJ0OiBmdW5jdGlvbiByZXZlcnQoX3JlZjkpIHtcbiAgICAgIHZhciBmcm9tU29ydGFibGUgPSBfcmVmOS5mcm9tU29ydGFibGUsXG4gICAgICAgIHJvb3RFbCA9IF9yZWY5LnJvb3RFbCxcbiAgICAgICAgc29ydGFibGUgPSBfcmVmOS5zb3J0YWJsZSxcbiAgICAgICAgZHJhZ1JlY3QgPSBfcmVmOS5kcmFnUmVjdDtcbiAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50cy5sZW5ndGggPiAxKSB7XG4gICAgICAgIC8vIFNldHVwIHVuZm9sZCBhbmltYXRpb25cbiAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgIHNvcnRhYmxlLmFkZEFuaW1hdGlvblN0YXRlKHtcbiAgICAgICAgICAgIHRhcmdldDogbXVsdGlEcmFnRWxlbWVudCxcbiAgICAgICAgICAgIHJlY3Q6IGZvbGRpbmcgPyBnZXRSZWN0KG11bHRpRHJhZ0VsZW1lbnQpIDogZHJhZ1JlY3RcbiAgICAgICAgICB9KTtcbiAgICAgICAgICB1bnNldFJlY3QobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudC5mcm9tUmVjdCA9IGRyYWdSZWN0O1xuICAgICAgICAgIGZyb21Tb3J0YWJsZS5yZW1vdmVBbmltYXRpb25TdGF0ZShtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgICAgfSk7XG4gICAgICAgIGZvbGRpbmcgPSBmYWxzZTtcbiAgICAgICAgaW5zZXJ0TXVsdGlEcmFnRWxlbWVudHMoIXRoaXMub3B0aW9ucy5yZW1vdmVDbG9uZU9uSGlkZSwgcm9vdEVsKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGRyYWdPdmVyQ29tcGxldGVkOiBmdW5jdGlvbiBkcmFnT3ZlckNvbXBsZXRlZChfcmVmMTApIHtcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWYxMC5zb3J0YWJsZSxcbiAgICAgICAgaXNPd25lciA9IF9yZWYxMC5pc093bmVyLFxuICAgICAgICBpbnNlcnRpb24gPSBfcmVmMTAuaW5zZXJ0aW9uLFxuICAgICAgICBhY3RpdmVTb3J0YWJsZSA9IF9yZWYxMC5hY3RpdmVTb3J0YWJsZSxcbiAgICAgICAgcGFyZW50RWwgPSBfcmVmMTAucGFyZW50RWwsXG4gICAgICAgIHB1dFNvcnRhYmxlID0gX3JlZjEwLnB1dFNvcnRhYmxlO1xuICAgICAgdmFyIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG4gICAgICBpZiAoaW5zZXJ0aW9uKSB7XG4gICAgICAgIC8vIENsb25lcyBtdXN0IGJlIGhpZGRlbiBiZWZvcmUgZm9sZGluZyBhbmltYXRpb24gdG8gY2FwdHVyZSBkcmFnUmVjdEFic29sdXRlIHByb3Blcmx5XG4gICAgICAgIGlmIChpc093bmVyKSB7XG4gICAgICAgICAgYWN0aXZlU29ydGFibGUuX2hpZGVDbG9uZSgpO1xuICAgICAgICB9XG4gICAgICAgIGluaXRpYWxGb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIC8vIElmIGxlYXZpbmcgc29ydDpmYWxzZSByb290LCBvciBhbHJlYWR5IGZvbGRpbmcgLSBGb2xkIHRvIG5ldyBsb2NhdGlvblxuICAgICAgICBpZiAob3B0aW9ucy5hbmltYXRpb24gJiYgbXVsdGlEcmFnRWxlbWVudHMubGVuZ3RoID4gMSAmJiAoZm9sZGluZyB8fCAhaXNPd25lciAmJiAhYWN0aXZlU29ydGFibGUub3B0aW9ucy5zb3J0ICYmICFwdXRTb3J0YWJsZSkpIHtcbiAgICAgICAgICAvLyBGb2xkOiBTZXQgYWxsIG11bHRpIGRyYWcgZWxlbWVudHMncyByZWN0cyB0byBkcmFnRWwncyByZWN0IHdoZW4gbXVsdGktZHJhZyBlbGVtZW50cyBhcmUgaW52aXNpYmxlXG4gICAgICAgICAgdmFyIGRyYWdSZWN0QWJzb2x1dGUgPSBnZXRSZWN0KGRyYWdFbCQxLCBmYWxzZSwgdHJ1ZSwgdHJ1ZSk7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgaWYgKG11bHRpRHJhZ0VsZW1lbnQgPT09IGRyYWdFbCQxKSByZXR1cm47XG4gICAgICAgICAgICBzZXRSZWN0KG11bHRpRHJhZ0VsZW1lbnQsIGRyYWdSZWN0QWJzb2x1dGUpO1xuXG4gICAgICAgICAgICAvLyBNb3ZlIGVsZW1lbnQocykgdG8gZW5kIG9mIHBhcmVudEVsIHNvIHRoYXQgaXQgZG9lcyBub3QgaW50ZXJmZXJlIHdpdGggbXVsdGktZHJhZyBjbG9uZXMgaW5zZXJ0aW9uIGlmIHRoZXkgYXJlIGluc2VydGVkXG4gICAgICAgICAgICAvLyB3aGlsZSBmb2xkaW5nLCBhbmQgc28gdGhhdCB3ZSBjYW4gY2FwdHVyZSB0aGVtIGFnYWluIGJlY2F1c2Ugb2xkIHNvcnRhYmxlIHdpbGwgbm8gbG9uZ2VyIGJlIGZyb21Tb3J0YWJsZVxuICAgICAgICAgICAgcGFyZW50RWwuYXBwZW5kQ2hpbGQobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgICAgfSk7XG4gICAgICAgICAgZm9sZGluZyA9IHRydWU7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBDbG9uZXMgbXVzdCBiZSBzaG93biAoYW5kIGNoZWNrIHRvIHJlbW92ZSBtdWx0aSBkcmFncykgYWZ0ZXIgZm9sZGluZyB3aGVuIGludGVyZmVyaW5nIG11bHRpRHJhZ0VsZW1lbnRzIGFyZSBtb3ZlZCBvdXRcbiAgICAgICAgaWYgKCFpc093bmVyKSB7XG4gICAgICAgICAgLy8gT25seSByZW1vdmUgaWYgbm90IGZvbGRpbmcgKGZvbGRpbmcgd2lsbCByZW1vdmUgdGhlbSBhbnl3YXlzKVxuICAgICAgICAgIGlmICghZm9sZGluZykge1xuICAgICAgICAgICAgcmVtb3ZlTXVsdGlEcmFnRWxlbWVudHMoKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYgKG11bHRpRHJhZ0VsZW1lbnRzLmxlbmd0aCA+IDEpIHtcbiAgICAgICAgICAgIHZhciBjbG9uZXNIaWRkZW5CZWZvcmUgPSBjbG9uZXNIaWRkZW47XG4gICAgICAgICAgICBhY3RpdmVTb3J0YWJsZS5fc2hvd0Nsb25lKHNvcnRhYmxlKTtcblxuICAgICAgICAgICAgLy8gVW5mb2xkIGFuaW1hdGlvbiBmb3IgY2xvbmVzIGlmIHNob3dpbmcgZnJvbSBoaWRkZW5cbiAgICAgICAgICAgIGlmIChhY3RpdmVTb3J0YWJsZS5vcHRpb25zLmFuaW1hdGlvbiAmJiAhY2xvbmVzSGlkZGVuICYmIGNsb25lc0hpZGRlbkJlZm9yZSkge1xuICAgICAgICAgICAgICBtdWx0aURyYWdDbG9uZXMuZm9yRWFjaChmdW5jdGlvbiAoY2xvbmUpIHtcbiAgICAgICAgICAgICAgICBhY3RpdmVTb3J0YWJsZS5hZGRBbmltYXRpb25TdGF0ZSh7XG4gICAgICAgICAgICAgICAgICB0YXJnZXQ6IGNsb25lLFxuICAgICAgICAgICAgICAgICAgcmVjdDogY2xvbmVzRnJvbVJlY3RcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBjbG9uZS5mcm9tUmVjdCA9IGNsb25lc0Zyb21SZWN0O1xuICAgICAgICAgICAgICAgIGNsb25lLnRoaXNBbmltYXRpb25EdXJhdGlvbiA9IG51bGw7XG4gICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBhY3RpdmVTb3J0YWJsZS5fc2hvd0Nsb25lKHNvcnRhYmxlKTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9LFxuICAgIGRyYWdPdmVyQW5pbWF0aW9uQ2FwdHVyZTogZnVuY3Rpb24gZHJhZ092ZXJBbmltYXRpb25DYXB0dXJlKF9yZWYxMSkge1xuICAgICAgdmFyIGRyYWdSZWN0ID0gX3JlZjExLmRyYWdSZWN0LFxuICAgICAgICBpc093bmVyID0gX3JlZjExLmlzT3duZXIsXG4gICAgICAgIGFjdGl2ZVNvcnRhYmxlID0gX3JlZjExLmFjdGl2ZVNvcnRhYmxlO1xuICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICBtdWx0aURyYWdFbGVtZW50LnRoaXNBbmltYXRpb25EdXJhdGlvbiA9IG51bGw7XG4gICAgICB9KTtcbiAgICAgIGlmIChhY3RpdmVTb3J0YWJsZS5vcHRpb25zLmFuaW1hdGlvbiAmJiAhaXNPd25lciAmJiBhY3RpdmVTb3J0YWJsZS5tdWx0aURyYWcuaXNNdWx0aURyYWcpIHtcbiAgICAgICAgY2xvbmVzRnJvbVJlY3QgPSBfZXh0ZW5kcyh7fSwgZHJhZ1JlY3QpO1xuICAgICAgICB2YXIgZHJhZ01hdHJpeCA9IG1hdHJpeChkcmFnRWwkMSwgdHJ1ZSk7XG4gICAgICAgIGNsb25lc0Zyb21SZWN0LnRvcCAtPSBkcmFnTWF0cml4LmY7XG4gICAgICAgIGNsb25lc0Zyb21SZWN0LmxlZnQgLT0gZHJhZ01hdHJpeC5lO1xuICAgICAgfVxuICAgIH0sXG4gICAgZHJhZ092ZXJBbmltYXRpb25Db21wbGV0ZTogZnVuY3Rpb24gZHJhZ092ZXJBbmltYXRpb25Db21wbGV0ZSgpIHtcbiAgICAgIGlmIChmb2xkaW5nKSB7XG4gICAgICAgIGZvbGRpbmcgPSBmYWxzZTtcbiAgICAgICAgcmVtb3ZlTXVsdGlEcmFnRWxlbWVudHMoKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGRyb3A6IGZ1bmN0aW9uIGRyb3AoX3JlZjEyKSB7XG4gICAgICB2YXIgZXZ0ID0gX3JlZjEyLm9yaWdpbmFsRXZlbnQsXG4gICAgICAgIHJvb3RFbCA9IF9yZWYxMi5yb290RWwsXG4gICAgICAgIHBhcmVudEVsID0gX3JlZjEyLnBhcmVudEVsLFxuICAgICAgICBzb3J0YWJsZSA9IF9yZWYxMi5zb3J0YWJsZSxcbiAgICAgICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50ID0gX3JlZjEyLmRpc3BhdGNoU29ydGFibGVFdmVudCxcbiAgICAgICAgb2xkSW5kZXggPSBfcmVmMTIub2xkSW5kZXgsXG4gICAgICAgIHB1dFNvcnRhYmxlID0gX3JlZjEyLnB1dFNvcnRhYmxlO1xuICAgICAgdmFyIHRvU29ydGFibGUgPSBwdXRTb3J0YWJsZSB8fCB0aGlzLnNvcnRhYmxlO1xuICAgICAgaWYgKCFldnQpIHJldHVybjtcbiAgICAgIHZhciBvcHRpb25zID0gdGhpcy5vcHRpb25zLFxuICAgICAgICBjaGlsZHJlbiA9IHBhcmVudEVsLmNoaWxkcmVuO1xuXG4gICAgICAvLyBNdWx0aS1kcmFnIHNlbGVjdGlvblxuICAgICAgaWYgKCFkcmFnU3RhcnRlZCkge1xuICAgICAgICBpZiAob3B0aW9ucy5tdWx0aURyYWdLZXkgJiYgIXRoaXMubXVsdGlEcmFnS2V5RG93bikge1xuICAgICAgICAgIHRoaXMuX2Rlc2VsZWN0TXVsdGlEcmFnKCk7XG4gICAgICAgIH1cbiAgICAgICAgdG9nZ2xlQ2xhc3MoZHJhZ0VsJDEsIG9wdGlvbnMuc2VsZWN0ZWRDbGFzcywgIX5tdWx0aURyYWdFbGVtZW50cy5pbmRleE9mKGRyYWdFbCQxKSk7XG4gICAgICAgIGlmICghfm11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YoZHJhZ0VsJDEpKSB7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMucHVzaChkcmFnRWwkMSk7XG4gICAgICAgICAgZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICBzb3J0YWJsZTogc29ydGFibGUsXG4gICAgICAgICAgICByb290RWw6IHJvb3RFbCxcbiAgICAgICAgICAgIG5hbWU6ICdzZWxlY3QnLFxuICAgICAgICAgICAgdGFyZ2V0RWw6IGRyYWdFbCQxLFxuICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgfSk7XG5cbiAgICAgICAgICAvLyBNb2RpZmllciBhY3RpdmF0ZWQsIHNlbGVjdCBmcm9tIGxhc3QgdG8gZHJhZ0VsXG4gICAgICAgICAgaWYgKGV2dC5zaGlmdEtleSAmJiBsYXN0TXVsdGlEcmFnU2VsZWN0ICYmIHNvcnRhYmxlLmVsLmNvbnRhaW5zKGxhc3RNdWx0aURyYWdTZWxlY3QpKSB7XG4gICAgICAgICAgICB2YXIgbGFzdEluZGV4ID0gaW5kZXgobGFzdE11bHRpRHJhZ1NlbGVjdCksXG4gICAgICAgICAgICAgIGN1cnJlbnRJbmRleCA9IGluZGV4KGRyYWdFbCQxKTtcbiAgICAgICAgICAgIGlmICh+bGFzdEluZGV4ICYmIH5jdXJyZW50SW5kZXggJiYgbGFzdEluZGV4ICE9PSBjdXJyZW50SW5kZXgpIHtcbiAgICAgICAgICAgICAgLy8gTXVzdCBpbmNsdWRlIGxhc3RNdWx0aURyYWdTZWxlY3QgKHNlbGVjdCBpdCksIGluIGNhc2UgbW9kaWZpZWQgc2VsZWN0aW9uIGZyb20gbm8gc2VsZWN0aW9uXG4gICAgICAgICAgICAgIC8vIChidXQgcHJldmlvdXMgc2VsZWN0aW9uIGV4aXN0ZWQpXG4gICAgICAgICAgICAgIHZhciBuLCBpO1xuICAgICAgICAgICAgICBpZiAoY3VycmVudEluZGV4ID4gbGFzdEluZGV4KSB7XG4gICAgICAgICAgICAgICAgaSA9IGxhc3RJbmRleDtcbiAgICAgICAgICAgICAgICBuID0gY3VycmVudEluZGV4O1xuICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGkgPSBjdXJyZW50SW5kZXg7XG4gICAgICAgICAgICAgICAgbiA9IGxhc3RJbmRleCArIDE7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgZm9yICg7IGkgPCBuOyBpKyspIHtcbiAgICAgICAgICAgICAgICBpZiAofm11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YoY2hpbGRyZW5baV0pKSBjb250aW51ZTtcbiAgICAgICAgICAgICAgICB0b2dnbGVDbGFzcyhjaGlsZHJlbltpXSwgb3B0aW9ucy5zZWxlY3RlZENsYXNzLCB0cnVlKTtcbiAgICAgICAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5wdXNoKGNoaWxkcmVuW2ldKTtcbiAgICAgICAgICAgICAgICBkaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgICAgICAgIHNvcnRhYmxlOiBzb3J0YWJsZSxcbiAgICAgICAgICAgICAgICAgIHJvb3RFbDogcm9vdEVsLFxuICAgICAgICAgICAgICAgICAgbmFtZTogJ3NlbGVjdCcsXG4gICAgICAgICAgICAgICAgICB0YXJnZXRFbDogY2hpbGRyZW5baV0sXG4gICAgICAgICAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsYXN0TXVsdGlEcmFnU2VsZWN0ID0gZHJhZ0VsJDE7XG4gICAgICAgICAgfVxuICAgICAgICAgIG11bHRpRHJhZ1NvcnRhYmxlID0gdG9Tb3J0YWJsZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5zcGxpY2UobXVsdGlEcmFnRWxlbWVudHMuaW5kZXhPZihkcmFnRWwkMSksIDEpO1xuICAgICAgICAgIGxhc3RNdWx0aURyYWdTZWxlY3QgPSBudWxsO1xuICAgICAgICAgIGRpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgc29ydGFibGU6IHNvcnRhYmxlLFxuICAgICAgICAgICAgcm9vdEVsOiByb290RWwsXG4gICAgICAgICAgICBuYW1lOiAnZGVzZWxlY3QnLFxuICAgICAgICAgICAgdGFyZ2V0RWw6IGRyYWdFbCQxLFxuICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgLy8gTXVsdGktZHJhZyBkcm9wXG4gICAgICBpZiAoZHJhZ1N0YXJ0ZWQgJiYgdGhpcy5pc011bHRpRHJhZykge1xuICAgICAgICBmb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIC8vIERvIG5vdCBcInVuZm9sZFwiIGFmdGVyIGFyb3VuZCBkcmFnRWwgaWYgcmV2ZXJ0ZWRcbiAgICAgICAgaWYgKChwYXJlbnRFbFtleHBhbmRvXS5vcHRpb25zLnNvcnQgfHwgcGFyZW50RWwgIT09IHJvb3RFbCkgJiYgbXVsdGlEcmFnRWxlbWVudHMubGVuZ3RoID4gMSkge1xuICAgICAgICAgIHZhciBkcmFnUmVjdCA9IGdldFJlY3QoZHJhZ0VsJDEpLFxuICAgICAgICAgICAgbXVsdGlEcmFnSW5kZXggPSBpbmRleChkcmFnRWwkMSwgJzpub3QoLicgKyB0aGlzLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcyArICcpJyk7XG4gICAgICAgICAgaWYgKCFpbml0aWFsRm9sZGluZyAmJiBvcHRpb25zLmFuaW1hdGlvbikgZHJhZ0VsJDEudGhpc0FuaW1hdGlvbkR1cmF0aW9uID0gbnVsbDtcbiAgICAgICAgICB0b1NvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgICAgICAgIGlmICghaW5pdGlhbEZvbGRpbmcpIHtcbiAgICAgICAgICAgIGlmIChvcHRpb25zLmFuaW1hdGlvbikge1xuICAgICAgICAgICAgICBkcmFnRWwkMS5mcm9tUmVjdCA9IGRyYWdSZWN0O1xuICAgICAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudC50aGlzQW5pbWF0aW9uRHVyYXRpb24gPSBudWxsO1xuICAgICAgICAgICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50ICE9PSBkcmFnRWwkMSkge1xuICAgICAgICAgICAgICAgICAgdmFyIHJlY3QgPSBmb2xkaW5nID8gZ2V0UmVjdChtdWx0aURyYWdFbGVtZW50KSA6IGRyYWdSZWN0O1xuICAgICAgICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudC5mcm9tUmVjdCA9IHJlY3Q7XG5cbiAgICAgICAgICAgICAgICAgIC8vIFByZXBhcmUgdW5mb2xkIGFuaW1hdGlvblxuICAgICAgICAgICAgICAgICAgdG9Tb3J0YWJsZS5hZGRBbmltYXRpb25TdGF0ZSh7XG4gICAgICAgICAgICAgICAgICAgIHRhcmdldDogbXVsdGlEcmFnRWxlbWVudCxcbiAgICAgICAgICAgICAgICAgICAgcmVjdDogcmVjdFxuICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy8gTXVsdGkgZHJhZyBlbGVtZW50cyBhcmUgbm90IG5lY2Vzc2FyaWx5IHJlbW92ZWQgZnJvbSB0aGUgRE9NIG9uIGRyb3AsIHNvIHRvIHJlaW5zZXJ0XG4gICAgICAgICAgICAvLyBwcm9wZXJseSB0aGV5IG11c3QgYWxsIGJlIHJlbW92ZWRcbiAgICAgICAgICAgIHJlbW92ZU11bHRpRHJhZ0VsZW1lbnRzKCk7XG4gICAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICAgIGlmIChjaGlsZHJlblttdWx0aURyYWdJbmRleF0pIHtcbiAgICAgICAgICAgICAgICBwYXJlbnRFbC5pbnNlcnRCZWZvcmUobXVsdGlEcmFnRWxlbWVudCwgY2hpbGRyZW5bbXVsdGlEcmFnSW5kZXhdKTtcbiAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBwYXJlbnRFbC5hcHBlbmRDaGlsZChtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICBtdWx0aURyYWdJbmRleCsrO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIC8vIElmIGluaXRpYWwgZm9sZGluZyBpcyBkb25lLCB0aGUgZWxlbWVudHMgbWF5IGhhdmUgY2hhbmdlZCBwb3NpdGlvbiBiZWNhdXNlIHRoZXkgYXJlIG5vd1xuICAgICAgICAgICAgLy8gdW5mb2xkaW5nIGFyb3VuZCBkcmFnRWwsIGV2ZW4gdGhvdWdoIGRyYWdFbCBtYXkgbm90IGhhdmUgaGlzIGluZGV4IGNoYW5nZWQsIHNvIHVwZGF0ZSBldmVudFxuICAgICAgICAgICAgLy8gbXVzdCBiZSBmaXJlZCBoZXJlIGFzIFNvcnRhYmxlIHdpbGwgbm90LlxuICAgICAgICAgICAgaWYgKG9sZEluZGV4ID09PSBpbmRleChkcmFnRWwkMSkpIHtcbiAgICAgICAgICAgICAgdmFyIHVwZGF0ZSA9IGZhbHNlO1xuICAgICAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICAgICAgaWYgKG11bHRpRHJhZ0VsZW1lbnQuc29ydGFibGVJbmRleCAhPT0gaW5kZXgobXVsdGlEcmFnRWxlbWVudCkpIHtcbiAgICAgICAgICAgICAgICAgIHVwZGF0ZSA9IHRydWU7XG4gICAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgaWYgKHVwZGF0ZSkge1xuICAgICAgICAgICAgICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCgndXBkYXRlJyk7XG4gICAgICAgICAgICAgICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50KCdzb3J0Jyk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG5cbiAgICAgICAgICAvLyBNdXN0IGJlIGRvbmUgYWZ0ZXIgY2FwdHVyaW5nIGluZGl2aWR1YWwgcmVjdHMgKHNjcm9sbCBiYXIpXG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgdW5zZXRSZWN0KG11bHRpRHJhZ0VsZW1lbnQpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAgIHRvU29ydGFibGUuYW5pbWF0ZUFsbCgpO1xuICAgICAgICB9XG4gICAgICAgIG11bHRpRHJhZ1NvcnRhYmxlID0gdG9Tb3J0YWJsZTtcbiAgICAgIH1cblxuICAgICAgLy8gUmVtb3ZlIGNsb25lcyBpZiBuZWNlc3NhcnlcbiAgICAgIGlmIChyb290RWwgPT09IHBhcmVudEVsIHx8IHB1dFNvcnRhYmxlICYmIHB1dFNvcnRhYmxlLmxhc3RQdXRNb2RlICE9PSAnY2xvbmUnKSB7XG4gICAgICAgIG11bHRpRHJhZ0Nsb25lcy5mb3JFYWNoKGZ1bmN0aW9uIChjbG9uZSkge1xuICAgICAgICAgIGNsb25lLnBhcmVudE5vZGUgJiYgY2xvbmUucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChjbG9uZSk7XG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgIH0sXG4gICAgbnVsbGluZ0dsb2JhbDogZnVuY3Rpb24gbnVsbGluZ0dsb2JhbCgpIHtcbiAgICAgIHRoaXMuaXNNdWx0aURyYWcgPSBkcmFnU3RhcnRlZCA9IGZhbHNlO1xuICAgICAgbXVsdGlEcmFnQ2xvbmVzLmxlbmd0aCA9IDA7XG4gICAgfSxcbiAgICBkZXN0cm95R2xvYmFsOiBmdW5jdGlvbiBkZXN0cm95R2xvYmFsKCkge1xuICAgICAgdGhpcy5fZGVzZWxlY3RNdWx0aURyYWcoKTtcbiAgICAgIG9mZihkb2N1bWVudCwgJ3BvaW50ZXJ1cCcsIHRoaXMuX2Rlc2VsZWN0TXVsdGlEcmFnKTtcbiAgICAgIG9mZihkb2N1bWVudCwgJ21vdXNldXAnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICBvZmYoZG9jdW1lbnQsICd0b3VjaGVuZCcsIHRoaXMuX2Rlc2VsZWN0TXVsdGlEcmFnKTtcbiAgICAgIG9mZihkb2N1bWVudCwgJ2tleWRvd24nLCB0aGlzLl9jaGVja0tleURvd24pO1xuICAgICAgb2ZmKGRvY3VtZW50LCAna2V5dXAnLCB0aGlzLl9jaGVja0tleVVwKTtcbiAgICB9LFxuICAgIF9kZXNlbGVjdE11bHRpRHJhZzogZnVuY3Rpb24gX2Rlc2VsZWN0TXVsdGlEcmFnKGV2dCkge1xuICAgICAgaWYgKHR5cGVvZiBkcmFnU3RhcnRlZCAhPT0gXCJ1bmRlZmluZWRcIiAmJiBkcmFnU3RhcnRlZCkgcmV0dXJuO1xuXG4gICAgICAvLyBPbmx5IGRlc2VsZWN0IGlmIHNlbGVjdGlvbiBpcyBpbiB0aGlzIHNvcnRhYmxlXG4gICAgICBpZiAobXVsdGlEcmFnU29ydGFibGUgIT09IHRoaXMuc29ydGFibGUpIHJldHVybjtcblxuICAgICAgLy8gT25seSBkZXNlbGVjdCBpZiB0YXJnZXQgaXMgbm90IGl0ZW0gaW4gdGhpcyBzb3J0YWJsZVxuICAgICAgaWYgKGV2dCAmJiBjbG9zZXN0KGV2dC50YXJnZXQsIHRoaXMub3B0aW9ucy5kcmFnZ2FibGUsIHRoaXMuc29ydGFibGUuZWwsIGZhbHNlKSkgcmV0dXJuO1xuXG4gICAgICAvLyBPbmx5IGRlc2VsZWN0IGlmIGxlZnQgY2xpY2tcbiAgICAgIGlmIChldnQgJiYgZXZ0LmJ1dHRvbiAhPT0gMCkgcmV0dXJuO1xuICAgICAgd2hpbGUgKG11bHRpRHJhZ0VsZW1lbnRzLmxlbmd0aCkge1xuICAgICAgICB2YXIgZWwgPSBtdWx0aURyYWdFbGVtZW50c1swXTtcbiAgICAgICAgdG9nZ2xlQ2xhc3MoZWwsIHRoaXMub3B0aW9ucy5zZWxlY3RlZENsYXNzLCBmYWxzZSk7XG4gICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLnNoaWZ0KCk7XG4gICAgICAgIGRpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLnNvcnRhYmxlLFxuICAgICAgICAgIHJvb3RFbDogdGhpcy5zb3J0YWJsZS5lbCxcbiAgICAgICAgICBuYW1lOiAnZGVzZWxlY3QnLFxuICAgICAgICAgIHRhcmdldEVsOiBlbCxcbiAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgfSk7XG4gICAgICB9XG4gICAgfSxcbiAgICBfY2hlY2tLZXlEb3duOiBmdW5jdGlvbiBfY2hlY2tLZXlEb3duKGV2dCkge1xuICAgICAgaWYgKGV2dC5rZXkgPT09IHRoaXMub3B0aW9ucy5tdWx0aURyYWdLZXkpIHtcbiAgICAgICAgdGhpcy5tdWx0aURyYWdLZXlEb3duID0gdHJ1ZTtcbiAgICAgIH1cbiAgICB9LFxuICAgIF9jaGVja0tleVVwOiBmdW5jdGlvbiBfY2hlY2tLZXlVcChldnQpIHtcbiAgICAgIGlmIChldnQua2V5ID09PSB0aGlzLm9wdGlvbnMubXVsdGlEcmFnS2V5KSB7XG4gICAgICAgIHRoaXMubXVsdGlEcmFnS2V5RG93biA9IGZhbHNlO1xuICAgICAgfVxuICAgIH1cbiAgfTtcbiAgcmV0dXJuIF9leHRlbmRzKE11bHRpRHJhZywge1xuICAgIC8vIFN0YXRpYyBtZXRob2RzICYgcHJvcGVydGllc1xuICAgIHBsdWdpbk5hbWU6ICdtdWx0aURyYWcnLFxuICAgIHV0aWxzOiB7XG4gICAgICAvKipcclxuICAgICAgICogU2VsZWN0cyB0aGUgcHJvdmlkZWQgbXVsdGktZHJhZyBpdGVtXHJcbiAgICAgICAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSBlbCAgICBUaGUgZWxlbWVudCB0byBiZSBzZWxlY3RlZFxyXG4gICAgICAgKi9cbiAgICAgIHNlbGVjdDogZnVuY3Rpb24gc2VsZWN0KGVsKSB7XG4gICAgICAgIHZhciBzb3J0YWJsZSA9IGVsLnBhcmVudE5vZGVbZXhwYW5kb107XG4gICAgICAgIGlmICghc29ydGFibGUgfHwgIXNvcnRhYmxlLm9wdGlvbnMubXVsdGlEcmFnIHx8IH5tdWx0aURyYWdFbGVtZW50cy5pbmRleE9mKGVsKSkgcmV0dXJuO1xuICAgICAgICBpZiAobXVsdGlEcmFnU29ydGFibGUgJiYgbXVsdGlEcmFnU29ydGFibGUgIT09IHNvcnRhYmxlKSB7XG4gICAgICAgICAgbXVsdGlEcmFnU29ydGFibGUubXVsdGlEcmFnLl9kZXNlbGVjdE11bHRpRHJhZygpO1xuICAgICAgICAgIG11bHRpRHJhZ1NvcnRhYmxlID0gc29ydGFibGU7XG4gICAgICAgIH1cbiAgICAgICAgdG9nZ2xlQ2xhc3MoZWwsIHNvcnRhYmxlLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcywgdHJ1ZSk7XG4gICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLnB1c2goZWwpO1xuICAgICAgfSxcbiAgICAgIC8qKlxyXG4gICAgICAgKiBEZXNlbGVjdHMgdGhlIHByb3ZpZGVkIG11bHRpLWRyYWcgaXRlbVxyXG4gICAgICAgKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWwgICAgVGhlIGVsZW1lbnQgdG8gYmUgZGVzZWxlY3RlZFxyXG4gICAgICAgKi9cbiAgICAgIGRlc2VsZWN0OiBmdW5jdGlvbiBkZXNlbGVjdChlbCkge1xuICAgICAgICB2YXIgc29ydGFibGUgPSBlbC5wYXJlbnROb2RlW2V4cGFuZG9dLFxuICAgICAgICAgIGluZGV4ID0gbXVsdGlEcmFnRWxlbWVudHMuaW5kZXhPZihlbCk7XG4gICAgICAgIGlmICghc29ydGFibGUgfHwgIXNvcnRhYmxlLm9wdGlvbnMubXVsdGlEcmFnIHx8ICF+aW5kZXgpIHJldHVybjtcbiAgICAgICAgdG9nZ2xlQ2xhc3MoZWwsIHNvcnRhYmxlLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcywgZmFsc2UpO1xuICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5zcGxpY2UoaW5kZXgsIDEpO1xuICAgICAgfVxuICAgIH0sXG4gICAgZXZlbnRQcm9wZXJ0aWVzOiBmdW5jdGlvbiBldmVudFByb3BlcnRpZXMoKSB7XG4gICAgICB2YXIgX3RoaXMzID0gdGhpcztcbiAgICAgIHZhciBvbGRJbmRpY2llcyA9IFtdLFxuICAgICAgICBuZXdJbmRpY2llcyA9IFtdO1xuICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICBvbGRJbmRpY2llcy5wdXNoKHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50OiBtdWx0aURyYWdFbGVtZW50LFxuICAgICAgICAgIGluZGV4OiBtdWx0aURyYWdFbGVtZW50LnNvcnRhYmxlSW5kZXhcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gbXVsdGlEcmFnRWxlbWVudHMgd2lsbCBhbHJlYWR5IGJlIHNvcnRlZCBpZiBmb2xkaW5nXG4gICAgICAgIHZhciBuZXdJbmRleDtcbiAgICAgICAgaWYgKGZvbGRpbmcgJiYgbXVsdGlEcmFnRWxlbWVudCAhPT0gZHJhZ0VsJDEpIHtcbiAgICAgICAgICBuZXdJbmRleCA9IC0xO1xuICAgICAgICB9IGVsc2UgaWYgKGZvbGRpbmcpIHtcbiAgICAgICAgICBuZXdJbmRleCA9IGluZGV4KG11bHRpRHJhZ0VsZW1lbnQsICc6bm90KC4nICsgX3RoaXMzLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcyArICcpJyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgbmV3SW5kZXggPSBpbmRleChtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgICAgfVxuICAgICAgICBuZXdJbmRpY2llcy5wdXNoKHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50OiBtdWx0aURyYWdFbGVtZW50LFxuICAgICAgICAgIGluZGV4OiBuZXdJbmRleFxuICAgICAgICB9KTtcbiAgICAgIH0pO1xuICAgICAgcmV0dXJuIHtcbiAgICAgICAgaXRlbXM6IF90b0NvbnN1bWFibGVBcnJheShtdWx0aURyYWdFbGVtZW50cyksXG4gICAgICAgIGNsb25lczogW10uY29uY2F0KG11bHRpRHJhZ0Nsb25lcyksXG4gICAgICAgIG9sZEluZGljaWVzOiBvbGRJbmRpY2llcyxcbiAgICAgICAgbmV3SW5kaWNpZXM6IG5ld0luZGljaWVzXG4gICAgICB9O1xuICAgIH0sXG4gICAgb3B0aW9uTGlzdGVuZXJzOiB7XG4gICAgICBtdWx0aURyYWdLZXk6IGZ1bmN0aW9uIG11bHRpRHJhZ0tleShrZXkpIHtcbiAgICAgICAga2V5ID0ga2V5LnRvTG93ZXJDYXNlKCk7XG4gICAgICAgIGlmIChrZXkgPT09ICdjdHJsJykge1xuICAgICAgICAgIGtleSA9ICdDb250cm9sJztcbiAgICAgICAgfSBlbHNlIGlmIChrZXkubGVuZ3RoID4gMSkge1xuICAgICAgICAgIGtleSA9IGtleS5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIGtleS5zdWJzdHIoMSk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGtleTtcbiAgICAgIH1cbiAgICB9XG4gIH0pO1xufVxuZnVuY3Rpb24gaW5zZXJ0TXVsdGlEcmFnRWxlbWVudHMoY2xvbmVzSW5zZXJ0ZWQsIHJvb3RFbCkge1xuICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50LCBpKSB7XG4gICAgdmFyIHRhcmdldCA9IHJvb3RFbC5jaGlsZHJlblttdWx0aURyYWdFbGVtZW50LnNvcnRhYmxlSW5kZXggKyAoY2xvbmVzSW5zZXJ0ZWQgPyBOdW1iZXIoaSkgOiAwKV07XG4gICAgaWYgKHRhcmdldCkge1xuICAgICAgcm9vdEVsLmluc2VydEJlZm9yZShtdWx0aURyYWdFbGVtZW50LCB0YXJnZXQpO1xuICAgIH0gZWxzZSB7XG4gICAgICByb290RWwuYXBwZW5kQ2hpbGQobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgfVxuICB9KTtcbn1cblxuLyoqXHJcbiAqIEluc2VydCBtdWx0aS1kcmFnIGNsb25lc1xyXG4gKiBAcGFyYW0gIHtbQm9vbGVhbl19IGVsZW1lbnRzSW5zZXJ0ZWQgIFdoZXRoZXIgdGhlIG11bHRpLWRyYWcgZWxlbWVudHMgYXJlIGluc2VydGVkXHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSByb290RWxcclxuICovXG5mdW5jdGlvbiBpbnNlcnRNdWx0aURyYWdDbG9uZXMoZWxlbWVudHNJbnNlcnRlZCwgcm9vdEVsKSB7XG4gIG11bHRpRHJhZ0Nsb25lcy5mb3JFYWNoKGZ1bmN0aW9uIChjbG9uZSwgaSkge1xuICAgIHZhciB0YXJnZXQgPSByb290RWwuY2hpbGRyZW5bY2xvbmUuc29ydGFibGVJbmRleCArIChlbGVtZW50c0luc2VydGVkID8gTnVtYmVyKGkpIDogMCldO1xuICAgIGlmICh0YXJnZXQpIHtcbiAgICAgIHJvb3RFbC5pbnNlcnRCZWZvcmUoY2xvbmUsIHRhcmdldCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHJvb3RFbC5hcHBlbmRDaGlsZChjbG9uZSk7XG4gICAgfVxuICB9KTtcbn1cbmZ1bmN0aW9uIHJlbW92ZU11bHRpRHJhZ0VsZW1lbnRzKCkge1xuICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgaWYgKG11bHRpRHJhZ0VsZW1lbnQgPT09IGRyYWdFbCQxKSByZXR1cm47XG4gICAgbXVsdGlEcmFnRWxlbWVudC5wYXJlbnROb2RlICYmIG11bHRpRHJhZ0VsZW1lbnQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChtdWx0aURyYWdFbGVtZW50KTtcbiAgfSk7XG59XG5cblNvcnRhYmxlLm1vdW50KG5ldyBBdXRvU2Nyb2xsUGx1Z2luKCkpO1xuU29ydGFibGUubW91bnQoUmVtb3ZlLCBSZXZlcnQpO1xuXG5leHBvcnQgZGVmYXVsdCBTb3J0YWJsZTtcbmV4cG9ydCB7IE11bHRpRHJhZ1BsdWdpbiBhcyBNdWx0aURyYWcsIFNvcnRhYmxlLCBTd2FwUGx1Z2luIGFzIFN3YXAgfTtcbiIsICJpbXBvcnQgU29ydGFibGUgZnJvbSAnc29ydGFibGVqcydcblxud2luZG93LlNvcnRhYmxlID0gU29ydGFibGVcblxuaWYgKHR5cGVvZiB3aW5kb3cuTGl2ZXdpcmUgPT09ICd1bmRlZmluZWQnKSB7XG4gIHRocm93ICdMaXZld2lyZSBTb3J0YWJsZSBQbHVnaW46IHdpbmRvdy5MaXZld2lyZSBpcyB1bmRlZmluZWQuIE1ha2Ugc3VyZSBAbGl2ZXdpcmVTY3JpcHRzIGlzIHBsYWNlZCBhYm92ZSB0aGlzIHNjcmlwdCBpbmNsdWRlJ1xufVxuXG5jb25zdCBtb3ZlRW5kTW9ycGhNYXJrZXIgPSAoZWwpID0+IHtcbiAgY29uc3QgZW5kTW9ycGhNYXJrZXIgPSBBcnJheS5mcm9tKGVsLmNoaWxkTm9kZXMpLmZpbHRlcigoY2hpbGROb2RlKSA9PiB7XG4gICAgcmV0dXJuIGNoaWxkTm9kZS5ub2RlVHlwZSA9PT0gOCAmJiBbJ1tpZiBFTkRCTE9DS10+PCFbZW5kaWZdJywgJ19fRU5EQkxPQ0tfXyddLmluY2x1ZGVzKGNoaWxkTm9kZS5ub2RlVmFsdWU/LnRyaW0oKSlcbiAgfSlbMF07XG5cbiAgaWYgKGVuZE1vcnBoTWFya2VyKSB7XG4gICAgZWwuYXBwZW5kQ2hpbGQoZW5kTW9ycGhNYXJrZXIpXG4gIH1cbn1cblxuTGl2ZXdpcmUuZGlyZWN0aXZlKCdzb3J0YWJsZScsICh7IGVsLCBkaXJlY3RpdmUsIGNvbXBvbmVudCB9KSA9PiB7XG4gIGlmIChkaXJlY3RpdmUubW9kaWZpZXJzLmxlbmd0aCA+IDApIHtcbiAgICByZXR1cm5cbiAgfVxuXG4gIGxldCBvcHRpb25zID0ge31cblxuICBpZiAoZWwuaGFzQXR0cmlidXRlKCd3aXJlOnNvcnRhYmxlLm9wdGlvbnMnKSkge1xuICAgIG9wdGlvbnMgPSAobmV3IEZ1bmN0aW9uKGByZXR1cm4gJHtlbC5nZXRBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUub3B0aW9ucycpfTtgKSkoKVxuICB9XG5cbiAgZWwubGl2ZXdpcmVfc29ydGFibGUgPSB3aW5kb3cuU29ydGFibGUuY3JlYXRlKGVsLCB7XG4gICAgc29ydDogdHJ1ZSxcbiAgICAuLi5vcHRpb25zLFxuICAgIGRyYWdnYWJsZTogJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaXRlbV0nLFxuICAgIGhhbmRsZTogZWwucXVlcnlTZWxlY3RvcignW3dpcmVcXFxcOnNvcnRhYmxlXFxcXC5oYW5kbGVdJykgPyAnW3dpcmVcXFxcOnNvcnRhYmxlXFxcXC5oYW5kbGVdJyA6IG51bGwsXG4gICAgZGF0YUlkQXR0cjogJ3dpcmU6c29ydGFibGUuaXRlbScsXG4gICAgZ3JvdXA6IHtcbiAgICAgIHB1bGw6IGZhbHNlLFxuICAgICAgcHV0OiBmYWxzZSxcbiAgICAgIC4uLm9wdGlvbnMuZ3JvdXAsXG4gICAgICBuYW1lOiBlbC5nZXRBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUnKSxcbiAgICB9LFxuICAgIHN0b3JlOiB7XG4gICAgICAuLi5vcHRpb25zLnN0b3JlLFxuICAgICAgc2V0OiBmdW5jdGlvbiAoc29ydGFibGUpIHtcbiAgICAgICAgbGV0IGl0ZW1zID0gc29ydGFibGUudG9BcnJheSgpLm1hcCgodmFsdWUsIGluZGV4KSA9PiB7XG4gICAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIG9yZGVyOiBpbmRleCArIDEsXG4gICAgICAgICAgICB2YWx1ZTogdmFsdWUsXG4gICAgICAgICAgfVxuICAgICAgICB9KVxuXG4gICAgICAgIG1vdmVFbmRNb3JwaE1hcmtlcihlbClcblxuICAgICAgICBjb21wb25lbnQuJHdpcmUuY2FsbChkaXJlY3RpdmUubWV0aG9kLCBpdGVtcylcbiAgICAgIH0sXG4gICAgfSxcbiAgfSlcblxuICBsZXQgaGFzU2V0SGFuZGxlQ29ycmVjdGx5ID0gZWwucXVlcnlTZWxlY3RvcignW3dpcmVcXFxcOnNvcnRhYmxlXFxcXC5pdGVtXScpICE9PSBudWxsXG5cbiAgLy8gSWYgdGhlcmUgYXJlIGFscmVhZHkgaXRlbXMsIHRoZW4gdGhlICdoYW5kbGUnIG9wdGlvbiBoYXMgYWxyZWFkeSBiZWVuIGNvcnJlY3RseSBzZXQuXG4gIC8vIFRoZSBvcHRpb24gZG9lcyBub3QgaGF2ZSB0byByZWV2YWx1YXRlZCBhZnRlciB0aGUgbmV4dCBMaXZld2lyZSBjb21wb25lbnQgdXBkYXRlLlxuICBpZiAoaGFzU2V0SGFuZGxlQ29ycmVjdGx5KSB7XG4gICAgcmV0dXJuXG4gIH1cblxuICBjb25zdCBjdXJyZW50Q29tcG9uZW50ID0gY29tcG9uZW50XG5cbiAgTGl2ZXdpcmUuaG9vaygnY29tbWl0JywgKHsgY29tcG9uZW50LCBzdWNjZWVkIH0pID0+IHtcbiAgICBpZiAoY29tcG9uZW50LmlkICE9PSBjdXJyZW50Q29tcG9uZW50LmlkKSB7XG4gICAgICByZXR1cm5cbiAgICB9XG5cbiAgICBpZiAoaGFzU2V0SGFuZGxlQ29ycmVjdGx5KSB7XG4gICAgICByZXR1cm5cbiAgICB9XG5cbiAgICBzdWNjZWVkKCgpID0+IHtcbiAgICAgIHF1ZXVlTWljcm90YXNrKCgpID0+IHtcbiAgICAgICAgZWwubGl2ZXdpcmVfc29ydGFibGUub3B0aW9uKCdoYW5kbGUnLCBlbC5xdWVyeVNlbGVjdG9yKCdbd2lyZVxcXFw6c29ydGFibGVcXFxcLmhhbmRsZV0nKSA/ICdbd2lyZVxcXFw6c29ydGFibGVcXFxcLmhhbmRsZV0nIDogbnVsbClcblxuICAgICAgICBoYXNTZXRIYW5kbGVDb3JyZWN0bHkgPSBlbC5xdWVyeVNlbGVjdG9yKCdbd2lyZVxcXFw6c29ydGFibGVcXFxcLml0ZW1dJykgIT09IG51bGxcbiAgICAgIH0pXG4gICAgfSlcbiAgfSlcbn0pXG5cbkxpdmV3aXJlLmRpcmVjdGl2ZSgnc29ydGFibGUtZ3JvdXAnLCAoeyBlbCwgZGlyZWN0aXZlLCBjb21wb25lbnQgfSkgPT4ge1xuICAvLyBPbmx5IGZpcmUgdGhpcyBoYW5kbGVyIG9uIHRoZSBcInJvb3RcIiBncm91cCBkaXJlY3RpdmUuXG4gIGlmICghIGRpcmVjdGl2ZS5tb2RpZmllcnMuaW5jbHVkZXMoJ2l0ZW0tZ3JvdXAnKSkge1xuICAgIHJldHVyblxuICB9XG5cbiAgbGV0IG9wdGlvbnMgPSB7fVxuXG4gIGlmIChlbC5oYXNBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUtZ3JvdXAub3B0aW9ucycpKSB7XG4gICAgb3B0aW9ucyA9IChuZXcgRnVuY3Rpb24oYHJldHVybiAke2VsLmdldEF0dHJpYnV0ZSgnd2lyZTpzb3J0YWJsZS1ncm91cC5vcHRpb25zJyl9O2ApKSgpO1xuICB9XG5cbiAgZWwubGl2ZXdpcmVfc29ydGFibGUgPSB3aW5kb3cuU29ydGFibGUuY3JlYXRlKGVsLCB7XG4gICAgc29ydDogdHJ1ZSxcbiAgICAuLi5vcHRpb25zLFxuICAgIGRyYWdnYWJsZTogJ1t3aXJlXFxcXDpzb3J0YWJsZS1ncm91cFxcXFwuaXRlbV0nLFxuICAgIGhhbmRsZTogJ1t3aXJlXFxcXDpzb3J0YWJsZS1ncm91cFxcXFwuaGFuZGxlXScsXG4gICAgZGF0YUlkQXR0cjogJ3dpcmU6c29ydGFibGUtZ3JvdXAuaXRlbScsXG4gICAgZ3JvdXA6IHtcbiAgICAgIHB1bGw6IHRydWUsXG4gICAgICBwdXQ6IHRydWUsXG4gICAgICAuLi5vcHRpb25zLmdyb3VwLFxuICAgICAgbmFtZTogZWwuY2xvc2VzdCgnW3dpcmVcXFxcOnNvcnRhYmxlLWdyb3VwXScpLmdldEF0dHJpYnV0ZSgnd2lyZTpzb3J0YWJsZS1ncm91cCcpLFxuICAgIH0sXG4gICAgb25Tb3J0OiAoZXZ0KSA9PiB7XG4gICAgICBpZiAoZXZ0LnRvICE9PSBldnQuZnJvbSAmJiBlbCA9PT0gZXZ0LmZyb20pIHtcbiAgICAgICAgcmV0dXJuXG4gICAgICB9XG5cbiAgICAgIGxldCBtYXN0ZXJFbCA9IGVsLmNsb3Nlc3QoJ1t3aXJlXFxcXDpzb3J0YWJsZS1ncm91cF0nKVxuXG4gICAgICBsZXQgZ3JvdXBzID0gQXJyYXkuZnJvbShtYXN0ZXJFbC5xdWVyeVNlbGVjdG9yQWxsKCdbd2lyZVxcXFw6c29ydGFibGUtZ3JvdXBcXFxcLml0ZW0tZ3JvdXBdJykpLm1hcCgoZWwsIGluZGV4KSA9PiB7XG4gICAgICAgIG1vdmVFbmRNb3JwaE1hcmtlcihlbClcblxuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgIG9yZGVyOiBpbmRleCArIDEsXG4gICAgICAgICAgdmFsdWU6IGVsLmdldEF0dHJpYnV0ZSgnd2lyZTpzb3J0YWJsZS1ncm91cC5pdGVtLWdyb3VwJyksXG4gICAgICAgICAgaXRlbXM6ICBlbC5saXZld2lyZV9zb3J0YWJsZS50b0FycmF5KCkubWFwKCh2YWx1ZSwgaW5kZXgpID0+IHtcbiAgICAgICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICAgIG9yZGVyOiBpbmRleCArIDEsXG4gICAgICAgICAgICAgIHZhbHVlOiB2YWx1ZVxuICAgICAgICAgICAgfVxuICAgICAgICAgIH0pLFxuICAgICAgICB9XG4gICAgICB9KVxuXG4gICAgICBtYXN0ZXJFbC5jbG9zZXN0KCdbd2lyZVxcXFw6aWRdJykuX19saXZld2lyZS4kd2lyZS5jYWxsKG1hc3RlckVsLmdldEF0dHJpYnV0ZSgnd2lyZTpzb3J0YWJsZS1ncm91cCcpLCBncm91cHMpXG4gICAgfSxcbiAgfSlcbn0pXG4iLCAiaW1wb3J0IFNsaWRlT3ZlclBhbmVsIGZyb20gJy4vY29tcG9uZW50cy9wYW5lbCdcbmltcG9ydCBTZWxlY3RUcmVlIGZyb20gJy4vY29tcG9uZW50cy9zZWxlY3QtdHJlZSdcbmltcG9ydCAnLi9jb21wb25lbnRzL3NvcnRhYmxlJ1xuXG53aW5kb3cuU2xpZGVPdmVyUGFuZWwgPSBTbGlkZU92ZXJQYW5lbFxud2luZG93LnNlbGVjdFRyZWUgPSBTZWxlY3RUcmVlXG5cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2FscGluZTppbml0JywgKCkgPT4ge1xuICBjb25zdCB0aGVtZSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCd0aGVtZScpID8/ICdzeXN0ZW0nXG5cbiAgd2luZG93LkFscGluZS5zdG9yZShcbiAgICAndGhlbWUnLFxuICAgIHRoZW1lID09PSAnZGFyaycgfHxcbiAgICAodGhlbWUgPT09ICdzeXN0ZW0nICYmXG4gICAgICB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLm1hdGNoZXMpXG4gICAgICA/ICdkYXJrJ1xuICAgICAgOiAnbGlnaHQnLFxuICApXG5cbiAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3RoZW1lLWNoYW5nZWQnLCAoZXZlbnQpID0+IHtcbiAgICBsZXQgdGhlbWUgPSBldmVudC5kZXRhaWxcblxuICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKCd0aGVtZScsIHRoZW1lKVxuXG4gICAgaWYgKHRoZW1lID09PSAnc3lzdGVtJykge1xuICAgICAgdGhlbWUgPSB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLm1hdGNoZXNcbiAgICAgICAgPyAnZGFyaydcbiAgICAgICAgOiAnbGlnaHQnXG4gICAgfVxuXG4gICAgd2luZG93LkFscGluZS5zdG9yZSgndGhlbWUnLCB0aGVtZSlcbiAgfSlcblxuICB3aW5kb3dcbiAgICAubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpXG4gICAgLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIChldmVudCkgPT4ge1xuICAgICAgaWYgKGxvY2FsU3RvcmFnZS5nZXRJdGVtKCd0aGVtZScpID09PSAnc3lzdGVtJykge1xuICAgICAgICB3aW5kb3cuQWxwaW5lLnN0b3JlKCd0aGVtZScsIGV2ZW50Lm1hdGNoZXMgPyAnZGFyaycgOiAnbGlnaHQnKVxuICAgICAgfVxuICAgIH0pXG5cbiAgd2luZG93LkFscGluZS5lZmZlY3QoKCkgPT4ge1xuICAgIGNvbnN0IHRoZW1lID0gd2luZG93LkFscGluZS5zdG9yZSgndGhlbWUnKVxuXG4gICAgdGhlbWUgPT09ICdkYXJrJ1xuICAgICAgPyBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGFyaycpXG4gICAgICA6IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdkYXJrJylcbiAgfSlcbn0pXG4iXSwKICAibWFwcGluZ3MiOiAiOztBQUFBLE1BQU0saUJBQWlCLE1BQU07QUFDM0IsV0FBTztBQUFBLE1BQ0wsTUFBTTtBQUFBLE1BQ04scUJBQXFCO0FBQUEsTUFDckIsaUJBQWlCO0FBQUEsTUFDakIsa0JBQWtCLENBQUM7QUFBQSxNQUNuQixZQUFZO0FBQUEsTUFDWixXQUFXLENBQUM7QUFBQSxNQUNaLGlDQUFpQyxLQUFLO0FBQ3BDLFlBQUksS0FBSyxNQUFNLElBQUksWUFBWSxFQUFFLEtBQUssZUFBZSxNQUFNLFFBQVc7QUFDcEUsaUJBQU8sS0FBSyxNQUFNLElBQUksWUFBWSxFQUFFLEtBQUssZUFBZSxFQUFFLGlCQUFpQixFQUFFLEdBQUc7QUFBQSxRQUNsRjtBQUFBLE1BQ0Y7QUFBQSxNQUNBLG1CQUFtQixTQUFTO0FBQzFCLFlBQUksS0FBSyxpQ0FBaUMsZUFBZSxNQUFNLE9BQU87QUFDcEU7QUFBQSxRQUNGO0FBRUEsWUFBSSxRQUFRLEtBQUssaUNBQWlDLHlCQUF5QixNQUFNO0FBQ2pGLGFBQUssV0FBVyxLQUFLO0FB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T0FBTyxNQUFNLFNBQVNBLE1BQUs7QUFBQSxJQUNwQyxDQUFDO0FBRUQsV0FDRyxXQUFXLDhCQUE4QixFQUN6QyxpQkFBaUIsVUFBVSxDQUFDLFVBQVU7QUFDckMsVUFBSSxhQUFhLFFBQVEsT0FBTyxNQUFNLFVBQVU7QUFDOUMsZUFBTyxPQUFPLE1BQU0sU0FBUyxNQUFNLFVBQVUsU0FBUyxPQUFPO0FBQUEsTUFDL0Q7QUFBQSxJQUNGLENBQUM7QUFFSCxXQUFPLE9BQU8sT0FBTyxNQUFNO0FBQ3pCLFlBQU1BLFNBQVEsT0FBTyxPQUFPLE1BQU0sT0FBTztBQUV6QyxNQUFBQSxXQUFVLFNBQ04sU0FBUyxnQkFBZ0IsVUFBVSxJQUFJLE1BQU0sSUFDN0MsU0FBUyxnQkFBZ0IsVUFBVSxPQUFPLE1BQU07QUFBQSxJQUN0RCxDQUFDO0FBQUEsRUFDSCxDQUFDOyIsCiAgIm5hbWVzIjogWyJpZCIsICJvYmoiLCAiXyIsICJuIiwgImluZGV4IiwgIm1zIiwgImdob3N0RWwiLCAib3B0aW9uIiwgInAiLCAiZGVmYXVsdHMiLCAicm9vdEVsIiwgImNsb25lRWwiLCAib2xkSW5kZXgiLCAibmV3SW5kZXgiLCAib2xkRHJhZ2dhYmxlSW5kZXgiLCAibmV3RHJhZ2dhYmxlSW5kZXgiLCAicHV0U29ydGFibGUiLCAicGx1Z2luRXZlbnQiLCAiX2RldGVjdERpcmVjdGlvbiIsICJfZHJhZ0VsSW5Sb3dDb2x1bW4iLCAiX2RldGVjdE5lYXJlc3RFbXB0eVNvcnRhYmxlIiwgIl9wcmVwYXJlR3JvdXAiLCAiZHJhZ0VsIiwgIl9oaWRlR2hvc3RGb3JUYXJnZXQiLCAiX3VuaGlkZUdob3N0Rm9yVGFyZ2V0IiwgIm5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50IiwgIl9jaGVja091dHNpZGVUYXJnZXRFbCIsICJkcmFnU3RhcnRGbiIsICJ0YXJnZXQiLCAiYWZ0ZXIiLCAibiIsICJlbCIsICJpcyIsICJwbHVnaW5zIiwgImRyb3AiLCAiYXV0b1Njcm9sbCIsICJvblNwaWxsIiwgImluZGV4IiwgImNvbXBvbmVudCIsICJlbCIsICJ0aGVtZSJdCn0K
