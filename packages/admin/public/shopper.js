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
          el.livewire_sortable.option(
            "handle",
            el.querySelector("[wire\\:sortable\\.handle]") ? "[wire\\:sortable\\.handle]" : null
          );
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
        let groups = Array.from(masterEl.querySelectorAll("[wire\\:sortable-group\\.item-group]")).map(
          (el2, index2) => {
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
          }
        );
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
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvcGFuZWwuanMiLCAiLi4vbm9kZV9tb2R1bGVzL3RyZWVzZWxlY3Rqcy9kaXN0L3RyZWVzZWxlY3Rqcy5tanMiLCAiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvc2VsZWN0LXRyZWUuanMiLCAiLi4vbm9kZV9tb2R1bGVzL3NvcnRhYmxlanMvbW9kdWxhci9zb3J0YWJsZS5lc20uanMiLCAiLi4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvc29ydGFibGUuanMiLCAiLi4vcmVzb3VyY2VzL2pzL2luZGV4LmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBTbGlkZU92ZXJQYW5lbCA9ICgpID0+IHtcbiAgICByZXR1cm4ge1xuICAgICAgICBvcGVuOiBmYWxzZSxcbiAgICAgICAgc2hvd0FjdGl2ZUNvbXBvbmVudDogdHJ1ZSxcbiAgICAgICAgYWN0aXZlQ29tcG9uZW50OiBmYWxzZSxcbiAgICAgICAgY29tcG9uZW50SGlzdG9yeTogW10sXG4gICAgICAgIHBhbmVsV2lkdGg6IG51bGwsXG4gICAgICAgIGxpc3RlbmVyczogW10sXG4gICAgICAgIGdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKGtleSkge1xuICAgICAgICAgICAgaWYgKHRoaXMuJHdpcmUuZ2V0KCdjb21wb25lbnRzJylbdGhpcy5hY3RpdmVDb21wb25lbnRdICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy4kd2lyZS5nZXQoJ2NvbXBvbmVudHMnKVt0aGlzLmFjdGl2ZUNvbXBvbmVudF1bJ3BhbmVsQXR0cmlidXRlcyddW2tleV1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgY2xvc2VQYW5lbE9uRXNjYXBlKHRyaWdnZXIpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdjbG9zZU9uRXNjYXBlJykgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGxldCBmb3JjZSA9IHRoaXMuZ2V0QWN0aXZlQ29tcG9uZW50UGFuZWxBdHRyaWJ1dGUoJ2Nsb3NlT25Fc2NhcGVJc0ZvcmNlZnVsJykgPT09IHRydWVcbiAgICAgICAgICAgIHRoaXMuY2xvc2VQYW5lbChmb3JjZSlcbiAgICAgICAgfSxcbiAgICAgICAgY2xvc2VQYW5lbE9uQ2xpY2tBd2F5KHRyaWdnZXIpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdjbG9zZU9uQ2xpY2tBd2F5JykgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuY2xvc2VQYW5lbCh0cnVlKVxuICAgICAgICB9LFxuICAgICAgICBjbG9zZVBhbmVsKGZvcmNlID0gZmFsc2UsIHNraXBQcmV2aW91c1BhbmVscyA9IDAsIGRlc3Ryb3lTa2lwcGVkID0gZmFsc2UpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLnNob3cgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdkaXNwYXRjaENsb3NlRXZlbnQnKSA9PT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgIGNvbnN0IGNvbXBvbmVudE5hbWUgPSB0aGlzLiR3aXJlLmdldCgnY29tcG9uZW50cycpW3RoaXMuYWN0aXZlQ29tcG9uZW50XS5uYW1lXG4gICAgICAgICAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ3BhbmVsQ2xvc2VkJywgeyBuYW1lOiBjb21wb25lbnROYW1lIH0pXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdkZXN0cm95T25DbG9zZScpID09PSB0cnVlKSB7XG4gICAgICAgICAgICAgICAgTGl2ZXdpcmUuZGlzcGF0Y2goJ2Rlc3Ryb3lDb21wb25lbnQnLCB7IGlkOiB0aGlzLmFjdGl2ZUNvbXBvbmVudCB9KVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBpZiAoc2tpcFByZXZpb3VzUGFuZWxzID4gMCkge1xuICAgICAgICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgc2tpcFByZXZpb3VzUGFuZWxzOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGRlc3Ryb3lTa2lwcGVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBpZCA9IHRoaXMuY29tcG9uZW50SGlzdG9yeVt0aGlzLmNvbXBvbmVudEhpc3RvcnkubGVuZ3RoIC0gMV1cbiAgICAgICAgICAgICAgICAgICAgICAgIExpdmV3aXJlLmRpc3BhdGNoKCdkZXN0cm95Q29tcG9uZW50JywgeyBpZDogaWQgfSlcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB0aGlzLmNvbXBvbmVudEhpc3RvcnkucG9wKClcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNvbnN0IGlkID0gdGhpcy5jb21wb25lbnRIaXN0b3J5LnBvcCgpXG5cbiAgICAgICAgICAgIGlmIChpZCAmJiAhZm9yY2UpIHtcbiAgICAgICAgICAgICAgICBpZiAoaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zZXRBY3RpdmVQYW5lbENvbXBvbmVudChpZCwgdHJ1ZSlcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNldFNob3dQcm9wZXJ0eVRvKGZhbHNlKVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zZXRTaG93UHJvcGVydHlUbyhmYWxzZSlcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgc2V0QWN0aXZlUGFuZWxDb21wb25lbnQoaWQsIHNraXAgPSBmYWxzZSkge1xuICAgICAgICAgICAgdGhpcy5zZXRTaG93UHJvcGVydHlUbyh0cnVlKVxuXG4gICAgICAgICAgICBpZiAodGhpcy5hY3RpdmVDb21wb25lbnQgPT09IGlkKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICh0aGlzLmFjdGl2ZUNvbXBvbmVudCAhPT0gZmFsc2UgJiYgc2tpcCA9PT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmNvbXBvbmVudEhpc3RvcnkucHVzaCh0aGlzLmFjdGl2ZUNvbXBvbmVudClcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgbGV0IGZvY3VzYWJsZVRpbWVvdXQgPSA1MFxuXG4gICAgICAgICAgICBpZiAodGhpcy5hY3RpdmVDb21wb25lbnQgPT09IGZhbHNlKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hY3RpdmVDb21wb25lbnQgPSBpZFxuICAgICAgICAgICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IHRydWVcbiAgICAgICAgICAgICAgICB0aGlzLnBhbmVsV2lkdGggPSB0aGlzLmdldEFjdGl2ZUNvbXBvbmVudFBhbmVsQXR0cmlidXRlKCdtYXhXaWR0aENsYXNzJylcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy5zaG93QWN0aXZlQ29tcG9uZW50ID0gZmFsc2VcblxuICAgICAgICAgICAgICAgIGZvY3VzYWJsZVRpbWVvdXQgPSA0MDBcblxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFjdGl2ZUNvbXBvbmVudCA9IGlkXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc2hvd0FjdGl2ZUNvbXBvbmVudCA9IHRydWVcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYW5lbFdpZHRoID0gdGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnbWF4V2lkdGhDbGFzcycpXG4gICAgICAgICAgICAgICAgfSwgMzAwKVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLiRuZXh0VGljaygoKSA9PiB7XG4gICAgICAgICAgICAgICAgbGV0IGZvY3VzYWJsZSA9IHRoaXMuJHJlZnNbaWRdPy5xdWVyeVNlbGVjdG9yKCdbYXV0b2ZvY3VzXScpXG4gICAgICAgICAgICAgICAgaWYgKGZvY3VzYWJsZSkge1xuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvY3VzYWJsZS5mb2N1cygpXG4gICAgICAgICAgICAgICAgICAgIH0sIGZvY3VzYWJsZVRpbWVvdXQpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSlcbiAgICAgICAgfSxcbiAgICAgICAgZm9jdXNhYmxlcygpIHtcbiAgICAgICAgICAgIGxldCBzZWxlY3RvciA9XG4gICAgICAgICAgICAgICAgXCJhLCBidXR0b24sIGlucHV0Om5vdChbdHlwZT0naGlkZGVuJ10sIHRleHRhcmVhLCBzZWxlY3QsIGRldGFpbHMsIFt0YWJpbmRleF06bm90KFt0YWJpbmRleD0nLTEnXSlcIlxuXG4gICAgICAgICAgICByZXR1cm4gWy4uLnRoaXMuJGVsLnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpXS5maWx0ZXIoKGVsKSA9PiAhZWwuaGFzQXR0cmlidXRlKCdkaXNhYmxlZCcpKVxuICAgICAgICB9LFxuICAgICAgICBmaXJzdEZvY3VzYWJsZSgpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLmZvY3VzYWJsZXMoKVswXVxuICAgICAgICB9LFxuICAgICAgICBsYXN0Rm9jdXNhYmxlKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpLnNsaWNlKC0xKVswXVxuICAgICAgICB9LFxuICAgICAgICBuZXh0Rm9jdXNhYmxlKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpW3RoaXMubmV4dEZvY3VzYWJsZUluZGV4KCldIHx8IHRoaXMuZmlyc3RGb2N1c2FibGUoKVxuICAgICAgICB9LFxuICAgICAgICBwcmV2Rm9jdXNhYmxlKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZm9jdXNhYmxlcygpW3RoaXMucHJldkZvY3VzYWJsZUluZGV4KCldIHx8IHRoaXMubGFzdEZvY3VzYWJsZSgpXG4gICAgICAgIH0sXG4gICAgICAgIG5leHRGb2N1c2FibGVJbmRleCgpIHtcbiAgICAgICAgICAgIHJldHVybiAodGhpcy5mb2N1c2FibGVzKCkuaW5kZXhPZihkb2N1bWVudC5hY3RpdmVFbGVtZW50KSArIDEpICUgKHRoaXMuZm9jdXNhYmxlcygpLmxlbmd0aCArIDEpXG4gICAgICAgIH0sXG4gICAgICAgIHByZXZGb2N1c2FibGVJbmRleCgpIHtcbiAgICAgICAgICAgIHJldHVybiBNYXRoLm1heCgwLCB0aGlzLmZvY3VzYWJsZXMoKS5pbmRleE9mKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQpKSAtIDFcbiAgICAgICAgfSxcbiAgICAgICAgc2V0U2hvd1Byb3BlcnR5VG8ob3Blbikge1xuICAgICAgICAgICAgdGhpcy5vcGVuID0gb3BlblxuXG4gICAgICAgICAgICBpZiAob3Blbikge1xuICAgICAgICAgICAgICAgIGRvY3VtZW50LmJvZHkuY2xhc3NMaXN0LmFkZCgnb3ZlcmZsb3cteS1oaWRkZW4nKVxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5yZW1vdmUoJ292ZXJmbG93LXktaGlkZGVuJylcblxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFjdGl2ZUNvbXBvbmVudCA9IGZhbHNlXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuJHdpcmUucmVzZXRTdGF0ZSgpXG4gICAgICAgICAgICAgICAgfSwgMzAwKVxuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBpbml0KCkge1xuICAgICAgICAgICAgdGhpcy5wYW5lbFdpZHRoID0gdGhpcy5nZXRBY3RpdmVDb21wb25lbnRQYW5lbEF0dHJpYnV0ZSgnbWF4V2lkdGhDbGFzcycpXG5cbiAgICAgICAgICAgIHRoaXMubGlzdGVuZXJzLnB1c2goXG4gICAgICAgICAgICAgICAgTGl2ZXdpcmUub24oJ2Nsb3NlUGFuZWwnLCAoZGF0YSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3NlUGFuZWwoZGF0YT8uZm9yY2UgPz8gZmFsc2UsIGRhdGE/LnNraXBQcmV2aW91c1BhbmVscyA/PyAwLCBkYXRhPy5kZXN0cm95U2tpcHBlZCA/PyBmYWxzZSlcbiAgICAgICAgICAgICAgICB9KSxcbiAgICAgICAgICAgIClcblxuICAgICAgICAgICAgdGhpcy5saXN0ZW5lcnMucHVzaChcbiAgICAgICAgICAgICAgICBMaXZld2lyZS5vbignYWN0aXZlUGFuZWxDb21wb25lbnRDaGFuZ2VkJywgKHsgaWQgfSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnNldEFjdGl2ZVBhbmVsQ29tcG9uZW50KGlkKVxuICAgICAgICAgICAgICAgIH0pLFxuICAgICAgICAgICAgKVxuICAgICAgICB9LFxuICAgICAgICBkZXN0cm95KCkge1xuICAgICAgICAgICAgdGhpcy5saXN0ZW5lcnMuZm9yRWFjaCgobGlzdGVuZXIpID0+IHtcbiAgICAgICAgICAgICAgICBsaXN0ZW5lcigpXG4gICAgICAgICAgICB9KVxuICAgICAgICB9LFxuICAgIH1cbn1cblxuZXhwb3J0IGRlZmF1bHQgU2xpZGVPdmVyUGFuZWxcbiIsICJ2YXIgcmkgPSBPYmplY3QuZGVmaW5lUHJvcGVydHk7XG52YXIgY2kgPSAobCwgZSwgdCkgPT4gZSBpbiBsID8gcmkobCwgZSwgeyBlbnVtZXJhYmxlOiAhMCwgY29uZmlndXJhYmxlOiAhMCwgd3JpdGFibGU6ICEwLCB2YWx1ZTogdCB9KSA6IGxbZV0gPSB0O1xudmFyIGMgPSAobCwgZSwgdCkgPT4gKGNpKGwsIHR5cGVvZiBlICE9IFwic3ltYm9sXCIgPyBlICsgXCJcIiA6IGUsIHQpLCB0KSwga3QgPSAobCwgZSwgdCkgPT4ge1xuICBpZiAoIWUuaGFzKGwpKVxuICAgIHRocm93IFR5cGVFcnJvcihcIkNhbm5vdCBcIiArIHQpO1xufTtcbnZhciBuID0gKGwsIGUsIHQpID0+IChrdChsLCBlLCBcInJlYWQgZnJvbSBwcml2YXRlIGZpZWxkXCIpLCB0ID8gdC5jYWxsKGwpIDogZS5nZXQobCkpLCByID0gKGwsIGUsIHQpID0+IHtcbiAgaWYgKGUuaGFzKGwpKVxuICAgIHRocm93IFR5cGVFcnJvcihcIkNhbm5vdCBhZGQgdGhlIHNhbWUgcHJpdmF0ZSBtZW1iZXIgbW9yZSB0aGFuIG9uY2VcIik7XG4gIGUgaW5zdGFuY2VvZiBXZWFrU2V0ID8gZS5hZGQobCkgOiBlLnNldChsLCB0KTtcbn0sIG0gPSAobCwgZSwgdCwgcykgPT4gKGt0KGwsIGUsIFwid3JpdGUgdG8gcHJpdmF0ZSBmaWVsZFwiKSwgcyA/IHMuY2FsbChsLCB0KSA6IGUuc2V0KGwsIHQpLCB0KTtcbnZhciBvID0gKGwsIGUsIHQpID0+IChrdChsLCBlLCBcImFjY2VzcyBwcml2YXRlIG1ldGhvZFwiKSwgdCk7XG5jb25zdCBQdCA9IHtcbiAgYXJyb3dVcDogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PHBhdGggZD1cIk0xOCAxNWwtNi02LTYgNlwiLz48L3N2Zz4nLFxuICBhcnJvd0Rvd246ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxwYXRoIGQ9XCJNNiA5bDYgNiA2LTZcIi8+PC9zdmc+JyxcbiAgYXJyb3dSaWdodDogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PHBhdGggZD1cIk05IDE4bDYtNi02LTZcIi8+PC9zdmc+JyxcbiAgYXR0ZW50aW9uOiAnPHN2ZyB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgd2lkdGg9XCIxNVwiIGhlaWdodD1cIjE1XCIgdmlld0JveD1cIjAgMCAyNSAyNVwiIGZpbGw9XCJub25lXCIgc3Ryb2tlPVwiIzAwMDAwMFwiIHN0cm9rZS13aWR0aD1cIjJcIiBzdHJva2UtbGluZWNhcD1cInJvdW5kXCIgc3Ryb2tlLWxpbmVqb2luPVwicm91bmRcIj48cGF0aCBkPVwiTTEwLjI5IDMuODZMMS44MiAxOGEyIDIgMCAwIDAgMS43MSAzaDE2Ljk0YTIgMiAwIDAgMCAxLjcxLTNMMTMuNzEgMy44NmEyIDIgMCAwIDAtMy40MiAwelwiPjwvcGF0aD48bGluZSB4MT1cIjEyXCIgeTE9XCI5XCIgeDI9XCIxMlwiIHkyPVwiMTNcIj48L2xpbmU+PGxpbmUgeDE9XCIxMlwiIHkxPVwiMTdcIiB4Mj1cIjEyLjAxXCIgeTI9XCIxN1wiPjwvbGluZT48L3N2Zz4nLFxuICBjbGVhcjogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PGNpcmNsZSBjeD1cIjEyXCIgY3k9XCIxMlwiIHI9XCIxMFwiPjwvY2lyY2xlPjxsaW5lIHgxPVwiMTVcIiB5MT1cIjlcIiB4Mj1cIjlcIiB5Mj1cIjE1XCI+PC9saW5lPjxsaW5lIHgxPVwiOVwiIHkxPVwiOVwiIHgyPVwiMTVcIiB5Mj1cIjE1XCI+PC9saW5lPjwvc3ZnPicsXG4gIGNyb3NzOiAnPHN2ZyB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgd2lkdGg9XCIxNVwiIGhlaWdodD1cIjE1XCIgdmlld0JveD1cIjAgMCAyNSAyNVwiIGZpbGw9XCJub25lXCIgc3Ryb2tlPVwiIzAwMDAwMFwiIHN0cm9rZS13aWR0aD1cIjJcIiBzdHJva2UtbGluZWNhcD1cInJvdW5kXCIgc3Ryb2tlLWxpbmVqb2luPVwicm91bmRcIj48bGluZSB4MT1cIjE4XCIgeTE9XCI2XCIgeDI9XCI2XCIgeTI9XCIxOFwiPjwvbGluZT48bGluZSB4MT1cIjZcIiB5MT1cIjZcIiB4Mj1cIjE4XCIgeTI9XCIxOFwiPjwvbGluZT48L3N2Zz4nLFxuICBjaGVjazogJzxzdmcgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHdpZHRoPVwiMTVcIiBoZWlnaHQ9XCIxNVwiIHZpZXdCb3g9XCIwIDAgMjUgMjVcIiBmaWxsPVwibm9uZVwiIHN0cm9rZT1cIiMwMDAwMDBcIiBzdHJva2Utd2lkdGg9XCIyXCIgc3Ryb2tlLWxpbmVjYXA9XCJyb3VuZFwiIHN0cm9rZS1saW5lam9pbj1cInJvdW5kXCI+PHBvbHlsaW5lIHBvaW50cz1cIjIwIDYgOSAxNyA0IDEyXCI+PC9wb2x5bGluZT48L3N2Zz4nLFxuICBwYXJ0aWFsQ2hlY2s6ICc8c3ZnIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB3aWR0aD1cIjE1XCIgaGVpZ2h0PVwiMTVcIiB2aWV3Qm94PVwiMCAwIDI1IDI1XCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIjMDAwMDAwXCIgc3Ryb2tlLXdpZHRoPVwiMlwiIHN0cm9rZS1saW5lY2FwPVwicm91bmRcIiBzdHJva2UtbGluZWpvaW49XCJyb3VuZFwiPjxsaW5lIHgxPVwiNVwiIHkxPVwiMTJcIiB4Mj1cIjE5XCIgeTI9XCIxMlwiPjwvbGluZT48L3N2Zz4nXG59LCBJID0gKGwsIGUpID0+IHtcbiAgaWYgKGUuaW5uZXJIVE1MID0gXCJcIiwgdHlwZW9mIGwgPT0gXCJzdHJpbmdcIilcbiAgICBlLmlubmVySFRNTCA9IGw7XG4gIGVsc2Uge1xuICAgIGNvbnN0IHQgPSBsLmNsb25lTm9kZSghMCk7XG4gICAgZS5hcHBlbmRDaGlsZCh0KTtcbiAgfVxufSwgQnQgPSAobCkgPT4ge1xuICBjb25zdCBlID0gbCA/IHsgLi4ubCB9IDoge307XG4gIHJldHVybiBPYmplY3Qua2V5cyhQdCkuZm9yRWFjaCgodCkgPT4ge1xuICAgIGVbdF0gfHwgKGVbdF0gPSBQdFt0XSk7XG4gIH0pLCBlO1xufSwgaGkgPSAobCkgPT4gbC5yZWR1Y2UoKGUsIHsgbmFtZTogdCB9LCBzKSA9PiAoZSArPSB0LCBzIDwgbC5sZW5ndGggLSAxICYmIChlICs9IFwiLCBcIiksIGUpLCBcIlwiKTtcbnZhciBOLCBFLCBELCB2LCB1ZSwgSHQsIEgsIFcsIHBlLCBHdCwgbWUsIE10LCBHLCBVLCBPLCBWLCBmZSwgRnQsIGJlLCBxdCwgQ2UsIGp0LCBnZSwgUnQsIGtlLCAkdCwgd2UsIFd0LCBFZSwgVXQsIHZlLCB6dCwgTGUsIFl0LCB5ZSwgS3QsIHhlLCBYdCwgU2UsIEp0LCBfZSwgWnQsIEFlLCBRdCwgVGUsIGVzLCBOZSwgdHMsIHosIHd0O1xuY2xhc3MgZGkge1xuICBjb25zdHJ1Y3Rvcih7XG4gICAgdmFsdWU6IGUsXG4gICAgc2hvd1RhZ3M6IHQsXG4gICAgdGFnc0NvdW50VGV4dDogcyxcbiAgICBjbGVhcmFibGU6IGksXG4gICAgaXNBbHdheXNPcGVuZWQ6IGEsXG4gICAgc2VhcmNoYWJsZTogaCxcbiAgICBwbGFjZWhvbGRlcjogZCxcbiAgICBkaXNhYmxlZDogQyxcbiAgICBpc1NpbmdsZVNlbGVjdDogZixcbiAgICBpZDogYixcbiAgICBhcmlhTGFiZWw6IGcsXG4gICAgaWNvbkVsZW1lbnRzOiBrLFxuICAgIGlucHV0Q2FsbGJhY2s6IHcsXG4gICAgc2VhcmNoQ2FsbGJhY2s6IHksXG4gICAgb3BlbkNhbGxiYWNrOiB4LFxuICAgIGNsb3NlQ2FsbGJhY2s6ICQsXG4gICAga2V5ZG93bkNhbGxiYWNrOiBhZSxcbiAgICBmb2N1c0NhbGxiYWNrOiBDdCxcbiAgICBibHVyQ2FsbGJhY2s6IGd0LFxuICAgIG5hbWVDaGFuZ2VDYWxsYmFjazogb2VcbiAgfSkge1xuICAgIC8vIFByaXZhdGUgbWV0aG9kc1xuICAgIHIodGhpcywgdWUpO1xuICAgIHIodGhpcywgSCk7XG4gICAgcih0aGlzLCBwZSk7XG4gICAgcih0aGlzLCBtZSk7XG4gICAgcih0aGlzLCBHKTtcbiAgICByKHRoaXMsIE8pO1xuICAgIHIodGhpcywgZmUpO1xuICAgIHIodGhpcywgYmUpO1xuICAgIHIodGhpcywgQ2UpO1xuICAgIHIodGhpcywgZ2UpO1xuICAgIHIodGhpcywga2UpO1xuICAgIHIodGhpcywgd2UpO1xuICAgIHIodGhpcywgRWUpO1xuICAgIHIodGhpcywgdmUpO1xuICAgIHIodGhpcywgTGUpO1xuICAgIHIodGhpcywgeWUpO1xuICAgIHIodGhpcywgeGUpO1xuICAgIHIodGhpcywgU2UpO1xuICAgIHIodGhpcywgX2UpO1xuICAgIHIodGhpcywgQWUpO1xuICAgIHIodGhpcywgVGUpO1xuICAgIHIodGhpcywgTmUpO1xuICAgIC8vIEVtaXRzXG4gICAgcih0aGlzLCB6KTtcbiAgICAvLyBQcm9wc1xuICAgIGModGhpcywgXCJ2YWx1ZVwiKTtcbiAgICBjKHRoaXMsIFwic2hvd1RhZ3NcIik7XG4gICAgYyh0aGlzLCBcInRhZ3NDb3VudFRleHRcIik7XG4gICAgYyh0aGlzLCBcImNsZWFyYWJsZVwiKTtcbiAgICBjKHRoaXMsIFwiaXNBbHdheXNPcGVuZWRcIik7XG4gICAgYyh0aGlzLCBcInNlYXJjaGFibGVcIik7XG4gICAgYyh0aGlzLCBcInBsYWNlaG9sZGVyXCIpO1xuICAgIGModGhpcywgXCJkaXNhYmxlZFwiKTtcbiAgICBjKHRoaXMsIFwiaXNTaW5nbGVTZWxlY3RcIik7XG4gICAgYyh0aGlzLCBcImlkXCIpO1xuICAgIGModGhpcywgXCJhcmlhTGFiZWxcIik7XG4gICAgYyh0aGlzLCBcImljb25FbGVtZW50c1wiKTtcbiAgICAvLyBJbm5lclN0YXRlXG4gICAgYyh0aGlzLCBcImlzT3BlbmVkXCIpO1xuICAgIGModGhpcywgXCJzZWFyY2hUZXh0XCIpO1xuICAgIGModGhpcywgXCJzcmNFbGVtZW50XCIpO1xuICAgIC8vIFByaXZhdGVJbm5lclN0YXRlXG4gICAgcih0aGlzLCBOLCB2b2lkIDApO1xuICAgIHIodGhpcywgRSwgdm9pZCAwKTtcbiAgICByKHRoaXMsIEQsIHZvaWQgMCk7XG4gICAgcih0aGlzLCB2LCB2b2lkIDApO1xuICAgIC8vIENhbGxiYWNrc1xuICAgIGModGhpcywgXCJpbnB1dENhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJzZWFyY2hDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwib3BlbkNhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJjbG9zZUNhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJrZXlkb3duQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcImZvY3VzQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcImJsdXJDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwibmFtZUNoYW5nZUNhbGxiYWNrXCIpO1xuICAgIHRoaXMudmFsdWUgPSBlLCB0aGlzLnNob3dUYWdzID0gdCwgdGhpcy50YWdzQ291bnRUZXh0ID0gcywgdGhpcy5zZWFyY2hhYmxlID0gaCwgdGhpcy5wbGFjZWhvbGRlciA9IGQsIHRoaXMuY2xlYXJhYmxlID0gaSwgdGhpcy5pc0Fsd2F5c09wZW5lZCA9IGEsIHRoaXMuZGlzYWJsZWQgPSBDLCB0aGlzLmlzU2luZ2xlU2VsZWN0ID0gZiwgdGhpcy5pZCA9IGIsIHRoaXMuYXJpYUxhYmVsID0gZywgdGhpcy5pY29uRWxlbWVudHMgPSBrLCB0aGlzLmlzT3BlbmVkID0gITEsIHRoaXMuc2VhcmNoVGV4dCA9IFwiXCIsIG0odGhpcywgTiwgbyh0aGlzLCBDZSwganQpLmNhbGwodGhpcykpLCBtKHRoaXMsIEUsIG8odGhpcywgTGUsIFl0KS5jYWxsKHRoaXMpKSwgbSh0aGlzLCBELCBvKHRoaXMsIFNlLCBKdCkuY2FsbCh0aGlzKSksIG0odGhpcywgdiwgbnVsbCksIHRoaXMuaW5wdXRDYWxsYmFjayA9IHcsIHRoaXMuc2VhcmNoQ2FsbGJhY2sgPSB5LCB0aGlzLm9wZW5DYWxsYmFjayA9IHgsIHRoaXMuY2xvc2VDYWxsYmFjayA9ICQsIHRoaXMua2V5ZG93bkNhbGxiYWNrID0gYWUsIHRoaXMuZm9jdXNDYWxsYmFjayA9IEN0LCB0aGlzLmJsdXJDYWxsYmFjayA9IGd0LCB0aGlzLm5hbWVDaGFuZ2VDYWxsYmFjayA9IG9lLCB0aGlzLnNyY0VsZW1lbnQgPSBvKHRoaXMsIGZlLCBGdCkuY2FsbCh0aGlzLCBuKHRoaXMsIE4pLCBuKHRoaXMsIEUpLCBuKHRoaXMsIEQpKSwgbyh0aGlzLCB1ZSwgSHQpLmNhbGwodGhpcyk7XG4gIH1cbiAgLy8gUHVibGljIG1ldGhvZHNcbiAgZm9jdXMoKSB7XG4gICAgc2V0VGltZW91dCgoKSA9PiBuKHRoaXMsIEUpLmZvY3VzKCksIDApO1xuICB9XG4gIGJsdXIoKSB7XG4gICAgdGhpcy5pc09wZW5lZCAmJiBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyksIHRoaXMuY2xlYXJTZWFyY2goKSwgbih0aGlzLCBFKS5ibHVyKCk7XG4gIH1cbiAgdXBkYXRlVmFsdWUoZSkge1xuICAgIHRoaXMudmFsdWUgPSBlLCBvKHRoaXMsIEgsIFcpLmNhbGwodGhpcyksIG8odGhpcywgRywgVSkuY2FsbCh0aGlzKTtcbiAgfVxuICByZW1vdmVJdGVtKGUpIHtcbiAgICB0aGlzLnZhbHVlID0gdGhpcy52YWx1ZS5maWx0ZXIoKHQpID0+IHQuaWQgIT09IGUpLCBvKHRoaXMsIHosIHd0KS5jYWxsKHRoaXMpLCBvKHRoaXMsIEgsIFcpLmNhbGwodGhpcyksIG8odGhpcywgRywgVSkuY2FsbCh0aGlzKTtcbiAgfVxuICBjbGVhcigpIHtcbiAgICB0aGlzLnZhbHVlID0gW10sIG8odGhpcywgeiwgd3QpLmNhbGwodGhpcyksIG8odGhpcywgSCwgVykuY2FsbCh0aGlzKSwgdGhpcy5jbGVhclNlYXJjaCgpO1xuICB9XG4gIG9wZW5DbG9zZSgpIHtcbiAgICBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyk7XG4gIH1cbiAgY2xlYXJTZWFyY2goKSB7XG4gICAgdGhpcy5zZWFyY2hUZXh0ID0gXCJcIiwgdGhpcy5zZWFyY2hDYWxsYmFjayhcIlwiKSwgbyh0aGlzLCBHLCBVKS5jYWxsKHRoaXMpO1xuICB9XG59XG5OID0gbmV3IFdlYWtNYXAoKSwgRSA9IG5ldyBXZWFrTWFwKCksIEQgPSBuZXcgV2Vha01hcCgpLCB2ID0gbmV3IFdlYWtNYXAoKSwgdWUgPSBuZXcgV2Vha1NldCgpLCBIdCA9IGZ1bmN0aW9uKCkge1xuICBvKHRoaXMsIEgsIFcpLmNhbGwodGhpcyksIG8odGhpcywgRywgVSkuY2FsbCh0aGlzKSwgbyh0aGlzLCBwZSwgR3QpLmNhbGwodGhpcyk7XG59LCBIID0gbmV3IFdlYWtTZXQoKSwgVyA9IGZ1bmN0aW9uKCkge1xuICBpZiAobih0aGlzLCBOKS5pbm5lckhUTUwgPSBcIlwiLCB0aGlzLnNob3dUYWdzKSB7XG4gICAgbih0aGlzLCBOKS5hcHBlbmQoLi4ubyh0aGlzLCBnZSwgUnQpLmNhbGwodGhpcykpO1xuICAgIGNvbnN0IGUgPSBoaSh0aGlzLnZhbHVlKTtcbiAgICB0aGlzLm5hbWVDaGFuZ2VDYWxsYmFjayhlKTtcbiAgfSBlbHNlIHtcbiAgICBjb25zdCBlID0gbyh0aGlzLCB2ZSwgenQpLmNhbGwodGhpcyk7XG4gICAgbih0aGlzLCBOKS5hcHBlbmRDaGlsZChlKSwgdGhpcy5uYW1lQ2hhbmdlQ2FsbGJhY2soZS5pbm5lclRleHQpO1xuICB9XG4gIG4odGhpcywgTikuYXBwZW5kQ2hpbGQobih0aGlzLCBFKSk7XG59LCBwZSA9IG5ldyBXZWFrU2V0KCksIEd0ID0gZnVuY3Rpb24oKSB7XG4gIGNvbnN0IGUgPSBbXTtcbiAgbih0aGlzLCBEKS5pbm5lckhUTUwgPSBcIlwiLCB0aGlzLmNsZWFyYWJsZSAmJiBlLnB1c2gobyh0aGlzLCBfZSwgWnQpLmNhbGwodGhpcykpLCB0aGlzLmlzQWx3YXlzT3BlbmVkIHx8IGUucHVzaChvKHRoaXMsIFRlLCBlcykuY2FsbCh0aGlzLCB0aGlzLmlzT3BlbmVkKSksIGUubGVuZ3RoICYmIG4odGhpcywgRCkuYXBwZW5kKC4uLmUpO1xufSwgbWUgPSBuZXcgV2Vha1NldCgpLCBNdCA9IGZ1bmN0aW9uKCkge1xuICBpZiAoIXRoaXMuaXNBbHdheXNPcGVuZWQgJiYgbih0aGlzLCB2KSkge1xuICAgIGNvbnN0IGUgPSB0aGlzLmlzT3BlbmVkID8gdGhpcy5pY29uRWxlbWVudHMuYXJyb3dVcCA6IHRoaXMuaWNvbkVsZW1lbnRzLmFycm93RG93bjtcbiAgICBJKGUsIG4odGhpcywgdikpO1xuICB9XG59LCBHID0gbmV3IFdlYWtTZXQoKSwgVSA9IGZ1bmN0aW9uKCkge1xuICB2YXIgZTtcbiAgKGUgPSB0aGlzLnZhbHVlKSAhPSBudWxsICYmIGUubGVuZ3RoID8gKG4odGhpcywgRSkucmVtb3ZlQXR0cmlidXRlKFwicGxhY2Vob2xkZXJcIiksIHRoaXMuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1pbnB1dC0tdmFsdWUtbm90LXNlbGVjdGVkXCIpKSA6IChuKHRoaXMsIEUpLnNldEF0dHJpYnV0ZShcInBsYWNlaG9sZGVyXCIsIHRoaXMucGxhY2Vob2xkZXIpLCB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXQtLXZhbHVlLW5vdC1zZWxlY3RlZFwiKSksIHRoaXMuc2VhcmNoYWJsZSA/IHRoaXMuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1pbnB1dC0tdW5zZWFyY2hhYmxlXCIpIDogdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS11bnNlYXJjaGFibGVcIiksIHRoaXMuaXNTaW5nbGVTZWxlY3QgPyB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXQtLWlzLXNpbmdsZS1zZWxlY3RcIikgOiB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLWlzLXNpbmdsZS1zZWxlY3RcIiksIG4odGhpcywgRSkudmFsdWUgPSB0aGlzLnNlYXJjaFRleHQ7XG59LCBPID0gbmV3IFdlYWtTZXQoKSwgViA9IGZ1bmN0aW9uKCkge1xuICB0aGlzLmlzT3BlbmVkID0gIXRoaXMuaXNPcGVuZWQsIG8odGhpcywgbWUsIE10KS5jYWxsKHRoaXMpLCB0aGlzLmlzT3BlbmVkID8gdGhpcy5vcGVuQ2FsbGJhY2soKSA6IHRoaXMuY2xvc2VDYWxsYmFjaygpO1xufSwgZmUgPSBuZXcgV2Vha1NldCgpLCBGdCA9IGZ1bmN0aW9uKGUsIHQsIHMpIHtcbiAgY29uc3QgaSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHJldHVybiBpLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0XCIpLCBpLnNldEF0dHJpYnV0ZShcInRhYmluZGV4XCIsIFwiLTFcIiksIGkuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCAoYSkgPT4gbyh0aGlzLCBiZSwgcXQpLmNhbGwodGhpcywgYSkpLCBpLmFkZEV2ZW50TGlzdGVuZXIoXCJmb2N1c1wiLCAoKSA9PiB0aGlzLmZvY3VzQ2FsbGJhY2soKSwgITApLCBpLmFkZEV2ZW50TGlzdGVuZXIoXCJibHVyXCIsICgpID0+IHRoaXMuYmx1ckNhbGxiYWNrKCksICEwKSwgZS5hcHBlbmRDaGlsZCh0KSwgaS5hcHBlbmQoZSwgcyksIGk7XG59LCBiZSA9IG5ldyBXZWFrU2V0KCksIHF0ID0gZnVuY3Rpb24oZSkge1xuICBlLnN0b3BQcm9wYWdhdGlvbigpLCB0aGlzLmlzT3BlbmVkIHx8IG8odGhpcywgTywgVikuY2FsbCh0aGlzKSwgdGhpcy5mb2N1cygpO1xufSwgQ2UgPSBuZXcgV2Vha1NldCgpLCBqdCA9IGZ1bmN0aW9uKCkge1xuICBjb25zdCBlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgcmV0dXJuIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX3RhZ3NcIiksIGU7XG59LCBnZSA9IG5ldyBXZWFrU2V0KCksIFJ0ID0gZnVuY3Rpb24oKSB7XG4gIHJldHVybiB0aGlzLnZhbHVlLm1hcCgoZSkgPT4ge1xuICAgIGNvbnN0IHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICAgIHQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX3RhZ3MtZWxlbWVudFwiKSwgdC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpLCB0LnNldEF0dHJpYnV0ZShcInRhZy1pZFwiLCBlLmlkLnRvU3RyaW5nKCkpLCB0LnNldEF0dHJpYnV0ZShcInRpdGxlXCIsIGUubmFtZSk7XG4gICAgY29uc3QgcyA9IG8odGhpcywgd2UsIFd0KS5jYWxsKHRoaXMsIGUubmFtZSksIGkgPSBvKHRoaXMsIEVlLCBVdCkuY2FsbCh0aGlzKTtcbiAgICByZXR1cm4gdC5hZGRFdmVudExpc3RlbmVyKFwibW91c2Vkb3duXCIsIChhKSA9PiBvKHRoaXMsIGtlLCAkdCkuY2FsbCh0aGlzLCBhLCBlLmlkKSksIHQuYXBwZW5kKHMsIGkpLCB0O1xuICB9KTtcbn0sIGtlID0gbmV3IFdlYWtTZXQoKSwgJHQgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGUucHJldmVudERlZmF1bHQoKSwgZS5zdG9wUHJvcGFnYXRpb24oKSwgdGhpcy5yZW1vdmVJdGVtKHQpLCB0aGlzLmZvY3VzKCk7XG59LCB3ZSA9IG5ldyBXZWFrU2V0KCksIFd0ID0gZnVuY3Rpb24oZSkge1xuICBjb25zdCB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gIHJldHVybiB0LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X190YWdzLW5hbWVcIiksIHQudGV4dENvbnRlbnQgPSBlLCB0O1xufSwgRWUgPSBuZXcgV2Vha1NldCgpLCBVdCA9IGZ1bmN0aW9uKCkge1xuICBjb25zdCBlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gIHJldHVybiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0X190YWdzLWNyb3NzXCIpLCBJKHRoaXMuaWNvbkVsZW1lbnRzLmNyb3NzLCBlKSwgZTtcbn0sIHZlID0gbmV3IFdlYWtTZXQoKSwgenQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICBpZiAoZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dF9fdGFncy1jb3VudFwiKSwgIXRoaXMudmFsdWUubGVuZ3RoKVxuICAgIHJldHVybiBlLnRleHRDb250ZW50ID0gXCJcIiwgZS5zZXRBdHRyaWJ1dGUoXCJ0aXRsZVwiLCBcIlwiKSwgZTtcbiAgY29uc3QgdCA9IHRoaXMudmFsdWUubGVuZ3RoID09PSAxID8gdGhpcy52YWx1ZVswXS5uYW1lIDogYCR7dGhpcy52YWx1ZS5sZW5ndGh9ICR7dGhpcy50YWdzQ291bnRUZXh0fWA7XG4gIHJldHVybiBlLnRleHRDb250ZW50ID0gdCwgZS5zZXRBdHRyaWJ1dGUoXCJ0aXRsZVwiLCB0KSwgZTtcbn0sIExlID0gbmV3IFdlYWtTZXQoKSwgWXQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJpbnB1dFwiKTtcbiAgcmV0dXJuIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX2VkaXRcIiksIHRoaXMuaWQgJiYgZS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLmlkKSwgKCF0aGlzLnNlYXJjaGFibGUgfHwgdGhpcy5kaXNhYmxlZCkgJiYgZS5zZXRBdHRyaWJ1dGUoXCJyZWFkb25seVwiLCBcInJlYWRvbmx5XCIpLCB0aGlzLmRpc2FibGVkICYmIGUuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgdGhpcy5hcmlhTGFiZWwubGVuZ3RoICYmIGUuc2V0QXR0cmlidXRlKFwiYXJpYS1sYWJlbFwiLCB0aGlzLmFyaWFMYWJlbCksIGUuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKHQpID0+IG8odGhpcywgeWUsIEt0KS5jYWxsKHRoaXMsIHQpKSwgZS5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKHQpID0+IG8odGhpcywgeGUsIFh0KS5jYWxsKHRoaXMsIHQsIGUpKSwgZTtcbn0sIHllID0gbmV3IFdlYWtTZXQoKSwgS3QgPSBmdW5jdGlvbihlKSB7XG4gIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gIGNvbnN0IHQgPSBlLmtleTtcbiAgdCA9PT0gXCJCYWNrc3BhY2VcIiAmJiAhdGhpcy5zZWFyY2hUZXh0Lmxlbmd0aCAmJiB0aGlzLnZhbHVlLmxlbmd0aCAmJiAhdGhpcy5zaG93VGFncyAmJiB0aGlzLmNsZWFyKCksIHQgPT09IFwiQmFja3NwYWNlXCIgJiYgIXRoaXMuc2VhcmNoVGV4dC5sZW5ndGggJiYgdGhpcy52YWx1ZS5sZW5ndGggJiYgdGhpcy5yZW1vdmVJdGVtKHRoaXMudmFsdWVbdGhpcy52YWx1ZS5sZW5ndGggLSAxXS5pZCksIGUuY29kZSA9PT0gXCJTcGFjZVwiICYmICghdGhpcy5zZWFyY2hUZXh0IHx8ICF0aGlzLnNlYXJjaGFibGUpICYmIG8odGhpcywgTywgVikuY2FsbCh0aGlzKSwgKHQgPT09IFwiRW50ZXJcIiB8fCB0ID09PSBcIkFycm93RG93blwiIHx8IHQgPT09IFwiQXJyb3dVcFwiKSAmJiBlLnByZXZlbnREZWZhdWx0KCksIHRoaXMua2V5ZG93bkNhbGxiYWNrKGUpLCB0ICE9PSBcIlRhYlwiICYmIHRoaXMuZm9jdXMoKTtcbn0sIHhlID0gbmV3IFdlYWtTZXQoKSwgWHQgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gIGNvbnN0IHMgPSB0aGlzLnNlYXJjaFRleHQsIGkgPSB0LnZhbHVlLnRyaW0oKTtcbiAgaWYgKHMubGVuZ3RoID09PSAwICYmIGkubGVuZ3RoID09PSAwKSB7XG4gICAgdC52YWx1ZSA9IFwiXCI7XG4gICAgcmV0dXJuO1xuICB9XG4gIGlmICh0aGlzLnNlYXJjaGFibGUpIHtcbiAgICBjb25zdCBhID0gZS50YXJnZXQudmFsdWU7XG4gICAgdGhpcy5zZWFyY2hDYWxsYmFjayhhKSwgdGhpcy5pc09wZW5lZCB8fCBvKHRoaXMsIE8sIFYpLmNhbGwodGhpcyk7XG4gIH0gZWxzZVxuICAgIHQudmFsdWUgPSBcIlwiO1xuICB0aGlzLnNlYXJjaFRleHQgPSB0LnZhbHVlO1xufSwgU2UgPSBuZXcgV2Vha1NldCgpLCBKdCA9IGZ1bmN0aW9uKCkge1xuICBjb25zdCBlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgcmV0dXJuIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXRfX29wZXJhdG9yc1wiKSwgZTtcbn0sIF9lID0gbmV3IFdlYWtTZXQoKSwgWnQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICByZXR1cm4gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dF9fY2xlYXJcIiksIGUuc2V0QXR0cmlidXRlKFwidGFiaW5kZXhcIiwgXCItMVwiKSwgSSh0aGlzLmljb25FbGVtZW50cy5jbGVhciwgZSksIGUuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCAodCkgPT4gbyh0aGlzLCBBZSwgUXQpLmNhbGwodGhpcywgdCkpLCBlO1xufSwgQWUgPSBuZXcgV2Vha1NldCgpLCBRdCA9IGZ1bmN0aW9uKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpLCBlLnN0b3BQcm9wYWdhdGlvbigpLCAodGhpcy5zZWFyY2hUZXh0Lmxlbmd0aCB8fCB0aGlzLnZhbHVlLmxlbmd0aCkgJiYgdGhpcy5jbGVhcigpLCB0aGlzLmZvY3VzKCk7XG59LCBUZSA9IG5ldyBXZWFrU2V0KCksIGVzID0gZnVuY3Rpb24oZSkge1xuICBtKHRoaXMsIHYsIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpKSwgbih0aGlzLCB2KS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dF9fYXJyb3dcIik7XG4gIGNvbnN0IHQgPSBlID8gdGhpcy5pY29uRWxlbWVudHMuYXJyb3dVcCA6IHRoaXMuaWNvbkVsZW1lbnRzLmFycm93RG93bjtcbiAgcmV0dXJuIEkodCwgbih0aGlzLCB2KSksIG4odGhpcywgdikuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCAocykgPT4gbyh0aGlzLCBOZSwgdHMpLmNhbGwodGhpcywgcykpLCBuKHRoaXMsIHYpO1xufSwgTmUgPSBuZXcgV2Vha1NldCgpLCB0cyA9IGZ1bmN0aW9uKGUpIHtcbiAgZS5zdG9wUHJvcGFnYXRpb24oKSwgZS5wcmV2ZW50RGVmYXVsdCgpLCB0aGlzLmZvY3VzKCksIG8odGhpcywgTywgVikuY2FsbCh0aGlzKTtcbn0sIHogPSBuZXcgV2Vha1NldCgpLCB3dCA9IGZ1bmN0aW9uKCkge1xuICB0aGlzLmlucHV0Q2FsbGJhY2sodGhpcy52YWx1ZSk7XG59O1xuY29uc3Qgc3MgPSAobCwgZSwgdCwgcykgPT4ge1xuICBmaShlKTtcbiAgY29uc3QgaSA9IGUuZmlsdGVyKChhKSA9PiAhYS5kaXNhYmxlZCAmJiBsLnNvbWUoKGgpID0+IGggPT09IGEuaWQpKTtcbiAgaWYgKHQgJiYgaS5sZW5ndGgpIHtcbiAgICBpWzBdLmNoZWNrZWQgPSAhMDtcbiAgICByZXR1cm47XG4gIH1cbiAgaS5mb3JFYWNoKChhKSA9PiB7XG4gICAgYS5jaGVja2VkID0gITA7XG4gICAgY29uc3QgaCA9IEl0KGEsIGUsIHMpO1xuICAgIGEuY2hlY2tlZCA9IGg7XG4gIH0pO1xufSwgSXQgPSAoeyBpZDogbCwgY2hlY2tlZDogZSB9LCB0LCBzKSA9PiB7XG4gIGNvbnN0IGkgPSB0LmZpbmQoKGgpID0+IGguaWQgPT09IGwpO1xuICBpZiAoIWkpXG4gICAgcmV0dXJuICExO1xuICBpZiAocylcbiAgICByZXR1cm4gaS5jaGVja2VkID0gaS5kaXNhYmxlZCA/ICExIDogISFlLCBpLmNoZWNrZWQ7XG4gIGNvbnN0IGEgPSBpcyghIWUsIGksIHQpO1xuICByZXR1cm4gbHMoaSwgdCksIGE7XG59LCBpcyA9IChsLCBlLCB0KSA9PiB7XG4gIGlmICghZS5pc0dyb3VwKVxuICAgIHJldHVybiBlLmNoZWNrZWQgPSBlLmRpc2FibGVkID8gITEgOiAhIWwsIGUuaXNQYXJ0aWFsQ2hlY2tlZCA9ICExLCBlLmNoZWNrZWQ7XG4gIGNvbnN0IHMgPSB0LmZpbHRlcigoZCkgPT4gZC5jaGlsZE9mID09PSBlLmlkKTtcbiAgcmV0dXJuICFsIHx8IGUuZGlzYWJsZWQgfHwgZS5pc1BhcnRpYWxDaGVja2VkID8gKGUuY2hlY2tlZCA9ICExLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMSwgRXQoZSwgcywgdCksIGUuY2hlY2tlZCkgOiBucyhzLCB0KSA/IGFzKHMpID8gKGUuY2hlY2tlZCA9ICExLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMSwgZS5kaXNhYmxlZCA9ICEwLCBlLmNoZWNrZWQpIDogKGUuY2hlY2tlZCA9ICExLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMCwgcy5mb3JFYWNoKChkKSA9PiB7XG4gICAgaXMobCwgZCwgdCk7XG4gIH0pLCBlLmNoZWNrZWQpIDogKGUuY2hlY2tlZCA9ICEwLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMSwgRXQoZSwgcywgdCksIGUuY2hlY2tlZCk7XG59LCBscyA9IChsLCBlKSA9PiB7XG4gIGNvbnN0IHQgPSBlLmZpbmQoKHMpID0+IHMuaWQgPT09IGwuY2hpbGRPZik7XG4gIHQgJiYgKHVpKHQsIGUpLCBscyh0LCBlKSk7XG59LCB1aSA9IChsLCBlKSA9PiB7XG4gIGNvbnN0IHQgPSBmdChsLCBlKTtcbiAgaWYgKGFzKHQpKSB7XG4gICAgbC5jaGVja2VkID0gITEsIGwuaXNQYXJ0aWFsQ2hlY2tlZCA9ICExLCBsLmRpc2FibGVkID0gITA7XG4gICAgcmV0dXJuO1xuICB9XG4gIGlmIChwaSh0KSkge1xuICAgIGwuY2hlY2tlZCA9ICEwLCBsLmlzUGFydGlhbENoZWNrZWQgPSAhMTtcbiAgICByZXR1cm47XG4gIH1cbiAgaWYgKG1pKHQpKSB7XG4gICAgbC5jaGVja2VkID0gITEsIGwuaXNQYXJ0aWFsQ2hlY2tlZCA9ICEwO1xuICAgIHJldHVybjtcbiAgfVxuICBsLmNoZWNrZWQgPSAhMSwgbC5pc1BhcnRpYWxDaGVja2VkID0gITE7XG59LCBFdCA9ICh7IGNoZWNrZWQ6IGwsIGRpc2FibGVkOiBlIH0sIHQsIHMpID0+IHtcbiAgdC5mb3JFYWNoKChpKSA9PiB7XG4gICAgaS5kaXNhYmxlZCA9ICEhZSB8fCAhIWkuZGlzYWJsZWQsIGkuY2hlY2tlZCA9ICEhbCAmJiAhaS5kaXNhYmxlZCwgaS5pc1BhcnRpYWxDaGVja2VkID0gITE7XG4gICAgY29uc3QgYSA9IGZ0KGksIHMpO1xuICAgIEV0KHsgY2hlY2tlZDogbCwgZGlzYWJsZWQ6IGUgfSwgYSwgcyk7XG4gIH0pO1xufSwgbnMgPSAobCwgZSkgPT4gbC5zb21lKChpKSA9PiBpLmRpc2FibGVkKSA/ICEwIDogbC5zb21lKChpKSA9PiB7XG4gIGlmIChpLmlzR3JvdXApIHtcbiAgICBjb25zdCBhID0gZnQoaSwgZSk7XG4gICAgcmV0dXJuIG5zKGEsIGUpO1xuICB9XG4gIHJldHVybiAhMTtcbn0pLCBhcyA9IChsKSA9PiBsLmV2ZXJ5KChlKSA9PiAhIWUuZGlzYWJsZWQpLCBwaSA9IChsKSA9PiBsLmV2ZXJ5KChlKSA9PiAhIWUuY2hlY2tlZCksIG1pID0gKGwpID0+IGwuc29tZSgoZSkgPT4gISFlLmNoZWNrZWQgfHwgISFlLmlzUGFydGlhbENoZWNrZWQpLCBmaSA9IChsKSA9PiB7XG4gIGwuZm9yRWFjaCgoZSkgPT4ge1xuICAgIGUuY2hlY2tlZCA9ICExLCBlLmlzUGFydGlhbENoZWNrZWQgPSAhMTtcbiAgfSk7XG59LCBiaSA9IChsLCBlLCB0KSA9PiB7XG4gIGNvbnN0IHMgPSB7IGxldmVsOiAwLCBncm91cElkOiBcIlwiIH0sIGkgPSBvcyhsLCBlLCBzLmdyb3VwSWQsIHMubGV2ZWwpO1xuICByZXR1cm4gZ2koaSwgdCk7XG59LCBvcyA9IChsLCBlLCB0LCBzKSA9PiBsLnJlZHVjZSgoaSwgYSkgPT4ge1xuICB2YXIgZjtcbiAgY29uc3QgaCA9ICEhKChmID0gYS5jaGlsZHJlbikgIT0gbnVsbCAmJiBmLmxlbmd0aCksIGQgPSBzID49IGUgJiYgaCwgQyA9IHMgPiBlO1xuICBpZiAoaS5wdXNoKHtcbiAgICBpZDogYS52YWx1ZSxcbiAgICBuYW1lOiBhLm5hbWUsXG4gICAgY2hpbGRPZjogdCxcbiAgICBpc0dyb3VwOiBoLFxuICAgIGNoZWNrZWQ6ICExLFxuICAgIGlzUGFydGlhbENoZWNrZWQ6ICExLFxuICAgIGxldmVsOiBzLFxuICAgIGlzQ2xvc2VkOiBkLFxuICAgIGhpZGRlbjogQyxcbiAgICBkaXNhYmxlZDogYS5kaXNhYmxlZCA/PyAhMVxuICB9KSwgaCkge1xuICAgIGNvbnN0IGIgPSBvcyhhLmNoaWxkcmVuLCBlLCBhLnZhbHVlLCBzICsgMSk7XG4gICAgaS5wdXNoKC4uLmIpO1xuICB9XG4gIHJldHVybiBpO1xufSwgW10pLCBmdCA9ICh7IGlkOiBsIH0sIGUpID0+IGUuZmlsdGVyKCh0KSA9PiB0LmNoaWxkT2YgPT09IGwpLCBDaSA9IChsKSA9PiB7XG4gIGNvbnN0IHsgdW5ncm91cGVkTm9kZXM6IGUsIGFsbEdyb3VwZWROb2RlczogdCwgYWxsTm9kZXM6IHMgfSA9IGwucmVkdWNlKFxuICAgIChhLCBoKSA9PiAoaC5jaGVja2VkICYmIChhLmFsbE5vZGVzLnB1c2goaCksIGguaXNHcm91cCA/IGEuYWxsR3JvdXBlZE5vZGVzLnB1c2goaCkgOiBhLnVuZ3JvdXBlZE5vZGVzLnB1c2goaCkpLCBhKSxcbiAgICB7XG4gICAgICB1bmdyb3VwZWROb2RlczogW10sXG4gICAgICBhbGxHcm91cGVkTm9kZXM6IFtdLFxuICAgICAgYWxsTm9kZXM6IFtdXG4gICAgfVxuICApLCBpID0gcy5maWx0ZXIoKGEpID0+ICF0LnNvbWUoKHsgaWQ6IGggfSkgPT4gaCA9PT0gYS5jaGlsZE9mKSk7XG4gIHJldHVybiB7IHVuZ3JvdXBlZE5vZGVzOiBlLCBncm91cGVkTm9kZXM6IGksIGFsbE5vZGVzOiBzIH07XG59LCBnaSA9IChsLCBlKSA9PiAobC5maWx0ZXIoKHMpID0+ICEhcy5kaXNhYmxlZCkuZm9yRWFjaChcbiAgKHsgaWQ6IHMgfSkgPT4gSXQoeyBpZDogcywgY2hlY2tlZDogITEgfSwgbCwgZSlcbiksIGwpLCBidCA9IChsLCB7IGlkOiBlLCBpc0Nsb3NlZDogdCB9KSA9PiB7XG4gIGZ0KHsgaWQ6IGUgfSwgbCkuZm9yRWFjaCgoaSkgPT4ge1xuICAgIGkuaGlkZGVuID0gdCA/PyAhMSwgaS5pc0dyb3VwICYmICFpLmlzQ2xvc2VkICYmIGJ0KGwsIHsgaWQ6IGkuaWQsIGlzQ2xvc2VkOiB0IH0pO1xuICB9KTtcbn0sIGtpID0gKGwpID0+IHtcbiAgbC5maWx0ZXIoKGUpID0+IGUuaXNHcm91cCAmJiAhZS5kaXNhYmxlZCAmJiAoZS5jaGVja2VkIHx8IGUuaXNQYXJ0aWFsQ2hlY2tlZCkpLmZvckVhY2goKGUpID0+IHtcbiAgICBlLmlzQ2xvc2VkID0gITEsIGJ0KGwsIGUpO1xuICB9KTtcbn0sIHdpID0gKGwsIGUpID0+IHtcbiAgY29uc3QgdCA9IEVpKGwsIGUpO1xuICBsLmZvckVhY2goKHMpID0+IHtcbiAgICB0LnNvbWUoKHsgaWQ6IGEgfSkgPT4gYSA9PT0gcy5pZCkgPyAocy5pc0dyb3VwICYmIChzLmlzQ2xvc2VkID0gITEsIGJ0KGwsIHMpKSwgcy5oaWRkZW4gPSAhMSkgOiBzLmhpZGRlbiA9ICEwO1xuICB9KTtcbn0sIEVpID0gKGwsIGUpID0+IGwucmVkdWNlKCh0LCBzKSA9PiB7XG4gIGlmIChzLm5hbWUudG9Mb3dlckNhc2UoKS5pbmNsdWRlcyhlLnRvTG93ZXJDYXNlKCkpKSB7XG4gICAgaWYgKHQucHVzaChzKSwgcy5pc0dyb3VwKSB7XG4gICAgICBjb25zdCBhID0gcnMocy5pZCwgbCk7XG4gICAgICB0LnB1c2goLi4uYSk7XG4gICAgfVxuICAgIGlmIChzLmNoaWxkT2YpIHtcbiAgICAgIGNvbnN0IGEgPSBjcyhzLmNoaWxkT2YsIGwpO1xuICAgICAgdC5wdXNoKC4uLmEpO1xuICAgIH1cbiAgfVxuICByZXR1cm4gdDtcbn0sIFtdKSwgcnMgPSAobCwgZSkgPT4gZS5yZWR1Y2UoKHQsIHMpID0+IChzLmNoaWxkT2YgPT09IGwgJiYgKHQucHVzaChzKSwgcy5pc0dyb3VwICYmIHQucHVzaCguLi5ycyhzLmlkLCBlKSkpLCB0KSwgW10pLCBjcyA9IChsLCBlKSA9PiBlLnJlZHVjZSgodCwgcykgPT4gKHMuaWQgPT09IGwgJiYgKHQucHVzaChzKSwgcy5jaGlsZE9mICYmIHQucHVzaCguLi5jcyhzLmNoaWxkT2YsIGUpKSksIHQpLCBbXSksIHZpID0gKGwpID0+IHtcbiAgY29uc3QgeyBkdXBsaWNhdGlvbnM6IGUgfSA9IGwucmVkdWNlKFxuICAgICh0LCBzKSA9PiAodC5hbGxJdGVtcy5zb21lKChpKSA9PiBpLnRvU3RyaW5nKCkgPT09IHMuaWQudG9TdHJpbmcoKSkgJiYgdC5kdXBsaWNhdGlvbnMucHVzaChzLmlkKSwgdC5hbGxJdGVtcy5wdXNoKHMuaWQpLCB0KSxcbiAgICB7XG4gICAgICBkdXBsaWNhdGlvbnM6IFtdLFxuICAgICAgYWxsSXRlbXM6IFtdXG4gICAgfVxuICApO1xuICBlLmxlbmd0aCAmJiBjb25zb2xlLmVycm9yKGBWYWxpZGF0aW9uOiBZb3UgaGF2ZSBkdXBsaWNhdGVkIHZhbHVlczogJHtlLmpvaW4oXCIsIFwiKX0hIFlvdSBzaG91bGQgdXNlIHVuaXF1ZSB2YWx1ZXMuYCk7XG59LCBMaSA9IChsLCBlLCB0LCBzLCBpLCBhLCBoLCBkLCBDLCBmKSA9PiB7XG4gIHNzKGwsIGUsIGksIEMpLCBkICYmIGggJiYga2koZSksIGNlKGUsIHQsIHMsIGEsIGYpO1xufSwgY2UgPSAobCwgZSwgdCwgcywgaSkgPT4ge1xuICBsLmZvckVhY2goKGEpID0+IHtcbiAgICBjb25zdCBoID0gZS5xdWVyeVNlbGVjdG9yKGBbaW5wdXQtaWQ9XCIke2EuaWR9XCJdYCksIGQgPSBUKGgpO1xuICAgIGguY2hlY2tlZCA9IGEuY2hlY2tlZCwgeWkoYSwgZCwgcyksIHhpKGEsIGQpLCBTaShhLCBkKSwgX2koYSwgZCwgdCksIEFpKGEsIGQpLCBOaShhLCBkLCBsLCBpKSwgVGkoYSwgaCwgdCk7XG4gIH0pLCBPaShsLCBlKTtcbn0sIHlpID0gKGwsIGUsIHQpID0+IHtcbiAgbC5jaGVja2VkID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1jaGVja2VkXCIpIDogZS5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1jaGVja2VkXCIpLCBBcnJheS5pc0FycmF5KHQpICYmIHRbMF0gPT09IGwuaWQgJiYgIWwuZGlzYWJsZWQgPyBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLXNpbmdsZS1zZWxlY3RlZFwiKSA6IGUuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tc2luZ2xlLXNlbGVjdGVkXCIpO1xufSwgeGkgPSAobCwgZSkgPT4ge1xuICBsLmlzUGFydGlhbENoZWNrZWQgPyBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLXBhcnRpYWwtY2hlY2tlZFwiKSA6IGUuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tcGFydGlhbC1jaGVja2VkXCIpO1xufSwgU2kgPSAobCwgZSkgPT4ge1xuICBsLmRpc2FibGVkID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1kaXNhYmxlZFwiKSA6IGUuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZGlzYWJsZWRcIik7XG59LCBfaSA9IChsLCBlLCB0KSA9PiB7XG4gIGlmIChsLmlzR3JvdXApIHtcbiAgICBjb25zdCBzID0gZS5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1pY29uXCIpLCBpID0gbC5pc0Nsb3NlZCA/IHQuYXJyb3dSaWdodCA6IHQuYXJyb3dEb3duO1xuICAgIEkoaSwgcyksIGwuaXNDbG9zZWQgPyBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWNsb3NlZFwiKSA6IGUuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tY2xvc2VkXCIpO1xuICB9XG59LCBBaSA9IChsLCBlKSA9PiB7XG4gIGwuaGlkZGVuID8gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1oaWRkZW5cIikgOiBlLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWhpZGRlblwiKTtcbn0sIFRpID0gKGwsIGUsIHQpID0+IHtcbiAgY29uc3QgaSA9IGUucGFyZW50Tm9kZS5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1jaGVja2JveC1pY29uXCIpO1xuICBsLmNoZWNrZWQgPyBJKHQuY2hlY2ssIGkpIDogbC5pc1BhcnRpYWxDaGVja2VkID8gSSh0LnBhcnRpYWxDaGVjaywgaSkgOiBpLmlubmVySFRNTCA9IFwiXCI7XG59LCBOaSA9IChsLCBlLCB0LCBzKSA9PiB7XG4gIGNvbnN0IGkgPSBsLmxldmVsID09PSAwLCBhID0gMjAsIGggPSA1O1xuICBpZiAoaSkge1xuICAgIGNvbnN0IGQgPSB0LnNvbWUoKGIpID0+IGIuaXNHcm91cCAmJiBiLmxldmVsID09PSBsLmxldmVsKSwgQyA9ICFsLmlzR3JvdXAgJiYgZCA/IGAke2F9cHhgIDogYCR7aH1weGAsIGYgPSBsLmlzR3JvdXAgPyBcIjBcIiA6IEM7XG4gICAgcyA/IGUuc3R5bGUucGFkZGluZ1JpZ2h0ID0gZiA6IGUuc3R5bGUucGFkZGluZ0xlZnQgPSBmO1xuICB9IGVsc2Uge1xuICAgIGNvbnN0IGQgPSBsLmlzR3JvdXAgPyBgJHtsLmxldmVsICogYX1weGAgOiBgJHtsLmxldmVsICogYSArIGF9cHhgO1xuICAgIHMgPyBlLnN0eWxlLnBhZGRpbmdSaWdodCA9IGQgOiBlLnN0eWxlLnBhZGRpbmdMZWZ0ID0gZDtcbiAgfVxuICBlLnNldEF0dHJpYnV0ZShcImxldmVsXCIsIGwubGV2ZWwudG9TdHJpbmcoKSksIGUuc2V0QXR0cmlidXRlKFwiZ3JvdXBcIiwgbC5pc0dyb3VwLnRvU3RyaW5nKCkpO1xufSwgT2kgPSAobCwgZSkgPT4ge1xuICBjb25zdCB0ID0gbC5zb21lKChpKSA9PiAhaS5oaWRkZW4pLCBzID0gZS5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9fZW1wdHlcIik7XG4gIHQgPyBzLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2VtcHR5LS1oaWRkZW5cIikgOiBzLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3RfX2VtcHR5LS1oaWRkZW5cIik7XG59LCBUID0gKGwpID0+IGwucGFyZW50Tm9kZS5wYXJlbnROb2RlLCBWdCA9IChsLCBlKSA9PiBlLmZpbmQoKHQpID0+IHQuaWQudG9TdHJpbmcoKSA9PT0gbCksIElpID0gKGwpID0+IFQobCkucXVlcnlTZWxlY3RvcihcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0taWNvblwiKSwgUGkgPSAobCwgZSkgPT4ge1xuICBlICYmIE9iamVjdC5rZXlzKGUpLmZvckVhY2goKHQpID0+IHtcbiAgICBjb25zdCBzID0gZVt0XTtcbiAgICB0eXBlb2YgcyA9PSBcInN0cmluZ1wiICYmIGwuc2V0QXR0cmlidXRlKHQsIHMpO1xuICB9KTtcbn07XG52YXIgTSwgUCwgUywgWSwgT2UsIGhzLCBJZSwgZHMsIFBlLCB1cywgQmUsIHBzLCBWZSwgbXMsIERlLCBmcywgSywgdnQsIEhlLCBicywgR2UsIENzLCBNZSwgZ3MsIFgsIEx0LCBGZSwga3MsIHFlLCB3cywgamUsIEVzLCBSZSwgdnMsICRlLCBMcywgV2UsIHlzLCBVZSwgeHMsIHplLCBTcywgWWUsIF9zLCBLZSwgQXMsIFhlLCBUcywgSiwgeXQsIFosIHh0LCBKZSwgTnM7XG5jbGFzcyBCaSB7XG4gIGNvbnN0cnVjdG9yKHtcbiAgICBvcHRpb25zOiBlLFxuICAgIHZhbHVlOiB0LFxuICAgIG9wZW5MZXZlbDogcyxcbiAgICBsaXN0U2xvdEh0bWxDb21wb25lbnQ6IGksXG4gICAgZW1wdHlUZXh0OiBhLFxuICAgIGlzU2luZ2xlU2VsZWN0OiBoLFxuICAgIGljb25FbGVtZW50czogZCxcbiAgICBzaG93Q291bnQ6IEMsXG4gICAgZGlzYWJsZWRCcmFuY2hOb2RlOiBmLFxuICAgIGV4cGFuZFNlbGVjdGVkOiBiLFxuICAgIGlzSW5kZXBlbmRlbnROb2RlczogZyxcbiAgICBydGw6IGssXG4gICAgaW5wdXRDYWxsYmFjazogdyxcbiAgICBhcnJvd0NsaWNrQ2FsbGJhY2s6IHksXG4gICAgbW91c2V1cENhbGxiYWNrOiB4XG4gIH0pIHtcbiAgICAvLyBQcml2YXRlIG1ldGhvZHNcbiAgICByKHRoaXMsIE9lKTtcbiAgICByKHRoaXMsIEllKTtcbiAgICByKHRoaXMsIFBlKTtcbiAgICByKHRoaXMsIEJlKTtcbiAgICByKHRoaXMsIFZlKTtcbiAgICByKHRoaXMsIERlKTtcbiAgICByKHRoaXMsIEspO1xuICAgIHIodGhpcywgSGUpO1xuICAgIHIodGhpcywgR2UpO1xuICAgIHIodGhpcywgTWUpO1xuICAgIHIodGhpcywgWCk7XG4gICAgcih0aGlzLCBGZSk7XG4gICAgcih0aGlzLCBxZSk7XG4gICAgcih0aGlzLCBqZSk7XG4gICAgcih0aGlzLCBSZSk7XG4gICAgcih0aGlzLCAkZSk7XG4gICAgcih0aGlzLCBXZSk7XG4gICAgcih0aGlzLCBVZSk7XG4gICAgcih0aGlzLCB6ZSk7XG4gICAgcih0aGlzLCBZZSk7XG4gICAgLy8gQWN0aW9uc1xuICAgIHIodGhpcywgS2UpO1xuICAgIHIodGhpcywgWGUpO1xuICAgIHIodGhpcywgSik7XG4gICAgcih0aGlzLCBaKTtcbiAgICAvLyBFbWl0c1xuICAgIHIodGhpcywgSmUpO1xuICAgIC8vIFByb3BzXG4gICAgYyh0aGlzLCBcIm9wdGlvbnNcIik7XG4gICAgYyh0aGlzLCBcInZhbHVlXCIpO1xuICAgIGModGhpcywgXCJvcGVuTGV2ZWxcIik7XG4gICAgYyh0aGlzLCBcImxpc3RTbG90SHRtbENvbXBvbmVudFwiKTtcbiAgICBjKHRoaXMsIFwiZW1wdHlUZXh0XCIpO1xuICAgIGModGhpcywgXCJpc1NpbmdsZVNlbGVjdFwiKTtcbiAgICBjKHRoaXMsIFwic2hvd0NvdW50XCIpO1xuICAgIGModGhpcywgXCJkaXNhYmxlZEJyYW5jaE5vZGVcIik7XG4gICAgYyh0aGlzLCBcImV4cGFuZFNlbGVjdGVkXCIpO1xuICAgIGModGhpcywgXCJpc0luZGVwZW5kZW50Tm9kZXNcIik7XG4gICAgYyh0aGlzLCBcInJ0bFwiKTtcbiAgICBjKHRoaXMsIFwiaWNvbkVsZW1lbnRzXCIpO1xuICAgIC8vIElubmVyU3RhdGVcbiAgICBjKHRoaXMsIFwic2VhcmNoVGV4dFwiKTtcbiAgICBjKHRoaXMsIFwiZmxhdHRlZE9wdGlvbnNcIik7XG4gICAgYyh0aGlzLCBcImZsYXR0ZWRPcHRpb25zQmVmb3JlU2VhcmNoXCIpO1xuICAgIGModGhpcywgXCJzZWxlY3RlZE5vZGVzXCIpO1xuICAgIGModGhpcywgXCJzcmNFbGVtZW50XCIpO1xuICAgIC8vIENhbGxiYWNrc1xuICAgIGModGhpcywgXCJpbnB1dENhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJhcnJvd0NsaWNrQ2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm1vdXNldXBDYWxsYmFja1wiKTtcbiAgICAvLyBQcml2YXRlSW5uZXJTdGF0ZVxuICAgIHIodGhpcywgTSwgbnVsbCk7XG4gICAgcih0aGlzLCBQLCAhMCk7XG4gICAgcih0aGlzLCBTLCBbXSk7XG4gICAgcih0aGlzLCBZLCAhMCk7XG4gICAgdGhpcy5vcHRpb25zID0gZSwgdGhpcy52YWx1ZSA9IHQsIHRoaXMub3BlbkxldmVsID0gcyA/PyAwLCB0aGlzLmxpc3RTbG90SHRtbENvbXBvbmVudCA9IGkgPz8gbnVsbCwgdGhpcy5lbXB0eVRleHQgPSBhID8/IFwiTm8gcmVzdWx0cyBmb3VuZC4uLlwiLCB0aGlzLmlzU2luZ2xlU2VsZWN0ID0gaCA/PyAhMSwgdGhpcy5zaG93Q291bnQgPSBDID8/ICExLCB0aGlzLmRpc2FibGVkQnJhbmNoTm9kZSA9IGYgPz8gITEsIHRoaXMuZXhwYW5kU2VsZWN0ZWQgPSBiID8/ICExLCB0aGlzLmlzSW5kZXBlbmRlbnROb2RlcyA9IGcgPz8gITEsIHRoaXMucnRsID0gayA/PyAhMSwgdGhpcy5pY29uRWxlbWVudHMgPSBkLCB0aGlzLnNlYXJjaFRleHQgPSBcIlwiLCB0aGlzLmZsYXR0ZWRPcHRpb25zID0gYmkodGhpcy5vcHRpb25zLCB0aGlzLm9wZW5MZXZlbCwgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMpLCB0aGlzLmZsYXR0ZWRPcHRpb25zQmVmb3JlU2VhcmNoID0gdGhpcy5mbGF0dGVkT3B0aW9ucywgdGhpcy5zZWxlY3RlZE5vZGVzID0geyBub2RlczogW10sIGdyb3VwZWROb2RlczogW10sIGFsbE5vZGVzOiBbXSB9LCB0aGlzLnNyY0VsZW1lbnQgPSBvKHRoaXMsIFBlLCB1cykuY2FsbCh0aGlzKSwgdGhpcy5pbnB1dENhbGxiYWNrID0gdywgdGhpcy5hcnJvd0NsaWNrQ2FsbGJhY2sgPSB5LCB0aGlzLm1vdXNldXBDYWxsYmFjayA9IHgsIHZpKHRoaXMuZmxhdHRlZE9wdGlvbnMpO1xuICB9XG4gIC8vIFB1YmxpYyBtZXRob2RzXG4gIHVwZGF0ZVZhbHVlKGUpIHtcbiAgICB0aGlzLnZhbHVlID0gZSwgbSh0aGlzLCBTLCB0aGlzLmlzU2luZ2xlU2VsZWN0ID8gdGhpcy52YWx1ZSA6IFtdKSwgTGkoXG4gICAgICBlLFxuICAgICAgdGhpcy5mbGF0dGVkT3B0aW9ucyxcbiAgICAgIHRoaXMuc3JjRWxlbWVudCxcbiAgICAgIHRoaXMuaWNvbkVsZW1lbnRzLFxuICAgICAgdGhpcy5pc1NpbmdsZVNlbGVjdCxcbiAgICAgIG4odGhpcywgUyksXG4gICAgICB0aGlzLmV4cGFuZFNlbGVjdGVkLFxuICAgICAgbih0aGlzLCBZKSxcbiAgICAgIHRoaXMuaXNJbmRlcGVuZGVudE5vZGVzLFxuICAgICAgdGhpcy5ydGxcbiAgICApLCBtKHRoaXMsIFksICExKSwgbyh0aGlzLCBaLCB4dCkuY2FsbCh0aGlzKTtcbiAgfVxuICB1cGRhdGVTZWFyY2hWYWx1ZShlKSB7XG4gICAgaWYgKGUgPT09IHRoaXMuc2VhcmNoVGV4dClcbiAgICAgIHJldHVybjtcbiAgICBjb25zdCB0ID0gdGhpcy5zZWFyY2hUZXh0ID09PSBcIlwiICYmIGUgIT09IFwiXCI7XG4gICAgdGhpcy5zZWFyY2hUZXh0ID0gZSwgdCAmJiAodGhpcy5mbGF0dGVkT3B0aW9uc0JlZm9yZVNlYXJjaCA9IEpTT04ucGFyc2UoSlNPTi5zdHJpbmdpZnkodGhpcy5mbGF0dGVkT3B0aW9ucykpKSwgdGhpcy5zZWFyY2hUZXh0ID09PSBcIlwiICYmICh0aGlzLmZsYXR0ZWRPcHRpb25zID0gdGhpcy5mbGF0dGVkT3B0aW9uc0JlZm9yZVNlYXJjaC5tYXAoKHMpID0+IHtcbiAgICAgIGNvbnN0IGkgPSB0aGlzLmZsYXR0ZWRPcHRpb25zLmZpbmQoKGEpID0+IGEuaWQgPT09IHMuaWQpO1xuICAgICAgcmV0dXJuIGkuaXNDbG9zZWQgPSBzLmlzQ2xvc2VkLCBpLmhpZGRlbiA9IHMuaGlkZGVuLCBpO1xuICAgIH0pLCB0aGlzLmZsYXR0ZWRPcHRpb25zQmVmb3JlU2VhcmNoID0gW10pLCB0aGlzLnNlYXJjaFRleHQgJiYgd2kodGhpcy5mbGF0dGVkT3B0aW9ucywgZSksIGNlKHRoaXMuZmxhdHRlZE9wdGlvbnMsIHRoaXMuc3JjRWxlbWVudCwgdGhpcy5pY29uRWxlbWVudHMsIG4odGhpcywgUyksIHRoaXMucnRsKSwgdGhpcy5mb2N1c0ZpcnN0TGlzdEVsZW1lbnQoKTtcbiAgfVxuICBjYWxsS2V5QWN0aW9uKGUpIHtcbiAgICBtKHRoaXMsIFAsICExKTtcbiAgICBjb25zdCB0ID0gdGhpcy5zcmNFbGVtZW50LnF1ZXJ5U2VsZWN0b3IoXCIudHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCIpO1xuICAgIGlmICh0ID09IG51bGwgPyB2b2lkIDAgOiB0LmNsYXNzTGlzdC5jb250YWlucyhcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0taGlkZGVuXCIpKVxuICAgICAgcmV0dXJuO1xuICAgIGNvbnN0IGkgPSBlLmtleTtcbiAgICBpID09PSBcIkVudGVyXCIgJiYgdCAmJiB0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KFwibW91c2Vkb3duXCIpKSwgKGkgPT09IFwiQXJyb3dMZWZ0XCIgfHwgaSA9PT0gXCJBcnJvd1JpZ2h0XCIpICYmIG8odGhpcywgT2UsIGhzKS5jYWxsKHRoaXMsIHQsIGUpLCAoaSA9PT0gXCJBcnJvd0Rvd25cIiB8fCBpID09PSBcIkFycm93VXBcIikgJiYgbyh0aGlzLCBJZSwgZHMpLmNhbGwodGhpcywgdCwgaSk7XG4gIH1cbiAgZm9jdXNGaXJzdExpc3RFbGVtZW50KCkge1xuICAgIGNvbnN0IGUgPSBcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiLCB0ID0gdGhpcy5zcmNFbGVtZW50LnF1ZXJ5U2VsZWN0b3IoYC4ke2V9YCksIHMgPSBBcnJheS5mcm9tKHRoaXMuc3JjRWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1jaGVja2JveFwiKSkuZmlsdGVyKFxuICAgICAgKGEpID0+IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKFQoYSkpLmRpc3BsYXkgIT09IFwibm9uZVwiXG4gICAgKTtcbiAgICBpZiAoIXMubGVuZ3RoKVxuICAgICAgcmV0dXJuO1xuICAgIHQgJiYgdC5jbGFzc0xpc3QucmVtb3ZlKGUpLCBUKHNbMF0pLmNsYXNzTGlzdC5hZGQoZSk7XG4gIH1cbiAgaXNMYXN0Rm9jdXNlZEVsZW1lbnRFeGlzdCgpIHtcbiAgICByZXR1cm4gISFuKHRoaXMsIE0pO1xuICB9XG59XG5NID0gbmV3IFdlYWtNYXAoKSwgUCA9IG5ldyBXZWFrTWFwKCksIFMgPSBuZXcgV2Vha01hcCgpLCBZID0gbmV3IFdlYWtNYXAoKSwgT2UgPSBuZXcgV2Vha1NldCgpLCBocyA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgaWYgKCFlKVxuICAgIHJldHVybjtcbiAgY29uc3QgcyA9IHQua2V5LCBhID0gZS5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1jaGVja2JveFwiKS5nZXRBdHRyaWJ1dGUoXCJpbnB1dC1pZFwiKSwgaCA9IFZ0KGEsIHRoaXMuZmxhdHRlZE9wdGlvbnMpLCBkID0gZS5xdWVyeVNlbGVjdG9yKFwiLnRyZWVzZWxlY3QtbGlzdF9faXRlbS1pY29uXCIpO1xuICBzID09PSBcIkFycm93TGVmdFwiICYmICFoLmlzQ2xvc2VkICYmIGguaXNHcm91cCAmJiAoZC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudChcIm1vdXNlZG93blwiKSksIHQucHJldmVudERlZmF1bHQoKSksIHMgPT09IFwiQXJyb3dSaWdodFwiICYmIGguaXNDbG9zZWQgJiYgaC5pc0dyb3VwICYmIChkLmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KFwibW91c2Vkb3duXCIpKSwgdC5wcmV2ZW50RGVmYXVsdCgpKTtcbn0sIEllID0gbmV3IFdlYWtTZXQoKSwgZHMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIHZhciBpO1xuICBjb25zdCBzID0gQXJyYXkuZnJvbSh0aGlzLnNyY0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi50cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3hcIikpLmZpbHRlcihcbiAgICAoYSkgPT4gd2luZG93LmdldENvbXB1dGVkU3R5bGUoVChhKSkuZGlzcGxheSAhPT0gXCJub25lXCJcbiAgKTtcbiAgaWYgKHMubGVuZ3RoKVxuICAgIGlmICghZSlcbiAgICAgIFQoc1swXSkuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS0tZm9jdXNlZFwiKTtcbiAgICBlbHNlIHtcbiAgICAgIGNvbnN0IGEgPSBzLmZpbmRJbmRleChcbiAgICAgICAgKHgpID0+IFQoeCkuY2xhc3NMaXN0LmNvbnRhaW5zKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCIpXG4gICAgICApO1xuICAgICAgVChzW2FdKS5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCIpO1xuICAgICAgY29uc3QgZCA9IHQgPT09IFwiQXJyb3dEb3duXCIgPyBhICsgMSA6IGEgLSAxLCBDID0gdCA9PT0gXCJBcnJvd0Rvd25cIiA/IDAgOiBzLmxlbmd0aCAtIDEsIGYgPSBzW2RdID8/IHNbQ10sIGIgPSAhc1tkXSwgZyA9IFQoZik7XG4gICAgICBnLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWZvY3VzZWRcIik7XG4gICAgICBjb25zdCBrID0gdGhpcy5zcmNFbGVtZW50LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLCB3ID0gZy5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKTtcbiAgICAgIGlmIChiICYmIHQgPT09IFwiQXJyb3dEb3duXCIpIHtcbiAgICAgICAgdGhpcy5zcmNFbGVtZW50LnNjcm9sbCgwLCAwKTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgaWYgKGIgJiYgdCA9PT0gXCJBcnJvd1VwXCIpIHtcbiAgICAgICAgdGhpcy5zcmNFbGVtZW50LnNjcm9sbCgwLCB0aGlzLnNyY0VsZW1lbnQuc2Nyb2xsSGVpZ2h0KTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgY29uc3QgeSA9ICgoaSA9IHRoaXMubGlzdFNsb3RIdG1sQ29tcG9uZW50KSA9PSBudWxsID8gdm9pZCAwIDogaS5jbGllbnRIZWlnaHQpID8/IDA7XG4gICAgICBpZiAoay55ICsgay5oZWlnaHQgPCB3LnkgKyB3LmhlaWdodCArIHkpIHtcbiAgICAgICAgdGhpcy5zcmNFbGVtZW50LnNjcm9sbCgwLCB0aGlzLnNyY0VsZW1lbnQuc2Nyb2xsVG9wICsgdy5oZWlnaHQpO1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICBpZiAoay55ID4gdy55KSB7XG4gICAgICAgIHRoaXMuc3JjRWxlbWVudC5zY3JvbGwoMCwgdGhpcy5zcmNFbGVtZW50LnNjcm9sbFRvcCAtIHcuaGVpZ2h0KTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgIH1cbn0sIFBlID0gbmV3IFdlYWtTZXQoKSwgdXMgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IG8odGhpcywgQmUsIHBzKS5jYWxsKHRoaXMpLCB0ID0gbyh0aGlzLCBLLCB2dCkuY2FsbCh0aGlzLCB0aGlzLm9wdGlvbnMpO1xuICBlLmFwcGVuZCguLi50KTtcbiAgY29uc3QgcyA9IG8odGhpcywgR2UsIENzKS5jYWxsKHRoaXMpO1xuICBlLmFwcGVuZChzKTtcbiAgY29uc3QgaSA9IG8odGhpcywgSGUsIGJzKS5jYWxsKHRoaXMpO1xuICByZXR1cm4gaSAmJiBlLmFwcGVuZChpKSwgZTtcbn0sIEJlID0gbmV3IFdlYWtTZXQoKSwgcHMgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHJldHVybiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RcIiksIHRoaXMuaXNTaW5nbGVTZWxlY3QgJiYgZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0LS1zaW5nbGUtc2VsZWN0XCIpLCB0aGlzLmRpc2FibGVkQnJhbmNoTm9kZSAmJiBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3QtLWRpc2FibGVkLWJyYW5jaC1ub2RlXCIpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZW91dFwiLCAodCkgPT4gbyh0aGlzLCBWZSwgbXMpLmNhbGwodGhpcywgdCkpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZW1vdmVcIiwgKCkgPT4gbyh0aGlzLCBEZSwgZnMpLmNhbGwodGhpcykpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZXVwXCIsICgpID0+IHRoaXMubW91c2V1cENhbGxiYWNrKCksICEwKSwgZTtcbn0sIFZlID0gbmV3IFdlYWtTZXQoKSwgbXMgPSBmdW5jdGlvbihlKSB7XG4gIGUuc3RvcFByb3BhZ2F0aW9uKCksIG4odGhpcywgTSkgJiYgbih0aGlzLCBQKSAmJiBuKHRoaXMsIE0pLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tLWZvY3VzZWRcIik7XG59LCBEZSA9IG5ldyBXZWFrU2V0KCksIGZzID0gZnVuY3Rpb24oKSB7XG4gIG0odGhpcywgUCwgITApO1xufSwgSyA9IG5ldyBXZWFrU2V0KCksIHZ0ID0gZnVuY3Rpb24oZSkge1xuICByZXR1cm4gZS5yZWR1Y2UoKHQsIHMpID0+IHtcbiAgICB2YXIgYTtcbiAgICBpZiAoKGEgPSBzLmNoaWxkcmVuKSAhPSBudWxsICYmIGEubGVuZ3RoKSB7XG4gICAgICBjb25zdCBoID0gbyh0aGlzLCBNZSwgZ3MpLmNhbGwodGhpcywgcyksIGQgPSBvKHRoaXMsIEssIHZ0KS5jYWxsKHRoaXMsIHMuY2hpbGRyZW4pO1xuICAgICAgcmV0dXJuIGguYXBwZW5kKC4uLmQpLCB0LnB1c2goaCksIHQ7XG4gICAgfVxuICAgIGNvbnN0IGkgPSBvKHRoaXMsIFgsIEx0KS5jYWxsKHRoaXMsIHMsICExKTtcbiAgICByZXR1cm4gdC5wdXNoKGkpLCB0O1xuICB9LCBbXSk7XG59LCBIZSA9IG5ldyBXZWFrU2V0KCksIGJzID0gZnVuY3Rpb24oKSB7XG4gIGlmICghdGhpcy5saXN0U2xvdEh0bWxDb21wb25lbnQpXG4gICAgcmV0dXJuIG51bGw7XG4gIGNvbnN0IGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICByZXR1cm4gZS5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19zbG90XCIpLCBlLmFwcGVuZENoaWxkKHRoaXMubGlzdFNsb3RIdG1sQ29tcG9uZW50KSwgZTtcbn0sIEdlID0gbmV3IFdlYWtTZXQoKSwgQ3MgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIGUuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9fZW1wdHlcIiksIGUuc2V0QXR0cmlidXRlKFwidGl0bGVcIiwgdGhpcy5lbXB0eVRleHQpO1xuICBjb25zdCB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gIHQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9fZW1wdHktaWNvblwiKSwgSSh0aGlzLmljb25FbGVtZW50cy5hdHRlbnRpb24sIHQpO1xuICBjb25zdCBzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gIHJldHVybiBzLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2VtcHR5LXRleHRcIiksIHMudGV4dENvbnRlbnQgPSB0aGlzLmVtcHR5VGV4dCwgZS5hcHBlbmQodCwgcyksIGU7XG59LCBNZSA9IG5ldyBXZWFrU2V0KCksIGdzID0gZnVuY3Rpb24oZSkge1xuICBjb25zdCB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgdC5zZXRBdHRyaWJ1dGUoXCJncm91cC1jb250YWluZXItaWRcIiwgZS52YWx1ZS50b1N0cmluZygpKSwgdC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19ncm91cC1jb250YWluZXJcIik7XG4gIGNvbnN0IHMgPSBvKHRoaXMsIFgsIEx0KS5jYWxsKHRoaXMsIGUsICEwKTtcbiAgcmV0dXJuIHQuYXBwZW5kQ2hpbGQocyksIHQ7XG59LCBYID0gbmV3IFdlYWtTZXQoKSwgTHQgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGNvbnN0IHMgPSBvKHRoaXMsIEZlLCBrcykuY2FsbCh0aGlzLCBlKTtcbiAgaWYgKHQpIHtcbiAgICBjb25zdCBoID0gbyh0aGlzLCAkZSwgTHMpLmNhbGwodGhpcyk7XG4gICAgcy5hcHBlbmRDaGlsZChoKSwgcy5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1ncm91cFwiKTtcbiAgfVxuICBjb25zdCBpID0gbyh0aGlzLCBVZSwgeHMpLmNhbGwodGhpcywgZSksIGEgPSBvKHRoaXMsIHplLCBTcykuY2FsbCh0aGlzLCBlLCB0KTtcbiAgcmV0dXJuIHMuYXBwZW5kKGksIGEpLCBzO1xufSwgRmUgPSBuZXcgV2Vha1NldCgpLCBrcyA9IGZ1bmN0aW9uKGUpIHtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gIHJldHVybiBQaSh0LCBlLmh0bWxBdHRyKSwgdC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpLCB0LnNldEF0dHJpYnV0ZShcInRpdGxlXCIsIGUubmFtZSksIHQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbVwiKSwgdC5hZGRFdmVudExpc3RlbmVyKFwibW91c2VvdmVyXCIsICgpID0+IG8odGhpcywgcWUsIHdzKS5jYWxsKHRoaXMsIHQpLCAhMCksIHQuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlb3V0XCIsICgpID0+IG8odGhpcywgamUsIEVzKS5jYWxsKHRoaXMsIHQpLCAhMCksIHQuYWRkRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCAocykgPT4gbyh0aGlzLCBSZSwgdnMpLmNhbGwodGhpcywgcywgZSkpLCB0O1xufSwgcWUgPSBuZXcgV2Vha1NldCgpLCB3cyA9IGZ1bmN0aW9uKGUpIHtcbiAgbih0aGlzLCBQKSAmJiBvKHRoaXMsIEosIHl0KS5jYWxsKHRoaXMsICEwLCBlKTtcbn0sIGplID0gbmV3IFdlYWtTZXQoKSwgRXMgPSBmdW5jdGlvbihlKSB7XG4gIG4odGhpcywgUCkgJiYgKG8odGhpcywgSiwgeXQpLmNhbGwodGhpcywgITEsIGUpLCBtKHRoaXMsIE0sIGUpKTtcbn0sIFJlID0gbmV3IFdlYWtTZXQoKSwgdnMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIHZhciBhO1xuICBpZiAoZS5wcmV2ZW50RGVmYXVsdCgpLCBlLnN0b3BQcm9wYWdhdGlvbigpLCAoYSA9IHRoaXMuZmxhdHRlZE9wdGlvbnMuZmluZCgoaCkgPT4gaC5pZCA9PT0gdC52YWx1ZSkpID09IG51bGwgPyB2b2lkIDAgOiBhLmRpc2FibGVkKVxuICAgIHJldHVybjtcbiAgY29uc3QgaSA9IGUudGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoXCIudHJlZXNlbGVjdC1saXN0X19pdGVtLWNoZWNrYm94XCIpO1xuICBpLmNoZWNrZWQgPSAhaS5jaGVja2VkLCBvKHRoaXMsIEtlLCBBcykuY2FsbCh0aGlzLCBpLCB0KTtcbn0sICRlID0gbmV3IFdlYWtTZXQoKSwgTHMgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgZSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICByZXR1cm4gZS5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpLCBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0taWNvblwiKSwgSSh0aGlzLmljb25FbGVtZW50cy5hcnJvd0Rvd24sIGUpLCBlLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgKHQpID0+IG8odGhpcywgV2UsIHlzKS5jYWxsKHRoaXMsIHQpKSwgZTtcbn0sIFdlID0gbmV3IFdlYWtTZXQoKSwgeXMgPSBmdW5jdGlvbihlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKSwgZS5zdG9wUHJvcGFnYXRpb24oKSwgbyh0aGlzLCBYZSwgVHMpLmNhbGwodGhpcywgZSk7XG59LCBVZSA9IG5ldyBXZWFrU2V0KCksIHhzID0gZnVuY3Rpb24oZSkge1xuICBjb25zdCB0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgdC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0X19pdGVtLWNoZWNrYm94LWNvbnRhaW5lclwiKTtcbiAgY29uc3QgcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpO1xuICBzLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3gtaWNvblwiKSwgcy5pbm5lckhUTUwgPSBcIlwiO1xuICBjb25zdCBpID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImlucHV0XCIpO1xuICByZXR1cm4gaS5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpLCBpLnNldEF0dHJpYnV0ZShcInR5cGVcIiwgXCJjaGVja2JveFwiKSwgaS5zZXRBdHRyaWJ1dGUoXCJpbnB1dC1pZFwiLCBlLnZhbHVlLnRvU3RyaW5nKCkpLCBpLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tY2hlY2tib3hcIiksIHQuYXBwZW5kKHMsIGkpLCB0O1xufSwgemUgPSBuZXcgV2Vha1NldCgpLCBTcyA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgY29uc3QgcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJsYWJlbFwiKTtcbiAgaWYgKHMudGV4dENvbnRlbnQgPSBlLm5hbWUsIHMuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtbGlzdF9faXRlbS1sYWJlbFwiKSwgdCAmJiB0aGlzLnNob3dDb3VudCkge1xuICAgIGNvbnN0IGkgPSBvKHRoaXMsIFllLCBfcykuY2FsbCh0aGlzLCBlKTtcbiAgICBzLmFwcGVuZENoaWxkKGkpO1xuICB9XG4gIHJldHVybiBzO1xufSwgWWUgPSBuZXcgV2Vha1NldCgpLCBfcyA9IGZ1bmN0aW9uKGUpIHtcbiAgY29uc3QgdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJzcGFuXCIpLCBzID0gdGhpcy5mbGF0dGVkT3B0aW9ucy5maWx0ZXIoKGkpID0+IGkuY2hpbGRPZiA9PT0gZS52YWx1ZSk7XG4gIHJldHVybiB0LnRleHRDb250ZW50ID0gYCgke3MubGVuZ3RofSlgLCB0LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWxpc3RfX2l0ZW0tbGFiZWwtY291bnRlclwiKSwgdDtcbn0sIEtlID0gbmV3IFdlYWtTZXQoKSwgQXMgPSBmdW5jdGlvbihlLCB0KSB7XG4gIGNvbnN0IHMgPSB0aGlzLmZsYXR0ZWRPcHRpb25zLmZpbmQoKGkpID0+IGkuaWQgPT09IHQudmFsdWUpO1xuICBpZiAocykge1xuICAgIGlmIChzICE9IG51bGwgJiYgcy5pc0dyb3VwICYmIHRoaXMuZGlzYWJsZWRCcmFuY2hOb2RlKSB7XG4gICAgICBjb25zdCBpID0gSWkoZSk7XG4gICAgICBpID09IG51bGwgfHwgaS5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudChcIm1vdXNlZG93blwiKSk7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIGlmICh0aGlzLmlzU2luZ2xlU2VsZWN0KSB7XG4gICAgICBjb25zdCBbaV0gPSBuKHRoaXMsIFMpO1xuICAgICAgaWYgKHMuaWQgPT09IGkpXG4gICAgICAgIHJldHVybjtcbiAgICAgIG0odGhpcywgUywgW3MuaWRdKSwgc3MoW3MuaWRdLCB0aGlzLmZsYXR0ZWRPcHRpb25zLCB0aGlzLmlzU2luZ2xlU2VsZWN0LCB0aGlzLmlzSW5kZXBlbmRlbnROb2Rlcyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHMuY2hlY2tlZCA9IGUuY2hlY2tlZDtcbiAgICAgIGNvbnN0IGkgPSBJdChzLCB0aGlzLmZsYXR0ZWRPcHRpb25zLCB0aGlzLmlzSW5kZXBlbmRlbnROb2Rlcyk7XG4gICAgICBlLmNoZWNrZWQgPSBpO1xuICAgIH1cbiAgICBjZSh0aGlzLmZsYXR0ZWRPcHRpb25zLCB0aGlzLnNyY0VsZW1lbnQsIHRoaXMuaWNvbkVsZW1lbnRzLCBuKHRoaXMsIFMpLCB0aGlzLnJ0bCksIG8odGhpcywgSmUsIE5zKS5jYWxsKHRoaXMpO1xuICB9XG59LCBYZSA9IG5ldyBXZWFrU2V0KCksIFRzID0gZnVuY3Rpb24oZSkge1xuICB2YXIgYSwgaDtcbiAgY29uc3QgdCA9IChoID0gKGEgPSBlLnRhcmdldCkgPT0gbnVsbCA/IHZvaWQgMCA6IGEucGFyZW50Tm9kZSkgPT0gbnVsbCA/IHZvaWQgMCA6IGgucXVlcnlTZWxlY3RvcihcIltpbnB1dC1pZF1cIiksIHMgPSAodCA9PSBudWxsID8gdm9pZCAwIDogdC5nZXRBdHRyaWJ1dGUoXCJpbnB1dC1pZFwiKSkgPz8gbnVsbCwgaSA9IFZ0KHMsIHRoaXMuZmxhdHRlZE9wdGlvbnMpO1xuICBpICYmIChpLmlzQ2xvc2VkID0gIWkuaXNDbG9zZWQsIGJ0KHRoaXMuZmxhdHRlZE9wdGlvbnMsIGkpLCBjZSh0aGlzLmZsYXR0ZWRPcHRpb25zLCB0aGlzLnNyY0VsZW1lbnQsIHRoaXMuaWNvbkVsZW1lbnRzLCBuKHRoaXMsIFMpLCB0aGlzLnJ0bCksIHRoaXMuYXJyb3dDbGlja0NhbGxiYWNrKGkuaWQsIGkuaXNDbG9zZWQpKTtcbn0sIEogPSBuZXcgV2Vha1NldCgpLCB5dCA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgY29uc3QgcyA9IFwidHJlZXNlbGVjdC1saXN0X19pdGVtLS1mb2N1c2VkXCI7XG4gIGlmIChlKSB7XG4gICAgY29uc3QgaSA9IEFycmF5LmZyb20odGhpcy5zcmNFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoYC4ke3N9YCkpO1xuICAgIGkubGVuZ3RoICYmIGkuZm9yRWFjaCgoYSkgPT4gYS5jbGFzc0xpc3QucmVtb3ZlKHMpKSwgdC5jbGFzc0xpc3QuYWRkKHMpO1xuICB9IGVsc2VcbiAgICB0LmNsYXNzTGlzdC5yZW1vdmUocyk7XG59LCBaID0gbmV3IFdlYWtTZXQoKSwgeHQgPSBmdW5jdGlvbigpIHtcbiAgY29uc3QgeyB1bmdyb3VwZWROb2RlczogZSwgZ3JvdXBlZE5vZGVzOiB0LCBhbGxOb2RlczogcyB9ID0gQ2kodGhpcy5mbGF0dGVkT3B0aW9ucyk7XG4gIHRoaXMuc2VsZWN0ZWROb2RlcyA9IHsgbm9kZXM6IGUsIGdyb3VwZWROb2RlczogdCwgYWxsTm9kZXM6IHMgfTtcbn0sIEplID0gbmV3IFdlYWtTZXQoKSwgTnMgPSBmdW5jdGlvbigpIHtcbiAgbyh0aGlzLCBaLCB4dCkuY2FsbCh0aGlzKSwgdGhpcy5pbnB1dENhbGxiYWNrKHRoaXMuc2VsZWN0ZWROb2RlcyksIHRoaXMudmFsdWUgPSB0aGlzLnNlbGVjdGVkTm9kZXMubm9kZXMubWFwKChlKSA9PiBlLmlkKTtcbn07XG5jb25zdCBEdCA9ICh7XG4gIHBhcmVudEh0bWxDb250YWluZXI6IGwsXG4gIHN0YXRpY0xpc3Q6IGUsXG4gIGFwcGVuZFRvQm9keTogdCxcbiAgaXNTaW5nbGVTZWxlY3Q6IHMsXG4gIHZhbHVlOiBpLFxuICBkaXJlY3Rpb246IGFcbn0pID0+IHtcbiAgbCB8fCBjb25zb2xlLmVycm9yKFwiVmFsaWRhdGlvbjogcGFyZW50SHRtbENvbnRhaW5lciBwcm9wIGlzIHJlcXVpcmVkIVwiKSwgZSAmJiB0ICYmIGNvbnNvbGUuZXJyb3IoXCJWYWxpZGF0aW9uOiBZb3Ugc2hvdWxkIHNldCBzdGF0aWNMaXN0IHRvIGZhbHNlIGlmIHlvdSB1c2UgYXBwZW5kVG9Cb2R5IVwiKSwgcyAmJiBBcnJheS5pc0FycmF5KGkpICYmIGNvbnNvbGUuZXJyb3IoXCJWYWxpZGF0aW9uOiBpZiB5b3UgdXNlIGlzU2luZ2xlU2VsZWN0IHByb3AsIHlvdSBzaG91bGQgcGFzcyBhIHNpbmdsZSB2YWx1ZSFcIiksICFzICYmICFBcnJheS5pc0FycmF5KGkpICYmIGNvbnNvbGUuZXJyb3IoXCJWYWxpZGF0aW9uOiB5b3Ugc2hvdWxkIHBhc3MgYW4gYXJyYXkgYXMgYSB2YWx1ZSFcIiksIGEgJiYgYSAhPT0gXCJhdXRvXCIgJiYgYSAhPT0gXCJib3R0b21cIiAmJiBhICE9PSBcInRvcFwiICYmIGNvbnNvbGUuZXJyb3IoXCJWYWxpZGF0aW9uOiB5b3Ugc2hvdWxkIHBhc3MgKGF1dG8gfCB0b3AgfCBib3R0b20gfCB1bmRlZmluZWQpIGFzIGEgdmFsdWUgZm9yIHRoZSBkaXJlY3Rpb24gcHJvcCFcIik7XG59LCByZSA9IChsKSA9PiBsLm1hcCgoZSkgPT4gZS5pZCksIFZpID0gKGwpID0+IGwgPyBBcnJheS5pc0FycmF5KGwpID8gbCA6IFtsXSA6IFtdLCBEaSA9IChsLCBlKSA9PiB7XG4gIGlmIChlKSB7XG4gICAgY29uc3QgW3RdID0gbDtcbiAgICByZXR1cm4gdCA/PyBudWxsO1xuICB9XG4gIHJldHVybiBsO1xufTtcbnZhciB1LCBwLCBGLCBRLCBxLCBfLCBBLCBMLCBCLCBlZSwgU3QsIHRlLCBfdCwgWmUsIE9zLCBRZSwgSXMsIGV0LCBQcywgdHQsIEJzLCBzdCwgVnMsIGl0LCBEcywgc2UsIEF0LCBsdCwgSHMsIG50LCBHcywgYXQsIE1zLCBvdCwgRnMsIGllLCBUdCwgcnQsIHFzLCBqLCBoZSwgbGUsIE50LCBSLCBkZSwgY3QsIGpzLCBuZSwgT3QsIGh0LCBScywgZHQsICRzLCB1dCwgV3MsIHB0LCBVcywgbXQsIHpzO1xuY2xhc3MgR2kge1xuICBjb25zdHJ1Y3Rvcih7XG4gICAgcGFyZW50SHRtbENvbnRhaW5lcjogZSxcbiAgICB2YWx1ZTogdCxcbiAgICBvcHRpb25zOiBzLFxuICAgIG9wZW5MZXZlbDogaSxcbiAgICBhcHBlbmRUb0JvZHk6IGEsXG4gICAgYWx3YXlzT3BlbjogaCxcbiAgICBzaG93VGFnczogZCxcbiAgICB0YWdzQ291bnRUZXh0OiBDLFxuICAgIGNsZWFyYWJsZTogZixcbiAgICBzZWFyY2hhYmxlOiBiLFxuICAgIHBsYWNlaG9sZGVyOiBnLFxuICAgIGdyb3VwZWQ6IGssXG4gICAgaXNHcm91cGVkVmFsdWU6IHcsXG4gICAgbGlzdFNsb3RIdG1sQ29tcG9uZW50OiB5LFxuICAgIGRpc2FibGVkOiB4LFxuICAgIGVtcHR5VGV4dDogJCxcbiAgICBzdGF0aWNMaXN0OiBhZSxcbiAgICBpZDogQ3QsXG4gICAgYXJpYUxhYmVsOiBndCxcbiAgICBpc1NpbmdsZVNlbGVjdDogb2UsXG4gICAgc2hvd0NvdW50OiBZcyxcbiAgICBkaXNhYmxlZEJyYW5jaE5vZGU6IEtzLFxuICAgIGRpcmVjdGlvbjogWHMsXG4gICAgZXhwYW5kU2VsZWN0ZWQ6IEpzLFxuICAgIHNhdmVTY3JvbGxQb3NpdGlvbjogWnMsXG4gICAgaXNJbmRlcGVuZGVudE5vZGVzOiBRcyxcbiAgICBydGw6IGVpLFxuICAgIGljb25FbGVtZW50czogdGksXG4gICAgaW5wdXRDYWxsYmFjazogc2ksXG4gICAgb3BlbkNhbGxiYWNrOiBpaSxcbiAgICBjbG9zZUNhbGxiYWNrOiBsaSxcbiAgICBuYW1lQ2hhbmdlQ2FsbGJhY2s6IG5pLFxuICAgIHNlYXJjaENhbGxiYWNrOiBhaSxcbiAgICBvcGVuQ2xvc2VHcm91cENhbGxiYWNrOiBvaVxuICB9KSB7XG4gICAgcih0aGlzLCBlZSk7XG4gICAgcih0aGlzLCB0ZSk7XG4gICAgcih0aGlzLCBaZSk7XG4gICAgcih0aGlzLCBRZSk7XG4gICAgcih0aGlzLCBldCk7XG4gICAgcih0aGlzLCB0dCk7XG4gICAgcih0aGlzLCBzdCk7XG4gICAgcih0aGlzLCBpdCk7XG4gICAgcih0aGlzLCBzZSk7XG4gICAgcih0aGlzLCBsdCk7XG4gICAgcih0aGlzLCBudCk7XG4gICAgcih0aGlzLCBhdCk7XG4gICAgcih0aGlzLCBvdCk7XG4gICAgcih0aGlzLCBpZSk7XG4gICAgcih0aGlzLCBydCk7XG4gICAgcih0aGlzLCBqKTtcbiAgICByKHRoaXMsIGxlKTtcbiAgICByKHRoaXMsIFIpO1xuICAgIHIodGhpcywgY3QpO1xuICAgIC8vIEVtaXRzXG4gICAgcih0aGlzLCBuZSk7XG4gICAgcih0aGlzLCBodCk7XG4gICAgcih0aGlzLCBkdCk7XG4gICAgcih0aGlzLCB1dCk7XG4gICAgcih0aGlzLCBwdCk7XG4gICAgcih0aGlzLCBtdCk7XG4gICAgLy8gUHJvcHNcbiAgICBjKHRoaXMsIFwicGFyZW50SHRtbENvbnRhaW5lclwiKTtcbiAgICBjKHRoaXMsIFwidmFsdWVcIik7XG4gICAgYyh0aGlzLCBcIm9wdGlvbnNcIik7XG4gICAgYyh0aGlzLCBcIm9wZW5MZXZlbFwiKTtcbiAgICBjKHRoaXMsIFwiYXBwZW5kVG9Cb2R5XCIpO1xuICAgIGModGhpcywgXCJhbHdheXNPcGVuXCIpO1xuICAgIGModGhpcywgXCJzaG93VGFnc1wiKTtcbiAgICBjKHRoaXMsIFwidGFnc0NvdW50VGV4dFwiKTtcbiAgICBjKHRoaXMsIFwiY2xlYXJhYmxlXCIpO1xuICAgIGModGhpcywgXCJzZWFyY2hhYmxlXCIpO1xuICAgIGModGhpcywgXCJwbGFjZWhvbGRlclwiKTtcbiAgICBjKHRoaXMsIFwiZ3JvdXBlZFwiKTtcbiAgICBjKHRoaXMsIFwiaXNHcm91cGVkVmFsdWVcIik7XG4gICAgYyh0aGlzLCBcImxpc3RTbG90SHRtbENvbXBvbmVudFwiKTtcbiAgICBjKHRoaXMsIFwiZGlzYWJsZWRcIik7XG4gICAgYyh0aGlzLCBcImVtcHR5VGV4dFwiKTtcbiAgICBjKHRoaXMsIFwic3RhdGljTGlzdFwiKTtcbiAgICBjKHRoaXMsIFwiaWRcIik7XG4gICAgYyh0aGlzLCBcImFyaWFMYWJlbFwiKTtcbiAgICBjKHRoaXMsIFwiaXNTaW5nbGVTZWxlY3RcIik7XG4gICAgYyh0aGlzLCBcInNob3dDb3VudFwiKTtcbiAgICBjKHRoaXMsIFwiZGlzYWJsZWRCcmFuY2hOb2RlXCIpO1xuICAgIGModGhpcywgXCJkaXJlY3Rpb25cIik7XG4gICAgYyh0aGlzLCBcImV4cGFuZFNlbGVjdGVkXCIpO1xuICAgIGModGhpcywgXCJzYXZlU2Nyb2xsUG9zaXRpb25cIik7XG4gICAgYyh0aGlzLCBcImlzSW5kZXBlbmRlbnROb2Rlc1wiKTtcbiAgICBjKHRoaXMsIFwicnRsXCIpO1xuICAgIGModGhpcywgXCJpY29uRWxlbWVudHNcIik7XG4gICAgYyh0aGlzLCBcImlucHV0Q2FsbGJhY2tcIik7XG4gICAgYyh0aGlzLCBcIm9wZW5DYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwiY2xvc2VDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwibmFtZUNoYW5nZUNhbGxiYWNrXCIpO1xuICAgIGModGhpcywgXCJzZWFyY2hDYWxsYmFja1wiKTtcbiAgICBjKHRoaXMsIFwib3BlbkNsb3NlR3JvdXBDYWxsYmFja1wiKTtcbiAgICAvLyBJbm5lclN0YXRlXG4gICAgYyh0aGlzLCBcInVuZ3JvdXBlZFZhbHVlXCIpO1xuICAgIGModGhpcywgXCJncm91cGVkVmFsdWVcIik7XG4gICAgYyh0aGlzLCBcImFsbFZhbHVlXCIpO1xuICAgIGModGhpcywgXCJpc0xpc3RPcGVuZWRcIik7XG4gICAgYyh0aGlzLCBcInNlbGVjdGVkTmFtZVwiKTtcbiAgICBjKHRoaXMsIFwic3JjRWxlbWVudFwiKTtcbiAgICAvLyBDb21wb25lbnRzXG4gICAgcih0aGlzLCB1LCBudWxsKTtcbiAgICByKHRoaXMsIHAsIG51bGwpO1xuICAgIC8vIFJlc2l6ZSBwcm9wc1xuICAgIHIodGhpcywgRiwgbnVsbCk7XG4gICAgLy8gTGlzdCBwb3NpdGlvbiBzY3JvbGxcbiAgICByKHRoaXMsIFEsIDApO1xuICAgIC8vIFRpbWVyIGZvciBzZWFyY2ggdGV4dFxuICAgIHIodGhpcywgcSwgMCk7XG4gICAgLy8gT3V0c2lkZSBsaXN0ZW5lcnNcbiAgICByKHRoaXMsIF8sIG51bGwpO1xuICAgIHIodGhpcywgQSwgbnVsbCk7XG4gICAgcih0aGlzLCBMLCBudWxsKTtcbiAgICByKHRoaXMsIEIsIG51bGwpO1xuICAgIER0KHtcbiAgICAgIHBhcmVudEh0bWxDb250YWluZXI6IGUsXG4gICAgICB2YWx1ZTogdCxcbiAgICAgIHN0YXRpY0xpc3Q6IGFlLFxuICAgICAgYXBwZW5kVG9Cb2R5OiBhLFxuICAgICAgaXNTaW5nbGVTZWxlY3Q6IG9lXG4gICAgfSksIHRoaXMucGFyZW50SHRtbENvbnRhaW5lciA9IGUsIHRoaXMudmFsdWUgPSBbXSwgdGhpcy5vcHRpb25zID0gcyA/PyBbXSwgdGhpcy5vcGVuTGV2ZWwgPSBpID8/IDAsIHRoaXMuYXBwZW5kVG9Cb2R5ID0gYSA/PyAhMSwgdGhpcy5hbHdheXNPcGVuID0gISEoaCAmJiAheCksIHRoaXMuc2hvd1RhZ3MgPSBkID8/ICEwLCB0aGlzLnRhZ3NDb3VudFRleHQgPSBDID8/IFwiZWxlbWVudHMgc2VsZWN0ZWRcIiwgdGhpcy5jbGVhcmFibGUgPSBmID8/ICEwLCB0aGlzLnNlYXJjaGFibGUgPSBiID8/ICEwLCB0aGlzLnBsYWNlaG9sZGVyID0gZyA/PyBcIlNlYXJjaC4uLlwiLCB0aGlzLmdyb3VwZWQgPSBrID8/ICEwLCB0aGlzLmlzR3JvdXBlZFZhbHVlID0gdyA/PyAhMSwgdGhpcy5saXN0U2xvdEh0bWxDb21wb25lbnQgPSB5ID8/IG51bGwsIHRoaXMuZGlzYWJsZWQgPSB4ID8/ICExLCB0aGlzLmVtcHR5VGV4dCA9ICQgPz8gXCJObyByZXN1bHRzIGZvdW5kLi4uXCIsIHRoaXMuc3RhdGljTGlzdCA9ICEhKGFlICYmICF0aGlzLmFwcGVuZFRvQm9keSksIHRoaXMuaWQgPSBDdCA/PyBcIlwiLCB0aGlzLmFyaWFMYWJlbCA9IGd0ID8/IFwiXCIsIHRoaXMuaXNTaW5nbGVTZWxlY3QgPSBvZSA/PyAhMSwgdGhpcy5zaG93Q291bnQgPSBZcyA/PyAhMSwgdGhpcy5kaXNhYmxlZEJyYW5jaE5vZGUgPSBLcyA/PyAhMSwgdGhpcy5kaXJlY3Rpb24gPSBYcyA/PyBcImF1dG9cIiwgdGhpcy5leHBhbmRTZWxlY3RlZCA9IEpzID8/ICExLCB0aGlzLnNhdmVTY3JvbGxQb3NpdGlvbiA9IFpzID8/ICEwLCB0aGlzLmlzSW5kZXBlbmRlbnROb2RlcyA9IFFzID8/ICExLCB0aGlzLnJ0bCA9IGVpID8/ICExLCB0aGlzLmljb25FbGVtZW50cyA9IEJ0KHRpKSwgdGhpcy5pbnB1dENhbGxiYWNrID0gc2ksIHRoaXMub3BlbkNhbGxiYWNrID0gaWksIHRoaXMuY2xvc2VDYWxsYmFjayA9IGxpLCB0aGlzLm5hbWVDaGFuZ2VDYWxsYmFjayA9IG5pLCB0aGlzLnNlYXJjaENhbGxiYWNrID0gYWksIHRoaXMub3BlbkNsb3NlR3JvdXBDYWxsYmFjayA9IG9pLCB0aGlzLnVuZ3JvdXBlZFZhbHVlID0gW10sIHRoaXMuZ3JvdXBlZFZhbHVlID0gW10sIHRoaXMuYWxsVmFsdWUgPSBbXSwgdGhpcy5pc0xpc3RPcGVuZWQgPSAhMSwgdGhpcy5zZWxlY3RlZE5hbWUgPSBcIlwiLCB0aGlzLnNyY0VsZW1lbnQgPSBudWxsLCBvKHRoaXMsIGVlLCBTdCkuY2FsbCh0aGlzLCB0KTtcbiAgfVxuICBtb3VudCgpIHtcbiAgICBEdCh7XG4gICAgICBwYXJlbnRIdG1sQ29udGFpbmVyOiB0aGlzLnBhcmVudEh0bWxDb250YWluZXIsXG4gICAgICB2YWx1ZTogdGhpcy52YWx1ZSxcbiAgICAgIHN0YXRpY0xpc3Q6IHRoaXMuc3RhdGljTGlzdCxcbiAgICAgIGFwcGVuZFRvQm9keTogdGhpcy5hcHBlbmRUb0JvZHksXG4gICAgICBpc1NpbmdsZVNlbGVjdDogdGhpcy5pc1NpbmdsZVNlbGVjdFxuICAgIH0pLCB0aGlzLmljb25FbGVtZW50cyA9IEJ0KHRoaXMuaWNvbkVsZW1lbnRzKSwgbyh0aGlzLCBlZSwgU3QpLmNhbGwodGhpcywgdGhpcy52YWx1ZSk7XG4gIH1cbiAgdXBkYXRlVmFsdWUoZSkge1xuICAgIGNvbnN0IHQgPSBWaShlKSwgcyA9IG4odGhpcywgdSk7XG4gICAgcyAmJiAocy51cGRhdGVWYWx1ZSh0KSwgbyh0aGlzLCBzZSwgQXQpLmNhbGwodGhpcywgcyA9PSBudWxsID8gdm9pZCAwIDogcy5zZWxlY3RlZE5vZGVzKSk7XG4gIH1cbiAgZGVzdHJveSgpIHtcbiAgICB0aGlzLnNyY0VsZW1lbnQgJiYgKG8odGhpcywgaWUsIFR0KS5jYWxsKHRoaXMpLCB0aGlzLnNyY0VsZW1lbnQuaW5uZXJIVE1MID0gXCJcIiwgdGhpcy5zcmNFbGVtZW50ID0gbnVsbCwgbyh0aGlzLCBSLCBkZSkuY2FsbCh0aGlzLCAhMCkpO1xuICB9XG4gIGZvY3VzKCkge1xuICAgIG4odGhpcywgcCkgJiYgbih0aGlzLCBwKS5mb2N1cygpO1xuICB9XG4gIHRvZ2dsZU9wZW5DbG9zZSgpIHtcbiAgICBuKHRoaXMsIHApICYmIChuKHRoaXMsIHApLm9wZW5DbG9zZSgpLCBuKHRoaXMsIHApLmZvY3VzKCkpO1xuICB9XG4gIC8vIE91dHNpZGUgTGlzdGVuZXJzXG4gIHNjcm9sbFdpbmRvd0hhbmRsZXIoKSB7XG4gICAgdGhpcy51cGRhdGVMaXN0UG9zaXRpb24oKTtcbiAgfVxuICBmb2N1c1dpbmRvd0hhbmRsZXIoZSkge1xuICAgIHZhciBzLCBpLCBhO1xuICAgICgocyA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCA/IHZvaWQgMCA6IHMuY29udGFpbnMoZS50YXJnZXQpKSB8fCAoKGkgPSBuKHRoaXMsIHUpKSA9PSBudWxsID8gdm9pZCAwIDogaS5zcmNFbGVtZW50LmNvbnRhaW5zKGUudGFyZ2V0KSkgfHwgKChhID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBhLmJsdXIoKSwgbyh0aGlzLCBSLCBkZSkuY2FsbCh0aGlzLCAhMSksIG8odGhpcywgaiwgaGUpLmNhbGwodGhpcywgITEpKTtcbiAgfVxuICBibHVyV2luZG93SGFuZGxlcigpIHtcbiAgICB2YXIgZTtcbiAgICAoZSA9IG4odGhpcywgcCkpID09IG51bGwgfHwgZS5ibHVyKCksIG8odGhpcywgUiwgZGUpLmNhbGwodGhpcywgITEpLCBvKHRoaXMsIGosIGhlKS5jYWxsKHRoaXMsICExKTtcbiAgfVxuICAvLyBVcGRhdGUgZGlyZWN0aW9uIG9mIHRoZSBsaXN0LiBTdXBwb3J0IGFwcGVuZFRvQm9keSBhbmQgc3RhbmRhcmQgbW9kZSB3aXRoIGFic29sdXRlXG4gIHVwZGF0ZUxpc3RQb3NpdGlvbigpIHtcbiAgICB2YXIgeTtcbiAgICBjb25zdCBlID0gdGhpcy5zcmNFbGVtZW50LCB0ID0gKHkgPSBuKHRoaXMsIHUpKSA9PSBudWxsID8gdm9pZCAwIDogeS5zcmNFbGVtZW50O1xuICAgIGlmICghZSB8fCAhdClcbiAgICAgIHJldHVybjtcbiAgICBjb25zdCB7IGhlaWdodDogcyB9ID0gdC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKSwge1xuICAgICAgeDogaSxcbiAgICAgIHk6IGEsXG4gICAgICBoZWlnaHQ6IGgsXG4gICAgICB3aWR0aDogZFxuICAgIH0gPSBlLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLCBDID0gd2luZG93LmlubmVySGVpZ2h0LCBmID0gYSwgYiA9IEMgLSBhIC0gaDtcbiAgICBsZXQgZyA9IGYgPiBiICYmIGYgPj0gcyAmJiBiIDwgcztcbiAgICBpZiAodGhpcy5kaXJlY3Rpb24gIT09IFwiYXV0b1wiICYmIChnID0gdGhpcy5kaXJlY3Rpb24gPT09IFwidG9wXCIpLCB0aGlzLmFwcGVuZFRvQm9keSkge1xuICAgICAgKHQuc3R5bGUudG9wICE9PSBcIjBweFwiIHx8IHQuc3R5bGUubGVmdCAhPT0gXCIwcHhcIikgJiYgKHQuc3R5bGUudG9wID0gXCIwcHhcIiwgdC5zdHlsZS5sZWZ0ID0gXCIwcHhcIik7XG4gICAgICBjb25zdCB4ID0gaSArIHdpbmRvdy5zY3JvbGxYLCAkID0gZyA/IGEgKyB3aW5kb3cuc2Nyb2xsWSAtIHMgOiBhICsgd2luZG93LnNjcm9sbFkgKyBoO1xuICAgICAgdC5zdHlsZS50cmFuc2Zvcm0gPSBgdHJhbnNsYXRlKCR7eH1weCwkeyR9cHgpYCwgdC5zdHlsZS53aWR0aCA9IGAke2R9cHhgO1xuICAgIH1cbiAgICBjb25zdCBrID0gZyA/IFwidG9wXCIgOiBcImJvdHRvbVwiO1xuICAgIHQuZ2V0QXR0cmlidXRlKFwiZGlyZWN0aW9uXCIpICE9PSBrICYmICh0LnNldEF0dHJpYnV0ZShcImRpcmVjdGlvblwiLCBrKSwgbyh0aGlzLCBydCwgcXMpLmNhbGwodGhpcywgZywgdGhpcy5hcHBlbmRUb0JvZHkpKTtcbiAgfVxufVxudSA9IG5ldyBXZWFrTWFwKCksIHAgPSBuZXcgV2Vha01hcCgpLCBGID0gbmV3IFdlYWtNYXAoKSwgUSA9IG5ldyBXZWFrTWFwKCksIHEgPSBuZXcgV2Vha01hcCgpLCBfID0gbmV3IFdlYWtNYXAoKSwgQSA9IG5ldyBXZWFrTWFwKCksIEwgPSBuZXcgV2Vha01hcCgpLCBCID0gbmV3IFdlYWtNYXAoKSwgZWUgPSBuZXcgV2Vha1NldCgpLCBTdCA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIGE7XG4gIHRoaXMuZGVzdHJveSgpO1xuICBjb25zdCB7IGNvbnRhaW5lcjogdCwgbGlzdDogcywgaW5wdXQ6IGkgfSA9IG8odGhpcywgWmUsIE9zKS5jYWxsKHRoaXMpO1xuICB0aGlzLnNyY0VsZW1lbnQgPSB0LCBtKHRoaXMsIHUsIHMpLCBtKHRoaXMsIHAsIGkpLCBtKHRoaXMsIF8sIHRoaXMuc2Nyb2xsV2luZG93SGFuZGxlci5iaW5kKHRoaXMpKSwgbSh0aGlzLCBBLCB0aGlzLnNjcm9sbFdpbmRvd0hhbmRsZXIuYmluZCh0aGlzKSksIG0odGhpcywgTCwgdGhpcy5mb2N1c1dpbmRvd0hhbmRsZXIuYmluZCh0aGlzKSksIG0odGhpcywgQiwgdGhpcy5ibHVyV2luZG93SGFuZGxlci5iaW5kKHRoaXMpKSwgdGhpcy5hbHdheXNPcGVuICYmICgoYSA9IG4odGhpcywgcCkpID09IG51bGwgfHwgYS5vcGVuQ2xvc2UoKSksIHRoaXMuZGlzYWJsZWQgPyB0aGlzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtLWRpc2FibGVkXCIpIDogdGhpcy5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LS1kaXNhYmxlZFwiKSwgdGhpcy51cGRhdGVWYWx1ZShlID8/IHRoaXMudmFsdWUpO1xufSwgdGUgPSBuZXcgV2Vha1NldCgpLCBfdCA9IGZ1bmN0aW9uKHtcbiAgZ3JvdXBlZE5vZGVzOiBlLFxuICBub2RlczogdCxcbiAgYWxsTm9kZXM6IHNcbn0pIHtcbiAgdGhpcy51bmdyb3VwZWRWYWx1ZSA9IHQgPyByZSh0KSA6IFtdLCB0aGlzLmdyb3VwZWRWYWx1ZSA9IGUgPyByZShlKSA6IFtdLCB0aGlzLmFsbFZhbHVlID0gcyA/IHJlKHMpIDogW107XG4gIGxldCBpID0gW107XG4gIHRoaXMuaXNJbmRlcGVuZGVudE5vZGVzIHx8IHRoaXMuaXNTaW5nbGVTZWxlY3QgPyBpID0gdGhpcy5hbGxWYWx1ZSA6IHRoaXMuaXNHcm91cGVkVmFsdWUgPyBpID0gdGhpcy5ncm91cGVkVmFsdWUgOiBpID0gdGhpcy51bmdyb3VwZWRWYWx1ZSwgdGhpcy52YWx1ZSA9IERpKGksIHRoaXMuaXNTaW5nbGVTZWxlY3QpO1xufSwgWmUgPSBuZXcgV2Vha1NldCgpLCBPcyA9IGZ1bmN0aW9uKCkge1xuICBjb25zdCBlID0gdGhpcy5wYXJlbnRIdG1sQ29udGFpbmVyO1xuICBlLmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0XCIpLCB0aGlzLnJ0bCAmJiBlLnNldEF0dHJpYnV0ZShcImRpclwiLCBcInJ0bFwiKTtcbiAgY29uc3QgdCA9IG5ldyBCaSh7XG4gICAgdmFsdWU6IFtdLFxuICAgIC8vIHVwZGF0ZVZhbHVlIG1ldGhvZCBjYWxscyBpbiBpbml0TW91bnQgbWV0aG9kIHRvIHNldCBhY3R1YWwgdmFsdWVcbiAgICBvcHRpb25zOiB0aGlzLm9wdGlvbnMsXG4gICAgb3BlbkxldmVsOiB0aGlzLm9wZW5MZXZlbCxcbiAgICBsaXN0U2xvdEh0bWxDb21wb25lbnQ6IHRoaXMubGlzdFNsb3RIdG1sQ29tcG9uZW50LFxuICAgIGVtcHR5VGV4dDogdGhpcy5lbXB0eVRleHQsXG4gICAgaXNTaW5nbGVTZWxlY3Q6IHRoaXMuaXNTaW5nbGVTZWxlY3QsXG4gICAgc2hvd0NvdW50OiB0aGlzLnNob3dDb3VudCxcbiAgICBkaXNhYmxlZEJyYW5jaE5vZGU6IHRoaXMuZGlzYWJsZWRCcmFuY2hOb2RlLFxuICAgIGV4cGFuZFNlbGVjdGVkOiB0aGlzLmV4cGFuZFNlbGVjdGVkLFxuICAgIGlzSW5kZXBlbmRlbnROb2RlczogdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMsXG4gICAgcnRsOiB0aGlzLnJ0bCxcbiAgICBpY29uRWxlbWVudHM6IHRoaXMuaWNvbkVsZW1lbnRzLFxuICAgIGlucHV0Q2FsbGJhY2s6IChpKSA9PiBvKHRoaXMsIGx0LCBIcykuY2FsbCh0aGlzLCBpKSxcbiAgICBhcnJvd0NsaWNrQ2FsbGJhY2s6IChpLCBhKSA9PiBvKHRoaXMsIG50LCBHcykuY2FsbCh0aGlzLCBpLCBhKSxcbiAgICBtb3VzZXVwQ2FsbGJhY2s6ICgpID0+IHtcbiAgICAgIHZhciBpO1xuICAgICAgcmV0dXJuIChpID0gbih0aGlzLCBwKSkgPT0gbnVsbCA/IHZvaWQgMCA6IGkuZm9jdXMoKTtcbiAgICB9XG4gIH0pLCBzID0gbmV3IGRpKHtcbiAgICB2YWx1ZTogW10sXG4gICAgLy8gdXBkYXRlVmFsdWUgbWV0aG9kIGNhbGxzIGluIGluaXRNb3VudCBtZXRob2QgdG8gc2V0IGFjdHVhbCB2YWx1ZVxuICAgIHNob3dUYWdzOiB0aGlzLnNob3dUYWdzLFxuICAgIHRhZ3NDb3VudFRleHQ6IHRoaXMudGFnc0NvdW50VGV4dCxcbiAgICBjbGVhcmFibGU6IHRoaXMuY2xlYXJhYmxlLFxuICAgIGlzQWx3YXlzT3BlbmVkOiB0aGlzLmFsd2F5c09wZW4sXG4gICAgc2VhcmNoYWJsZTogdGhpcy5zZWFyY2hhYmxlLFxuICAgIHBsYWNlaG9sZGVyOiB0aGlzLnBsYWNlaG9sZGVyLFxuICAgIGRpc2FibGVkOiB0aGlzLmRpc2FibGVkLFxuICAgIGlzU2luZ2xlU2VsZWN0OiB0aGlzLmlzU2luZ2xlU2VsZWN0LFxuICAgIGlkOiB0aGlzLmlkLFxuICAgIGFyaWFMYWJlbDogdGhpcy5hcmlhTGFiZWwsXG4gICAgaWNvbkVsZW1lbnRzOiB0aGlzLmljb25FbGVtZW50cyxcbiAgICBpbnB1dENhbGxiYWNrOiAoaSkgPT4gbyh0aGlzLCBRZSwgSXMpLmNhbGwodGhpcywgaSksXG4gICAgc2VhcmNoQ2FsbGJhY2s6IChpKSA9PiBvKHRoaXMsIHR0LCBCcykuY2FsbCh0aGlzLCBpKSxcbiAgICBvcGVuQ2FsbGJhY2s6ICgpID0+IG8odGhpcywgb3QsIEZzKS5jYWxsKHRoaXMpLFxuICAgIGNsb3NlQ2FsbGJhY2s6ICgpID0+IG8odGhpcywgaWUsIFR0KS5jYWxsKHRoaXMpLFxuICAgIGtleWRvd25DYWxsYmFjazogKGkpID0+IG8odGhpcywgZXQsIFBzKS5jYWxsKHRoaXMsIGkpLFxuICAgIGZvY3VzQ2FsbGJhY2s6ICgpID0+IG8odGhpcywgc3QsIFZzKS5jYWxsKHRoaXMpLFxuICAgIGJsdXJDYWxsYmFjazogKCkgPT4gbyh0aGlzLCBpdCwgRHMpLmNhbGwodGhpcyksXG4gICAgbmFtZUNoYW5nZUNhbGxiYWNrOiAoaSkgPT4gbyh0aGlzLCBhdCwgTXMpLmNhbGwodGhpcywgaSlcbiAgfSk7XG4gIHJldHVybiB0aGlzLmFwcGVuZFRvQm9keSAmJiBtKHRoaXMsIEYsIG5ldyBSZXNpemVPYnNlcnZlcigoKSA9PiB0aGlzLnVwZGF0ZUxpc3RQb3NpdGlvbigpKSksIGUuYXBwZW5kKHMuc3JjRWxlbWVudCksIHsgY29udGFpbmVyOiBlLCBsaXN0OiB0LCBpbnB1dDogcyB9O1xufSwgUWUgPSBuZXcgV2Vha1NldCgpLCBJcyA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIGksIGE7XG4gIGNvbnN0IHQgPSByZShlKTtcbiAgKGkgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IGkudXBkYXRlVmFsdWUodCk7XG4gIGNvbnN0IHMgPSAoKGEgPSBuKHRoaXMsIHUpKSA9PSBudWxsID8gdm9pZCAwIDogYS5zZWxlY3RlZE5vZGVzKSA/PyB7fTtcbiAgbyh0aGlzLCB0ZSwgX3QpLmNhbGwodGhpcywgcyksIG8odGhpcywgbmUsIE90KS5jYWxsKHRoaXMpO1xufSwgZXQgPSBuZXcgV2Vha1NldCgpLCBQcyA9IGZ1bmN0aW9uKGUpIHtcbiAgdmFyIHQ7XG4gIHRoaXMuaXNMaXN0T3BlbmVkICYmICgodCA9IG4odGhpcywgdSkpID09IG51bGwgfHwgdC5jYWxsS2V5QWN0aW9uKGUpKTtcbn0sIHR0ID0gbmV3IFdlYWtTZXQoKSwgQnMgPSBmdW5jdGlvbihlKSB7XG4gIG4odGhpcywgcSkgJiYgY2xlYXJUaW1lb3V0KG4odGhpcywgcSkpLCBtKHRoaXMsIHEsIHdpbmRvdy5zZXRUaW1lb3V0KCgpID0+IHtcbiAgICB2YXIgdDtcbiAgICAodCA9IG4odGhpcywgdSkpID09IG51bGwgfHwgdC51cGRhdGVTZWFyY2hWYWx1ZShlKSwgdGhpcy51cGRhdGVMaXN0UG9zaXRpb24oKTtcbiAgfSwgMzUwKSksIG8odGhpcywgcHQsIFVzKS5jYWxsKHRoaXMsIGUpO1xufSwgc3QgPSBuZXcgV2Vha1NldCgpLCBWcyA9IGZ1bmN0aW9uKCkge1xuICBvKHRoaXMsIGosIGhlKS5jYWxsKHRoaXMsICEwKSwgbih0aGlzLCBMKSAmJiBuKHRoaXMsIEwpICYmIG4odGhpcywgQikgJiYgKGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgbih0aGlzLCBMKSwgITApLCBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiZm9jdXNcIiwgbih0aGlzLCBMKSwgITApLCB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcihcImJsdXJcIiwgbih0aGlzLCBCKSkpO1xufSwgaXQgPSBuZXcgV2Vha1NldCgpLCBEcyA9IGZ1bmN0aW9uKCkge1xuICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICB2YXIgcywgaTtcbiAgICBjb25zdCBlID0gKHMgPSBuKHRoaXMsIHApKSA9PSBudWxsID8gdm9pZCAwIDogcy5zcmNFbGVtZW50LmNvbnRhaW5zKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQpLCB0ID0gKGkgPSBuKHRoaXMsIHUpKSA9PSBudWxsID8gdm9pZCAwIDogaS5zcmNFbGVtZW50LmNvbnRhaW5zKGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQpO1xuICAgICFlICYmICF0ICYmIHRoaXMuYmx1cldpbmRvd0hhbmRsZXIoKTtcbiAgfSwgMSk7XG59LCBzZSA9IG5ldyBXZWFrU2V0KCksIEF0ID0gZnVuY3Rpb24oZSkge1xuICB2YXIgcztcbiAgaWYgKCFlKVxuICAgIHJldHVybjtcbiAgbGV0IHQgPSBbXTtcbiAgdGhpcy5pc0luZGVwZW5kZW50Tm9kZXMgfHwgdGhpcy5pc1NpbmdsZVNlbGVjdCA/IHQgPSBlLmFsbE5vZGVzIDogdGhpcy5ncm91cGVkID8gdCA9IGUuZ3JvdXBlZE5vZGVzIDogdCA9IGUubm9kZXMsIChzID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBzLnVwZGF0ZVZhbHVlKHQpLCBvKHRoaXMsIHRlLCBfdCkuY2FsbCh0aGlzLCBlKTtcbn0sIGx0ID0gbmV3IFdlYWtTZXQoKSwgSHMgPSBmdW5jdGlvbihlKSB7XG4gIHZhciB0LCBzLCBpO1xuICBvKHRoaXMsIHNlLCBBdCkuY2FsbCh0aGlzLCBlKSwgdGhpcy5pc1NpbmdsZVNlbGVjdCAmJiAhdGhpcy5hbHdheXNPcGVuICYmICgodCA9IG4odGhpcywgcCkpID09IG51bGwgfHwgdC5vcGVuQ2xvc2UoKSwgKHMgPSBuKHRoaXMsIHApKSA9PSBudWxsIHx8IHMuY2xlYXJTZWFyY2goKSksIChpID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBpLmZvY3VzKCksIG8odGhpcywgbmUsIE90KS5jYWxsKHRoaXMpO1xufSwgbnQgPSBuZXcgV2Vha1NldCgpLCBHcyA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgdmFyIHM7XG4gIChzID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBzLmZvY3VzKCksIHRoaXMudXBkYXRlTGlzdFBvc2l0aW9uKCksIG8odGhpcywgbXQsIHpzKS5jYWxsKHRoaXMsIGUsIHQpO1xufSwgYXQgPSBuZXcgV2Vha1NldCgpLCBNcyA9IGZ1bmN0aW9uKGUpIHtcbiAgdGhpcy5zZWxlY3RlZE5hbWUgIT09IGUgJiYgKHRoaXMuc2VsZWN0ZWROYW1lID0gZSwgbyh0aGlzLCBodCwgUnMpLmNhbGwodGhpcykpO1xufSwgb3QgPSBuZXcgV2Vha1NldCgpLCBGcyA9IGZ1bmN0aW9uKCkge1xuICB2YXIgZTtcbiAgdGhpcy5pc0xpc3RPcGVuZWQgPSAhMCwgbih0aGlzLCBfKSAmJiBuKHRoaXMsIEEpICYmICh3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcihcInNjcm9sbFwiLCBuKHRoaXMsIF8pLCAhMCksIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFwicmVzaXplXCIsIG4odGhpcywgQSkpKSwgISghbih0aGlzLCB1KSB8fCAhdGhpcy5zcmNFbGVtZW50KSAmJiAodGhpcy5hcHBlbmRUb0JvZHkgPyAoZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChuKHRoaXMsIHUpLnNyY0VsZW1lbnQpLCAoZSA9IG4odGhpcywgRikpID09IG51bGwgfHwgZS5vYnNlcnZlKHRoaXMuc3JjRWxlbWVudCkpIDogdGhpcy5zcmNFbGVtZW50LmFwcGVuZENoaWxkKG4odGhpcywgdSkuc3JjRWxlbWVudCksIHRoaXMudXBkYXRlTGlzdFBvc2l0aW9uKCksIG8odGhpcywgbGUsIE50KS5jYWxsKHRoaXMsICEwKSwgbyh0aGlzLCBjdCwganMpLmNhbGwodGhpcyksIG8odGhpcywgZHQsICRzKS5jYWxsKHRoaXMpKTtcbn0sIGllID0gbmV3IFdlYWtTZXQoKSwgVHQgPSBmdW5jdGlvbigpIHtcbiAgdmFyIHQ7XG4gIHRoaXMuYWx3YXlzT3BlbiB8fCAodGhpcy5pc0xpc3RPcGVuZWQgPSAhMSwgbih0aGlzLCBfKSAmJiBuKHRoaXMsIEEpICYmICh3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInNjcm9sbFwiLCBuKHRoaXMsIF8pLCAhMCksIHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyKFwicmVzaXplXCIsIG4odGhpcywgQSkpKSwgIW4odGhpcywgdSkgfHwgIXRoaXMuc3JjRWxlbWVudCkgfHwgISh0aGlzLmFwcGVuZFRvQm9keSA/IGRvY3VtZW50LmJvZHkuY29udGFpbnMobih0aGlzLCB1KS5zcmNFbGVtZW50KSA6IHRoaXMuc3JjRWxlbWVudC5jb250YWlucyhuKHRoaXMsIHUpLnNyY0VsZW1lbnQpKSB8fCAobSh0aGlzLCBRLCBuKHRoaXMsIHUpLnNyY0VsZW1lbnQuc2Nyb2xsVG9wKSwgdGhpcy5hcHBlbmRUb0JvZHkgPyAoZG9jdW1lbnQuYm9keS5yZW1vdmVDaGlsZChuKHRoaXMsIHUpLnNyY0VsZW1lbnQpLCAodCA9IG4odGhpcywgRikpID09IG51bGwgfHwgdC5kaXNjb25uZWN0KCkpIDogdGhpcy5zcmNFbGVtZW50LnJlbW92ZUNoaWxkKG4odGhpcywgdSkuc3JjRWxlbWVudCksIG8odGhpcywgbGUsIE50KS5jYWxsKHRoaXMsICExKSwgbyh0aGlzLCB1dCwgV3MpLmNhbGwodGhpcykpO1xufSwgcnQgPSBuZXcgV2Vha1NldCgpLCBxcyA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgaWYgKCFuKHRoaXMsIHUpIHx8ICFuKHRoaXMsIHApKVxuICAgIHJldHVybjtcbiAgY29uc3QgcyA9IHQgPyBcInRyZWVzZWxlY3QtbGlzdC0tdG9wLXRvLWJvZHlcIiA6IFwidHJlZXNlbGVjdC1saXN0LS10b3BcIiwgaSA9IHQgPyBcInRyZWVzZWxlY3QtbGlzdC0tYm90dG9tLXRvLWJvZHlcIiA6IFwidHJlZXNlbGVjdC1saXN0LS1ib3R0b21cIjtcbiAgZSA/IChuKHRoaXMsIHUpLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChzKSwgbih0aGlzLCB1KS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoaSksIG4odGhpcywgcCkuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1pbnB1dC0tdG9wXCIpLCBuKHRoaXMsIHApLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLWJvdHRvbVwiKSkgOiAobih0aGlzLCB1KS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUocyksIG4odGhpcywgdSkuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKGkpLCBuKHRoaXMsIHApLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLXRvcFwiKSwgbih0aGlzLCBwKS5zcmNFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJ0cmVlc2VsZWN0LWlucHV0LS1ib3R0b21cIikpO1xufSwgaiA9IG5ldyBXZWFrU2V0KCksIGhlID0gZnVuY3Rpb24oZSkge1xuICAhbih0aGlzLCBwKSB8fCAhbih0aGlzLCB1KSB8fCAoZSA/IChuKHRoaXMsIHApLnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXQtLWZvY3VzZWRcIiksIG4odGhpcywgdSkuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0LS1mb2N1c2VkXCIpKSA6IChuKHRoaXMsIHApLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLWZvY3VzZWRcIiksIG4odGhpcywgdSkuc3JjRWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwidHJlZXNlbGVjdC1saXN0LS1mb2N1c2VkXCIpKSk7XG59LCBsZSA9IG5ldyBXZWFrU2V0KCksIE50ID0gZnVuY3Rpb24oZSkge1xuICB2YXIgdCwgcywgaSwgYTtcbiAgZSA/ICh0ID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCB0LnNyY0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcInRyZWVzZWxlY3QtaW5wdXQtLW9wZW5lZFwiKSA6IChzID0gbih0aGlzLCBwKSkgPT0gbnVsbCB8fCBzLnNyY0VsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcInRyZWVzZWxlY3QtaW5wdXQtLW9wZW5lZFwiKSwgdGhpcy5zdGF0aWNMaXN0ID8gKGkgPSBuKHRoaXMsIHUpKSA9PSBudWxsIHx8IGkuc3JjRWxlbWVudC5jbGFzc0xpc3QuYWRkKFwidHJlZXNlbGVjdC1saXN0LS1zdGF0aWNcIikgOiAoYSA9IG4odGhpcywgdSkpID09IG51bGwgfHwgYS5zcmNFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmVlc2VsZWN0LWxpc3QtLXN0YXRpY1wiKTtcbn0sIFIgPSBuZXcgV2Vha1NldCgpLCBkZSA9IGZ1bmN0aW9uKGUpIHtcbiAgIW4odGhpcywgXykgfHwgIW4odGhpcywgQSkgfHwgIW4odGhpcywgTCkgfHwgIW4odGhpcywgQikgfHwgKCghdGhpcy5hbHdheXNPcGVuIHx8IGUpICYmICh3aW5kb3cucmVtb3ZlRXZlbnRMaXN0ZW5lcihcInNjcm9sbFwiLCBuKHRoaXMsIF8pLCAhMCksIHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyKFwicmVzaXplXCIsIG4odGhpcywgQSkpKSwgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcIm1vdXNlZG93blwiLCBuKHRoaXMsIEwpLCAhMCksIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJmb2N1c1wiLCBuKHRoaXMsIEwpLCAhMCksIHdpbmRvdy5yZW1vdmVFdmVudExpc3RlbmVyKFwiYmx1clwiLCBuKHRoaXMsIEIpKSk7XG59LCBjdCA9IG5ldyBXZWFrU2V0KCksIGpzID0gZnVuY3Rpb24oKSB7XG4gIHZhciB0LCBzLCBpO1xuICBjb25zdCBlID0gKHQgPSBuKHRoaXMsIHUpKSA9PSBudWxsID8gdm9pZCAwIDogdC5pc0xhc3RGb2N1c2VkRWxlbWVudEV4aXN0KCk7XG4gIHRoaXMuc2F2ZVNjcm9sbFBvc2l0aW9uICYmIGUgPyAocyA9IG4odGhpcywgdSkpID09IG51bGwgfHwgcy5zcmNFbGVtZW50LnNjcm9sbCgwLCBuKHRoaXMsIFEpKSA6IChpID0gbih0aGlzLCB1KSkgPT0gbnVsbCB8fCBpLmZvY3VzRmlyc3RMaXN0RWxlbWVudCgpO1xufSwgbmUgPSBuZXcgV2Vha1NldCgpLCBPdCA9IGZ1bmN0aW9uKCkge1xuICB2YXIgZTtcbiAgKGUgPSB0aGlzLnNyY0VsZW1lbnQpID09IG51bGwgfHwgZS5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChcImlucHV0XCIsIHsgZGV0YWlsOiB0aGlzLnZhbHVlIH0pKSwgdGhpcy5pbnB1dENhbGxiYWNrICYmIHRoaXMuaW5wdXRDYWxsYmFjayh0aGlzLnZhbHVlKTtcbn0sIGh0ID0gbmV3IFdlYWtTZXQoKSwgUnMgPSBmdW5jdGlvbigpIHtcbiAgdmFyIGU7XG4gIChlID0gdGhpcy5zcmNFbGVtZW50KSA9PSBudWxsIHx8IGUuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoXCJuYW1lLWNoYW5nZVwiLCB7IGRldGFpbDogdGhpcy5zZWxlY3RlZE5hbWUgfSkpLCB0aGlzLm5hbWVDaGFuZ2VDYWxsYmFjayAmJiB0aGlzLm5hbWVDaGFuZ2VDYWxsYmFjayh0aGlzLnNlbGVjdGVkTmFtZSk7XG59LCBkdCA9IG5ldyBXZWFrU2V0KCksICRzID0gZnVuY3Rpb24oKSB7XG4gIHZhciBlO1xuICB0aGlzLmFsd2F5c09wZW4gfHwgKChlID0gdGhpcy5zcmNFbGVtZW50KSA9PSBudWxsIHx8IGUuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoXCJvcGVuXCIsIHsgZGV0YWlsOiB0aGlzLnZhbHVlIH0pKSwgdGhpcy5vcGVuQ2FsbGJhY2sgJiYgdGhpcy5vcGVuQ2FsbGJhY2sodGhpcy52YWx1ZSkpO1xufSwgdXQgPSBuZXcgV2Vha1NldCgpLCBXcyA9IGZ1bmN0aW9uKCkge1xuICB2YXIgZTtcbiAgdGhpcy5hbHdheXNPcGVuIHx8ICgoZSA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCB8fCBlLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFwiY2xvc2VcIiwgeyBkZXRhaWw6IHRoaXMudmFsdWUgfSkpLCB0aGlzLmNsb3NlQ2FsbGJhY2sgJiYgdGhpcy5jbG9zZUNhbGxiYWNrKHRoaXMudmFsdWUpKTtcbn0sIHB0ID0gbmV3IFdlYWtTZXQoKSwgVXMgPSBmdW5jdGlvbihlKSB7XG4gIHZhciBzO1xuICBjb25zdCB0ID0gKGUgPT0gbnVsbCA/IHZvaWQgMCA6IGUudHJpbSgpKSA/PyBcIlwiO1xuICAocyA9IHRoaXMuc3JjRWxlbWVudCkgPT0gbnVsbCB8fCBzLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KFwic2VhcmNoXCIsIHsgZGV0YWlsOiB0IH0pKSwgdGhpcy5zZWFyY2hDYWxsYmFjayAmJiB0aGlzLnNlYXJjaENhbGxiYWNrKHQpO1xufSwgbXQgPSBuZXcgV2Vha1NldCgpLCB6cyA9IGZ1bmN0aW9uKGUsIHQpIHtcbiAgdmFyIHM7XG4gIChzID0gdGhpcy5zcmNFbGVtZW50KSA9PSBudWxsIHx8IHMuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoXCJvcGVuLWNsb3NlLWdyb3VwXCIsIHsgZGV0YWlsOiB7IGdyb3VwSWQ6IGUsIGlzQ2xvc2VkOiB0IH0gfSkpLCB0aGlzLm9wZW5DbG9zZUdyb3VwQ2FsbGJhY2sgJiYgdGhpcy5vcGVuQ2xvc2VHcm91cENhbGxiYWNrKGUsIHQpO1xufTtcbmV4cG9ydCB7XG4gIEdpIGFzIGRlZmF1bHRcbn07XG4iLCAiaW1wb3J0IFRyZWVzZWxlY3QgZnJvbSAndHJlZXNlbGVjdGpzJ1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBzZWxlY3RUcmVlKHtcbiAgICBzdGF0ZSxcbiAgICBuYW1lLFxuICAgIG9wdGlvbnMsXG4gICAgc2VhcmNoYWJsZSxcbiAgICBzaG93Q291bnQsXG4gICAgcGxhY2Vob2xkZXIsXG4gICAgcnRsLFxuICAgIGRpc2FibGVkQnJhbmNoTm9kZSA9IHRydWUsXG4gICAgZGlzYWJsZWQgPSBmYWxzZSxcbiAgICBpc1NpbmdsZVNlbGVjdCA9IHRydWUsXG4gICAgc2hvd1RhZ3MgPSB0cnVlLFxuICAgIGNsZWFyYWJsZSA9IHRydWUsXG4gICAgaXNJbmRlcGVuZGVudE5vZGVzID0gdHJ1ZSxcbiAgICBhbHdheXNPcGVuID0gZmFsc2UsXG4gICAgZW1wdHlUZXh0LFxuICAgIGV4cGFuZFNlbGVjdGVkID0gdHJ1ZSxcbiAgICBncm91cGVkID0gdHJ1ZSxcbiAgICBvcGVuTGV2ZWwgPSAwLFxuICAgIGRpcmVjdGlvbiA9ICdhdXRvJyxcbn0pIHtcbiAgICByZXR1cm4ge1xuICAgICAgICBzdGF0ZSxcblxuICAgICAgICAvKiogQHR5cGUgVHJlZXNlbGVjdCAqL1xuICAgICAgICB0cmVlOiBudWxsLFxuXG4gICAgICAgIGluaXQoKSB7XG4gICAgICAgICAgICB0aGlzLnRyZWUgPSBuZXcgVHJlZXNlbGVjdCh7XG4gICAgICAgICAgICAgICAgaWQ6IGB0cmVlLSR7bmFtZX0taWRgLFxuICAgICAgICAgICAgICAgIGFyaWFMYWJlbDogYHRyZWUtJHtuYW1lfS1sYWJlbGAsXG4gICAgICAgICAgICAgICAgcGFyZW50SHRtbENvbnRhaW5lcjogdGhpcy4kcmVmcy50cmVlLFxuICAgICAgICAgICAgICAgIHZhbHVlOiB0aGlzLnN0YXRlID8/IFtdLFxuICAgICAgICAgICAgICAgIG9wdGlvbnMsXG4gICAgICAgICAgICAgICAgc2VhcmNoYWJsZSxcbiAgICAgICAgICAgICAgICBzaG93Q291bnQsXG4gICAgICAgICAgICAgICAgcGxhY2Vob2xkZXIsXG4gICAgICAgICAgICAgICAgZGlzYWJsZWRCcmFuY2hOb2RlLFxuICAgICAgICAgICAgICAgIGRpc2FibGVkLFxuICAgICAgICAgICAgICAgIGlzU2luZ2xlU2VsZWN0LFxuICAgICAgICAgICAgICAgIHNob3dUYWdzLFxuICAgICAgICAgICAgICAgIGNsZWFyYWJsZSxcbiAgICAgICAgICAgICAgICBpc0luZGVwZW5kZW50Tm9kZXMsXG4gICAgICAgICAgICAgICAgYWx3YXlzT3BlbixcbiAgICAgICAgICAgICAgICBlbXB0eVRleHQsXG4gICAgICAgICAgICAgICAgZXhwYW5kU2VsZWN0ZWQsXG4gICAgICAgICAgICAgICAgZ3JvdXBlZCxcbiAgICAgICAgICAgICAgICBvcGVuTGV2ZWwsXG4gICAgICAgICAgICAgICAgZGlyZWN0aW9uLFxuICAgICAgICAgICAgICAgIHJ0bCxcbiAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgIHRoaXMudHJlZS5zcmNFbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2lucHV0JywgKGUpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLnN0YXRlID0gZS5kZXRhaWxcbiAgICAgICAgICAgIH0pXG4gICAgICAgIH0sXG4gICAgfVxufVxuIiwgIi8qKiFcbiAqIFNvcnRhYmxlIDEuMTUuMlxuICogQGF1dGhvclx0UnViYVhhICAgPHRyYXNoQHJ1YmF4YS5vcmc+XG4gKiBAYXV0aG9yXHRvd2VubSAgICA8b3dlbjIzMzU1QGdtYWlsLmNvbT5cbiAqIEBsaWNlbnNlIE1JVFxuICovXG5mdW5jdGlvbiBvd25LZXlzKG9iamVjdCwgZW51bWVyYWJsZU9ubHkpIHtcbiAgdmFyIGtleXMgPSBPYmplY3Qua2V5cyhvYmplY3QpO1xuICBpZiAoT2JqZWN0LmdldE93blByb3BlcnR5U3ltYm9scykge1xuICAgIHZhciBzeW1ib2xzID0gT2JqZWN0LmdldE93blByb3BlcnR5U3ltYm9scyhvYmplY3QpO1xuICAgIGlmIChlbnVtZXJhYmxlT25seSkge1xuICAgICAgc3ltYm9scyA9IHN5bWJvbHMuZmlsdGVyKGZ1bmN0aW9uIChzeW0pIHtcbiAgICAgICAgcmV0dXJuIE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Iob2JqZWN0LCBzeW0pLmVudW1lcmFibGU7XG4gICAgICB9KTtcbiAgICB9XG4gICAga2V5cy5wdXNoLmFwcGx5KGtleXMsIHN5bWJvbHMpO1xuICB9XG4gIHJldHVybiBrZXlzO1xufVxuZnVuY3Rpb24gX29iamVjdFNwcmVhZDIodGFyZ2V0KSB7XG4gIGZvciAodmFyIGkgPSAxOyBpIDwgYXJndW1lbnRzLmxlbmd0aDsgaSsrKSB7XG4gICAgdmFyIHNvdXJjZSA9IGFyZ3VtZW50c1tpXSAhPSBudWxsID8gYXJndW1lbnRzW2ldIDoge307XG4gICAgaWYgKGkgJSAyKSB7XG4gICAgICBvd25LZXlzKE9iamVjdChzb3VyY2UpLCB0cnVlKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcbiAgICAgICAgX2RlZmluZVByb3BlcnR5KHRhcmdldCwga2V5LCBzb3VyY2Vba2V5XSk7XG4gICAgICB9KTtcbiAgICB9IGVsc2UgaWYgKE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3JzKSB7XG4gICAgICBPYmplY3QuZGVmaW5lUHJvcGVydGllcyh0YXJnZXQsIE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3JzKHNvdXJjZSkpO1xuICAgIH0gZWxzZSB7XG4gICAgICBvd25LZXlzKE9iamVjdChzb3VyY2UpKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcbiAgICAgICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KHRhcmdldCwga2V5LCBPYmplY3QuZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKHNvdXJjZSwga2V5KSk7XG4gICAgICB9KTtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIHRhcmdldDtcbn1cbmZ1bmN0aW9uIF90eXBlb2Yob2JqKSB7XG4gIFwiQGJhYmVsL2hlbHBlcnMgLSB0eXBlb2ZcIjtcblxuICBpZiAodHlwZW9mIFN5bWJvbCA9PT0gXCJmdW5jdGlvblwiICYmIHR5cGVvZiBTeW1ib2wuaXRlcmF0b3IgPT09IFwic3ltYm9sXCIpIHtcbiAgICBfdHlwZW9mID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgcmV0dXJuIHR5cGVvZiBvYmo7XG4gICAgfTtcbiAgfSBlbHNlIHtcbiAgICBfdHlwZW9mID0gZnVuY3Rpb24gKG9iaikge1xuICAgICAgcmV0dXJuIG9iaiAmJiB0eXBlb2YgU3ltYm9sID09PSBcImZ1bmN0aW9uXCIgJiYgb2JqLmNvbnN0cnVjdG9yID09PSBTeW1ib2wgJiYgb2JqICE9PSBTeW1ib2wucHJvdG90eXBlID8gXCJzeW1ib2xcIiA6IHR5cGVvZiBvYmo7XG4gICAgfTtcbiAgfVxuICByZXR1cm4gX3R5cGVvZihvYmopO1xufVxuZnVuY3Rpb24gX2RlZmluZVByb3BlcnR5KG9iaiwga2V5LCB2YWx1ZSkge1xuICBpZiAoa2V5IGluIG9iaikge1xuICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShvYmosIGtleSwge1xuICAgICAgdmFsdWU6IHZhbHVlLFxuICAgICAgZW51bWVyYWJsZTogdHJ1ZSxcbiAgICAgIGNvbmZpZ3VyYWJsZTogdHJ1ZSxcbiAgICAgIHdyaXRhYmxlOiB0cnVlXG4gICAgfSk7XG4gIH0gZWxzZSB7XG4gICAgb2JqW2tleV0gPSB2YWx1ZTtcbiAgfVxuICByZXR1cm4gb2JqO1xufVxuZnVuY3Rpb24gX2V4dGVuZHMoKSB7XG4gIF9leHRlbmRzID0gT2JqZWN0LmFzc2lnbiB8fCBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgZm9yICh2YXIgaSA9IDE7IGkgPCBhcmd1bWVudHMubGVuZ3RoOyBpKyspIHtcbiAgICAgIHZhciBzb3VyY2UgPSBhcmd1bWVudHNbaV07XG4gICAgICBmb3IgKHZhciBrZXkgaW4gc291cmNlKSB7XG4gICAgICAgIGlmIChPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwoc291cmNlLCBrZXkpKSB7XG4gICAgICAgICAgdGFyZ2V0W2tleV0gPSBzb3VyY2Vba2V5XTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cbiAgICByZXR1cm4gdGFyZ2V0O1xuICB9O1xuICByZXR1cm4gX2V4dGVuZHMuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbn1cbmZ1bmN0aW9uIF9vYmplY3RXaXRob3V0UHJvcGVydGllc0xvb3NlKHNvdXJjZSwgZXhjbHVkZWQpIHtcbiAgaWYgKHNvdXJjZSA9PSBudWxsKSByZXR1cm4ge307XG4gIHZhciB0YXJnZXQgPSB7fTtcbiAgdmFyIHNvdXJjZUtleXMgPSBPYmplY3Qua2V5cyhzb3VyY2UpO1xuICB2YXIga2V5LCBpO1xuICBmb3IgKGkgPSAwOyBpIDwgc291cmNlS2V5cy5sZW5ndGg7IGkrKykge1xuICAgIGtleSA9IHNvdXJjZUtleXNbaV07XG4gICAgaWYgKGV4Y2x1ZGVkLmluZGV4T2Yoa2V5KSA+PSAwKSBjb250aW51ZTtcbiAgICB0YXJnZXRba2V5XSA9IHNvdXJjZVtrZXldO1xuICB9XG4gIHJldHVybiB0YXJnZXQ7XG59XG5mdW5jdGlvbiBfb2JqZWN0V2l0aG91dFByb3BlcnRpZXMoc291cmNlLCBleGNsdWRlZCkge1xuICBpZiAoc291cmNlID09IG51bGwpIHJldHVybiB7fTtcbiAgdmFyIHRhcmdldCA9IF9vYmplY3RXaXRob3V0UHJvcGVydGllc0xvb3NlKHNvdXJjZSwgZXhjbHVkZWQpO1xuICB2YXIga2V5LCBpO1xuICBpZiAoT2JqZWN0LmdldE93blByb3BlcnR5U3ltYm9scykge1xuICAgIHZhciBzb3VyY2VTeW1ib2xLZXlzID0gT2JqZWN0LmdldE93blByb3BlcnR5U3ltYm9scyhzb3VyY2UpO1xuICAgIGZvciAoaSA9IDA7IGkgPCBzb3VyY2VTeW1ib2xLZXlzLmxlbmd0aDsgaSsrKSB7XG4gICAgICBrZXkgPSBzb3VyY2VTeW1ib2xLZXlzW2ldO1xuICAgICAgaWYgKGV4Y2x1ZGVkLmluZGV4T2Yoa2V5KSA+PSAwKSBjb250aW51ZTtcbiAgICAgIGlmICghT2JqZWN0LnByb3RvdHlwZS5wcm9wZXJ0eUlzRW51bWVyYWJsZS5jYWxsKHNvdXJjZSwga2V5KSkgY29udGludWU7XG4gICAgICB0YXJnZXRba2V5XSA9IHNvdXJjZVtrZXldO1xuICAgIH1cbiAgfVxuICByZXR1cm4gdGFyZ2V0O1xufVxuZnVuY3Rpb24gX3RvQ29uc3VtYWJsZUFycmF5KGFycikge1xuICByZXR1cm4gX2FycmF5V2l0aG91dEhvbGVzKGFycikgfHwgX2l0ZXJhYmxlVG9BcnJheShhcnIpIHx8IF91bnN1cHBvcnRlZEl0ZXJhYmxlVG9BcnJheShhcnIpIHx8IF9ub25JdGVyYWJsZVNwcmVhZCgpO1xufVxuZnVuY3Rpb24gX2FycmF5V2l0aG91dEhvbGVzKGFycikge1xuICBpZiAoQXJyYXkuaXNBcnJheShhcnIpKSByZXR1cm4gX2FycmF5TGlrZVRvQXJyYXkoYXJyKTtcbn1cbmZ1bmN0aW9uIF9pdGVyYWJsZVRvQXJyYXkoaXRlcikge1xuICBpZiAodHlwZW9mIFN5bWJvbCAhPT0gXCJ1bmRlZmluZWRcIiAmJiBpdGVyW1N5bWJvbC5pdGVyYXRvcl0gIT0gbnVsbCB8fCBpdGVyW1wiQEBpdGVyYXRvclwiXSAhPSBudWxsKSByZXR1cm4gQXJyYXkuZnJvbShpdGVyKTtcbn1cbmZ1bmN0aW9uIF91bnN1cHBvcnRlZEl0ZXJhYmxlVG9BcnJheShvLCBtaW5MZW4pIHtcbiAgaWYgKCFvKSByZXR1cm47XG4gIGlmICh0eXBlb2YgbyA9PT0gXCJzdHJpbmdcIikgcmV0dXJuIF9hcnJheUxpa2VUb0FycmF5KG8sIG1pbkxlbik7XG4gIHZhciBuID0gT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKG8pLnNsaWNlKDgsIC0xKTtcbiAgaWYgKG4gPT09IFwiT2JqZWN0XCIgJiYgby5jb25zdHJ1Y3RvcikgbiA9IG8uY29uc3RydWN0b3IubmFtZTtcbiAgaWYgKG4gPT09IFwiTWFwXCIgfHwgbiA9PT0gXCJTZXRcIikgcmV0dXJuIEFycmF5LmZyb20obyk7XG4gIGlmIChuID09PSBcIkFyZ3VtZW50c1wiIHx8IC9eKD86VWl8SSludCg/Ojh8MTZ8MzIpKD86Q2xhbXBlZCk/QXJyYXkkLy50ZXN0KG4pKSByZXR1cm4gX2FycmF5TGlrZVRvQXJyYXkobywgbWluTGVuKTtcbn1cbmZ1bmN0aW9uIF9hcnJheUxpa2VUb0FycmF5KGFyciwgbGVuKSB7XG4gIGlmIChsZW4gPT0gbnVsbCB8fCBsZW4gPiBhcnIubGVuZ3RoKSBsZW4gPSBhcnIubGVuZ3RoO1xuICBmb3IgKHZhciBpID0gMCwgYXJyMiA9IG5ldyBBcnJheShsZW4pOyBpIDwgbGVuOyBpKyspIGFycjJbaV0gPSBhcnJbaV07XG4gIHJldHVybiBhcnIyO1xufVxuZnVuY3Rpb24gX25vbkl0ZXJhYmxlU3ByZWFkKCkge1xuICB0aHJvdyBuZXcgVHlwZUVycm9yKFwiSW52YWxpZCBhdHRlbXB0IHRvIHNwcmVhZCBub24taXRlcmFibGUgaW5zdGFuY2UuXFxuSW4gb3JkZXIgdG8gYmUgaXRlcmFibGUsIG5vbi1hcnJheSBvYmplY3RzIG11c3QgaGF2ZSBhIFtTeW1ib2wuaXRlcmF0b3JdKCkgbWV0aG9kLlwiKTtcbn1cblxudmFyIHZlcnNpb24gPSBcIjEuMTUuMlwiO1xuXG5mdW5jdGlvbiB1c2VyQWdlbnQocGF0dGVybikge1xuICBpZiAodHlwZW9mIHdpbmRvdyAhPT0gJ3VuZGVmaW5lZCcgJiYgd2luZG93Lm5hdmlnYXRvcikge1xuICAgIHJldHVybiAhISAvKkBfX1BVUkVfXyovbmF2aWdhdG9yLnVzZXJBZ2VudC5tYXRjaChwYXR0ZXJuKTtcbiAgfVxufVxudmFyIElFMTFPckxlc3MgPSB1c2VyQWdlbnQoLyg/OlRyaWRlbnQuKnJ2WyA6XT8xMVxcLnxtc2llfGllbW9iaWxlfFdpbmRvd3MgUGhvbmUpL2kpO1xudmFyIEVkZ2UgPSB1c2VyQWdlbnQoL0VkZ2UvaSk7XG52YXIgRmlyZUZveCA9IHVzZXJBZ2VudCgvZmlyZWZveC9pKTtcbnZhciBTYWZhcmkgPSB1c2VyQWdlbnQoL3NhZmFyaS9pKSAmJiAhdXNlckFnZW50KC9jaHJvbWUvaSkgJiYgIXVzZXJBZ2VudCgvYW5kcm9pZC9pKTtcbnZhciBJT1MgPSB1c2VyQWdlbnQoL2lQKGFkfG9kfGhvbmUpL2kpO1xudmFyIENocm9tZUZvckFuZHJvaWQgPSB1c2VyQWdlbnQoL2Nocm9tZS9pKSAmJiB1c2VyQWdlbnQoL2FuZHJvaWQvaSk7XG5cbnZhciBjYXB0dXJlTW9kZSA9IHtcbiAgY2FwdHVyZTogZmFsc2UsXG4gIHBhc3NpdmU6IGZhbHNlXG59O1xuZnVuY3Rpb24gb24oZWwsIGV2ZW50LCBmbikge1xuICBlbC5hZGRFdmVudExpc3RlbmVyKGV2ZW50LCBmbiwgIUlFMTFPckxlc3MgJiYgY2FwdHVyZU1vZGUpO1xufVxuZnVuY3Rpb24gb2ZmKGVsLCBldmVudCwgZm4pIHtcbiAgZWwucmVtb3ZlRXZlbnRMaXN0ZW5lcihldmVudCwgZm4sICFJRTExT3JMZXNzICYmIGNhcHR1cmVNb2RlKTtcbn1cbmZ1bmN0aW9uIG1hdGNoZXMoIC8qKkhUTUxFbGVtZW50Ki9lbCwgLyoqU3RyaW5nKi9zZWxlY3Rvcikge1xuICBpZiAoIXNlbGVjdG9yKSByZXR1cm47XG4gIHNlbGVjdG9yWzBdID09PSAnPicgJiYgKHNlbGVjdG9yID0gc2VsZWN0b3Iuc3Vic3RyaW5nKDEpKTtcbiAgaWYgKGVsKSB7XG4gICAgdHJ5IHtcbiAgICAgIGlmIChlbC5tYXRjaGVzKSB7XG4gICAgICAgIHJldHVybiBlbC5tYXRjaGVzKHNlbGVjdG9yKTtcbiAgICAgIH0gZWxzZSBpZiAoZWwubXNNYXRjaGVzU2VsZWN0b3IpIHtcbiAgICAgICAgcmV0dXJuIGVsLm1zTWF0Y2hlc1NlbGVjdG9yKHNlbGVjdG9yKTtcbiAgICAgIH0gZWxzZSBpZiAoZWwud2Via2l0TWF0Y2hlc1NlbGVjdG9yKSB7XG4gICAgICAgIHJldHVybiBlbC53ZWJraXRNYXRjaGVzU2VsZWN0b3Ioc2VsZWN0b3IpO1xuICAgICAgfVxuICAgIH0gY2F0Y2ggKF8pIHtcbiAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIGZhbHNlO1xufVxuZnVuY3Rpb24gZ2V0UGFyZW50T3JIb3N0KGVsKSB7XG4gIHJldHVybiBlbC5ob3N0ICYmIGVsICE9PSBkb2N1bWVudCAmJiBlbC5ob3N0Lm5vZGVUeXBlID8gZWwuaG9zdCA6IGVsLnBhcmVudE5vZGU7XG59XG5mdW5jdGlvbiBjbG9zZXN0KCAvKipIVE1MRWxlbWVudCovZWwsIC8qKlN0cmluZyovc2VsZWN0b3IsIC8qKkhUTUxFbGVtZW50Ki9jdHgsIGluY2x1ZGVDVFgpIHtcbiAgaWYgKGVsKSB7XG4gICAgY3R4ID0gY3R4IHx8IGRvY3VtZW50O1xuICAgIGRvIHtcbiAgICAgIGlmIChzZWxlY3RvciAhPSBudWxsICYmIChzZWxlY3RvclswXSA9PT0gJz4nID8gZWwucGFyZW50Tm9kZSA9PT0gY3R4ICYmIG1hdGNoZXMoZWwsIHNlbGVjdG9yKSA6IG1hdGNoZXMoZWwsIHNlbGVjdG9yKSkgfHwgaW5jbHVkZUNUWCAmJiBlbCA9PT0gY3R4KSB7XG4gICAgICAgIHJldHVybiBlbDtcbiAgICAgIH1cbiAgICAgIGlmIChlbCA9PT0gY3R4KSBicmVhaztcbiAgICAgIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgICB9IHdoaWxlIChlbCA9IGdldFBhcmVudE9ySG9zdChlbCkpO1xuICB9XG4gIHJldHVybiBudWxsO1xufVxudmFyIFJfU1BBQ0UgPSAvXFxzKy9nO1xuZnVuY3Rpb24gdG9nZ2xlQ2xhc3MoZWwsIG5hbWUsIHN0YXRlKSB7XG4gIGlmIChlbCAmJiBuYW1lKSB7XG4gICAgaWYgKGVsLmNsYXNzTGlzdCkge1xuICAgICAgZWwuY2xhc3NMaXN0W3N0YXRlID8gJ2FkZCcgOiAncmVtb3ZlJ10obmFtZSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHZhciBjbGFzc05hbWUgPSAoJyAnICsgZWwuY2xhc3NOYW1lICsgJyAnKS5yZXBsYWNlKFJfU1BBQ0UsICcgJykucmVwbGFjZSgnICcgKyBuYW1lICsgJyAnLCAnICcpO1xuICAgICAgZWwuY2xhc3NOYW1lID0gKGNsYXNzTmFtZSArIChzdGF0ZSA/ICcgJyArIG5hbWUgOiAnJykpLnJlcGxhY2UoUl9TUEFDRSwgJyAnKTtcbiAgICB9XG4gIH1cbn1cbmZ1bmN0aW9uIGNzcyhlbCwgcHJvcCwgdmFsKSB7XG4gIHZhciBzdHlsZSA9IGVsICYmIGVsLnN0eWxlO1xuICBpZiAoc3R5bGUpIHtcbiAgICBpZiAodmFsID09PSB2b2lkIDApIHtcbiAgICAgIGlmIChkb2N1bWVudC5kZWZhdWx0VmlldyAmJiBkb2N1bWVudC5kZWZhdWx0Vmlldy5nZXRDb21wdXRlZFN0eWxlKSB7XG4gICAgICAgIHZhbCA9IGRvY3VtZW50LmRlZmF1bHRWaWV3LmdldENvbXB1dGVkU3R5bGUoZWwsICcnKTtcbiAgICAgIH0gZWxzZSBpZiAoZWwuY3VycmVudFN0eWxlKSB7XG4gICAgICAgIHZhbCA9IGVsLmN1cnJlbnRTdHlsZTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBwcm9wID09PSB2b2lkIDAgPyB2YWwgOiB2YWxbcHJvcF07XG4gICAgfSBlbHNlIHtcbiAgICAgIGlmICghKHByb3AgaW4gc3R5bGUpICYmIHByb3AuaW5kZXhPZignd2Via2l0JykgPT09IC0xKSB7XG4gICAgICAgIHByb3AgPSAnLXdlYmtpdC0nICsgcHJvcDtcbiAgICAgIH1cbiAgICAgIHN0eWxlW3Byb3BdID0gdmFsICsgKHR5cGVvZiB2YWwgPT09ICdzdHJpbmcnID8gJycgOiAncHgnKTtcbiAgICB9XG4gIH1cbn1cbmZ1bmN0aW9uIG1hdHJpeChlbCwgc2VsZk9ubHkpIHtcbiAgdmFyIGFwcGxpZWRUcmFuc2Zvcm1zID0gJyc7XG4gIGlmICh0eXBlb2YgZWwgPT09ICdzdHJpbmcnKSB7XG4gICAgYXBwbGllZFRyYW5zZm9ybXMgPSBlbDtcbiAgfSBlbHNlIHtcbiAgICBkbyB7XG4gICAgICB2YXIgdHJhbnNmb3JtID0gY3NzKGVsLCAndHJhbnNmb3JtJyk7XG4gICAgICBpZiAodHJhbnNmb3JtICYmIHRyYW5zZm9ybSAhPT0gJ25vbmUnKSB7XG4gICAgICAgIGFwcGxpZWRUcmFuc2Zvcm1zID0gdHJhbnNmb3JtICsgJyAnICsgYXBwbGllZFRyYW5zZm9ybXM7XG4gICAgICB9XG4gICAgICAvKiBqc2hpbnQgYm9zczp0cnVlICovXG4gICAgfSB3aGlsZSAoIXNlbGZPbmx5ICYmIChlbCA9IGVsLnBhcmVudE5vZGUpKTtcbiAgfVxuICB2YXIgbWF0cml4Rm4gPSB3aW5kb3cuRE9NTWF0cml4IHx8IHdpbmRvdy5XZWJLaXRDU1NNYXRyaXggfHwgd2luZG93LkNTU01hdHJpeCB8fCB3aW5kb3cuTVNDU1NNYXRyaXg7XG4gIC8qanNoaW50IC1XMDU2ICovXG4gIHJldHVybiBtYXRyaXhGbiAmJiBuZXcgbWF0cml4Rm4oYXBwbGllZFRyYW5zZm9ybXMpO1xufVxuZnVuY3Rpb24gZmluZChjdHgsIHRhZ05hbWUsIGl0ZXJhdG9yKSB7XG4gIGlmIChjdHgpIHtcbiAgICB2YXIgbGlzdCA9IGN0eC5nZXRFbGVtZW50c0J5VGFnTmFtZSh0YWdOYW1lKSxcbiAgICAgIGkgPSAwLFxuICAgICAgbiA9IGxpc3QubGVuZ3RoO1xuICAgIGlmIChpdGVyYXRvcikge1xuICAgICAgZm9yICg7IGkgPCBuOyBpKyspIHtcbiAgICAgICAgaXRlcmF0b3IobGlzdFtpXSwgaSk7XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBsaXN0O1xuICB9XG4gIHJldHVybiBbXTtcbn1cbmZ1bmN0aW9uIGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKSB7XG4gIHZhciBzY3JvbGxpbmdFbGVtZW50ID0gZG9jdW1lbnQuc2Nyb2xsaW5nRWxlbWVudDtcbiAgaWYgKHNjcm9sbGluZ0VsZW1lbnQpIHtcbiAgICByZXR1cm4gc2Nyb2xsaW5nRWxlbWVudDtcbiAgfSBlbHNlIHtcbiAgICByZXR1cm4gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50O1xuICB9XG59XG5cbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBcImJvdW5kaW5nIGNsaWVudCByZWN0XCIgb2YgZ2l2ZW4gZWxlbWVudFxyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWwgICAgICAgICAgICAgICAgICAgICAgIFRoZSBlbGVtZW50IHdob3NlIGJvdW5kaW5nQ2xpZW50UmVjdCBpcyB3YW50ZWRcclxuICogQHBhcmFtICB7W0Jvb2xlYW5dfSByZWxhdGl2ZVRvQ29udGFpbmluZ0Jsb2NrICBXaGV0aGVyIHRoZSByZWN0IHNob3VsZCBiZSByZWxhdGl2ZSB0byB0aGUgY29udGFpbmluZyBibG9jayBvZiAoaW5jbHVkaW5nKSB0aGUgY29udGFpbmVyXHJcbiAqIEBwYXJhbSAge1tCb29sZWFuXX0gcmVsYXRpdmVUb05vblN0YXRpY1BhcmVudCAgV2hldGhlciB0aGUgcmVjdCBzaG91bGQgYmUgcmVsYXRpdmUgdG8gdGhlIHJlbGF0aXZlIHBhcmVudCBvZiAoaW5jbHVkaW5nKSB0aGUgY29udGFpZW5yXHJcbiAqIEBwYXJhbSAge1tCb29sZWFuXX0gdW5kb1NjYWxlICAgICAgICAgICAgICAgICAgV2hldGhlciB0aGUgY29udGFpbmVyJ3Mgc2NhbGUoKSBzaG91bGQgYmUgdW5kb25lXHJcbiAqIEBwYXJhbSAge1tIVE1MRWxlbWVudF19IGNvbnRhaW5lciAgICAgICAgICAgICAgVGhlIHBhcmVudCB0aGUgZWxlbWVudCB3aWxsIGJlIHBsYWNlZCBpblxyXG4gKiBAcmV0dXJuIHtPYmplY3R9ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFRoZSBib3VuZGluZ0NsaWVudFJlY3Qgb2YgZWwsIHdpdGggc3BlY2lmaWVkIGFkanVzdG1lbnRzXHJcbiAqL1xuZnVuY3Rpb24gZ2V0UmVjdChlbCwgcmVsYXRpdmVUb0NvbnRhaW5pbmdCbG9jaywgcmVsYXRpdmVUb05vblN0YXRpY1BhcmVudCwgdW5kb1NjYWxlLCBjb250YWluZXIpIHtcbiAgaWYgKCFlbC5nZXRCb3VuZGluZ0NsaWVudFJlY3QgJiYgZWwgIT09IHdpbmRvdykgcmV0dXJuO1xuICB2YXIgZWxSZWN0LCB0b3AsIGxlZnQsIGJvdHRvbSwgcmlnaHQsIGhlaWdodCwgd2lkdGg7XG4gIGlmIChlbCAhPT0gd2luZG93ICYmIGVsLnBhcmVudE5vZGUgJiYgZWwgIT09IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKSkge1xuICAgIGVsUmVjdCA9IGVsLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuICAgIHRvcCA9IGVsUmVjdC50b3A7XG4gICAgbGVmdCA9IGVsUmVjdC5sZWZ0O1xuICAgIGJvdHRvbSA9IGVsUmVjdC5ib3R0b207XG4gICAgcmlnaHQgPSBlbFJlY3QucmlnaHQ7XG4gICAgaGVpZ2h0ID0gZWxSZWN0LmhlaWdodDtcbiAgICB3aWR0aCA9IGVsUmVjdC53aWR0aDtcbiAgfSBlbHNlIHtcbiAgICB0b3AgPSAwO1xuICAgIGxlZnQgPSAwO1xuICAgIGJvdHRvbSA9IHdpbmRvdy5pbm5lckhlaWdodDtcbiAgICByaWdodCA9IHdpbmRvdy5pbm5lcldpZHRoO1xuICAgIGhlaWdodCA9IHdpbmRvdy5pbm5lckhlaWdodDtcbiAgICB3aWR0aCA9IHdpbmRvdy5pbm5lcldpZHRoO1xuICB9XG4gIGlmICgocmVsYXRpdmVUb0NvbnRhaW5pbmdCbG9jayB8fCByZWxhdGl2ZVRvTm9uU3RhdGljUGFyZW50KSAmJiBlbCAhPT0gd2luZG93KSB7XG4gICAgLy8gQWRqdXN0IGZvciB0cmFuc2xhdGUoKVxuICAgIGNvbnRhaW5lciA9IGNvbnRhaW5lciB8fCBlbC5wYXJlbnROb2RlO1xuXG4gICAgLy8gc29sdmVzICMxMTIzIChzZWU6IGh0dHBzOi8vc3RhY2tvdmVyZmxvdy5jb20vYS8zNzk1MzgwNi82MDg4MzEyKVxuICAgIC8vIE5vdCBuZWVkZWQgb24gPD0gSUUxMVxuICAgIGlmICghSUUxMU9yTGVzcykge1xuICAgICAgZG8ge1xuICAgICAgICBpZiAoY29udGFpbmVyICYmIGNvbnRhaW5lci5nZXRCb3VuZGluZ0NsaWVudFJlY3QgJiYgKGNzcyhjb250YWluZXIsICd0cmFuc2Zvcm0nKSAhPT0gJ25vbmUnIHx8IHJlbGF0aXZlVG9Ob25TdGF0aWNQYXJlbnQgJiYgY3NzKGNvbnRhaW5lciwgJ3Bvc2l0aW9uJykgIT09ICdzdGF0aWMnKSkge1xuICAgICAgICAgIHZhciBjb250YWluZXJSZWN0ID0gY29udGFpbmVyLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuXG4gICAgICAgICAgLy8gU2V0IHJlbGF0aXZlIHRvIGVkZ2VzIG9mIHBhZGRpbmcgYm94IG9mIGNvbnRhaW5lclxuICAgICAgICAgIHRvcCAtPSBjb250YWluZXJSZWN0LnRvcCArIHBhcnNlSW50KGNzcyhjb250YWluZXIsICdib3JkZXItdG9wLXdpZHRoJykpO1xuICAgICAgICAgIGxlZnQgLT0gY29udGFpbmVyUmVjdC5sZWZ0ICsgcGFyc2VJbnQoY3NzKGNvbnRhaW5lciwgJ2JvcmRlci1sZWZ0LXdpZHRoJykpO1xuICAgICAgICAgIGJvdHRvbSA9IHRvcCArIGVsUmVjdC5oZWlnaHQ7XG4gICAgICAgICAgcmlnaHQgPSBsZWZ0ICsgZWxSZWN0LndpZHRoO1xuICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICAgIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgICAgIH0gd2hpbGUgKGNvbnRhaW5lciA9IGNvbnRhaW5lci5wYXJlbnROb2RlKTtcbiAgICB9XG4gIH1cbiAgaWYgKHVuZG9TY2FsZSAmJiBlbCAhPT0gd2luZG93KSB7XG4gICAgLy8gQWRqdXN0IGZvciBzY2FsZSgpXG4gICAgdmFyIGVsTWF0cml4ID0gbWF0cml4KGNvbnRhaW5lciB8fCBlbCksXG4gICAgICBzY2FsZVggPSBlbE1hdHJpeCAmJiBlbE1hdHJpeC5hLFxuICAgICAgc2NhbGVZID0gZWxNYXRyaXggJiYgZWxNYXRyaXguZDtcbiAgICBpZiAoZWxNYXRyaXgpIHtcbiAgICAgIHRvcCAvPSBzY2FsZVk7XG4gICAgICBsZWZ0IC89IHNjYWxlWDtcbiAgICAgIHdpZHRoIC89IHNjYWxlWDtcbiAgICAgIGhlaWdodCAvPSBzY2FsZVk7XG4gICAgICBib3R0b20gPSB0b3AgKyBoZWlnaHQ7XG4gICAgICByaWdodCA9IGxlZnQgKyB3aWR0aDtcbiAgICB9XG4gIH1cbiAgcmV0dXJuIHtcbiAgICB0b3A6IHRvcCxcbiAgICBsZWZ0OiBsZWZ0LFxuICAgIGJvdHRvbTogYm90dG9tLFxuICAgIHJpZ2h0OiByaWdodCxcbiAgICB3aWR0aDogd2lkdGgsXG4gICAgaGVpZ2h0OiBoZWlnaHRcbiAgfTtcbn1cblxuLyoqXHJcbiAqIENoZWNrcyBpZiBhIHNpZGUgb2YgYW4gZWxlbWVudCBpcyBzY3JvbGxlZCBwYXN0IGEgc2lkZSBvZiBpdHMgcGFyZW50c1xyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gIGVsICAgICAgICAgICBUaGUgZWxlbWVudCB3aG8ncyBzaWRlIGJlaW5nIHNjcm9sbGVkIG91dCBvZiB2aWV3IGlzIGluIHF1ZXN0aW9uXHJcbiAqIEBwYXJhbSAge1N0cmluZ30gICAgICAgZWxTaWRlICAgICAgIFNpZGUgb2YgdGhlIGVsZW1lbnQgaW4gcXVlc3Rpb24gKCd0b3AnLCAnbGVmdCcsICdyaWdodCcsICdib3R0b20nKVxyXG4gKiBAcGFyYW0gIHtTdHJpbmd9ICAgICAgIHBhcmVudFNpZGUgICBTaWRlIG9mIHRoZSBwYXJlbnQgaW4gcXVlc3Rpb24gKCd0b3AnLCAnbGVmdCcsICdyaWdodCcsICdib3R0b20nKVxyXG4gKiBAcmV0dXJuIHtIVE1MRWxlbWVudH0gICAgICAgICAgICAgICBUaGUgcGFyZW50IHNjcm9sbCBlbGVtZW50IHRoYXQgdGhlIGVsJ3Mgc2lkZSBpcyBzY3JvbGxlZCBwYXN0LCBvciBudWxsIGlmIHRoZXJlIGlzIG5vIHN1Y2ggZWxlbWVudFxyXG4gKi9cbmZ1bmN0aW9uIGlzU2Nyb2xsZWRQYXN0KGVsLCBlbFNpZGUsIHBhcmVudFNpZGUpIHtcbiAgdmFyIHBhcmVudCA9IGdldFBhcmVudEF1dG9TY3JvbGxFbGVtZW50KGVsLCB0cnVlKSxcbiAgICBlbFNpZGVWYWwgPSBnZXRSZWN0KGVsKVtlbFNpZGVdO1xuXG4gIC8qIGpzaGludCBib3NzOnRydWUgKi9cbiAgd2hpbGUgKHBhcmVudCkge1xuICAgIHZhciBwYXJlbnRTaWRlVmFsID0gZ2V0UmVjdChwYXJlbnQpW3BhcmVudFNpZGVdLFxuICAgICAgdmlzaWJsZSA9IHZvaWQgMDtcbiAgICBpZiAocGFyZW50U2lkZSA9PT0gJ3RvcCcgfHwgcGFyZW50U2lkZSA9PT0gJ2xlZnQnKSB7XG4gICAgICB2aXNpYmxlID0gZWxTaWRlVmFsID49IHBhcmVudFNpZGVWYWw7XG4gICAgfSBlbHNlIHtcbiAgICAgIHZpc2libGUgPSBlbFNpZGVWYWwgPD0gcGFyZW50U2lkZVZhbDtcbiAgICB9XG4gICAgaWYgKCF2aXNpYmxlKSByZXR1cm4gcGFyZW50O1xuICAgIGlmIChwYXJlbnQgPT09IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKSkgYnJlYWs7XG4gICAgcGFyZW50ID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQocGFyZW50LCBmYWxzZSk7XG4gIH1cbiAgcmV0dXJuIGZhbHNlO1xufVxuXG4vKipcclxuICogR2V0cyBudGggY2hpbGQgb2YgZWwsIGlnbm9yaW5nIGhpZGRlbiBjaGlsZHJlbiwgc29ydGFibGUncyBlbGVtZW50cyAoZG9lcyBub3QgaWdub3JlIGNsb25lIGlmIGl0J3MgdmlzaWJsZSlcclxuICogYW5kIG5vbi1kcmFnZ2FibGUgZWxlbWVudHNcclxuICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IGVsICAgICAgIFRoZSBwYXJlbnQgZWxlbWVudFxyXG4gKiBAcGFyYW0gIHtOdW1iZXJ9IGNoaWxkTnVtICAgICAgVGhlIGluZGV4IG9mIHRoZSBjaGlsZFxyXG4gKiBAcGFyYW0gIHtPYmplY3R9IG9wdGlvbnMgICAgICAgUGFyZW50IFNvcnRhYmxlJ3Mgb3B0aW9uc1xyXG4gKiBAcmV0dXJuIHtIVE1MRWxlbWVudH0gICAgICAgICAgVGhlIGNoaWxkIGF0IGluZGV4IGNoaWxkTnVtLCBvciBudWxsIGlmIG5vdCBmb3VuZFxyXG4gKi9cbmZ1bmN0aW9uIGdldENoaWxkKGVsLCBjaGlsZE51bSwgb3B0aW9ucywgaW5jbHVkZURyYWdFbCkge1xuICB2YXIgY3VycmVudENoaWxkID0gMCxcbiAgICBpID0gMCxcbiAgICBjaGlsZHJlbiA9IGVsLmNoaWxkcmVuO1xuICB3aGlsZSAoaSA8IGNoaWxkcmVuLmxlbmd0aCkge1xuICAgIGlmIChjaGlsZHJlbltpXS5zdHlsZS5kaXNwbGF5ICE9PSAnbm9uZScgJiYgY2hpbGRyZW5baV0gIT09IFNvcnRhYmxlLmdob3N0ICYmIChpbmNsdWRlRHJhZ0VsIHx8IGNoaWxkcmVuW2ldICE9PSBTb3J0YWJsZS5kcmFnZ2VkKSAmJiBjbG9zZXN0KGNoaWxkcmVuW2ldLCBvcHRpb25zLmRyYWdnYWJsZSwgZWwsIGZhbHNlKSkge1xuICAgICAgaWYgKGN1cnJlbnRDaGlsZCA9PT0gY2hpbGROdW0pIHtcbiAgICAgICAgcmV0dXJuIGNoaWxkcmVuW2ldO1xuICAgICAgfVxuICAgICAgY3VycmVudENoaWxkKys7XG4gICAgfVxuICAgIGkrKztcbiAgfVxuICByZXR1cm4gbnVsbDtcbn1cblxuLyoqXHJcbiAqIEdldHMgdGhlIGxhc3QgY2hpbGQgaW4gdGhlIGVsLCBpZ25vcmluZyBnaG9zdEVsIG9yIGludmlzaWJsZSBlbGVtZW50cyAoY2xvbmVzKVxyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWwgICAgICAgUGFyZW50IGVsZW1lbnRcclxuICogQHBhcmFtICB7c2VsZWN0b3J9IHNlbGVjdG9yICAgIEFueSBvdGhlciBlbGVtZW50cyB0aGF0IHNob3VsZCBiZSBpZ25vcmVkXHJcbiAqIEByZXR1cm4ge0hUTUxFbGVtZW50fSAgICAgICAgICBUaGUgbGFzdCBjaGlsZCwgaWdub3JpbmcgZ2hvc3RFbFxyXG4gKi9cbmZ1bmN0aW9uIGxhc3RDaGlsZChlbCwgc2VsZWN0b3IpIHtcbiAgdmFyIGxhc3QgPSBlbC5sYXN0RWxlbWVudENoaWxkO1xuICB3aGlsZSAobGFzdCAmJiAobGFzdCA9PT0gU29ydGFibGUuZ2hvc3QgfHwgY3NzKGxhc3QsICdkaXNwbGF5JykgPT09ICdub25lJyB8fCBzZWxlY3RvciAmJiAhbWF0Y2hlcyhsYXN0LCBzZWxlY3RvcikpKSB7XG4gICAgbGFzdCA9IGxhc3QucHJldmlvdXNFbGVtZW50U2libGluZztcbiAgfVxuICByZXR1cm4gbGFzdCB8fCBudWxsO1xufVxuXG4vKipcclxuICogUmV0dXJucyB0aGUgaW5kZXggb2YgYW4gZWxlbWVudCB3aXRoaW4gaXRzIHBhcmVudCBmb3IgYSBzZWxlY3RlZCBzZXQgb2ZcclxuICogZWxlbWVudHNcclxuICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IGVsXHJcbiAqIEBwYXJhbSAge3NlbGVjdG9yfSBzZWxlY3RvclxyXG4gKiBAcmV0dXJuIHtudW1iZXJ9XHJcbiAqL1xuZnVuY3Rpb24gaW5kZXgoZWwsIHNlbGVjdG9yKSB7XG4gIHZhciBpbmRleCA9IDA7XG4gIGlmICghZWwgfHwgIWVsLnBhcmVudE5vZGUpIHtcbiAgICByZXR1cm4gLTE7XG4gIH1cblxuICAvKiBqc2hpbnQgYm9zczp0cnVlICovXG4gIHdoaWxlIChlbCA9IGVsLnByZXZpb3VzRWxlbWVudFNpYmxpbmcpIHtcbiAgICBpZiAoZWwubm9kZU5hbWUudG9VcHBlckNhc2UoKSAhPT0gJ1RFTVBMQVRFJyAmJiBlbCAhPT0gU29ydGFibGUuY2xvbmUgJiYgKCFzZWxlY3RvciB8fCBtYXRjaGVzKGVsLCBzZWxlY3RvcikpKSB7XG4gICAgICBpbmRleCsrO1xuICAgIH1cbiAgfVxuICByZXR1cm4gaW5kZXg7XG59XG5cbi8qKlxyXG4gKiBSZXR1cm5zIHRoZSBzY3JvbGwgb2Zmc2V0IG9mIHRoZSBnaXZlbiBlbGVtZW50LCBhZGRlZCB3aXRoIGFsbCB0aGUgc2Nyb2xsIG9mZnNldHMgb2YgcGFyZW50IGVsZW1lbnRzLlxyXG4gKiBUaGUgdmFsdWUgaXMgcmV0dXJuZWQgaW4gcmVhbCBwaXhlbHMuXHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSBlbFxyXG4gKiBAcmV0dXJuIHtBcnJheX0gICAgICAgICAgICAgT2Zmc2V0cyBpbiB0aGUgZm9ybWF0IG9mIFtsZWZ0LCB0b3BdXHJcbiAqL1xuZnVuY3Rpb24gZ2V0UmVsYXRpdmVTY3JvbGxPZmZzZXQoZWwpIHtcbiAgdmFyIG9mZnNldExlZnQgPSAwLFxuICAgIG9mZnNldFRvcCA9IDAsXG4gICAgd2luU2Nyb2xsZXIgPSBnZXRXaW5kb3dTY3JvbGxpbmdFbGVtZW50KCk7XG4gIGlmIChlbCkge1xuICAgIGRvIHtcbiAgICAgIHZhciBlbE1hdHJpeCA9IG1hdHJpeChlbCksXG4gICAgICAgIHNjYWxlWCA9IGVsTWF0cml4LmEsXG4gICAgICAgIHNjYWxlWSA9IGVsTWF0cml4LmQ7XG4gICAgICBvZmZzZXRMZWZ0ICs9IGVsLnNjcm9sbExlZnQgKiBzY2FsZVg7XG4gICAgICBvZmZzZXRUb3AgKz0gZWwuc2Nyb2xsVG9wICogc2NhbGVZO1xuICAgIH0gd2hpbGUgKGVsICE9PSB3aW5TY3JvbGxlciAmJiAoZWwgPSBlbC5wYXJlbnROb2RlKSk7XG4gIH1cbiAgcmV0dXJuIFtvZmZzZXRMZWZ0LCBvZmZzZXRUb3BdO1xufVxuXG4vKipcclxuICogUmV0dXJucyB0aGUgaW5kZXggb2YgdGhlIG9iamVjdCB3aXRoaW4gdGhlIGdpdmVuIGFycmF5XHJcbiAqIEBwYXJhbSAge0FycmF5fSBhcnIgICBBcnJheSB0aGF0IG1heSBvciBtYXkgbm90IGhvbGQgdGhlIG9iamVjdFxyXG4gKiBAcGFyYW0gIHtPYmplY3R9IG9iaiAgQW4gb2JqZWN0IHRoYXQgaGFzIGEga2V5LXZhbHVlIHBhaXIgdW5pcXVlIHRvIGFuZCBpZGVudGljYWwgdG8gYSBrZXktdmFsdWUgcGFpciBpbiB0aGUgb2JqZWN0IHlvdSB3YW50IHRvIGZpbmRcclxuICogQHJldHVybiB7TnVtYmVyfSAgICAgIFRoZSBpbmRleCBvZiB0aGUgb2JqZWN0IGluIHRoZSBhcnJheSwgb3IgLTFcclxuICovXG5mdW5jdGlvbiBpbmRleE9mT2JqZWN0KGFyciwgb2JqKSB7XG4gIGZvciAodmFyIGkgaW4gYXJyKSB7XG4gICAgaWYgKCFhcnIuaGFzT3duUHJvcGVydHkoaSkpIGNvbnRpbnVlO1xuICAgIGZvciAodmFyIGtleSBpbiBvYmopIHtcbiAgICAgIGlmIChvYmouaGFzT3duUHJvcGVydHkoa2V5KSAmJiBvYmpba2V5XSA9PT0gYXJyW2ldW2tleV0pIHJldHVybiBOdW1iZXIoaSk7XG4gICAgfVxuICB9XG4gIHJldHVybiAtMTtcbn1cbmZ1bmN0aW9uIGdldFBhcmVudEF1dG9TY3JvbGxFbGVtZW50KGVsLCBpbmNsdWRlU2VsZikge1xuICAvLyBza2lwIHRvIHdpbmRvd1xuICBpZiAoIWVsIHx8ICFlbC5nZXRCb3VuZGluZ0NsaWVudFJlY3QpIHJldHVybiBnZXRXaW5kb3dTY3JvbGxpbmdFbGVtZW50KCk7XG4gIHZhciBlbGVtID0gZWw7XG4gIHZhciBnb3RTZWxmID0gZmFsc2U7XG4gIGRvIHtcbiAgICAvLyB3ZSBkb24ndCBuZWVkIHRvIGdldCBlbGVtIGNzcyBpZiBpdCBpc24ndCBldmVuIG92ZXJmbG93aW5nIGluIHRoZSBmaXJzdCBwbGFjZSAocGVyZm9ybWFuY2UpXG4gICAgaWYgKGVsZW0uY2xpZW50V2lkdGggPCBlbGVtLnNjcm9sbFdpZHRoIHx8IGVsZW0uY2xpZW50SGVpZ2h0IDwgZWxlbS5zY3JvbGxIZWlnaHQpIHtcbiAgICAgIHZhciBlbGVtQ1NTID0gY3NzKGVsZW0pO1xuICAgICAgaWYgKGVsZW0uY2xpZW50V2lkdGggPCBlbGVtLnNjcm9sbFdpZHRoICYmIChlbGVtQ1NTLm92ZXJmbG93WCA9PSAnYXV0bycgfHwgZWxlbUNTUy5vdmVyZmxvd1ggPT0gJ3Njcm9sbCcpIHx8IGVsZW0uY2xpZW50SGVpZ2h0IDwgZWxlbS5zY3JvbGxIZWlnaHQgJiYgKGVsZW1DU1Mub3ZlcmZsb3dZID09ICdhdXRvJyB8fCBlbGVtQ1NTLm92ZXJmbG93WSA9PSAnc2Nyb2xsJykpIHtcbiAgICAgICAgaWYgKCFlbGVtLmdldEJvdW5kaW5nQ2xpZW50UmVjdCB8fCBlbGVtID09PSBkb2N1bWVudC5ib2R5KSByZXR1cm4gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpO1xuICAgICAgICBpZiAoZ290U2VsZiB8fCBpbmNsdWRlU2VsZikgcmV0dXJuIGVsZW07XG4gICAgICAgIGdvdFNlbGYgPSB0cnVlO1xuICAgICAgfVxuICAgIH1cbiAgICAvKiBqc2hpbnQgYm9zczp0cnVlICovXG4gIH0gd2hpbGUgKGVsZW0gPSBlbGVtLnBhcmVudE5vZGUpO1xuICByZXR1cm4gZ2V0V2luZG93U2Nyb2xsaW5nRWxlbWVudCgpO1xufVxuZnVuY3Rpb24gZXh0ZW5kKGRzdCwgc3JjKSB7XG4gIGlmIChkc3QgJiYgc3JjKSB7XG4gICAgZm9yICh2YXIga2V5IGluIHNyYykge1xuICAgICAgaWYgKHNyYy5oYXNPd25Qcm9wZXJ0eShrZXkpKSB7XG4gICAgICAgIGRzdFtrZXldID0gc3JjW2tleV07XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiBkc3Q7XG59XG5mdW5jdGlvbiBpc1JlY3RFcXVhbChyZWN0MSwgcmVjdDIpIHtcbiAgcmV0dXJuIE1hdGgucm91bmQocmVjdDEudG9wKSA9PT0gTWF0aC5yb3VuZChyZWN0Mi50b3ApICYmIE1hdGgucm91bmQocmVjdDEubGVmdCkgPT09IE1hdGgucm91bmQocmVjdDIubGVmdCkgJiYgTWF0aC5yb3VuZChyZWN0MS5oZWlnaHQpID09PSBNYXRoLnJvdW5kKHJlY3QyLmhlaWdodCkgJiYgTWF0aC5yb3VuZChyZWN0MS53aWR0aCkgPT09IE1hdGgucm91bmQocmVjdDIud2lkdGgpO1xufVxudmFyIF90aHJvdHRsZVRpbWVvdXQ7XG5mdW5jdGlvbiB0aHJvdHRsZShjYWxsYmFjaywgbXMpIHtcbiAgcmV0dXJuIGZ1bmN0aW9uICgpIHtcbiAgICBpZiAoIV90aHJvdHRsZVRpbWVvdXQpIHtcbiAgICAgIHZhciBhcmdzID0gYXJndW1lbnRzLFxuICAgICAgICBfdGhpcyA9IHRoaXM7XG4gICAgICBpZiAoYXJncy5sZW5ndGggPT09IDEpIHtcbiAgICAgICAgY2FsbGJhY2suY2FsbChfdGhpcywgYXJnc1swXSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBjYWxsYmFjay5hcHBseShfdGhpcywgYXJncyk7XG4gICAgICB9XG4gICAgICBfdGhyb3R0bGVUaW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgIF90aHJvdHRsZVRpbWVvdXQgPSB2b2lkIDA7XG4gICAgICB9LCBtcyk7XG4gICAgfVxuICB9O1xufVxuZnVuY3Rpb24gY2FuY2VsVGhyb3R0bGUoKSB7XG4gIGNsZWFyVGltZW91dChfdGhyb3R0bGVUaW1lb3V0KTtcbiAgX3Rocm90dGxlVGltZW91dCA9IHZvaWQgMDtcbn1cbmZ1bmN0aW9uIHNjcm9sbEJ5KGVsLCB4LCB5KSB7XG4gIGVsLnNjcm9sbExlZnQgKz0geDtcbiAgZWwuc2Nyb2xsVG9wICs9IHk7XG59XG5mdW5jdGlvbiBjbG9uZShlbCkge1xuICB2YXIgUG9seW1lciA9IHdpbmRvdy5Qb2x5bWVyO1xuICB2YXIgJCA9IHdpbmRvdy5qUXVlcnkgfHwgd2luZG93LlplcHRvO1xuICBpZiAoUG9seW1lciAmJiBQb2x5bWVyLmRvbSkge1xuICAgIHJldHVybiBQb2x5bWVyLmRvbShlbCkuY2xvbmVOb2RlKHRydWUpO1xuICB9IGVsc2UgaWYgKCQpIHtcbiAgICByZXR1cm4gJChlbCkuY2xvbmUodHJ1ZSlbMF07XG4gIH0gZWxzZSB7XG4gICAgcmV0dXJuIGVsLmNsb25lTm9kZSh0cnVlKTtcbiAgfVxufVxuZnVuY3Rpb24gc2V0UmVjdChlbCwgcmVjdCkge1xuICBjc3MoZWwsICdwb3NpdGlvbicsICdhYnNvbHV0ZScpO1xuICBjc3MoZWwsICd0b3AnLCByZWN0LnRvcCk7XG4gIGNzcyhlbCwgJ2xlZnQnLCByZWN0LmxlZnQpO1xuICBjc3MoZWwsICd3aWR0aCcsIHJlY3Qud2lkdGgpO1xuICBjc3MoZWwsICdoZWlnaHQnLCByZWN0LmhlaWdodCk7XG59XG5mdW5jdGlvbiB1bnNldFJlY3QoZWwpIHtcbiAgY3NzKGVsLCAncG9zaXRpb24nLCAnJyk7XG4gIGNzcyhlbCwgJ3RvcCcsICcnKTtcbiAgY3NzKGVsLCAnbGVmdCcsICcnKTtcbiAgY3NzKGVsLCAnd2lkdGgnLCAnJyk7XG4gIGNzcyhlbCwgJ2hlaWdodCcsICcnKTtcbn1cbmZ1bmN0aW9uIGdldENoaWxkQ29udGFpbmluZ1JlY3RGcm9tRWxlbWVudChjb250YWluZXIsIG9wdGlvbnMsIGdob3N0RWwpIHtcbiAgdmFyIHJlY3QgPSB7fTtcbiAgQXJyYXkuZnJvbShjb250YWluZXIuY2hpbGRyZW4pLmZvckVhY2goZnVuY3Rpb24gKGNoaWxkKSB7XG4gICAgdmFyIF9yZWN0JGxlZnQsIF9yZWN0JHRvcCwgX3JlY3QkcmlnaHQsIF9yZWN0JGJvdHRvbTtcbiAgICBpZiAoIWNsb3Nlc3QoY2hpbGQsIG9wdGlvbnMuZHJhZ2dhYmxlLCBjb250YWluZXIsIGZhbHNlKSB8fCBjaGlsZC5hbmltYXRlZCB8fCBjaGlsZCA9PT0gZ2hvc3RFbCkgcmV0dXJuO1xuICAgIHZhciBjaGlsZFJlY3QgPSBnZXRSZWN0KGNoaWxkKTtcbiAgICByZWN0LmxlZnQgPSBNYXRoLm1pbigoX3JlY3QkbGVmdCA9IHJlY3QubGVmdCkgIT09IG51bGwgJiYgX3JlY3QkbGVmdCAhPT0gdm9pZCAwID8gX3JlY3QkbGVmdCA6IEluZmluaXR5LCBjaGlsZFJlY3QubGVmdCk7XG4gICAgcmVjdC50b3AgPSBNYXRoLm1pbigoX3JlY3QkdG9wID0gcmVjdC50b3ApICE9PSBudWxsICYmIF9yZWN0JHRvcCAhPT0gdm9pZCAwID8gX3JlY3QkdG9wIDogSW5maW5pdHksIGNoaWxkUmVjdC50b3ApO1xuICAgIHJlY3QucmlnaHQgPSBNYXRoLm1heCgoX3JlY3QkcmlnaHQgPSByZWN0LnJpZ2h0KSAhPT0gbnVsbCAmJiBfcmVjdCRyaWdodCAhPT0gdm9pZCAwID8gX3JlY3QkcmlnaHQgOiAtSW5maW5pdHksIGNoaWxkUmVjdC5yaWdodCk7XG4gICAgcmVjdC5ib3R0b20gPSBNYXRoLm1heCgoX3JlY3QkYm90dG9tID0gcmVjdC5ib3R0b20pICE9PSBudWxsICYmIF9yZWN0JGJvdHRvbSAhPT0gdm9pZCAwID8gX3JlY3QkYm90dG9tIDogLUluZmluaXR5LCBjaGlsZFJlY3QuYm90dG9tKTtcbiAgfSk7XG4gIHJlY3Qud2lkdGggPSByZWN0LnJpZ2h0IC0gcmVjdC5sZWZ0O1xuICByZWN0LmhlaWdodCA9IHJlY3QuYm90dG9tIC0gcmVjdC50b3A7XG4gIHJlY3QueCA9IHJlY3QubGVmdDtcbiAgcmVjdC55ID0gcmVjdC50b3A7XG4gIHJldHVybiByZWN0O1xufVxudmFyIGV4cGFuZG8gPSAnU29ydGFibGUnICsgbmV3IERhdGUoKS5nZXRUaW1lKCk7XG5cbmZ1bmN0aW9uIEFuaW1hdGlvblN0YXRlTWFuYWdlcigpIHtcbiAgdmFyIGFuaW1hdGlvblN0YXRlcyA9IFtdLFxuICAgIGFuaW1hdGlvbkNhbGxiYWNrSWQ7XG4gIHJldHVybiB7XG4gICAgY2FwdHVyZUFuaW1hdGlvblN0YXRlOiBmdW5jdGlvbiBjYXB0dXJlQW5pbWF0aW9uU3RhdGUoKSB7XG4gICAgICBhbmltYXRpb25TdGF0ZXMgPSBbXTtcbiAgICAgIGlmICghdGhpcy5vcHRpb25zLmFuaW1hdGlvbikgcmV0dXJuO1xuICAgICAgdmFyIGNoaWxkcmVuID0gW10uc2xpY2UuY2FsbCh0aGlzLmVsLmNoaWxkcmVuKTtcbiAgICAgIGNoaWxkcmVuLmZvckVhY2goZnVuY3Rpb24gKGNoaWxkKSB7XG4gICAgICAgIGlmIChjc3MoY2hpbGQsICdkaXNwbGF5JykgPT09ICdub25lJyB8fCBjaGlsZCA9PT0gU29ydGFibGUuZ2hvc3QpIHJldHVybjtcbiAgICAgICAgYW5pbWF0aW9uU3RhdGVzLnB1c2goe1xuICAgICAgICAgIHRhcmdldDogY2hpbGQsXG4gICAgICAgICAgcmVjdDogZ2V0UmVjdChjaGlsZClcbiAgICAgICAgfSk7XG4gICAgICAgIHZhciBmcm9tUmVjdCA9IF9vYmplY3RTcHJlYWQyKHt9LCBhbmltYXRpb25TdGF0ZXNbYW5pbWF0aW9uU3RhdGVzLmxlbmd0aCAtIDFdLnJlY3QpO1xuXG4gICAgICAgIC8vIElmIGFuaW1hdGluZzogY29tcGVuc2F0ZSBmb3IgY3VycmVudCBhbmltYXRpb25cbiAgICAgICAgaWYgKGNoaWxkLnRoaXNBbmltYXRpb25EdXJhdGlvbikge1xuICAgICAgICAgIHZhciBjaGlsZE1hdHJpeCA9IG1hdHJpeChjaGlsZCwgdHJ1ZSk7XG4gICAgICAgICAgaWYgKGNoaWxkTWF0cml4KSB7XG4gICAgICAgICAgICBmcm9tUmVjdC50b3AgLT0gY2hpbGRNYXRyaXguZjtcbiAgICAgICAgICAgIGZyb21SZWN0LmxlZnQgLT0gY2hpbGRNYXRyaXguZTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgY2hpbGQuZnJvbVJlY3QgPSBmcm9tUmVjdDtcbiAgICAgIH0pO1xuICAgIH0sXG4gICAgYWRkQW5pbWF0aW9uU3RhdGU6IGZ1bmN0aW9uIGFkZEFuaW1hdGlvblN0YXRlKHN0YXRlKSB7XG4gICAgICBhbmltYXRpb25TdGF0ZXMucHVzaChzdGF0ZSk7XG4gICAgfSxcbiAgICByZW1vdmVBbmltYXRpb25TdGF0ZTogZnVuY3Rpb24gcmVtb3ZlQW5pbWF0aW9uU3RhdGUodGFyZ2V0KSB7XG4gICAgICBhbmltYXRpb25TdGF0ZXMuc3BsaWNlKGluZGV4T2ZPYmplY3QoYW5pbWF0aW9uU3RhdGVzLCB7XG4gICAgICAgIHRhcmdldDogdGFyZ2V0XG4gICAgICB9KSwgMSk7XG4gICAgfSxcbiAgICBhbmltYXRlQWxsOiBmdW5jdGlvbiBhbmltYXRlQWxsKGNhbGxiYWNrKSB7XG4gICAgICB2YXIgX3RoaXMgPSB0aGlzO1xuICAgICAgaWYgKCF0aGlzLm9wdGlvbnMuYW5pbWF0aW9uKSB7XG4gICAgICAgIGNsZWFyVGltZW91dChhbmltYXRpb25DYWxsYmFja0lkKTtcbiAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykgY2FsbGJhY2soKTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgdmFyIGFuaW1hdGluZyA9IGZhbHNlLFxuICAgICAgICBhbmltYXRpb25UaW1lID0gMDtcbiAgICAgIGFuaW1hdGlvblN0YXRlcy5mb3JFYWNoKGZ1bmN0aW9uIChzdGF0ZSkge1xuICAgICAgICB2YXIgdGltZSA9IDAsXG4gICAgICAgICAgdGFyZ2V0ID0gc3RhdGUudGFyZ2V0LFxuICAgICAgICAgIGZyb21SZWN0ID0gdGFyZ2V0LmZyb21SZWN0LFxuICAgICAgICAgIHRvUmVjdCA9IGdldFJlY3QodGFyZ2V0KSxcbiAgICAgICAgICBwcmV2RnJvbVJlY3QgPSB0YXJnZXQucHJldkZyb21SZWN0LFxuICAgICAgICAgIHByZXZUb1JlY3QgPSB0YXJnZXQucHJldlRvUmVjdCxcbiAgICAgICAgICBhbmltYXRpbmdSZWN0ID0gc3RhdGUucmVjdCxcbiAgICAgICAgICB0YXJnZXRNYXRyaXggPSBtYXRyaXgodGFyZ2V0LCB0cnVlKTtcbiAgICAgICAgaWYgKHRhcmdldE1hdHJpeCkge1xuICAgICAgICAgIC8vIENvbXBlbnNhdGUgZm9yIGN1cnJlbnQgYW5pbWF0aW9uXG4gICAgICAgICAgdG9SZWN0LnRvcCAtPSB0YXJnZXRNYXRyaXguZjtcbiAgICAgICAgICB0b1JlY3QubGVmdCAtPSB0YXJnZXRNYXRyaXguZTtcbiAgICAgICAgfVxuICAgICAgICB0YXJnZXQudG9SZWN0ID0gdG9SZWN0O1xuICAgICAgICBpZiAodGFyZ2V0LnRoaXNBbmltYXRpb25EdXJhdGlvbikge1xuICAgICAgICAgIC8vIENvdWxkIGFsc28gY2hlY2sgaWYgYW5pbWF0aW5nUmVjdCBpcyBiZXR3ZWVuIGZyb21SZWN0IGFuZCB0b1JlY3RcbiAgICAgICAgICBpZiAoaXNSZWN0RXF1YWwocHJldkZyb21SZWN0LCB0b1JlY3QpICYmICFpc1JlY3RFcXVhbChmcm9tUmVjdCwgdG9SZWN0KSAmJlxuICAgICAgICAgIC8vIE1ha2Ugc3VyZSBhbmltYXRpbmdSZWN0IGlzIG9uIGxpbmUgYmV0d2VlbiB0b1JlY3QgJiBmcm9tUmVjdFxuICAgICAgICAgIChhbmltYXRpbmdSZWN0LnRvcCAtIHRvUmVjdC50b3ApIC8gKGFuaW1hdGluZ1JlY3QubGVmdCAtIHRvUmVjdC5sZWZ0KSA9PT0gKGZyb21SZWN0LnRvcCAtIHRvUmVjdC50b3ApIC8gKGZyb21SZWN0LmxlZnQgLSB0b1JlY3QubGVmdCkpIHtcbiAgICAgICAgICAgIC8vIElmIHJldHVybmluZyB0byBzYW1lIHBsYWNlIGFzIHN0YXJ0ZWQgZnJvbSBhbmltYXRpb24gYW5kIG9uIHNhbWUgYXhpc1xuICAgICAgICAgICAgdGltZSA9IGNhbGN1bGF0ZVJlYWxUaW1lKGFuaW1hdGluZ1JlY3QsIHByZXZGcm9tUmVjdCwgcHJldlRvUmVjdCwgX3RoaXMub3B0aW9ucyk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgLy8gaWYgZnJvbVJlY3QgIT0gdG9SZWN0OiBhbmltYXRlXG4gICAgICAgIGlmICghaXNSZWN0RXF1YWwodG9SZWN0LCBmcm9tUmVjdCkpIHtcbiAgICAgICAgICB0YXJnZXQucHJldkZyb21SZWN0ID0gZnJvbVJlY3Q7XG4gICAgICAgICAgdGFyZ2V0LnByZXZUb1JlY3QgPSB0b1JlY3Q7XG4gICAgICAgICAgaWYgKCF0aW1lKSB7XG4gICAgICAgICAgICB0aW1lID0gX3RoaXMub3B0aW9ucy5hbmltYXRpb247XG4gICAgICAgICAgfVxuICAgICAgICAgIF90aGlzLmFuaW1hdGUodGFyZ2V0LCBhbmltYXRpbmdSZWN0LCB0b1JlY3QsIHRpbWUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aW1lKSB7XG4gICAgICAgICAgYW5pbWF0aW5nID0gdHJ1ZTtcbiAgICAgICAgICBhbmltYXRpb25UaW1lID0gTWF0aC5tYXgoYW5pbWF0aW9uVGltZSwgdGltZSk7XG4gICAgICAgICAgY2xlYXJUaW1lb3V0KHRhcmdldC5hbmltYXRpb25SZXNldFRpbWVyKTtcbiAgICAgICAgICB0YXJnZXQuYW5pbWF0aW9uUmVzZXRUaW1lciA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGFyZ2V0LmFuaW1hdGlvblRpbWUgPSAwO1xuICAgICAgICAgICAgdGFyZ2V0LnByZXZGcm9tUmVjdCA9IG51bGw7XG4gICAgICAgICAgICB0YXJnZXQuZnJvbVJlY3QgPSBudWxsO1xuICAgICAgICAgICAgdGFyZ2V0LnByZXZUb1JlY3QgPSBudWxsO1xuICAgICAgICAgICAgdGFyZ2V0LnRoaXNBbmltYXRpb25EdXJhdGlvbiA9IG51bGw7XG4gICAgICAgICAgfSwgdGltZSk7XG4gICAgICAgICAgdGFyZ2V0LnRoaXNBbmltYXRpb25EdXJhdGlvbiA9IHRpbWU7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgICAgY2xlYXJUaW1lb3V0KGFuaW1hdGlvbkNhbGxiYWNrSWQpO1xuICAgICAgaWYgKCFhbmltYXRpbmcpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykgY2FsbGJhY2soKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGFuaW1hdGlvbkNhbGxiYWNrSWQgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSBjYWxsYmFjaygpO1xuICAgICAgICB9LCBhbmltYXRpb25UaW1lKTtcbiAgICAgIH1cbiAgICAgIGFuaW1hdGlvblN0YXRlcyA9IFtdO1xuICAgIH0sXG4gICAgYW5pbWF0ZTogZnVuY3Rpb24gYW5pbWF0ZSh0YXJnZXQsIGN1cnJlbnRSZWN0LCB0b1JlY3QsIGR1cmF0aW9uKSB7XG4gICAgICBpZiAoZHVyYXRpb24pIHtcbiAgICAgICAgY3NzKHRhcmdldCwgJ3RyYW5zaXRpb24nLCAnJyk7XG4gICAgICAgIGNzcyh0YXJnZXQsICd0cmFuc2Zvcm0nLCAnJyk7XG4gICAgICAgIHZhciBlbE1hdHJpeCA9IG1hdHJpeCh0aGlzLmVsKSxcbiAgICAgICAgICBzY2FsZVggPSBlbE1hdHJpeCAmJiBlbE1hdHJpeC5hLFxuICAgICAgICAgIHNjYWxlWSA9IGVsTWF0cml4ICYmIGVsTWF0cml4LmQsXG4gICAgICAgICAgdHJhbnNsYXRlWCA9IChjdXJyZW50UmVjdC5sZWZ0IC0gdG9SZWN0LmxlZnQpIC8gKHNjYWxlWCB8fCAxKSxcbiAgICAgICAgICB0cmFuc2xhdGVZID0gKGN1cnJlbnRSZWN0LnRvcCAtIHRvUmVjdC50b3ApIC8gKHNjYWxlWSB8fCAxKTtcbiAgICAgICAgdGFyZ2V0LmFuaW1hdGluZ1ggPSAhIXRyYW5zbGF0ZVg7XG4gICAgICAgIHRhcmdldC5hbmltYXRpbmdZID0gISF0cmFuc2xhdGVZO1xuICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNmb3JtJywgJ3RyYW5zbGF0ZTNkKCcgKyB0cmFuc2xhdGVYICsgJ3B4LCcgKyB0cmFuc2xhdGVZICsgJ3B4LDApJyk7XG4gICAgICAgIHRoaXMuZm9yUmVwYWludER1bW15ID0gcmVwYWludCh0YXJnZXQpOyAvLyByZXBhaW50XG5cbiAgICAgICAgY3NzKHRhcmdldCwgJ3RyYW5zaXRpb24nLCAndHJhbnNmb3JtICcgKyBkdXJhdGlvbiArICdtcycgKyAodGhpcy5vcHRpb25zLmVhc2luZyA/ICcgJyArIHRoaXMub3B0aW9ucy5lYXNpbmcgOiAnJykpO1xuICAgICAgICBjc3ModGFyZ2V0LCAndHJhbnNmb3JtJywgJ3RyYW5zbGF0ZTNkKDAsMCwwKScpO1xuICAgICAgICB0eXBlb2YgdGFyZ2V0LmFuaW1hdGVkID09PSAnbnVtYmVyJyAmJiBjbGVhclRpbWVvdXQodGFyZ2V0LmFuaW1hdGVkKTtcbiAgICAgICAgdGFyZ2V0LmFuaW1hdGVkID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgY3NzKHRhcmdldCwgJ3RyYW5zaXRpb24nLCAnJyk7XG4gICAgICAgICAgY3NzKHRhcmdldCwgJ3RyYW5zZm9ybScsICcnKTtcbiAgICAgICAgICB0YXJnZXQuYW5pbWF0ZWQgPSBmYWxzZTtcbiAgICAgICAgICB0YXJnZXQuYW5pbWF0aW5nWCA9IGZhbHNlO1xuICAgICAgICAgIHRhcmdldC5hbmltYXRpbmdZID0gZmFsc2U7XG4gICAgICAgIH0sIGR1cmF0aW9uKTtcbiAgICAgIH1cbiAgICB9XG4gIH07XG59XG5mdW5jdGlvbiByZXBhaW50KHRhcmdldCkge1xuICByZXR1cm4gdGFyZ2V0Lm9mZnNldFdpZHRoO1xufVxuZnVuY3Rpb24gY2FsY3VsYXRlUmVhbFRpbWUoYW5pbWF0aW5nUmVjdCwgZnJvbVJlY3QsIHRvUmVjdCwgb3B0aW9ucykge1xuICByZXR1cm4gTWF0aC5zcXJ0KE1hdGgucG93KGZyb21SZWN0LnRvcCAtIGFuaW1hdGluZ1JlY3QudG9wLCAyKSArIE1hdGgucG93KGZyb21SZWN0LmxlZnQgLSBhbmltYXRpbmdSZWN0LmxlZnQsIDIpKSAvIE1hdGguc3FydChNYXRoLnBvdyhmcm9tUmVjdC50b3AgLSB0b1JlY3QudG9wLCAyKSArIE1hdGgucG93KGZyb21SZWN0LmxlZnQgLSB0b1JlY3QubGVmdCwgMikpICogb3B0aW9ucy5hbmltYXRpb247XG59XG5cbnZhciBwbHVnaW5zID0gW107XG52YXIgZGVmYXVsdHMgPSB7XG4gIGluaXRpYWxpemVCeURlZmF1bHQ6IHRydWVcbn07XG52YXIgUGx1Z2luTWFuYWdlciA9IHtcbiAgbW91bnQ6IGZ1bmN0aW9uIG1vdW50KHBsdWdpbikge1xuICAgIC8vIFNldCBkZWZhdWx0IHN0YXRpYyBwcm9wZXJ0aWVzXG4gICAgZm9yICh2YXIgb3B0aW9uIGluIGRlZmF1bHRzKSB7XG4gICAgICBpZiAoZGVmYXVsdHMuaGFzT3duUHJvcGVydHkob3B0aW9uKSAmJiAhKG9wdGlvbiBpbiBwbHVnaW4pKSB7XG4gICAgICAgIHBsdWdpbltvcHRpb25dID0gZGVmYXVsdHNbb3B0aW9uXTtcbiAgICAgIH1cbiAgICB9XG4gICAgcGx1Z2lucy5mb3JFYWNoKGZ1bmN0aW9uIChwKSB7XG4gICAgICBpZiAocC5wbHVnaW5OYW1lID09PSBwbHVnaW4ucGx1Z2luTmFtZSkge1xuICAgICAgICB0aHJvdyBcIlNvcnRhYmxlOiBDYW5ub3QgbW91bnQgcGx1Z2luIFwiLmNvbmNhdChwbHVnaW4ucGx1Z2luTmFtZSwgXCIgbW9yZSB0aGFuIG9uY2VcIik7XG4gICAgICB9XG4gICAgfSk7XG4gICAgcGx1Z2lucy5wdXNoKHBsdWdpbik7XG4gIH0sXG4gIHBsdWdpbkV2ZW50OiBmdW5jdGlvbiBwbHVnaW5FdmVudChldmVudE5hbWUsIHNvcnRhYmxlLCBldnQpIHtcbiAgICB2YXIgX3RoaXMgPSB0aGlzO1xuICAgIHRoaXMuZXZlbnRDYW5jZWxlZCA9IGZhbHNlO1xuICAgIGV2dC5jYW5jZWwgPSBmdW5jdGlvbiAoKSB7XG4gICAgICBfdGhpcy5ldmVudENhbmNlbGVkID0gdHJ1ZTtcbiAgICB9O1xuICAgIHZhciBldmVudE5hbWVHbG9iYWwgPSBldmVudE5hbWUgKyAnR2xvYmFsJztcbiAgICBwbHVnaW5zLmZvckVhY2goZnVuY3Rpb24gKHBsdWdpbikge1xuICAgICAgaWYgKCFzb3J0YWJsZVtwbHVnaW4ucGx1Z2luTmFtZV0pIHJldHVybjtcbiAgICAgIC8vIEZpcmUgZ2xvYmFsIGV2ZW50cyBpZiBpdCBleGlzdHMgaW4gdGhpcyBzb3J0YWJsZVxuICAgICAgaWYgKHNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXVtldmVudE5hbWVHbG9iYWxdKSB7XG4gICAgICAgIHNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXVtldmVudE5hbWVHbG9iYWxdKF9vYmplY3RTcHJlYWQyKHtcbiAgICAgICAgICBzb3J0YWJsZTogc29ydGFibGVcbiAgICAgICAgfSwgZXZ0KSk7XG4gICAgICB9XG5cbiAgICAgIC8vIE9ubHkgZmlyZSBwbHVnaW4gZXZlbnQgaWYgcGx1Z2luIGlzIGVuYWJsZWQgaW4gdGhpcyBzb3J0YWJsZSxcbiAgICAgIC8vIGFuZCBwbHVnaW4gaGFzIGV2ZW50IGRlZmluZWRcbiAgICAgIGlmIChzb3J0YWJsZS5vcHRpb25zW3BsdWdpbi5wbHVnaW5OYW1lXSAmJiBzb3J0YWJsZVtwbHVnaW4ucGx1Z2luTmFtZV1bZXZlbnROYW1lXSkge1xuICAgICAgICBzb3J0YWJsZVtwbHVnaW4ucGx1Z2luTmFtZV1bZXZlbnROYW1lXShfb2JqZWN0U3ByZWFkMih7XG4gICAgICAgICAgc29ydGFibGU6IHNvcnRhYmxlXG4gICAgICAgIH0sIGV2dCkpO1xuICAgICAgfVxuICAgIH0pO1xuICB9LFxuICBpbml0aWFsaXplUGx1Z2luczogZnVuY3Rpb24gaW5pdGlhbGl6ZVBsdWdpbnMoc29ydGFibGUsIGVsLCBkZWZhdWx0cywgb3B0aW9ucykge1xuICAgIHBsdWdpbnMuZm9yRWFjaChmdW5jdGlvbiAocGx1Z2luKSB7XG4gICAgICB2YXIgcGx1Z2luTmFtZSA9IHBsdWdpbi5wbHVnaW5OYW1lO1xuICAgICAgaWYgKCFzb3J0YWJsZS5vcHRpb25zW3BsdWdpbk5hbWVdICYmICFwbHVnaW4uaW5pdGlhbGl6ZUJ5RGVmYXVsdCkgcmV0dXJuO1xuICAgICAgdmFyIGluaXRpYWxpemVkID0gbmV3IHBsdWdpbihzb3J0YWJsZSwgZWwsIHNvcnRhYmxlLm9wdGlvbnMpO1xuICAgICAgaW5pdGlhbGl6ZWQuc29ydGFibGUgPSBzb3J0YWJsZTtcbiAgICAgIGluaXRpYWxpemVkLm9wdGlvbnMgPSBzb3J0YWJsZS5vcHRpb25zO1xuICAgICAgc29ydGFibGVbcGx1Z2luTmFtZV0gPSBpbml0aWFsaXplZDtcblxuICAgICAgLy8gQWRkIGRlZmF1bHQgb3B0aW9ucyBmcm9tIHBsdWdpblxuICAgICAgX2V4dGVuZHMoZGVmYXVsdHMsIGluaXRpYWxpemVkLmRlZmF1bHRzKTtcbiAgICB9KTtcbiAgICBmb3IgKHZhciBvcHRpb24gaW4gc29ydGFibGUub3B0aW9ucykge1xuICAgICAgaWYgKCFzb3J0YWJsZS5vcHRpb25zLmhhc093blByb3BlcnR5KG9wdGlvbikpIGNvbnRpbnVlO1xuICAgICAgdmFyIG1vZGlmaWVkID0gdGhpcy5tb2RpZnlPcHRpb24oc29ydGFibGUsIG9wdGlvbiwgc29ydGFibGUub3B0aW9uc1tvcHRpb25dKTtcbiAgICAgIGlmICh0eXBlb2YgbW9kaWZpZWQgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHNvcnRhYmxlLm9wdGlvbnNbb3B0aW9uXSA9IG1vZGlmaWVkO1xuICAgICAgfVxuICAgIH1cbiAgfSxcbiAgZ2V0RXZlbnRQcm9wZXJ0aWVzOiBmdW5jdGlvbiBnZXRFdmVudFByb3BlcnRpZXMobmFtZSwgc29ydGFibGUpIHtcbiAgICB2YXIgZXZlbnRQcm9wZXJ0aWVzID0ge307XG4gICAgcGx1Z2lucy5mb3JFYWNoKGZ1bmN0aW9uIChwbHVnaW4pIHtcbiAgICAgIGlmICh0eXBlb2YgcGx1Z2luLmV2ZW50UHJvcGVydGllcyAhPT0gJ2Z1bmN0aW9uJykgcmV0dXJuO1xuICAgICAgX2V4dGVuZHMoZXZlbnRQcm9wZXJ0aWVzLCBwbHVnaW4uZXZlbnRQcm9wZXJ0aWVzLmNhbGwoc29ydGFibGVbcGx1Z2luLnBsdWdpbk5hbWVdLCBuYW1lKSk7XG4gICAgfSk7XG4gICAgcmV0dXJuIGV2ZW50UHJvcGVydGllcztcbiAgfSxcbiAgbW9kaWZ5T3B0aW9uOiBmdW5jdGlvbiBtb2RpZnlPcHRpb24oc29ydGFibGUsIG5hbWUsIHZhbHVlKSB7XG4gICAgdmFyIG1vZGlmaWVkVmFsdWU7XG4gICAgcGx1Z2lucy5mb3JFYWNoKGZ1bmN0aW9uIChwbHVnaW4pIHtcbiAgICAgIC8vIFBsdWdpbiBtdXN0IGV4aXN0IG9uIHRoZSBTb3J0YWJsZVxuICAgICAgaWYgKCFzb3J0YWJsZVtwbHVnaW4ucGx1Z2luTmFtZV0pIHJldHVybjtcblxuICAgICAgLy8gSWYgc3RhdGljIG9wdGlvbiBsaXN0ZW5lciBleGlzdHMgZm9yIHRoaXMgb3B0aW9uLCBjYWxsIGluIHRoZSBjb250ZXh0IG9mIHRoZSBTb3J0YWJsZSdzIGluc3RhbmNlIG9mIHRoaXMgcGx1Z2luXG4gICAgICBpZiAocGx1Z2luLm9wdGlvbkxpc3RlbmVycyAmJiB0eXBlb2YgcGx1Z2luLm9wdGlvbkxpc3RlbmVyc1tuYW1lXSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICBtb2RpZmllZFZhbHVlID0gcGx1Z2luLm9wdGlvbkxpc3RlbmVyc1tuYW1lXS5jYWxsKHNvcnRhYmxlW3BsdWdpbi5wbHVnaW5OYW1lXSwgdmFsdWUpO1xuICAgICAgfVxuICAgIH0pO1xuICAgIHJldHVybiBtb2RpZmllZFZhbHVlO1xuICB9XG59O1xuXG5mdW5jdGlvbiBkaXNwYXRjaEV2ZW50KF9yZWYpIHtcbiAgdmFyIHNvcnRhYmxlID0gX3JlZi5zb3J0YWJsZSxcbiAgICByb290RWwgPSBfcmVmLnJvb3RFbCxcbiAgICBuYW1lID0gX3JlZi5uYW1lLFxuICAgIHRhcmdldEVsID0gX3JlZi50YXJnZXRFbCxcbiAgICBjbG9uZUVsID0gX3JlZi5jbG9uZUVsLFxuICAgIHRvRWwgPSBfcmVmLnRvRWwsXG4gICAgZnJvbUVsID0gX3JlZi5mcm9tRWwsXG4gICAgb2xkSW5kZXggPSBfcmVmLm9sZEluZGV4LFxuICAgIG5ld0luZGV4ID0gX3JlZi5uZXdJbmRleCxcbiAgICBvbGREcmFnZ2FibGVJbmRleCA9IF9yZWYub2xkRHJhZ2dhYmxlSW5kZXgsXG4gICAgbmV3RHJhZ2dhYmxlSW5kZXggPSBfcmVmLm5ld0RyYWdnYWJsZUluZGV4LFxuICAgIG9yaWdpbmFsRXZlbnQgPSBfcmVmLm9yaWdpbmFsRXZlbnQsXG4gICAgcHV0U29ydGFibGUgPSBfcmVmLnB1dFNvcnRhYmxlLFxuICAgIGV4dHJhRXZlbnRQcm9wZXJ0aWVzID0gX3JlZi5leHRyYUV2ZW50UHJvcGVydGllcztcbiAgc29ydGFibGUgPSBzb3J0YWJsZSB8fCByb290RWwgJiYgcm9vdEVsW2V4cGFuZG9dO1xuICBpZiAoIXNvcnRhYmxlKSByZXR1cm47XG4gIHZhciBldnQsXG4gICAgb3B0aW9ucyA9IHNvcnRhYmxlLm9wdGlvbnMsXG4gICAgb25OYW1lID0gJ29uJyArIG5hbWUuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBuYW1lLnN1YnN0cigxKTtcbiAgLy8gU3VwcG9ydCBmb3IgbmV3IEN1c3RvbUV2ZW50IGZlYXR1cmVcbiAgaWYgKHdpbmRvdy5DdXN0b21FdmVudCAmJiAhSUUxMU9yTGVzcyAmJiAhRWRnZSkge1xuICAgIGV2dCA9IG5ldyBDdXN0b21FdmVudChuYW1lLCB7XG4gICAgICBidWJibGVzOiB0cnVlLFxuICAgICAgY2FuY2VsYWJsZTogdHJ1ZVxuICAgIH0pO1xuICB9IGVsc2Uge1xuICAgIGV2dCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KCdFdmVudCcpO1xuICAgIGV2dC5pbml0RXZlbnQobmFtZSwgdHJ1ZSwgdHJ1ZSk7XG4gIH1cbiAgZXZ0LnRvID0gdG9FbCB8fCByb290RWw7XG4gIGV2dC5mcm9tID0gZnJvbUVsIHx8IHJvb3RFbDtcbiAgZXZ0Lml0ZW0gPSB0YXJnZXRFbCB8fCByb290RWw7XG4gIGV2dC5jbG9uZSA9IGNsb25lRWw7XG4gIGV2dC5vbGRJbmRleCA9IG9sZEluZGV4O1xuICBldnQubmV3SW5kZXggPSBuZXdJbmRleDtcbiAgZXZ0Lm9sZERyYWdnYWJsZUluZGV4ID0gb2xkRHJhZ2dhYmxlSW5kZXg7XG4gIGV2dC5uZXdEcmFnZ2FibGVJbmRleCA9IG5ld0RyYWdnYWJsZUluZGV4O1xuICBldnQub3JpZ2luYWxFdmVudCA9IG9yaWdpbmFsRXZlbnQ7XG4gIGV2dC5wdWxsTW9kZSA9IHB1dFNvcnRhYmxlID8gcHV0U29ydGFibGUubGFzdFB1dE1vZGUgOiB1bmRlZmluZWQ7XG4gIHZhciBhbGxFdmVudFByb3BlcnRpZXMgPSBfb2JqZWN0U3ByZWFkMihfb2JqZWN0U3ByZWFkMih7fSwgZXh0cmFFdmVudFByb3BlcnRpZXMpLCBQbHVnaW5NYW5hZ2VyLmdldEV2ZW50UHJvcGVydGllcyhuYW1lLCBzb3J0YWJsZSkpO1xuICBmb3IgKHZhciBvcHRpb24gaW4gYWxsRXZlbnRQcm9wZXJ0aWVzKSB7XG4gICAgZXZ0W29wdGlvbl0gPSBhbGxFdmVudFByb3BlcnRpZXNbb3B0aW9uXTtcbiAgfVxuICBpZiAocm9vdEVsKSB7XG4gICAgcm9vdEVsLmRpc3BhdGNoRXZlbnQoZXZ0KTtcbiAgfVxuICBpZiAob3B0aW9uc1tvbk5hbWVdKSB7XG4gICAgb3B0aW9uc1tvbk5hbWVdLmNhbGwoc29ydGFibGUsIGV2dCk7XG4gIH1cbn1cblxudmFyIF9leGNsdWRlZCA9IFtcImV2dFwiXTtcbnZhciBwbHVnaW5FdmVudCA9IGZ1bmN0aW9uIHBsdWdpbkV2ZW50KGV2ZW50TmFtZSwgc29ydGFibGUpIHtcbiAgdmFyIF9yZWYgPSBhcmd1bWVudHMubGVuZ3RoID4gMiAmJiBhcmd1bWVudHNbMl0gIT09IHVuZGVmaW5lZCA/IGFyZ3VtZW50c1syXSA6IHt9LFxuICAgIG9yaWdpbmFsRXZlbnQgPSBfcmVmLmV2dCxcbiAgICBkYXRhID0gX29iamVjdFdpdGhvdXRQcm9wZXJ0aWVzKF9yZWYsIF9leGNsdWRlZCk7XG4gIFBsdWdpbk1hbmFnZXIucGx1Z2luRXZlbnQuYmluZChTb3J0YWJsZSkoZXZlbnROYW1lLCBzb3J0YWJsZSwgX29iamVjdFNwcmVhZDIoe1xuICAgIGRyYWdFbDogZHJhZ0VsLFxuICAgIHBhcmVudEVsOiBwYXJlbnRFbCxcbiAgICBnaG9zdEVsOiBnaG9zdEVsLFxuICAgIHJvb3RFbDogcm9vdEVsLFxuICAgIG5leHRFbDogbmV4dEVsLFxuICAgIGxhc3REb3duRWw6IGxhc3REb3duRWwsXG4gICAgY2xvbmVFbDogY2xvbmVFbCxcbiAgICBjbG9uZUhpZGRlbjogY2xvbmVIaWRkZW4sXG4gICAgZHJhZ1N0YXJ0ZWQ6IG1vdmVkLFxuICAgIHB1dFNvcnRhYmxlOiBwdXRTb3J0YWJsZSxcbiAgICBhY3RpdmVTb3J0YWJsZTogU29ydGFibGUuYWN0aXZlLFxuICAgIG9yaWdpbmFsRXZlbnQ6IG9yaWdpbmFsRXZlbnQsXG4gICAgb2xkSW5kZXg6IG9sZEluZGV4LFxuICAgIG9sZERyYWdnYWJsZUluZGV4OiBvbGREcmFnZ2FibGVJbmRleCxcbiAgICBuZXdJbmRleDogbmV3SW5kZXgsXG4gICAgbmV3RHJhZ2dhYmxlSW5kZXg6IG5ld0RyYWdnYWJsZUluZGV4LFxuICAgIGhpZGVHaG9zdEZvclRhcmdldDogX2hpZGVHaG9zdEZvclRhcmdldCxcbiAgICB1bmhpZGVHaG9zdEZvclRhcmdldDogX3VuaGlkZUdob3N0Rm9yVGFyZ2V0LFxuICAgIGNsb25lTm93SGlkZGVuOiBmdW5jdGlvbiBjbG9uZU5vd0hpZGRlbigpIHtcbiAgICAgIGNsb25lSGlkZGVuID0gdHJ1ZTtcbiAgICB9LFxuICAgIGNsb25lTm93U2hvd246IGZ1bmN0aW9uIGNsb25lTm93U2hvd24oKSB7XG4gICAgICBjbG9uZUhpZGRlbiA9IGZhbHNlO1xuICAgIH0sXG4gICAgZGlzcGF0Y2hTb3J0YWJsZUV2ZW50OiBmdW5jdGlvbiBkaXNwYXRjaFNvcnRhYmxlRXZlbnQobmFtZSkge1xuICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICBzb3J0YWJsZTogc29ydGFibGUsXG4gICAgICAgIG5hbWU6IG5hbWUsXG4gICAgICAgIG9yaWdpbmFsRXZlbnQ6IG9yaWdpbmFsRXZlbnRcbiAgICAgIH0pO1xuICAgIH1cbiAgfSwgZGF0YSkpO1xufTtcbmZ1bmN0aW9uIF9kaXNwYXRjaEV2ZW50KGluZm8pIHtcbiAgZGlzcGF0Y2hFdmVudChfb2JqZWN0U3ByZWFkMih7XG4gICAgcHV0U29ydGFibGU6IHB1dFNvcnRhYmxlLFxuICAgIGNsb25lRWw6IGNsb25lRWwsXG4gICAgdGFyZ2V0RWw6IGRyYWdFbCxcbiAgICByb290RWw6IHJvb3RFbCxcbiAgICBvbGRJbmRleDogb2xkSW5kZXgsXG4gICAgb2xkRHJhZ2dhYmxlSW5kZXg6IG9sZERyYWdnYWJsZUluZGV4LFxuICAgIG5ld0luZGV4OiBuZXdJbmRleCxcbiAgICBuZXdEcmFnZ2FibGVJbmRleDogbmV3RHJhZ2dhYmxlSW5kZXhcbiAgfSwgaW5mbykpO1xufVxudmFyIGRyYWdFbCxcbiAgcGFyZW50RWwsXG4gIGdob3N0RWwsXG4gIHJvb3RFbCxcbiAgbmV4dEVsLFxuICBsYXN0RG93bkVsLFxuICBjbG9uZUVsLFxuICBjbG9uZUhpZGRlbixcbiAgb2xkSW5kZXgsXG4gIG5ld0luZGV4LFxuICBvbGREcmFnZ2FibGVJbmRleCxcbiAgbmV3RHJhZ2dhYmxlSW5kZXgsXG4gIGFjdGl2ZUdyb3VwLFxuICBwdXRTb3J0YWJsZSxcbiAgYXdhaXRpbmdEcmFnU3RhcnRlZCA9IGZhbHNlLFxuICBpZ25vcmVOZXh0Q2xpY2sgPSBmYWxzZSxcbiAgc29ydGFibGVzID0gW10sXG4gIHRhcEV2dCxcbiAgdG91Y2hFdnQsXG4gIGxhc3REeCxcbiAgbGFzdER5LFxuICB0YXBEaXN0YW5jZUxlZnQsXG4gIHRhcERpc3RhbmNlVG9wLFxuICBtb3ZlZCxcbiAgbGFzdFRhcmdldCxcbiAgbGFzdERpcmVjdGlvbixcbiAgcGFzdEZpcnN0SW52ZXJ0VGhyZXNoID0gZmFsc2UsXG4gIGlzQ2lyY3Vtc3RhbnRpYWxJbnZlcnQgPSBmYWxzZSxcbiAgdGFyZ2V0TW92ZURpc3RhbmNlLFxuICAvLyBGb3IgcG9zaXRpb25pbmcgZ2hvc3QgYWJzb2x1dGVseVxuICBnaG9zdFJlbGF0aXZlUGFyZW50LFxuICBnaG9zdFJlbGF0aXZlUGFyZW50SW5pdGlhbFNjcm9sbCA9IFtdLFxuICAvLyAobGVmdCwgdG9wKVxuXG4gIF9zaWxlbnQgPSBmYWxzZSxcbiAgc2F2ZWRJbnB1dENoZWNrZWQgPSBbXTtcblxuLyoqIEBjb25zdCAqL1xudmFyIGRvY3VtZW50RXhpc3RzID0gdHlwZW9mIGRvY3VtZW50ICE9PSAndW5kZWZpbmVkJyxcbiAgUG9zaXRpb25HaG9zdEFic29sdXRlbHkgPSBJT1MsXG4gIENTU0Zsb2F0UHJvcGVydHkgPSBFZGdlIHx8IElFMTFPckxlc3MgPyAnY3NzRmxvYXQnIDogJ2Zsb2F0JyxcbiAgLy8gVGhpcyB3aWxsIG5vdCBwYXNzIGZvciBJRTksIGJlY2F1c2UgSUU5IERuRCBvbmx5IHdvcmtzIG9uIGFuY2hvcnNcbiAgc3VwcG9ydERyYWdnYWJsZSA9IGRvY3VtZW50RXhpc3RzICYmICFDaHJvbWVGb3JBbmRyb2lkICYmICFJT1MgJiYgJ2RyYWdnYWJsZScgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2JyksXG4gIHN1cHBvcnRDc3NQb2ludGVyRXZlbnRzID0gZnVuY3Rpb24gKCkge1xuICAgIGlmICghZG9jdW1lbnRFeGlzdHMpIHJldHVybjtcbiAgICAvLyBmYWxzZSB3aGVuIDw9IElFMTFcbiAgICBpZiAoSUUxMU9yTGVzcykge1xuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbiAgICB2YXIgZWwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCd4Jyk7XG4gICAgZWwuc3R5bGUuY3NzVGV4dCA9ICdwb2ludGVyLWV2ZW50czphdXRvJztcbiAgICByZXR1cm4gZWwuc3R5bGUucG9pbnRlckV2ZW50cyA9PT0gJ2F1dG8nO1xuICB9KCksXG4gIF9kZXRlY3REaXJlY3Rpb24gPSBmdW5jdGlvbiBfZGV0ZWN0RGlyZWN0aW9uKGVsLCBvcHRpb25zKSB7XG4gICAgdmFyIGVsQ1NTID0gY3NzKGVsKSxcbiAgICAgIGVsV2lkdGggPSBwYXJzZUludChlbENTUy53aWR0aCkgLSBwYXJzZUludChlbENTUy5wYWRkaW5nTGVmdCkgLSBwYXJzZUludChlbENTUy5wYWRkaW5nUmlnaHQpIC0gcGFyc2VJbnQoZWxDU1MuYm9yZGVyTGVmdFdpZHRoKSAtIHBhcnNlSW50KGVsQ1NTLmJvcmRlclJpZ2h0V2lkdGgpLFxuICAgICAgY2hpbGQxID0gZ2V0Q2hpbGQoZWwsIDAsIG9wdGlvbnMpLFxuICAgICAgY2hpbGQyID0gZ2V0Q2hpbGQoZWwsIDEsIG9wdGlvbnMpLFxuICAgICAgZmlyc3RDaGlsZENTUyA9IGNoaWxkMSAmJiBjc3MoY2hpbGQxKSxcbiAgICAgIHNlY29uZENoaWxkQ1NTID0gY2hpbGQyICYmIGNzcyhjaGlsZDIpLFxuICAgICAgZmlyc3RDaGlsZFdpZHRoID0gZmlyc3RDaGlsZENTUyAmJiBwYXJzZUludChmaXJzdENoaWxkQ1NTLm1hcmdpbkxlZnQpICsgcGFyc2VJbnQoZmlyc3RDaGlsZENTUy5tYXJnaW5SaWdodCkgKyBnZXRSZWN0KGNoaWxkMSkud2lkdGgsXG4gICAgICBzZWNvbmRDaGlsZFdpZHRoID0gc2Vjb25kQ2hpbGRDU1MgJiYgcGFyc2VJbnQoc2Vjb25kQ2hpbGRDU1MubWFyZ2luTGVmdCkgKyBwYXJzZUludChzZWNvbmRDaGlsZENTUy5tYXJnaW5SaWdodCkgKyBnZXRSZWN0KGNoaWxkMikud2lkdGg7XG4gICAgaWYgKGVsQ1NTLmRpc3BsYXkgPT09ICdmbGV4Jykge1xuICAgICAgcmV0dXJuIGVsQ1NTLmZsZXhEaXJlY3Rpb24gPT09ICdjb2x1bW4nIHx8IGVsQ1NTLmZsZXhEaXJlY3Rpb24gPT09ICdjb2x1bW4tcmV2ZXJzZScgPyAndmVydGljYWwnIDogJ2hvcml6b250YWwnO1xuICAgIH1cbiAgICBpZiAoZWxDU1MuZGlzcGxheSA9PT0gJ2dyaWQnKSB7XG4gICAgICByZXR1cm4gZWxDU1MuZ3JpZFRlbXBsYXRlQ29sdW1ucy5zcGxpdCgnICcpLmxlbmd0aCA8PSAxID8gJ3ZlcnRpY2FsJyA6ICdob3Jpem9udGFsJztcbiAgICB9XG4gICAgaWYgKGNoaWxkMSAmJiBmaXJzdENoaWxkQ1NTW1wiZmxvYXRcIl0gJiYgZmlyc3RDaGlsZENTU1tcImZsb2F0XCJdICE9PSAnbm9uZScpIHtcbiAgICAgIHZhciB0b3VjaGluZ1NpZGVDaGlsZDIgPSBmaXJzdENoaWxkQ1NTW1wiZmxvYXRcIl0gPT09ICdsZWZ0JyA/ICdsZWZ0JyA6ICdyaWdodCc7XG4gICAgICByZXR1cm4gY2hpbGQyICYmIChzZWNvbmRDaGlsZENTUy5jbGVhciA9PT0gJ2JvdGgnIHx8IHNlY29uZENoaWxkQ1NTLmNsZWFyID09PSB0b3VjaGluZ1NpZGVDaGlsZDIpID8gJ3ZlcnRpY2FsJyA6ICdob3Jpem9udGFsJztcbiAgICB9XG4gICAgcmV0dXJuIGNoaWxkMSAmJiAoZmlyc3RDaGlsZENTUy5kaXNwbGF5ID09PSAnYmxvY2snIHx8IGZpcnN0Q2hpbGRDU1MuZGlzcGxheSA9PT0gJ2ZsZXgnIHx8IGZpcnN0Q2hpbGRDU1MuZGlzcGxheSA9PT0gJ3RhYmxlJyB8fCBmaXJzdENoaWxkQ1NTLmRpc3BsYXkgPT09ICdncmlkJyB8fCBmaXJzdENoaWxkV2lkdGggPj0gZWxXaWR0aCAmJiBlbENTU1tDU1NGbG9hdFByb3BlcnR5XSA9PT0gJ25vbmUnIHx8IGNoaWxkMiAmJiBlbENTU1tDU1NGbG9hdFByb3BlcnR5XSA9PT0gJ25vbmUnICYmIGZpcnN0Q2hpbGRXaWR0aCArIHNlY29uZENoaWxkV2lkdGggPiBlbFdpZHRoKSA/ICd2ZXJ0aWNhbCcgOiAnaG9yaXpvbnRhbCc7XG4gIH0sXG4gIF9kcmFnRWxJblJvd0NvbHVtbiA9IGZ1bmN0aW9uIF9kcmFnRWxJblJvd0NvbHVtbihkcmFnUmVjdCwgdGFyZ2V0UmVjdCwgdmVydGljYWwpIHtcbiAgICB2YXIgZHJhZ0VsUzFPcHAgPSB2ZXJ0aWNhbCA/IGRyYWdSZWN0LmxlZnQgOiBkcmFnUmVjdC50b3AsXG4gICAgICBkcmFnRWxTMk9wcCA9IHZlcnRpY2FsID8gZHJhZ1JlY3QucmlnaHQgOiBkcmFnUmVjdC5ib3R0b20sXG4gICAgICBkcmFnRWxPcHBMZW5ndGggPSB2ZXJ0aWNhbCA/IGRyYWdSZWN0LndpZHRoIDogZHJhZ1JlY3QuaGVpZ2h0LFxuICAgICAgdGFyZ2V0UzFPcHAgPSB2ZXJ0aWNhbCA/IHRhcmdldFJlY3QubGVmdCA6IHRhcmdldFJlY3QudG9wLFxuICAgICAgdGFyZ2V0UzJPcHAgPSB2ZXJ0aWNhbCA/IHRhcmdldFJlY3QucmlnaHQgOiB0YXJnZXRSZWN0LmJvdHRvbSxcbiAgICAgIHRhcmdldE9wcExlbmd0aCA9IHZlcnRpY2FsID8gdGFyZ2V0UmVjdC53aWR0aCA6IHRhcmdldFJlY3QuaGVpZ2h0O1xuICAgIHJldHVybiBkcmFnRWxTMU9wcCA9PT0gdGFyZ2V0UzFPcHAgfHwgZHJhZ0VsUzJPcHAgPT09IHRhcmdldFMyT3BwIHx8IGRyYWdFbFMxT3BwICsgZHJhZ0VsT3BwTGVuZ3RoIC8gMiA9PT0gdGFyZ2V0UzFPcHAgKyB0YXJnZXRPcHBMZW5ndGggLyAyO1xuICB9LFxuICAvKipcclxuICAgKiBEZXRlY3RzIGZpcnN0IG5lYXJlc3QgZW1wdHkgc29ydGFibGUgdG8gWCBhbmQgWSBwb3NpdGlvbiB1c2luZyBlbXB0eUluc2VydFRocmVzaG9sZC5cclxuICAgKiBAcGFyYW0gIHtOdW1iZXJ9IHggICAgICBYIHBvc2l0aW9uXHJcbiAgICogQHBhcmFtICB7TnVtYmVyfSB5ICAgICAgWSBwb3NpdGlvblxyXG4gICAqIEByZXR1cm4ge0hUTUxFbGVtZW50fSAgIEVsZW1lbnQgb2YgdGhlIGZpcnN0IGZvdW5kIG5lYXJlc3QgU29ydGFibGVcclxuICAgKi9cbiAgX2RldGVjdE5lYXJlc3RFbXB0eVNvcnRhYmxlID0gZnVuY3Rpb24gX2RldGVjdE5lYXJlc3RFbXB0eVNvcnRhYmxlKHgsIHkpIHtcbiAgICB2YXIgcmV0O1xuICAgIHNvcnRhYmxlcy5zb21lKGZ1bmN0aW9uIChzb3J0YWJsZSkge1xuICAgICAgdmFyIHRocmVzaG9sZCA9IHNvcnRhYmxlW2V4cGFuZG9dLm9wdGlvbnMuZW1wdHlJbnNlcnRUaHJlc2hvbGQ7XG4gICAgICBpZiAoIXRocmVzaG9sZCB8fCBsYXN0Q2hpbGQoc29ydGFibGUpKSByZXR1cm47XG4gICAgICB2YXIgcmVjdCA9IGdldFJlY3Qoc29ydGFibGUpLFxuICAgICAgICBpbnNpZGVIb3Jpem9udGFsbHkgPSB4ID49IHJlY3QubGVmdCAtIHRocmVzaG9sZCAmJiB4IDw9IHJlY3QucmlnaHQgKyB0aHJlc2hvbGQsXG4gICAgICAgIGluc2lkZVZlcnRpY2FsbHkgPSB5ID49IHJlY3QudG9wIC0gdGhyZXNob2xkICYmIHkgPD0gcmVjdC5ib3R0b20gKyB0aHJlc2hvbGQ7XG4gICAgICBpZiAoaW5zaWRlSG9yaXpvbnRhbGx5ICYmIGluc2lkZVZlcnRpY2FsbHkpIHtcbiAgICAgICAgcmV0dXJuIHJldCA9IHNvcnRhYmxlO1xuICAgICAgfVxuICAgIH0pO1xuICAgIHJldHVybiByZXQ7XG4gIH0sXG4gIF9wcmVwYXJlR3JvdXAgPSBmdW5jdGlvbiBfcHJlcGFyZUdyb3VwKG9wdGlvbnMpIHtcbiAgICBmdW5jdGlvbiB0b0ZuKHZhbHVlLCBwdWxsKSB7XG4gICAgICByZXR1cm4gZnVuY3Rpb24gKHRvLCBmcm9tLCBkcmFnRWwsIGV2dCkge1xuICAgICAgICB2YXIgc2FtZUdyb3VwID0gdG8ub3B0aW9ucy5ncm91cC5uYW1lICYmIGZyb20ub3B0aW9ucy5ncm91cC5uYW1lICYmIHRvLm9wdGlvbnMuZ3JvdXAubmFtZSA9PT0gZnJvbS5vcHRpb25zLmdyb3VwLm5hbWU7XG4gICAgICAgIGlmICh2YWx1ZSA9PSBudWxsICYmIChwdWxsIHx8IHNhbWVHcm91cCkpIHtcbiAgICAgICAgICAvLyBEZWZhdWx0IHB1bGwgdmFsdWVcbiAgICAgICAgICAvLyBEZWZhdWx0IHB1bGwgYW5kIHB1dCB2YWx1ZSBpZiBzYW1lIGdyb3VwXG4gICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH0gZWxzZSBpZiAodmFsdWUgPT0gbnVsbCB8fCB2YWx1ZSA9PT0gZmFsc2UpIHtcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH0gZWxzZSBpZiAocHVsbCAmJiB2YWx1ZSA9PT0gJ2Nsb25lJykge1xuICAgICAgICAgIHJldHVybiB2YWx1ZTtcbiAgICAgICAgfSBlbHNlIGlmICh0eXBlb2YgdmFsdWUgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICByZXR1cm4gdG9Gbih2YWx1ZSh0bywgZnJvbSwgZHJhZ0VsLCBldnQpLCBwdWxsKSh0bywgZnJvbSwgZHJhZ0VsLCBldnQpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHZhciBvdGhlckdyb3VwID0gKHB1bGwgPyB0byA6IGZyb20pLm9wdGlvbnMuZ3JvdXAubmFtZTtcbiAgICAgICAgICByZXR1cm4gdmFsdWUgPT09IHRydWUgfHwgdHlwZW9mIHZhbHVlID09PSAnc3RyaW5nJyAmJiB2YWx1ZSA9PT0gb3RoZXJHcm91cCB8fCB2YWx1ZS5qb2luICYmIHZhbHVlLmluZGV4T2Yob3RoZXJHcm91cCkgPiAtMTtcbiAgICAgICAgfVxuICAgICAgfTtcbiAgICB9XG4gICAgdmFyIGdyb3VwID0ge307XG4gICAgdmFyIG9yaWdpbmFsR3JvdXAgPSBvcHRpb25zLmdyb3VwO1xuICAgIGlmICghb3JpZ2luYWxHcm91cCB8fCBfdHlwZW9mKG9yaWdpbmFsR3JvdXApICE9ICdvYmplY3QnKSB7XG4gICAgICBvcmlnaW5hbEdyb3VwID0ge1xuICAgICAgICBuYW1lOiBvcmlnaW5hbEdyb3VwXG4gICAgICB9O1xuICAgIH1cbiAgICBncm91cC5uYW1lID0gb3JpZ2luYWxHcm91cC5uYW1lO1xuICAgIGdyb3VwLmNoZWNrUHVsbCA9IHRvRm4ob3JpZ2luYWxHcm91cC5wdWxsLCB0cnVlKTtcbiAgICBncm91cC5jaGVja1B1dCA9IHRvRm4ob3JpZ2luYWxHcm91cC5wdXQpO1xuICAgIGdyb3VwLnJldmVydENsb25lID0gb3JpZ2luYWxHcm91cC5yZXZlcnRDbG9uZTtcbiAgICBvcHRpb25zLmdyb3VwID0gZ3JvdXA7XG4gIH0sXG4gIF9oaWRlR2hvc3RGb3JUYXJnZXQgPSBmdW5jdGlvbiBfaGlkZUdob3N0Rm9yVGFyZ2V0KCkge1xuICAgIGlmICghc3VwcG9ydENzc1BvaW50ZXJFdmVudHMgJiYgZ2hvc3RFbCkge1xuICAgICAgY3NzKGdob3N0RWwsICdkaXNwbGF5JywgJ25vbmUnKTtcbiAgICB9XG4gIH0sXG4gIF91bmhpZGVHaG9zdEZvclRhcmdldCA9IGZ1bmN0aW9uIF91bmhpZGVHaG9zdEZvclRhcmdldCgpIHtcbiAgICBpZiAoIXN1cHBvcnRDc3NQb2ludGVyRXZlbnRzICYmIGdob3N0RWwpIHtcbiAgICAgIGNzcyhnaG9zdEVsLCAnZGlzcGxheScsICcnKTtcbiAgICB9XG4gIH07XG5cbi8vICMxMTg0IGZpeCAtIFByZXZlbnQgY2xpY2sgZXZlbnQgb24gZmFsbGJhY2sgaWYgZHJhZ2dlZCBidXQgaXRlbSBub3QgY2hhbmdlZCBwb3NpdGlvblxuaWYgKGRvY3VtZW50RXhpc3RzICYmICFDaHJvbWVGb3JBbmRyb2lkKSB7XG4gIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKGV2dCkge1xuICAgIGlmIChpZ25vcmVOZXh0Q2xpY2spIHtcbiAgICAgIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgZXZ0LnN0b3BQcm9wYWdhdGlvbiAmJiBldnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICBldnQuc3RvcEltbWVkaWF0ZVByb3BhZ2F0aW9uICYmIGV2dC5zdG9wSW1tZWRpYXRlUHJvcGFnYXRpb24oKTtcbiAgICAgIGlnbm9yZU5leHRDbGljayA9IGZhbHNlO1xuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cbiAgfSwgdHJ1ZSk7XG59XG52YXIgbmVhcmVzdEVtcHR5SW5zZXJ0RGV0ZWN0RXZlbnQgPSBmdW5jdGlvbiBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudChldnQpIHtcbiAgaWYgKGRyYWdFbCkge1xuICAgIGV2dCA9IGV2dC50b3VjaGVzID8gZXZ0LnRvdWNoZXNbMF0gOiBldnQ7XG4gICAgdmFyIG5lYXJlc3QgPSBfZGV0ZWN0TmVhcmVzdEVtcHR5U29ydGFibGUoZXZ0LmNsaWVudFgsIGV2dC5jbGllbnRZKTtcbiAgICBpZiAobmVhcmVzdCkge1xuICAgICAgLy8gQ3JlYXRlIGltaXRhdGlvbiBldmVudFxuICAgICAgdmFyIGV2ZW50ID0ge307XG4gICAgICBmb3IgKHZhciBpIGluIGV2dCkge1xuICAgICAgICBpZiAoZXZ0Lmhhc093blByb3BlcnR5KGkpKSB7XG4gICAgICAgICAgZXZlbnRbaV0gPSBldnRbaV07XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIGV2ZW50LnRhcmdldCA9IGV2ZW50LnJvb3RFbCA9IG5lYXJlc3Q7XG4gICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCA9IHZvaWQgMDtcbiAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbiA9IHZvaWQgMDtcbiAgICAgIG5lYXJlc3RbZXhwYW5kb10uX29uRHJhZ092ZXIoZXZlbnQpO1xuICAgIH1cbiAgfVxufTtcbnZhciBfY2hlY2tPdXRzaWRlVGFyZ2V0RWwgPSBmdW5jdGlvbiBfY2hlY2tPdXRzaWRlVGFyZ2V0RWwoZXZ0KSB7XG4gIGlmIChkcmFnRWwpIHtcbiAgICBkcmFnRWwucGFyZW50Tm9kZVtleHBhbmRvXS5faXNPdXRzaWRlVGhpc0VsKGV2dC50YXJnZXQpO1xuICB9XG59O1xuXG4vKipcclxuICogQGNsYXNzICBTb3J0YWJsZVxyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gIGVsXHJcbiAqIEBwYXJhbSAge09iamVjdH0gICAgICAgW29wdGlvbnNdXHJcbiAqL1xuZnVuY3Rpb24gU29ydGFibGUoZWwsIG9wdGlvbnMpIHtcbiAgaWYgKCEoZWwgJiYgZWwubm9kZVR5cGUgJiYgZWwubm9kZVR5cGUgPT09IDEpKSB7XG4gICAgdGhyb3cgXCJTb3J0YWJsZTogYGVsYCBtdXN0IGJlIGFuIEhUTUxFbGVtZW50LCBub3QgXCIuY29uY2F0KHt9LnRvU3RyaW5nLmNhbGwoZWwpKTtcbiAgfVxuICB0aGlzLmVsID0gZWw7IC8vIHJvb3QgZWxlbWVudFxuICB0aGlzLm9wdGlvbnMgPSBvcHRpb25zID0gX2V4dGVuZHMoe30sIG9wdGlvbnMpO1xuXG4gIC8vIEV4cG9ydCBpbnN0YW5jZVxuICBlbFtleHBhbmRvXSA9IHRoaXM7XG4gIHZhciBkZWZhdWx0cyA9IHtcbiAgICBncm91cDogbnVsbCxcbiAgICBzb3J0OiB0cnVlLFxuICAgIGRpc2FibGVkOiBmYWxzZSxcbiAgICBzdG9yZTogbnVsbCxcbiAgICBoYW5kbGU6IG51bGwsXG4gICAgZHJhZ2dhYmxlOiAvXlt1b11sJC9pLnRlc3QoZWwubm9kZU5hbWUpID8gJz5saScgOiAnPionLFxuICAgIHN3YXBUaHJlc2hvbGQ6IDEsXG4gICAgLy8gcGVyY2VudGFnZTsgMCA8PSB4IDw9IDFcbiAgICBpbnZlcnRTd2FwOiBmYWxzZSxcbiAgICAvLyBpbnZlcnQgYWx3YXlzXG4gICAgaW52ZXJ0ZWRTd2FwVGhyZXNob2xkOiBudWxsLFxuICAgIC8vIHdpbGwgYmUgc2V0IHRvIHNhbWUgYXMgc3dhcFRocmVzaG9sZCBpZiBkZWZhdWx0XG4gICAgcmVtb3ZlQ2xvbmVPbkhpZGU6IHRydWUsXG4gICAgZGlyZWN0aW9uOiBmdW5jdGlvbiBkaXJlY3Rpb24oKSB7XG4gICAgICByZXR1cm4gX2RldGVjdERpcmVjdGlvbihlbCwgdGhpcy5vcHRpb25zKTtcbiAgICB9LFxuICAgIGdob3N0Q2xhc3M6ICdzb3J0YWJsZS1naG9zdCcsXG4gICAgY2hvc2VuQ2xhc3M6ICdzb3J0YWJsZS1jaG9zZW4nLFxuICAgIGRyYWdDbGFzczogJ3NvcnRhYmxlLWRyYWcnLFxuICAgIGlnbm9yZTogJ2EsIGltZycsXG4gICAgZmlsdGVyOiBudWxsLFxuICAgIHByZXZlbnRPbkZpbHRlcjogdHJ1ZSxcbiAgICBhbmltYXRpb246IDAsXG4gICAgZWFzaW5nOiBudWxsLFxuICAgIHNldERhdGE6IGZ1bmN0aW9uIHNldERhdGEoZGF0YVRyYW5zZmVyLCBkcmFnRWwpIHtcbiAgICAgIGRhdGFUcmFuc2Zlci5zZXREYXRhKCdUZXh0JywgZHJhZ0VsLnRleHRDb250ZW50KTtcbiAgICB9LFxuICAgIGRyb3BCdWJibGU6IGZhbHNlLFxuICAgIGRyYWdvdmVyQnViYmxlOiBmYWxzZSxcbiAgICBkYXRhSWRBdHRyOiAnZGF0YS1pZCcsXG4gICAgZGVsYXk6IDAsXG4gICAgZGVsYXlPblRvdWNoT25seTogZmFsc2UsXG4gICAgdG91Y2hTdGFydFRocmVzaG9sZDogKE51bWJlci5wYXJzZUludCA/IE51bWJlciA6IHdpbmRvdykucGFyc2VJbnQod2luZG93LmRldmljZVBpeGVsUmF0aW8sIDEwKSB8fCAxLFxuICAgIGZvcmNlRmFsbGJhY2s6IGZhbHNlLFxuICAgIGZhbGxiYWNrQ2xhc3M6ICdzb3J0YWJsZS1mYWxsYmFjaycsXG4gICAgZmFsbGJhY2tPbkJvZHk6IGZhbHNlLFxuICAgIGZhbGxiYWNrVG9sZXJhbmNlOiAwLFxuICAgIGZhbGxiYWNrT2Zmc2V0OiB7XG4gICAgICB4OiAwLFxuICAgICAgeTogMFxuICAgIH0sXG4gICAgc3VwcG9ydFBvaW50ZXI6IFNvcnRhYmxlLnN1cHBvcnRQb2ludGVyICE9PSBmYWxzZSAmJiAnUG9pbnRlckV2ZW50JyBpbiB3aW5kb3cgJiYgIVNhZmFyaSxcbiAgICBlbXB0eUluc2VydFRocmVzaG9sZDogNVxuICB9O1xuICBQbHVnaW5NYW5hZ2VyLmluaXRpYWxpemVQbHVnaW5zKHRoaXMsIGVsLCBkZWZhdWx0cyk7XG5cbiAgLy8gU2V0IGRlZmF1bHQgb3B0aW9uc1xuICBmb3IgKHZhciBuYW1lIGluIGRlZmF1bHRzKSB7XG4gICAgIShuYW1lIGluIG9wdGlvbnMpICYmIChvcHRpb25zW25hbWVdID0gZGVmYXVsdHNbbmFtZV0pO1xuICB9XG4gIF9wcmVwYXJlR3JvdXAob3B0aW9ucyk7XG5cbiAgLy8gQmluZCBhbGwgcHJpdmF0ZSBtZXRob2RzXG4gIGZvciAodmFyIGZuIGluIHRoaXMpIHtcbiAgICBpZiAoZm4uY2hhckF0KDApID09PSAnXycgJiYgdHlwZW9mIHRoaXNbZm5dID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICB0aGlzW2ZuXSA9IHRoaXNbZm5dLmJpbmQodGhpcyk7XG4gICAgfVxuICB9XG5cbiAgLy8gU2V0dXAgZHJhZyBtb2RlXG4gIHRoaXMubmF0aXZlRHJhZ2dhYmxlID0gb3B0aW9ucy5mb3JjZUZhbGxiYWNrID8gZmFsc2UgOiBzdXBwb3J0RHJhZ2dhYmxlO1xuICBpZiAodGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAvLyBUb3VjaCBzdGFydCB0aHJlc2hvbGQgY2Fubm90IGJlIGdyZWF0ZXIgdGhhbiB0aGUgbmF0aXZlIGRyYWdzdGFydCB0aHJlc2hvbGRcbiAgICB0aGlzLm9wdGlvbnMudG91Y2hTdGFydFRocmVzaG9sZCA9IDE7XG4gIH1cblxuICAvLyBCaW5kIGV2ZW50c1xuICBpZiAob3B0aW9ucy5zdXBwb3J0UG9pbnRlcikge1xuICAgIG9uKGVsLCAncG9pbnRlcmRvd24nLCB0aGlzLl9vblRhcFN0YXJ0KTtcbiAgfSBlbHNlIHtcbiAgICBvbihlbCwgJ21vdXNlZG93bicsIHRoaXMuX29uVGFwU3RhcnQpO1xuICAgIG9uKGVsLCAndG91Y2hzdGFydCcsIHRoaXMuX29uVGFwU3RhcnQpO1xuICB9XG4gIGlmICh0aGlzLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgIG9uKGVsLCAnZHJhZ292ZXInLCB0aGlzKTtcbiAgICBvbihlbCwgJ2RyYWdlbnRlcicsIHRoaXMpO1xuICB9XG4gIHNvcnRhYmxlcy5wdXNoKHRoaXMuZWwpO1xuXG4gIC8vIFJlc3RvcmUgc29ydGluZ1xuICBvcHRpb25zLnN0b3JlICYmIG9wdGlvbnMuc3RvcmUuZ2V0ICYmIHRoaXMuc29ydChvcHRpb25zLnN0b3JlLmdldCh0aGlzKSB8fCBbXSk7XG5cbiAgLy8gQWRkIGFuaW1hdGlvbiBzdGF0ZSBtYW5hZ2VyXG4gIF9leHRlbmRzKHRoaXMsIEFuaW1hdGlvblN0YXRlTWFuYWdlcigpKTtcbn1cblNvcnRhYmxlLnByb3RvdHlwZSA9IC8qKiBAbGVuZHMgU29ydGFibGUucHJvdG90eXBlICove1xuICBjb25zdHJ1Y3RvcjogU29ydGFibGUsXG4gIF9pc091dHNpZGVUaGlzRWw6IGZ1bmN0aW9uIF9pc091dHNpZGVUaGlzRWwodGFyZ2V0KSB7XG4gICAgaWYgKCF0aGlzLmVsLmNvbnRhaW5zKHRhcmdldCkgJiYgdGFyZ2V0ICE9PSB0aGlzLmVsKSB7XG4gICAgICBsYXN0VGFyZ2V0ID0gbnVsbDtcbiAgICB9XG4gIH0sXG4gIF9nZXREaXJlY3Rpb246IGZ1bmN0aW9uIF9nZXREaXJlY3Rpb24oZXZ0LCB0YXJnZXQpIHtcbiAgICByZXR1cm4gdHlwZW9mIHRoaXMub3B0aW9ucy5kaXJlY3Rpb24gPT09ICdmdW5jdGlvbicgPyB0aGlzLm9wdGlvbnMuZGlyZWN0aW9uLmNhbGwodGhpcywgZXZ0LCB0YXJnZXQsIGRyYWdFbCkgOiB0aGlzLm9wdGlvbnMuZGlyZWN0aW9uO1xuICB9LFxuICBfb25UYXBTdGFydDogZnVuY3Rpb24gX29uVGFwU3RhcnQoIC8qKiBFdmVudHxUb3VjaEV2ZW50ICovZXZ0KSB7XG4gICAgaWYgKCFldnQuY2FuY2VsYWJsZSkgcmV0dXJuO1xuICAgIHZhciBfdGhpcyA9IHRoaXMsXG4gICAgICBlbCA9IHRoaXMuZWwsXG4gICAgICBvcHRpb25zID0gdGhpcy5vcHRpb25zLFxuICAgICAgcHJldmVudE9uRmlsdGVyID0gb3B0aW9ucy5wcmV2ZW50T25GaWx0ZXIsXG4gICAgICB0eXBlID0gZXZ0LnR5cGUsXG4gICAgICB0b3VjaCA9IGV2dC50b3VjaGVzICYmIGV2dC50b3VjaGVzWzBdIHx8IGV2dC5wb2ludGVyVHlwZSAmJiBldnQucG9pbnRlclR5cGUgPT09ICd0b3VjaCcgJiYgZXZ0LFxuICAgICAgdGFyZ2V0ID0gKHRvdWNoIHx8IGV2dCkudGFyZ2V0LFxuICAgICAgb3JpZ2luYWxUYXJnZXQgPSBldnQudGFyZ2V0LnNoYWRvd1Jvb3QgJiYgKGV2dC5wYXRoICYmIGV2dC5wYXRoWzBdIHx8IGV2dC5jb21wb3NlZFBhdGggJiYgZXZ0LmNvbXBvc2VkUGF0aCgpWzBdKSB8fCB0YXJnZXQsXG4gICAgICBmaWx0ZXIgPSBvcHRpb25zLmZpbHRlcjtcbiAgICBfc2F2ZUlucHV0Q2hlY2tlZFN0YXRlKGVsKTtcblxuICAgIC8vIERvbid0IHRyaWdnZXIgc3RhcnQgZXZlbnQgd2hlbiBhbiBlbGVtZW50IGlzIGJlZW4gZHJhZ2dlZCwgb3RoZXJ3aXNlIHRoZSBldnQub2xkaW5kZXggYWx3YXlzIHdyb25nIHdoZW4gc2V0IG9wdGlvbi5ncm91cC5cbiAgICBpZiAoZHJhZ0VsKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIGlmICgvbW91c2Vkb3dufHBvaW50ZXJkb3duLy50ZXN0KHR5cGUpICYmIGV2dC5idXR0b24gIT09IDAgfHwgb3B0aW9ucy5kaXNhYmxlZCkge1xuICAgICAgcmV0dXJuOyAvLyBvbmx5IGxlZnQgYnV0dG9uIGFuZCBlbmFibGVkXG4gICAgfVxuXG4gICAgLy8gY2FuY2VsIGRuZCBpZiBvcmlnaW5hbCB0YXJnZXQgaXMgY29udGVudCBlZGl0YWJsZVxuICAgIGlmIChvcmlnaW5hbFRhcmdldC5pc0NvbnRlbnRFZGl0YWJsZSkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIC8vIFNhZmFyaSBpZ25vcmVzIGZ1cnRoZXIgZXZlbnQgaGFuZGxpbmcgYWZ0ZXIgbW91c2Vkb3duXG4gICAgaWYgKCF0aGlzLm5hdGl2ZURyYWdnYWJsZSAmJiBTYWZhcmkgJiYgdGFyZ2V0ICYmIHRhcmdldC50YWdOYW1lLnRvVXBwZXJDYXNlKCkgPT09ICdTRUxFQ1QnKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIHRhcmdldCA9IGNsb3Nlc3QodGFyZ2V0LCBvcHRpb25zLmRyYWdnYWJsZSwgZWwsIGZhbHNlKTtcbiAgICBpZiAodGFyZ2V0ICYmIHRhcmdldC5hbmltYXRlZCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBpZiAobGFzdERvd25FbCA9PT0gdGFyZ2V0KSB7XG4gICAgICAvLyBJZ25vcmluZyBkdXBsaWNhdGUgYGRvd25gXG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgLy8gR2V0IHRoZSBpbmRleCBvZiB0aGUgZHJhZ2dlZCBlbGVtZW50IHdpdGhpbiBpdHMgcGFyZW50XG4gICAgb2xkSW5kZXggPSBpbmRleCh0YXJnZXQpO1xuICAgIG9sZERyYWdnYWJsZUluZGV4ID0gaW5kZXgodGFyZ2V0LCBvcHRpb25zLmRyYWdnYWJsZSk7XG5cbiAgICAvLyBDaGVjayBmaWx0ZXJcbiAgICBpZiAodHlwZW9mIGZpbHRlciA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgaWYgKGZpbHRlci5jYWxsKHRoaXMsIGV2dCwgdGFyZ2V0LCB0aGlzKSkge1xuICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgc29ydGFibGU6IF90aGlzLFxuICAgICAgICAgIHJvb3RFbDogb3JpZ2luYWxUYXJnZXQsXG4gICAgICAgICAgbmFtZTogJ2ZpbHRlcicsXG4gICAgICAgICAgdGFyZ2V0RWw6IHRhcmdldCxcbiAgICAgICAgICB0b0VsOiBlbCxcbiAgICAgICAgICBmcm9tRWw6IGVsXG4gICAgICAgIH0pO1xuICAgICAgICBwbHVnaW5FdmVudCgnZmlsdGVyJywgX3RoaXMsIHtcbiAgICAgICAgICBldnQ6IGV2dFxuICAgICAgICB9KTtcbiAgICAgICAgcHJldmVudE9uRmlsdGVyICYmIGV2dC5jYW5jZWxhYmxlICYmIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICByZXR1cm47IC8vIGNhbmNlbCBkbmRcbiAgICAgIH1cbiAgICB9IGVsc2UgaWYgKGZpbHRlcikge1xuICAgICAgZmlsdGVyID0gZmlsdGVyLnNwbGl0KCcsJykuc29tZShmdW5jdGlvbiAoY3JpdGVyaWEpIHtcbiAgICAgICAgY3JpdGVyaWEgPSBjbG9zZXN0KG9yaWdpbmFsVGFyZ2V0LCBjcml0ZXJpYS50cmltKCksIGVsLCBmYWxzZSk7XG4gICAgICAgIGlmIChjcml0ZXJpYSkge1xuICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgIHNvcnRhYmxlOiBfdGhpcyxcbiAgICAgICAgICAgIHJvb3RFbDogY3JpdGVyaWEsXG4gICAgICAgICAgICBuYW1lOiAnZmlsdGVyJyxcbiAgICAgICAgICAgIHRhcmdldEVsOiB0YXJnZXQsXG4gICAgICAgICAgICBmcm9tRWw6IGVsLFxuICAgICAgICAgICAgdG9FbDogZWxcbiAgICAgICAgICB9KTtcbiAgICAgICAgICBwbHVnaW5FdmVudCgnZmlsdGVyJywgX3RoaXMsIHtcbiAgICAgICAgICAgIGV2dDogZXZ0XG4gICAgICAgICAgfSk7XG4gICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgICAgaWYgKGZpbHRlcikge1xuICAgICAgICBwcmV2ZW50T25GaWx0ZXIgJiYgZXZ0LmNhbmNlbGFibGUgJiYgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHJldHVybjsgLy8gY2FuY2VsIGRuZFxuICAgICAgfVxuICAgIH1cbiAgICBpZiAob3B0aW9ucy5oYW5kbGUgJiYgIWNsb3Nlc3Qob3JpZ2luYWxUYXJnZXQsIG9wdGlvbnMuaGFuZGxlLCBlbCwgZmFsc2UpKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgLy8gUHJlcGFyZSBgZHJhZ3N0YXJ0YFxuICAgIHRoaXMuX3ByZXBhcmVEcmFnU3RhcnQoZXZ0LCB0b3VjaCwgdGFyZ2V0KTtcbiAgfSxcbiAgX3ByZXBhcmVEcmFnU3RhcnQ6IGZ1bmN0aW9uIF9wcmVwYXJlRHJhZ1N0YXJ0KCAvKiogRXZlbnQgKi9ldnQsIC8qKiBUb3VjaCAqL3RvdWNoLCAvKiogSFRNTEVsZW1lbnQgKi90YXJnZXQpIHtcbiAgICB2YXIgX3RoaXMgPSB0aGlzLFxuICAgICAgZWwgPSBfdGhpcy5lbCxcbiAgICAgIG9wdGlvbnMgPSBfdGhpcy5vcHRpb25zLFxuICAgICAgb3duZXJEb2N1bWVudCA9IGVsLm93bmVyRG9jdW1lbnQsXG4gICAgICBkcmFnU3RhcnRGbjtcbiAgICBpZiAodGFyZ2V0ICYmICFkcmFnRWwgJiYgdGFyZ2V0LnBhcmVudE5vZGUgPT09IGVsKSB7XG4gICAgICB2YXIgZHJhZ1JlY3QgPSBnZXRSZWN0KHRhcmdldCk7XG4gICAgICByb290RWwgPSBlbDtcbiAgICAgIGRyYWdFbCA9IHRhcmdldDtcbiAgICAgIHBhcmVudEVsID0gZHJhZ0VsLnBhcmVudE5vZGU7XG4gICAgICBuZXh0RWwgPSBkcmFnRWwubmV4dFNpYmxpbmc7XG4gICAgICBsYXN0RG93bkVsID0gdGFyZ2V0O1xuICAgICAgYWN0aXZlR3JvdXAgPSBvcHRpb25zLmdyb3VwO1xuICAgICAgU29ydGFibGUuZHJhZ2dlZCA9IGRyYWdFbDtcbiAgICAgIHRhcEV2dCA9IHtcbiAgICAgICAgdGFyZ2V0OiBkcmFnRWwsXG4gICAgICAgIGNsaWVudFg6ICh0b3VjaCB8fCBldnQpLmNsaWVudFgsXG4gICAgICAgIGNsaWVudFk6ICh0b3VjaCB8fCBldnQpLmNsaWVudFlcbiAgICAgIH07XG4gICAgICB0YXBEaXN0YW5jZUxlZnQgPSB0YXBFdnQuY2xpZW50WCAtIGRyYWdSZWN0LmxlZnQ7XG4gICAgICB0YXBEaXN0YW5jZVRvcCA9IHRhcEV2dC5jbGllbnRZIC0gZHJhZ1JlY3QudG9wO1xuICAgICAgdGhpcy5fbGFzdFggPSAodG91Y2ggfHwgZXZ0KS5jbGllbnRYO1xuICAgICAgdGhpcy5fbGFzdFkgPSAodG91Y2ggfHwgZXZ0KS5jbGllbnRZO1xuICAgICAgZHJhZ0VsLnN0eWxlWyd3aWxsLWNoYW5nZSddID0gJ2FsbCc7XG4gICAgICBkcmFnU3RhcnRGbiA9IGZ1bmN0aW9uIGRyYWdTdGFydEZuKCkge1xuICAgICAgICBwbHVnaW5FdmVudCgnZGVsYXlFbmRlZCcsIF90aGlzLCB7XG4gICAgICAgICAgZXZ0OiBldnRcbiAgICAgICAgfSk7XG4gICAgICAgIGlmIChTb3J0YWJsZS5ldmVudENhbmNlbGVkKSB7XG4gICAgICAgICAgX3RoaXMuX29uRHJvcCgpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICAvLyBEZWxheWVkIGRyYWcgaGFzIGJlZW4gdHJpZ2dlcmVkXG4gICAgICAgIC8vIHdlIGNhbiByZS1lbmFibGUgdGhlIGV2ZW50czogdG91Y2htb3ZlL21vdXNlbW92ZVxuICAgICAgICBfdGhpcy5fZGlzYWJsZURlbGF5ZWREcmFnRXZlbnRzKCk7XG4gICAgICAgIGlmICghRmlyZUZveCAmJiBfdGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgICBkcmFnRWwuZHJhZ2dhYmxlID0gdHJ1ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIEJpbmQgdGhlIGV2ZW50czogZHJhZ3N0YXJ0L2RyYWdlbmRcbiAgICAgICAgX3RoaXMuX3RyaWdnZXJEcmFnU3RhcnQoZXZ0LCB0b3VjaCk7XG5cbiAgICAgICAgLy8gRHJhZyBzdGFydCBldmVudFxuICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgc29ydGFibGU6IF90aGlzLFxuICAgICAgICAgIG5hbWU6ICdjaG9vc2UnLFxuICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICB9KTtcblxuICAgICAgICAvLyBDaG9zZW4gaXRlbVxuICAgICAgICB0b2dnbGVDbGFzcyhkcmFnRWwsIG9wdGlvbnMuY2hvc2VuQ2xhc3MsIHRydWUpO1xuICAgICAgfTtcblxuICAgICAgLy8gRGlzYWJsZSBcImRyYWdnYWJsZVwiXG4gICAgICBvcHRpb25zLmlnbm9yZS5zcGxpdCgnLCcpLmZvckVhY2goZnVuY3Rpb24gKGNyaXRlcmlhKSB7XG4gICAgICAgIGZpbmQoZHJhZ0VsLCBjcml0ZXJpYS50cmltKCksIF9kaXNhYmxlRHJhZ2dhYmxlKTtcbiAgICAgIH0pO1xuICAgICAgb24ob3duZXJEb2N1bWVudCwgJ2RyYWdvdmVyJywgbmVhcmVzdEVtcHR5SW5zZXJ0RGV0ZWN0RXZlbnQpO1xuICAgICAgb24ob3duZXJEb2N1bWVudCwgJ21vdXNlbW92ZScsIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KTtcbiAgICAgIG9uKG93bmVyRG9jdW1lbnQsICd0b3VjaG1vdmUnLCBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudCk7XG4gICAgICBvbihvd25lckRvY3VtZW50LCAnbW91c2V1cCcsIF90aGlzLl9vbkRyb3ApO1xuICAgICAgb24ob3duZXJEb2N1bWVudCwgJ3RvdWNoZW5kJywgX3RoaXMuX29uRHJvcCk7XG4gICAgICBvbihvd25lckRvY3VtZW50LCAndG91Y2hjYW5jZWwnLCBfdGhpcy5fb25Ecm9wKTtcblxuICAgICAgLy8gTWFrZSBkcmFnRWwgZHJhZ2dhYmxlIChtdXN0IGJlIGJlZm9yZSBkZWxheSBmb3IgRmlyZUZveClcbiAgICAgIGlmIChGaXJlRm94ICYmIHRoaXMubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgICAgIHRoaXMub3B0aW9ucy50b3VjaFN0YXJ0VGhyZXNob2xkID0gNDtcbiAgICAgICAgZHJhZ0VsLmRyYWdnYWJsZSA9IHRydWU7XG4gICAgICB9XG4gICAgICBwbHVnaW5FdmVudCgnZGVsYXlTdGFydCcsIHRoaXMsIHtcbiAgICAgICAgZXZ0OiBldnRcbiAgICAgIH0pO1xuXG4gICAgICAvLyBEZWxheSBpcyBpbXBvc3NpYmxlIGZvciBuYXRpdmUgRG5EIGluIEVkZ2Ugb3IgSUVcbiAgICAgIGlmIChvcHRpb25zLmRlbGF5ICYmICghb3B0aW9ucy5kZWxheU9uVG91Y2hPbmx5IHx8IHRvdWNoKSAmJiAoIXRoaXMubmF0aXZlRHJhZ2dhYmxlIHx8ICEoRWRnZSB8fCBJRTExT3JMZXNzKSkpIHtcbiAgICAgICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHtcbiAgICAgICAgICB0aGlzLl9vbkRyb3AoKTtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgLy8gSWYgdGhlIHVzZXIgbW92ZXMgdGhlIHBvaW50ZXIgb3IgbGV0IGdvIHRoZSBjbGljayBvciB0b3VjaFxuICAgICAgICAvLyBiZWZvcmUgdGhlIGRlbGF5IGhhcyBiZWVuIHJlYWNoZWQ6XG4gICAgICAgIC8vIGRpc2FibGUgdGhlIGRlbGF5ZWQgZHJhZ1xuICAgICAgICBvbihvd25lckRvY3VtZW50LCAnbW91c2V1cCcsIF90aGlzLl9kaXNhYmxlRGVsYXllZERyYWcpO1xuICAgICAgICBvbihvd25lckRvY3VtZW50LCAndG91Y2hlbmQnLCBfdGhpcy5fZGlzYWJsZURlbGF5ZWREcmFnKTtcbiAgICAgICAgb24ob3duZXJEb2N1bWVudCwgJ3RvdWNoY2FuY2VsJywgX3RoaXMuX2Rpc2FibGVEZWxheWVkRHJhZyk7XG4gICAgICAgIG9uKG93bmVyRG9jdW1lbnQsICdtb3VzZW1vdmUnLCBfdGhpcy5fZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyKTtcbiAgICAgICAgb24ob3duZXJEb2N1bWVudCwgJ3RvdWNobW92ZScsIF90aGlzLl9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXIpO1xuICAgICAgICBvcHRpb25zLnN1cHBvcnRQb2ludGVyICYmIG9uKG93bmVyRG9jdW1lbnQsICdwb2ludGVybW92ZScsIF90aGlzLl9kZWxheWVkRHJhZ1RvdWNoTW92ZUhhbmRsZXIpO1xuICAgICAgICBfdGhpcy5fZHJhZ1N0YXJ0VGltZXIgPSBzZXRUaW1lb3V0KGRyYWdTdGFydEZuLCBvcHRpb25zLmRlbGF5KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGRyYWdTdGFydEZuKCk7XG4gICAgICB9XG4gICAgfVxuICB9LFxuICBfZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyOiBmdW5jdGlvbiBfZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyKCAvKiogVG91Y2hFdmVudHxQb2ludGVyRXZlbnQgKiovZSkge1xuICAgIHZhciB0b3VjaCA9IGUudG91Y2hlcyA/IGUudG91Y2hlc1swXSA6IGU7XG4gICAgaWYgKE1hdGgubWF4KE1hdGguYWJzKHRvdWNoLmNsaWVudFggLSB0aGlzLl9sYXN0WCksIE1hdGguYWJzKHRvdWNoLmNsaWVudFkgLSB0aGlzLl9sYXN0WSkpID49IE1hdGguZmxvb3IodGhpcy5vcHRpb25zLnRvdWNoU3RhcnRUaHJlc2hvbGQgLyAodGhpcy5uYXRpdmVEcmFnZ2FibGUgJiYgd2luZG93LmRldmljZVBpeGVsUmF0aW8gfHwgMSkpKSB7XG4gICAgICB0aGlzLl9kaXNhYmxlRGVsYXllZERyYWcoKTtcbiAgICB9XG4gIH0sXG4gIF9kaXNhYmxlRGVsYXllZERyYWc6IGZ1bmN0aW9uIF9kaXNhYmxlRGVsYXllZERyYWcoKSB7XG4gICAgZHJhZ0VsICYmIF9kaXNhYmxlRHJhZ2dhYmxlKGRyYWdFbCk7XG4gICAgY2xlYXJUaW1lb3V0KHRoaXMuX2RyYWdTdGFydFRpbWVyKTtcbiAgICB0aGlzLl9kaXNhYmxlRGVsYXllZERyYWdFdmVudHMoKTtcbiAgfSxcbiAgX2Rpc2FibGVEZWxheWVkRHJhZ0V2ZW50czogZnVuY3Rpb24gX2Rpc2FibGVEZWxheWVkRHJhZ0V2ZW50cygpIHtcbiAgICB2YXIgb3duZXJEb2N1bWVudCA9IHRoaXMuZWwub3duZXJEb2N1bWVudDtcbiAgICBvZmYob3duZXJEb2N1bWVudCwgJ21vdXNldXAnLCB0aGlzLl9kaXNhYmxlRGVsYXllZERyYWcpO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAndG91Y2hlbmQnLCB0aGlzLl9kaXNhYmxlRGVsYXllZERyYWcpO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAndG91Y2hjYW5jZWwnLCB0aGlzLl9kaXNhYmxlRGVsYXllZERyYWcpO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAnbW91c2Vtb3ZlJywgdGhpcy5fZGVsYXllZERyYWdUb3VjaE1vdmVIYW5kbGVyKTtcbiAgICBvZmYob3duZXJEb2N1bWVudCwgJ3RvdWNobW92ZScsIHRoaXMuX2RlbGF5ZWREcmFnVG91Y2hNb3ZlSGFuZGxlcik7XG4gICAgb2ZmKG93bmVyRG9jdW1lbnQsICdwb2ludGVybW92ZScsIHRoaXMuX2RlbGF5ZWREcmFnVG91Y2hNb3ZlSGFuZGxlcik7XG4gIH0sXG4gIF90cmlnZ2VyRHJhZ1N0YXJ0OiBmdW5jdGlvbiBfdHJpZ2dlckRyYWdTdGFydCggLyoqIEV2ZW50ICovZXZ0LCAvKiogVG91Y2ggKi90b3VjaCkge1xuICAgIHRvdWNoID0gdG91Y2ggfHwgZXZ0LnBvaW50ZXJUeXBlID09ICd0b3VjaCcgJiYgZXZ0O1xuICAgIGlmICghdGhpcy5uYXRpdmVEcmFnZ2FibGUgfHwgdG91Y2gpIHtcbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuc3VwcG9ydFBvaW50ZXIpIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICdwb2ludGVybW92ZScsIHRoaXMuX29uVG91Y2hNb3ZlKTtcbiAgICAgIH0gZWxzZSBpZiAodG91Y2gpIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICd0b3VjaG1vdmUnLCB0aGlzLl9vblRvdWNoTW92ZSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBvbihkb2N1bWVudCwgJ21vdXNlbW92ZScsIHRoaXMuX29uVG91Y2hNb3ZlKTtcbiAgICAgIH1cbiAgICB9IGVsc2Uge1xuICAgICAgb24oZHJhZ0VsLCAnZHJhZ2VuZCcsIHRoaXMpO1xuICAgICAgb24ocm9vdEVsLCAnZHJhZ3N0YXJ0JywgdGhpcy5fb25EcmFnU3RhcnQpO1xuICAgIH1cbiAgICB0cnkge1xuICAgICAgaWYgKGRvY3VtZW50LnNlbGVjdGlvbikge1xuICAgICAgICAvLyBUaW1lb3V0IG5lY2Nlc3NhcnkgZm9yIElFOVxuICAgICAgICBfbmV4dFRpY2soZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGRvY3VtZW50LnNlbGVjdGlvbi5lbXB0eSgpO1xuICAgICAgICB9KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHdpbmRvdy5nZXRTZWxlY3Rpb24oKS5yZW1vdmVBbGxSYW5nZXMoKTtcbiAgICAgIH1cbiAgICB9IGNhdGNoIChlcnIpIHt9XG4gIH0sXG4gIF9kcmFnU3RhcnRlZDogZnVuY3Rpb24gX2RyYWdTdGFydGVkKGZhbGxiYWNrLCBldnQpIHtcbiAgICBhd2FpdGluZ0RyYWdTdGFydGVkID0gZmFsc2U7XG4gICAgaWYgKHJvb3RFbCAmJiBkcmFnRWwpIHtcbiAgICAgIHBsdWdpbkV2ZW50KCdkcmFnU3RhcnRlZCcsIHRoaXMsIHtcbiAgICAgICAgZXZ0OiBldnRcbiAgICAgIH0pO1xuICAgICAgaWYgKHRoaXMubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgICAgIG9uKGRvY3VtZW50LCAnZHJhZ292ZXInLCBfY2hlY2tPdXRzaWRlVGFyZ2V0RWwpO1xuICAgICAgfVxuICAgICAgdmFyIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG5cbiAgICAgIC8vIEFwcGx5IGVmZmVjdFxuICAgICAgIWZhbGxiYWNrICYmIHRvZ2dsZUNsYXNzKGRyYWdFbCwgb3B0aW9ucy5kcmFnQ2xhc3MsIGZhbHNlKTtcbiAgICAgIHRvZ2dsZUNsYXNzKGRyYWdFbCwgb3B0aW9ucy5naG9zdENsYXNzLCB0cnVlKTtcbiAgICAgIFNvcnRhYmxlLmFjdGl2ZSA9IHRoaXM7XG4gICAgICBmYWxsYmFjayAmJiB0aGlzLl9hcHBlbmRHaG9zdCgpO1xuXG4gICAgICAvLyBEcmFnIHN0YXJ0IGV2ZW50XG4gICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICBuYW1lOiAnc3RhcnQnLFxuICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgIH0pO1xuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLl9udWxsaW5nKCk7XG4gICAgfVxuICB9LFxuICBfZW11bGF0ZURyYWdPdmVyOiBmdW5jdGlvbiBfZW11bGF0ZURyYWdPdmVyKCkge1xuICAgIGlmICh0b3VjaEV2dCkge1xuICAgICAgdGhpcy5fbGFzdFggPSB0b3VjaEV2dC5jbGllbnRYO1xuICAgICAgdGhpcy5fbGFzdFkgPSB0b3VjaEV2dC5jbGllbnRZO1xuICAgICAgX2hpZGVHaG9zdEZvclRhcmdldCgpO1xuICAgICAgdmFyIHRhcmdldCA9IGRvY3VtZW50LmVsZW1lbnRGcm9tUG9pbnQodG91Y2hFdnQuY2xpZW50WCwgdG91Y2hFdnQuY2xpZW50WSk7XG4gICAgICB2YXIgcGFyZW50ID0gdGFyZ2V0O1xuICAgICAgd2hpbGUgKHRhcmdldCAmJiB0YXJnZXQuc2hhZG93Um9vdCkge1xuICAgICAgICB0YXJnZXQgPSB0YXJnZXQuc2hhZG93Um9vdC5lbGVtZW50RnJvbVBvaW50KHRvdWNoRXZ0LmNsaWVudFgsIHRvdWNoRXZ0LmNsaWVudFkpO1xuICAgICAgICBpZiAodGFyZ2V0ID09PSBwYXJlbnQpIGJyZWFrO1xuICAgICAgICBwYXJlbnQgPSB0YXJnZXQ7XG4gICAgICB9XG4gICAgICBkcmFnRWwucGFyZW50Tm9kZVtleHBhbmRvXS5faXNPdXRzaWRlVGhpc0VsKHRhcmdldCk7XG4gICAgICBpZiAocGFyZW50KSB7XG4gICAgICAgIGRvIHtcbiAgICAgICAgICBpZiAocGFyZW50W2V4cGFuZG9dKSB7XG4gICAgICAgICAgICB2YXIgaW5zZXJ0ZWQgPSB2b2lkIDA7XG4gICAgICAgICAgICBpbnNlcnRlZCA9IHBhcmVudFtleHBhbmRvXS5fb25EcmFnT3Zlcih7XG4gICAgICAgICAgICAgIGNsaWVudFg6IHRvdWNoRXZ0LmNsaWVudFgsXG4gICAgICAgICAgICAgIGNsaWVudFk6IHRvdWNoRXZ0LmNsaWVudFksXG4gICAgICAgICAgICAgIHRhcmdldDogdGFyZ2V0LFxuICAgICAgICAgICAgICByb290RWw6IHBhcmVudFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBpZiAoaW5zZXJ0ZWQgJiYgIXRoaXMub3B0aW9ucy5kcmFnb3ZlckJ1YmJsZSkge1xuICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgdGFyZ2V0ID0gcGFyZW50OyAvLyBzdG9yZSBsYXN0IGVsZW1lbnRcbiAgICAgICAgfVxuICAgICAgICAvKiBqc2hpbnQgYm9zczp0cnVlICovIHdoaWxlIChwYXJlbnQgPSBwYXJlbnQucGFyZW50Tm9kZSk7XG4gICAgICB9XG4gICAgICBfdW5oaWRlR2hvc3RGb3JUYXJnZXQoKTtcbiAgICB9XG4gIH0sXG4gIF9vblRvdWNoTW92ZTogZnVuY3Rpb24gX29uVG91Y2hNb3ZlKCAvKipUb3VjaEV2ZW50Ki9ldnQpIHtcbiAgICBpZiAodGFwRXZ0KSB7XG4gICAgICB2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucyxcbiAgICAgICAgZmFsbGJhY2tUb2xlcmFuY2UgPSBvcHRpb25zLmZhbGxiYWNrVG9sZXJhbmNlLFxuICAgICAgICBmYWxsYmFja09mZnNldCA9IG9wdGlvbnMuZmFsbGJhY2tPZmZzZXQsXG4gICAgICAgIHRvdWNoID0gZXZ0LnRvdWNoZXMgPyBldnQudG91Y2hlc1swXSA6IGV2dCxcbiAgICAgICAgZ2hvc3RNYXRyaXggPSBnaG9zdEVsICYmIG1hdHJpeChnaG9zdEVsLCB0cnVlKSxcbiAgICAgICAgc2NhbGVYID0gZ2hvc3RFbCAmJiBnaG9zdE1hdHJpeCAmJiBnaG9zdE1hdHJpeC5hLFxuICAgICAgICBzY2FsZVkgPSBnaG9zdEVsICYmIGdob3N0TWF0cml4ICYmIGdob3N0TWF0cml4LmQsXG4gICAgICAgIHJlbGF0aXZlU2Nyb2xsT2Zmc2V0ID0gUG9zaXRpb25HaG9zdEFic29sdXRlbHkgJiYgZ2hvc3RSZWxhdGl2ZVBhcmVudCAmJiBnZXRSZWxhdGl2ZVNjcm9sbE9mZnNldChnaG9zdFJlbGF0aXZlUGFyZW50KSxcbiAgICAgICAgZHggPSAodG91Y2guY2xpZW50WCAtIHRhcEV2dC5jbGllbnRYICsgZmFsbGJhY2tPZmZzZXQueCkgLyAoc2NhbGVYIHx8IDEpICsgKHJlbGF0aXZlU2Nyb2xsT2Zmc2V0ID8gcmVsYXRpdmVTY3JvbGxPZmZzZXRbMF0gLSBnaG9zdFJlbGF0aXZlUGFyZW50SW5pdGlhbFNjcm9sbFswXSA6IDApIC8gKHNjYWxlWCB8fCAxKSxcbiAgICAgICAgZHkgPSAodG91Y2guY2xpZW50WSAtIHRhcEV2dC5jbGllbnRZICsgZmFsbGJhY2tPZmZzZXQueSkgLyAoc2NhbGVZIHx8IDEpICsgKHJlbGF0aXZlU2Nyb2xsT2Zmc2V0ID8gcmVsYXRpdmVTY3JvbGxPZmZzZXRbMV0gLSBnaG9zdFJlbGF0aXZlUGFyZW50SW5pdGlhbFNjcm9sbFsxXSA6IDApIC8gKHNjYWxlWSB8fCAxKTtcblxuICAgICAgLy8gb25seSBzZXQgdGhlIHN0YXR1cyB0byBkcmFnZ2luZywgd2hlbiB3ZSBhcmUgYWN0dWFsbHkgZHJhZ2dpbmdcbiAgICAgIGlmICghU29ydGFibGUuYWN0aXZlICYmICFhd2FpdGluZ0RyYWdTdGFydGVkKSB7XG4gICAgICAgIGlmIChmYWxsYmFja1RvbGVyYW5jZSAmJiBNYXRoLm1heChNYXRoLmFicyh0b3VjaC5jbGllbnRYIC0gdGhpcy5fbGFzdFgpLCBNYXRoLmFicyh0b3VjaC5jbGllbnRZIC0gdGhpcy5fbGFzdFkpKSA8IGZhbGxiYWNrVG9sZXJhbmNlKSB7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuX29uRHJhZ1N0YXJ0KGV2dCwgdHJ1ZSk7XG4gICAgICB9XG4gICAgICBpZiAoZ2hvc3RFbCkge1xuICAgICAgICBpZiAoZ2hvc3RNYXRyaXgpIHtcbiAgICAgICAgICBnaG9zdE1hdHJpeC5lICs9IGR4IC0gKGxhc3REeCB8fCAwKTtcbiAgICAgICAgICBnaG9zdE1hdHJpeC5mICs9IGR5IC0gKGxhc3REeSB8fCAwKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBnaG9zdE1hdHJpeCA9IHtcbiAgICAgICAgICAgIGE6IDEsXG4gICAgICAgICAgICBiOiAwLFxuICAgICAgICAgICAgYzogMCxcbiAgICAgICAgICAgIGQ6IDEsXG4gICAgICAgICAgICBlOiBkeCxcbiAgICAgICAgICAgIGY6IGR5XG4gICAgICAgICAgfTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgY3NzTWF0cml4ID0gXCJtYXRyaXgoXCIuY29uY2F0KGdob3N0TWF0cml4LmEsIFwiLFwiKS5jb25jYXQoZ2hvc3RNYXRyaXguYiwgXCIsXCIpLmNvbmNhdChnaG9zdE1hdHJpeC5jLCBcIixcIikuY29uY2F0KGdob3N0TWF0cml4LmQsIFwiLFwiKS5jb25jYXQoZ2hvc3RNYXRyaXguZSwgXCIsXCIpLmNvbmNhdChnaG9zdE1hdHJpeC5mLCBcIilcIik7XG4gICAgICAgIGNzcyhnaG9zdEVsLCAnd2Via2l0VHJhbnNmb3JtJywgY3NzTWF0cml4KTtcbiAgICAgICAgY3NzKGdob3N0RWwsICdtb3pUcmFuc2Zvcm0nLCBjc3NNYXRyaXgpO1xuICAgICAgICBjc3MoZ2hvc3RFbCwgJ21zVHJhbnNmb3JtJywgY3NzTWF0cml4KTtcbiAgICAgICAgY3NzKGdob3N0RWwsICd0cmFuc2Zvcm0nLCBjc3NNYXRyaXgpO1xuICAgICAgICBsYXN0RHggPSBkeDtcbiAgICAgICAgbGFzdER5ID0gZHk7XG4gICAgICAgIHRvdWNoRXZ0ID0gdG91Y2g7XG4gICAgICB9XG4gICAgICBldnQuY2FuY2VsYWJsZSAmJiBldnQucHJldmVudERlZmF1bHQoKTtcbiAgICB9XG4gIH0sXG4gIF9hcHBlbmRHaG9zdDogZnVuY3Rpb24gX2FwcGVuZEdob3N0KCkge1xuICAgIC8vIEJ1ZyBpZiB1c2luZyBzY2FsZSgpOiBodHRwczovL3N0YWNrb3ZlcmZsb3cuY29tL3F1ZXN0aW9ucy8yNjM3MDU4XG4gICAgLy8gTm90IGJlaW5nIGFkanVzdGVkIGZvclxuICAgIGlmICghZ2hvc3RFbCkge1xuICAgICAgdmFyIGNvbnRhaW5lciA9IHRoaXMub3B0aW9ucy5mYWxsYmFja09uQm9keSA/IGRvY3VtZW50LmJvZHkgOiByb290RWwsXG4gICAgICAgIHJlY3QgPSBnZXRSZWN0KGRyYWdFbCwgdHJ1ZSwgUG9zaXRpb25HaG9zdEFic29sdXRlbHksIHRydWUsIGNvbnRhaW5lciksXG4gICAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG5cbiAgICAgIC8vIFBvc2l0aW9uIGFic29sdXRlbHlcbiAgICAgIGlmIChQb3NpdGlvbkdob3N0QWJzb2x1dGVseSkge1xuICAgICAgICAvLyBHZXQgcmVsYXRpdmVseSBwb3NpdGlvbmVkIHBhcmVudFxuICAgICAgICBnaG9zdFJlbGF0aXZlUGFyZW50ID0gY29udGFpbmVyO1xuICAgICAgICB3aGlsZSAoY3NzKGdob3N0UmVsYXRpdmVQYXJlbnQsICdwb3NpdGlvbicpID09PSAnc3RhdGljJyAmJiBjc3MoZ2hvc3RSZWxhdGl2ZVBhcmVudCwgJ3RyYW5zZm9ybScpID09PSAnbm9uZScgJiYgZ2hvc3RSZWxhdGl2ZVBhcmVudCAhPT0gZG9jdW1lbnQpIHtcbiAgICAgICAgICBnaG9zdFJlbGF0aXZlUGFyZW50ID0gZ2hvc3RSZWxhdGl2ZVBhcmVudC5wYXJlbnROb2RlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChnaG9zdFJlbGF0aXZlUGFyZW50ICE9PSBkb2N1bWVudC5ib2R5ICYmIGdob3N0UmVsYXRpdmVQYXJlbnQgIT09IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudCkge1xuICAgICAgICAgIGlmIChnaG9zdFJlbGF0aXZlUGFyZW50ID09PSBkb2N1bWVudCkgZ2hvc3RSZWxhdGl2ZVBhcmVudCA9IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKTtcbiAgICAgICAgICByZWN0LnRvcCArPSBnaG9zdFJlbGF0aXZlUGFyZW50LnNjcm9sbFRvcDtcbiAgICAgICAgICByZWN0LmxlZnQgKz0gZ2hvc3RSZWxhdGl2ZVBhcmVudC5zY3JvbGxMZWZ0O1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGdob3N0UmVsYXRpdmVQYXJlbnQgPSBnZXRXaW5kb3dTY3JvbGxpbmdFbGVtZW50KCk7XG4gICAgICAgIH1cbiAgICAgICAgZ2hvc3RSZWxhdGl2ZVBhcmVudEluaXRpYWxTY3JvbGwgPSBnZXRSZWxhdGl2ZVNjcm9sbE9mZnNldChnaG9zdFJlbGF0aXZlUGFyZW50KTtcbiAgICAgIH1cbiAgICAgIGdob3N0RWwgPSBkcmFnRWwuY2xvbmVOb2RlKHRydWUpO1xuICAgICAgdG9nZ2xlQ2xhc3MoZ2hvc3RFbCwgb3B0aW9ucy5naG9zdENsYXNzLCBmYWxzZSk7XG4gICAgICB0b2dnbGVDbGFzcyhnaG9zdEVsLCBvcHRpb25zLmZhbGxiYWNrQ2xhc3MsIHRydWUpO1xuICAgICAgdG9nZ2xlQ2xhc3MoZ2hvc3RFbCwgb3B0aW9ucy5kcmFnQ2xhc3MsIHRydWUpO1xuICAgICAgY3NzKGdob3N0RWwsICd0cmFuc2l0aW9uJywgJycpO1xuICAgICAgY3NzKGdob3N0RWwsICd0cmFuc2Zvcm0nLCAnJyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ2JveC1zaXppbmcnLCAnYm9yZGVyLWJveCcpO1xuICAgICAgY3NzKGdob3N0RWwsICdtYXJnaW4nLCAwKTtcbiAgICAgIGNzcyhnaG9zdEVsLCAndG9wJywgcmVjdC50b3ApO1xuICAgICAgY3NzKGdob3N0RWwsICdsZWZ0JywgcmVjdC5sZWZ0KTtcbiAgICAgIGNzcyhnaG9zdEVsLCAnd2lkdGgnLCByZWN0LndpZHRoKTtcbiAgICAgIGNzcyhnaG9zdEVsLCAnaGVpZ2h0JywgcmVjdC5oZWlnaHQpO1xuICAgICAgY3NzKGdob3N0RWwsICdvcGFjaXR5JywgJzAuOCcpO1xuICAgICAgY3NzKGdob3N0RWwsICdwb3NpdGlvbicsIFBvc2l0aW9uR2hvc3RBYnNvbHV0ZWx5ID8gJ2Fic29sdXRlJyA6ICdmaXhlZCcpO1xuICAgICAgY3NzKGdob3N0RWwsICd6SW5kZXgnLCAnMTAwMDAwJyk7XG4gICAgICBjc3MoZ2hvc3RFbCwgJ3BvaW50ZXJFdmVudHMnLCAnbm9uZScpO1xuICAgICAgU29ydGFibGUuZ2hvc3QgPSBnaG9zdEVsO1xuICAgICAgY29udGFpbmVyLmFwcGVuZENoaWxkKGdob3N0RWwpO1xuXG4gICAgICAvLyBTZXQgdHJhbnNmb3JtLW9yaWdpblxuICAgICAgY3NzKGdob3N0RWwsICd0cmFuc2Zvcm0tb3JpZ2luJywgdGFwRGlzdGFuY2VMZWZ0IC8gcGFyc2VJbnQoZ2hvc3RFbC5zdHlsZS53aWR0aCkgKiAxMDAgKyAnJSAnICsgdGFwRGlzdGFuY2VUb3AgLyBwYXJzZUludChnaG9zdEVsLnN0eWxlLmhlaWdodCkgKiAxMDAgKyAnJScpO1xuICAgIH1cbiAgfSxcbiAgX29uRHJhZ1N0YXJ0OiBmdW5jdGlvbiBfb25EcmFnU3RhcnQoIC8qKkV2ZW50Ki9ldnQsIC8qKmJvb2xlYW4qL2ZhbGxiYWNrKSB7XG4gICAgdmFyIF90aGlzID0gdGhpcztcbiAgICB2YXIgZGF0YVRyYW5zZmVyID0gZXZ0LmRhdGFUcmFuc2ZlcjtcbiAgICB2YXIgb3B0aW9ucyA9IF90aGlzLm9wdGlvbnM7XG4gICAgcGx1Z2luRXZlbnQoJ2RyYWdTdGFydCcsIHRoaXMsIHtcbiAgICAgIGV2dDogZXZ0XG4gICAgfSk7XG4gICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHtcbiAgICAgIHRoaXMuX29uRHJvcCgpO1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBwbHVnaW5FdmVudCgnc2V0dXBDbG9uZScsIHRoaXMpO1xuICAgIGlmICghU29ydGFibGUuZXZlbnRDYW5jZWxlZCkge1xuICAgICAgY2xvbmVFbCA9IGNsb25lKGRyYWdFbCk7XG4gICAgICBjbG9uZUVsLnJlbW92ZUF0dHJpYnV0ZShcImlkXCIpO1xuICAgICAgY2xvbmVFbC5kcmFnZ2FibGUgPSBmYWxzZTtcbiAgICAgIGNsb25lRWwuc3R5bGVbJ3dpbGwtY2hhbmdlJ10gPSAnJztcbiAgICAgIHRoaXMuX2hpZGVDbG9uZSgpO1xuICAgICAgdG9nZ2xlQ2xhc3MoY2xvbmVFbCwgdGhpcy5vcHRpb25zLmNob3NlbkNsYXNzLCBmYWxzZSk7XG4gICAgICBTb3J0YWJsZS5jbG9uZSA9IGNsb25lRWw7XG4gICAgfVxuXG4gICAgLy8gIzExNDM6IElGcmFtZSBzdXBwb3J0IHdvcmthcm91bmRcbiAgICBfdGhpcy5jbG9uZUlkID0gX25leHRUaWNrKGZ1bmN0aW9uICgpIHtcbiAgICAgIHBsdWdpbkV2ZW50KCdjbG9uZScsIF90aGlzKTtcbiAgICAgIGlmIChTb3J0YWJsZS5ldmVudENhbmNlbGVkKSByZXR1cm47XG4gICAgICBpZiAoIV90aGlzLm9wdGlvbnMucmVtb3ZlQ2xvbmVPbkhpZGUpIHtcbiAgICAgICAgcm9vdEVsLmluc2VydEJlZm9yZShjbG9uZUVsLCBkcmFnRWwpO1xuICAgICAgfVxuICAgICAgX3RoaXMuX2hpZGVDbG9uZSgpO1xuICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICBzb3J0YWJsZTogX3RoaXMsXG4gICAgICAgIG5hbWU6ICdjbG9uZSdcbiAgICAgIH0pO1xuICAgIH0pO1xuICAgICFmYWxsYmFjayAmJiB0b2dnbGVDbGFzcyhkcmFnRWwsIG9wdGlvbnMuZHJhZ0NsYXNzLCB0cnVlKTtcblxuICAgIC8vIFNldCBwcm9wZXIgZHJvcCBldmVudHNcbiAgICBpZiAoZmFsbGJhY2spIHtcbiAgICAgIGlnbm9yZU5leHRDbGljayA9IHRydWU7XG4gICAgICBfdGhpcy5fbG9vcElkID0gc2V0SW50ZXJ2YWwoX3RoaXMuX2VtdWxhdGVEcmFnT3ZlciwgNTApO1xuICAgIH0gZWxzZSB7XG4gICAgICAvLyBVbmRvIHdoYXQgd2FzIHNldCBpbiBfcHJlcGFyZURyYWdTdGFydCBiZWZvcmUgZHJhZyBzdGFydGVkXG4gICAgICBvZmYoZG9jdW1lbnQsICdtb3VzZXVwJywgX3RoaXMuX29uRHJvcCk7XG4gICAgICBvZmYoZG9jdW1lbnQsICd0b3VjaGVuZCcsIF90aGlzLl9vbkRyb3ApO1xuICAgICAgb2ZmKGRvY3VtZW50LCAndG91Y2hjYW5jZWwnLCBfdGhpcy5fb25Ecm9wKTtcbiAgICAgIGlmIChkYXRhVHJhbnNmZXIpIHtcbiAgICAgICAgZGF0YVRyYW5zZmVyLmVmZmVjdEFsbG93ZWQgPSAnbW92ZSc7XG4gICAgICAgIG9wdGlvbnMuc2V0RGF0YSAmJiBvcHRpb25zLnNldERhdGEuY2FsbChfdGhpcywgZGF0YVRyYW5zZmVyLCBkcmFnRWwpO1xuICAgICAgfVxuICAgICAgb24oZG9jdW1lbnQsICdkcm9wJywgX3RoaXMpO1xuXG4gICAgICAvLyAjMTI3NiBmaXg6XG4gICAgICBjc3MoZHJhZ0VsLCAndHJhbnNmb3JtJywgJ3RyYW5zbGF0ZVooMCknKTtcbiAgICB9XG4gICAgYXdhaXRpbmdEcmFnU3RhcnRlZCA9IHRydWU7XG4gICAgX3RoaXMuX2RyYWdTdGFydElkID0gX25leHRUaWNrKF90aGlzLl9kcmFnU3RhcnRlZC5iaW5kKF90aGlzLCBmYWxsYmFjaywgZXZ0KSk7XG4gICAgb24oZG9jdW1lbnQsICdzZWxlY3RzdGFydCcsIF90aGlzKTtcbiAgICBtb3ZlZCA9IHRydWU7XG4gICAgaWYgKFNhZmFyaSkge1xuICAgICAgY3NzKGRvY3VtZW50LmJvZHksICd1c2VyLXNlbGVjdCcsICdub25lJyk7XG4gICAgfVxuICB9LFxuICAvLyBSZXR1cm5zIHRydWUgLSBpZiBubyBmdXJ0aGVyIGFjdGlvbiBpcyBuZWVkZWQgKGVpdGhlciBpbnNlcnRlZCBvciBhbm90aGVyIGNvbmRpdGlvbilcbiAgX29uRHJhZ092ZXI6IGZ1bmN0aW9uIF9vbkRyYWdPdmVyKCAvKipFdmVudCovZXZ0KSB7XG4gICAgdmFyIGVsID0gdGhpcy5lbCxcbiAgICAgIHRhcmdldCA9IGV2dC50YXJnZXQsXG4gICAgICBkcmFnUmVjdCxcbiAgICAgIHRhcmdldFJlY3QsXG4gICAgICByZXZlcnQsXG4gICAgICBvcHRpb25zID0gdGhpcy5vcHRpb25zLFxuICAgICAgZ3JvdXAgPSBvcHRpb25zLmdyb3VwLFxuICAgICAgYWN0aXZlU29ydGFibGUgPSBTb3J0YWJsZS5hY3RpdmUsXG4gICAgICBpc093bmVyID0gYWN0aXZlR3JvdXAgPT09IGdyb3VwLFxuICAgICAgY2FuU29ydCA9IG9wdGlvbnMuc29ydCxcbiAgICAgIGZyb21Tb3J0YWJsZSA9IHB1dFNvcnRhYmxlIHx8IGFjdGl2ZVNvcnRhYmxlLFxuICAgICAgdmVydGljYWwsXG4gICAgICBfdGhpcyA9IHRoaXMsXG4gICAgICBjb21wbGV0ZWRGaXJlZCA9IGZhbHNlO1xuICAgIGlmIChfc2lsZW50KSByZXR1cm47XG4gICAgZnVuY3Rpb24gZHJhZ092ZXJFdmVudChuYW1lLCBleHRyYSkge1xuICAgICAgcGx1Z2luRXZlbnQobmFtZSwgX3RoaXMsIF9vYmplY3RTcHJlYWQyKHtcbiAgICAgICAgZXZ0OiBldnQsXG4gICAgICAgIGlzT3duZXI6IGlzT3duZXIsXG4gICAgICAgIGF4aXM6IHZlcnRpY2FsID8gJ3ZlcnRpY2FsJyA6ICdob3Jpem9udGFsJyxcbiAgICAgICAgcmV2ZXJ0OiByZXZlcnQsXG4gICAgICAgIGRyYWdSZWN0OiBkcmFnUmVjdCxcbiAgICAgICAgdGFyZ2V0UmVjdDogdGFyZ2V0UmVjdCxcbiAgICAgICAgY2FuU29ydDogY2FuU29ydCxcbiAgICAgICAgZnJvbVNvcnRhYmxlOiBmcm9tU29ydGFibGUsXG4gICAgICAgIHRhcmdldDogdGFyZ2V0LFxuICAgICAgICBjb21wbGV0ZWQ6IGNvbXBsZXRlZCxcbiAgICAgICAgb25Nb3ZlOiBmdW5jdGlvbiBvbk1vdmUodGFyZ2V0LCBhZnRlcikge1xuICAgICAgICAgIHJldHVybiBfb25Nb3ZlKHJvb3RFbCwgZWwsIGRyYWdFbCwgZHJhZ1JlY3QsIHRhcmdldCwgZ2V0UmVjdCh0YXJnZXQpLCBldnQsIGFmdGVyKTtcbiAgICAgICAgfSxcbiAgICAgICAgY2hhbmdlZDogY2hhbmdlZFxuICAgICAgfSwgZXh0cmEpKTtcbiAgICB9XG5cbiAgICAvLyBDYXB0dXJlIGFuaW1hdGlvbiBzdGF0ZVxuICAgIGZ1bmN0aW9uIGNhcHR1cmUoKSB7XG4gICAgICBkcmFnT3ZlckV2ZW50KCdkcmFnT3ZlckFuaW1hdGlvbkNhcHR1cmUnKTtcbiAgICAgIF90aGlzLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgICAgaWYgKF90aGlzICE9PSBmcm9tU29ydGFibGUpIHtcbiAgICAgICAgZnJvbVNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgICAgfVxuICAgIH1cblxuICAgIC8vIFJldHVybiBpbnZvY2F0aW9uIHdoZW4gZHJhZ0VsIGlzIGluc2VydGVkIChvciBjb21wbGV0ZWQpXG4gICAgZnVuY3Rpb24gY29tcGxldGVkKGluc2VydGlvbikge1xuICAgICAgZHJhZ092ZXJFdmVudCgnZHJhZ092ZXJDb21wbGV0ZWQnLCB7XG4gICAgICAgIGluc2VydGlvbjogaW5zZXJ0aW9uXG4gICAgICB9KTtcbiAgICAgIGlmIChpbnNlcnRpb24pIHtcbiAgICAgICAgLy8gQ2xvbmVzIG11c3QgYmUgaGlkZGVuIGJlZm9yZSBmb2xkaW5nIGFuaW1hdGlvbiB0byBjYXB0dXJlIGRyYWdSZWN0QWJzb2x1dGUgcHJvcGVybHlcbiAgICAgICAgaWYgKGlzT3duZXIpIHtcbiAgICAgICAgICBhY3RpdmVTb3J0YWJsZS5faGlkZUNsb25lKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgYWN0aXZlU29ydGFibGUuX3Nob3dDbG9uZShfdGhpcyk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKF90aGlzICE9PSBmcm9tU29ydGFibGUpIHtcbiAgICAgICAgICAvLyBTZXQgZ2hvc3QgY2xhc3MgdG8gbmV3IHNvcnRhYmxlJ3MgZ2hvc3QgY2xhc3NcbiAgICAgICAgICB0b2dnbGVDbGFzcyhkcmFnRWwsIHB1dFNvcnRhYmxlID8gcHV0U29ydGFibGUub3B0aW9ucy5naG9zdENsYXNzIDogYWN0aXZlU29ydGFibGUub3B0aW9ucy5naG9zdENsYXNzLCBmYWxzZSk7XG4gICAgICAgICAgdG9nZ2xlQ2xhc3MoZHJhZ0VsLCBvcHRpb25zLmdob3N0Q2xhc3MsIHRydWUpO1xuICAgICAgICB9XG4gICAgICAgIGlmIChwdXRTb3J0YWJsZSAhPT0gX3RoaXMgJiYgX3RoaXMgIT09IFNvcnRhYmxlLmFjdGl2ZSkge1xuICAgICAgICAgIHB1dFNvcnRhYmxlID0gX3RoaXM7XG4gICAgICAgIH0gZWxzZSBpZiAoX3RoaXMgPT09IFNvcnRhYmxlLmFjdGl2ZSAmJiBwdXRTb3J0YWJsZSkge1xuICAgICAgICAgIHB1dFNvcnRhYmxlID0gbnVsbDtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIEFuaW1hdGlvblxuICAgICAgICBpZiAoZnJvbVNvcnRhYmxlID09PSBfdGhpcykge1xuICAgICAgICAgIF90aGlzLl9pZ25vcmVXaGlsZUFuaW1hdGluZyA9IHRhcmdldDtcbiAgICAgICAgfVxuICAgICAgICBfdGhpcy5hbmltYXRlQWxsKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBkcmFnT3ZlckV2ZW50KCdkcmFnT3ZlckFuaW1hdGlvbkNvbXBsZXRlJyk7XG4gICAgICAgICAgX3RoaXMuX2lnbm9yZVdoaWxlQW5pbWF0aW5nID0gbnVsbDtcbiAgICAgICAgfSk7XG4gICAgICAgIGlmIChfdGhpcyAhPT0gZnJvbVNvcnRhYmxlKSB7XG4gICAgICAgICAgZnJvbVNvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICAgICAgICBmcm9tU29ydGFibGUuX2lnbm9yZVdoaWxlQW5pbWF0aW5nID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICAvLyBOdWxsIGxhc3RUYXJnZXQgaWYgaXQgaXMgbm90IGluc2lkZSBhIHByZXZpb3VzbHkgc3dhcHBlZCBlbGVtZW50XG4gICAgICBpZiAodGFyZ2V0ID09PSBkcmFnRWwgJiYgIWRyYWdFbC5hbmltYXRlZCB8fCB0YXJnZXQgPT09IGVsICYmICF0YXJnZXQuYW5pbWF0ZWQpIHtcbiAgICAgICAgbGFzdFRhcmdldCA9IG51bGw7XG4gICAgICB9XG5cbiAgICAgIC8vIG5vIGJ1YmJsaW5nIGFuZCBub3QgZmFsbGJhY2tcbiAgICAgIGlmICghb3B0aW9ucy5kcmFnb3ZlckJ1YmJsZSAmJiAhZXZ0LnJvb3RFbCAmJiB0YXJnZXQgIT09IGRvY3VtZW50KSB7XG4gICAgICAgIGRyYWdFbC5wYXJlbnROb2RlW2V4cGFuZG9dLl9pc091dHNpZGVUaGlzRWwoZXZ0LnRhcmdldCk7XG5cbiAgICAgICAgLy8gRG8gbm90IGRldGVjdCBmb3IgZW1wdHkgaW5zZXJ0IGlmIGFscmVhZHkgaW5zZXJ0ZWRcbiAgICAgICAgIWluc2VydGlvbiAmJiBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudChldnQpO1xuICAgICAgfVxuICAgICAgIW9wdGlvbnMuZHJhZ292ZXJCdWJibGUgJiYgZXZ0LnN0b3BQcm9wYWdhdGlvbiAmJiBldnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICByZXR1cm4gY29tcGxldGVkRmlyZWQgPSB0cnVlO1xuICAgIH1cblxuICAgIC8vIENhbGwgd2hlbiBkcmFnRWwgaGFzIGJlZW4gaW5zZXJ0ZWRcbiAgICBmdW5jdGlvbiBjaGFuZ2VkKCkge1xuICAgICAgbmV3SW5kZXggPSBpbmRleChkcmFnRWwpO1xuICAgICAgbmV3RHJhZ2dhYmxlSW5kZXggPSBpbmRleChkcmFnRWwsIG9wdGlvbnMuZHJhZ2dhYmxlKTtcbiAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgc29ydGFibGU6IF90aGlzLFxuICAgICAgICBuYW1lOiAnY2hhbmdlJyxcbiAgICAgICAgdG9FbDogZWwsXG4gICAgICAgIG5ld0luZGV4OiBuZXdJbmRleCxcbiAgICAgICAgbmV3RHJhZ2dhYmxlSW5kZXg6IG5ld0RyYWdnYWJsZUluZGV4LFxuICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgIH0pO1xuICAgIH1cbiAgICBpZiAoZXZ0LnByZXZlbnREZWZhdWx0ICE9PSB2b2lkIDApIHtcbiAgICAgIGV2dC5jYW5jZWxhYmxlICYmIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIH1cbiAgICB0YXJnZXQgPSBjbG9zZXN0KHRhcmdldCwgb3B0aW9ucy5kcmFnZ2FibGUsIGVsLCB0cnVlKTtcbiAgICBkcmFnT3ZlckV2ZW50KCdkcmFnT3ZlcicpO1xuICAgIGlmIChTb3J0YWJsZS5ldmVudENhbmNlbGVkKSByZXR1cm4gY29tcGxldGVkRmlyZWQ7XG4gICAgaWYgKGRyYWdFbC5jb250YWlucyhldnQudGFyZ2V0KSB8fCB0YXJnZXQuYW5pbWF0ZWQgJiYgdGFyZ2V0LmFuaW1hdGluZ1ggJiYgdGFyZ2V0LmFuaW1hdGluZ1kgfHwgX3RoaXMuX2lnbm9yZVdoaWxlQW5pbWF0aW5nID09PSB0YXJnZXQpIHtcbiAgICAgIHJldHVybiBjb21wbGV0ZWQoZmFsc2UpO1xuICAgIH1cbiAgICBpZ25vcmVOZXh0Q2xpY2sgPSBmYWxzZTtcbiAgICBpZiAoYWN0aXZlU29ydGFibGUgJiYgIW9wdGlvbnMuZGlzYWJsZWQgJiYgKGlzT3duZXIgPyBjYW5Tb3J0IHx8IChyZXZlcnQgPSBwYXJlbnRFbCAhPT0gcm9vdEVsKSAvLyBSZXZlcnRpbmcgaXRlbSBpbnRvIHRoZSBvcmlnaW5hbCBsaXN0XG4gICAgOiBwdXRTb3J0YWJsZSA9PT0gdGhpcyB8fCAodGhpcy5sYXN0UHV0TW9kZSA9IGFjdGl2ZUdyb3VwLmNoZWNrUHVsbCh0aGlzLCBhY3RpdmVTb3J0YWJsZSwgZHJhZ0VsLCBldnQpKSAmJiBncm91cC5jaGVja1B1dCh0aGlzLCBhY3RpdmVTb3J0YWJsZSwgZHJhZ0VsLCBldnQpKSkge1xuICAgICAgdmVydGljYWwgPSB0aGlzLl9nZXREaXJlY3Rpb24oZXZ0LCB0YXJnZXQpID09PSAndmVydGljYWwnO1xuICAgICAgZHJhZ1JlY3QgPSBnZXRSZWN0KGRyYWdFbCk7XG4gICAgICBkcmFnT3ZlckV2ZW50KCdkcmFnT3ZlclZhbGlkJyk7XG4gICAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkgcmV0dXJuIGNvbXBsZXRlZEZpcmVkO1xuICAgICAgaWYgKHJldmVydCkge1xuICAgICAgICBwYXJlbnRFbCA9IHJvb3RFbDsgLy8gYWN0dWFsaXphdGlvblxuICAgICAgICBjYXB0dXJlKCk7XG4gICAgICAgIHRoaXMuX2hpZGVDbG9uZSgpO1xuICAgICAgICBkcmFnT3ZlckV2ZW50KCdyZXZlcnQnKTtcbiAgICAgICAgaWYgKCFTb3J0YWJsZS5ldmVudENhbmNlbGVkKSB7XG4gICAgICAgICAgaWYgKG5leHRFbCkge1xuICAgICAgICAgICAgcm9vdEVsLmluc2VydEJlZm9yZShkcmFnRWwsIG5leHRFbCk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHJvb3RFbC5hcHBlbmRDaGlsZChkcmFnRWwpO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gY29tcGxldGVkKHRydWUpO1xuICAgICAgfVxuICAgICAgdmFyIGVsTGFzdENoaWxkID0gbGFzdENoaWxkKGVsLCBvcHRpb25zLmRyYWdnYWJsZSk7XG4gICAgICBpZiAoIWVsTGFzdENoaWxkIHx8IF9naG9zdElzTGFzdChldnQsIHZlcnRpY2FsLCB0aGlzKSAmJiAhZWxMYXN0Q2hpbGQuYW5pbWF0ZWQpIHtcbiAgICAgICAgLy8gSW5zZXJ0IHRvIGVuZCBvZiBsaXN0XG5cbiAgICAgICAgLy8gSWYgYWxyZWFkeSBhdCBlbmQgb2YgbGlzdDogRG8gbm90IGluc2VydFxuICAgICAgICBpZiAoZWxMYXN0Q2hpbGQgPT09IGRyYWdFbCkge1xuICAgICAgICAgIHJldHVybiBjb21wbGV0ZWQoZmFsc2UpO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gaWYgdGhlcmUgaXMgYSBsYXN0IGVsZW1lbnQsIGl0IGlzIHRoZSB0YXJnZXRcbiAgICAgICAgaWYgKGVsTGFzdENoaWxkICYmIGVsID09PSBldnQudGFyZ2V0KSB7XG4gICAgICAgICAgdGFyZ2V0ID0gZWxMYXN0Q2hpbGQ7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRhcmdldCkge1xuICAgICAgICAgIHRhcmdldFJlY3QgPSBnZXRSZWN0KHRhcmdldCk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKF9vbk1vdmUocm9vdEVsLCBlbCwgZHJhZ0VsLCBkcmFnUmVjdCwgdGFyZ2V0LCB0YXJnZXRSZWN0LCBldnQsICEhdGFyZ2V0KSAhPT0gZmFsc2UpIHtcbiAgICAgICAgICBjYXB0dXJlKCk7XG4gICAgICAgICAgaWYgKGVsTGFzdENoaWxkICYmIGVsTGFzdENoaWxkLm5leHRTaWJsaW5nKSB7XG4gICAgICAgICAgICAvLyB0aGUgbGFzdCBkcmFnZ2FibGUgZWxlbWVudCBpcyBub3QgdGhlIGxhc3Qgbm9kZVxuICAgICAgICAgICAgZWwuaW5zZXJ0QmVmb3JlKGRyYWdFbCwgZWxMYXN0Q2hpbGQubmV4dFNpYmxpbmcpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBlbC5hcHBlbmRDaGlsZChkcmFnRWwpO1xuICAgICAgICAgIH1cbiAgICAgICAgICBwYXJlbnRFbCA9IGVsOyAvLyBhY3R1YWxpemF0aW9uXG5cbiAgICAgICAgICBjaGFuZ2VkKCk7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZCh0cnVlKTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmIChlbExhc3RDaGlsZCAmJiBfZ2hvc3RJc0ZpcnN0KGV2dCwgdmVydGljYWwsIHRoaXMpKSB7XG4gICAgICAgIC8vIEluc2VydCB0byBzdGFydCBvZiBsaXN0XG4gICAgICAgIHZhciBmaXJzdENoaWxkID0gZ2V0Q2hpbGQoZWwsIDAsIG9wdGlvbnMsIHRydWUpO1xuICAgICAgICBpZiAoZmlyc3RDaGlsZCA9PT0gZHJhZ0VsKSB7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgICAgIH1cbiAgICAgICAgdGFyZ2V0ID0gZmlyc3RDaGlsZDtcbiAgICAgICAgdGFyZ2V0UmVjdCA9IGdldFJlY3QodGFyZ2V0KTtcbiAgICAgICAgaWYgKF9vbk1vdmUocm9vdEVsLCBlbCwgZHJhZ0VsLCBkcmFnUmVjdCwgdGFyZ2V0LCB0YXJnZXRSZWN0LCBldnQsIGZhbHNlKSAhPT0gZmFsc2UpIHtcbiAgICAgICAgICBjYXB0dXJlKCk7XG4gICAgICAgICAgZWwuaW5zZXJ0QmVmb3JlKGRyYWdFbCwgZmlyc3RDaGlsZCk7XG4gICAgICAgICAgcGFyZW50RWwgPSBlbDsgLy8gYWN0dWFsaXphdGlvblxuXG4gICAgICAgICAgY2hhbmdlZCgpO1xuICAgICAgICAgIHJldHVybiBjb21wbGV0ZWQodHJ1ZSk7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAodGFyZ2V0LnBhcmVudE5vZGUgPT09IGVsKSB7XG4gICAgICAgIHRhcmdldFJlY3QgPSBnZXRSZWN0KHRhcmdldCk7XG4gICAgICAgIHZhciBkaXJlY3Rpb24gPSAwLFxuICAgICAgICAgIHRhcmdldEJlZm9yZUZpcnN0U3dhcCxcbiAgICAgICAgICBkaWZmZXJlbnRMZXZlbCA9IGRyYWdFbC5wYXJlbnROb2RlICE9PSBlbCxcbiAgICAgICAgICBkaWZmZXJlbnRSb3dDb2wgPSAhX2RyYWdFbEluUm93Q29sdW1uKGRyYWdFbC5hbmltYXRlZCAmJiBkcmFnRWwudG9SZWN0IHx8IGRyYWdSZWN0LCB0YXJnZXQuYW5pbWF0ZWQgJiYgdGFyZ2V0LnRvUmVjdCB8fCB0YXJnZXRSZWN0LCB2ZXJ0aWNhbCksXG4gICAgICAgICAgc2lkZTEgPSB2ZXJ0aWNhbCA/ICd0b3AnIDogJ2xlZnQnLFxuICAgICAgICAgIHNjcm9sbGVkUGFzdFRvcCA9IGlzU2Nyb2xsZWRQYXN0KHRhcmdldCwgJ3RvcCcsICd0b3AnKSB8fCBpc1Njcm9sbGVkUGFzdChkcmFnRWwsICd0b3AnLCAndG9wJyksXG4gICAgICAgICAgc2Nyb2xsQmVmb3JlID0gc2Nyb2xsZWRQYXN0VG9wID8gc2Nyb2xsZWRQYXN0VG9wLnNjcm9sbFRvcCA6IHZvaWQgMDtcbiAgICAgICAgaWYgKGxhc3RUYXJnZXQgIT09IHRhcmdldCkge1xuICAgICAgICAgIHRhcmdldEJlZm9yZUZpcnN0U3dhcCA9IHRhcmdldFJlY3Rbc2lkZTFdO1xuICAgICAgICAgIHBhc3RGaXJzdEludmVydFRocmVzaCA9IGZhbHNlO1xuICAgICAgICAgIGlzQ2lyY3Vtc3RhbnRpYWxJbnZlcnQgPSAhZGlmZmVyZW50Um93Q29sICYmIG9wdGlvbnMuaW52ZXJ0U3dhcCB8fCBkaWZmZXJlbnRMZXZlbDtcbiAgICAgICAgfVxuICAgICAgICBkaXJlY3Rpb24gPSBfZ2V0U3dhcERpcmVjdGlvbihldnQsIHRhcmdldCwgdGFyZ2V0UmVjdCwgdmVydGljYWwsIGRpZmZlcmVudFJvd0NvbCA/IDEgOiBvcHRpb25zLnN3YXBUaHJlc2hvbGQsIG9wdGlvbnMuaW52ZXJ0ZWRTd2FwVGhyZXNob2xkID09IG51bGwgPyBvcHRpb25zLnN3YXBUaHJlc2hvbGQgOiBvcHRpb25zLmludmVydGVkU3dhcFRocmVzaG9sZCwgaXNDaXJjdW1zdGFudGlhbEludmVydCwgbGFzdFRhcmdldCA9PT0gdGFyZ2V0KTtcbiAgICAgICAgdmFyIHNpYmxpbmc7XG4gICAgICAgIGlmIChkaXJlY3Rpb24gIT09IDApIHtcbiAgICAgICAgICAvLyBDaGVjayBpZiB0YXJnZXQgaXMgYmVzaWRlIGRyYWdFbCBpbiByZXNwZWN0aXZlIGRpcmVjdGlvbiAoaWdub3JpbmcgaGlkZGVuIGVsZW1lbnRzKVxuICAgICAgICAgIHZhciBkcmFnSW5kZXggPSBpbmRleChkcmFnRWwpO1xuICAgICAgICAgIGRvIHtcbiAgICAgICAgICAgIGRyYWdJbmRleCAtPSBkaXJlY3Rpb247XG4gICAgICAgICAgICBzaWJsaW5nID0gcGFyZW50RWwuY2hpbGRyZW5bZHJhZ0luZGV4XTtcbiAgICAgICAgICB9IHdoaWxlIChzaWJsaW5nICYmIChjc3Moc2libGluZywgJ2Rpc3BsYXknKSA9PT0gJ25vbmUnIHx8IHNpYmxpbmcgPT09IGdob3N0RWwpKTtcbiAgICAgICAgfVxuICAgICAgICAvLyBJZiBkcmFnRWwgaXMgYWxyZWFkeSBiZXNpZGUgdGFyZ2V0OiBEbyBub3QgaW5zZXJ0XG4gICAgICAgIGlmIChkaXJlY3Rpb24gPT09IDAgfHwgc2libGluZyA9PT0gdGFyZ2V0KSB7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgICAgIH1cbiAgICAgICAgbGFzdFRhcmdldCA9IHRhcmdldDtcbiAgICAgICAgbGFzdERpcmVjdGlvbiA9IGRpcmVjdGlvbjtcbiAgICAgICAgdmFyIG5leHRTaWJsaW5nID0gdGFyZ2V0Lm5leHRFbGVtZW50U2libGluZyxcbiAgICAgICAgICBhZnRlciA9IGZhbHNlO1xuICAgICAgICBhZnRlciA9IGRpcmVjdGlvbiA9PT0gMTtcbiAgICAgICAgdmFyIG1vdmVWZWN0b3IgPSBfb25Nb3ZlKHJvb3RFbCwgZWwsIGRyYWdFbCwgZHJhZ1JlY3QsIHRhcmdldCwgdGFyZ2V0UmVjdCwgZXZ0LCBhZnRlcik7XG4gICAgICAgIGlmIChtb3ZlVmVjdG9yICE9PSBmYWxzZSkge1xuICAgICAgICAgIGlmIChtb3ZlVmVjdG9yID09PSAxIHx8IG1vdmVWZWN0b3IgPT09IC0xKSB7XG4gICAgICAgICAgICBhZnRlciA9IG1vdmVWZWN0b3IgPT09IDE7XG4gICAgICAgICAgfVxuICAgICAgICAgIF9zaWxlbnQgPSB0cnVlO1xuICAgICAgICAgIHNldFRpbWVvdXQoX3Vuc2lsZW50LCAzMCk7XG4gICAgICAgICAgY2FwdHVyZSgpO1xuICAgICAgICAgIGlmIChhZnRlciAmJiAhbmV4dFNpYmxpbmcpIHtcbiAgICAgICAgICAgIGVsLmFwcGVuZENoaWxkKGRyYWdFbCk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHRhcmdldC5wYXJlbnROb2RlLmluc2VydEJlZm9yZShkcmFnRWwsIGFmdGVyID8gbmV4dFNpYmxpbmcgOiB0YXJnZXQpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIC8vIFVuZG8gY2hyb21lJ3Mgc2Nyb2xsIGFkanVzdG1lbnQgKGhhcyBubyBlZmZlY3Qgb24gb3RoZXIgYnJvd3NlcnMpXG4gICAgICAgICAgaWYgKHNjcm9sbGVkUGFzdFRvcCkge1xuICAgICAgICAgICAgc2Nyb2xsQnkoc2Nyb2xsZWRQYXN0VG9wLCAwLCBzY3JvbGxCZWZvcmUgLSBzY3JvbGxlZFBhc3RUb3Auc2Nyb2xsVG9wKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcGFyZW50RWwgPSBkcmFnRWwucGFyZW50Tm9kZTsgLy8gYWN0dWFsaXphdGlvblxuXG4gICAgICAgICAgLy8gbXVzdCBiZSBkb25lIGJlZm9yZSBhbmltYXRpb25cbiAgICAgICAgICBpZiAodGFyZ2V0QmVmb3JlRmlyc3RTd2FwICE9PSB1bmRlZmluZWQgJiYgIWlzQ2lyY3Vtc3RhbnRpYWxJbnZlcnQpIHtcbiAgICAgICAgICAgIHRhcmdldE1vdmVEaXN0YW5jZSA9IE1hdGguYWJzKHRhcmdldEJlZm9yZUZpcnN0U3dhcCAtIGdldFJlY3QodGFyZ2V0KVtzaWRlMV0pO1xuICAgICAgICAgIH1cbiAgICAgICAgICBjaGFuZ2VkKCk7XG4gICAgICAgICAgcmV0dXJuIGNvbXBsZXRlZCh0cnVlKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgaWYgKGVsLmNvbnRhaW5zKGRyYWdFbCkpIHtcbiAgICAgICAgcmV0dXJuIGNvbXBsZXRlZChmYWxzZSk7XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBmYWxzZTtcbiAgfSxcbiAgX2lnbm9yZVdoaWxlQW5pbWF0aW5nOiBudWxsLFxuICBfb2ZmTW92ZUV2ZW50czogZnVuY3Rpb24gX29mZk1vdmVFdmVudHMoKSB7XG4gICAgb2ZmKGRvY3VtZW50LCAnbW91c2Vtb3ZlJywgdGhpcy5fb25Ub3VjaE1vdmUpO1xuICAgIG9mZihkb2N1bWVudCwgJ3RvdWNobW92ZScsIHRoaXMuX29uVG91Y2hNb3ZlKTtcbiAgICBvZmYoZG9jdW1lbnQsICdwb2ludGVybW92ZScsIHRoaXMuX29uVG91Y2hNb3ZlKTtcbiAgICBvZmYoZG9jdW1lbnQsICdkcmFnb3ZlcicsIG5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50KTtcbiAgICBvZmYoZG9jdW1lbnQsICdtb3VzZW1vdmUnLCBuZWFyZXN0RW1wdHlJbnNlcnREZXRlY3RFdmVudCk7XG4gICAgb2ZmKGRvY3VtZW50LCAndG91Y2htb3ZlJywgbmVhcmVzdEVtcHR5SW5zZXJ0RGV0ZWN0RXZlbnQpO1xuICB9LFxuICBfb2ZmVXBFdmVudHM6IGZ1bmN0aW9uIF9vZmZVcEV2ZW50cygpIHtcbiAgICB2YXIgb3duZXJEb2N1bWVudCA9IHRoaXMuZWwub3duZXJEb2N1bWVudDtcbiAgICBvZmYob3duZXJEb2N1bWVudCwgJ21vdXNldXAnLCB0aGlzLl9vbkRyb3ApO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAndG91Y2hlbmQnLCB0aGlzLl9vbkRyb3ApO1xuICAgIG9mZihvd25lckRvY3VtZW50LCAncG9pbnRlcnVwJywgdGhpcy5fb25Ecm9wKTtcbiAgICBvZmYob3duZXJEb2N1bWVudCwgJ3RvdWNoY2FuY2VsJywgdGhpcy5fb25Ecm9wKTtcbiAgICBvZmYoZG9jdW1lbnQsICdzZWxlY3RzdGFydCcsIHRoaXMpO1xuICB9LFxuICBfb25Ecm9wOiBmdW5jdGlvbiBfb25Ecm9wKCAvKipFdmVudCovZXZ0KSB7XG4gICAgdmFyIGVsID0gdGhpcy5lbCxcbiAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG5cbiAgICAvLyBHZXQgdGhlIGluZGV4IG9mIHRoZSBkcmFnZ2VkIGVsZW1lbnQgd2l0aGluIGl0cyBwYXJlbnRcbiAgICBuZXdJbmRleCA9IGluZGV4KGRyYWdFbCk7XG4gICAgbmV3RHJhZ2dhYmxlSW5kZXggPSBpbmRleChkcmFnRWwsIG9wdGlvbnMuZHJhZ2dhYmxlKTtcbiAgICBwbHVnaW5FdmVudCgnZHJvcCcsIHRoaXMsIHtcbiAgICAgIGV2dDogZXZ0XG4gICAgfSk7XG4gICAgcGFyZW50RWwgPSBkcmFnRWwgJiYgZHJhZ0VsLnBhcmVudE5vZGU7XG5cbiAgICAvLyBHZXQgYWdhaW4gYWZ0ZXIgcGx1Z2luIGV2ZW50XG4gICAgbmV3SW5kZXggPSBpbmRleChkcmFnRWwpO1xuICAgIG5ld0RyYWdnYWJsZUluZGV4ID0gaW5kZXgoZHJhZ0VsLCBvcHRpb25zLmRyYWdnYWJsZSk7XG4gICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHtcbiAgICAgIHRoaXMuX251bGxpbmcoKTtcbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgYXdhaXRpbmdEcmFnU3RhcnRlZCA9IGZhbHNlO1xuICAgIGlzQ2lyY3Vtc3RhbnRpYWxJbnZlcnQgPSBmYWxzZTtcbiAgICBwYXN0Rmlyc3RJbnZlcnRUaHJlc2ggPSBmYWxzZTtcbiAgICBjbGVhckludGVydmFsKHRoaXMuX2xvb3BJZCk7XG4gICAgY2xlYXJUaW1lb3V0KHRoaXMuX2RyYWdTdGFydFRpbWVyKTtcbiAgICBfY2FuY2VsTmV4dFRpY2sodGhpcy5jbG9uZUlkKTtcbiAgICBfY2FuY2VsTmV4dFRpY2sodGhpcy5fZHJhZ1N0YXJ0SWQpO1xuXG4gICAgLy8gVW5iaW5kIGV2ZW50c1xuICAgIGlmICh0aGlzLm5hdGl2ZURyYWdnYWJsZSkge1xuICAgICAgb2ZmKGRvY3VtZW50LCAnZHJvcCcsIHRoaXMpO1xuICAgICAgb2ZmKGVsLCAnZHJhZ3N0YXJ0JywgdGhpcy5fb25EcmFnU3RhcnQpO1xuICAgIH1cbiAgICB0aGlzLl9vZmZNb3ZlRXZlbnRzKCk7XG4gICAgdGhpcy5fb2ZmVXBFdmVudHMoKTtcbiAgICBpZiAoU2FmYXJpKSB7XG4gICAgICBjc3MoZG9jdW1lbnQuYm9keSwgJ3VzZXItc2VsZWN0JywgJycpO1xuICAgIH1cbiAgICBjc3MoZHJhZ0VsLCAndHJhbnNmb3JtJywgJycpO1xuICAgIGlmIChldnQpIHtcbiAgICAgIGlmIChtb3ZlZCkge1xuICAgICAgICBldnQuY2FuY2VsYWJsZSAmJiBldnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgIW9wdGlvbnMuZHJvcEJ1YmJsZSAmJiBldnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICB9XG4gICAgICBnaG9zdEVsICYmIGdob3N0RWwucGFyZW50Tm9kZSAmJiBnaG9zdEVsLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoZ2hvc3RFbCk7XG4gICAgICBpZiAocm9vdEVsID09PSBwYXJlbnRFbCB8fCBwdXRTb3J0YWJsZSAmJiBwdXRTb3J0YWJsZS5sYXN0UHV0TW9kZSAhPT0gJ2Nsb25lJykge1xuICAgICAgICAvLyBSZW1vdmUgY2xvbmUocylcbiAgICAgICAgY2xvbmVFbCAmJiBjbG9uZUVsLnBhcmVudE5vZGUgJiYgY2xvbmVFbC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGNsb25lRWwpO1xuICAgICAgfVxuICAgICAgaWYgKGRyYWdFbCkge1xuICAgICAgICBpZiAodGhpcy5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgICBvZmYoZHJhZ0VsLCAnZHJhZ2VuZCcsIHRoaXMpO1xuICAgICAgICB9XG4gICAgICAgIF9kaXNhYmxlRHJhZ2dhYmxlKGRyYWdFbCk7XG4gICAgICAgIGRyYWdFbC5zdHlsZVsnd2lsbC1jaGFuZ2UnXSA9ICcnO1xuXG4gICAgICAgIC8vIFJlbW92ZSBjbGFzc2VzXG4gICAgICAgIC8vIGdob3N0Q2xhc3MgaXMgYWRkZWQgaW4gZHJhZ1N0YXJ0ZWRcbiAgICAgICAgaWYgKG1vdmVkICYmICFhd2FpdGluZ0RyYWdTdGFydGVkKSB7XG4gICAgICAgICAgdG9nZ2xlQ2xhc3MoZHJhZ0VsLCBwdXRTb3J0YWJsZSA/IHB1dFNvcnRhYmxlLm9wdGlvbnMuZ2hvc3RDbGFzcyA6IHRoaXMub3B0aW9ucy5naG9zdENsYXNzLCBmYWxzZSk7XG4gICAgICAgIH1cbiAgICAgICAgdG9nZ2xlQ2xhc3MoZHJhZ0VsLCB0aGlzLm9wdGlvbnMuY2hvc2VuQ2xhc3MsIGZhbHNlKTtcblxuICAgICAgICAvLyBEcmFnIHN0b3AgZXZlbnRcbiAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgIG5hbWU6ICd1bmNob29zZScsXG4gICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgbmV3SW5kZXg6IG51bGwsXG4gICAgICAgICAgbmV3RHJhZ2dhYmxlSW5kZXg6IG51bGwsXG4gICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgIH0pO1xuICAgICAgICBpZiAocm9vdEVsICE9PSBwYXJlbnRFbCkge1xuICAgICAgICAgIGlmIChuZXdJbmRleCA+PSAwKSB7XG4gICAgICAgICAgICAvLyBBZGQgZXZlbnRcbiAgICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgICAgcm9vdEVsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgICAgbmFtZTogJ2FkZCcsXG4gICAgICAgICAgICAgIHRvRWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICBmcm9tRWw6IHJvb3RFbCxcbiAgICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy8gUmVtb3ZlIGV2ZW50XG4gICAgICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgICAgICBuYW1lOiAncmVtb3ZlJyxcbiAgICAgICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIC8vIGRyYWcgZnJvbSBvbmUgbGlzdCBhbmQgZHJvcCBpbnRvIGFub3RoZXJcbiAgICAgICAgICAgIF9kaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgICAgcm9vdEVsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgICAgbmFtZTogJ3NvcnQnLFxuICAgICAgICAgICAgICB0b0VsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgICAgZnJvbUVsOiByb290RWwsXG4gICAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgICAgICBuYW1lOiAnc29ydCcsXG4gICAgICAgICAgICAgIHRvRWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH1cbiAgICAgICAgICBwdXRTb3J0YWJsZSAmJiBwdXRTb3J0YWJsZS5zYXZlKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgaWYgKG5ld0luZGV4ICE9PSBvbGRJbmRleCkge1xuICAgICAgICAgICAgaWYgKG5ld0luZGV4ID49IDApIHtcbiAgICAgICAgICAgICAgLy8gZHJhZyAmIGRyb3Agd2l0aGluIHRoZSBzYW1lIGxpc3RcbiAgICAgICAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgICAgICAgIG5hbWU6ICd1cGRhdGUnLFxuICAgICAgICAgICAgICAgIHRvRWw6IHBhcmVudEVsLFxuICAgICAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgX2Rpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgICAgIHNvcnRhYmxlOiB0aGlzLFxuICAgICAgICAgICAgICAgIG5hbWU6ICdzb3J0JyxcbiAgICAgICAgICAgICAgICB0b0VsOiBwYXJlbnRFbCxcbiAgICAgICAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGlmIChTb3J0YWJsZS5hY3RpdmUpIHtcbiAgICAgICAgICAvKiBqc2hpbnQgZXFudWxsOnRydWUgKi9cbiAgICAgICAgICBpZiAobmV3SW5kZXggPT0gbnVsbCB8fCBuZXdJbmRleCA9PT0gLTEpIHtcbiAgICAgICAgICAgIG5ld0luZGV4ID0gb2xkSW5kZXg7XG4gICAgICAgICAgICBuZXdEcmFnZ2FibGVJbmRleCA9IG9sZERyYWdnYWJsZUluZGV4O1xuICAgICAgICAgIH1cbiAgICAgICAgICBfZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICBzb3J0YWJsZTogdGhpcyxcbiAgICAgICAgICAgIG5hbWU6ICdlbmQnLFxuICAgICAgICAgICAgdG9FbDogcGFyZW50RWwsXG4gICAgICAgICAgICBvcmlnaW5hbEV2ZW50OiBldnRcbiAgICAgICAgICB9KTtcblxuICAgICAgICAgIC8vIFNhdmUgc29ydGluZ1xuICAgICAgICAgIHRoaXMuc2F2ZSgpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICAgIHRoaXMuX251bGxpbmcoKTtcbiAgfSxcbiAgX251bGxpbmc6IGZ1bmN0aW9uIF9udWxsaW5nKCkge1xuICAgIHBsdWdpbkV2ZW50KCdudWxsaW5nJywgdGhpcyk7XG4gICAgcm9vdEVsID0gZHJhZ0VsID0gcGFyZW50RWwgPSBnaG9zdEVsID0gbmV4dEVsID0gY2xvbmVFbCA9IGxhc3REb3duRWwgPSBjbG9uZUhpZGRlbiA9IHRhcEV2dCA9IHRvdWNoRXZ0ID0gbW92ZWQgPSBuZXdJbmRleCA9IG5ld0RyYWdnYWJsZUluZGV4ID0gb2xkSW5kZXggPSBvbGREcmFnZ2FibGVJbmRleCA9IGxhc3RUYXJnZXQgPSBsYXN0RGlyZWN0aW9uID0gcHV0U29ydGFibGUgPSBhY3RpdmVHcm91cCA9IFNvcnRhYmxlLmRyYWdnZWQgPSBTb3J0YWJsZS5naG9zdCA9IFNvcnRhYmxlLmNsb25lID0gU29ydGFibGUuYWN0aXZlID0gbnVsbDtcbiAgICBzYXZlZElucHV0Q2hlY2tlZC5mb3JFYWNoKGZ1bmN0aW9uIChlbCkge1xuICAgICAgZWwuY2hlY2tlZCA9IHRydWU7XG4gICAgfSk7XG4gICAgc2F2ZWRJbnB1dENoZWNrZWQubGVuZ3RoID0gbGFzdER4ID0gbGFzdER5ID0gMDtcbiAgfSxcbiAgaGFuZGxlRXZlbnQ6IGZ1bmN0aW9uIGhhbmRsZUV2ZW50KCAvKipFdmVudCovZXZ0KSB7XG4gICAgc3dpdGNoIChldnQudHlwZSkge1xuICAgICAgY2FzZSAnZHJvcCc6XG4gICAgICBjYXNlICdkcmFnZW5kJzpcbiAgICAgICAgdGhpcy5fb25Ecm9wKGV2dCk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgY2FzZSAnZHJhZ2VudGVyJzpcbiAgICAgIGNhc2UgJ2RyYWdvdmVyJzpcbiAgICAgICAgaWYgKGRyYWdFbCkge1xuICAgICAgICAgIHRoaXMuX29uRHJhZ092ZXIoZXZ0KTtcbiAgICAgICAgICBfZ2xvYmFsRHJhZ092ZXIoZXZ0KTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIGNhc2UgJ3NlbGVjdHN0YXJ0JzpcbiAgICAgICAgZXZ0LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGJyZWFrO1xuICAgIH1cbiAgfSxcbiAgLyoqXHJcbiAgICogU2VyaWFsaXplcyB0aGUgaXRlbSBpbnRvIGFuIGFycmF5IG9mIHN0cmluZy5cclxuICAgKiBAcmV0dXJucyB7U3RyaW5nW119XHJcbiAgICovXG4gIHRvQXJyYXk6IGZ1bmN0aW9uIHRvQXJyYXkoKSB7XG4gICAgdmFyIG9yZGVyID0gW10sXG4gICAgICBlbCxcbiAgICAgIGNoaWxkcmVuID0gdGhpcy5lbC5jaGlsZHJlbixcbiAgICAgIGkgPSAwLFxuICAgICAgbiA9IGNoaWxkcmVuLmxlbmd0aCxcbiAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG4gICAgZm9yICg7IGkgPCBuOyBpKyspIHtcbiAgICAgIGVsID0gY2hpbGRyZW5baV07XG4gICAgICBpZiAoY2xvc2VzdChlbCwgb3B0aW9ucy5kcmFnZ2FibGUsIHRoaXMuZWwsIGZhbHNlKSkge1xuICAgICAgICBvcmRlci5wdXNoKGVsLmdldEF0dHJpYnV0ZShvcHRpb25zLmRhdGFJZEF0dHIpIHx8IF9nZW5lcmF0ZUlkKGVsKSk7XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBvcmRlcjtcbiAgfSxcbiAgLyoqXHJcbiAgICogU29ydHMgdGhlIGVsZW1lbnRzIGFjY29yZGluZyB0byB0aGUgYXJyYXkuXHJcbiAgICogQHBhcmFtICB7U3RyaW5nW119ICBvcmRlciAgb3JkZXIgb2YgdGhlIGl0ZW1zXHJcbiAgICovXG4gIHNvcnQ6IGZ1bmN0aW9uIHNvcnQob3JkZXIsIHVzZUFuaW1hdGlvbikge1xuICAgIHZhciBpdGVtcyA9IHt9LFxuICAgICAgcm9vdEVsID0gdGhpcy5lbDtcbiAgICB0aGlzLnRvQXJyYXkoKS5mb3JFYWNoKGZ1bmN0aW9uIChpZCwgaSkge1xuICAgICAgdmFyIGVsID0gcm9vdEVsLmNoaWxkcmVuW2ldO1xuICAgICAgaWYgKGNsb3Nlc3QoZWwsIHRoaXMub3B0aW9ucy5kcmFnZ2FibGUsIHJvb3RFbCwgZmFsc2UpKSB7XG4gICAgICAgIGl0ZW1zW2lkXSA9IGVsO1xuICAgICAgfVxuICAgIH0sIHRoaXMpO1xuICAgIHVzZUFuaW1hdGlvbiAmJiB0aGlzLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgIG9yZGVyLmZvckVhY2goZnVuY3Rpb24gKGlkKSB7XG4gICAgICBpZiAoaXRlbXNbaWRdKSB7XG4gICAgICAgIHJvb3RFbC5yZW1vdmVDaGlsZChpdGVtc1tpZF0pO1xuICAgICAgICByb290RWwuYXBwZW5kQ2hpbGQoaXRlbXNbaWRdKTtcbiAgICAgIH1cbiAgICB9KTtcbiAgICB1c2VBbmltYXRpb24gJiYgdGhpcy5hbmltYXRlQWxsKCk7XG4gIH0sXG4gIC8qKlxyXG4gICAqIFNhdmUgdGhlIGN1cnJlbnQgc29ydGluZ1xyXG4gICAqL1xuICBzYXZlOiBmdW5jdGlvbiBzYXZlKCkge1xuICAgIHZhciBzdG9yZSA9IHRoaXMub3B0aW9ucy5zdG9yZTtcbiAgICBzdG9yZSAmJiBzdG9yZS5zZXQgJiYgc3RvcmUuc2V0KHRoaXMpO1xuICB9LFxuICAvKipcclxuICAgKiBGb3IgZWFjaCBlbGVtZW50IGluIHRoZSBzZXQsIGdldCB0aGUgZmlyc3QgZWxlbWVudCB0aGF0IG1hdGNoZXMgdGhlIHNlbGVjdG9yIGJ5IHRlc3RpbmcgdGhlIGVsZW1lbnQgaXRzZWxmIGFuZCB0cmF2ZXJzaW5nIHVwIHRocm91Z2ggaXRzIGFuY2VzdG9ycyBpbiB0aGUgRE9NIHRyZWUuXHJcbiAgICogQHBhcmFtICAge0hUTUxFbGVtZW50fSAgZWxcclxuICAgKiBAcGFyYW0gICB7U3RyaW5nfSAgICAgICBbc2VsZWN0b3JdICBkZWZhdWx0OiBgb3B0aW9ucy5kcmFnZ2FibGVgXHJcbiAgICogQHJldHVybnMge0hUTUxFbGVtZW50fG51bGx9XHJcbiAgICovXG4gIGNsb3Nlc3Q6IGZ1bmN0aW9uIGNsb3Nlc3QkMShlbCwgc2VsZWN0b3IpIHtcbiAgICByZXR1cm4gY2xvc2VzdChlbCwgc2VsZWN0b3IgfHwgdGhpcy5vcHRpb25zLmRyYWdnYWJsZSwgdGhpcy5lbCwgZmFsc2UpO1xuICB9LFxuICAvKipcclxuICAgKiBTZXQvZ2V0IG9wdGlvblxyXG4gICAqIEBwYXJhbSAgIHtzdHJpbmd9IG5hbWVcclxuICAgKiBAcGFyYW0gICB7Kn0gICAgICBbdmFsdWVdXHJcbiAgICogQHJldHVybnMgeyp9XHJcbiAgICovXG4gIG9wdGlvbjogZnVuY3Rpb24gb3B0aW9uKG5hbWUsIHZhbHVlKSB7XG4gICAgdmFyIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG4gICAgaWYgKHZhbHVlID09PSB2b2lkIDApIHtcbiAgICAgIHJldHVybiBvcHRpb25zW25hbWVdO1xuICAgIH0gZWxzZSB7XG4gICAgICB2YXIgbW9kaWZpZWRWYWx1ZSA9IFBsdWdpbk1hbmFnZXIubW9kaWZ5T3B0aW9uKHRoaXMsIG5hbWUsIHZhbHVlKTtcbiAgICAgIGlmICh0eXBlb2YgbW9kaWZpZWRWYWx1ZSAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgb3B0aW9uc1tuYW1lXSA9IG1vZGlmaWVkVmFsdWU7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBvcHRpb25zW25hbWVdID0gdmFsdWU7XG4gICAgICB9XG4gICAgICBpZiAobmFtZSA9PT0gJ2dyb3VwJykge1xuICAgICAgICBfcHJlcGFyZUdyb3VwKG9wdGlvbnMpO1xuICAgICAgfVxuICAgIH1cbiAgfSxcbiAgLyoqXHJcbiAgICogRGVzdHJveVxyXG4gICAqL1xuICBkZXN0cm95OiBmdW5jdGlvbiBkZXN0cm95KCkge1xuICAgIHBsdWdpbkV2ZW50KCdkZXN0cm95JywgdGhpcyk7XG4gICAgdmFyIGVsID0gdGhpcy5lbDtcbiAgICBlbFtleHBhbmRvXSA9IG51bGw7XG4gICAgb2ZmKGVsLCAnbW91c2Vkb3duJywgdGhpcy5fb25UYXBTdGFydCk7XG4gICAgb2ZmKGVsLCAndG91Y2hzdGFydCcsIHRoaXMuX29uVGFwU3RhcnQpO1xuICAgIG9mZihlbCwgJ3BvaW50ZXJkb3duJywgdGhpcy5fb25UYXBTdGFydCk7XG4gICAgaWYgKHRoaXMubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgICBvZmYoZWwsICdkcmFnb3ZlcicsIHRoaXMpO1xuICAgICAgb2ZmKGVsLCAnZHJhZ2VudGVyJywgdGhpcyk7XG4gICAgfVxuICAgIC8vIFJlbW92ZSBkcmFnZ2FibGUgYXR0cmlidXRlc1xuICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwoZWwucXVlcnlTZWxlY3RvckFsbCgnW2RyYWdnYWJsZV0nKSwgZnVuY3Rpb24gKGVsKSB7XG4gICAgICBlbC5yZW1vdmVBdHRyaWJ1dGUoJ2RyYWdnYWJsZScpO1xuICAgIH0pO1xuICAgIHRoaXMuX29uRHJvcCgpO1xuICAgIHRoaXMuX2Rpc2FibGVEZWxheWVkRHJhZ0V2ZW50cygpO1xuICAgIHNvcnRhYmxlcy5zcGxpY2Uoc29ydGFibGVzLmluZGV4T2YodGhpcy5lbCksIDEpO1xuICAgIHRoaXMuZWwgPSBlbCA9IG51bGw7XG4gIH0sXG4gIF9oaWRlQ2xvbmU6IGZ1bmN0aW9uIF9oaWRlQ2xvbmUoKSB7XG4gICAgaWYgKCFjbG9uZUhpZGRlbikge1xuICAgICAgcGx1Z2luRXZlbnQoJ2hpZGVDbG9uZScsIHRoaXMpO1xuICAgICAgaWYgKFNvcnRhYmxlLmV2ZW50Q2FuY2VsZWQpIHJldHVybjtcbiAgICAgIGNzcyhjbG9uZUVsLCAnZGlzcGxheScsICdub25lJyk7XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnJlbW92ZUNsb25lT25IaWRlICYmIGNsb25lRWwucGFyZW50Tm9kZSkge1xuICAgICAgICBjbG9uZUVsLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY2xvbmVFbCk7XG4gICAgICB9XG4gICAgICBjbG9uZUhpZGRlbiA9IHRydWU7XG4gICAgfVxuICB9LFxuICBfc2hvd0Nsb25lOiBmdW5jdGlvbiBfc2hvd0Nsb25lKHB1dFNvcnRhYmxlKSB7XG4gICAgaWYgKHB1dFNvcnRhYmxlLmxhc3RQdXRNb2RlICE9PSAnY2xvbmUnKSB7XG4gICAgICB0aGlzLl9oaWRlQ2xvbmUoKTtcbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgaWYgKGNsb25lSGlkZGVuKSB7XG4gICAgICBwbHVnaW5FdmVudCgnc2hvd0Nsb25lJywgdGhpcyk7XG4gICAgICBpZiAoU29ydGFibGUuZXZlbnRDYW5jZWxlZCkgcmV0dXJuO1xuXG4gICAgICAvLyBzaG93IGNsb25lIGF0IGRyYWdFbCBvciBvcmlnaW5hbCBwb3NpdGlvblxuICAgICAgaWYgKGRyYWdFbC5wYXJlbnROb2RlID09IHJvb3RFbCAmJiAhdGhpcy5vcHRpb25zLmdyb3VwLnJldmVydENsb25lKSB7XG4gICAgICAgIHJvb3RFbC5pbnNlcnRCZWZvcmUoY2xvbmVFbCwgZHJhZ0VsKTtcbiAgICAgIH0gZWxzZSBpZiAobmV4dEVsKSB7XG4gICAgICAgIHJvb3RFbC5pbnNlcnRCZWZvcmUoY2xvbmVFbCwgbmV4dEVsKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHJvb3RFbC5hcHBlbmRDaGlsZChjbG9uZUVsKTtcbiAgICAgIH1cbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuZ3JvdXAucmV2ZXJ0Q2xvbmUpIHtcbiAgICAgICAgdGhpcy5hbmltYXRlKGRyYWdFbCwgY2xvbmVFbCk7XG4gICAgICB9XG4gICAgICBjc3MoY2xvbmVFbCwgJ2Rpc3BsYXknLCAnJyk7XG4gICAgICBjbG9uZUhpZGRlbiA9IGZhbHNlO1xuICAgIH1cbiAgfVxufTtcbmZ1bmN0aW9uIF9nbG9iYWxEcmFnT3ZlciggLyoqRXZlbnQqL2V2dCkge1xuICBpZiAoZXZ0LmRhdGFUcmFuc2Zlcikge1xuICAgIGV2dC5kYXRhVHJhbnNmZXIuZHJvcEVmZmVjdCA9ICdtb3ZlJztcbiAgfVxuICBldnQuY2FuY2VsYWJsZSAmJiBldnQucHJldmVudERlZmF1bHQoKTtcbn1cbmZ1bmN0aW9uIF9vbk1vdmUoZnJvbUVsLCB0b0VsLCBkcmFnRWwsIGRyYWdSZWN0LCB0YXJnZXRFbCwgdGFyZ2V0UmVjdCwgb3JpZ2luYWxFdmVudCwgd2lsbEluc2VydEFmdGVyKSB7XG4gIHZhciBldnQsXG4gICAgc29ydGFibGUgPSBmcm9tRWxbZXhwYW5kb10sXG4gICAgb25Nb3ZlRm4gPSBzb3J0YWJsZS5vcHRpb25zLm9uTW92ZSxcbiAgICByZXRWYWw7XG4gIC8vIFN1cHBvcnQgZm9yIG5ldyBDdXN0b21FdmVudCBmZWF0dXJlXG4gIGlmICh3aW5kb3cuQ3VzdG9tRXZlbnQgJiYgIUlFMTFPckxlc3MgJiYgIUVkZ2UpIHtcbiAgICBldnQgPSBuZXcgQ3VzdG9tRXZlbnQoJ21vdmUnLCB7XG4gICAgICBidWJibGVzOiB0cnVlLFxuICAgICAgY2FuY2VsYWJsZTogdHJ1ZVxuICAgIH0pO1xuICB9IGVsc2Uge1xuICAgIGV2dCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KCdFdmVudCcpO1xuICAgIGV2dC5pbml0RXZlbnQoJ21vdmUnLCB0cnVlLCB0cnVlKTtcbiAgfVxuICBldnQudG8gPSB0b0VsO1xuICBldnQuZnJvbSA9IGZyb21FbDtcbiAgZXZ0LmRyYWdnZWQgPSBkcmFnRWw7XG4gIGV2dC5kcmFnZ2VkUmVjdCA9IGRyYWdSZWN0O1xuICBldnQucmVsYXRlZCA9IHRhcmdldEVsIHx8IHRvRWw7XG4gIGV2dC5yZWxhdGVkUmVjdCA9IHRhcmdldFJlY3QgfHwgZ2V0UmVjdCh0b0VsKTtcbiAgZXZ0LndpbGxJbnNlcnRBZnRlciA9IHdpbGxJbnNlcnRBZnRlcjtcbiAgZXZ0Lm9yaWdpbmFsRXZlbnQgPSBvcmlnaW5hbEV2ZW50O1xuICBmcm9tRWwuZGlzcGF0Y2hFdmVudChldnQpO1xuICBpZiAob25Nb3ZlRm4pIHtcbiAgICByZXRWYWwgPSBvbk1vdmVGbi5jYWxsKHNvcnRhYmxlLCBldnQsIG9yaWdpbmFsRXZlbnQpO1xuICB9XG4gIHJldHVybiByZXRWYWw7XG59XG5mdW5jdGlvbiBfZGlzYWJsZURyYWdnYWJsZShlbCkge1xuICBlbC5kcmFnZ2FibGUgPSBmYWxzZTtcbn1cbmZ1bmN0aW9uIF91bnNpbGVudCgpIHtcbiAgX3NpbGVudCA9IGZhbHNlO1xufVxuZnVuY3Rpb24gX2dob3N0SXNGaXJzdChldnQsIHZlcnRpY2FsLCBzb3J0YWJsZSkge1xuICB2YXIgZmlyc3RFbFJlY3QgPSBnZXRSZWN0KGdldENoaWxkKHNvcnRhYmxlLmVsLCAwLCBzb3J0YWJsZS5vcHRpb25zLCB0cnVlKSk7XG4gIHZhciBjaGlsZENvbnRhaW5pbmdSZWN0ID0gZ2V0Q2hpbGRDb250YWluaW5nUmVjdEZyb21FbGVtZW50KHNvcnRhYmxlLmVsLCBzb3J0YWJsZS5vcHRpb25zLCBnaG9zdEVsKTtcbiAgdmFyIHNwYWNlciA9IDEwO1xuICByZXR1cm4gdmVydGljYWwgPyBldnQuY2xpZW50WCA8IGNoaWxkQ29udGFpbmluZ1JlY3QubGVmdCAtIHNwYWNlciB8fCBldnQuY2xpZW50WSA8IGZpcnN0RWxSZWN0LnRvcCAmJiBldnQuY2xpZW50WCA8IGZpcnN0RWxSZWN0LnJpZ2h0IDogZXZ0LmNsaWVudFkgPCBjaGlsZENvbnRhaW5pbmdSZWN0LnRvcCAtIHNwYWNlciB8fCBldnQuY2xpZW50WSA8IGZpcnN0RWxSZWN0LmJvdHRvbSAmJiBldnQuY2xpZW50WCA8IGZpcnN0RWxSZWN0LmxlZnQ7XG59XG5mdW5jdGlvbiBfZ2hvc3RJc0xhc3QoZXZ0LCB2ZXJ0aWNhbCwgc29ydGFibGUpIHtcbiAgdmFyIGxhc3RFbFJlY3QgPSBnZXRSZWN0KGxhc3RDaGlsZChzb3J0YWJsZS5lbCwgc29ydGFibGUub3B0aW9ucy5kcmFnZ2FibGUpKTtcbiAgdmFyIGNoaWxkQ29udGFpbmluZ1JlY3QgPSBnZXRDaGlsZENvbnRhaW5pbmdSZWN0RnJvbUVsZW1lbnQoc29ydGFibGUuZWwsIHNvcnRhYmxlLm9wdGlvbnMsIGdob3N0RWwpO1xuICB2YXIgc3BhY2VyID0gMTA7XG4gIHJldHVybiB2ZXJ0aWNhbCA/IGV2dC5jbGllbnRYID4gY2hpbGRDb250YWluaW5nUmVjdC5yaWdodCArIHNwYWNlciB8fCBldnQuY2xpZW50WSA+IGxhc3RFbFJlY3QuYm90dG9tICYmIGV2dC5jbGllbnRYID4gbGFzdEVsUmVjdC5sZWZ0IDogZXZ0LmNsaWVudFkgPiBjaGlsZENvbnRhaW5pbmdSZWN0LmJvdHRvbSArIHNwYWNlciB8fCBldnQuY2xpZW50WCA+IGxhc3RFbFJlY3QucmlnaHQgJiYgZXZ0LmNsaWVudFkgPiBsYXN0RWxSZWN0LnRvcDtcbn1cbmZ1bmN0aW9uIF9nZXRTd2FwRGlyZWN0aW9uKGV2dCwgdGFyZ2V0LCB0YXJnZXRSZWN0LCB2ZXJ0aWNhbCwgc3dhcFRocmVzaG9sZCwgaW52ZXJ0ZWRTd2FwVGhyZXNob2xkLCBpbnZlcnRTd2FwLCBpc0xhc3RUYXJnZXQpIHtcbiAgdmFyIG1vdXNlT25BeGlzID0gdmVydGljYWwgPyBldnQuY2xpZW50WSA6IGV2dC5jbGllbnRYLFxuICAgIHRhcmdldExlbmd0aCA9IHZlcnRpY2FsID8gdGFyZ2V0UmVjdC5oZWlnaHQgOiB0YXJnZXRSZWN0LndpZHRoLFxuICAgIHRhcmdldFMxID0gdmVydGljYWwgPyB0YXJnZXRSZWN0LnRvcCA6IHRhcmdldFJlY3QubGVmdCxcbiAgICB0YXJnZXRTMiA9IHZlcnRpY2FsID8gdGFyZ2V0UmVjdC5ib3R0b20gOiB0YXJnZXRSZWN0LnJpZ2h0LFxuICAgIGludmVydCA9IGZhbHNlO1xuICBpZiAoIWludmVydFN3YXApIHtcbiAgICAvLyBOZXZlciBpbnZlcnQgb3IgY3JlYXRlIGRyYWdFbCBzaGFkb3cgd2hlbiB0YXJnZXQgbW92ZW1lbmV0IGNhdXNlcyBtb3VzZSB0byBtb3ZlIHBhc3QgdGhlIGVuZCBvZiByZWd1bGFyIHN3YXBUaHJlc2hvbGRcbiAgICBpZiAoaXNMYXN0VGFyZ2V0ICYmIHRhcmdldE1vdmVEaXN0YW5jZSA8IHRhcmdldExlbmd0aCAqIHN3YXBUaHJlc2hvbGQpIHtcbiAgICAgIC8vIG11bHRpcGxpZWQgb25seSBieSBzd2FwVGhyZXNob2xkIGJlY2F1c2UgbW91c2Ugd2lsbCBhbHJlYWR5IGJlIGluc2lkZSB0YXJnZXQgYnkgKDEgLSB0aHJlc2hvbGQpICogdGFyZ2V0TGVuZ3RoIC8gMlxuICAgICAgLy8gY2hlY2sgaWYgcGFzdCBmaXJzdCBpbnZlcnQgdGhyZXNob2xkIG9uIHNpZGUgb3Bwb3NpdGUgb2YgbGFzdERpcmVjdGlvblxuICAgICAgaWYgKCFwYXN0Rmlyc3RJbnZlcnRUaHJlc2ggJiYgKGxhc3REaXJlY3Rpb24gPT09IDEgPyBtb3VzZU9uQXhpcyA+IHRhcmdldFMxICsgdGFyZ2V0TGVuZ3RoICogaW52ZXJ0ZWRTd2FwVGhyZXNob2xkIC8gMiA6IG1vdXNlT25BeGlzIDwgdGFyZ2V0UzIgLSB0YXJnZXRMZW5ndGggKiBpbnZlcnRlZFN3YXBUaHJlc2hvbGQgLyAyKSkge1xuICAgICAgICAvLyBwYXN0IGZpcnN0IGludmVydCB0aHJlc2hvbGQsIGRvIG5vdCByZXN0cmljdCBpbnZlcnRlZCB0aHJlc2hvbGQgdG8gZHJhZ0VsIHNoYWRvd1xuICAgICAgICBwYXN0Rmlyc3RJbnZlcnRUaHJlc2ggPSB0cnVlO1xuICAgICAgfVxuICAgICAgaWYgKCFwYXN0Rmlyc3RJbnZlcnRUaHJlc2gpIHtcbiAgICAgICAgLy8gZHJhZ0VsIHNoYWRvdyAodGFyZ2V0IG1vdmUgZGlzdGFuY2Ugc2hhZG93KVxuICAgICAgICBpZiAobGFzdERpcmVjdGlvbiA9PT0gMSA/IG1vdXNlT25BeGlzIDwgdGFyZ2V0UzEgKyB0YXJnZXRNb3ZlRGlzdGFuY2UgLy8gb3ZlciBkcmFnRWwgc2hhZG93XG4gICAgICAgIDogbW91c2VPbkF4aXMgPiB0YXJnZXRTMiAtIHRhcmdldE1vdmVEaXN0YW5jZSkge1xuICAgICAgICAgIHJldHVybiAtbGFzdERpcmVjdGlvbjtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIHtcbiAgICAgICAgaW52ZXJ0ID0gdHJ1ZTtcbiAgICAgIH1cbiAgICB9IGVsc2Uge1xuICAgICAgLy8gUmVndWxhclxuICAgICAgaWYgKG1vdXNlT25BeGlzID4gdGFyZ2V0UzEgKyB0YXJnZXRMZW5ndGggKiAoMSAtIHN3YXBUaHJlc2hvbGQpIC8gMiAmJiBtb3VzZU9uQXhpcyA8IHRhcmdldFMyIC0gdGFyZ2V0TGVuZ3RoICogKDEgLSBzd2FwVGhyZXNob2xkKSAvIDIpIHtcbiAgICAgICAgcmV0dXJuIF9nZXRJbnNlcnREaXJlY3Rpb24odGFyZ2V0KTtcbiAgICAgIH1cbiAgICB9XG4gIH1cbiAgaW52ZXJ0ID0gaW52ZXJ0IHx8IGludmVydFN3YXA7XG4gIGlmIChpbnZlcnQpIHtcbiAgICAvLyBJbnZlcnQgb2YgcmVndWxhclxuICAgIGlmIChtb3VzZU9uQXhpcyA8IHRhcmdldFMxICsgdGFyZ2V0TGVuZ3RoICogaW52ZXJ0ZWRTd2FwVGhyZXNob2xkIC8gMiB8fCBtb3VzZU9uQXhpcyA+IHRhcmdldFMyIC0gdGFyZ2V0TGVuZ3RoICogaW52ZXJ0ZWRTd2FwVGhyZXNob2xkIC8gMikge1xuICAgICAgcmV0dXJuIG1vdXNlT25BeGlzID4gdGFyZ2V0UzEgKyB0YXJnZXRMZW5ndGggLyAyID8gMSA6IC0xO1xuICAgIH1cbiAgfVxuICByZXR1cm4gMDtcbn1cblxuLyoqXHJcbiAqIEdldHMgdGhlIGRpcmVjdGlvbiBkcmFnRWwgbXVzdCBiZSBzd2FwcGVkIHJlbGF0aXZlIHRvIHRhcmdldCBpbiBvcmRlciB0byBtYWtlIGl0XHJcbiAqIHNlZW0gdGhhdCBkcmFnRWwgaGFzIGJlZW4gXCJpbnNlcnRlZFwiIGludG8gdGhhdCBlbGVtZW50J3MgcG9zaXRpb25cclxuICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IHRhcmdldCAgICAgICBUaGUgdGFyZ2V0IHdob3NlIHBvc2l0aW9uIGRyYWdFbCBpcyBiZWluZyBpbnNlcnRlZCBhdFxyXG4gKiBAcmV0dXJuIHtOdW1iZXJ9ICAgICAgICAgICAgICAgICAgIERpcmVjdGlvbiBkcmFnRWwgbXVzdCBiZSBzd2FwcGVkXHJcbiAqL1xuZnVuY3Rpb24gX2dldEluc2VydERpcmVjdGlvbih0YXJnZXQpIHtcbiAgaWYgKGluZGV4KGRyYWdFbCkgPCBpbmRleCh0YXJnZXQpKSB7XG4gICAgcmV0dXJuIDE7XG4gIH0gZWxzZSB7XG4gICAgcmV0dXJuIC0xO1xuICB9XG59XG5cbi8qKlxyXG4gKiBHZW5lcmF0ZSBpZFxyXG4gKiBAcGFyYW0gICB7SFRNTEVsZW1lbnR9IGVsXHJcbiAqIEByZXR1cm5zIHtTdHJpbmd9XHJcbiAqIEBwcml2YXRlXHJcbiAqL1xuZnVuY3Rpb24gX2dlbmVyYXRlSWQoZWwpIHtcbiAgdmFyIHN0ciA9IGVsLnRhZ05hbWUgKyBlbC5jbGFzc05hbWUgKyBlbC5zcmMgKyBlbC5ocmVmICsgZWwudGV4dENvbnRlbnQsXG4gICAgaSA9IHN0ci5sZW5ndGgsXG4gICAgc3VtID0gMDtcbiAgd2hpbGUgKGktLSkge1xuICAgIHN1bSArPSBzdHIuY2hhckNvZGVBdChpKTtcbiAgfVxuICByZXR1cm4gc3VtLnRvU3RyaW5nKDM2KTtcbn1cbmZ1bmN0aW9uIF9zYXZlSW5wdXRDaGVja2VkU3RhdGUocm9vdCkge1xuICBzYXZlZElucHV0Q2hlY2tlZC5sZW5ndGggPSAwO1xuICB2YXIgaW5wdXRzID0gcm9vdC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnaW5wdXQnKTtcbiAgdmFyIGlkeCA9IGlucHV0cy5sZW5ndGg7XG4gIHdoaWxlIChpZHgtLSkge1xuICAgIHZhciBlbCA9IGlucHV0c1tpZHhdO1xuICAgIGVsLmNoZWNrZWQgJiYgc2F2ZWRJbnB1dENoZWNrZWQucHVzaChlbCk7XG4gIH1cbn1cbmZ1bmN0aW9uIF9uZXh0VGljayhmbikge1xuICByZXR1cm4gc2V0VGltZW91dChmbiwgMCk7XG59XG5mdW5jdGlvbiBfY2FuY2VsTmV4dFRpY2soaWQpIHtcbiAgcmV0dXJuIGNsZWFyVGltZW91dChpZCk7XG59XG5cbi8vIEZpeGVkICM5NzM6XG5pZiAoZG9jdW1lbnRFeGlzdHMpIHtcbiAgb24oZG9jdW1lbnQsICd0b3VjaG1vdmUnLCBmdW5jdGlvbiAoZXZ0KSB7XG4gICAgaWYgKChTb3J0YWJsZS5hY3RpdmUgfHwgYXdhaXRpbmdEcmFnU3RhcnRlZCkgJiYgZXZ0LmNhbmNlbGFibGUpIHtcbiAgICAgIGV2dC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIH1cbiAgfSk7XG59XG5cbi8vIEV4cG9ydCB1dGlsc1xuU29ydGFibGUudXRpbHMgPSB7XG4gIG9uOiBvbixcbiAgb2ZmOiBvZmYsXG4gIGNzczogY3NzLFxuICBmaW5kOiBmaW5kLFxuICBpczogZnVuY3Rpb24gaXMoZWwsIHNlbGVjdG9yKSB7XG4gICAgcmV0dXJuICEhY2xvc2VzdChlbCwgc2VsZWN0b3IsIGVsLCBmYWxzZSk7XG4gIH0sXG4gIGV4dGVuZDogZXh0ZW5kLFxuICB0aHJvdHRsZTogdGhyb3R0bGUsXG4gIGNsb3Nlc3Q6IGNsb3Nlc3QsXG4gIHRvZ2dsZUNsYXNzOiB0b2dnbGVDbGFzcyxcbiAgY2xvbmU6IGNsb25lLFxuICBpbmRleDogaW5kZXgsXG4gIG5leHRUaWNrOiBfbmV4dFRpY2ssXG4gIGNhbmNlbE5leHRUaWNrOiBfY2FuY2VsTmV4dFRpY2ssXG4gIGRldGVjdERpcmVjdGlvbjogX2RldGVjdERpcmVjdGlvbixcbiAgZ2V0Q2hpbGQ6IGdldENoaWxkXG59O1xuXG4vKipcclxuICogR2V0IHRoZSBTb3J0YWJsZSBpbnN0YW5jZSBvZiBhbiBlbGVtZW50XHJcbiAqIEBwYXJhbSAge0hUTUxFbGVtZW50fSBlbGVtZW50IFRoZSBlbGVtZW50XHJcbiAqIEByZXR1cm4ge1NvcnRhYmxlfHVuZGVmaW5lZH0gICAgICAgICBUaGUgaW5zdGFuY2Ugb2YgU29ydGFibGVcclxuICovXG5Tb3J0YWJsZS5nZXQgPSBmdW5jdGlvbiAoZWxlbWVudCkge1xuICByZXR1cm4gZWxlbWVudFtleHBhbmRvXTtcbn07XG5cbi8qKlxyXG4gKiBNb3VudCBhIHBsdWdpbiB0byBTb3J0YWJsZVxyXG4gKiBAcGFyYW0gIHsuLi5Tb3J0YWJsZVBsdWdpbnxTb3J0YWJsZVBsdWdpbltdfSBwbHVnaW5zICAgICAgIFBsdWdpbnMgYmVpbmcgbW91bnRlZFxyXG4gKi9cblNvcnRhYmxlLm1vdW50ID0gZnVuY3Rpb24gKCkge1xuICBmb3IgKHZhciBfbGVuID0gYXJndW1lbnRzLmxlbmd0aCwgcGx1Z2lucyA9IG5ldyBBcnJheShfbGVuKSwgX2tleSA9IDA7IF9rZXkgPCBfbGVuOyBfa2V5KyspIHtcbiAgICBwbHVnaW5zW19rZXldID0gYXJndW1lbnRzW19rZXldO1xuICB9XG4gIGlmIChwbHVnaW5zWzBdLmNvbnN0cnVjdG9yID09PSBBcnJheSkgcGx1Z2lucyA9IHBsdWdpbnNbMF07XG4gIHBsdWdpbnMuZm9yRWFjaChmdW5jdGlvbiAocGx1Z2luKSB7XG4gICAgaWYgKCFwbHVnaW4ucHJvdG90eXBlIHx8ICFwbHVnaW4ucHJvdG90eXBlLmNvbnN0cnVjdG9yKSB7XG4gICAgICB0aHJvdyBcIlNvcnRhYmxlOiBNb3VudGVkIHBsdWdpbiBtdXN0IGJlIGEgY29uc3RydWN0b3IgZnVuY3Rpb24sIG5vdCBcIi5jb25jYXQoe30udG9TdHJpbmcuY2FsbChwbHVnaW4pKTtcbiAgICB9XG4gICAgaWYgKHBsdWdpbi51dGlscykgU29ydGFibGUudXRpbHMgPSBfb2JqZWN0U3ByZWFkMihfb2JqZWN0U3ByZWFkMih7fSwgU29ydGFibGUudXRpbHMpLCBwbHVnaW4udXRpbHMpO1xuICAgIFBsdWdpbk1hbmFnZXIubW91bnQocGx1Z2luKTtcbiAgfSk7XG59O1xuXG4vKipcclxuICogQ3JlYXRlIHNvcnRhYmxlIGluc3RhbmNlXHJcbiAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9ICBlbFxyXG4gKiBAcGFyYW0ge09iamVjdH0gICAgICBbb3B0aW9uc11cclxuICovXG5Tb3J0YWJsZS5jcmVhdGUgPSBmdW5jdGlvbiAoZWwsIG9wdGlvbnMpIHtcbiAgcmV0dXJuIG5ldyBTb3J0YWJsZShlbCwgb3B0aW9ucyk7XG59O1xuXG4vLyBFeHBvcnRcblNvcnRhYmxlLnZlcnNpb24gPSB2ZXJzaW9uO1xuXG52YXIgYXV0b1Njcm9sbHMgPSBbXSxcbiAgc2Nyb2xsRWwsXG4gIHNjcm9sbFJvb3RFbCxcbiAgc2Nyb2xsaW5nID0gZmFsc2UsXG4gIGxhc3RBdXRvU2Nyb2xsWCxcbiAgbGFzdEF1dG9TY3JvbGxZLFxuICB0b3VjaEV2dCQxLFxuICBwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbDtcbmZ1bmN0aW9uIEF1dG9TY3JvbGxQbHVnaW4oKSB7XG4gIGZ1bmN0aW9uIEF1dG9TY3JvbGwoKSB7XG4gICAgdGhpcy5kZWZhdWx0cyA9IHtcbiAgICAgIHNjcm9sbDogdHJ1ZSxcbiAgICAgIGZvcmNlQXV0b1Njcm9sbEZhbGxiYWNrOiBmYWxzZSxcbiAgICAgIHNjcm9sbFNlbnNpdGl2aXR5OiAzMCxcbiAgICAgIHNjcm9sbFNwZWVkOiAxMCxcbiAgICAgIGJ1YmJsZVNjcm9sbDogdHJ1ZVxuICAgIH07XG5cbiAgICAvLyBCaW5kIGFsbCBwcml2YXRlIG1ldGhvZHNcbiAgICBmb3IgKHZhciBmbiBpbiB0aGlzKSB7XG4gICAgICBpZiAoZm4uY2hhckF0KDApID09PSAnXycgJiYgdHlwZW9mIHRoaXNbZm5dID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgIHRoaXNbZm5dID0gdGhpc1tmbl0uYmluZCh0aGlzKTtcbiAgICAgIH1cbiAgICB9XG4gIH1cbiAgQXV0b1Njcm9sbC5wcm90b3R5cGUgPSB7XG4gICAgZHJhZ1N0YXJ0ZWQ6IGZ1bmN0aW9uIGRyYWdTdGFydGVkKF9yZWYpIHtcbiAgICAgIHZhciBvcmlnaW5hbEV2ZW50ID0gX3JlZi5vcmlnaW5hbEV2ZW50O1xuICAgICAgaWYgKHRoaXMuc29ydGFibGUubmF0aXZlRHJhZ2dhYmxlKSB7XG4gICAgICAgIG9uKGRvY3VtZW50LCAnZHJhZ292ZXInLCB0aGlzLl9oYW5kbGVBdXRvU2Nyb2xsKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuc3VwcG9ydFBvaW50ZXIpIHtcbiAgICAgICAgICBvbihkb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgdGhpcy5faGFuZGxlRmFsbGJhY2tBdXRvU2Nyb2xsKTtcbiAgICAgICAgfSBlbHNlIGlmIChvcmlnaW5hbEV2ZW50LnRvdWNoZXMpIHtcbiAgICAgICAgICBvbihkb2N1bWVudCwgJ3RvdWNobW92ZScsIHRoaXMuX2hhbmRsZUZhbGxiYWNrQXV0b1Njcm9sbCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgb24oZG9jdW1lbnQsICdtb3VzZW1vdmUnLCB0aGlzLl9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfSxcbiAgICBkcmFnT3ZlckNvbXBsZXRlZDogZnVuY3Rpb24gZHJhZ092ZXJDb21wbGV0ZWQoX3JlZjIpIHtcbiAgICAgIHZhciBvcmlnaW5hbEV2ZW50ID0gX3JlZjIub3JpZ2luYWxFdmVudDtcbiAgICAgIC8vIEZvciB3aGVuIGJ1YmJsaW5nIGlzIGNhbmNlbGVkIGFuZCB1c2luZyBmYWxsYmFjayAoZmFsbGJhY2sgJ3RvdWNobW92ZScgYWx3YXlzIHJlYWNoZWQpXG4gICAgICBpZiAoIXRoaXMub3B0aW9ucy5kcmFnT3ZlckJ1YmJsZSAmJiAhb3JpZ2luYWxFdmVudC5yb290RWwpIHtcbiAgICAgICAgdGhpcy5faGFuZGxlQXV0b1Njcm9sbChvcmlnaW5hbEV2ZW50KTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGRyb3A6IGZ1bmN0aW9uIGRyb3AoKSB7XG4gICAgICBpZiAodGhpcy5zb3J0YWJsZS5uYXRpdmVEcmFnZ2FibGUpIHtcbiAgICAgICAgb2ZmKGRvY3VtZW50LCAnZHJhZ292ZXInLCB0aGlzLl9oYW5kbGVBdXRvU2Nyb2xsKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIG9mZihkb2N1bWVudCwgJ3BvaW50ZXJtb3ZlJywgdGhpcy5faGFuZGxlRmFsbGJhY2tBdXRvU2Nyb2xsKTtcbiAgICAgICAgb2ZmKGRvY3VtZW50LCAndG91Y2htb3ZlJywgdGhpcy5faGFuZGxlRmFsbGJhY2tBdXRvU2Nyb2xsKTtcbiAgICAgICAgb2ZmKGRvY3VtZW50LCAnbW91c2Vtb3ZlJywgdGhpcy5faGFuZGxlRmFsbGJhY2tBdXRvU2Nyb2xsKTtcbiAgICAgIH1cbiAgICAgIGNsZWFyUG9pbnRlckVsZW1DaGFuZ2VkSW50ZXJ2YWwoKTtcbiAgICAgIGNsZWFyQXV0b1Njcm9sbHMoKTtcbiAgICAgIGNhbmNlbFRocm90dGxlKCk7XG4gICAgfSxcbiAgICBudWxsaW5nOiBmdW5jdGlvbiBudWxsaW5nKCkge1xuICAgICAgdG91Y2hFdnQkMSA9IHNjcm9sbFJvb3RFbCA9IHNjcm9sbEVsID0gc2Nyb2xsaW5nID0gcG9pbnRlckVsZW1DaGFuZ2VkSW50ZXJ2YWwgPSBsYXN0QXV0b1Njcm9sbFggPSBsYXN0QXV0b1Njcm9sbFkgPSBudWxsO1xuICAgICAgYXV0b1Njcm9sbHMubGVuZ3RoID0gMDtcbiAgICB9LFxuICAgIF9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGw6IGZ1bmN0aW9uIF9oYW5kbGVGYWxsYmFja0F1dG9TY3JvbGwoZXZ0KSB7XG4gICAgICB0aGlzLl9oYW5kbGVBdXRvU2Nyb2xsKGV2dCwgdHJ1ZSk7XG4gICAgfSxcbiAgICBfaGFuZGxlQXV0b1Njcm9sbDogZnVuY3Rpb24gX2hhbmRsZUF1dG9TY3JvbGwoZXZ0LCBmYWxsYmFjaykge1xuICAgICAgdmFyIF90aGlzID0gdGhpcztcbiAgICAgIHZhciB4ID0gKGV2dC50b3VjaGVzID8gZXZ0LnRvdWNoZXNbMF0gOiBldnQpLmNsaWVudFgsXG4gICAgICAgIHkgPSAoZXZ0LnRvdWNoZXMgPyBldnQudG91Y2hlc1swXSA6IGV2dCkuY2xpZW50WSxcbiAgICAgICAgZWxlbSA9IGRvY3VtZW50LmVsZW1lbnRGcm9tUG9pbnQoeCwgeSk7XG4gICAgICB0b3VjaEV2dCQxID0gZXZ0O1xuXG4gICAgICAvLyBJRSBkb2VzIG5vdCBzZWVtIHRvIGhhdmUgbmF0aXZlIGF1dG9zY3JvbGwsXG4gICAgICAvLyBFZGdlJ3MgYXV0b3Njcm9sbCBzZWVtcyB0b28gY29uZGl0aW9uYWwsXG4gICAgICAvLyBNQUNPUyBTYWZhcmkgZG9lcyBub3QgaGF2ZSBhdXRvc2Nyb2xsLFxuICAgICAgLy8gRmlyZWZveCBhbmQgQ2hyb21lIGFyZSBnb29kXG4gICAgICBpZiAoZmFsbGJhY2sgfHwgdGhpcy5vcHRpb25zLmZvcmNlQXV0b1Njcm9sbEZhbGxiYWNrIHx8IEVkZ2UgfHwgSUUxMU9yTGVzcyB8fCBTYWZhcmkpIHtcbiAgICAgICAgYXV0b1Njcm9sbChldnQsIHRoaXMub3B0aW9ucywgZWxlbSwgZmFsbGJhY2spO1xuXG4gICAgICAgIC8vIExpc3RlbmVyIGZvciBwb2ludGVyIGVsZW1lbnQgY2hhbmdlXG4gICAgICAgIHZhciBvZ0VsZW1TY3JvbGxlciA9IGdldFBhcmVudEF1dG9TY3JvbGxFbGVtZW50KGVsZW0sIHRydWUpO1xuICAgICAgICBpZiAoc2Nyb2xsaW5nICYmICghcG9pbnRlckVsZW1DaGFuZ2VkSW50ZXJ2YWwgfHwgeCAhPT0gbGFzdEF1dG9TY3JvbGxYIHx8IHkgIT09IGxhc3RBdXRvU2Nyb2xsWSkpIHtcbiAgICAgICAgICBwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCAmJiBjbGVhclBvaW50ZXJFbGVtQ2hhbmdlZEludGVydmFsKCk7XG4gICAgICAgICAgLy8gRGV0ZWN0IGZvciBwb2ludGVyIGVsZW0gY2hhbmdlLCBlbXVsYXRpbmcgbmF0aXZlIERuRCBiZWhhdmlvdXJcbiAgICAgICAgICBwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCA9IHNldEludGVydmFsKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBuZXdFbGVtID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZG9jdW1lbnQuZWxlbWVudEZyb21Qb2ludCh4LCB5KSwgdHJ1ZSk7XG4gICAgICAgICAgICBpZiAobmV3RWxlbSAhPT0gb2dFbGVtU2Nyb2xsZXIpIHtcbiAgICAgICAgICAgICAgb2dFbGVtU2Nyb2xsZXIgPSBuZXdFbGVtO1xuICAgICAgICAgICAgICBjbGVhckF1dG9TY3JvbGxzKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBhdXRvU2Nyb2xsKGV2dCwgX3RoaXMub3B0aW9ucywgbmV3RWxlbSwgZmFsbGJhY2spO1xuICAgICAgICAgIH0sIDEwKTtcbiAgICAgICAgICBsYXN0QXV0b1Njcm9sbFggPSB4O1xuICAgICAgICAgIGxhc3RBdXRvU2Nyb2xsWSA9IHk7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIC8vIGlmIERuRCBpcyBlbmFibGVkIChhbmQgYnJvd3NlciBoYXMgZ29vZCBhdXRvc2Nyb2xsaW5nKSwgZmlyc3QgYXV0b3Njcm9sbCB3aWxsIGFscmVhZHkgc2Nyb2xsLCBzbyBnZXQgcGFyZW50IGF1dG9zY3JvbGwgb2YgZmlyc3QgYXV0b3Njcm9sbFxuICAgICAgICBpZiAoIXRoaXMub3B0aW9ucy5idWJibGVTY3JvbGwgfHwgZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZWxlbSwgdHJ1ZSkgPT09IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKSkge1xuICAgICAgICAgIGNsZWFyQXV0b1Njcm9sbHMoKTtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgYXV0b1Njcm9sbChldnQsIHRoaXMub3B0aW9ucywgZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoZWxlbSwgZmFsc2UpLCBmYWxzZSk7XG4gICAgICB9XG4gICAgfVxuICB9O1xuICByZXR1cm4gX2V4dGVuZHMoQXV0b1Njcm9sbCwge1xuICAgIHBsdWdpbk5hbWU6ICdzY3JvbGwnLFxuICAgIGluaXRpYWxpemVCeURlZmF1bHQ6IHRydWVcbiAgfSk7XG59XG5mdW5jdGlvbiBjbGVhckF1dG9TY3JvbGxzKCkge1xuICBhdXRvU2Nyb2xscy5mb3JFYWNoKGZ1bmN0aW9uIChhdXRvU2Nyb2xsKSB7XG4gICAgY2xlYXJJbnRlcnZhbChhdXRvU2Nyb2xsLnBpZCk7XG4gIH0pO1xuICBhdXRvU2Nyb2xscyA9IFtdO1xufVxuZnVuY3Rpb24gY2xlYXJQb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCgpIHtcbiAgY2xlYXJJbnRlcnZhbChwb2ludGVyRWxlbUNoYW5nZWRJbnRlcnZhbCk7XG59XG52YXIgYXV0b1Njcm9sbCA9IHRocm90dGxlKGZ1bmN0aW9uIChldnQsIG9wdGlvbnMsIHJvb3RFbCwgaXNGYWxsYmFjaykge1xuICAvLyBCdWc6IGh0dHBzOi8vYnVnemlsbGEubW96aWxsYS5vcmcvc2hvd19idWcuY2dpP2lkPTUwNTUyMVxuICBpZiAoIW9wdGlvbnMuc2Nyb2xsKSByZXR1cm47XG4gIHZhciB4ID0gKGV2dC50b3VjaGVzID8gZXZ0LnRvdWNoZXNbMF0gOiBldnQpLmNsaWVudFgsXG4gICAgeSA9IChldnQudG91Y2hlcyA/IGV2dC50b3VjaGVzWzBdIDogZXZ0KS5jbGllbnRZLFxuICAgIHNlbnMgPSBvcHRpb25zLnNjcm9sbFNlbnNpdGl2aXR5LFxuICAgIHNwZWVkID0gb3B0aW9ucy5zY3JvbGxTcGVlZCxcbiAgICB3aW5TY3JvbGxlciA9IGdldFdpbmRvd1Njcm9sbGluZ0VsZW1lbnQoKTtcbiAgdmFyIHNjcm9sbFRoaXNJbnN0YW5jZSA9IGZhbHNlLFxuICAgIHNjcm9sbEN1c3RvbUZuO1xuXG4gIC8vIE5ldyBzY3JvbGwgcm9vdCwgc2V0IHNjcm9sbEVsXG4gIGlmIChzY3JvbGxSb290RWwgIT09IHJvb3RFbCkge1xuICAgIHNjcm9sbFJvb3RFbCA9IHJvb3RFbDtcbiAgICBjbGVhckF1dG9TY3JvbGxzKCk7XG4gICAgc2Nyb2xsRWwgPSBvcHRpb25zLnNjcm9sbDtcbiAgICBzY3JvbGxDdXN0b21GbiA9IG9wdGlvbnMuc2Nyb2xsRm47XG4gICAgaWYgKHNjcm9sbEVsID09PSB0cnVlKSB7XG4gICAgICBzY3JvbGxFbCA9IGdldFBhcmVudEF1dG9TY3JvbGxFbGVtZW50KHJvb3RFbCwgdHJ1ZSk7XG4gICAgfVxuICB9XG4gIHZhciBsYXllcnNPdXQgPSAwO1xuICB2YXIgY3VycmVudFBhcmVudCA9IHNjcm9sbEVsO1xuICBkbyB7XG4gICAgdmFyIGVsID0gY3VycmVudFBhcmVudCxcbiAgICAgIHJlY3QgPSBnZXRSZWN0KGVsKSxcbiAgICAgIHRvcCA9IHJlY3QudG9wLFxuICAgICAgYm90dG9tID0gcmVjdC5ib3R0b20sXG4gICAgICBsZWZ0ID0gcmVjdC5sZWZ0LFxuICAgICAgcmlnaHQgPSByZWN0LnJpZ2h0LFxuICAgICAgd2lkdGggPSByZWN0LndpZHRoLFxuICAgICAgaGVpZ2h0ID0gcmVjdC5oZWlnaHQsXG4gICAgICBjYW5TY3JvbGxYID0gdm9pZCAwLFxuICAgICAgY2FuU2Nyb2xsWSA9IHZvaWQgMCxcbiAgICAgIHNjcm9sbFdpZHRoID0gZWwuc2Nyb2xsV2lkdGgsXG4gICAgICBzY3JvbGxIZWlnaHQgPSBlbC5zY3JvbGxIZWlnaHQsXG4gICAgICBlbENTUyA9IGNzcyhlbCksXG4gICAgICBzY3JvbGxQb3NYID0gZWwuc2Nyb2xsTGVmdCxcbiAgICAgIHNjcm9sbFBvc1kgPSBlbC5zY3JvbGxUb3A7XG4gICAgaWYgKGVsID09PSB3aW5TY3JvbGxlcikge1xuICAgICAgY2FuU2Nyb2xsWCA9IHdpZHRoIDwgc2Nyb2xsV2lkdGggJiYgKGVsQ1NTLm92ZXJmbG93WCA9PT0gJ2F1dG8nIHx8IGVsQ1NTLm92ZXJmbG93WCA9PT0gJ3Njcm9sbCcgfHwgZWxDU1Mub3ZlcmZsb3dYID09PSAndmlzaWJsZScpO1xuICAgICAgY2FuU2Nyb2xsWSA9IGhlaWdodCA8IHNjcm9sbEhlaWdodCAmJiAoZWxDU1Mub3ZlcmZsb3dZID09PSAnYXV0bycgfHwgZWxDU1Mub3ZlcmZsb3dZID09PSAnc2Nyb2xsJyB8fCBlbENTUy5vdmVyZmxvd1kgPT09ICd2aXNpYmxlJyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNhblNjcm9sbFggPSB3aWR0aCA8IHNjcm9sbFdpZHRoICYmIChlbENTUy5vdmVyZmxvd1ggPT09ICdhdXRvJyB8fCBlbENTUy5vdmVyZmxvd1ggPT09ICdzY3JvbGwnKTtcbiAgICAgIGNhblNjcm9sbFkgPSBoZWlnaHQgPCBzY3JvbGxIZWlnaHQgJiYgKGVsQ1NTLm92ZXJmbG93WSA9PT0gJ2F1dG8nIHx8IGVsQ1NTLm92ZXJmbG93WSA9PT0gJ3Njcm9sbCcpO1xuICAgIH1cbiAgICB2YXIgdnggPSBjYW5TY3JvbGxYICYmIChNYXRoLmFicyhyaWdodCAtIHgpIDw9IHNlbnMgJiYgc2Nyb2xsUG9zWCArIHdpZHRoIDwgc2Nyb2xsV2lkdGgpIC0gKE1hdGguYWJzKGxlZnQgLSB4KSA8PSBzZW5zICYmICEhc2Nyb2xsUG9zWCk7XG4gICAgdmFyIHZ5ID0gY2FuU2Nyb2xsWSAmJiAoTWF0aC5hYnMoYm90dG9tIC0geSkgPD0gc2VucyAmJiBzY3JvbGxQb3NZICsgaGVpZ2h0IDwgc2Nyb2xsSGVpZ2h0KSAtIChNYXRoLmFicyh0b3AgLSB5KSA8PSBzZW5zICYmICEhc2Nyb2xsUG9zWSk7XG4gICAgaWYgKCFhdXRvU2Nyb2xsc1tsYXllcnNPdXRdKSB7XG4gICAgICBmb3IgKHZhciBpID0gMDsgaSA8PSBsYXllcnNPdXQ7IGkrKykge1xuICAgICAgICBpZiAoIWF1dG9TY3JvbGxzW2ldKSB7XG4gICAgICAgICAgYXV0b1Njcm9sbHNbaV0gPSB7fTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cbiAgICBpZiAoYXV0b1Njcm9sbHNbbGF5ZXJzT3V0XS52eCAhPSB2eCB8fCBhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLnZ5ICE9IHZ5IHx8IGF1dG9TY3JvbGxzW2xheWVyc091dF0uZWwgIT09IGVsKSB7XG4gICAgICBhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLmVsID0gZWw7XG4gICAgICBhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLnZ4ID0gdng7XG4gICAgICBhdXRvU2Nyb2xsc1tsYXllcnNPdXRdLnZ5ID0gdnk7XG4gICAgICBjbGVhckludGVydmFsKGF1dG9TY3JvbGxzW2xheWVyc091dF0ucGlkKTtcbiAgICAgIGlmICh2eCAhPSAwIHx8IHZ5ICE9IDApIHtcbiAgICAgICAgc2Nyb2xsVGhpc0luc3RhbmNlID0gdHJ1ZTtcbiAgICAgICAgLyoganNoaW50IGxvb3BmdW5jOnRydWUgKi9cbiAgICAgICAgYXV0b1Njcm9sbHNbbGF5ZXJzT3V0XS5waWQgPSBzZXRJbnRlcnZhbChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgLy8gZW11bGF0ZSBkcmFnIG92ZXIgZHVyaW5nIGF1dG9zY3JvbGwgKGZhbGxiYWNrKSwgZW11bGF0aW5nIG5hdGl2ZSBEbkQgYmVoYXZpb3VyXG4gICAgICAgICAgaWYgKGlzRmFsbGJhY2sgJiYgdGhpcy5sYXllciA9PT0gMCkge1xuICAgICAgICAgICAgU29ydGFibGUuYWN0aXZlLl9vblRvdWNoTW92ZSh0b3VjaEV2dCQxKTsgLy8gVG8gbW92ZSBnaG9zdCBpZiBpdCBpcyBwb3NpdGlvbmVkIGFic29sdXRlbHlcbiAgICAgICAgICB9XG4gICAgICAgICAgdmFyIHNjcm9sbE9mZnNldFkgPSBhdXRvU2Nyb2xsc1t0aGlzLmxheWVyXS52eSA/IGF1dG9TY3JvbGxzW3RoaXMubGF5ZXJdLnZ5ICogc3BlZWQgOiAwO1xuICAgICAgICAgIHZhciBzY3JvbGxPZmZzZXRYID0gYXV0b1Njcm9sbHNbdGhpcy5sYXllcl0udnggPyBhdXRvU2Nyb2xsc1t0aGlzLmxheWVyXS52eCAqIHNwZWVkIDogMDtcbiAgICAgICAgICBpZiAodHlwZW9mIHNjcm9sbEN1c3RvbUZuID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICBpZiAoc2Nyb2xsQ3VzdG9tRm4uY2FsbChTb3J0YWJsZS5kcmFnZ2VkLnBhcmVudE5vZGVbZXhwYW5kb10sIHNjcm9sbE9mZnNldFgsIHNjcm9sbE9mZnNldFksIGV2dCwgdG91Y2hFdnQkMSwgYXV0b1Njcm9sbHNbdGhpcy5sYXllcl0uZWwpICE9PSAnY29udGludWUnKSB7XG4gICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgc2Nyb2xsQnkoYXV0b1Njcm9sbHNbdGhpcy5sYXllcl0uZWwsIHNjcm9sbE9mZnNldFgsIHNjcm9sbE9mZnNldFkpO1xuICAgICAgICB9LmJpbmQoe1xuICAgICAgICAgIGxheWVyOiBsYXllcnNPdXRcbiAgICAgICAgfSksIDI0KTtcbiAgICAgIH1cbiAgICB9XG4gICAgbGF5ZXJzT3V0Kys7XG4gIH0gd2hpbGUgKG9wdGlvbnMuYnViYmxlU2Nyb2xsICYmIGN1cnJlbnRQYXJlbnQgIT09IHdpblNjcm9sbGVyICYmIChjdXJyZW50UGFyZW50ID0gZ2V0UGFyZW50QXV0b1Njcm9sbEVsZW1lbnQoY3VycmVudFBhcmVudCwgZmFsc2UpKSk7XG4gIHNjcm9sbGluZyA9IHNjcm9sbFRoaXNJbnN0YW5jZTsgLy8gaW4gY2FzZSBhbm90aGVyIGZ1bmN0aW9uIGNhdGNoZXMgc2Nyb2xsaW5nIGFzIGZhbHNlIGluIGJldHdlZW4gd2hlbiBpdCBpcyBub3Rcbn0sIDMwKTtcblxudmFyIGRyb3AgPSBmdW5jdGlvbiBkcm9wKF9yZWYpIHtcbiAgdmFyIG9yaWdpbmFsRXZlbnQgPSBfcmVmLm9yaWdpbmFsRXZlbnQsXG4gICAgcHV0U29ydGFibGUgPSBfcmVmLnB1dFNvcnRhYmxlLFxuICAgIGRyYWdFbCA9IF9yZWYuZHJhZ0VsLFxuICAgIGFjdGl2ZVNvcnRhYmxlID0gX3JlZi5hY3RpdmVTb3J0YWJsZSxcbiAgICBkaXNwYXRjaFNvcnRhYmxlRXZlbnQgPSBfcmVmLmRpc3BhdGNoU29ydGFibGVFdmVudCxcbiAgICBoaWRlR2hvc3RGb3JUYXJnZXQgPSBfcmVmLmhpZGVHaG9zdEZvclRhcmdldCxcbiAgICB1bmhpZGVHaG9zdEZvclRhcmdldCA9IF9yZWYudW5oaWRlR2hvc3RGb3JUYXJnZXQ7XG4gIGlmICghb3JpZ2luYWxFdmVudCkgcmV0dXJuO1xuICB2YXIgdG9Tb3J0YWJsZSA9IHB1dFNvcnRhYmxlIHx8IGFjdGl2ZVNvcnRhYmxlO1xuICBoaWRlR2hvc3RGb3JUYXJnZXQoKTtcbiAgdmFyIHRvdWNoID0gb3JpZ2luYWxFdmVudC5jaGFuZ2VkVG91Y2hlcyAmJiBvcmlnaW5hbEV2ZW50LmNoYW5nZWRUb3VjaGVzLmxlbmd0aCA/IG9yaWdpbmFsRXZlbnQuY2hhbmdlZFRvdWNoZXNbMF0gOiBvcmlnaW5hbEV2ZW50O1xuICB2YXIgdGFyZ2V0ID0gZG9jdW1lbnQuZWxlbWVudEZyb21Qb2ludCh0b3VjaC5jbGllbnRYLCB0b3VjaC5jbGllbnRZKTtcbiAgdW5oaWRlR2hvc3RGb3JUYXJnZXQoKTtcbiAgaWYgKHRvU29ydGFibGUgJiYgIXRvU29ydGFibGUuZWwuY29udGFpbnModGFyZ2V0KSkge1xuICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCgnc3BpbGwnKTtcbiAgICB0aGlzLm9uU3BpbGwoe1xuICAgICAgZHJhZ0VsOiBkcmFnRWwsXG4gICAgICBwdXRTb3J0YWJsZTogcHV0U29ydGFibGVcbiAgICB9KTtcbiAgfVxufTtcbmZ1bmN0aW9uIFJldmVydCgpIHt9XG5SZXZlcnQucHJvdG90eXBlID0ge1xuICBzdGFydEluZGV4OiBudWxsLFxuICBkcmFnU3RhcnQ6IGZ1bmN0aW9uIGRyYWdTdGFydChfcmVmMikge1xuICAgIHZhciBvbGREcmFnZ2FibGVJbmRleCA9IF9yZWYyLm9sZERyYWdnYWJsZUluZGV4O1xuICAgIHRoaXMuc3RhcnRJbmRleCA9IG9sZERyYWdnYWJsZUluZGV4O1xuICB9LFxuICBvblNwaWxsOiBmdW5jdGlvbiBvblNwaWxsKF9yZWYzKSB7XG4gICAgdmFyIGRyYWdFbCA9IF9yZWYzLmRyYWdFbCxcbiAgICAgIHB1dFNvcnRhYmxlID0gX3JlZjMucHV0U29ydGFibGU7XG4gICAgdGhpcy5zb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICBpZiAocHV0U29ydGFibGUpIHtcbiAgICAgIHB1dFNvcnRhYmxlLmNhcHR1cmVBbmltYXRpb25TdGF0ZSgpO1xuICAgIH1cbiAgICB2YXIgbmV4dFNpYmxpbmcgPSBnZXRDaGlsZCh0aGlzLnNvcnRhYmxlLmVsLCB0aGlzLnN0YXJ0SW5kZXgsIHRoaXMub3B0aW9ucyk7XG4gICAgaWYgKG5leHRTaWJsaW5nKSB7XG4gICAgICB0aGlzLnNvcnRhYmxlLmVsLmluc2VydEJlZm9yZShkcmFnRWwsIG5leHRTaWJsaW5nKTtcbiAgICB9IGVsc2Uge1xuICAgICAgdGhpcy5zb3J0YWJsZS5lbC5hcHBlbmRDaGlsZChkcmFnRWwpO1xuICAgIH1cbiAgICB0aGlzLnNvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICBpZiAocHV0U29ydGFibGUpIHtcbiAgICAgIHB1dFNvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICB9XG4gIH0sXG4gIGRyb3A6IGRyb3Bcbn07XG5fZXh0ZW5kcyhSZXZlcnQsIHtcbiAgcGx1Z2luTmFtZTogJ3JldmVydE9uU3BpbGwnXG59KTtcbmZ1bmN0aW9uIFJlbW92ZSgpIHt9XG5SZW1vdmUucHJvdG90eXBlID0ge1xuICBvblNwaWxsOiBmdW5jdGlvbiBvblNwaWxsKF9yZWY0KSB7XG4gICAgdmFyIGRyYWdFbCA9IF9yZWY0LmRyYWdFbCxcbiAgICAgIHB1dFNvcnRhYmxlID0gX3JlZjQucHV0U29ydGFibGU7XG4gICAgdmFyIHBhcmVudFNvcnRhYmxlID0gcHV0U29ydGFibGUgfHwgdGhpcy5zb3J0YWJsZTtcbiAgICBwYXJlbnRTb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICBkcmFnRWwucGFyZW50Tm9kZSAmJiBkcmFnRWwucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChkcmFnRWwpO1xuICAgIHBhcmVudFNvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgfSxcbiAgZHJvcDogZHJvcFxufTtcbl9leHRlbmRzKFJlbW92ZSwge1xuICBwbHVnaW5OYW1lOiAncmVtb3ZlT25TcGlsbCdcbn0pO1xuXG52YXIgbGFzdFN3YXBFbDtcbmZ1bmN0aW9uIFN3YXBQbHVnaW4oKSB7XG4gIGZ1bmN0aW9uIFN3YXAoKSB7XG4gICAgdGhpcy5kZWZhdWx0cyA9IHtcbiAgICAgIHN3YXBDbGFzczogJ3NvcnRhYmxlLXN3YXAtaGlnaGxpZ2h0J1xuICAgIH07XG4gIH1cbiAgU3dhcC5wcm90b3R5cGUgPSB7XG4gICAgZHJhZ1N0YXJ0OiBmdW5jdGlvbiBkcmFnU3RhcnQoX3JlZikge1xuICAgICAgdmFyIGRyYWdFbCA9IF9yZWYuZHJhZ0VsO1xuICAgICAgbGFzdFN3YXBFbCA9IGRyYWdFbDtcbiAgICB9LFxuICAgIGRyYWdPdmVyVmFsaWQ6IGZ1bmN0aW9uIGRyYWdPdmVyVmFsaWQoX3JlZjIpIHtcbiAgICAgIHZhciBjb21wbGV0ZWQgPSBfcmVmMi5jb21wbGV0ZWQsXG4gICAgICAgIHRhcmdldCA9IF9yZWYyLnRhcmdldCxcbiAgICAgICAgb25Nb3ZlID0gX3JlZjIub25Nb3ZlLFxuICAgICAgICBhY3RpdmVTb3J0YWJsZSA9IF9yZWYyLmFjdGl2ZVNvcnRhYmxlLFxuICAgICAgICBjaGFuZ2VkID0gX3JlZjIuY2hhbmdlZCxcbiAgICAgICAgY2FuY2VsID0gX3JlZjIuY2FuY2VsO1xuICAgICAgaWYgKCFhY3RpdmVTb3J0YWJsZS5vcHRpb25zLnN3YXApIHJldHVybjtcbiAgICAgIHZhciBlbCA9IHRoaXMuc29ydGFibGUuZWwsXG4gICAgICAgIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG4gICAgICBpZiAodGFyZ2V0ICYmIHRhcmdldCAhPT0gZWwpIHtcbiAgICAgICAgdmFyIHByZXZTd2FwRWwgPSBsYXN0U3dhcEVsO1xuICAgICAgICBpZiAob25Nb3ZlKHRhcmdldCkgIT09IGZhbHNlKSB7XG4gICAgICAgICAgdG9nZ2xlQ2xhc3ModGFyZ2V0LCBvcHRpb25zLnN3YXBDbGFzcywgdHJ1ZSk7XG4gICAgICAgICAgbGFzdFN3YXBFbCA9IHRhcmdldDtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBsYXN0U3dhcEVsID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICBpZiAocHJldlN3YXBFbCAmJiBwcmV2U3dhcEVsICE9PSBsYXN0U3dhcEVsKSB7XG4gICAgICAgICAgdG9nZ2xlQ2xhc3MocHJldlN3YXBFbCwgb3B0aW9ucy5zd2FwQ2xhc3MsIGZhbHNlKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgY2hhbmdlZCgpO1xuICAgICAgY29tcGxldGVkKHRydWUpO1xuICAgICAgY2FuY2VsKCk7XG4gICAgfSxcbiAgICBkcm9wOiBmdW5jdGlvbiBkcm9wKF9yZWYzKSB7XG4gICAgICB2YXIgYWN0aXZlU29ydGFibGUgPSBfcmVmMy5hY3RpdmVTb3J0YWJsZSxcbiAgICAgICAgcHV0U29ydGFibGUgPSBfcmVmMy5wdXRTb3J0YWJsZSxcbiAgICAgICAgZHJhZ0VsID0gX3JlZjMuZHJhZ0VsO1xuICAgICAgdmFyIHRvU29ydGFibGUgPSBwdXRTb3J0YWJsZSB8fCB0aGlzLnNvcnRhYmxlO1xuICAgICAgdmFyIG9wdGlvbnMgPSB0aGlzLm9wdGlvbnM7XG4gICAgICBsYXN0U3dhcEVsICYmIHRvZ2dsZUNsYXNzKGxhc3RTd2FwRWwsIG9wdGlvbnMuc3dhcENsYXNzLCBmYWxzZSk7XG4gICAgICBpZiAobGFzdFN3YXBFbCAmJiAob3B0aW9ucy5zd2FwIHx8IHB1dFNvcnRhYmxlICYmIHB1dFNvcnRhYmxlLm9wdGlvbnMuc3dhcCkpIHtcbiAgICAgICAgaWYgKGRyYWdFbCAhPT0gbGFzdFN3YXBFbCkge1xuICAgICAgICAgIHRvU29ydGFibGUuY2FwdHVyZUFuaW1hdGlvblN0YXRlKCk7XG4gICAgICAgICAgaWYgKHRvU29ydGFibGUgIT09IGFjdGl2ZVNvcnRhYmxlKSBhY3RpdmVTb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICAgICAgICBzd2FwTm9kZXMoZHJhZ0VsLCBsYXN0U3dhcEVsKTtcbiAgICAgICAgICB0b1NvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICAgICAgICBpZiAodG9Tb3J0YWJsZSAhPT0gYWN0aXZlU29ydGFibGUpIGFjdGl2ZVNvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sXG4gICAgbnVsbGluZzogZnVuY3Rpb24gbnVsbGluZygpIHtcbiAgICAgIGxhc3RTd2FwRWwgPSBudWxsO1xuICAgIH1cbiAgfTtcbiAgcmV0dXJuIF9leHRlbmRzKFN3YXAsIHtcbiAgICBwbHVnaW5OYW1lOiAnc3dhcCcsXG4gICAgZXZlbnRQcm9wZXJ0aWVzOiBmdW5jdGlvbiBldmVudFByb3BlcnRpZXMoKSB7XG4gICAgICByZXR1cm4ge1xuICAgICAgICBzd2FwSXRlbTogbGFzdFN3YXBFbFxuICAgICAgfTtcbiAgICB9XG4gIH0pO1xufVxuZnVuY3Rpb24gc3dhcE5vZGVzKG4xLCBuMikge1xuICB2YXIgcDEgPSBuMS5wYXJlbnROb2RlLFxuICAgIHAyID0gbjIucGFyZW50Tm9kZSxcbiAgICBpMSxcbiAgICBpMjtcbiAgaWYgKCFwMSB8fCAhcDIgfHwgcDEuaXNFcXVhbE5vZGUobjIpIHx8IHAyLmlzRXF1YWxOb2RlKG4xKSkgcmV0dXJuO1xuICBpMSA9IGluZGV4KG4xKTtcbiAgaTIgPSBpbmRleChuMik7XG4gIGlmIChwMS5pc0VxdWFsTm9kZShwMikgJiYgaTEgPCBpMikge1xuICAgIGkyKys7XG4gIH1cbiAgcDEuaW5zZXJ0QmVmb3JlKG4yLCBwMS5jaGlsZHJlbltpMV0pO1xuICBwMi5pbnNlcnRCZWZvcmUobjEsIHAyLmNoaWxkcmVuW2kyXSk7XG59XG5cbnZhciBtdWx0aURyYWdFbGVtZW50cyA9IFtdLFxuICBtdWx0aURyYWdDbG9uZXMgPSBbXSxcbiAgbGFzdE11bHRpRHJhZ1NlbGVjdCxcbiAgLy8gZm9yIHNlbGVjdGlvbiB3aXRoIG1vZGlmaWVyIGtleSBkb3duIChTSElGVClcbiAgbXVsdGlEcmFnU29ydGFibGUsXG4gIGluaXRpYWxGb2xkaW5nID0gZmFsc2UsXG4gIC8vIEluaXRpYWwgbXVsdGktZHJhZyBmb2xkIHdoZW4gZHJhZyBzdGFydGVkXG4gIGZvbGRpbmcgPSBmYWxzZSxcbiAgLy8gRm9sZGluZyBhbnkgb3RoZXIgdGltZVxuICBkcmFnU3RhcnRlZCA9IGZhbHNlLFxuICBkcmFnRWwkMSxcbiAgY2xvbmVzRnJvbVJlY3QsXG4gIGNsb25lc0hpZGRlbjtcbmZ1bmN0aW9uIE11bHRpRHJhZ1BsdWdpbigpIHtcbiAgZnVuY3Rpb24gTXVsdGlEcmFnKHNvcnRhYmxlKSB7XG4gICAgLy8gQmluZCBhbGwgcHJpdmF0ZSBtZXRob2RzXG4gICAgZm9yICh2YXIgZm4gaW4gdGhpcykge1xuICAgICAgaWYgKGZuLmNoYXJBdCgwKSA9PT0gJ18nICYmIHR5cGVvZiB0aGlzW2ZuXSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICB0aGlzW2ZuXSA9IHRoaXNbZm5dLmJpbmQodGhpcyk7XG4gICAgICB9XG4gICAgfVxuICAgIGlmICghc29ydGFibGUub3B0aW9ucy5hdm9pZEltcGxpY2l0RGVzZWxlY3QpIHtcbiAgICAgIGlmIChzb3J0YWJsZS5vcHRpb25zLnN1cHBvcnRQb2ludGVyKSB7XG4gICAgICAgIG9uKGRvY3VtZW50LCAncG9pbnRlcnVwJywgdGhpcy5fZGVzZWxlY3RNdWx0aURyYWcpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgb24oZG9jdW1lbnQsICdtb3VzZXVwJywgdGhpcy5fZGVzZWxlY3RNdWx0aURyYWcpO1xuICAgICAgICBvbihkb2N1bWVudCwgJ3RvdWNoZW5kJywgdGhpcy5fZGVzZWxlY3RNdWx0aURyYWcpO1xuICAgICAgfVxuICAgIH1cbiAgICBvbihkb2N1bWVudCwgJ2tleWRvd24nLCB0aGlzLl9jaGVja0tleURvd24pO1xuICAgIG9uKGRvY3VtZW50LCAna2V5dXAnLCB0aGlzLl9jaGVja0tleVVwKTtcbiAgICB0aGlzLmRlZmF1bHRzID0ge1xuICAgICAgc2VsZWN0ZWRDbGFzczogJ3NvcnRhYmxlLXNlbGVjdGVkJyxcbiAgICAgIG11bHRpRHJhZ0tleTogbnVsbCxcbiAgICAgIGF2b2lkSW1wbGljaXREZXNlbGVjdDogZmFsc2UsXG4gICAgICBzZXREYXRhOiBmdW5jdGlvbiBzZXREYXRhKGRhdGFUcmFuc2ZlciwgZHJhZ0VsKSB7XG4gICAgICAgIHZhciBkYXRhID0gJyc7XG4gICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50cy5sZW5ndGggJiYgbXVsdGlEcmFnU29ydGFibGUgPT09IHNvcnRhYmxlKSB7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCwgaSkge1xuICAgICAgICAgICAgZGF0YSArPSAoIWkgPyAnJyA6ICcsICcpICsgbXVsdGlEcmFnRWxlbWVudC50ZXh0Q29udGVudDtcbiAgICAgICAgICB9KTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBkYXRhID0gZHJhZ0VsLnRleHRDb250ZW50O1xuICAgICAgICB9XG4gICAgICAgIGRhdGFUcmFuc2Zlci5zZXREYXRhKCdUZXh0JywgZGF0YSk7XG4gICAgICB9XG4gICAgfTtcbiAgfVxuICBNdWx0aURyYWcucHJvdG90eXBlID0ge1xuICAgIG11bHRpRHJhZ0tleURvd246IGZhbHNlLFxuICAgIGlzTXVsdGlEcmFnOiBmYWxzZSxcbiAgICBkZWxheVN0YXJ0R2xvYmFsOiBmdW5jdGlvbiBkZWxheVN0YXJ0R2xvYmFsKF9yZWYpIHtcbiAgICAgIHZhciBkcmFnZ2VkID0gX3JlZi5kcmFnRWw7XG4gICAgICBkcmFnRWwkMSA9IGRyYWdnZWQ7XG4gICAgfSxcbiAgICBkZWxheUVuZGVkOiBmdW5jdGlvbiBkZWxheUVuZGVkKCkge1xuICAgICAgdGhpcy5pc011bHRpRHJhZyA9IH5tdWx0aURyYWdFbGVtZW50cy5pbmRleE9mKGRyYWdFbCQxKTtcbiAgICB9LFxuICAgIHNldHVwQ2xvbmU6IGZ1bmN0aW9uIHNldHVwQ2xvbmUoX3JlZjIpIHtcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWYyLnNvcnRhYmxlLFxuICAgICAgICBjYW5jZWwgPSBfcmVmMi5jYW5jZWw7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcpIHJldHVybjtcbiAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgbXVsdGlEcmFnRWxlbWVudHMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgbXVsdGlEcmFnQ2xvbmVzLnB1c2goY2xvbmUobXVsdGlEcmFnRWxlbWVudHNbaV0pKTtcbiAgICAgICAgbXVsdGlEcmFnQ2xvbmVzW2ldLnNvcnRhYmxlSW5kZXggPSBtdWx0aURyYWdFbGVtZW50c1tpXS5zb3J0YWJsZUluZGV4O1xuICAgICAgICBtdWx0aURyYWdDbG9uZXNbaV0uZHJhZ2dhYmxlID0gZmFsc2U7XG4gICAgICAgIG11bHRpRHJhZ0Nsb25lc1tpXS5zdHlsZVsnd2lsbC1jaGFuZ2UnXSA9ICcnO1xuICAgICAgICB0b2dnbGVDbGFzcyhtdWx0aURyYWdDbG9uZXNbaV0sIHRoaXMub3B0aW9ucy5zZWxlY3RlZENsYXNzLCBmYWxzZSk7XG4gICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzW2ldID09PSBkcmFnRWwkMSAmJiB0b2dnbGVDbGFzcyhtdWx0aURyYWdDbG9uZXNbaV0sIHRoaXMub3B0aW9ucy5jaG9zZW5DbGFzcywgZmFsc2UpO1xuICAgICAgfVxuICAgICAgc29ydGFibGUuX2hpZGVDbG9uZSgpO1xuICAgICAgY2FuY2VsKCk7XG4gICAgfSxcbiAgICBjbG9uZTogZnVuY3Rpb24gY2xvbmUoX3JlZjMpIHtcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWYzLnNvcnRhYmxlLFxuICAgICAgICByb290RWwgPSBfcmVmMy5yb290RWwsXG4gICAgICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCA9IF9yZWYzLmRpc3BhdGNoU29ydGFibGVFdmVudCxcbiAgICAgICAgY2FuY2VsID0gX3JlZjMuY2FuY2VsO1xuICAgICAgaWYgKCF0aGlzLmlzTXVsdGlEcmFnKSByZXR1cm47XG4gICAgICBpZiAoIXRoaXMub3B0aW9ucy5yZW1vdmVDbG9uZU9uSGlkZSkge1xuICAgICAgICBpZiAobXVsdGlEcmFnRWxlbWVudHMubGVuZ3RoICYmIG11bHRpRHJhZ1NvcnRhYmxlID09PSBzb3J0YWJsZSkge1xuICAgICAgICAgIGluc2VydE11bHRpRHJhZ0Nsb25lcyh0cnVlLCByb290RWwpO1xuICAgICAgICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCgnY2xvbmUnKTtcbiAgICAgICAgICBjYW5jZWwoKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0sXG4gICAgc2hvd0Nsb25lOiBmdW5jdGlvbiBzaG93Q2xvbmUoX3JlZjQpIHtcbiAgICAgIHZhciBjbG9uZU5vd1Nob3duID0gX3JlZjQuY2xvbmVOb3dTaG93bixcbiAgICAgICAgcm9vdEVsID0gX3JlZjQucm9vdEVsLFxuICAgICAgICBjYW5jZWwgPSBfcmVmNC5jYW5jZWw7XG4gICAgICBpZiAoIXRoaXMuaXNNdWx0aURyYWcpIHJldHVybjtcbiAgICAgIGluc2VydE11bHRpRHJhZ0Nsb25lcyhmYWxzZSwgcm9vdEVsKTtcbiAgICAgIG11bHRpRHJhZ0Nsb25lcy5mb3JFYWNoKGZ1bmN0aW9uIChjbG9uZSkge1xuICAgICAgICBjc3MoY2xvbmUsICdkaXNwbGF5JywgJycpO1xuICAgICAgfSk7XG4gICAgICBjbG9uZU5vd1Nob3duKCk7XG4gICAgICBjbG9uZXNIaWRkZW4gPSBmYWxzZTtcbiAgICAgIGNhbmNlbCgpO1xuICAgIH0sXG4gICAgaGlkZUNsb25lOiBmdW5jdGlvbiBoaWRlQ2xvbmUoX3JlZjUpIHtcbiAgICAgIHZhciBfdGhpcyA9IHRoaXM7XG4gICAgICB2YXIgc29ydGFibGUgPSBfcmVmNS5zb3J0YWJsZSxcbiAgICAgICAgY2xvbmVOb3dIaWRkZW4gPSBfcmVmNS5jbG9uZU5vd0hpZGRlbixcbiAgICAgICAgY2FuY2VsID0gX3JlZjUuY2FuY2VsO1xuICAgICAgaWYgKCF0aGlzLmlzTXVsdGlEcmFnKSByZXR1cm47XG4gICAgICBtdWx0aURyYWdDbG9uZXMuZm9yRWFjaChmdW5jdGlvbiAoY2xvbmUpIHtcbiAgICAgICAgY3NzKGNsb25lLCAnZGlzcGxheScsICdub25lJyk7XG4gICAgICAgIGlmIChfdGhpcy5vcHRpb25zLnJlbW92ZUNsb25lT25IaWRlICYmIGNsb25lLnBhcmVudE5vZGUpIHtcbiAgICAgICAgICBjbG9uZS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGNsb25lKTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgICBjbG9uZU5vd0hpZGRlbigpO1xuICAgICAgY2xvbmVzSGlkZGVuID0gdHJ1ZTtcbiAgICAgIGNhbmNlbCgpO1xuICAgIH0sXG4gICAgZHJhZ1N0YXJ0R2xvYmFsOiBmdW5jdGlvbiBkcmFnU3RhcnRHbG9iYWwoX3JlZjYpIHtcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWY2LnNvcnRhYmxlO1xuICAgICAgaWYgKCF0aGlzLmlzTXVsdGlEcmFnICYmIG11bHRpRHJhZ1NvcnRhYmxlKSB7XG4gICAgICAgIG11bHRpRHJhZ1NvcnRhYmxlLm11bHRpRHJhZy5fZGVzZWxlY3RNdWx0aURyYWcoKTtcbiAgICAgIH1cbiAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgbXVsdGlEcmFnRWxlbWVudC5zb3J0YWJsZUluZGV4ID0gaW5kZXgobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICB9KTtcblxuICAgICAgLy8gU29ydCBtdWx0aS1kcmFnIGVsZW1lbnRzXG4gICAgICBtdWx0aURyYWdFbGVtZW50cyA9IG11bHRpRHJhZ0VsZW1lbnRzLnNvcnQoZnVuY3Rpb24gKGEsIGIpIHtcbiAgICAgICAgcmV0dXJuIGEuc29ydGFibGVJbmRleCAtIGIuc29ydGFibGVJbmRleDtcbiAgICAgIH0pO1xuICAgICAgZHJhZ1N0YXJ0ZWQgPSB0cnVlO1xuICAgIH0sXG4gICAgZHJhZ1N0YXJ0ZWQ6IGZ1bmN0aW9uIGRyYWdTdGFydGVkKF9yZWY3KSB7XG4gICAgICB2YXIgX3RoaXMyID0gdGhpcztcbiAgICAgIHZhciBzb3J0YWJsZSA9IF9yZWY3LnNvcnRhYmxlO1xuICAgICAgaWYgKCF0aGlzLmlzTXVsdGlEcmFnKSByZXR1cm47XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnNvcnQpIHtcbiAgICAgICAgLy8gQ2FwdHVyZSByZWN0cyxcbiAgICAgICAgLy8gaGlkZSBtdWx0aSBkcmFnIGVsZW1lbnRzIChieSBwb3NpdGlvbmluZyB0aGVtIGFic29sdXRlKSxcbiAgICAgICAgLy8gc2V0IG11bHRpIGRyYWcgZWxlbWVudHMgcmVjdHMgdG8gZHJhZ1JlY3QsXG4gICAgICAgIC8vIHNob3cgbXVsdGkgZHJhZyBlbGVtZW50cyxcbiAgICAgICAgLy8gYW5pbWF0ZSB0byByZWN0cyxcbiAgICAgICAgLy8gdW5zZXQgcmVjdHMgJiByZW1vdmUgZnJvbSBET01cblxuICAgICAgICBzb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5hbmltYXRpb24pIHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICBpZiAobXVsdGlEcmFnRWxlbWVudCA9PT0gZHJhZ0VsJDEpIHJldHVybjtcbiAgICAgICAgICAgIGNzcyhtdWx0aURyYWdFbGVtZW50LCAncG9zaXRpb24nLCAnYWJzb2x1dGUnKTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgICB2YXIgZHJhZ1JlY3QgPSBnZXRSZWN0KGRyYWdFbCQxLCBmYWxzZSwgdHJ1ZSwgdHJ1ZSk7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgaWYgKG11bHRpRHJhZ0VsZW1lbnQgPT09IGRyYWdFbCQxKSByZXR1cm47XG4gICAgICAgICAgICBzZXRSZWN0KG11bHRpRHJhZ0VsZW1lbnQsIGRyYWdSZWN0KTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgICBmb2xkaW5nID0gdHJ1ZTtcbiAgICAgICAgICBpbml0aWFsRm9sZGluZyA9IHRydWU7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHNvcnRhYmxlLmFuaW1hdGVBbGwoZnVuY3Rpb24gKCkge1xuICAgICAgICBmb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIGluaXRpYWxGb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIGlmIChfdGhpczIub3B0aW9ucy5hbmltYXRpb24pIHtcbiAgICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChtdWx0aURyYWdFbGVtZW50KSB7XG4gICAgICAgICAgICB1bnNldFJlY3QobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBSZW1vdmUgYWxsIGF1eGlsaWFyeSBtdWx0aWRyYWcgaXRlbXMgZnJvbSBlbCwgaWYgc29ydGluZyBlbmFibGVkXG4gICAgICAgIGlmIChfdGhpczIub3B0aW9ucy5zb3J0KSB7XG4gICAgICAgICAgcmVtb3ZlTXVsdGlEcmFnRWxlbWVudHMoKTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgfSxcbiAgICBkcmFnT3ZlcjogZnVuY3Rpb24gZHJhZ092ZXIoX3JlZjgpIHtcbiAgICAgIHZhciB0YXJnZXQgPSBfcmVmOC50YXJnZXQsXG4gICAgICAgIGNvbXBsZXRlZCA9IF9yZWY4LmNvbXBsZXRlZCxcbiAgICAgICAgY2FuY2VsID0gX3JlZjguY2FuY2VsO1xuICAgICAgaWYgKGZvbGRpbmcgJiYgfm11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YodGFyZ2V0KSkge1xuICAgICAgICBjb21wbGV0ZWQoZmFsc2UpO1xuICAgICAgICBjYW5jZWwoKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIHJldmVydDogZnVuY3Rpb24gcmV2ZXJ0KF9yZWY5KSB7XG4gICAgICB2YXIgZnJvbVNvcnRhYmxlID0gX3JlZjkuZnJvbVNvcnRhYmxlLFxuICAgICAgICByb290RWwgPSBfcmVmOS5yb290RWwsXG4gICAgICAgIHNvcnRhYmxlID0gX3JlZjkuc29ydGFibGUsXG4gICAgICAgIGRyYWdSZWN0ID0gX3JlZjkuZHJhZ1JlY3Q7XG4gICAgICBpZiAobXVsdGlEcmFnRWxlbWVudHMubGVuZ3RoID4gMSkge1xuICAgICAgICAvLyBTZXR1cCB1bmZvbGQgYW5pbWF0aW9uXG4gICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgICBzb3J0YWJsZS5hZGRBbmltYXRpb25TdGF0ZSh7XG4gICAgICAgICAgICB0YXJnZXQ6IG11bHRpRHJhZ0VsZW1lbnQsXG4gICAgICAgICAgICByZWN0OiBmb2xkaW5nID8gZ2V0UmVjdChtdWx0aURyYWdFbGVtZW50KSA6IGRyYWdSZWN0XG4gICAgICAgICAgfSk7XG4gICAgICAgICAgdW5zZXRSZWN0KG11bHRpRHJhZ0VsZW1lbnQpO1xuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnQuZnJvbVJlY3QgPSBkcmFnUmVjdDtcbiAgICAgICAgICBmcm9tU29ydGFibGUucmVtb3ZlQW5pbWF0aW9uU3RhdGUobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgIH0pO1xuICAgICAgICBmb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIGluc2VydE11bHRpRHJhZ0VsZW1lbnRzKCF0aGlzLm9wdGlvbnMucmVtb3ZlQ2xvbmVPbkhpZGUsIHJvb3RFbCk7XG4gICAgICB9XG4gICAgfSxcbiAgICBkcmFnT3ZlckNvbXBsZXRlZDogZnVuY3Rpb24gZHJhZ092ZXJDb21wbGV0ZWQoX3JlZjEwKSB7XG4gICAgICB2YXIgc29ydGFibGUgPSBfcmVmMTAuc29ydGFibGUsXG4gICAgICAgIGlzT3duZXIgPSBfcmVmMTAuaXNPd25lcixcbiAgICAgICAgaW5zZXJ0aW9uID0gX3JlZjEwLmluc2VydGlvbixcbiAgICAgICAgYWN0aXZlU29ydGFibGUgPSBfcmVmMTAuYWN0aXZlU29ydGFibGUsXG4gICAgICAgIHBhcmVudEVsID0gX3JlZjEwLnBhcmVudEVsLFxuICAgICAgICBwdXRTb3J0YWJsZSA9IF9yZWYxMC5wdXRTb3J0YWJsZTtcbiAgICAgIHZhciBvcHRpb25zID0gdGhpcy5vcHRpb25zO1xuICAgICAgaWYgKGluc2VydGlvbikge1xuICAgICAgICAvLyBDbG9uZXMgbXVzdCBiZSBoaWRkZW4gYmVmb3JlIGZvbGRpbmcgYW5pbWF0aW9uIHRvIGNhcHR1cmUgZHJhZ1JlY3RBYnNvbHV0ZSBwcm9wZXJseVxuICAgICAgICBpZiAoaXNPd25lcikge1xuICAgICAgICAgIGFjdGl2ZVNvcnRhYmxlLl9oaWRlQ2xvbmUoKTtcbiAgICAgICAgfVxuICAgICAgICBpbml0aWFsRm9sZGluZyA9IGZhbHNlO1xuICAgICAgICAvLyBJZiBsZWF2aW5nIHNvcnQ6ZmFsc2Ugcm9vdCwgb3IgYWxyZWFkeSBmb2xkaW5nIC0gRm9sZCB0byBuZXcgbG9jYXRpb25cbiAgICAgICAgaWYgKG9wdGlvbnMuYW5pbWF0aW9uICYmIG11bHRpRHJhZ0VsZW1lbnRzLmxlbmd0aCA+IDEgJiYgKGZvbGRpbmcgfHwgIWlzT3duZXIgJiYgIWFjdGl2ZVNvcnRhYmxlLm9wdGlvbnMuc29ydCAmJiAhcHV0U29ydGFibGUpKSB7XG4gICAgICAgICAgLy8gRm9sZDogU2V0IGFsbCBtdWx0aSBkcmFnIGVsZW1lbnRzJ3MgcmVjdHMgdG8gZHJhZ0VsJ3MgcmVjdCB3aGVuIG11bHRpLWRyYWcgZWxlbWVudHMgYXJlIGludmlzaWJsZVxuICAgICAgICAgIHZhciBkcmFnUmVjdEFic29sdXRlID0gZ2V0UmVjdChkcmFnRWwkMSwgZmFsc2UsIHRydWUsIHRydWUpO1xuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50ID09PSBkcmFnRWwkMSkgcmV0dXJuO1xuICAgICAgICAgICAgc2V0UmVjdChtdWx0aURyYWdFbGVtZW50LCBkcmFnUmVjdEFic29sdXRlKTtcblxuICAgICAgICAgICAgLy8gTW92ZSBlbGVtZW50KHMpIHRvIGVuZCBvZiBwYXJlbnRFbCBzbyB0aGF0IGl0IGRvZXMgbm90IGludGVyZmVyZSB3aXRoIG11bHRpLWRyYWcgY2xvbmVzIGluc2VydGlvbiBpZiB0aGV5IGFyZSBpbnNlcnRlZFxuICAgICAgICAgICAgLy8gd2hpbGUgZm9sZGluZywgYW5kIHNvIHRoYXQgd2UgY2FuIGNhcHR1cmUgdGhlbSBhZ2FpbiBiZWNhdXNlIG9sZCBzb3J0YWJsZSB3aWxsIG5vIGxvbmdlciBiZSBmcm9tU29ydGFibGVcbiAgICAgICAgICAgIHBhcmVudEVsLmFwcGVuZENoaWxkKG11bHRpRHJhZ0VsZW1lbnQpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAgIGZvbGRpbmcgPSB0cnVlO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gQ2xvbmVzIG11c3QgYmUgc2hvd24gKGFuZCBjaGVjayB0byByZW1vdmUgbXVsdGkgZHJhZ3MpIGFmdGVyIGZvbGRpbmcgd2hlbiBpbnRlcmZlcmluZyBtdWx0aURyYWdFbGVtZW50cyBhcmUgbW92ZWQgb3V0XG4gICAgICAgIGlmICghaXNPd25lcikge1xuICAgICAgICAgIC8vIE9ubHkgcmVtb3ZlIGlmIG5vdCBmb2xkaW5nIChmb2xkaW5nIHdpbGwgcmVtb3ZlIHRoZW0gYW55d2F5cylcbiAgICAgICAgICBpZiAoIWZvbGRpbmcpIHtcbiAgICAgICAgICAgIHJlbW92ZU11bHRpRHJhZ0VsZW1lbnRzKCk7XG4gICAgICAgICAgfVxuICAgICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50cy5sZW5ndGggPiAxKSB7XG4gICAgICAgICAgICB2YXIgY2xvbmVzSGlkZGVuQmVmb3JlID0gY2xvbmVzSGlkZGVuO1xuICAgICAgICAgICAgYWN0aXZlU29ydGFibGUuX3Nob3dDbG9uZShzb3J0YWJsZSk7XG5cbiAgICAgICAgICAgIC8vIFVuZm9sZCBhbmltYXRpb24gZm9yIGNsb25lcyBpZiBzaG93aW5nIGZyb20gaGlkZGVuXG4gICAgICAgICAgICBpZiAoYWN0aXZlU29ydGFibGUub3B0aW9ucy5hbmltYXRpb24gJiYgIWNsb25lc0hpZGRlbiAmJiBjbG9uZXNIaWRkZW5CZWZvcmUpIHtcbiAgICAgICAgICAgICAgbXVsdGlEcmFnQ2xvbmVzLmZvckVhY2goZnVuY3Rpb24gKGNsb25lKSB7XG4gICAgICAgICAgICAgICAgYWN0aXZlU29ydGFibGUuYWRkQW5pbWF0aW9uU3RhdGUoe1xuICAgICAgICAgICAgICAgICAgdGFyZ2V0OiBjbG9uZSxcbiAgICAgICAgICAgICAgICAgIHJlY3Q6IGNsb25lc0Zyb21SZWN0XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgY2xvbmUuZnJvbVJlY3QgPSBjbG9uZXNGcm9tUmVjdDtcbiAgICAgICAgICAgICAgICBjbG9uZS50aGlzQW5pbWF0aW9uRHVyYXRpb24gPSBudWxsO1xuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgYWN0aXZlU29ydGFibGUuX3Nob3dDbG9uZShzb3J0YWJsZSk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgfSxcbiAgICBkcmFnT3ZlckFuaW1hdGlvbkNhcHR1cmU6IGZ1bmN0aW9uIGRyYWdPdmVyQW5pbWF0aW9uQ2FwdHVyZShfcmVmMTEpIHtcbiAgICAgIHZhciBkcmFnUmVjdCA9IF9yZWYxMS5kcmFnUmVjdCxcbiAgICAgICAgaXNPd25lciA9IF9yZWYxMS5pc093bmVyLFxuICAgICAgICBhY3RpdmVTb3J0YWJsZSA9IF9yZWYxMS5hY3RpdmVTb3J0YWJsZTtcbiAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgbXVsdGlEcmFnRWxlbWVudC50aGlzQW5pbWF0aW9uRHVyYXRpb24gPSBudWxsO1xuICAgICAgfSk7XG4gICAgICBpZiAoYWN0aXZlU29ydGFibGUub3B0aW9ucy5hbmltYXRpb24gJiYgIWlzT3duZXIgJiYgYWN0aXZlU29ydGFibGUubXVsdGlEcmFnLmlzTXVsdGlEcmFnKSB7XG4gICAgICAgIGNsb25lc0Zyb21SZWN0ID0gX2V4dGVuZHMoe30sIGRyYWdSZWN0KTtcbiAgICAgICAgdmFyIGRyYWdNYXRyaXggPSBtYXRyaXgoZHJhZ0VsJDEsIHRydWUpO1xuICAgICAgICBjbG9uZXNGcm9tUmVjdC50b3AgLT0gZHJhZ01hdHJpeC5mO1xuICAgICAgICBjbG9uZXNGcm9tUmVjdC5sZWZ0IC09IGRyYWdNYXRyaXguZTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGRyYWdPdmVyQW5pbWF0aW9uQ29tcGxldGU6IGZ1bmN0aW9uIGRyYWdPdmVyQW5pbWF0aW9uQ29tcGxldGUoKSB7XG4gICAgICBpZiAoZm9sZGluZykge1xuICAgICAgICBmb2xkaW5nID0gZmFsc2U7XG4gICAgICAgIHJlbW92ZU11bHRpRHJhZ0VsZW1lbnRzKCk7XG4gICAgICB9XG4gICAgfSxcbiAgICBkcm9wOiBmdW5jdGlvbiBkcm9wKF9yZWYxMikge1xuICAgICAgdmFyIGV2dCA9IF9yZWYxMi5vcmlnaW5hbEV2ZW50LFxuICAgICAgICByb290RWwgPSBfcmVmMTIucm9vdEVsLFxuICAgICAgICBwYXJlbnRFbCA9IF9yZWYxMi5wYXJlbnRFbCxcbiAgICAgICAgc29ydGFibGUgPSBfcmVmMTIuc29ydGFibGUsXG4gICAgICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCA9IF9yZWYxMi5kaXNwYXRjaFNvcnRhYmxlRXZlbnQsXG4gICAgICAgIG9sZEluZGV4ID0gX3JlZjEyLm9sZEluZGV4LFxuICAgICAgICBwdXRTb3J0YWJsZSA9IF9yZWYxMi5wdXRTb3J0YWJsZTtcbiAgICAgIHZhciB0b1NvcnRhYmxlID0gcHV0U29ydGFibGUgfHwgdGhpcy5zb3J0YWJsZTtcbiAgICAgIGlmICghZXZ0KSByZXR1cm47XG4gICAgICB2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucyxcbiAgICAgICAgY2hpbGRyZW4gPSBwYXJlbnRFbC5jaGlsZHJlbjtcblxuICAgICAgLy8gTXVsdGktZHJhZyBzZWxlY3Rpb25cbiAgICAgIGlmICghZHJhZ1N0YXJ0ZWQpIHtcbiAgICAgICAgaWYgKG9wdGlvbnMubXVsdGlEcmFnS2V5ICYmICF0aGlzLm11bHRpRHJhZ0tleURvd24pIHtcbiAgICAgICAgICB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZygpO1xuICAgICAgICB9XG4gICAgICAgIHRvZ2dsZUNsYXNzKGRyYWdFbCQxLCBvcHRpb25zLnNlbGVjdGVkQ2xhc3MsICF+bXVsdGlEcmFnRWxlbWVudHMuaW5kZXhPZihkcmFnRWwkMSkpO1xuICAgICAgICBpZiAoIX5tdWx0aURyYWdFbGVtZW50cy5pbmRleE9mKGRyYWdFbCQxKSkge1xuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLnB1c2goZHJhZ0VsJDEpO1xuICAgICAgICAgIGRpc3BhdGNoRXZlbnQoe1xuICAgICAgICAgICAgc29ydGFibGU6IHNvcnRhYmxlLFxuICAgICAgICAgICAgcm9vdEVsOiByb290RWwsXG4gICAgICAgICAgICBuYW1lOiAnc2VsZWN0JyxcbiAgICAgICAgICAgIHRhcmdldEVsOiBkcmFnRWwkMSxcbiAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgLy8gTW9kaWZpZXIgYWN0aXZhdGVkLCBzZWxlY3QgZnJvbSBsYXN0IHRvIGRyYWdFbFxuICAgICAgICAgIGlmIChldnQuc2hpZnRLZXkgJiYgbGFzdE11bHRpRHJhZ1NlbGVjdCAmJiBzb3J0YWJsZS5lbC5jb250YWlucyhsYXN0TXVsdGlEcmFnU2VsZWN0KSkge1xuICAgICAgICAgICAgdmFyIGxhc3RJbmRleCA9IGluZGV4KGxhc3RNdWx0aURyYWdTZWxlY3QpLFxuICAgICAgICAgICAgICBjdXJyZW50SW5kZXggPSBpbmRleChkcmFnRWwkMSk7XG4gICAgICAgICAgICBpZiAofmxhc3RJbmRleCAmJiB+Y3VycmVudEluZGV4ICYmIGxhc3RJbmRleCAhPT0gY3VycmVudEluZGV4KSB7XG4gICAgICAgICAgICAgIC8vIE11c3QgaW5jbHVkZSBsYXN0TXVsdGlEcmFnU2VsZWN0IChzZWxlY3QgaXQpLCBpbiBjYXNlIG1vZGlmaWVkIHNlbGVjdGlvbiBmcm9tIG5vIHNlbGVjdGlvblxuICAgICAgICAgICAgICAvLyAoYnV0IHByZXZpb3VzIHNlbGVjdGlvbiBleGlzdGVkKVxuICAgICAgICAgICAgICB2YXIgbiwgaTtcbiAgICAgICAgICAgICAgaWYgKGN1cnJlbnRJbmRleCA+IGxhc3RJbmRleCkge1xuICAgICAgICAgICAgICAgIGkgPSBsYXN0SW5kZXg7XG4gICAgICAgICAgICAgICAgbiA9IGN1cnJlbnRJbmRleDtcbiAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBpID0gY3VycmVudEluZGV4O1xuICAgICAgICAgICAgICAgIG4gPSBsYXN0SW5kZXggKyAxO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIGZvciAoOyBpIDwgbjsgaSsrKSB7XG4gICAgICAgICAgICAgICAgaWYgKH5tdWx0aURyYWdFbGVtZW50cy5pbmRleE9mKGNoaWxkcmVuW2ldKSkgY29udGludWU7XG4gICAgICAgICAgICAgICAgdG9nZ2xlQ2xhc3MoY2hpbGRyZW5baV0sIG9wdGlvbnMuc2VsZWN0ZWRDbGFzcywgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMucHVzaChjaGlsZHJlbltpXSk7XG4gICAgICAgICAgICAgICAgZGlzcGF0Y2hFdmVudCh7XG4gICAgICAgICAgICAgICAgICBzb3J0YWJsZTogc29ydGFibGUsXG4gICAgICAgICAgICAgICAgICByb290RWw6IHJvb3RFbCxcbiAgICAgICAgICAgICAgICAgIG5hbWU6ICdzZWxlY3QnLFxuICAgICAgICAgICAgICAgICAgdGFyZ2V0RWw6IGNoaWxkcmVuW2ldLFxuICAgICAgICAgICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgbGFzdE11bHRpRHJhZ1NlbGVjdCA9IGRyYWdFbCQxO1xuICAgICAgICAgIH1cbiAgICAgICAgICBtdWx0aURyYWdTb3J0YWJsZSA9IHRvU29ydGFibGU7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuc3BsaWNlKG11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YoZHJhZ0VsJDEpLCAxKTtcbiAgICAgICAgICBsYXN0TXVsdGlEcmFnU2VsZWN0ID0gbnVsbDtcbiAgICAgICAgICBkaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICAgIHNvcnRhYmxlOiBzb3J0YWJsZSxcbiAgICAgICAgICAgIHJvb3RFbDogcm9vdEVsLFxuICAgICAgICAgICAgbmFtZTogJ2Rlc2VsZWN0JyxcbiAgICAgICAgICAgIHRhcmdldEVsOiBkcmFnRWwkMSxcbiAgICAgICAgICAgIG9yaWdpbmFsRXZlbnQ6IGV2dFxuICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIC8vIE11bHRpLWRyYWcgZHJvcFxuICAgICAgaWYgKGRyYWdTdGFydGVkICYmIHRoaXMuaXNNdWx0aURyYWcpIHtcbiAgICAgICAgZm9sZGluZyA9IGZhbHNlO1xuICAgICAgICAvLyBEbyBub3QgXCJ1bmZvbGRcIiBhZnRlciBhcm91bmQgZHJhZ0VsIGlmIHJldmVydGVkXG4gICAgICAgIGlmICgocGFyZW50RWxbZXhwYW5kb10ub3B0aW9ucy5zb3J0IHx8IHBhcmVudEVsICE9PSByb290RWwpICYmIG11bHRpRHJhZ0VsZW1lbnRzLmxlbmd0aCA+IDEpIHtcbiAgICAgICAgICB2YXIgZHJhZ1JlY3QgPSBnZXRSZWN0KGRyYWdFbCQxKSxcbiAgICAgICAgICAgIG11bHRpRHJhZ0luZGV4ID0gaW5kZXgoZHJhZ0VsJDEsICc6bm90KC4nICsgdGhpcy5vcHRpb25zLnNlbGVjdGVkQ2xhc3MgKyAnKScpO1xuICAgICAgICAgIGlmICghaW5pdGlhbEZvbGRpbmcgJiYgb3B0aW9ucy5hbmltYXRpb24pIGRyYWdFbCQxLnRoaXNBbmltYXRpb25EdXJhdGlvbiA9IG51bGw7XG4gICAgICAgICAgdG9Tb3J0YWJsZS5jYXB0dXJlQW5pbWF0aW9uU3RhdGUoKTtcbiAgICAgICAgICBpZiAoIWluaXRpYWxGb2xkaW5nKSB7XG4gICAgICAgICAgICBpZiAob3B0aW9ucy5hbmltYXRpb24pIHtcbiAgICAgICAgICAgICAgZHJhZ0VsJDEuZnJvbVJlY3QgPSBkcmFnUmVjdDtcbiAgICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnQudGhpc0FuaW1hdGlvbkR1cmF0aW9uID0gbnVsbDtcbiAgICAgICAgICAgICAgICBpZiAobXVsdGlEcmFnRWxlbWVudCAhPT0gZHJhZ0VsJDEpIHtcbiAgICAgICAgICAgICAgICAgIHZhciByZWN0ID0gZm9sZGluZyA/IGdldFJlY3QobXVsdGlEcmFnRWxlbWVudCkgOiBkcmFnUmVjdDtcbiAgICAgICAgICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnQuZnJvbVJlY3QgPSByZWN0O1xuXG4gICAgICAgICAgICAgICAgICAvLyBQcmVwYXJlIHVuZm9sZCBhbmltYXRpb25cbiAgICAgICAgICAgICAgICAgIHRvU29ydGFibGUuYWRkQW5pbWF0aW9uU3RhdGUoe1xuICAgICAgICAgICAgICAgICAgICB0YXJnZXQ6IG11bHRpRHJhZ0VsZW1lbnQsXG4gICAgICAgICAgICAgICAgICAgIHJlY3Q6IHJlY3RcbiAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vIE11bHRpIGRyYWcgZWxlbWVudHMgYXJlIG5vdCBuZWNlc3NhcmlseSByZW1vdmVkIGZyb20gdGhlIERPTSBvbiBkcm9wLCBzbyB0byByZWluc2VydFxuICAgICAgICAgICAgLy8gcHJvcGVybHkgdGhleSBtdXN0IGFsbCBiZSByZW1vdmVkXG4gICAgICAgICAgICByZW1vdmVNdWx0aURyYWdFbGVtZW50cygpO1xuICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgICBpZiAoY2hpbGRyZW5bbXVsdGlEcmFnSW5kZXhdKSB7XG4gICAgICAgICAgICAgICAgcGFyZW50RWwuaW5zZXJ0QmVmb3JlKG11bHRpRHJhZ0VsZW1lbnQsIGNoaWxkcmVuW211bHRpRHJhZ0luZGV4XSk7XG4gICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgcGFyZW50RWwuYXBwZW5kQ2hpbGQobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgbXVsdGlEcmFnSW5kZXgrKztcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAvLyBJZiBpbml0aWFsIGZvbGRpbmcgaXMgZG9uZSwgdGhlIGVsZW1lbnRzIG1heSBoYXZlIGNoYW5nZWQgcG9zaXRpb24gYmVjYXVzZSB0aGV5IGFyZSBub3dcbiAgICAgICAgICAgIC8vIHVuZm9sZGluZyBhcm91bmQgZHJhZ0VsLCBldmVuIHRob3VnaCBkcmFnRWwgbWF5IG5vdCBoYXZlIGhpcyBpbmRleCBjaGFuZ2VkLCBzbyB1cGRhdGUgZXZlbnRcbiAgICAgICAgICAgIC8vIG11c3QgYmUgZmlyZWQgaGVyZSBhcyBTb3J0YWJsZSB3aWxsIG5vdC5cbiAgICAgICAgICAgIGlmIChvbGRJbmRleCA9PT0gaW5kZXgoZHJhZ0VsJDEpKSB7XG4gICAgICAgICAgICAgIHZhciB1cGRhdGUgPSBmYWxzZTtcbiAgICAgICAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgICAgICAgICAgICAgIGlmIChtdWx0aURyYWdFbGVtZW50LnNvcnRhYmxlSW5kZXggIT09IGluZGV4KG11bHRpRHJhZ0VsZW1lbnQpKSB7XG4gICAgICAgICAgICAgICAgICB1cGRhdGUgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgIGlmICh1cGRhdGUpIHtcbiAgICAgICAgICAgICAgICBkaXNwYXRjaFNvcnRhYmxlRXZlbnQoJ3VwZGF0ZScpO1xuICAgICAgICAgICAgICAgIGRpc3BhdGNoU29ydGFibGVFdmVudCgnc29ydCcpO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgLy8gTXVzdCBiZSBkb25lIGFmdGVyIGNhcHR1cmluZyBpbmRpdmlkdWFsIHJlY3RzIChzY3JvbGwgYmFyKVxuICAgICAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgICAgIHVuc2V0UmVjdChtdWx0aURyYWdFbGVtZW50KTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgICB0b1NvcnRhYmxlLmFuaW1hdGVBbGwoKTtcbiAgICAgICAgfVxuICAgICAgICBtdWx0aURyYWdTb3J0YWJsZSA9IHRvU29ydGFibGU7XG4gICAgICB9XG5cbiAgICAgIC8vIFJlbW92ZSBjbG9uZXMgaWYgbmVjZXNzYXJ5XG4gICAgICBpZiAocm9vdEVsID09PSBwYXJlbnRFbCB8fCBwdXRTb3J0YWJsZSAmJiBwdXRTb3J0YWJsZS5sYXN0UHV0TW9kZSAhPT0gJ2Nsb25lJykge1xuICAgICAgICBtdWx0aURyYWdDbG9uZXMuZm9yRWFjaChmdW5jdGlvbiAoY2xvbmUpIHtcbiAgICAgICAgICBjbG9uZS5wYXJlbnROb2RlICYmIGNsb25lLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY2xvbmUpO1xuICAgICAgICB9KTtcbiAgICAgIH1cbiAgICB9LFxuICAgIG51bGxpbmdHbG9iYWw6IGZ1bmN0aW9uIG51bGxpbmdHbG9iYWwoKSB7XG4gICAgICB0aGlzLmlzTXVsdGlEcmFnID0gZHJhZ1N0YXJ0ZWQgPSBmYWxzZTtcbiAgICAgIG11bHRpRHJhZ0Nsb25lcy5sZW5ndGggPSAwO1xuICAgIH0sXG4gICAgZGVzdHJveUdsb2JhbDogZnVuY3Rpb24gZGVzdHJveUdsb2JhbCgpIHtcbiAgICAgIHRoaXMuX2Rlc2VsZWN0TXVsdGlEcmFnKCk7XG4gICAgICBvZmYoZG9jdW1lbnQsICdwb2ludGVydXAnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICBvZmYoZG9jdW1lbnQsICdtb3VzZXVwJywgdGhpcy5fZGVzZWxlY3RNdWx0aURyYWcpO1xuICAgICAgb2ZmKGRvY3VtZW50LCAndG91Y2hlbmQnLCB0aGlzLl9kZXNlbGVjdE11bHRpRHJhZyk7XG4gICAgICBvZmYoZG9jdW1lbnQsICdrZXlkb3duJywgdGhpcy5fY2hlY2tLZXlEb3duKTtcbiAgICAgIG9mZihkb2N1bWVudCwgJ2tleXVwJywgdGhpcy5fY2hlY2tLZXlVcCk7XG4gICAgfSxcbiAgICBfZGVzZWxlY3RNdWx0aURyYWc6IGZ1bmN0aW9uIF9kZXNlbGVjdE11bHRpRHJhZyhldnQpIHtcbiAgICAgIGlmICh0eXBlb2YgZHJhZ1N0YXJ0ZWQgIT09IFwidW5kZWZpbmVkXCIgJiYgZHJhZ1N0YXJ0ZWQpIHJldHVybjtcblxuICAgICAgLy8gT25seSBkZXNlbGVjdCBpZiBzZWxlY3Rpb24gaXMgaW4gdGhpcyBzb3J0YWJsZVxuICAgICAgaWYgKG11bHRpRHJhZ1NvcnRhYmxlICE9PSB0aGlzLnNvcnRhYmxlKSByZXR1cm47XG5cbiAgICAgIC8vIE9ubHkgZGVzZWxlY3QgaWYgdGFyZ2V0IGlzIG5vdCBpdGVtIGluIHRoaXMgc29ydGFibGVcbiAgICAgIGlmIChldnQgJiYgY2xvc2VzdChldnQudGFyZ2V0LCB0aGlzLm9wdGlvbnMuZHJhZ2dhYmxlLCB0aGlzLnNvcnRhYmxlLmVsLCBmYWxzZSkpIHJldHVybjtcblxuICAgICAgLy8gT25seSBkZXNlbGVjdCBpZiBsZWZ0IGNsaWNrXG4gICAgICBpZiAoZXZ0ICYmIGV2dC5idXR0b24gIT09IDApIHJldHVybjtcbiAgICAgIHdoaWxlIChtdWx0aURyYWdFbGVtZW50cy5sZW5ndGgpIHtcbiAgICAgICAgdmFyIGVsID0gbXVsdGlEcmFnRWxlbWVudHNbMF07XG4gICAgICAgIHRvZ2dsZUNsYXNzKGVsLCB0aGlzLm9wdGlvbnMuc2VsZWN0ZWRDbGFzcywgZmFsc2UpO1xuICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5zaGlmdCgpO1xuICAgICAgICBkaXNwYXRjaEV2ZW50KHtcbiAgICAgICAgICBzb3J0YWJsZTogdGhpcy5zb3J0YWJsZSxcbiAgICAgICAgICByb290RWw6IHRoaXMuc29ydGFibGUuZWwsXG4gICAgICAgICAgbmFtZTogJ2Rlc2VsZWN0JyxcbiAgICAgICAgICB0YXJnZXRFbDogZWwsXG4gICAgICAgICAgb3JpZ2luYWxFdmVudDogZXZ0XG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgIH0sXG4gICAgX2NoZWNrS2V5RG93bjogZnVuY3Rpb24gX2NoZWNrS2V5RG93bihldnQpIHtcbiAgICAgIGlmIChldnQua2V5ID09PSB0aGlzLm9wdGlvbnMubXVsdGlEcmFnS2V5KSB7XG4gICAgICAgIHRoaXMubXVsdGlEcmFnS2V5RG93biA9IHRydWU7XG4gICAgICB9XG4gICAgfSxcbiAgICBfY2hlY2tLZXlVcDogZnVuY3Rpb24gX2NoZWNrS2V5VXAoZXZ0KSB7XG4gICAgICBpZiAoZXZ0LmtleSA9PT0gdGhpcy5vcHRpb25zLm11bHRpRHJhZ0tleSkge1xuICAgICAgICB0aGlzLm11bHRpRHJhZ0tleURvd24gPSBmYWxzZTtcbiAgICAgIH1cbiAgICB9XG4gIH07XG4gIHJldHVybiBfZXh0ZW5kcyhNdWx0aURyYWcsIHtcbiAgICAvLyBTdGF0aWMgbWV0aG9kcyAmIHByb3BlcnRpZXNcbiAgICBwbHVnaW5OYW1lOiAnbXVsdGlEcmFnJyxcbiAgICB1dGlsczoge1xuICAgICAgLyoqXHJcbiAgICAgICAqIFNlbGVjdHMgdGhlIHByb3ZpZGVkIG11bHRpLWRyYWcgaXRlbVxyXG4gICAgICAgKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gZWwgICAgVGhlIGVsZW1lbnQgdG8gYmUgc2VsZWN0ZWRcclxuICAgICAgICovXG4gICAgICBzZWxlY3Q6IGZ1bmN0aW9uIHNlbGVjdChlbCkge1xuICAgICAgICB2YXIgc29ydGFibGUgPSBlbC5wYXJlbnROb2RlW2V4cGFuZG9dO1xuICAgICAgICBpZiAoIXNvcnRhYmxlIHx8ICFzb3J0YWJsZS5vcHRpb25zLm11bHRpRHJhZyB8fCB+bXVsdGlEcmFnRWxlbWVudHMuaW5kZXhPZihlbCkpIHJldHVybjtcbiAgICAgICAgaWYgKG11bHRpRHJhZ1NvcnRhYmxlICYmIG11bHRpRHJhZ1NvcnRhYmxlICE9PSBzb3J0YWJsZSkge1xuICAgICAgICAgIG11bHRpRHJhZ1NvcnRhYmxlLm11bHRpRHJhZy5fZGVzZWxlY3RNdWx0aURyYWcoKTtcbiAgICAgICAgICBtdWx0aURyYWdTb3J0YWJsZSA9IHNvcnRhYmxlO1xuICAgICAgICB9XG4gICAgICAgIHRvZ2dsZUNsYXNzKGVsLCBzb3J0YWJsZS5vcHRpb25zLnNlbGVjdGVkQ2xhc3MsIHRydWUpO1xuICAgICAgICBtdWx0aURyYWdFbGVtZW50cy5wdXNoKGVsKTtcbiAgICAgIH0sXG4gICAgICAvKipcclxuICAgICAgICogRGVzZWxlY3RzIHRoZSBwcm92aWRlZCBtdWx0aS1kcmFnIGl0ZW1cclxuICAgICAgICogQHBhcmFtICB7SFRNTEVsZW1lbnR9IGVsICAgIFRoZSBlbGVtZW50IHRvIGJlIGRlc2VsZWN0ZWRcclxuICAgICAgICovXG4gICAgICBkZXNlbGVjdDogZnVuY3Rpb24gZGVzZWxlY3QoZWwpIHtcbiAgICAgICAgdmFyIHNvcnRhYmxlID0gZWwucGFyZW50Tm9kZVtleHBhbmRvXSxcbiAgICAgICAgICBpbmRleCA9IG11bHRpRHJhZ0VsZW1lbnRzLmluZGV4T2YoZWwpO1xuICAgICAgICBpZiAoIXNvcnRhYmxlIHx8ICFzb3J0YWJsZS5vcHRpb25zLm11bHRpRHJhZyB8fCAhfmluZGV4KSByZXR1cm47XG4gICAgICAgIHRvZ2dsZUNsYXNzKGVsLCBzb3J0YWJsZS5vcHRpb25zLnNlbGVjdGVkQ2xhc3MsIGZhbHNlKTtcbiAgICAgICAgbXVsdGlEcmFnRWxlbWVudHMuc3BsaWNlKGluZGV4LCAxKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGV2ZW50UHJvcGVydGllczogZnVuY3Rpb24gZXZlbnRQcm9wZXJ0aWVzKCkge1xuICAgICAgdmFyIF90aGlzMyA9IHRoaXM7XG4gICAgICB2YXIgb2xkSW5kaWNpZXMgPSBbXSxcbiAgICAgICAgbmV3SW5kaWNpZXMgPSBbXTtcbiAgICAgIG11bHRpRHJhZ0VsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKG11bHRpRHJhZ0VsZW1lbnQpIHtcbiAgICAgICAgb2xkSW5kaWNpZXMucHVzaCh7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudDogbXVsdGlEcmFnRWxlbWVudCxcbiAgICAgICAgICBpbmRleDogbXVsdGlEcmFnRWxlbWVudC5zb3J0YWJsZUluZGV4XG4gICAgICAgIH0pO1xuXG4gICAgICAgIC8vIG11bHRpRHJhZ0VsZW1lbnRzIHdpbGwgYWxyZWFkeSBiZSBzb3J0ZWQgaWYgZm9sZGluZ1xuICAgICAgICB2YXIgbmV3SW5kZXg7XG4gICAgICAgIGlmIChmb2xkaW5nICYmIG11bHRpRHJhZ0VsZW1lbnQgIT09IGRyYWdFbCQxKSB7XG4gICAgICAgICAgbmV3SW5kZXggPSAtMTtcbiAgICAgICAgfSBlbHNlIGlmIChmb2xkaW5nKSB7XG4gICAgICAgICAgbmV3SW5kZXggPSBpbmRleChtdWx0aURyYWdFbGVtZW50LCAnOm5vdCguJyArIF90aGlzMy5vcHRpb25zLnNlbGVjdGVkQ2xhc3MgKyAnKScpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIG5ld0luZGV4ID0gaW5kZXgobXVsdGlEcmFnRWxlbWVudCk7XG4gICAgICAgIH1cbiAgICAgICAgbmV3SW5kaWNpZXMucHVzaCh7XG4gICAgICAgICAgbXVsdGlEcmFnRWxlbWVudDogbXVsdGlEcmFnRWxlbWVudCxcbiAgICAgICAgICBpbmRleDogbmV3SW5kZXhcbiAgICAgICAgfSk7XG4gICAgICB9KTtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIGl0ZW1zOiBfdG9Db25zdW1hYmxlQXJyYXkobXVsdGlEcmFnRWxlbWVudHMpLFxuICAgICAgICBjbG9uZXM6IFtdLmNvbmNhdChtdWx0aURyYWdDbG9uZXMpLFxuICAgICAgICBvbGRJbmRpY2llczogb2xkSW5kaWNpZXMsXG4gICAgICAgIG5ld0luZGljaWVzOiBuZXdJbmRpY2llc1xuICAgICAgfTtcbiAgICB9LFxuICAgIG9wdGlvbkxpc3RlbmVyczoge1xuICAgICAgbXVsdGlEcmFnS2V5OiBmdW5jdGlvbiBtdWx0aURyYWdLZXkoa2V5KSB7XG4gICAgICAgIGtleSA9IGtleS50b0xvd2VyQ2FzZSgpO1xuICAgICAgICBpZiAoa2V5ID09PSAnY3RybCcpIHtcbiAgICAgICAgICBrZXkgPSAnQ29udHJvbCc7XG4gICAgICAgIH0gZWxzZSBpZiAoa2V5Lmxlbmd0aCA+IDEpIHtcbiAgICAgICAgICBrZXkgPSBrZXkuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBrZXkuc3Vic3RyKDEpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBrZXk7XG4gICAgICB9XG4gICAgfVxuICB9KTtcbn1cbmZ1bmN0aW9uIGluc2VydE11bHRpRHJhZ0VsZW1lbnRzKGNsb25lc0luc2VydGVkLCByb290RWwpIHtcbiAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCwgaSkge1xuICAgIHZhciB0YXJnZXQgPSByb290RWwuY2hpbGRyZW5bbXVsdGlEcmFnRWxlbWVudC5zb3J0YWJsZUluZGV4ICsgKGNsb25lc0luc2VydGVkID8gTnVtYmVyKGkpIDogMCldO1xuICAgIGlmICh0YXJnZXQpIHtcbiAgICAgIHJvb3RFbC5pbnNlcnRCZWZvcmUobXVsdGlEcmFnRWxlbWVudCwgdGFyZ2V0KTtcbiAgICB9IGVsc2Uge1xuICAgICAgcm9vdEVsLmFwcGVuZENoaWxkKG11bHRpRHJhZ0VsZW1lbnQpO1xuICAgIH1cbiAgfSk7XG59XG5cbi8qKlxyXG4gKiBJbnNlcnQgbXVsdGktZHJhZyBjbG9uZXNcclxuICogQHBhcmFtICB7W0Jvb2xlYW5dfSBlbGVtZW50c0luc2VydGVkICBXaGV0aGVyIHRoZSBtdWx0aS1kcmFnIGVsZW1lbnRzIGFyZSBpbnNlcnRlZFxyXG4gKiBAcGFyYW0gIHtIVE1MRWxlbWVudH0gcm9vdEVsXHJcbiAqL1xuZnVuY3Rpb24gaW5zZXJ0TXVsdGlEcmFnQ2xvbmVzKGVsZW1lbnRzSW5zZXJ0ZWQsIHJvb3RFbCkge1xuICBtdWx0aURyYWdDbG9uZXMuZm9yRWFjaChmdW5jdGlvbiAoY2xvbmUsIGkpIHtcbiAgICB2YXIgdGFyZ2V0ID0gcm9vdEVsLmNoaWxkcmVuW2Nsb25lLnNvcnRhYmxlSW5kZXggKyAoZWxlbWVudHNJbnNlcnRlZCA/IE51bWJlcihpKSA6IDApXTtcbiAgICBpZiAodGFyZ2V0KSB7XG4gICAgICByb290RWwuaW5zZXJ0QmVmb3JlKGNsb25lLCB0YXJnZXQpO1xuICAgIH0gZWxzZSB7XG4gICAgICByb290RWwuYXBwZW5kQ2hpbGQoY2xvbmUpO1xuICAgIH1cbiAgfSk7XG59XG5mdW5jdGlvbiByZW1vdmVNdWx0aURyYWdFbGVtZW50cygpIHtcbiAgbXVsdGlEcmFnRWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAobXVsdGlEcmFnRWxlbWVudCkge1xuICAgIGlmIChtdWx0aURyYWdFbGVtZW50ID09PSBkcmFnRWwkMSkgcmV0dXJuO1xuICAgIG11bHRpRHJhZ0VsZW1lbnQucGFyZW50Tm9kZSAmJiBtdWx0aURyYWdFbGVtZW50LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQobXVsdGlEcmFnRWxlbWVudCk7XG4gIH0pO1xufVxuXG5Tb3J0YWJsZS5tb3VudChuZXcgQXV0b1Njcm9sbFBsdWdpbigpKTtcblNvcnRhYmxlLm1vdW50KFJlbW92ZSwgUmV2ZXJ0KTtcblxuZXhwb3J0IGRlZmF1bHQgU29ydGFibGU7XG5leHBvcnQgeyBNdWx0aURyYWdQbHVnaW4gYXMgTXVsdGlEcmFnLCBTb3J0YWJsZSwgU3dhcFBsdWdpbiBhcyBTd2FwIH07XG4iLCAiaW1wb3J0IFNvcnRhYmxlIGZyb20gJ3NvcnRhYmxlanMnXG5cbndpbmRvdy5Tb3J0YWJsZSA9IFNvcnRhYmxlXG5cbmlmICh0eXBlb2Ygd2luZG93LkxpdmV3aXJlID09PSAndW5kZWZpbmVkJykge1xuICAgIHRocm93ICdMaXZld2lyZSBTb3J0YWJsZSBQbHVnaW46IHdpbmRvdy5MaXZld2lyZSBpcyB1bmRlZmluZWQuIE1ha2Ugc3VyZSBAbGl2ZXdpcmVTY3JpcHRzIGlzIHBsYWNlZCBhYm92ZSB0aGlzIHNjcmlwdCBpbmNsdWRlJ1xufVxuXG5jb25zdCBtb3ZlRW5kTW9ycGhNYXJrZXIgPSAoZWwpID0+IHtcbiAgICBjb25zdCBlbmRNb3JwaE1hcmtlciA9IEFycmF5LmZyb20oZWwuY2hpbGROb2RlcykuZmlsdGVyKChjaGlsZE5vZGUpID0+IHtcbiAgICAgICAgcmV0dXJuIChcbiAgICAgICAgICAgIGNoaWxkTm9kZS5ub2RlVHlwZSA9PT0gOCAmJlxuICAgICAgICAgICAgWydbaWYgRU5EQkxPQ0tdPjwhW2VuZGlmXScsICdfX0VOREJMT0NLX18nXS5pbmNsdWRlcyhjaGlsZE5vZGUubm9kZVZhbHVlPy50cmltKCkpXG4gICAgICAgIClcbiAgICB9KVswXVxuXG4gICAgaWYgKGVuZE1vcnBoTWFya2VyKSB7XG4gICAgICAgIGVsLmFwcGVuZENoaWxkKGVuZE1vcnBoTWFya2VyKVxuICAgIH1cbn1cblxuTGl2ZXdpcmUuZGlyZWN0aXZlKCdzb3J0YWJsZScsICh7IGVsLCBkaXJlY3RpdmUsIGNvbXBvbmVudCB9KSA9PiB7XG4gICAgaWYgKGRpcmVjdGl2ZS5tb2RpZmllcnMubGVuZ3RoID4gMCkge1xuICAgICAgICByZXR1cm5cbiAgICB9XG5cbiAgICBsZXQgb3B0aW9ucyA9IHt9XG5cbiAgICBpZiAoZWwuaGFzQXR0cmlidXRlKCd3aXJlOnNvcnRhYmxlLm9wdGlvbnMnKSkge1xuICAgICAgICBvcHRpb25zID0gbmV3IEZ1bmN0aW9uKGByZXR1cm4gJHtlbC5nZXRBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUub3B0aW9ucycpfTtgKSgpXG4gICAgfVxuXG4gICAgZWwubGl2ZXdpcmVfc29ydGFibGUgPSB3aW5kb3cuU29ydGFibGUuY3JlYXRlKGVsLCB7XG4gICAgICAgIHNvcnQ6IHRydWUsXG4gICAgICAgIC4uLm9wdGlvbnMsXG4gICAgICAgIGRyYWdnYWJsZTogJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaXRlbV0nLFxuICAgICAgICBoYW5kbGU6IGVsLnF1ZXJ5U2VsZWN0b3IoJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaGFuZGxlXScpID8gJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaGFuZGxlXScgOiBudWxsLFxuICAgICAgICBkYXRhSWRBdHRyOiAnd2lyZTpzb3J0YWJsZS5pdGVtJyxcbiAgICAgICAgZ3JvdXA6IHtcbiAgICAgICAgICAgIHB1bGw6IGZhbHNlLFxuICAgICAgICAgICAgcHV0OiBmYWxzZSxcbiAgICAgICAgICAgIC4uLm9wdGlvbnMuZ3JvdXAsXG4gICAgICAgICAgICBuYW1lOiBlbC5nZXRBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUnKSxcbiAgICAgICAgfSxcbiAgICAgICAgc3RvcmU6IHtcbiAgICAgICAgICAgIC4uLm9wdGlvbnMuc3RvcmUsXG4gICAgICAgICAgICBzZXQ6IGZ1bmN0aW9uIChzb3J0YWJsZSkge1xuICAgICAgICAgICAgICAgIGxldCBpdGVtcyA9IHNvcnRhYmxlLnRvQXJyYXkoKS5tYXAoKHZhbHVlLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXI6IGluZGV4ICsgMSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiB2YWx1ZSxcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgICAgICBtb3ZlRW5kTW9ycGhNYXJrZXIoZWwpXG5cbiAgICAgICAgICAgICAgICBjb21wb25lbnQuJHdpcmUuY2FsbChkaXJlY3RpdmUubWV0aG9kLCBpdGVtcylcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0sXG4gICAgfSlcblxuICAgIGxldCBoYXNTZXRIYW5kbGVDb3JyZWN0bHkgPSBlbC5xdWVyeVNlbGVjdG9yKCdbd2lyZVxcXFw6c29ydGFibGVcXFxcLml0ZW1dJykgIT09IG51bGxcblxuICAgIC8vIElmIHRoZXJlIGFyZSBhbHJlYWR5IGl0ZW1zLCB0aGVuIHRoZSAnaGFuZGxlJyBvcHRpb24gaGFzIGFscmVhZHkgYmVlbiBjb3JyZWN0bHkgc2V0LlxuICAgIC8vIFRoZSBvcHRpb24gZG9lcyBub3QgaGF2ZSB0byByZWV2YWx1YXRlZCBhZnRlciB0aGUgbmV4dCBMaXZld2lyZSBjb21wb25lbnQgdXBkYXRlLlxuICAgIGlmIChoYXNTZXRIYW5kbGVDb3JyZWN0bHkpIHtcbiAgICAgICAgcmV0dXJuXG4gICAgfVxuXG4gICAgY29uc3QgY3VycmVudENvbXBvbmVudCA9IGNvbXBvbmVudFxuXG4gICAgTGl2ZXdpcmUuaG9vaygnY29tbWl0JywgKHsgY29tcG9uZW50LCBzdWNjZWVkIH0pID0+IHtcbiAgICAgICAgaWYgKGNvbXBvbmVudC5pZCAhPT0gY3VycmVudENvbXBvbmVudC5pZCkge1xuICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgIH1cblxuICAgICAgICBpZiAoaGFzU2V0SGFuZGxlQ29ycmVjdGx5KSB7XG4gICAgICAgICAgICByZXR1cm5cbiAgICAgICAgfVxuXG4gICAgICAgIHN1Y2NlZWQoKCkgPT4ge1xuICAgICAgICAgICAgcXVldWVNaWNyb3Rhc2soKCkgPT4ge1xuICAgICAgICAgICAgICAgIGVsLmxpdmV3aXJlX3NvcnRhYmxlLm9wdGlvbihcbiAgICAgICAgICAgICAgICAgICAgJ2hhbmRsZScsXG4gICAgICAgICAgICAgICAgICAgIGVsLnF1ZXJ5U2VsZWN0b3IoJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaGFuZGxlXScpID8gJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaGFuZGxlXScgOiBudWxsLFxuICAgICAgICAgICAgICAgIClcblxuICAgICAgICAgICAgICAgIGhhc1NldEhhbmRsZUNvcnJlY3RseSA9IGVsLnF1ZXJ5U2VsZWN0b3IoJ1t3aXJlXFxcXDpzb3J0YWJsZVxcXFwuaXRlbV0nKSAhPT0gbnVsbFxuICAgICAgICAgICAgfSlcbiAgICAgICAgfSlcbiAgICB9KVxufSlcblxuTGl2ZXdpcmUuZGlyZWN0aXZlKCdzb3J0YWJsZS1ncm91cCcsICh7IGVsLCBkaXJlY3RpdmUsIGNvbXBvbmVudCB9KSA9PiB7XG4gICAgLy8gT25seSBmaXJlIHRoaXMgaGFuZGxlciBvbiB0aGUgXCJyb290XCIgZ3JvdXAgZGlyZWN0aXZlLlxuICAgIGlmICghZGlyZWN0aXZlLm1vZGlmaWVycy5pbmNsdWRlcygnaXRlbS1ncm91cCcpKSB7XG4gICAgICAgIHJldHVyblxuICAgIH1cblxuICAgIGxldCBvcHRpb25zID0ge31cblxuICAgIGlmIChlbC5oYXNBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUtZ3JvdXAub3B0aW9ucycpKSB7XG4gICAgICAgIG9wdGlvbnMgPSBuZXcgRnVuY3Rpb24oYHJldHVybiAke2VsLmdldEF0dHJpYnV0ZSgnd2lyZTpzb3J0YWJsZS1ncm91cC5vcHRpb25zJyl9O2ApKClcbiAgICB9XG5cbiAgICBlbC5saXZld2lyZV9zb3J0YWJsZSA9IHdpbmRvdy5Tb3J0YWJsZS5jcmVhdGUoZWwsIHtcbiAgICAgICAgc29ydDogdHJ1ZSxcbiAgICAgICAgLi4ub3B0aW9ucyxcbiAgICAgICAgZHJhZ2dhYmxlOiAnW3dpcmVcXFxcOnNvcnRhYmxlLWdyb3VwXFxcXC5pdGVtXScsXG4gICAgICAgIGhhbmRsZTogJ1t3aXJlXFxcXDpzb3J0YWJsZS1ncm91cFxcXFwuaGFuZGxlXScsXG4gICAgICAgIGRhdGFJZEF0dHI6ICd3aXJlOnNvcnRhYmxlLWdyb3VwLml0ZW0nLFxuICAgICAgICBncm91cDoge1xuICAgICAgICAgICAgcHVsbDogdHJ1ZSxcbiAgICAgICAgICAgIHB1dDogdHJ1ZSxcbiAgICAgICAgICAgIC4uLm9wdGlvbnMuZ3JvdXAsXG4gICAgICAgICAgICBuYW1lOiBlbC5jbG9zZXN0KCdbd2lyZVxcXFw6c29ydGFibGUtZ3JvdXBdJykuZ2V0QXR0cmlidXRlKCd3aXJlOnNvcnRhYmxlLWdyb3VwJyksXG4gICAgICAgIH0sXG4gICAgICAgIG9uU29ydDogKGV2dCkgPT4ge1xuICAgICAgICAgICAgaWYgKGV2dC50byAhPT0gZXZ0LmZyb20gJiYgZWwgPT09IGV2dC5mcm9tKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGxldCBtYXN0ZXJFbCA9IGVsLmNsb3Nlc3QoJ1t3aXJlXFxcXDpzb3J0YWJsZS1ncm91cF0nKVxuXG4gICAgICAgICAgICBsZXQgZ3JvdXBzID0gQXJyYXkuZnJvbShtYXN0ZXJFbC5xdWVyeVNlbGVjdG9yQWxsKCdbd2lyZVxcXFw6c29ydGFibGUtZ3JvdXBcXFxcLml0ZW0tZ3JvdXBdJykpLm1hcChcbiAgICAgICAgICAgICAgICAoZWwsIGluZGV4KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIG1vdmVFbmRNb3JwaE1hcmtlcihlbClcblxuICAgICAgICAgICAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXI6IGluZGV4ICsgMSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiBlbC5nZXRBdHRyaWJ1dGUoJ3dpcmU6c29ydGFibGUtZ3JvdXAuaXRlbS1ncm91cCcpLFxuICAgICAgICAgICAgICAgICAgICAgICAgaXRlbXM6IGVsLmxpdmV3aXJlX3NvcnRhYmxlLnRvQXJyYXkoKS5tYXAoKHZhbHVlLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9yZGVyOiBpbmRleCArIDEsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlOiB2YWx1ZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICB9KSxcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICApXG5cbiAgICAgICAgICAgIG1hc3RlckVsLmNsb3Nlc3QoJ1t3aXJlXFxcXDppZF0nKS5fX2xpdmV3aXJlLiR3aXJlLmNhbGwobWFzdGVyRWwuZ2V0QXR0cmlidXRlKCd3aXJlOnNvcnRhYmxlLWdyb3VwJyksIGdyb3VwcylcbiAgICAgICAgfSxcbiAgICB9KVxufSlcbiIsICJpbXBvcnQgU2xpZGVPdmVyUGFuZWwgZnJvbSAnLi9jb21wb25lbnRzL3BhbmVsJ1xuaW1wb3J0IFNlbGVjdFRyZWUgZnJvbSAnLi9jb21wb25lbnRzL3NlbGVjdC10cmVlJ1xuaW1wb3J0ICcuL2NvbXBvbmVudHMvc29ydGFibGUnXG5cbndpbmRvdy5TbGlkZU92ZXJQYW5lbCA9IFNsaWRlT3ZlclBhbmVsXG53aW5kb3cuc2VsZWN0VHJlZSA9IFNlbGVjdFRyZWVcblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignYWxwaW5lOmluaXQnLCAoKSA9PiB7XG4gICAgY29uc3QgdGhlbWUgPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSgndGhlbWUnKSA/PyAnc3lzdGVtJ1xuXG4gICAgd2luZG93LkFscGluZS5zdG9yZShcbiAgICAgICAgJ3RoZW1lJyxcbiAgICAgICAgdGhlbWUgPT09ICdkYXJrJyB8fCAodGhlbWUgPT09ICdzeXN0ZW0nICYmIHdpbmRvdy5tYXRjaE1lZGlhKCcocHJlZmVycy1jb2xvci1zY2hlbWU6IGRhcmspJykubWF0Y2hlcylcbiAgICAgICAgICAgID8gJ2RhcmsnXG4gICAgICAgICAgICA6ICdsaWdodCcsXG4gICAgKVxuXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3RoZW1lLWNoYW5nZWQnLCAoZXZlbnQpID0+IHtcbiAgICAgICAgbGV0IHRoZW1lID0gZXZlbnQuZGV0YWlsXG5cbiAgICAgICAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oJ3RoZW1lJywgdGhlbWUpXG5cbiAgICAgICAgaWYgKHRoZW1lID09PSAnc3lzdGVtJykge1xuICAgICAgICAgICAgdGhlbWUgPSB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLm1hdGNoZXMgPyAnZGFyaycgOiAnbGlnaHQnXG4gICAgICAgIH1cblxuICAgICAgICB3aW5kb3cuQWxwaW5lLnN0b3JlKCd0aGVtZScsIHRoZW1lKVxuICAgIH0pXG5cbiAgICB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIChldmVudCkgPT4ge1xuICAgICAgICBpZiAobG9jYWxTdG9yYWdlLmdldEl0ZW0oJ3RoZW1lJykgPT09ICdzeXN0ZW0nKSB7XG4gICAgICAgICAgICB3aW5kb3cuQWxwaW5lLnN0b3JlKCd0aGVtZScsIGV2ZW50Lm1hdGNoZXMgPyAnZGFyaycgOiAnbGlnaHQnKVxuICAgICAgICB9XG4gICAgfSlcblxuICAgIHdpbmRvdy5BbHBpbmUuZWZmZWN0KCgpID0+IHtcbiAgICAgICAgY29uc3QgdGhlbWUgPSB3aW5kb3cuQWxwaW5lLnN0b3JlKCd0aGVtZScpXG5cbiAgICAgICAgdGhlbWUgPT09ICdkYXJrJ1xuICAgICAgICAgICAgPyBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZCgnZGFyaycpXG4gICAgICAgICAgICA6IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCdkYXJrJylcbiAgICB9KVxufSlcbiJdLAogICJtYXBwaW5ncyI6ICI7O0FBQUEsTUFBTSxpQkFBaUIsTUFBTTtBQUN6QixXQUFPO0FBQUEsTUFDSCxNQUFNO0FBQUEsTUFDTixxQkFBcUI7QUFBQSxNQUNyQixpQkFBaUI7QUFBQSxNQUNqQixrQkFBa0IsQ0FBQztBQUFBLE1BQ25CLFlBQVk7QUFBQSxNQUNaLFdBQVcsQ0FBQztBQUFBLE1BQ1osaUNBQWlDLEtBQUs7QUFDbEMsWUFBSSxLQUFLLE1BQU0sSUFBSSxZQUFZLEVBQUUsS0FBSyxlQUFlLE1BQU0sUUFBVztBQUNsRSxpQkFBTyxLQUFLLE1BQU0sSUFBSSxZQUFZLEVBQUUsS0FBSyxlQUFlLEVBQUUsaUJBQWlCLEVBQUUsR0FBRztBQUFBLFFBQ3BGO0FBQUEsTUFDSjtBQUFBLE1BQ0EsbUJBQW1CLFNBQVM7QUFDeEIsWUFBSSxLQUFLLGlDQUFpQyxlQUFlLE1BQU0sT0FBTztBQUNsRTtBQUFBLFFBQ0o7QUFFQSxZQUFJLFFB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QSxXQUFVLFVBQVU7QUFDcEIsUUFBQUEsU0FBUSxPQUFPLFdBQVcsOEJBQThCLEVBQUUsVUFBVSxTQUFTO0FBQUEsTUFDakY7QUFFQSxhQUFPLE9BQU8sTUFBTSxTQUFTQSxNQUFLO0FBQUEsSUFDdEMsQ0FBQztBQUVELFdBQU8sV0FBVyw4QkFBOEIsRUFBRSxpQkFBaUIsVUFBVSxDQUFDLFVBQVU7QUFDcEYsVUFBSSxhQUFhLFFBQVEsT0FBTyxNQUFNLFVBQVU7QUFDNUMsZUFBTyxPQUFPLE1BQU0sU0FBUyxNQUFNLFVBQVUsU0FBUyxPQUFPO0FBQUEsTUFDakU7QUFBQSxJQUNKLENBQUM7QUFFRCxXQUFPLE9BQU8sT0FBTyxNQUFNO0FBQ3ZCLFlBQU1BLFNBQVEsT0FBTyxPQUFPLE1BQU0sT0FBTztBQUV6QyxNQUFBQSxXQUFVLFNBQ0osU0FBUyxnQkFBZ0IsVUFBVSxJQUFJLE1BQU0sSUFDN0MsU0FBUyxnQkFBZ0IsVUFBVSxPQUFPLE1BQU07QUFBQSxJQUMxRCxDQUFDO0FBQUEsRUFDTCxDQUFDOyIsCiAgIm5hbWVzIjogWyJpZCIsICJvYmoiLCAiXyIsICJuIiwgImluZGV4IiwgIm1zIiwgImdob3N0RWwiLCAib3B0aW9uIiwgInAiLCAiZGVmYXVsdHMiLCAicm9vdEVsIiwgImNsb25lRWwiLCAib2xkSW5kZXgiLCAibmV3SW5kZXgiLCAib2xkRHJhZ2dhYmxlSW5kZXgiLCAibmV3RHJhZ2dhYmxlSW5kZXgiLCAicHV0U29ydGFibGUiLCAicGx1Z2luRXZlbnQiLCAiX2RldGVjdERpcmVjdGlvbiIsICJfZHJhZ0VsSW5Sb3dDb2x1bW4iLCAiX2RldGVjdE5lYXJlc3RFbXB0eVNvcnRhYmxlIiwgIl9wcmVwYXJlR3JvdXAiLCAiZHJhZ0VsIiwgIl9oaWRlR2hvc3RGb3JUYXJnZXQiLCAiX3VuaGlkZUdob3N0Rm9yVGFyZ2V0IiwgIm5lYXJlc3RFbXB0eUluc2VydERldGVjdEV2ZW50IiwgIl9jaGVja091dHNpZGVUYXJnZXRFbCIsICJkcmFnU3RhcnRGbiIsICJ0YXJnZXQiLCAiYWZ0ZXIiLCAibiIsICJlbCIsICJpcyIsICJwbHVnaW5zIiwgImRyb3AiLCAiYXV0b1Njcm9sbCIsICJvblNwaWxsIiwgImluZGV4IiwgImNvbXBvbmVudCIsICJlbCIsICJ0aGVtZSJdCn0K
