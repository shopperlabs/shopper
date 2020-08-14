/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/alpinejs/dist/alpine.js":
/*!**********************************************!*\
  !*** ./node_modules/alpinejs/dist/alpine.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

(function (global, factory) {
   true ? module.exports = factory() :
  undefined;
}(this, (function () { 'use strict';

  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }

    return obj;
  }

  function ownKeys(object, enumerableOnly) {
    var keys = Object.keys(object);

    if (Object.getOwnPropertySymbols) {
      var symbols = Object.getOwnPropertySymbols(object);
      if (enumerableOnly) symbols = symbols.filter(function (sym) {
        return Object.getOwnPropertyDescriptor(object, sym).enumerable;
      });
      keys.push.apply(keys, symbols);
    }

    return keys;
  }

  function _objectSpread2(target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i] != null ? arguments[i] : {};

      if (i % 2) {
        ownKeys(Object(source), true).forEach(function (key) {
          _defineProperty(target, key, source[key]);
        });
      } else if (Object.getOwnPropertyDescriptors) {
        Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
      } else {
        ownKeys(Object(source)).forEach(function (key) {
          Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
        });
      }
    }

    return target;
  }

  // Thanks @stimulus:
  // https://github.com/stimulusjs/stimulus/blob/master/packages/%40stimulus/core/src/application.ts
  function domReady() {
    return new Promise(resolve => {
      if (document.readyState == "loading") {
        document.addEventListener("DOMContentLoaded", resolve);
      } else {
        resolve();
      }
    });
  }
  function arrayUnique(array) {
    return Array.from(new Set(array));
  }
  function isTesting() {
    return navigator.userAgent.includes("Node.js") || navigator.userAgent.includes("jsdom");
  }
  function kebabCase(subject) {
    return subject.replace(/([a-z])([A-Z])/g, '$1-$2').replace(/[_\s]/, '-').toLowerCase();
  }
  function walk(el, callback) {
    if (callback(el) === false) return;
    let node = el.firstElementChild;

    while (node) {
      walk(node, callback);
      node = node.nextElementSibling;
    }
  }
  function debounce(func, wait) {
    var timeout;
    return function () {
      var context = this,
          args = arguments;

      var later = function later() {
        timeout = null;
        func.apply(context, args);
      };

      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  function saferEval(expression, dataContext, additionalHelperVariables = {}) {
    return new Function(['$data', ...Object.keys(additionalHelperVariables)], `var result; with($data) { result = ${expression} }; return result`)(dataContext, ...Object.values(additionalHelperVariables));
  }
  function saferEvalNoReturn(expression, dataContext, additionalHelperVariables = {}) {
    // For the cases when users pass only a function reference to the caller: `x-on:click="foo"`
    // Where "foo" is a function. Also, we'll pass the function the event instance when we call it.
    if (Object.keys(dataContext).includes(expression)) {
      let methodReference = new Function(['dataContext', ...Object.keys(additionalHelperVariables)], `with(dataContext) { return ${expression} }`)(dataContext, ...Object.values(additionalHelperVariables));

      if (typeof methodReference === 'function') {
        return methodReference.call(dataContext, additionalHelperVariables['$event']);
      }
    }

    return new Function(['dataContext', ...Object.keys(additionalHelperVariables)], `with(dataContext) { ${expression} }`)(dataContext, ...Object.values(additionalHelperVariables));
  }
  const xAttrRE = /^x-(on|bind|data|text|html|model|if|for|show|cloak|transition|ref)\b/;
  function isXAttr(attr) {
    const name = replaceAtAndColonWithStandardSyntax(attr.name);
    return xAttrRE.test(name);
  }
  function getXAttrs(el, type) {
    return Array.from(el.attributes).filter(isXAttr).map(attr => {
      const name = replaceAtAndColonWithStandardSyntax(attr.name);
      const typeMatch = name.match(xAttrRE);
      const valueMatch = name.match(/:([a-zA-Z\-:]+)/);
      const modifiers = name.match(/\.[^.\]]+(?=[^\]]*$)/g) || [];
      return {
        type: typeMatch ? typeMatch[1] : null,
        value: valueMatch ? valueMatch[1] : null,
        modifiers: modifiers.map(i => i.replace('.', '')),
        expression: attr.value
      };
    }).filter(i => {
      // If no type is passed in for filtering, bypass filter
      if (!type) return true;
      return i.type === type;
    });
  }
  function isBooleanAttr(attrName) {
    // As per HTML spec table https://html.spec.whatwg.org/multipage/indices.html#attributes-3:boolean-attribute
    // Array roughly ordered by estimated usage
    const booleanAttributes = ['disabled', 'checked', 'required', 'readonly', 'hidden', 'open', 'selected', 'autofocus', 'itemscope', 'multiple', 'novalidate', 'allowfullscreen', 'allowpaymentrequest', 'formnovalidate', 'autoplay', 'controls', 'loop', 'muted', 'playsinline', 'default', 'ismap', 'reversed', 'async', 'defer', 'nomodule'];
    return booleanAttributes.includes(attrName);
  }
  function replaceAtAndColonWithStandardSyntax(name) {
    if (name.startsWith('@')) {
      return name.replace('@', 'x-on:');
    } else if (name.startsWith(':')) {
      return name.replace(':', 'x-bind:');
    }

    return name;
  }
  function transitionIn(el, show, forceSkip = false) {
    // We don't want to transition on the initial page load.
    if (forceSkip) return show();
    const attrs = getXAttrs(el, 'transition');
    const showAttr = getXAttrs(el, 'show')[0]; // If this is triggered by a x-show.transition.

    if (showAttr && showAttr.modifiers.includes('transition')) {
      let modifiers = showAttr.modifiers; // If x-show.transition.out, we'll skip the "in" transition.

      if (modifiers.includes('out') && !modifiers.includes('in')) return show();
      const settingBothSidesOfTransition = modifiers.includes('in') && modifiers.includes('out'); // If x-show.transition.in...out... only use "in" related modifiers for this transition.

      modifiers = settingBothSidesOfTransition ? modifiers.filter((i, index) => index < modifiers.indexOf('out')) : modifiers;
      transitionHelperIn(el, modifiers, show); // Otherwise, we can assume x-transition:enter.
    } else if (attrs.length > 0) {
      transitionClassesIn(el, attrs, show);
    } else {
      // If neither, just show that damn thing.
      show();
    }
  }
  function transitionOut(el, hide, forceSkip = false) {
    if (forceSkip) return hide();
    const attrs = getXAttrs(el, 'transition');
    const showAttr = getXAttrs(el, 'show')[0];

    if (showAttr && showAttr.modifiers.includes('transition')) {
      let modifiers = showAttr.modifiers;
      if (modifiers.includes('in') && !modifiers.includes('out')) return hide();
      const settingBothSidesOfTransition = modifiers.includes('in') && modifiers.includes('out');
      modifiers = settingBothSidesOfTransition ? modifiers.filter((i, index) => index > modifiers.indexOf('out')) : modifiers;
      transitionHelperOut(el, modifiers, settingBothSidesOfTransition, hide);
    } else if (attrs.length > 0) {
      transitionClassesOut(el, attrs, hide);
    } else {
      hide();
    }
  }
  function transitionHelperIn(el, modifiers, showCallback) {
    // Default values inspired by: https://material.io/design/motion/speed.html#duration
    const styleValues = {
      duration: modifierValue(modifiers, 'duration', 150),
      origin: modifierValue(modifiers, 'origin', 'center'),
      first: {
        opacity: 0,
        scale: modifierValue(modifiers, 'scale', 95)
      },
      second: {
        opacity: 1,
        scale: 100
      }
    };
    transitionHelper(el, modifiers, showCallback, () => {}, styleValues);
  }
  function transitionHelperOut(el, modifiers, settingBothSidesOfTransition, hideCallback) {
    // Make the "out" transition .5x slower than the "in". (Visually better)
    // HOWEVER, if they explicitly set a duration for the "out" transition,
    // use that.
    const duration = settingBothSidesOfTransition ? modifierValue(modifiers, 'duration', 150) : modifierValue(modifiers, 'duration', 150) / 2;
    const styleValues = {
      duration: duration,
      origin: modifierValue(modifiers, 'origin', 'center'),
      first: {
        opacity: 1,
        scale: 100
      },
      second: {
        opacity: 0,
        scale: modifierValue(modifiers, 'scale', 95)
      }
    };
    transitionHelper(el, modifiers, () => {}, hideCallback, styleValues);
  }

  function modifierValue(modifiers, key, fallback) {
    // If the modifier isn't present, use the default.
    if (modifiers.indexOf(key) === -1) return fallback; // If it IS present, grab the value after it: x-show.transition.duration.500ms

    const rawValue = modifiers[modifiers.indexOf(key) + 1];
    if (!rawValue) return fallback;

    if (key === 'scale') {
      // Check if the very next value is NOT a number and return the fallback.
      // If x-show.transition.scale, we'll use the default scale value.
      // That is how a user opts out of the opacity transition.
      if (!isNumeric(rawValue)) return fallback;
    }

    if (key === 'duration') {
      // Support x-show.transition.duration.500ms && duration.500
      let match = rawValue.match(/([0-9]+)ms/);
      if (match) return match[1];
    }

    if (key === 'origin') {
      // Support chaining origin directions: x-show.transition.top.right
      if (['top', 'right', 'left', 'center', 'bottom'].includes(modifiers[modifiers.indexOf(key) + 2])) {
        return [rawValue, modifiers[modifiers.indexOf(key) + 2]].join(' ');
      }
    }

    return rawValue;
  }

  function transitionHelper(el, modifiers, hook1, hook2, styleValues) {
    // If the user set these style values, we'll put them back when we're done with them.
    const opacityCache = el.style.opacity;
    const transformCache = el.style.transform;
    const transformOriginCache = el.style.transformOrigin; // If no modifiers are present: x-show.transition, we'll default to both opacity and scale.

    const noModifiers = !modifiers.includes('opacity') && !modifiers.includes('scale');
    const transitionOpacity = noModifiers || modifiers.includes('opacity');
    const transitionScale = noModifiers || modifiers.includes('scale'); // These are the explicit stages of a transition (same stages for in and for out).
    // This way you can get a birds eye view of the hooks, and the differences
    // between them.

    const stages = {
      start() {
        if (transitionOpacity) el.style.opacity = styleValues.first.opacity;
        if (transitionScale) el.style.transform = `scale(${styleValues.first.scale / 100})`;
      },

      during() {
        if (transitionScale) el.style.transformOrigin = styleValues.origin;
        el.style.transitionProperty = [transitionOpacity ? `opacity` : ``, transitionScale ? `transform` : ``].join(' ').trim();
        el.style.transitionDuration = `${styleValues.duration / 1000}s`;
        el.style.transitionTimingFunction = `cubic-bezier(0.4, 0.0, 0.2, 1)`;
      },

      show() {
        hook1();
      },

      end() {
        if (transitionOpacity) el.style.opacity = styleValues.second.opacity;
        if (transitionScale) el.style.transform = `scale(${styleValues.second.scale / 100})`;
      },

      hide() {
        hook2();
      },

      cleanup() {
        if (transitionOpacity) el.style.opacity = opacityCache;
        if (transitionScale) el.style.transform = transformCache;
        if (transitionScale) el.style.transformOrigin = transformOriginCache;
        el.style.transitionProperty = null;
        el.style.transitionDuration = null;
        el.style.transitionTimingFunction = null;
      }

    };
    transition(el, stages);
  }
  function transitionClassesIn(el, directives, showCallback) {
    const enter = (directives.find(i => i.value === 'enter') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    const enterStart = (directives.find(i => i.value === 'enter-start') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    const enterEnd = (directives.find(i => i.value === 'enter-end') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    transitionClasses(el, enter, enterStart, enterEnd, showCallback, () => {});
  }
  function transitionClassesOut(el, directives, hideCallback) {
    const leave = (directives.find(i => i.value === 'leave') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    const leaveStart = (directives.find(i => i.value === 'leave-start') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    const leaveEnd = (directives.find(i => i.value === 'leave-end') || {
      expression: ''
    }).expression.split(' ').filter(i => i !== '');
    transitionClasses(el, leave, leaveStart, leaveEnd, () => {}, hideCallback);
  }
  function transitionClasses(el, classesDuring, classesStart, classesEnd, hook1, hook2) {
    const originalClasses = el.__x_original_classes || [];
    const stages = {
      start() {
        el.classList.add(...classesStart);
      },

      during() {
        el.classList.add(...classesDuring);
      },

      show() {
        hook1();
      },

      end() {
        // Don't remove classes that were in the original class attribute.
        el.classList.remove(...classesStart.filter(i => !originalClasses.includes(i)));
        el.classList.add(...classesEnd);
      },

      hide() {
        hook2();
      },

      cleanup() {
        el.classList.remove(...classesDuring.filter(i => !originalClasses.includes(i)));
        el.classList.remove(...classesEnd.filter(i => !originalClasses.includes(i)));
      }

    };
    transition(el, stages);
  }
  function transition(el, stages) {
    stages.start();
    stages.during();
    requestAnimationFrame(() => {
      // Note: Safari's transitionDuration property will list out comma separated transition durations
      // for every single transition property. Let's grab the first one and call it a day.
      let duration = Number(getComputedStyle(el).transitionDuration.replace(/,.*/, '').replace('s', '')) * 1000;
      stages.show();
      requestAnimationFrame(() => {
        stages.end();
        setTimeout(() => {
          stages.hide(); // Adding an "isConnected" check, in case the callback
          // removed the element from the DOM.

          if (el.isConnected) {
            stages.cleanup();
          }
        }, duration);
      });
    });
  }
  function isNumeric(subject) {
    return !isNaN(subject);
  }

  function handleForDirective(component, templateEl, expression, initialUpdate, extraVars) {
    warnIfNotTemplateTag(templateEl);
    let iteratorNames = parseForExpression(expression);
    let items = evaluateItemsAndReturnEmptyIfXIfIsPresentAndFalseOnElement(component, templateEl, iteratorNames, extraVars); // As we walk the array, we'll also walk the DOM (updating/creating as we go).

    let currentEl = templateEl;
    items.forEach((item, index) => {
      let iterationScopeVariables = getIterationScopeVariables(iteratorNames, item, index, items, extraVars());
      let currentKey = generateKeyForIteration(component, templateEl, index, iterationScopeVariables);
      let nextEl = currentEl.nextElementSibling; // If there's no previously x-for processed element ahead, add one.

      if (!nextEl || nextEl.__x_for_key === undefined) {
        nextEl = addElementInLoopAfterCurrentEl(templateEl, currentEl); // And transition it in if it's not the first page load.

        transitionIn(nextEl, () => {}, initialUpdate);
        nextEl.__x_for = iterationScopeVariables;
        component.initializeElements(nextEl, () => nextEl.__x_for);
      } else {
        nextEl = lookAheadForMatchingKeyedElementAndMoveItIfFound(nextEl, currentKey); // If we haven't found a matching key, just insert the element at the current position

        if (!nextEl) {
          nextEl = addElementInLoopAfterCurrentEl(templateEl, currentEl);
        } // Temporarily remove the key indicator to allow the normal "updateElements" to work


        delete nextEl.__x_for_key;
        nextEl.__x_for = iterationScopeVariables;
        component.updateElements(nextEl, () => nextEl.__x_for);
      }

      currentEl = nextEl;
      currentEl.__x_for_key = currentKey;
    });
    removeAnyLeftOverElementsFromPreviousUpdate(currentEl);
  } // This was taken from VueJS 2.* core. Thanks Vue!

  function parseForExpression(expression) {
    let forIteratorRE = /,([^,\}\]]*)(?:,([^,\}\]]*))?$/;
    let stripParensRE = /^\(|\)$/g;
    let forAliasRE = /([\s\S]*?)\s+(?:in|of)\s+([\s\S]*)/;
    let inMatch = expression.match(forAliasRE);
    if (!inMatch) return;
    let res = {};
    res.items = inMatch[2].trim();
    let item = inMatch[1].trim().replace(stripParensRE, '');
    let iteratorMatch = item.match(forIteratorRE);

    if (iteratorMatch) {
      res.item = item.replace(forIteratorRE, '').trim();
      res.index = iteratorMatch[1].trim();

      if (iteratorMatch[2]) {
        res.collection = iteratorMatch[2].trim();
      }
    } else {
      res.item = item;
    }

    return res;
  }

  function getIterationScopeVariables(iteratorNames, item, index, items, extraVars) {
    // We must create a new object, so each iteration has a new scope
    let scopeVariables = extraVars ? _objectSpread2({}, extraVars) : {};
    scopeVariables[iteratorNames.item] = item;
    if (iteratorNames.index) scopeVariables[iteratorNames.index] = index;
    if (iteratorNames.collection) scopeVariables[iteratorNames.collection] = items;
    return scopeVariables;
  }

  function generateKeyForIteration(component, el, index, iterationScopeVariables) {
    let bindKeyAttribute = getXAttrs(el, 'bind').filter(attr => attr.value === 'key')[0]; // If the dev hasn't specified a key, just return the index of the iteration.

    if (!bindKeyAttribute) return index;
    return component.evaluateReturnExpression(el, bindKeyAttribute.expression, () => iterationScopeVariables);
  }

  function warnIfNotTemplateTag(el) {
    if (el.tagName.toLowerCase() !== 'template') console.warn('Alpine: [x-for] directive should only be added to <template> tags.');
  }

  function evaluateItemsAndReturnEmptyIfXIfIsPresentAndFalseOnElement(component, el, iteratorNames, extraVars) {
    let ifAttribute = getXAttrs(el, 'if')[0];

    if (ifAttribute && !component.evaluateReturnExpression(el, ifAttribute.expression)) {
      return [];
    }

    return component.evaluateReturnExpression(el, iteratorNames.items, extraVars);
  }

  function addElementInLoopAfterCurrentEl(templateEl, currentEl) {
    let clone = document.importNode(templateEl.content, true);
    if (clone.childElementCount !== 1) console.warn('Alpine: <template> tag with [x-for] encountered with multiple element roots. Make sure <template> only has a single child node.');
    currentEl.parentElement.insertBefore(clone, currentEl.nextElementSibling);
    return currentEl.nextElementSibling;
  }

  function lookAheadForMatchingKeyedElementAndMoveItIfFound(nextEl, currentKey) {
    // If the the key's DO match, no need to look ahead.
    if (nextEl.__x_for_key === currentKey) return nextEl; // If they don't, we'll look ahead for a match.
    // If we find it, we'll move it to the current position in the loop.

    let tmpNextEl = nextEl;

    while (tmpNextEl) {
      if (tmpNextEl.__x_for_key === currentKey) {
        return tmpNextEl.parentElement.insertBefore(tmpNextEl, nextEl);
      }

      tmpNextEl = tmpNextEl.nextElementSibling && tmpNextEl.nextElementSibling.__x_for_key !== undefined ? tmpNextEl.nextElementSibling : false;
    }
  }

  function removeAnyLeftOverElementsFromPreviousUpdate(currentEl) {
    var nextElementFromOldLoop = currentEl.nextElementSibling && currentEl.nextElementSibling.__x_for_key !== undefined ? currentEl.nextElementSibling : false;

    while (nextElementFromOldLoop) {
      let nextElementFromOldLoopImmutable = nextElementFromOldLoop;
      let nextSibling = nextElementFromOldLoop.nextElementSibling;
      transitionOut(nextElementFromOldLoop, () => {
        nextElementFromOldLoopImmutable.remove();
      });
      nextElementFromOldLoop = nextSibling && nextSibling.__x_for_key !== undefined ? nextSibling : false;
    }
  }

  function handleAttributeBindingDirective(component, el, attrName, expression, extraVars, attrType) {
    var value = component.evaluateReturnExpression(el, expression, extraVars);

    if (attrName === 'value') {
      // If nested model key is undefined, set the default value to empty string.
      if (value === undefined && expression.match(/\./).length) {
        value = '';
      }

      if (el.type === 'radio') {
        // Set radio value from x-bind:value, if no "value" attribute exists.
        // If there are any initial state values, radio will have a correct
        // "checked" value since x-bind:value is processed before x-model.
        if (el.attributes.value === undefined && attrType === 'bind') {
          el.value = value;
        } else if (attrType !== 'bind') {
          el.checked = el.value == value;
        }
      } else if (el.type === 'checkbox') {
        if (Array.isArray(value)) {
          // I'm purposely not using Array.includes here because it's
          // strict, and because of Numeric/String mis-casting, I
          // want the "includes" to be "fuzzy".
          let valueFound = false;
          value.forEach(val => {
            if (val == el.value) {
              valueFound = true;
            }
          });
          el.checked = valueFound;
        } else {
          el.checked = !!value;
        } // If we are explicitly binding a string to the :value, set the string,
        // If the value is a boolean, leave it alone, it will be set to "on"
        // automatically.


        if (typeof value === 'string') {
          el.value = value;
        }
      } else if (el.tagName === 'SELECT') {
        updateSelect(el, value);
      } else {
        // Cursor position should be restored back to origin due to a safari bug
        const cursorPosition = el.selectionStart;
        el.value = value;

        if (el === document.activeElement) {
          el.setSelectionRange(cursorPosition, cursorPosition);
        }
      }
    } else if (attrName === 'class') {
      if (Array.isArray(value)) {
        const originalClasses = el.__x_original_classes || [];
        el.setAttribute('class', arrayUnique(originalClasses.concat(value)).join(' '));
      } else if (typeof value === 'object') {
        // Sorting the keys / class names by their boolean value will ensure that
        // anything that evaluates to `false` and needs to remove classes is run first.
        const keysSortedByBooleanValue = Object.keys(value).sort((a, b) => value[a] - value[b]);
        keysSortedByBooleanValue.forEach(classNames => {
          if (value[classNames]) {
            classNames.split(' ').forEach(className => el.classList.add(className));
          } else {
            classNames.split(' ').forEach(className => el.classList.remove(className));
          }
        });
      } else {
        const originalClasses = el.__x_original_classes || [];
        const newClasses = value.split(' ');
        el.setAttribute('class', arrayUnique(originalClasses.concat(newClasses)).join(' '));
      }
    } else if (isBooleanAttr(attrName)) {
      // Boolean attributes have to be explicitly added and removed, not just set.
      if (!!value) {
        el.setAttribute(attrName, '');
      } else {
        el.removeAttribute(attrName);
      }
    } else {
      el.setAttribute(attrName, value);
    }
  }

  function updateSelect(el, value) {
    const arrayWrappedValue = [].concat(value).map(value => {
      return value + '';
    });
    Array.from(el.options).forEach(option => {
      option.selected = arrayWrappedValue.includes(option.value || option.text);
    });
  }

  function handleTextDirective(el, output, expression) {
    // If nested model key is undefined, set the default value to empty string.
    if (output === undefined && expression.match(/\./).length) {
      output = '';
    }

    el.innerText = output;
  }

  function handleHtmlDirective(component, el, expression, extraVars) {
    el.innerHTML = component.evaluateReturnExpression(el, expression, extraVars);
  }

  function handleShowDirective(component, el, value, modifiers, initialUpdate = false) {
    const hide = () => {
      el.style.display = 'none';
    };

    const show = () => {
      if (el.style.length === 1 && el.style.display === 'none') {
        el.removeAttribute('style');
      } else {
        el.style.removeProperty('display');
      }
    };

    if (initialUpdate === true) {
      if (value) {
        show();
      } else {
        hide();
      }

      return;
    }

    const handle = resolve => {
      if (!value) {
        if (el.style.display !== 'none') {
          transitionOut(el, () => {
            resolve(() => {
              hide();
            });
          });
        } else {
          resolve(() => {});
        }
      } else {
        if (el.style.display !== '') {
          transitionIn(el, () => {
            show();
          });
        } // Resolve immediately, only hold up parent `x-show`s for hidin.


        resolve(() => {});
      }
    }; // The working of x-show is a bit complex because we need to
    // wait for any child transitions to finish before hiding
    // some element. Also, this has to be done recursively.
    // If x-show.immediate, foregoe the waiting.


    if (modifiers.includes('immediate')) {
      handle(finish => finish());
      return;
    } // x-show is encountered during a DOM tree walk. If an element
    // we encounter is NOT a child of another x-show element we
    // can execute the previous x-show stack (if one exists).


    if (component.showDirectiveLastElement && !component.showDirectiveLastElement.contains(el)) {
      component.executeAndClearRemainingShowDirectiveStack();
    } // We'll push the handler onto a stack to be handled later.


    component.showDirectiveStack.push(handle);
    component.showDirectiveLastElement = el;
  }

  function handleIfDirective(component, el, expressionResult, initialUpdate, extraVars) {
    if (el.nodeName.toLowerCase() !== 'template') console.warn(`Alpine: [x-if] directive should only be added to <template> tags. See https://github.com/alpinejs/alpine#x-if`);
    const elementHasAlreadyBeenAdded = el.nextElementSibling && el.nextElementSibling.__x_inserted_me === true;

    if (expressionResult && !elementHasAlreadyBeenAdded) {
      const clone = document.importNode(el.content, true);
      el.parentElement.insertBefore(clone, el.nextElementSibling);
      transitionIn(el.nextElementSibling, () => {}, initialUpdate);
      component.initializeElements(el.nextElementSibling, extraVars);
      el.nextElementSibling.__x_inserted_me = true;
    } else if (!expressionResult && elementHasAlreadyBeenAdded) {
      transitionOut(el.nextElementSibling, () => {
        el.nextElementSibling.remove();
      }, initialUpdate);
    }
  }

  function registerListener(component, el, event, modifiers, expression, extraVars = {}) {
    if (modifiers.includes('away')) {
      let handler = e => {
        // Don't do anything if the click came form the element or within it.
        if (el.contains(e.target)) return; // Don't do anything if this element isn't currently visible.

        if (el.offsetWidth < 1 && el.offsetHeight < 1) return; // Now that we are sure the element is visible, AND the click
        // is from outside it, let's run the expression.

        runListenerHandler(component, expression, e, extraVars);

        if (modifiers.includes('once')) {
          document.removeEventListener(event, handler);
        }
      }; // Listen for this event at the root level.


      document.addEventListener(event, handler);
    } else {
      let listenerTarget = modifiers.includes('window') ? window : modifiers.includes('document') ? document : el;

      let handler = e => {
        // Remove this global event handler if the element that declared it
        // has been removed. It's now stale.
        if (listenerTarget === window || listenerTarget === document) {
          if (!document.body.contains(el)) {
            listenerTarget.removeEventListener(event, handler);
            return;
          }
        }

        if (isKeyEvent(event)) {
          if (isListeningForASpecificKeyThatHasntBeenPressed(e, modifiers)) {
            return;
          }
        }

        if (modifiers.includes('prevent')) e.preventDefault();
        if (modifiers.includes('stop')) e.stopPropagation(); // If the .self modifier isn't present, or if it is present and
        // the target element matches the element we are registering the
        // event on, run the handler

        if (!modifiers.includes('self') || e.target === el) {
          const returnValue = runListenerHandler(component, expression, e, extraVars);

          if (returnValue === false) {
            e.preventDefault();
          } else {
            if (modifiers.includes('once')) {
              listenerTarget.removeEventListener(event, handler);
            }
          }
        }
      };

      if (modifiers.includes('debounce')) {
        let nextModifier = modifiers[modifiers.indexOf('debounce') + 1] || 'invalid-wait';
        let wait = isNumeric(nextModifier.split('ms')[0]) ? Number(nextModifier.split('ms')[0]) : 250;
        handler = debounce(handler, wait);
      }

      listenerTarget.addEventListener(event, handler);
    }
  }

  function runListenerHandler(component, expression, e, extraVars) {
    return component.evaluateCommandExpression(e.target, expression, () => {
      return _objectSpread2({}, extraVars(), {
        '$event': e
      });
    });
  }

  function isKeyEvent(event) {
    return ['keydown', 'keyup'].includes(event);
  }

  function isListeningForASpecificKeyThatHasntBeenPressed(e, modifiers) {
    let keyModifiers = modifiers.filter(i => {
      return !['window', 'document', 'prevent', 'stop'].includes(i);
    });

    if (keyModifiers.includes('debounce')) {
      let debounceIndex = keyModifiers.indexOf('debounce');
      keyModifiers.splice(debounceIndex, isNumeric((keyModifiers[debounceIndex + 1] || 'invalid-wait').split('ms')[0]) ? 2 : 1);
    } // If no modifier is specified, we'll call it a press.


    if (keyModifiers.length === 0) return false; // If one is passed, AND it matches the key pressed, we'll call it a press.

    if (keyModifiers.length === 1 && keyModifiers[0] === keyToModifier(e.key)) return false; // The user is listening for key combinations.

    const systemKeyModifiers = ['ctrl', 'shift', 'alt', 'meta', 'cmd', 'super'];
    const selectedSystemKeyModifiers = systemKeyModifiers.filter(modifier => keyModifiers.includes(modifier));
    keyModifiers = keyModifiers.filter(i => !selectedSystemKeyModifiers.includes(i));

    if (selectedSystemKeyModifiers.length > 0) {
      const activelyPressedKeyModifiers = selectedSystemKeyModifiers.filter(modifier => {
        // Alias "cmd" and "super" to "meta"
        if (modifier === 'cmd' || modifier === 'super') modifier = 'meta';
        return e[`${modifier}Key`];
      }); // If all the modifiers selected are pressed, ...

      if (activelyPressedKeyModifiers.length === selectedSystemKeyModifiers.length) {
        // AND the remaining key is pressed as well. It's a press.
        if (keyModifiers[0] === keyToModifier(e.key)) return false;
      }
    } // We'll call it NOT a valid keypress.


    return true;
  }

  function keyToModifier(key) {
    switch (key) {
      case '/':
        return 'slash';

      case ' ':
      case 'Spacebar':
        return 'space';

      default:
        return key && kebabCase(key);
    }
  }

  function registerModelListener(component, el, modifiers, expression, extraVars) {
    // If the element we are binding to is a select, a radio, or checkbox
    // we'll listen for the change event instead of the "input" event.
    var event = el.tagName.toLowerCase() === 'select' || ['checkbox', 'radio'].includes(el.type) || modifiers.includes('lazy') ? 'change' : 'input';
    const listenerExpression = `${expression} = rightSideOfExpression($event, ${expression})`;
    registerListener(component, el, event, modifiers, listenerExpression, () => {
      return _objectSpread2({}, extraVars(), {
        rightSideOfExpression: generateModelAssignmentFunction(el, modifiers, expression)
      });
    });
  }

  function generateModelAssignmentFunction(el, modifiers, expression) {
    if (el.type === 'radio') {
      // Radio buttons only work properly when they share a name attribute.
      // People might assume we take care of that for them, because
      // they already set a shared "x-model" attribute.
      if (!el.hasAttribute('name')) el.setAttribute('name', expression);
    }

    return (event, currentValue) => {
      // Check for event.detail due to an issue where IE11 handles other events as a CustomEvent.
      if (event instanceof CustomEvent && event.detail) {
        return event.detail;
      } else if (el.type === 'checkbox') {
        // If the data we are binding to is an array, toggle it's value inside the array.
        if (Array.isArray(currentValue)) {
          return event.target.checked ? currentValue.concat([event.target.value]) : currentValue.filter(i => i !== event.target.value);
        } else {
          return event.target.checked;
        }
      } else if (el.tagName.toLowerCase() === 'select' && el.multiple) {
        return modifiers.includes('number') ? Array.from(event.target.selectedOptions).map(option => {
          const rawValue = option.value || option.text;
          const number = rawValue ? parseFloat(rawValue) : null;
          return isNaN(number) ? rawValue : number;
        }) : Array.from(event.target.selectedOptions).map(option => {
          return option.value || option.text;
        });
      } else {
        const rawValue = event.target.value;
        const number = rawValue ? parseFloat(rawValue) : null;
        return modifiers.includes('number') ? isNaN(number) ? rawValue : number : modifiers.includes('trim') ? rawValue.trim() : rawValue;
      }
    };
  }

  /**
   * Copyright (C) 2017 salesforce.com, inc.
   */
  const { isArray } = Array;
  const { getPrototypeOf, create: ObjectCreate, defineProperty: ObjectDefineProperty, defineProperties: ObjectDefineProperties, isExtensible, getOwnPropertyDescriptor, getOwnPropertyNames, getOwnPropertySymbols, preventExtensions, hasOwnProperty, } = Object;
  const { push: ArrayPush, concat: ArrayConcat, map: ArrayMap, } = Array.prototype;
  function isUndefined(obj) {
      return obj === undefined;
  }
  function isFunction(obj) {
      return typeof obj === 'function';
  }
  function isObject(obj) {
      return typeof obj === 'object';
  }
  const proxyToValueMap = new WeakMap();
  function registerProxy(proxy, value) {
      proxyToValueMap.set(proxy, value);
  }
  const unwrap = (replicaOrAny) => proxyToValueMap.get(replicaOrAny) || replicaOrAny;

  function wrapValue(membrane, value) {
      return membrane.valueIsObservable(value) ? membrane.getProxy(value) : value;
  }
  /**
   * Unwrap property descriptors will set value on original descriptor
   * We only need to unwrap if value is specified
   * @param descriptor external descrpitor provided to define new property on original value
   */
  function unwrapDescriptor(descriptor) {
      if (hasOwnProperty.call(descriptor, 'value')) {
          descriptor.value = unwrap(descriptor.value);
      }
      return descriptor;
  }
  function lockShadowTarget(membrane, shadowTarget, originalTarget) {
      const targetKeys = ArrayConcat.call(getOwnPropertyNames(originalTarget), getOwnPropertySymbols(originalTarget));
      targetKeys.forEach((key) => {
          let descriptor = getOwnPropertyDescriptor(originalTarget, key);
          // We do not need to wrap the descriptor if configurable
          // Because we can deal with wrapping it when user goes through
          // Get own property descriptor. There is also a chance that this descriptor
          // could change sometime in the future, so we can defer wrapping
          // until we need to
          if (!descriptor.configurable) {
              descriptor = wrapDescriptor(membrane, descriptor, wrapValue);
          }
          ObjectDefineProperty(shadowTarget, key, descriptor);
      });
      preventExtensions(shadowTarget);
  }
  class ReactiveProxyHandler {
      constructor(membrane, value) {
          this.originalTarget = value;
          this.membrane = membrane;
      }
      get(shadowTarget, key) {
          const { originalTarget, membrane } = this;
          const value = originalTarget[key];
          const { valueObserved } = membrane;
          valueObserved(originalTarget, key);
          return membrane.getProxy(value);
      }
      set(shadowTarget, key, value) {
          const { originalTarget, membrane: { valueMutated } } = this;
          const oldValue = originalTarget[key];
          if (oldValue !== value) {
              originalTarget[key] = value;
              valueMutated(originalTarget, key);
          }
          else if (key === 'length' && isArray(originalTarget)) {
              // fix for issue #236: push will add the new index, and by the time length
              // is updated, the internal length is already equal to the new length value
              // therefore, the oldValue is equal to the value. This is the forking logic
              // to support this use case.
              valueMutated(originalTarget, key);
          }
          return true;
      }
      deleteProperty(shadowTarget, key) {
          const { originalTarget, membrane: { valueMutated } } = this;
          delete originalTarget[key];
          valueMutated(originalTarget, key);
          return true;
      }
      apply(shadowTarget, thisArg, argArray) {
          /* No op */
      }
      construct(target, argArray, newTarget) {
          /* No op */
      }
      has(shadowTarget, key) {
          const { originalTarget, membrane: { valueObserved } } = this;
          valueObserved(originalTarget, key);
          return key in originalTarget;
      }
      ownKeys(shadowTarget) {
          const { originalTarget } = this;
          return ArrayConcat.call(getOwnPropertyNames(originalTarget), getOwnPropertySymbols(originalTarget));
      }
      isExtensible(shadowTarget) {
          const shadowIsExtensible = isExtensible(shadowTarget);
          if (!shadowIsExtensible) {
              return shadowIsExtensible;
          }
          const { originalTarget, membrane } = this;
          const targetIsExtensible = isExtensible(originalTarget);
          if (!targetIsExtensible) {
              lockShadowTarget(membrane, shadowTarget, originalTarget);
          }
          return targetIsExtensible;
      }
      setPrototypeOf(shadowTarget, prototype) {
      }
      getPrototypeOf(shadowTarget) {
          const { originalTarget } = this;
          return getPrototypeOf(originalTarget);
      }
      getOwnPropertyDescriptor(shadowTarget, key) {
          const { originalTarget, membrane } = this;
          const { valueObserved } = this.membrane;
          // keys looked up via hasOwnProperty need to be reactive
          valueObserved(originalTarget, key);
          let desc = getOwnPropertyDescriptor(originalTarget, key);
          if (isUndefined(desc)) {
              return desc;
          }
          const shadowDescriptor = getOwnPropertyDescriptor(shadowTarget, key);
          if (!isUndefined(shadowDescriptor)) {
              return shadowDescriptor;
          }
          // Note: by accessing the descriptor, the key is marked as observed
          // but access to the value, setter or getter (if available) cannot observe
          // mutations, just like regular methods, in which case we just do nothing.
          desc = wrapDescriptor(membrane, desc, wrapValue);
          if (!desc.configurable) {
              // If descriptor from original target is not configurable,
              // We must copy the wrapped descriptor over to the shadow target.
              // Otherwise, proxy will throw an invariant error.
              // This is our last chance to lock the value.
              // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Proxy/handler/getOwnPropertyDescriptor#Invariants
              ObjectDefineProperty(shadowTarget, key, desc);
          }
          return desc;
      }
      preventExtensions(shadowTarget) {
          const { originalTarget, membrane } = this;
          lockShadowTarget(membrane, shadowTarget, originalTarget);
          preventExtensions(originalTarget);
          return true;
      }
      defineProperty(shadowTarget, key, descriptor) {
          const { originalTarget, membrane } = this;
          const { valueMutated } = membrane;
          const { configurable } = descriptor;
          // We have to check for value in descriptor
          // because Object.freeze(proxy) calls this method
          // with only { configurable: false, writeable: false }
          // Additionally, method will only be called with writeable:false
          // if the descriptor has a value, as opposed to getter/setter
          // So we can just check if writable is present and then see if
          // value is present. This eliminates getter and setter descriptors
          if (hasOwnProperty.call(descriptor, 'writable') && !hasOwnProperty.call(descriptor, 'value')) {
              const originalDescriptor = getOwnPropertyDescriptor(originalTarget, key);
              descriptor.value = originalDescriptor.value;
          }
          ObjectDefineProperty(originalTarget, key, unwrapDescriptor(descriptor));
          if (configurable === false) {
              ObjectDefineProperty(shadowTarget, key, wrapDescriptor(membrane, descriptor, wrapValue));
          }
          valueMutated(originalTarget, key);
          return true;
      }
  }

  function wrapReadOnlyValue(membrane, value) {
      return membrane.valueIsObservable(value) ? membrane.getReadOnlyProxy(value) : value;
  }
  class ReadOnlyHandler {
      constructor(membrane, value) {
          this.originalTarget = value;
          this.membrane = membrane;
      }
      get(shadowTarget, key) {
          const { membrane, originalTarget } = this;
          const value = originalTarget[key];
          const { valueObserved } = membrane;
          valueObserved(originalTarget, key);
          return membrane.getReadOnlyProxy(value);
      }
      set(shadowTarget, key, value) {
          return false;
      }
      deleteProperty(shadowTarget, key) {
          return false;
      }
      apply(shadowTarget, thisArg, argArray) {
          /* No op */
      }
      construct(target, argArray, newTarget) {
          /* No op */
      }
      has(shadowTarget, key) {
          const { originalTarget, membrane: { valueObserved } } = this;
          valueObserved(originalTarget, key);
          return key in originalTarget;
      }
      ownKeys(shadowTarget) {
          const { originalTarget } = this;
          return ArrayConcat.call(getOwnPropertyNames(originalTarget), getOwnPropertySymbols(originalTarget));
      }
      setPrototypeOf(shadowTarget, prototype) {
      }
      getOwnPropertyDescriptor(shadowTarget, key) {
          const { originalTarget, membrane } = this;
          const { valueObserved } = membrane;
          // keys looked up via hasOwnProperty need to be reactive
          valueObserved(originalTarget, key);
          let desc = getOwnPropertyDescriptor(originalTarget, key);
          if (isUndefined(desc)) {
              return desc;
          }
          const shadowDescriptor = getOwnPropertyDescriptor(shadowTarget, key);
          if (!isUndefined(shadowDescriptor)) {
              return shadowDescriptor;
          }
          // Note: by accessing the descriptor, the key is marked as observed
          // but access to the value or getter (if available) cannot be observed,
          // just like regular methods, in which case we just do nothing.
          desc = wrapDescriptor(membrane, desc, wrapReadOnlyValue);
          if (hasOwnProperty.call(desc, 'set')) {
              desc.set = undefined; // readOnly membrane does not allow setters
          }
          if (!desc.configurable) {
              // If descriptor from original target is not configurable,
              // We must copy the wrapped descriptor over to the shadow target.
              // Otherwise, proxy will throw an invariant error.
              // This is our last chance to lock the value.
              // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Proxy/handler/getOwnPropertyDescriptor#Invariants
              ObjectDefineProperty(shadowTarget, key, desc);
          }
          return desc;
      }
      preventExtensions(shadowTarget) {
          return false;
      }
      defineProperty(shadowTarget, key, descriptor) {
          return false;
      }
  }
  function createShadowTarget(value) {
      let shadowTarget = undefined;
      if (isArray(value)) {
          shadowTarget = [];
      }
      else if (isObject(value)) {
          shadowTarget = {};
      }
      return shadowTarget;
  }
  const ObjectDotPrototype = Object.prototype;
  function defaultValueIsObservable(value) {
      // intentionally checking for null
      if (value === null) {
          return false;
      }
      // treat all non-object types, including undefined, as non-observable values
      if (typeof value !== 'object') {
          return false;
      }
      if (isArray(value)) {
          return true;
      }
      const proto = getPrototypeOf(value);
      return (proto === ObjectDotPrototype || proto === null || getPrototypeOf(proto) === null);
  }
  const defaultValueObserved = (obj, key) => {
      /* do nothing */
  };
  const defaultValueMutated = (obj, key) => {
      /* do nothing */
  };
  const defaultValueDistortion = (value) => value;
  function wrapDescriptor(membrane, descriptor, getValue) {
      const { set, get } = descriptor;
      if (hasOwnProperty.call(descriptor, 'value')) {
          descriptor.value = getValue(membrane, descriptor.value);
      }
      else {
          if (!isUndefined(get)) {
              descriptor.get = function () {
                  // invoking the original getter with the original target
                  return getValue(membrane, get.call(unwrap(this)));
              };
          }
          if (!isUndefined(set)) {
              descriptor.set = function (value) {
                  // At this point we don't have a clear indication of whether
                  // or not a valid mutation will occur, we don't have the key,
                  // and we are not sure why and how they are invoking this setter.
                  // Nevertheless we preserve the original semantics by invoking the
                  // original setter with the original target and the unwrapped value
                  set.call(unwrap(this), membrane.unwrapProxy(value));
              };
          }
      }
      return descriptor;
  }
  class ReactiveMembrane {
      constructor(options) {
          this.valueDistortion = defaultValueDistortion;
          this.valueMutated = defaultValueMutated;
          this.valueObserved = defaultValueObserved;
          this.valueIsObservable = defaultValueIsObservable;
          this.objectGraph = new WeakMap();
          if (!isUndefined(options)) {
              const { valueDistortion, valueMutated, valueObserved, valueIsObservable } = options;
              this.valueDistortion = isFunction(valueDistortion) ? valueDistortion : defaultValueDistortion;
              this.valueMutated = isFunction(valueMutated) ? valueMutated : defaultValueMutated;
              this.valueObserved = isFunction(valueObserved) ? valueObserved : defaultValueObserved;
              this.valueIsObservable = isFunction(valueIsObservable) ? valueIsObservable : defaultValueIsObservable;
          }
      }
      getProxy(value) {
          const unwrappedValue = unwrap(value);
          const distorted = this.valueDistortion(unwrappedValue);
          if (this.valueIsObservable(distorted)) {
              const o = this.getReactiveState(unwrappedValue, distorted);
              // when trying to extract the writable version of a readonly
              // we return the readonly.
              return o.readOnly === value ? value : o.reactive;
          }
          return distorted;
      }
      getReadOnlyProxy(value) {
          value = unwrap(value);
          const distorted = this.valueDistortion(value);
          if (this.valueIsObservable(distorted)) {
              return this.getReactiveState(value, distorted).readOnly;
          }
          return distorted;
      }
      unwrapProxy(p) {
          return unwrap(p);
      }
      getReactiveState(value, distortedValue) {
          const { objectGraph, } = this;
          let reactiveState = objectGraph.get(distortedValue);
          if (reactiveState) {
              return reactiveState;
          }
          const membrane = this;
          reactiveState = {
              get reactive() {
                  const reactiveHandler = new ReactiveProxyHandler(membrane, distortedValue);
                  // caching the reactive proxy after the first time it is accessed
                  const proxy = new Proxy(createShadowTarget(distortedValue), reactiveHandler);
                  registerProxy(proxy, value);
                  ObjectDefineProperty(this, 'reactive', { value: proxy });
                  return proxy;
              },
              get readOnly() {
                  const readOnlyHandler = new ReadOnlyHandler(membrane, distortedValue);
                  // caching the readOnly proxy after the first time it is accessed
                  const proxy = new Proxy(createShadowTarget(distortedValue), readOnlyHandler);
                  registerProxy(proxy, value);
                  ObjectDefineProperty(this, 'readOnly', { value: proxy });
                  return proxy;
              }
          };
          objectGraph.set(distortedValue, reactiveState);
          return reactiveState;
      }
  }
  /** version: 0.26.0 */

  function wrap(data, mutationCallback) {

    let membrane = new ReactiveMembrane({
      valueMutated(target, key) {
        mutationCallback(target, key);
      }

    });
    return {
      data: membrane.getProxy(data),
      membrane: membrane
    };
  }
  function unwrap$1(membrane, observable) {
    let unwrappedData = membrane.unwrapProxy(observable);
    let copy = {};
    Object.keys(unwrappedData).forEach(key => {
      if (['$el', '$refs', '$nextTick', '$watch'].includes(key)) return;
      copy[key] = unwrappedData[key];
    });
    return copy;
  }

  class Component {
    constructor(el, seedDataForCloning = null) {
      this.$el = el;
      const dataAttr = this.$el.getAttribute('x-data');
      const dataExpression = dataAttr === '' ? '{}' : dataAttr;
      const initExpression = this.$el.getAttribute('x-init');
      this.unobservedData = seedDataForCloning ? seedDataForCloning : saferEval(dataExpression, {});
      // Construct a Proxy-based observable. This will be used to handle reactivity.

      let {
        membrane,
        data
      } = this.wrapDataInObservable(this.unobservedData);
      this.$data = data;
      this.membrane = membrane; // After making user-supplied data methods reactive, we can now add
      // our magic properties to the original data for access.

      this.unobservedData.$el = this.$el;
      this.unobservedData.$refs = this.getRefsProxy();
      this.nextTickStack = [];

      this.unobservedData.$nextTick = callback => {
        this.nextTickStack.push(callback);
      };

      this.watchers = {};

      this.unobservedData.$watch = (property, callback) => {
        if (!this.watchers[property]) this.watchers[property] = [];
        this.watchers[property].push(callback);
      };

      this.showDirectiveStack = [];
      this.showDirectiveLastElement;
      var initReturnedCallback; // If x-init is present AND we aren't cloning (skip x-init on clone)

      if (initExpression && !seedDataForCloning) {
        // We want to allow data manipulation, but not trigger DOM updates just yet.
        // We haven't even initialized the elements with their Alpine bindings. I mean c'mon.
        this.pauseReactivity = true;
        initReturnedCallback = this.evaluateReturnExpression(this.$el, initExpression);
        this.pauseReactivity = false;
      } // Register all our listeners and set all our attribute bindings.


      this.initializeElements(this.$el); // Use mutation observer to detect new elements being added within this component at run-time.
      // Alpine's just so darn flexible amirite?

      this.listenForNewElementsToInitialize();

      if (typeof initReturnedCallback === 'function') {
        // Run the callback returned from the "x-init" hook to allow the user to do stuff after
        // Alpine's got it's grubby little paws all over everything.
        initReturnedCallback.call(this.$data);
      }
    }

    getUnobservedData() {
      return unwrap$1(this.membrane, this.$data);
    }

    wrapDataInObservable(data) {
      var self = this;
      let updateDom = debounce(function () {
        self.updateElements(self.$el);
      }, 0);
      return wrap(data, (target, key) => {
        if (self.watchers[key]) {
          // If there's a watcher for this specific key, run it.
          self.watchers[key].forEach(callback => callback(target[key]));
        } else {
          // Let's walk through the watchers with "dot-notation" (foo.bar) and see
          // if this mutation fits any of them.
          Object.keys(self.watchers).filter(i => i.includes('.')).forEach(fullDotNotationKey => {
            let dotNotationParts = fullDotNotationKey.split('.'); // If this dot-notation watcher's last "part" doesn't match the current
            // key, then skip it early for performance reasons.

            if (key !== dotNotationParts[dotNotationParts.length - 1]) return; // Now, walk through the dot-notation "parts" recursively to find
            // a match, and call the watcher if one's found.

            dotNotationParts.reduce((comparisonData, part) => {
              if (Object.is(target, comparisonData)) {
                // Run the watchers.
                self.watchers[fullDotNotationKey].forEach(callback => callback(target[key]));
              }

              return comparisonData[part];
            }, self.getUnobservedData());
          });
        } // Don't react to data changes for cases like the `x-created` hook.


        if (self.pauseReactivity) return;
        updateDom();
      });
    }

    walkAndSkipNestedComponents(el, callback, initializeComponentCallback = () => {}) {
      walk(el, el => {
        // We've hit a component.
        if (el.hasAttribute('x-data')) {
          // If it's not the current one.
          if (!el.isSameNode(this.$el)) {
            // Initialize it if it's not.
            if (!el.__x) initializeComponentCallback(el); // Now we'll let that sub-component deal with itself.

            return false;
          }
        }

        return callback(el);
      });
    }

    initializeElements(rootEl, extraVars = () => {}) {
      this.walkAndSkipNestedComponents(rootEl, el => {
        // Don't touch spawns from for loop
        if (el.__x_for_key !== undefined) return false; // Don't touch spawns from if directives

        if (el.__x_inserted_me !== undefined) return false;
        this.initializeElement(el, extraVars);
      }, el => {
        el.__x = new Component(el);
      });
      this.executeAndClearRemainingShowDirectiveStack();
      this.executeAndClearNextTickStack(rootEl);
    }

    initializeElement(el, extraVars) {
      // To support class attribute merging, we have to know what the element's
      // original class attribute looked like for reference.
      if (el.hasAttribute('class') && getXAttrs(el).length > 0) {
        el.__x_original_classes = el.getAttribute('class').split(' ');
      }

      this.registerListeners(el, extraVars);
      this.resolveBoundAttributes(el, true, extraVars);
    }

    updateElements(rootEl, extraVars = () => {}) {
      this.walkAndSkipNestedComponents(rootEl, el => {
        // Don't touch spawns from for loop (and check if the root is actually a for loop in a parent, don't skip it.)
        if (el.__x_for_key !== undefined && !el.isSameNode(this.$el)) return false;
        this.updateElement(el, extraVars);
      }, el => {
        el.__x = new Component(el);
      });
      this.executeAndClearRemainingShowDirectiveStack();
      this.executeAndClearNextTickStack(rootEl);
    }

    executeAndClearNextTickStack(el) {
      // Skip spawns from alpine directives
      if (el === this.$el) {
        // Walk through the $nextTick stack and clear it as we go.
        while (this.nextTickStack.length > 0) {
          this.nextTickStack.shift()();
        }
      }
    }

    executeAndClearRemainingShowDirectiveStack() {
      // The goal here is to start all the x-show transitions
      // and build a nested promise chain so that elements
      // only hide when the children are finished hiding.
      this.showDirectiveStack.reverse().map(thing => {
        return new Promise(resolve => {
          thing(finish => {
            resolve(finish);
          });
        });
      }).reduce((nestedPromise, promise) => {
        return nestedPromise.then(() => {
          return promise.then(finish => finish());
        });
      }, Promise.resolve(() => {})); // We've processed the handler stack. let's clear it.

      this.showDirectiveStack = [];
      this.showDirectiveLastElement = undefined;
    }

    updateElement(el, extraVars) {
      this.resolveBoundAttributes(el, false, extraVars);
    }

    registerListeners(el, extraVars) {
      getXAttrs(el).forEach(({
        type,
        value,
        modifiers,
        expression
      }) => {
        switch (type) {
          case 'on':
            registerListener(this, el, value, modifiers, expression, extraVars);
            break;

          case 'model':
            registerModelListener(this, el, modifiers, expression, extraVars);
            break;
        }
      });
    }

    resolveBoundAttributes(el, initialUpdate = false, extraVars) {
      let attrs = getXAttrs(el);

      if (el.type !== undefined && el.type === 'radio') {
        // If there's an x-model on a radio input, move it to end of attribute list
        // to ensure that x-bind:value (if present) is processed first.
        const modelIdx = attrs.findIndex(attr => attr.type === 'model');

        if (modelIdx > -1) {
          attrs.push(attrs.splice(modelIdx, 1)[0]);
        }
      }

      attrs.forEach(({
        type,
        value,
        modifiers,
        expression
      }) => {
        switch (type) {
          case 'model':
            handleAttributeBindingDirective(this, el, 'value', expression, extraVars, type);
            break;

          case 'bind':
            // The :key binding on an x-for is special, ignore it.
            if (el.tagName.toLowerCase() === 'template' && value === 'key') return;
            handleAttributeBindingDirective(this, el, value, expression, extraVars, type);
            break;

          case 'text':
            var output = this.evaluateReturnExpression(el, expression, extraVars);
            handleTextDirective(el, output, expression);
            break;

          case 'html':
            handleHtmlDirective(this, el, expression, extraVars);
            break;

          case 'show':
            var output = this.evaluateReturnExpression(el, expression, extraVars);
            handleShowDirective(this, el, output, modifiers, initialUpdate);
            break;

          case 'if':
            // If this element also has x-for on it, don't process x-if.
            // We will let the "x-for" directive handle the "if"ing.
            if (attrs.filter(i => i.type === 'for').length > 0) return;
            var output = this.evaluateReturnExpression(el, expression, extraVars);
            handleIfDirective(this, el, output, initialUpdate, extraVars);
            break;

          case 'for':
            handleForDirective(this, el, expression, initialUpdate, extraVars);
            break;

          case 'cloak':
            el.removeAttribute('x-cloak');
            break;
        }
      });
    }

    evaluateReturnExpression(el, expression, extraVars = () => {}) {
      return saferEval(expression, this.$data, _objectSpread2({}, extraVars(), {
        $dispatch: this.getDispatchFunction(el)
      }));
    }

    evaluateCommandExpression(el, expression, extraVars = () => {}) {
      return saferEvalNoReturn(expression, this.$data, _objectSpread2({}, extraVars(), {
        $dispatch: this.getDispatchFunction(el)
      }));
    }

    getDispatchFunction(el) {
      return (event, detail = {}) => {
        el.dispatchEvent(new CustomEvent(event, {
          detail,
          bubbles: true
        }));
      };
    }

    listenForNewElementsToInitialize() {
      const targetNode = this.$el;
      const observerOptions = {
        childList: true,
        attributes: true,
        subtree: true
      };
      const observer = new MutationObserver(mutations => {
        for (let i = 0; i < mutations.length; i++) {
          // Filter out mutations triggered from child components.
          const closestParentComponent = mutations[i].target.closest('[x-data]');
          if (!(closestParentComponent && closestParentComponent.isSameNode(this.$el))) return;

          if (mutations[i].type === 'attributes' && mutations[i].attributeName === 'x-data') {
            const rawData = saferEval(mutations[i].target.getAttribute('x-data'), {});
            Object.keys(rawData).forEach(key => {
              if (this.$data[key] !== rawData[key]) {
                this.$data[key] = rawData[key];
              }
            });
          }

          if (mutations[i].addedNodes.length > 0) {
            mutations[i].addedNodes.forEach(node => {
              if (node.nodeType !== 1 || node.__x_inserted_me) return;

              if (node.matches('[x-data]')) {
                node.__x = new Component(node);
                return;
              }

              this.initializeElements(node);
            });
          }
        }
      });
      observer.observe(targetNode, observerOptions);
    }

    getRefsProxy() {
      var self = this;
      var refObj = {};
      // One of the goals of this is to not hold elements in memory, but rather re-evaluate
      // the DOM when the system needs something from it. This way, the framework is flexible and
      // friendly to outside DOM changes from libraries like Vue/Livewire.
      // For this reason, I'm using an "on-demand" proxy to fake a "$refs" object.

      return new Proxy(refObj, {
        get(object, property) {
          if (property === '$isAlpineProxy') return true;
          var ref; // We can't just query the DOM because it's hard to filter out refs in
          // nested components.

          self.walkAndSkipNestedComponents(self.$el, el => {
            if (el.hasAttribute('x-ref') && el.getAttribute('x-ref') === property) {
              ref = el;
            }
          });
          return ref;
        }

      });
    }

  }

  const Alpine = {
    start: async function start() {
      if (!isTesting()) {
        await domReady();
      }

      this.discoverComponents(el => {
        this.initializeComponent(el);
      }); // It's easier and more performant to just support Turbolinks than listen
      // to MutationObserver mutations at the document level.

      document.addEventListener("turbolinks:load", () => {
        this.discoverUninitializedComponents(el => {
          this.initializeComponent(el);
        });
      });
      this.listenForNewUninitializedComponentsAtRunTime(el => {
        this.initializeComponent(el);
      });
    },
    discoverComponents: function discoverComponents(callback) {
      const rootEls = document.querySelectorAll('[x-data]');
      rootEls.forEach(rootEl => {
        callback(rootEl);
      });
    },
    discoverUninitializedComponents: function discoverUninitializedComponents(callback, el = null) {
      const rootEls = (el || document).querySelectorAll('[x-data]');
      Array.from(rootEls).filter(el => el.__x === undefined).forEach(rootEl => {
        callback(rootEl);
      });
    },
    listenForNewUninitializedComponentsAtRunTime: function listenForNewUninitializedComponentsAtRunTime(callback) {
      const targetNode = document.querySelector('body');
      const observerOptions = {
        childList: true,
        attributes: true,
        subtree: true
      };
      const observer = new MutationObserver(mutations => {
        for (let i = 0; i < mutations.length; i++) {
          if (mutations[i].addedNodes.length > 0) {
            mutations[i].addedNodes.forEach(node => {
              // Discard non-element nodes (like line-breaks)
              if (node.nodeType !== 1) return; // Discard any changes happening within an existing component.
              // They will take care of themselves.

              if (node.parentElement && node.parentElement.closest('[x-data]')) return;
              this.discoverUninitializedComponents(el => {
                this.initializeComponent(el);
              }, node.parentElement);
            });
          }
        }
      });
      observer.observe(targetNode, observerOptions);
    },
    initializeComponent: function initializeComponent(el) {
      if (!el.__x) {
        el.__x = new Component(el);
      }
    },
    clone: function clone(component, newEl) {
      if (!newEl.__x) {
        newEl.__x = new Component(newEl, component.getUnobservedData());
      }
    }
  };

  if (!isTesting()) {
    window.Alpine = Alpine;

    if (window.deferLoadingAlpine) {
      window.deferLoadingAlpine(function () {
        window.Alpine.start();
      });
    } else {
      window.Alpine.start();
    }
  }

  return Alpine;

})));


/***/ }),

/***/ "./resources/assets/js/application.js":
/*!********************************************!*\
  !*** ./resources/assets/js/application.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/alpine.js");
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(alpinejs__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Shopper.js
 *
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 *
 * @author Arthur Monney<contact@arthurmonney.me>
 * @version 1.0.0
 * @since November 2019
 */


/***/ }),

/***/ "./resources/assets/sass/shopper.scss":
/*!********************************************!*\
  !*** ./resources/assets/sass/shopper.scss ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!***************************************************************************************!*\
  !*** multi ./resources/assets/js/application.js ./resources/assets/sass/shopper.scss ***!
  \***************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/mckenzie/Sites/packages/laravel-shopper/packages/shopper/framework/resources/assets/js/application.js */"./resources/assets/js/application.js");
module.exports = __webpack_require__(/*! /Users/mckenzie/Sites/packages/laravel-shopper/packages/shopper/framework/resources/assets/sass/shopper.scss */"./resources/assets/sass/shopper.scss");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2FscGluZWpzL2Rpc3QvYWxwaW5lLmpzIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9hc3NldHMvanMvYXBwbGljYXRpb24uanMiLCJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9zYXNzL3Nob3BwZXIuc2Nzcz9lOWQ0Il0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7OztBQ2xGQTtBQUNBLEVBQUUsS0FBNEQ7QUFDOUQsRUFBRSxTQUNvRDtBQUN0RCxDQUFDLHFCQUFxQjs7QUFFdEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1AsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FB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QTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxPQUFPLEVBQUU7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsT0FBTztBQUNQO0FBQ0E7QUFDQSxPQUFPO0FBQ1AsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QixzQkFBc0I7QUFDN0M7QUFDQTtBQUNBO0FBQ0EsOENBQThDO0FBQzlDOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGVBQWU7QUFDZixhQUFhO0FBQ2I7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1AsS0FBSztBQUNMO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxDQUFDOzs7Ozs7Ozs7Ozs7O0FDbnJERDtBQUFBO0FBQUE7QUFBQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ0FBLHlDIiwiZmlsZSI6Ii9qcy9hcHBsaWNhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL1wiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4iLCIoZnVuY3Rpb24gKGdsb2JhbCwgZmFjdG9yeSkge1xuICB0eXBlb2YgZXhwb3J0cyA9PT0gJ29iamVjdCcgJiYgdHlwZW9mIG1vZHVsZSAhPT0gJ3VuZGVmaW5lZCcgPyBtb2R1bGUuZXhwb3J0cyA9IGZhY3RvcnkoKSA6XG4gIHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCA/IGRlZmluZShmYWN0b3J5KSA6XG4gIChnbG9iYWwgPSBnbG9iYWwgfHwgc2VsZiwgZ2xvYmFsLkFscGluZSA9IGZhY3RvcnkoKSk7XG59KHRoaXMsIChmdW5jdGlvbiAoKSB7ICd1c2Ugc3RyaWN0JztcblxuICBmdW5jdGlvbiBfZGVmaW5lUHJvcGVydHkob2JqLCBrZXksIHZhbHVlKSB7XG4gICAgaWYgKGtleSBpbiBvYmopIHtcbiAgICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShvYmosIGtleSwge1xuICAgICAgICB2YWx1ZTogdmFsdWUsXG4gICAgICAgIGVudW1lcmFibGU6IHRydWUsXG4gICAgICAgIGNvbmZpZ3VyYWJsZTogdHJ1ZSxcbiAgICAgICAgd3JpdGFibGU6IHRydWVcbiAgICAgIH0pO1xuICAgIH0gZWxzZSB7XG4gICAgICBvYmpba2V5XSA9IHZhbHVlO1xuICAgIH1cblxuICAgIHJldHVybiBvYmo7XG4gIH1cblxuICBmdW5jdGlvbiBvd25LZXlzKG9iamVjdCwgZW51bWVyYWJsZU9ubHkpIHtcbiAgICB2YXIga2V5cyA9IE9iamVjdC5rZXlzKG9iamVjdCk7XG5cbiAgICBpZiAoT2JqZWN0LmdldE93blByb3BlcnR5U3ltYm9scykge1xuICAgICAgdmFyIHN5bWJvbHMgPSBPYmplY3QuZ2V0T3duUHJvcGVydHlTeW1ib2xzKG9iamVjdCk7XG4gICAgICBpZiAoZW51bWVyYWJsZU9ubHkpIHN5bWJvbHMgPSBzeW1ib2xzLmZpbHRlcihmdW5jdGlvbiAoc3ltKSB7XG4gICAgICAgIHJldHVybiBPYmplY3QuZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKG9iamVjdCwgc3ltKS5lbnVtZXJhYmxlO1xuICAgICAgfSk7XG4gICAgICBrZXlzLnB1c2guYXBwbHkoa2V5cywgc3ltYm9scyk7XG4gICAgfVxuXG4gICAgcmV0dXJuIGtleXM7XG4gIH1cblxuICBmdW5jdGlvbiBfb2JqZWN0U3ByZWFkMih0YXJnZXQpIHtcbiAgICBmb3IgKHZhciBpID0gMTsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xuICAgICAgdmFyIHNvdXJjZSA9IGFyZ3VtZW50c1tpXSAhPSBudWxsID8gYXJndW1lbnRzW2ldIDoge307XG5cbiAgICAgIGlmIChpICUgMikge1xuICAgICAgICBvd25LZXlzKE9iamVjdChzb3VyY2UpLCB0cnVlKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcbiAgICAgICAgICBfZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBrZXksIHNvdXJjZVtrZXldKTtcbiAgICAgICAgfSk7XG4gICAgICB9IGVsc2UgaWYgKE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3JzKSB7XG4gICAgICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcnMoc291cmNlKSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBvd25LZXlzKE9iamVjdChzb3VyY2UpKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcbiAgICAgICAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBrZXksIE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Ioc291cmNlLCBrZXkpKTtcbiAgICAgICAgfSk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgcmV0dXJuIHRhcmdldDtcbiAgfVxuXG4gIC8vIFRoYW5rcyBAc3RpbXVsdXM6XG4gIC8vIGh0dHBzOi8vZ2l0aHViLmNvbS9zdGltdWx1c2pzL3N0aW11bHVzL2Jsb2IvbWFzdGVyL3BhY2thZ2VzLyU0MHN0aW11bHVzL2NvcmUvc3JjL2FwcGxpY2F0aW9uLnRzXG4gIGZ1bmN0aW9uIGRvbVJlYWR5KCkge1xuICAgIHJldHVybiBuZXcgUHJvbWlzZShyZXNvbHZlID0+IHtcbiAgICAgIGlmIChkb2N1bWVudC5yZWFkeVN0YXRlID09IFwibG9hZGluZ1wiKSB7XG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsIHJlc29sdmUpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcmVzb2x2ZSgpO1xuICAgICAgfVxuICAgIH0pO1xuICB9XG4gIGZ1bmN0aW9uIGFycmF5VW5pcXVlKGFycmF5KSB7XG4gICAgcmV0dXJuIEFycmF5LmZyb20obmV3IFNldChhcnJheSkpO1xuICB9XG4gIGZ1bmN0aW9uIGlzVGVzdGluZygpIHtcbiAgICByZXR1cm4gbmF2aWdhdG9yLnVzZXJBZ2VudC5pbmNsdWRlcyhcIk5vZGUuanNcIikgfHwgbmF2aWdhdG9yLnVzZXJBZ2VudC5pbmNsdWRlcyhcImpzZG9tXCIpO1xuICB9XG4gIGZ1bmN0aW9uIGtlYmFiQ2FzZShzdWJqZWN0KSB7XG4gICAgcmV0dXJuIHN1YmplY3QucmVwbGFjZSgvKFthLXpdKShbQS1aXSkvZywgJyQxLSQyJykucmVwbGFjZSgvW19cXHNdLywgJy0nKS50b0xvd2VyQ2FzZSgpO1xuICB9XG4gIGZ1bmN0aW9uIHdhbGsoZWwsIGNhbGxiYWNrKSB7XG4gICAgaWYgKGNhbGxiYWNrKGVsKSA9PT0gZmFsc2UpIHJldHVybjtcbiAgICBsZXQgbm9kZSA9IGVsLmZpcnN0RWxlbWVudENoaWxkO1xuXG4gICAgd2hpbGUgKG5vZGUpIHtcbiAgICAgIHdhbGsobm9kZSwgY2FsbGJhY2spO1xuICAgICAgbm9kZSA9IG5vZGUubmV4dEVsZW1lbnRTaWJsaW5nO1xuICAgIH1cbiAgfVxuICBmdW5jdGlvbiBkZWJvdW5jZShmdW5jLCB3YWl0KSB7XG4gICAgdmFyIHRpbWVvdXQ7XG4gICAgcmV0dXJuIGZ1bmN0aW9uICgpIHtcbiAgICAgIHZhciBjb250ZXh0ID0gdGhpcyxcbiAgICAgICAgICBhcmdzID0gYXJndW1lbnRzO1xuXG4gICAgICB2YXIgbGF0ZXIgPSBmdW5jdGlvbiBsYXRlcigpIHtcbiAgICAgICAgdGltZW91dCA9IG51bGw7XG4gICAgICAgIGZ1bmMuYXBwbHkoY29udGV4dCwgYXJncyk7XG4gICAgICB9O1xuXG4gICAgICBjbGVhclRpbWVvdXQodGltZW91dCk7XG4gICAgICB0aW1lb3V0ID0gc2V0VGltZW91dChsYXRlciwgd2FpdCk7XG4gICAgfTtcbiAgfVxuICBmdW5jdGlvbiBzYWZlckV2YWwoZXhwcmVzc2lvbiwgZGF0YUNvbnRleHQsIGFkZGl0aW9uYWxIZWxwZXJWYXJpYWJsZXMgPSB7fSkge1xuICAgIHJldHVybiBuZXcgRnVuY3Rpb24oWyckZGF0YScsIC4uLk9iamVjdC5rZXlzKGFkZGl0aW9uYWxIZWxwZXJWYXJpYWJsZXMpXSwgYHZhciByZXN1bHQ7IHdpdGgoJGRhdGEpIHsgcmVzdWx0ID0gJHtleHByZXNzaW9ufSB9OyByZXR1cm4gcmVzdWx0YCkoZGF0YUNvbnRleHQsIC4uLk9iamVjdC52YWx1ZXMoYWRkaXRpb25hbEhlbHBlclZhcmlhYmxlcykpO1xuICB9XG4gIGZ1bmN0aW9uIHNhZmVyRXZhbE5vUmV0dXJuKGV4cHJlc3Npb24sIGRhdGFDb250ZXh0LCBhZGRpdGlvbmFsSGVscGVyVmFyaWFibGVzID0ge30pIHtcbiAgICAvLyBGb3IgdGhlIGNhc2VzIHdoZW4gdXNlcnMgcGFzcyBvbmx5IGEgZnVuY3Rpb24gcmVmZXJlbmNlIHRvIHRoZSBjYWxsZXI6IGB4LW9uOmNsaWNrPVwiZm9vXCJgXG4gICAgLy8gV2hlcmUgXCJmb29cIiBpcyBhIGZ1bmN0aW9uLiBBbHNvLCB3ZSdsbCBwYXNzIHRoZSBmdW5jdGlvbiB0aGUgZXZlbnQgaW5zdGFuY2Ugd2hlbiB3ZSBjYWxsIGl0LlxuICAgIGlmIChPYmplY3Qua2V5cyhkYXRhQ29udGV4dCkuaW5jbHVkZXMoZXhwcmVzc2lvbikpIHtcbiAgICAgIGxldCBtZXRob2RSZWZlcmVuY2UgPSBuZXcgRnVuY3Rpb24oWydkYXRhQ29udGV4dCcsIC4uLk9iamVjdC5rZXlzKGFkZGl0aW9uYWxIZWxwZXJWYXJpYWJsZXMpXSwgYHdpdGgoZGF0YUNvbnRleHQpIHsgcmV0dXJuICR7ZXhwcmVzc2lvbn0gfWApKGRhdGFDb250ZXh0LCAuLi5PYmplY3QudmFsdWVzKGFkZGl0aW9uYWxIZWxwZXJWYXJpYWJsZXMpKTtcblxuICAgICAgaWYgKHR5cGVvZiBtZXRob2RSZWZlcmVuY2UgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgcmV0dXJuIG1ldGhvZFJlZmVyZW5jZS5jYWxsKGRhdGFDb250ZXh0LCBhZGRpdGlvbmFsSGVscGVyVmFyaWFibGVzWyckZXZlbnQnXSk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgcmV0dXJuIG5ldyBGdW5jdGlvbihbJ2RhdGFDb250ZXh0JywgLi4uT2JqZWN0LmtleXMoYWRkaXRpb25hbEhlbHBlclZhcmlhYmxlcyldLCBgd2l0aChkYXRhQ29udGV4dCkgeyAke2V4cHJlc3Npb259IH1gKShkYXRhQ29udGV4dCwgLi4uT2JqZWN0LnZhbHVlcyhhZGRpdGlvbmFsSGVscGVyVmFyaWFibGVzKSk7XG4gIH1cbiAgY29uc3QgeEF0dHJSRSA9IC9eeC0ob258YmluZHxkYXRhfHRleHR8aHRtbHxtb2RlbHxpZnxmb3J8c2hvd3xjbG9ha3x0cmFuc2l0aW9ufHJlZilcXGIvO1xuICBmdW5jdGlvbiBpc1hBdHRyKGF0dHIpIHtcbiAgICBjb25zdCBuYW1lID0gcmVwbGFjZUF0QW5kQ29sb25XaXRoU3RhbmRhcmRTeW50YXgoYXR0ci5uYW1lKTtcbiAgICByZXR1cm4geEF0dHJSRS50ZXN0KG5hbWUpO1xuICB9XG4gIGZ1bmN0aW9uIGdldFhBdHRycyhlbCwgdHlwZSkge1xuICAgIHJldHVybiBBcnJheS5mcm9tKGVsLmF0dHJpYnV0ZXMpLmZpbHRlcihpc1hBdHRyKS5tYXAoYXR0ciA9PiB7XG4gICAgICBjb25zdCBuYW1lID0gcmVwbGFjZUF0QW5kQ29sb25XaXRoU3RhbmRhcmRTeW50YXgoYXR0ci5uYW1lKTtcbiAgICAgIGNvbnN0IHR5cGVNYXRjaCA9IG5hbWUubWF0Y2goeEF0dHJSRSk7XG4gICAgICBjb25zdCB2YWx1ZU1hdGNoID0gbmFtZS5tYXRjaCgvOihbYS16QS1aXFwtOl0rKS8pO1xuICAgICAgY29uc3QgbW9kaWZpZXJzID0gbmFtZS5tYXRjaCgvXFwuW14uXFxdXSsoPz1bXlxcXV0qJCkvZykgfHwgW107XG4gICAgICByZXR1cm4ge1xuICAgICAgICB0eXBlOiB0eXBlTWF0Y2ggPyB0eXBlTWF0Y2hbMV0gOiBudWxsLFxuICAgICAgICB2YWx1ZTogdmFsdWVNYXRjaCA/IHZhbHVlTWF0Y2hbMV0gOiBudWxsLFxuICAgICAgICBtb2RpZmllcnM6IG1vZGlmaWVycy5tYXAoaSA9PiBpLnJlcGxhY2UoJy4nLCAnJykpLFxuICAgICAgICBleHByZXNzaW9uOiBhdHRyLnZhbHVlXG4gICAgICB9O1xuICAgIH0pLmZpbHRlcihpID0+IHtcbiAgICAgIC8vIElmIG5vIHR5cGUgaXMgcGFzc2VkIGluIGZvciBmaWx0ZXJpbmcsIGJ5cGFzcyBmaWx0ZXJcbiAgICAgIGlmICghdHlwZSkgcmV0dXJuIHRydWU7XG4gICAgICByZXR1cm4gaS50eXBlID09PSB0eXBlO1xuICAgIH0pO1xuICB9XG4gIGZ1bmN0aW9uIGlzQm9vbGVhbkF0dHIoYXR0ck5hbWUpIHtcbiAgICAvLyBBcyBwZXIgSFRNTCBzcGVjIHRhYmxlIGh0dHBzOi8vaHRtbC5zcGVjLndoYXR3Zy5vcmcvbXVsdGlwYWdlL2luZGljZXMuaHRtbCNhdHRyaWJ1dGVzLTM6Ym9vbGVhbi1hdHRyaWJ1dGVcbiAgICAvLyBBcnJheSByb3VnaGx5IG9yZGVyZWQgYnkgZXN0aW1hdGVkIHVzYWdlXG4gICAgY29uc3QgYm9vbGVhbkF0dHJpYnV0ZXMgPSBbJ2Rpc2FibGVkJywgJ2NoZWNrZWQnLCAncmVxdWlyZWQnLCAncmVhZG9ubHknLCAnaGlkZGVuJywgJ29wZW4nLCAnc2VsZWN0ZWQnLCAnYXV0b2ZvY3VzJywgJ2l0ZW1zY29wZScsICdtdWx0aXBsZScsICdub3ZhbGlkYXRlJywgJ2FsbG93ZnVsbHNjcmVlbicsICdhbGxvd3BheW1lbnRyZXF1ZXN0JywgJ2Zvcm1ub3ZhbGlkYXRlJywgJ2F1dG9wbGF5JywgJ2NvbnRyb2xzJywgJ2xvb3AnLCAnbXV0ZWQnLCAncGxheXNpbmxpbmUnLCAnZGVmYXVsdCcsICdpc21hcCcsICdyZXZlcnNlZCcsICdhc3luYycsICdkZWZlcicsICdub21vZHVsZSddO1xuICAgIHJldHVybiBib29sZWFuQXR0cmlidXRlcy5pbmNsdWRlcyhhdHRyTmFtZSk7XG4gIH1cbiAgZnVuY3Rpb24gcmVwbGFjZUF0QW5kQ29sb25XaXRoU3RhbmRhcmRTeW50YXgobmFtZSkge1xuICAgIGlmIChuYW1lLnN0YXJ0c1dpdGgoJ0AnKSkge1xuICAgICAgcmV0dXJuIG5hbWUucmVwbGFjZSgnQCcsICd4LW9uOicpO1xuICAgIH0gZWxzZSBpZiAobmFtZS5zdGFydHNXaXRoKCc6JykpIHtcbiAgICAgIHJldHVybiBuYW1lLnJlcGxhY2UoJzonLCAneC1iaW5kOicpO1xuICAgIH1cblxuICAgIHJldHVybiBuYW1lO1xuICB9XG4gIGZ1bmN0aW9uIHRyYW5zaXRpb25JbihlbCwgc2hvdywgZm9yY2VTa2lwID0gZmFsc2UpIHtcbiAgICAvLyBXZSBkb24ndCB3YW50IHRvIHRyYW5zaXRpb24gb24gdGhlIGluaXRpYWwgcGFnZSBsb2FkLlxuICAgIGlmIChmb3JjZVNraXApIHJldHVybiBzaG93KCk7XG4gICAgY29uc3QgYXR0cnMgPSBnZXRYQXR0cnMoZWwsICd0cmFuc2l0aW9uJyk7XG4gICAgY29uc3Qgc2hvd0F0dHIgPSBnZXRYQXR0cnMoZWwsICdzaG93JylbMF07IC8vIElmIHRoaXMgaXMgdHJpZ2dlcmVkIGJ5IGEgeC1zaG93LnRyYW5zaXRpb24uXG5cbiAgICBpZiAoc2hvd0F0dHIgJiYgc2hvd0F0dHIubW9kaWZpZXJzLmluY2x1ZGVzKCd0cmFuc2l0aW9uJykpIHtcbiAgICAgIGxldCBtb2RpZmllcnMgPSBzaG93QXR0ci5tb2RpZmllcnM7IC8vIElmIHgtc2hvdy50cmFuc2l0aW9uLm91dCwgd2UnbGwgc2tpcCB0aGUgXCJpblwiIHRyYW5zaXRpb24uXG5cbiAgICAgIGlmIChtb2RpZmllcnMuaW5jbHVkZXMoJ291dCcpICYmICFtb2RpZmllcnMuaW5jbHVkZXMoJ2luJykpIHJldHVybiBzaG93KCk7XG4gICAgICBjb25zdCBzZXR0aW5nQm90aFNpZGVzT2ZUcmFuc2l0aW9uID0gbW9kaWZpZXJzLmluY2x1ZGVzKCdpbicpICYmIG1vZGlmaWVycy5pbmNsdWRlcygnb3V0Jyk7IC8vIElmIHgtc2hvdy50cmFuc2l0aW9uLmluLi4ub3V0Li4uIG9ubHkgdXNlIFwiaW5cIiByZWxhdGVkIG1vZGlmaWVycyBmb3IgdGhpcyB0cmFuc2l0aW9uLlxuXG4gICAgICBtb2RpZmllcnMgPSBzZXR0aW5nQm90aFNpZGVzT2ZUcmFuc2l0aW9uID8gbW9kaWZpZXJzLmZpbHRlcigoaSwgaW5kZXgpID0+IGluZGV4IDwgbW9kaWZpZXJzLmluZGV4T2YoJ291dCcpKSA6IG1vZGlmaWVycztcbiAgICAgIHRyYW5zaXRpb25IZWxwZXJJbihlbCwgbW9kaWZpZXJzLCBzaG93KTsgLy8gT3RoZXJ3aXNlLCB3ZSBjYW4gYXNzdW1lIHgtdHJhbnNpdGlvbjplbnRlci5cbiAgICB9IGVsc2UgaWYgKGF0dHJzLmxlbmd0aCA+IDApIHtcbiAgICAgIHRyYW5zaXRpb25DbGFzc2VzSW4oZWwsIGF0dHJzLCBzaG93KTtcbiAgICB9IGVsc2Uge1xuICAgICAgLy8gSWYgbmVpdGhlciwganVzdCBzaG93IHRoYXQgZGFtbiB0aGluZy5cbiAgICAgIHNob3coKTtcbiAgICB9XG4gIH1cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbk91dChlbCwgaGlkZSwgZm9yY2VTa2lwID0gZmFsc2UpIHtcbiAgICBpZiAoZm9yY2VTa2lwKSByZXR1cm4gaGlkZSgpO1xuICAgIGNvbnN0IGF0dHJzID0gZ2V0WEF0dHJzKGVsLCAndHJhbnNpdGlvbicpO1xuICAgIGNvbnN0IHNob3dBdHRyID0gZ2V0WEF0dHJzKGVsLCAnc2hvdycpWzBdO1xuXG4gICAgaWYgKHNob3dBdHRyICYmIHNob3dBdHRyLm1vZGlmaWVycy5pbmNsdWRlcygndHJhbnNpdGlvbicpKSB7XG4gICAgICBsZXQgbW9kaWZpZXJzID0gc2hvd0F0dHIubW9kaWZpZXJzO1xuICAgICAgaWYgKG1vZGlmaWVycy5pbmNsdWRlcygnaW4nKSAmJiAhbW9kaWZpZXJzLmluY2x1ZGVzKCdvdXQnKSkgcmV0dXJuIGhpZGUoKTtcbiAgICAgIGNvbnN0IHNldHRpbmdCb3RoU2lkZXNPZlRyYW5zaXRpb24gPSBtb2RpZmllcnMuaW5jbHVkZXMoJ2luJykgJiYgbW9kaWZpZXJzLmluY2x1ZGVzKCdvdXQnKTtcbiAgICAgIG1vZGlmaWVycyA9IHNldHRpbmdCb3RoU2lkZXNPZlRyYW5zaXRpb24gPyBtb2RpZmllcnMuZmlsdGVyKChpLCBpbmRleCkgPT4gaW5kZXggPiBtb2RpZmllcnMuaW5kZXhPZignb3V0JykpIDogbW9kaWZpZXJzO1xuICAgICAgdHJhbnNpdGlvbkhlbHBlck91dChlbCwgbW9kaWZpZXJzLCBzZXR0aW5nQm90aFNpZGVzT2ZUcmFuc2l0aW9uLCBoaWRlKTtcbiAgICB9IGVsc2UgaWYgKGF0dHJzLmxlbmd0aCA+IDApIHtcbiAgICAgIHRyYW5zaXRpb25DbGFzc2VzT3V0KGVsLCBhdHRycywgaGlkZSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGhpZGUoKTtcbiAgICB9XG4gIH1cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbkhlbHBlckluKGVsLCBtb2RpZmllcnMsIHNob3dDYWxsYmFjaykge1xuICAgIC8vIERlZmF1bHQgdmFsdWVzIGluc3BpcmVkIGJ5OiBodHRwczovL21hdGVyaWFsLmlvL2Rlc2lnbi9tb3Rpb24vc3BlZWQuaHRtbCNkdXJhdGlvblxuICAgIGNvbnN0IHN0eWxlVmFsdWVzID0ge1xuICAgICAgZHVyYXRpb246IG1vZGlmaWVyVmFsdWUobW9kaWZpZXJzLCAnZHVyYXRpb24nLCAxNTApLFxuICAgICAgb3JpZ2luOiBtb2RpZmllclZhbHVlKG1vZGlmaWVycywgJ29yaWdpbicsICdjZW50ZXInKSxcbiAgICAgIGZpcnN0OiB7XG4gICAgICAgIG9wYWNpdHk6IDAsXG4gICAgICAgIHNjYWxlOiBtb2RpZmllclZhbHVlKG1vZGlmaWVycywgJ3NjYWxlJywgOTUpXG4gICAgICB9LFxuICAgICAgc2Vjb25kOiB7XG4gICAgICAgIG9wYWNpdHk6IDEsXG4gICAgICAgIHNjYWxlOiAxMDBcbiAgICAgIH1cbiAgICB9O1xuICAgIHRyYW5zaXRpb25IZWxwZXIoZWwsIG1vZGlmaWVycywgc2hvd0NhbGxiYWNrLCAoKSA9PiB7fSwgc3R5bGVWYWx1ZXMpO1xuICB9XG4gIGZ1bmN0aW9uIHRyYW5zaXRpb25IZWxwZXJPdXQoZWwsIG1vZGlmaWVycywgc2V0dGluZ0JvdGhTaWRlc09mVHJhbnNpdGlvbiwgaGlkZUNhbGxiYWNrKSB7XG4gICAgLy8gTWFrZSB0aGUgXCJvdXRcIiB0cmFuc2l0aW9uIC41eCBzbG93ZXIgdGhhbiB0aGUgXCJpblwiLiAoVmlzdWFsbHkgYmV0dGVyKVxuICAgIC8vIEhPV0VWRVIsIGlmIHRoZXkgZXhwbGljaXRseSBzZXQgYSBkdXJhdGlvbiBmb3IgdGhlIFwib3V0XCIgdHJhbnNpdGlvbixcbiAgICAvLyB1c2UgdGhhdC5cbiAgICBjb25zdCBkdXJhdGlvbiA9IHNldHRpbmdCb3RoU2lkZXNPZlRyYW5zaXRpb24gPyBtb2RpZmllclZhbHVlKG1vZGlmaWVycywgJ2R1cmF0aW9uJywgMTUwKSA6IG1vZGlmaWVyVmFsdWUobW9kaWZpZXJzLCAnZHVyYXRpb24nLCAxNTApIC8gMjtcbiAgICBjb25zdCBzdHlsZVZhbHVlcyA9IHtcbiAgICAgIGR1cmF0aW9uOiBkdXJhdGlvbixcbiAgICAgIG9yaWdpbjogbW9kaWZpZXJWYWx1ZShtb2RpZmllcnMsICdvcmlnaW4nLCAnY2VudGVyJyksXG4gICAgICBmaXJzdDoge1xuICAgICAgICBvcGFjaXR5OiAxLFxuICAgICAgICBzY2FsZTogMTAwXG4gICAgICB9LFxuICAgICAgc2Vjb25kOiB7XG4gICAgICAgIG9wYWNpdHk6IDAsXG4gICAgICAgIHNjYWxlOiBtb2RpZmllclZhbHVlKG1vZGlmaWVycywgJ3NjYWxlJywgOTUpXG4gICAgICB9XG4gICAgfTtcbiAgICB0cmFuc2l0aW9uSGVscGVyKGVsLCBtb2RpZmllcnMsICgpID0+IHt9LCBoaWRlQ2FsbGJhY2ssIHN0eWxlVmFsdWVzKTtcbiAgfVxuXG4gIGZ1bmN0aW9uIG1vZGlmaWVyVmFsdWUobW9kaWZpZXJzLCBrZXksIGZhbGxiYWNrKSB7XG4gICAgLy8gSWYgdGhlIG1vZGlmaWVyIGlzbid0IHByZXNlbnQsIHVzZSB0aGUgZGVmYXVsdC5cbiAgICBpZiAobW9kaWZpZXJzLmluZGV4T2Yoa2V5KSA9PT0gLTEpIHJldHVybiBmYWxsYmFjazsgLy8gSWYgaXQgSVMgcHJlc2VudCwgZ3JhYiB0aGUgdmFsdWUgYWZ0ZXIgaXQ6IHgtc2hvdy50cmFuc2l0aW9uLmR1cmF0aW9uLjUwMG1zXG5cbiAgICBjb25zdCByYXdWYWx1ZSA9IG1vZGlmaWVyc1ttb2RpZmllcnMuaW5kZXhPZihrZXkpICsgMV07XG4gICAgaWYgKCFyYXdWYWx1ZSkgcmV0dXJuIGZhbGxiYWNrO1xuXG4gICAgaWYgKGtleSA9PT0gJ3NjYWxlJykge1xuICAgICAgLy8gQ2hlY2sgaWYgdGhlIHZlcnkgbmV4dCB2YWx1ZSBpcyBOT1QgYSBudW1iZXIgYW5kIHJldHVybiB0aGUgZmFsbGJhY2suXG4gICAgICAvLyBJZiB4LXNob3cudHJhbnNpdGlvbi5zY2FsZSwgd2UnbGwgdXNlIHRoZSBkZWZhdWx0IHNjYWxlIHZhbHVlLlxuICAgICAgLy8gVGhhdCBpcyBob3cgYSB1c2VyIG9wdHMgb3V0IG9mIHRoZSBvcGFjaXR5IHRyYW5zaXRpb24uXG4gICAgICBpZiAoIWlzTnVtZXJpYyhyYXdWYWx1ZSkpIHJldHVybiBmYWxsYmFjaztcbiAgICB9XG5cbiAgICBpZiAoa2V5ID09PSAnZHVyYXRpb24nKSB7XG4gICAgICAvLyBTdXBwb3J0IHgtc2hvdy50cmFuc2l0aW9uLmR1cmF0aW9uLjUwMG1zICYmIGR1cmF0aW9uLjUwMFxuICAgICAgbGV0IG1hdGNoID0gcmF3VmFsdWUubWF0Y2goLyhbMC05XSspbXMvKTtcbiAgICAgIGlmIChtYXRjaCkgcmV0dXJuIG1hdGNoWzFdO1xuICAgIH1cblxuICAgIGlmIChrZXkgPT09ICdvcmlnaW4nKSB7XG4gICAgICAvLyBTdXBwb3J0IGNoYWluaW5nIG9yaWdpbiBkaXJlY3Rpb25zOiB4LXNob3cudHJhbnNpdGlvbi50b3AucmlnaHRcbiAgICAgIGlmIChbJ3RvcCcsICdyaWdodCcsICdsZWZ0JywgJ2NlbnRlcicsICdib3R0b20nXS5pbmNsdWRlcyhtb2RpZmllcnNbbW9kaWZpZXJzLmluZGV4T2Yoa2V5KSArIDJdKSkge1xuICAgICAgICByZXR1cm4gW3Jhd1ZhbHVlLCBtb2RpZmllcnNbbW9kaWZpZXJzLmluZGV4T2Yoa2V5KSArIDJdXS5qb2luKCcgJyk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgcmV0dXJuIHJhd1ZhbHVlO1xuICB9XG5cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbkhlbHBlcihlbCwgbW9kaWZpZXJzLCBob29rMSwgaG9vazIsIHN0eWxlVmFsdWVzKSB7XG4gICAgLy8gSWYgdGhlIHVzZXIgc2V0IHRoZXNlIHN0eWxlIHZhbHVlcywgd2UnbGwgcHV0IHRoZW0gYmFjayB3aGVuIHdlJ3JlIGRvbmUgd2l0aCB0aGVtLlxuICAgIGNvbnN0IG9wYWNpdHlDYWNoZSA9IGVsLnN0eWxlLm9wYWNpdHk7XG4gICAgY29uc3QgdHJhbnNmb3JtQ2FjaGUgPSBlbC5zdHlsZS50cmFuc2Zvcm07XG4gICAgY29uc3QgdHJhbnNmb3JtT3JpZ2luQ2FjaGUgPSBlbC5zdHlsZS50cmFuc2Zvcm1PcmlnaW47IC8vIElmIG5vIG1vZGlmaWVycyBhcmUgcHJlc2VudDogeC1zaG93LnRyYW5zaXRpb24sIHdlJ2xsIGRlZmF1bHQgdG8gYm90aCBvcGFjaXR5IGFuZCBzY2FsZS5cblxuICAgIGNvbnN0IG5vTW9kaWZpZXJzID0gIW1vZGlmaWVycy5pbmNsdWRlcygnb3BhY2l0eScpICYmICFtb2RpZmllcnMuaW5jbHVkZXMoJ3NjYWxlJyk7XG4gICAgY29uc3QgdHJhbnNpdGlvbk9wYWNpdHkgPSBub01vZGlmaWVycyB8fCBtb2RpZmllcnMuaW5jbHVkZXMoJ29wYWNpdHknKTtcbiAgICBjb25zdCB0cmFuc2l0aW9uU2NhbGUgPSBub01vZGlmaWVycyB8fCBtb2RpZmllcnMuaW5jbHVkZXMoJ3NjYWxlJyk7IC8vIFRoZXNlIGFyZSB0aGUgZXhwbGljaXQgc3RhZ2VzIG9mIGEgdHJhbnNpdGlvbiAoc2FtZSBzdGFnZXMgZm9yIGluIGFuZCBmb3Igb3V0KS5cbiAgICAvLyBUaGlzIHdheSB5b3UgY2FuIGdldCBhIGJpcmRzIGV5ZSB2aWV3IG9mIHRoZSBob29rcywgYW5kIHRoZSBkaWZmZXJlbmNlc1xuICAgIC8vIGJldHdlZW4gdGhlbS5cblxuICAgIGNvbnN0IHN0YWdlcyA9IHtcbiAgICAgIHN0YXJ0KCkge1xuICAgICAgICBpZiAodHJhbnNpdGlvbk9wYWNpdHkpIGVsLnN0eWxlLm9wYWNpdHkgPSBzdHlsZVZhbHVlcy5maXJzdC5vcGFjaXR5O1xuICAgICAgICBpZiAodHJhbnNpdGlvblNjYWxlKSBlbC5zdHlsZS50cmFuc2Zvcm0gPSBgc2NhbGUoJHtzdHlsZVZhbHVlcy5maXJzdC5zY2FsZSAvIDEwMH0pYDtcbiAgICAgIH0sXG5cbiAgICAgIGR1cmluZygpIHtcbiAgICAgICAgaWYgKHRyYW5zaXRpb25TY2FsZSkgZWwuc3R5bGUudHJhbnNmb3JtT3JpZ2luID0gc3R5bGVWYWx1ZXMub3JpZ2luO1xuICAgICAgICBlbC5zdHlsZS50cmFuc2l0aW9uUHJvcGVydHkgPSBbdHJhbnNpdGlvbk9wYWNpdHkgPyBgb3BhY2l0eWAgOiBgYCwgdHJhbnNpdGlvblNjYWxlID8gYHRyYW5zZm9ybWAgOiBgYF0uam9pbignICcpLnRyaW0oKTtcbiAgICAgICAgZWwuc3R5bGUudHJhbnNpdGlvbkR1cmF0aW9uID0gYCR7c3R5bGVWYWx1ZXMuZHVyYXRpb24gLyAxMDAwfXNgO1xuICAgICAgICBlbC5zdHlsZS50cmFuc2l0aW9uVGltaW5nRnVuY3Rpb24gPSBgY3ViaWMtYmV6aWVyKDAuNCwgMC4wLCAwLjIsIDEpYDtcbiAgICAgIH0sXG5cbiAgICAgIHNob3coKSB7XG4gICAgICAgIGhvb2sxKCk7XG4gICAgICB9LFxuXG4gICAgICBlbmQoKSB7XG4gICAgICAgIGlmICh0cmFuc2l0aW9uT3BhY2l0eSkgZWwuc3R5bGUub3BhY2l0eSA9IHN0eWxlVmFsdWVzLnNlY29uZC5vcGFjaXR5O1xuICAgICAgICBpZiAodHJhbnNpdGlvblNjYWxlKSBlbC5zdHlsZS50cmFuc2Zvcm0gPSBgc2NhbGUoJHtzdHlsZVZhbHVlcy5zZWNvbmQuc2NhbGUgLyAxMDB9KWA7XG4gICAgICB9LFxuXG4gICAgICBoaWRlKCkge1xuICAgICAgICBob29rMigpO1xuICAgICAgfSxcblxuICAgICAgY2xlYW51cCgpIHtcbiAgICAgICAgaWYgKHRyYW5zaXRpb25PcGFjaXR5KSBlbC5zdHlsZS5vcGFjaXR5ID0gb3BhY2l0eUNhY2hlO1xuICAgICAgICBpZiAodHJhbnNpdGlvblNjYWxlKSBlbC5zdHlsZS50cmFuc2Zvcm0gPSB0cmFuc2Zvcm1DYWNoZTtcbiAgICAgICAgaWYgKHRyYW5zaXRpb25TY2FsZSkgZWwuc3R5bGUudHJhbnNmb3JtT3JpZ2luID0gdHJhbnNmb3JtT3JpZ2luQ2FjaGU7XG4gICAgICAgIGVsLnN0eWxlLnRyYW5zaXRpb25Qcm9wZXJ0eSA9IG51bGw7XG4gICAgICAgIGVsLnN0eWxlLnRyYW5zaXRpb25EdXJhdGlvbiA9IG51bGw7XG4gICAgICAgIGVsLnN0eWxlLnRyYW5zaXRpb25UaW1pbmdGdW5jdGlvbiA9IG51bGw7XG4gICAgICB9XG5cbiAgICB9O1xuICAgIHRyYW5zaXRpb24oZWwsIHN0YWdlcyk7XG4gIH1cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbkNsYXNzZXNJbihlbCwgZGlyZWN0aXZlcywgc2hvd0NhbGxiYWNrKSB7XG4gICAgY29uc3QgZW50ZXIgPSAoZGlyZWN0aXZlcy5maW5kKGkgPT4gaS52YWx1ZSA9PT0gJ2VudGVyJykgfHwge1xuICAgICAgZXhwcmVzc2lvbjogJydcbiAgICB9KS5leHByZXNzaW9uLnNwbGl0KCcgJykuZmlsdGVyKGkgPT4gaSAhPT0gJycpO1xuICAgIGNvbnN0IGVudGVyU3RhcnQgPSAoZGlyZWN0aXZlcy5maW5kKGkgPT4gaS52YWx1ZSA9PT0gJ2VudGVyLXN0YXJ0JykgfHwge1xuICAgICAgZXhwcmVzc2lvbjogJydcbiAgICB9KS5leHByZXNzaW9uLnNwbGl0KCcgJykuZmlsdGVyKGkgPT4gaSAhPT0gJycpO1xuICAgIGNvbnN0IGVudGVyRW5kID0gKGRpcmVjdGl2ZXMuZmluZChpID0+IGkudmFsdWUgPT09ICdlbnRlci1lbmQnKSB8fCB7XG4gICAgICBleHByZXNzaW9uOiAnJ1xuICAgIH0pLmV4cHJlc3Npb24uc3BsaXQoJyAnKS5maWx0ZXIoaSA9PiBpICE9PSAnJyk7XG4gICAgdHJhbnNpdGlvbkNsYXNzZXMoZWwsIGVudGVyLCBlbnRlclN0YXJ0LCBlbnRlckVuZCwgc2hvd0NhbGxiYWNrLCAoKSA9PiB7fSk7XG4gIH1cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbkNsYXNzZXNPdXQoZWwsIGRpcmVjdGl2ZXMsIGhpZGVDYWxsYmFjaykge1xuICAgIGNvbnN0IGxlYXZlID0gKGRpcmVjdGl2ZXMuZmluZChpID0+IGkudmFsdWUgPT09ICdsZWF2ZScpIHx8IHtcbiAgICAgIGV4cHJlc3Npb246ICcnXG4gICAgfSkuZXhwcmVzc2lvbi5zcGxpdCgnICcpLmZpbHRlcihpID0+IGkgIT09ICcnKTtcbiAgICBjb25zdCBsZWF2ZVN0YXJ0ID0gKGRpcmVjdGl2ZXMuZmluZChpID0+IGkudmFsdWUgPT09ICdsZWF2ZS1zdGFydCcpIHx8IHtcbiAgICAgIGV4cHJlc3Npb246ICcnXG4gICAgfSkuZXhwcmVzc2lvbi5zcGxpdCgnICcpLmZpbHRlcihpID0+IGkgIT09ICcnKTtcbiAgICBjb25zdCBsZWF2ZUVuZCA9IChkaXJlY3RpdmVzLmZpbmQoaSA9PiBpLnZhbHVlID09PSAnbGVhdmUtZW5kJykgfHwge1xuICAgICAgZXhwcmVzc2lvbjogJydcbiAgICB9KS5leHByZXNzaW9uLnNwbGl0KCcgJykuZmlsdGVyKGkgPT4gaSAhPT0gJycpO1xuICAgIHRyYW5zaXRpb25DbGFzc2VzKGVsLCBsZWF2ZSwgbGVhdmVTdGFydCwgbGVhdmVFbmQsICgpID0+IHt9LCBoaWRlQ2FsbGJhY2spO1xuICB9XG4gIGZ1bmN0aW9uIHRyYW5zaXRpb25DbGFzc2VzKGVsLCBjbGFzc2VzRHVyaW5nLCBjbGFzc2VzU3RhcnQsIGNsYXNzZXNFbmQsIGhvb2sxLCBob29rMikge1xuICAgIGNvbnN0IG9yaWdpbmFsQ2xhc3NlcyA9IGVsLl9feF9vcmlnaW5hbF9jbGFzc2VzIHx8IFtdO1xuICAgIGNvbnN0IHN0YWdlcyA9IHtcbiAgICAgIHN0YXJ0KCkge1xuICAgICAgICBlbC5jbGFzc0xpc3QuYWRkKC4uLmNsYXNzZXNTdGFydCk7XG4gICAgICB9LFxuXG4gICAgICBkdXJpbmcoKSB7XG4gICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoLi4uY2xhc3Nlc0R1cmluZyk7XG4gICAgICB9LFxuXG4gICAgICBzaG93KCkge1xuICAgICAgICBob29rMSgpO1xuICAgICAgfSxcblxuICAgICAgZW5kKCkge1xuICAgICAgICAvLyBEb24ndCByZW1vdmUgY2xhc3NlcyB0aGF0IHdlcmUgaW4gdGhlIG9yaWdpbmFsIGNsYXNzIGF0dHJpYnV0ZS5cbiAgICAgICAgZWwuY2xhc3NMaXN0LnJlbW92ZSguLi5jbGFzc2VzU3RhcnQuZmlsdGVyKGkgPT4gIW9yaWdpbmFsQ2xhc3Nlcy5pbmNsdWRlcyhpKSkpO1xuICAgICAgICBlbC5jbGFzc0xpc3QuYWRkKC4uLmNsYXNzZXNFbmQpO1xuICAgICAgfSxcblxuICAgICAgaGlkZSgpIHtcbiAgICAgICAgaG9vazIoKTtcbiAgICAgIH0sXG5cbiAgICAgIGNsZWFudXAoKSB7XG4gICAgICAgIGVsLmNsYXNzTGlzdC5yZW1vdmUoLi4uY2xhc3Nlc0R1cmluZy5maWx0ZXIoaSA9PiAhb3JpZ2luYWxDbGFzc2VzLmluY2x1ZGVzKGkpKSk7XG4gICAgICAgIGVsLmNsYXNzTGlzdC5yZW1vdmUoLi4uY2xhc3Nlc0VuZC5maWx0ZXIoaSA9PiAhb3JpZ2luYWxDbGFzc2VzLmluY2x1ZGVzKGkpKSk7XG4gICAgICB9XG5cbiAgICB9O1xuICAgIHRyYW5zaXRpb24oZWwsIHN0YWdlcyk7XG4gIH1cbiAgZnVuY3Rpb24gdHJhbnNpdGlvbihlbCwgc3RhZ2VzKSB7XG4gICAgc3RhZ2VzLnN0YXJ0KCk7XG4gICAgc3RhZ2VzLmR1cmluZygpO1xuICAgIHJlcXVlc3RBbmltYXRpb25GcmFtZSgoKSA9PiB7XG4gICAgICAvLyBOb3RlOiBTYWZhcmkncyB0cmFuc2l0aW9uRHVyYXRpb24gcHJvcGVydHkgd2lsbCBsaXN0IG91dCBjb21tYSBzZXBhcmF0ZWQgdHJhbnNpdGlvbiBkdXJhdGlvbnNcbiAgICAgIC8vIGZvciBldmVyeSBzaW5nbGUgdHJhbnNpdGlvbiBwcm9wZXJ0eS4gTGV0J3MgZ3JhYiB0aGUgZmlyc3Qgb25lIGFuZCBjYWxsIGl0IGEgZGF5LlxuICAgICAgbGV0IGR1cmF0aW9uID0gTnVtYmVyKGdldENvbXB1dGVkU3R5bGUoZWwpLnRyYW5zaXRpb25EdXJhdGlvbi5yZXBsYWNlKC8sLiovLCAnJykucmVwbGFjZSgncycsICcnKSkgKiAxMDAwO1xuICAgICAgc3RhZ2VzLnNob3coKTtcbiAgICAgIHJlcXVlc3RBbmltYXRpb25GcmFtZSgoKSA9PiB7XG4gICAgICAgIHN0YWdlcy5lbmQoKTtcbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgICAgc3RhZ2VzLmhpZGUoKTsgLy8gQWRkaW5nIGFuIFwiaXNDb25uZWN0ZWRcIiBjaGVjaywgaW4gY2FzZSB0aGUgY2FsbGJhY2tcbiAgICAgICAgICAvLyByZW1vdmVkIHRoZSBlbGVtZW50IGZyb20gdGhlIERPTS5cblxuICAgICAgICAgIGlmIChlbC5pc0Nvbm5lY3RlZCkge1xuICAgICAgICAgICAgc3RhZ2VzLmNsZWFudXAoKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0sIGR1cmF0aW9uKTtcbiAgICAgIH0pO1xuICAgIH0pO1xuICB9XG4gIGZ1bmN0aW9uIGlzTnVtZXJpYyhzdWJqZWN0KSB7XG4gICAgcmV0dXJuICFpc05hTihzdWJqZWN0KTtcbiAgfVxuXG4gIGZ1bmN0aW9uIGhhbmRsZUZvckRpcmVjdGl2ZShjb21wb25lbnQsIHRlbXBsYXRlRWwsIGV4cHJlc3Npb24sIGluaXRpYWxVcGRhdGUsIGV4dHJhVmFycykge1xuICAgIHdhcm5JZk5vdFRlbXBsYXRlVGFnKHRlbXBsYXRlRWwpO1xuICAgIGxldCBpdGVyYXRvck5hbWVzID0gcGFyc2VGb3JFeHByZXNzaW9uKGV4cHJlc3Npb24pO1xuICAgIGxldCBpdGVtcyA9IGV2YWx1YXRlSXRlbXNBbmRSZXR1cm5FbXB0eUlmWElmSXNQcmVzZW50QW5kRmFsc2VPbkVsZW1lbnQoY29tcG9uZW50LCB0ZW1wbGF0ZUVsLCBpdGVyYXRvck5hbWVzLCBleHRyYVZhcnMpOyAvLyBBcyB3ZSB3YWxrIHRoZSBhcnJheSwgd2UnbGwgYWxzbyB3YWxrIHRoZSBET00gKHVwZGF0aW5nL2NyZWF0aW5nIGFzIHdlIGdvKS5cblxuICAgIGxldCBjdXJyZW50RWwgPSB0ZW1wbGF0ZUVsO1xuICAgIGl0ZW1zLmZvckVhY2goKGl0ZW0sIGluZGV4KSA9PiB7XG4gICAgICBsZXQgaXRlcmF0aW9uU2NvcGVWYXJpYWJsZXMgPSBnZXRJdGVyYXRpb25TY29wZVZhcmlhYmxlcyhpdGVyYXRvck5hbWVzLCBpdGVtLCBpbmRleCwgaXRlbXMsIGV4dHJhVmFycygpKTtcbiAgICAgIGxldCBjdXJyZW50S2V5ID0gZ2VuZXJhdGVLZXlGb3JJdGVyYXRpb24oY29tcG9uZW50LCB0ZW1wbGF0ZUVsLCBpbmRleCwgaXRlcmF0aW9uU2NvcGVWYXJpYWJsZXMpO1xuICAgICAgbGV0IG5leHRFbCA9IGN1cnJlbnRFbC5uZXh0RWxlbWVudFNpYmxpbmc7IC8vIElmIHRoZXJlJ3Mgbm8gcHJldmlvdXNseSB4LWZvciBwcm9jZXNzZWQgZWxlbWVudCBhaGVhZCwgYWRkIG9uZS5cblxuICAgICAgaWYgKCFuZXh0RWwgfHwgbmV4dEVsLl9feF9mb3Jfa2V5ID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgbmV4dEVsID0gYWRkRWxlbWVudEluTG9vcEFmdGVyQ3VycmVudEVsKHRlbXBsYXRlRWwsIGN1cnJlbnRFbCk7IC8vIEFuZCB0cmFuc2l0aW9uIGl0IGluIGlmIGl0J3Mgbm90IHRoZSBmaXJzdCBwYWdlIGxvYWQuXG5cbiAgICAgICAgdHJhbnNpdGlvbkluKG5leHRFbCwgKCkgPT4ge30sIGluaXRpYWxVcGRhdGUpO1xuICAgICAgICBuZXh0RWwuX194X2ZvciA9IGl0ZXJhdGlvblNjb3BlVmFyaWFibGVzO1xuICAgICAgICBjb21wb25lbnQuaW5pdGlhbGl6ZUVsZW1lbnRzKG5leHRFbCwgKCkgPT4gbmV4dEVsLl9feF9mb3IpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgbmV4dEVsID0gbG9va0FoZWFkRm9yTWF0Y2hpbmdLZXllZEVsZW1lbnRBbmRNb3ZlSXRJZkZvdW5kKG5leHRFbCwgY3VycmVudEtleSk7IC8vIElmIHdlIGhhdmVuJ3QgZm91bmQgYSBtYXRjaGluZyBrZXksIGp1c3QgaW5zZXJ0IHRoZSBlbGVtZW50IGF0IHRoZSBjdXJyZW50IHBvc2l0aW9uXG5cbiAgICAgICAgaWYgKCFuZXh0RWwpIHtcbiAgICAgICAgICBuZXh0RWwgPSBhZGRFbGVtZW50SW5Mb29wQWZ0ZXJDdXJyZW50RWwodGVtcGxhdGVFbCwgY3VycmVudEVsKTtcbiAgICAgICAgfSAvLyBUZW1wb3JhcmlseSByZW1vdmUgdGhlIGtleSBpbmRpY2F0b3IgdG8gYWxsb3cgdGhlIG5vcm1hbCBcInVwZGF0ZUVsZW1lbnRzXCIgdG8gd29ya1xuXG5cbiAgICAgICAgZGVsZXRlIG5leHRFbC5fX3hfZm9yX2tleTtcbiAgICAgICAgbmV4dEVsLl9feF9mb3IgPSBpdGVyYXRpb25TY29wZVZhcmlhYmxlcztcbiAgICAgICAgY29tcG9uZW50LnVwZGF0ZUVsZW1lbnRzKG5leHRFbCwgKCkgPT4gbmV4dEVsLl9feF9mb3IpO1xuICAgICAgfVxuXG4gICAgICBjdXJyZW50RWwgPSBuZXh0RWw7XG4gICAgICBjdXJyZW50RWwuX194X2Zvcl9rZXkgPSBjdXJyZW50S2V5O1xuICAgIH0pO1xuICAgIHJlbW92ZUFueUxlZnRPdmVyRWxlbWVudHNGcm9tUHJldmlvdXNVcGRhdGUoY3VycmVudEVsKTtcbiAgfSAvLyBUaGlzIHdhcyB0YWtlbiBmcm9tIFZ1ZUpTIDIuKiBjb3JlLiBUaGFua3MgVnVlIVxuXG4gIGZ1bmN0aW9uIHBhcnNlRm9yRXhwcmVzc2lvbihleHByZXNzaW9uKSB7XG4gICAgbGV0IGZvckl0ZXJhdG9yUkUgPSAvLChbXixcXH1cXF1dKikoPzosKFteLFxcfVxcXV0qKSk/JC87XG4gICAgbGV0IHN0cmlwUGFyZW5zUkUgPSAvXlxcKHxcXCkkL2c7XG4gICAgbGV0IGZvckFsaWFzUkUgPSAvKFtcXHNcXFNdKj8pXFxzKyg/OmlufG9mKVxccysoW1xcc1xcU10qKS87XG4gICAgbGV0IGluTWF0Y2ggPSBleHByZXNzaW9uLm1hdGNoKGZvckFsaWFzUkUpO1xuICAgIGlmICghaW5NYXRjaCkgcmV0dXJuO1xuICAgIGxldCByZXMgPSB7fTtcbiAgICByZXMuaXRlbXMgPSBpbk1hdGNoWzJdLnRyaW0oKTtcbiAgICBsZXQgaXRlbSA9IGluTWF0Y2hbMV0udHJpbSgpLnJlcGxhY2Uoc3RyaXBQYXJlbnNSRSwgJycpO1xuICAgIGxldCBpdGVyYXRvck1hdGNoID0gaXRlbS5tYXRjaChmb3JJdGVyYXRvclJFKTtcblxuICAgIGlmIChpdGVyYXRvck1hdGNoKSB7XG4gICAgICByZXMuaXRlbSA9IGl0ZW0ucmVwbGFjZShmb3JJdGVyYXRvclJFLCAnJykudHJpbSgpO1xuICAgICAgcmVzLmluZGV4ID0gaXRlcmF0b3JNYXRjaFsxXS50cmltKCk7XG5cbiAgICAgIGlmIChpdGVyYXRvck1hdGNoWzJdKSB7XG4gICAgICAgIHJlcy5jb2xsZWN0aW9uID0gaXRlcmF0b3JNYXRjaFsyXS50cmltKCk7XG4gICAgICB9XG4gICAgfSBlbHNlIHtcbiAgICAgIHJlcy5pdGVtID0gaXRlbTtcbiAgICB9XG5cbiAgICByZXR1cm4gcmVzO1xuICB9XG5cbiAgZnVuY3Rpb24gZ2V0SXRlcmF0aW9uU2NvcGVWYXJpYWJsZXMoaXRlcmF0b3JOYW1lcywgaXRlbSwgaW5kZXgsIGl0ZW1zLCBleHRyYVZhcnMpIHtcbiAgICAvLyBXZSBtdXN0IGNyZWF0ZSBhIG5ldyBvYmplY3QsIHNvIGVhY2ggaXRlcmF0aW9uIGhhcyBhIG5ldyBzY29wZVxuICAgIGxldCBzY29wZVZhcmlhYmxlcyA9IGV4dHJhVmFycyA/IF9vYmplY3RTcHJlYWQyKHt9LCBleHRyYVZhcnMpIDoge307XG4gICAgc2NvcGVWYXJpYWJsZXNbaXRlcmF0b3JOYW1lcy5pdGVtXSA9IGl0ZW07XG4gICAgaWYgKGl0ZXJhdG9yTmFtZXMuaW5kZXgpIHNjb3BlVmFyaWFibGVzW2l0ZXJhdG9yTmFtZXMuaW5kZXhdID0gaW5kZXg7XG4gICAgaWYgKGl0ZXJhdG9yTmFtZXMuY29sbGVjdGlvbikgc2NvcGVWYXJpYWJsZXNbaXRlcmF0b3JOYW1lcy5jb2xsZWN0aW9uXSA9IGl0ZW1zO1xuICAgIHJldHVybiBzY29wZVZhcmlhYmxlcztcbiAgfVxuXG4gIGZ1bmN0aW9uIGdlbmVyYXRlS2V5Rm9ySXRlcmF0aW9uKGNvbXBvbmVudCwgZWwsIGluZGV4LCBpdGVyYXRpb25TY29wZVZhcmlhYmxlcykge1xuICAgIGxldCBiaW5kS2V5QXR0cmlidXRlID0gZ2V0WEF0dHJzKGVsLCAnYmluZCcpLmZpbHRlcihhdHRyID0+IGF0dHIudmFsdWUgPT09ICdrZXknKVswXTsgLy8gSWYgdGhlIGRldiBoYXNuJ3Qgc3BlY2lmaWVkIGEga2V5LCBqdXN0IHJldHVybiB0aGUgaW5kZXggb2YgdGhlIGl0ZXJhdGlvbi5cblxuICAgIGlmICghYmluZEtleUF0dHJpYnV0ZSkgcmV0dXJuIGluZGV4O1xuICAgIHJldHVybiBjb21wb25lbnQuZXZhbHVhdGVSZXR1cm5FeHByZXNzaW9uKGVsLCBiaW5kS2V5QXR0cmlidXRlLmV4cHJlc3Npb24sICgpID0+IGl0ZXJhdGlvblNjb3BlVmFyaWFibGVzKTtcbiAgfVxuXG4gIGZ1bmN0aW9uIHdhcm5JZk5vdFRlbXBsYXRlVGFnKGVsKSB7XG4gICAgaWYgKGVsLnRhZ05hbWUudG9Mb3dlckNhc2UoKSAhPT0gJ3RlbXBsYXRlJykgY29uc29sZS53YXJuKCdBbHBpbmU6IFt4LWZvcl0gZGlyZWN0aXZlIHNob3VsZCBvbmx5IGJlIGFkZGVkIHRvIDx0ZW1wbGF0ZT4gdGFncy4nKTtcbiAgfVxuXG4gIGZ1bmN0aW9uIGV2YWx1YXRlSXRlbXNBbmRSZXR1cm5FbXB0eUlmWElmSXNQcmVzZW50QW5kRmFsc2VPbkVsZW1lbnQoY29tcG9uZW50LCBlbCwgaXRlcmF0b3JOYW1lcywgZXh0cmFWYXJzKSB7XG4gICAgbGV0IGlmQXR0cmlidXRlID0gZ2V0WEF0dHJzKGVsLCAnaWYnKVswXTtcblxuICAgIGlmIChpZkF0dHJpYnV0ZSAmJiAhY29tcG9uZW50LmV2YWx1YXRlUmV0dXJuRXhwcmVzc2lvbihlbCwgaWZBdHRyaWJ1dGUuZXhwcmVzc2lvbikpIHtcbiAgICAgIHJldHVybiBbXTtcbiAgICB9XG5cbiAgICByZXR1cm4gY29tcG9uZW50LmV2YWx1YXRlUmV0dXJuRXhwcmVzc2lvbihlbCwgaXRlcmF0b3JOYW1lcy5pdGVtcywgZXh0cmFWYXJzKTtcbiAgfVxuXG4gIGZ1bmN0aW9uIGFkZEVsZW1lbnRJbkxvb3BBZnRlckN1cnJlbnRFbCh0ZW1wbGF0ZUVsLCBjdXJyZW50RWwpIHtcbiAgICBsZXQgY2xvbmUgPSBkb2N1bWVudC5pbXBvcnROb2RlKHRlbXBsYXRlRWwuY29udGVudCwgdHJ1ZSk7XG4gICAgaWYgKGNsb25lLmNoaWxkRWxlbWVudENvdW50ICE9PSAxKSBjb25zb2xlLndhcm4oJ0FscGluZTogPHRlbXBsYXRlPiB0YWcgd2l0aCBbeC1mb3JdIGVuY291bnRlcmVkIHdpdGggbXVsdGlwbGUgZWxlbWVudCByb290cy4gTWFrZSBzdXJlIDx0ZW1wbGF0ZT4gb25seSBoYXMgYSBzaW5nbGUgY2hpbGQgbm9kZS4nKTtcbiAgICBjdXJyZW50RWwucGFyZW50RWxlbWVudC5pbnNlcnRCZWZvcmUoY2xvbmUsIGN1cnJlbnRFbC5uZXh0RWxlbWVudFNpYmxpbmcpO1xuICAgIHJldHVybiBjdXJyZW50RWwubmV4dEVsZW1lbnRTaWJsaW5nO1xuICB9XG5cbiAgZnVuY3Rpb24gbG9va0FoZWFkRm9yTWF0Y2hpbmdLZXllZEVsZW1lbnRBbmRNb3ZlSXRJZkZvdW5kKG5leHRFbCwgY3VycmVudEtleSkge1xuICAgIC8vIElmIHRoZSB0aGUga2V5J3MgRE8gbWF0Y2gsIG5vIG5lZWQgdG8gbG9vayBhaGVhZC5cbiAgICBpZiAobmV4dEVsLl9feF9mb3Jfa2V5ID09PSBjdXJyZW50S2V5KSByZXR1cm4gbmV4dEVsOyAvLyBJZiB0aGV5IGRvbid0LCB3ZSdsbCBsb29rIGFoZWFkIGZvciBhIG1hdGNoLlxuICAgIC8vIElmIHdlIGZpbmQgaXQsIHdlJ2xsIG1vdmUgaXQgdG8gdGhlIGN1cnJlbnQgcG9zaXRpb24gaW4gdGhlIGxvb3AuXG5cbiAgICBsZXQgdG1wTmV4dEVsID0gbmV4dEVsO1xuXG4gICAgd2hpbGUgKHRtcE5leHRFbCkge1xuICAgICAgaWYgKHRtcE5leHRFbC5fX3hfZm9yX2tleSA9PT0gY3VycmVudEtleSkge1xuICAgICAgICByZXR1cm4gdG1wTmV4dEVsLnBhcmVudEVsZW1lbnQuaW5zZXJ0QmVmb3JlKHRtcE5leHRFbCwgbmV4dEVsKTtcbiAgICAgIH1cblxuICAgICAgdG1wTmV4dEVsID0gdG1wTmV4dEVsLm5leHRFbGVtZW50U2libGluZyAmJiB0bXBOZXh0RWwubmV4dEVsZW1lbnRTaWJsaW5nLl9feF9mb3Jfa2V5ICE9PSB1bmRlZmluZWQgPyB0bXBOZXh0RWwubmV4dEVsZW1lbnRTaWJsaW5nIDogZmFsc2U7XG4gICAgfVxuICB9XG5cbiAgZnVuY3Rpb24gcmVtb3ZlQW55TGVmdE92ZXJFbGVtZW50c0Zyb21QcmV2aW91c1VwZGF0ZShjdXJyZW50RWwpIHtcbiAgICB2YXIgbmV4dEVsZW1lbnRGcm9tT2xkTG9vcCA9IGN1cnJlbnRFbC5uZXh0RWxlbWVudFNpYmxpbmcgJiYgY3VycmVudEVsLm5leHRFbGVtZW50U2libGluZy5fX3hfZm9yX2tleSAhPT0gdW5kZWZpbmVkID8gY3VycmVudEVsLm5leHRFbGVtZW50U2libGluZyA6IGZhbHNlO1xuXG4gICAgd2hpbGUgKG5leHRFbGVtZW50RnJvbU9sZExvb3ApIHtcbiAgICAgIGxldCBuZXh0RWxlbWVudEZyb21PbGRMb29wSW1tdXRhYmxlID0gbmV4dEVsZW1lbnRGcm9tT2xkTG9vcDtcbiAgICAgIGxldCBuZXh0U2libGluZyA9IG5leHRFbGVtZW50RnJvbU9sZExvb3AubmV4dEVsZW1lbnRTaWJsaW5nO1xuICAgICAgdHJhbnNpdGlvbk91dChuZXh0RWxlbWVudEZyb21PbGRMb29wLCAoKSA9PiB7XG4gICAgICAgIG5leHRFbGVtZW50RnJvbU9sZExvb3BJbW11dGFibGUucmVtb3ZlKCk7XG4gICAgICB9KTtcbiAgICAgIG5leHRFbGVtZW50RnJvbU9sZExvb3AgPSBuZXh0U2libGluZyAmJiBuZXh0U2libGluZy5fX3hfZm9yX2tleSAhPT0gdW5kZWZpbmVkID8gbmV4dFNpYmxpbmcgOiBmYWxzZTtcbiAgICB9XG4gIH1cblxuICBmdW5jdGlvbiBoYW5kbGVBdHRyaWJ1dGVCaW5kaW5nRGlyZWN0aXZlKGNvbXBvbmVudCwgZWwsIGF0dHJOYW1lLCBleHByZXNzaW9uLCBleHRyYVZhcnMsIGF0dHJUeXBlKSB7XG4gICAgdmFyIHZhbHVlID0gY29tcG9uZW50LmV2YWx1YXRlUmV0dXJuRXhwcmVzc2lvbihlbCwgZXhwcmVzc2lvbiwgZXh0cmFWYXJzKTtcblxuICAgIGlmIChhdHRyTmFtZSA9PT0gJ3ZhbHVlJykge1xuICAgICAgLy8gSWYgbmVzdGVkIG1vZGVsIGtleSBpcyB1bmRlZmluZWQsIHNldCB0aGUgZGVmYXVsdCB2YWx1ZSB0byBlbXB0eSBzdHJpbmcuXG4gICAgICBpZiAodmFsdWUgPT09IHVuZGVmaW5lZCAmJiBleHByZXNzaW9uLm1hdGNoKC9cXC4vKS5sZW5ndGgpIHtcbiAgICAgICAgdmFsdWUgPSAnJztcbiAgICAgIH1cblxuICAgICAgaWYgKGVsLnR5cGUgPT09ICdyYWRpbycpIHtcbiAgICAgICAgLy8gU2V0IHJhZGlvIHZhbHVlIGZyb20geC1iaW5kOnZhbHVlLCBpZiBubyBcInZhbHVlXCIgYXR0cmlidXRlIGV4aXN0cy5cbiAgICAgICAgLy8gSWYgdGhlcmUgYXJlIGFueSBpbml0aWFsIHN0YXRlIHZhbHVlcywgcmFkaW8gd2lsbCBoYXZlIGEgY29ycmVjdFxuICAgICAgICAvLyBcImNoZWNrZWRcIiB2YWx1ZSBzaW5jZSB4LWJpbmQ6dmFsdWUgaXMgcHJvY2Vzc2VkIGJlZm9yZSB4LW1vZGVsLlxuICAgICAgICBpZiAoZWwuYXR0cmlidXRlcy52YWx1ZSA9PT0gdW5kZWZpbmVkICYmIGF0dHJUeXBlID09PSAnYmluZCcpIHtcbiAgICAgICAgICBlbC52YWx1ZSA9IHZhbHVlO1xuICAgICAgICB9IGVsc2UgaWYgKGF0dHJUeXBlICE9PSAnYmluZCcpIHtcbiAgICAgICAgICBlbC5jaGVja2VkID0gZWwudmFsdWUgPT0gdmFsdWU7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAoZWwudHlwZSA9PT0gJ2NoZWNrYm94Jykge1xuICAgICAgICBpZiAoQXJyYXkuaXNBcnJheSh2YWx1ZSkpIHtcbiAgICAgICAgICAvLyBJJ20gcHVycG9zZWx5IG5vdCB1c2luZyBBcnJheS5pbmNsdWRlcyBoZXJlIGJlY2F1c2UgaXQnc1xuICAgICAgICAgIC8vIHN0cmljdCwgYW5kIGJlY2F1c2Ugb2YgTnVtZXJpYy9TdHJpbmcgbWlzLWNhc3RpbmcsIElcbiAgICAgICAgICAvLyB3YW50IHRoZSBcImluY2x1ZGVzXCIgdG8gYmUgXCJmdXp6eVwiLlxuICAgICAgICAgIGxldCB2YWx1ZUZvdW5kID0gZmFsc2U7XG4gICAgICAgICAgdmFsdWUuZm9yRWFjaCh2YWwgPT4ge1xuICAgICAgICAgICAgaWYgKHZhbCA9PSBlbC52YWx1ZSkge1xuICAgICAgICAgICAgICB2YWx1ZUZvdW5kID0gdHJ1ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9KTtcbiAgICAgICAgICBlbC5jaGVja2VkID0gdmFsdWVGb3VuZDtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBlbC5jaGVja2VkID0gISF2YWx1ZTtcbiAgICAgICAgfSAvLyBJZiB3ZSBhcmUgZXhwbGljaXRseSBiaW5kaW5nIGEgc3RyaW5nIHRvIHRoZSA6dmFsdWUsIHNldCB0aGUgc3RyaW5nLFxuICAgICAgICAvLyBJZiB0aGUgdmFsdWUgaXMgYSBib29sZWFuLCBsZWF2ZSBpdCBhbG9uZSwgaXQgd2lsbCBiZSBzZXQgdG8gXCJvblwiXG4gICAgICAgIC8vIGF1dG9tYXRpY2FsbHkuXG5cblxuICAgICAgICBpZiAodHlwZW9mIHZhbHVlID09PSAnc3RyaW5nJykge1xuICAgICAgICAgIGVsLnZhbHVlID0gdmFsdWU7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAoZWwudGFnTmFtZSA9PT0gJ1NFTEVDVCcpIHtcbiAgICAgICAgdXBkYXRlU2VsZWN0KGVsLCB2YWx1ZSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICAvLyBDdXJzb3IgcG9zaXRpb24gc2hvdWxkIGJlIHJlc3RvcmVkIGJhY2sgdG8gb3JpZ2luIGR1ZSB0byBhIHNhZmFyaSBidWdcbiAgICAgICAgY29uc3QgY3Vyc29yUG9zaXRpb24gPSBlbC5zZWxlY3Rpb25TdGFydDtcbiAgICAgICAgZWwudmFsdWUgPSB2YWx1ZTtcblxuICAgICAgICBpZiAoZWwgPT09IGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQpIHtcbiAgICAgICAgICBlbC5zZXRTZWxlY3Rpb25SYW5nZShjdXJzb3JQb3NpdGlvbiwgY3Vyc29yUG9zaXRpb24pO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfSBlbHNlIGlmIChhdHRyTmFtZSA9PT0gJ2NsYXNzJykge1xuICAgICAgaWYgKEFycmF5LmlzQXJyYXkodmFsdWUpKSB7XG4gICAgICAgIGNvbnN0IG9yaWdpbmFsQ2xhc3NlcyA9IGVsLl9feF9vcmlnaW5hbF9jbGFzc2VzIHx8IFtdO1xuICAgICAgICBlbC5zZXRBdHRyaWJ1dGUoJ2NsYXNzJywgYXJyYXlVbmlxdWUob3JpZ2luYWxDbGFzc2VzLmNvbmNhdCh2YWx1ZSkpLmpvaW4oJyAnKSk7XG4gICAgICB9IGVsc2UgaWYgKHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcpIHtcbiAgICAgICAgLy8gU29ydGluZyB0aGUga2V5cyAvIGNsYXNzIG5hbWVzIGJ5IHRoZWlyIGJvb2xlYW4gdmFsdWUgd2lsbCBlbnN1cmUgdGhhdFxuICAgICAgICAvLyBhbnl0aGluZyB0aGF0IGV2YWx1YXRlcyB0byBgZmFsc2VgIGFuZCBuZWVkcyB0byByZW1vdmUgY2xhc3NlcyBpcyBydW4gZmlyc3QuXG4gICAgICAgIGNvbnN0IGtleXNTb3J0ZWRCeUJvb2xlYW5WYWx1ZSA9IE9iamVjdC5rZXlzKHZhbHVlKS5zb3J0KChhLCBiKSA9PiB2YWx1ZVthXSAtIHZhbHVlW2JdKTtcbiAgICAgICAga2V5c1NvcnRlZEJ5Qm9vbGVhblZhbHVlLmZvckVhY2goY2xhc3NOYW1lcyA9PiB7XG4gICAgICAgICAgaWYgKHZhbHVlW2NsYXNzTmFtZXNdKSB7XG4gICAgICAgICAgICBjbGFzc05hbWVzLnNwbGl0KCcgJykuZm9yRWFjaChjbGFzc05hbWUgPT4gZWwuY2xhc3NMaXN0LmFkZChjbGFzc05hbWUpKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY2xhc3NOYW1lcy5zcGxpdCgnICcpLmZvckVhY2goY2xhc3NOYW1lID0+IGVsLmNsYXNzTGlzdC5yZW1vdmUoY2xhc3NOYW1lKSk7XG4gICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGNvbnN0IG9yaWdpbmFsQ2xhc3NlcyA9IGVsLl9feF9vcmlnaW5hbF9jbGFzc2VzIHx8IFtdO1xuICAgICAgICBjb25zdCBuZXdDbGFzc2VzID0gdmFsdWUuc3BsaXQoJyAnKTtcbiAgICAgICAgZWwuc2V0QXR0cmlidXRlKCdjbGFzcycsIGFycmF5VW5pcXVlKG9yaWdpbmFsQ2xhc3Nlcy5jb25jYXQobmV3Q2xhc3NlcykpLmpvaW4oJyAnKSk7XG4gICAgICB9XG4gICAgfSBlbHNlIGlmIChpc0Jvb2xlYW5BdHRyKGF0dHJOYW1lKSkge1xuICAgICAgLy8gQm9vbGVhbiBhdHRyaWJ1dGVzIGhhdmUgdG8gYmUgZXhwbGljaXRseSBhZGRlZCBhbmQgcmVtb3ZlZCwgbm90IGp1c3Qgc2V0LlxuICAgICAgaWYgKCEhdmFsdWUpIHtcbiAgICAgICAgZWwuc2V0QXR0cmlidXRlKGF0dHJOYW1lLCAnJyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBlbC5yZW1vdmVBdHRyaWJ1dGUoYXR0ck5hbWUpO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBlbC5zZXRBdHRyaWJ1dGUoYXR0ck5hbWUsIHZhbHVlKTtcbiAgICB9XG4gIH1cblxuICBmdW5jdGlvbiB1cGRhdGVTZWxlY3QoZWwsIHZhbHVlKSB7XG4gICAgY29uc3QgYXJyYXlXcmFwcGVkVmFsdWUgPSBbXS5jb25jYXQodmFsdWUpLm1hcCh2YWx1ZSA9PiB7XG4gICAgICByZXR1cm4gdmFsdWUgKyAnJztcbiAgICB9KTtcbiAgICBBcnJheS5mcm9tKGVsLm9wdGlvbnMpLmZvckVhY2gob3B0aW9uID0+IHtcbiAgICAgIG9wdGlvbi5zZWxlY3RlZCA9IGFycmF5V3JhcHBlZFZhbHVlLmluY2x1ZGVzKG9wdGlvbi52YWx1ZSB8fCBvcHRpb24udGV4dCk7XG4gICAgfSk7XG4gIH1cblxuICBmdW5jdGlvbiBoYW5kbGVUZXh0RGlyZWN0aXZlKGVsLCBvdXRwdXQsIGV4cHJlc3Npb24pIHtcbiAgICAvLyBJZiBuZXN0ZWQgbW9kZWwga2V5IGlzIHVuZGVmaW5lZCwgc2V0IHRoZSBkZWZhdWx0IHZhbHVlIHRvIGVtcHR5IHN0cmluZy5cbiAgICBpZiAob3V0cHV0ID09PSB1bmRlZmluZWQgJiYgZXhwcmVzc2lvbi5tYXRjaCgvXFwuLykubGVuZ3RoKSB7XG4gICAgICBvdXRwdXQgPSAnJztcbiAgICB9XG5cbiAgICBlbC5pbm5lclRleHQgPSBvdXRwdXQ7XG4gIH1cblxuICBmdW5jdGlvbiBoYW5kbGVIdG1sRGlyZWN0aXZlKGNvbXBvbmVudCwgZWwsIGV4cHJlc3Npb24sIGV4dHJhVmFycykge1xuICAgIGVsLmlubmVySFRNTCA9IGNvbXBvbmVudC5ldmFsdWF0ZVJldHVybkV4cHJlc3Npb24oZWwsIGV4cHJlc3Npb24sIGV4dHJhVmFycyk7XG4gIH1cblxuICBmdW5jdGlvbiBoYW5kbGVTaG93RGlyZWN0aXZlKGNvbXBvbmVudCwgZWwsIHZhbHVlLCBtb2RpZmllcnMsIGluaXRpYWxVcGRhdGUgPSBmYWxzZSkge1xuICAgIGNvbnN0IGhpZGUgPSAoKSA9PiB7XG4gICAgICBlbC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgIH07XG5cbiAgICBjb25zdCBzaG93ID0gKCkgPT4ge1xuICAgICAgaWYgKGVsLnN0eWxlLmxlbmd0aCA9PT0gMSAmJiBlbC5zdHlsZS5kaXNwbGF5ID09PSAnbm9uZScpIHtcbiAgICAgICAgZWwucmVtb3ZlQXR0cmlidXRlKCdzdHlsZScpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgZWwuc3R5bGUucmVtb3ZlUHJvcGVydHkoJ2Rpc3BsYXknKTtcbiAgICAgIH1cbiAgICB9O1xuXG4gICAgaWYgKGluaXRpYWxVcGRhdGUgPT09IHRydWUpIHtcbiAgICAgIGlmICh2YWx1ZSkge1xuICAgICAgICBzaG93KCk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBoaWRlKCk7XG4gICAgICB9XG5cbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICBjb25zdCBoYW5kbGUgPSByZXNvbHZlID0+IHtcbiAgICAgIGlmICghdmFsdWUpIHtcbiAgICAgICAgaWYgKGVsLnN0eWxlLmRpc3BsYXkgIT09ICdub25lJykge1xuICAgICAgICAgIHRyYW5zaXRpb25PdXQoZWwsICgpID0+IHtcbiAgICAgICAgICAgIHJlc29sdmUoKCkgPT4ge1xuICAgICAgICAgICAgICBoaWRlKCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICByZXNvbHZlKCgpID0+IHt9KTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIHtcbiAgICAgICAgaWYgKGVsLnN0eWxlLmRpc3BsYXkgIT09ICcnKSB7XG4gICAgICAgICAgdHJhbnNpdGlvbkluKGVsLCAoKSA9PiB7XG4gICAgICAgICAgICBzaG93KCk7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH0gLy8gUmVzb2x2ZSBpbW1lZGlhdGVseSwgb25seSBob2xkIHVwIHBhcmVudCBgeC1zaG93YHMgZm9yIGhpZGluLlxuXG5cbiAgICAgICAgcmVzb2x2ZSgoKSA9PiB7fSk7XG4gICAgICB9XG4gICAgfTsgLy8gVGhlIHdvcmtpbmcgb2YgeC1zaG93IGlzIGEgYml0IGNvbXBsZXggYmVjYXVzZSB3ZSBuZWVkIHRvXG4gICAgLy8gd2FpdCBmb3IgYW55IGNoaWxkIHRyYW5zaXRpb25zIHRvIGZpbmlzaCBiZWZvcmUgaGlkaW5nXG4gICAgLy8gc29tZSBlbGVtZW50LiBBbHNvLCB0aGlzIGhhcyB0byBiZSBkb25lIHJlY3Vyc2l2ZWx5LlxuICAgIC8vIElmIHgtc2hvdy5pbW1lZGlhdGUsIGZvcmVnb2UgdGhlIHdhaXRpbmcuXG5cblxuICAgIGlmIChtb2RpZmllcnMuaW5jbHVkZXMoJ2ltbWVkaWF0ZScpKSB7XG4gICAgICBoYW5kbGUoZmluaXNoID0+IGZpbmlzaCgpKTtcbiAgICAgIHJldHVybjtcbiAgICB9IC8vIHgtc2hvdyBpcyBlbmNvdW50ZXJlZCBkdXJpbmcgYSBET00gdHJlZSB3YWxrLiBJZiBhbiBlbGVtZW50XG4gICAgLy8gd2UgZW5jb3VudGVyIGlzIE5PVCBhIGNoaWxkIG9mIGFub3RoZXIgeC1zaG93IGVsZW1lbnQgd2VcbiAgICAvLyBjYW4gZXhlY3V0ZSB0aGUgcHJldmlvdXMgeC1zaG93IHN0YWNrIChpZiBvbmUgZXhpc3RzKS5cblxuXG4gICAgaWYgKGNvbXBvbmVudC5zaG93RGlyZWN0aXZlTGFzdEVsZW1lbnQgJiYgIWNvbXBvbmVudC5zaG93RGlyZWN0aXZlTGFzdEVsZW1lbnQuY29udGFpbnMoZWwpKSB7XG4gICAgICBjb21wb25lbnQuZXhlY3V0ZUFuZENsZWFyUmVtYWluaW5nU2hvd0RpcmVjdGl2ZVN0YWNrKCk7XG4gICAgfSAvLyBXZSdsbCBwdXNoIHRoZSBoYW5kbGVyIG9udG8gYSBzdGFjayB0byBiZSBoYW5kbGVkIGxhdGVyLlxuXG5cbiAgICBjb21wb25lbnQuc2hvd0RpcmVjdGl2ZVN0YWNrLnB1c2goaGFuZGxlKTtcbiAgICBjb21wb25lbnQuc2hvd0RpcmVjdGl2ZUxhc3RFbGVtZW50ID0gZWw7XG4gIH1cblxuICBmdW5jdGlvbiBoYW5kbGVJZkRpcmVjdGl2ZShjb21wb25lbnQsIGVsLCBleHByZXNzaW9uUmVzdWx0LCBpbml0aWFsVXBkYXRlLCBleHRyYVZhcnMpIHtcbiAgICBpZiAoZWwubm9kZU5hbWUudG9Mb3dlckNhc2UoKSAhPT0gJ3RlbXBsYXRlJykgY29uc29sZS53YXJuKGBBbHBpbmU6IFt4LWlmXSBkaXJlY3RpdmUgc2hvdWxkIG9ubHkgYmUgYWRkZWQgdG8gPHRlbXBsYXRlPiB0YWdzLiBTZWUgaHR0cHM6Ly9naXRodWIuY29tL2FscGluZWpzL2FscGluZSN4LWlmYCk7XG4gICAgY29uc3QgZWxlbWVudEhhc0FscmVhZHlCZWVuQWRkZWQgPSBlbC5uZXh0RWxlbWVudFNpYmxpbmcgJiYgZWwubmV4dEVsZW1lbnRTaWJsaW5nLl9feF9pbnNlcnRlZF9tZSA9PT0gdHJ1ZTtcblxuICAgIGlmIChleHByZXNzaW9uUmVzdWx0ICYmICFlbGVtZW50SGFzQWxyZWFkeUJlZW5BZGRlZCkge1xuICAgICAgY29uc3QgY2xvbmUgPSBkb2N1bWVudC5pbXBvcnROb2RlKGVsLmNvbnRlbnQsIHRydWUpO1xuICAgICAgZWwucGFyZW50RWxlbWVudC5pbnNlcnRCZWZvcmUoY2xvbmUsIGVsLm5leHRFbGVtZW50U2libGluZyk7XG4gICAgICB0cmFuc2l0aW9uSW4oZWwubmV4dEVsZW1lbnRTaWJsaW5nLCAoKSA9PiB7fSwgaW5pdGlhbFVwZGF0ZSk7XG4gICAgICBjb21wb25lbnQuaW5pdGlhbGl6ZUVsZW1lbnRzKGVsLm5leHRFbGVtZW50U2libGluZywgZXh0cmFWYXJzKTtcbiAgICAgIGVsLm5leHRFbGVtZW50U2libGluZy5fX3hfaW5zZXJ0ZWRfbWUgPSB0cnVlO1xuICAgIH0gZWxzZSBpZiAoIWV4cHJlc3Npb25SZXN1bHQgJiYgZWxlbWVudEhhc0FscmVhZHlCZWVuQWRkZWQpIHtcbiAgICAgIHRyYW5zaXRpb25PdXQoZWwubmV4dEVsZW1lbnRTaWJsaW5nLCAoKSA9PiB7XG4gICAgICAgIGVsLm5leHRFbGVtZW50U2libGluZy5yZW1vdmUoKTtcbiAgICAgIH0sIGluaXRpYWxVcGRhdGUpO1xuICAgIH1cbiAgfVxuXG4gIGZ1bmN0aW9uIHJlZ2lzdGVyTGlzdGVuZXIoY29tcG9uZW50LCBlbCwgZXZlbnQsIG1vZGlmaWVycywgZXhwcmVzc2lvbiwgZXh0cmFWYXJzID0ge30pIHtcbiAgICBpZiAobW9kaWZpZXJzLmluY2x1ZGVzKCdhd2F5JykpIHtcbiAgICAgIGxldCBoYW5kbGVyID0gZSA9PiB7XG4gICAgICAgIC8vIERvbid0IGRvIGFueXRoaW5nIGlmIHRoZSBjbGljayBjYW1lIGZvcm0gdGhlIGVsZW1lbnQgb3Igd2l0aGluIGl0LlxuICAgICAgICBpZiAoZWwuY29udGFpbnMoZS50YXJnZXQpKSByZXR1cm47IC8vIERvbid0IGRvIGFueXRoaW5nIGlmIHRoaXMgZWxlbWVudCBpc24ndCBjdXJyZW50bHkgdmlzaWJsZS5cblxuICAgICAgICBpZiAoZWwub2Zmc2V0V2lkdGggPCAxICYmIGVsLm9mZnNldEhlaWdodCA8IDEpIHJldHVybjsgLy8gTm93IHRoYXQgd2UgYXJlIHN1cmUgdGhlIGVsZW1lbnQgaXMgdmlzaWJsZSwgQU5EIHRoZSBjbGlja1xuICAgICAgICAvLyBpcyBmcm9tIG91dHNpZGUgaXQsIGxldCdzIHJ1biB0aGUgZXhwcmVzc2lvbi5cblxuICAgICAgICBydW5MaXN0ZW5lckhhbmRsZXIoY29tcG9uZW50LCBleHByZXNzaW9uLCBlLCBleHRyYVZhcnMpO1xuXG4gICAgICAgIGlmIChtb2RpZmllcnMuaW5jbHVkZXMoJ29uY2UnKSkge1xuICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGhhbmRsZXIpO1xuICAgICAgICB9XG4gICAgICB9OyAvLyBMaXN0ZW4gZm9yIHRoaXMgZXZlbnQgYXQgdGhlIHJvb3QgbGV2ZWwuXG5cblxuICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudCwgaGFuZGxlcik7XG4gICAgfSBlbHNlIHtcbiAgICAgIGxldCBsaXN0ZW5lclRhcmdldCA9IG1vZGlmaWVycy5pbmNsdWRlcygnd2luZG93JykgPyB3aW5kb3cgOiBtb2RpZmllcnMuaW5jbHVkZXMoJ2RvY3VtZW50JykgPyBkb2N1bWVudCA6IGVsO1xuXG4gICAgICBsZXQgaGFuZGxlciA9IGUgPT4ge1xuICAgICAgICAvLyBSZW1vdmUgdGhpcyBnbG9iYWwgZXZlbnQgaGFuZGxlciBpZiB0aGUgZWxlbWVudCB0aGF0IGRlY2xhcmVkIGl0XG4gICAgICAgIC8vIGhhcyBiZWVuIHJlbW92ZWQuIEl0J3Mgbm93IHN0YWxlLlxuICAgICAgICBpZiAobGlzdGVuZXJUYXJnZXQgPT09IHdpbmRvdyB8fCBsaXN0ZW5lclRhcmdldCA9PT0gZG9jdW1lbnQpIHtcbiAgICAgICAgICBpZiAoIWRvY3VtZW50LmJvZHkuY29udGFpbnMoZWwpKSB7XG4gICAgICAgICAgICBsaXN0ZW5lclRhcmdldC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBoYW5kbGVyKTtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoaXNLZXlFdmVudChldmVudCkpIHtcbiAgICAgICAgICBpZiAoaXNMaXN0ZW5pbmdGb3JBU3BlY2lmaWNLZXlUaGF0SGFzbnRCZWVuUHJlc3NlZChlLCBtb2RpZmllcnMpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaWYgKG1vZGlmaWVycy5pbmNsdWRlcygncHJldmVudCcpKSBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGlmIChtb2RpZmllcnMuaW5jbHVkZXMoJ3N0b3AnKSkgZS5zdG9wUHJvcGFnYXRpb24oKTsgLy8gSWYgdGhlIC5zZWxmIG1vZGlmaWVyIGlzbid0IHByZXNlbnQsIG9yIGlmIGl0IGlzIHByZXNlbnQgYW5kXG4gICAgICAgIC8vIHRoZSB0YXJnZXQgZWxlbWVudCBtYXRjaGVzIHRoZSBlbGVtZW50IHdlIGFyZSByZWdpc3RlcmluZyB0aGVcbiAgICAgICAgLy8gZXZlbnQgb24sIHJ1biB0aGUgaGFuZGxlclxuXG4gICAgICAgIGlmICghbW9kaWZpZXJzLmluY2x1ZGVzKCdzZWxmJykgfHwgZS50YXJnZXQgPT09IGVsKSB7XG4gICAgICAgICAgY29uc3QgcmV0dXJuVmFsdWUgPSBydW5MaXN0ZW5lckhhbmRsZXIoY29tcG9uZW50LCBleHByZXNzaW9uLCBlLCBleHRyYVZhcnMpO1xuXG4gICAgICAgICAgaWYgKHJldHVyblZhbHVlID09PSBmYWxzZSkge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBpZiAobW9kaWZpZXJzLmluY2x1ZGVzKCdvbmNlJykpIHtcbiAgICAgICAgICAgICAgbGlzdGVuZXJUYXJnZXQucmVtb3ZlRXZlbnRMaXN0ZW5lcihldmVudCwgaGFuZGxlcik7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9O1xuXG4gICAgICBpZiAobW9kaWZpZXJzLmluY2x1ZGVzKCdkZWJvdW5jZScpKSB7XG4gICAgICAgIGxldCBuZXh0TW9kaWZpZXIgPSBtb2RpZmllcnNbbW9kaWZpZXJzLmluZGV4T2YoJ2RlYm91bmNlJykgKyAxXSB8fCAnaW52YWxpZC13YWl0JztcbiAgICAgICAgbGV0IHdhaXQgPSBpc051bWVyaWMobmV4dE1vZGlmaWVyLnNwbGl0KCdtcycpWzBdKSA/IE51bWJlcihuZXh0TW9kaWZpZXIuc3BsaXQoJ21zJylbMF0pIDogMjUwO1xuICAgICAgICBoYW5kbGVyID0gZGVib3VuY2UoaGFuZGxlciwgd2FpdCk7XG4gICAgICB9XG5cbiAgICAgIGxpc3RlbmVyVGFyZ2V0LmFkZEV2ZW50TGlzdGVuZXIoZXZlbnQsIGhhbmRsZXIpO1xuICAgIH1cbiAgfVxuXG4gIGZ1bmN0aW9uIHJ1bkxpc3RlbmVySGFuZGxlcihjb21wb25lbnQsIGV4cHJlc3Npb24sIGUsIGV4dHJhVmFycykge1xuICAgIHJldHVybiBjb21wb25lbnQuZXZhbHVhdGVDb21tYW5kRXhwcmVzc2lvbihlLnRhcmdldCwgZXhwcmVzc2lvbiwgKCkgPT4ge1xuICAgICAgcmV0dXJuIF9vYmplY3RTcHJlYWQyKHt9LCBleHRyYVZhcnMoKSwge1xuICAgICAgICAnJGV2ZW50JzogZVxuICAgICAgfSk7XG4gICAgfSk7XG4gIH1cblxuICBmdW5jdGlvbiBpc0tleUV2ZW50KGV2ZW50KSB7XG4gICAgcmV0dXJuIFsna2V5ZG93bicsICdrZXl1cCddLmluY2x1ZGVzKGV2ZW50KTtcbiAgfVxuXG4gIGZ1bmN0aW9uIGlzTGlzdGVuaW5nRm9yQVNwZWNpZmljS2V5VGhhdEhhc250QmVlblByZXNzZWQoZSwgbW9kaWZpZXJzKSB7XG4gICAgbGV0IGtleU1vZGlmaWVycyA9IG1vZGlmaWVycy5maWx0ZXIoaSA9PiB7XG4gICAgICByZXR1cm4gIVsnd2luZG93JywgJ2RvY3VtZW50JywgJ3ByZXZlbnQnLCAnc3RvcCddLmluY2x1ZGVzKGkpO1xuICAgIH0pO1xuXG4gICAgaWYgKGtleU1vZGlmaWVycy5pbmNsdWRlcygnZGVib3VuY2UnKSkge1xuICAgICAgbGV0IGRlYm91bmNlSW5kZXggPSBrZXlNb2RpZmllcnMuaW5kZXhPZignZGVib3VuY2UnKTtcbiAgICAgIGtleU1vZGlmaWVycy5zcGxpY2UoZGVib3VuY2VJbmRleCwgaXNOdW1lcmljKChrZXlNb2RpZmllcnNbZGVib3VuY2VJbmRleCArIDFdIHx8ICdpbnZhbGlkLXdhaXQnKS5zcGxpdCgnbXMnKVswXSkgPyAyIDogMSk7XG4gICAgfSAvLyBJZiBubyBtb2RpZmllciBpcyBzcGVjaWZpZWQsIHdlJ2xsIGNhbGwgaXQgYSBwcmVzcy5cblxuXG4gICAgaWYgKGtleU1vZGlmaWVycy5sZW5ndGggPT09IDApIHJldHVybiBmYWxzZTsgLy8gSWYgb25lIGlzIHBhc3NlZCwgQU5EIGl0IG1hdGNoZXMgdGhlIGtleSBwcmVzc2VkLCB3ZSdsbCBjYWxsIGl0IGEgcHJlc3MuXG5cbiAgICBpZiAoa2V5TW9kaWZpZXJzLmxlbmd0aCA9PT0gMSAmJiBrZXlNb2RpZmllcnNbMF0gPT09IGtleVRvTW9kaWZpZXIoZS5rZXkpKSByZXR1cm4gZmFsc2U7IC8vIFRoZSB1c2VyIGlzIGxpc3RlbmluZyBmb3Iga2V5IGNvbWJpbmF0aW9ucy5cblxuICAgIGNvbnN0IHN5c3RlbUtleU1vZGlmaWVycyA9IFsnY3RybCcsICdzaGlmdCcsICdhbHQnLCAnbWV0YScsICdjbWQnLCAnc3VwZXInXTtcbiAgICBjb25zdCBzZWxlY3RlZFN5c3RlbUtleU1vZGlmaWVycyA9IHN5c3RlbUtleU1vZGlmaWVycy5maWx0ZXIobW9kaWZpZXIgPT4ga2V5TW9kaWZpZXJzLmluY2x1ZGVzKG1vZGlmaWVyKSk7XG4gICAga2V5TW9kaWZpZXJzID0ga2V5TW9kaWZpZXJzLmZpbHRlcihpID0+ICFzZWxlY3RlZFN5c3RlbUtleU1vZGlmaWVycy5pbmNsdWRlcyhpKSk7XG5cbiAgICBpZiAoc2VsZWN0ZWRTeXN0ZW1LZXlNb2RpZmllcnMubGVuZ3RoID4gMCkge1xuICAgICAgY29uc3QgYWN0aXZlbHlQcmVzc2VkS2V5TW9kaWZpZXJzID0gc2VsZWN0ZWRTeXN0ZW1LZXlNb2RpZmllcnMuZmlsdGVyKG1vZGlmaWVyID0+IHtcbiAgICAgICAgLy8gQWxpYXMgXCJjbWRcIiBhbmQgXCJzdXBlclwiIHRvIFwibWV0YVwiXG4gICAgICAgIGlmIChtb2RpZmllciA9PT0gJ2NtZCcgfHwgbW9kaWZpZXIgPT09ICdzdXBlcicpIG1vZGlmaWVyID0gJ21ldGEnO1xuICAgICAgICByZXR1cm4gZVtgJHttb2RpZmllcn1LZXlgXTtcbiAgICAgIH0pOyAvLyBJZiBhbGwgdGhlIG1vZGlmaWVycyBzZWxlY3RlZCBhcmUgcHJlc3NlZCwgLi4uXG5cbiAgICAgIGlmIChhY3RpdmVseVByZXNzZWRLZXlNb2RpZmllcnMubGVuZ3RoID09PSBzZWxlY3RlZFN5c3RlbUtleU1vZGlmaWVycy5sZW5ndGgpIHtcbiAgICAgICAgLy8gQU5EIHRoZSByZW1haW5pbmcga2V5IGlzIHByZXNzZWQgYXMgd2VsbC4gSXQncyBhIHByZXNzLlxuICAgICAgICBpZiAoa2V5TW9kaWZpZXJzWzBdID09PSBrZXlUb01vZGlmaWVyKGUua2V5KSkgcmV0dXJuIGZhbHNlO1xuICAgICAgfVxuICAgIH0gLy8gV2UnbGwgY2FsbCBpdCBOT1QgYSB2YWxpZCBrZXlwcmVzcy5cblxuXG4gICAgcmV0dXJuIHRydWU7XG4gIH1cblxuICBmdW5jdGlvbiBrZXlUb01vZGlmaWVyKGtleSkge1xuICAgIHN3aXRjaCAoa2V5KSB7XG4gICAgICBjYXNlICcvJzpcbiAgICAgICAgcmV0dXJuICdzbGFzaCc7XG5cbiAgICAgIGNhc2UgJyAnOlxuICAgICAgY2FzZSAnU3BhY2ViYXInOlxuICAgICAgICByZXR1cm4gJ3NwYWNlJztcblxuICAgICAgZGVmYXVsdDpcbiAgICAgICAgcmV0dXJuIGtleSAmJiBrZWJhYkNhc2Uoa2V5KTtcbiAgICB9XG4gIH1cblxuICBmdW5jdGlvbiByZWdpc3Rlck1vZGVsTGlzdGVuZXIoY29tcG9uZW50LCBlbCwgbW9kaWZpZXJzLCBleHByZXNzaW9uLCBleHRyYVZhcnMpIHtcbiAgICAvLyBJZiB0aGUgZWxlbWVudCB3ZSBhcmUgYmluZGluZyB0byBpcyBhIHNlbGVjdCwgYSByYWRpbywgb3IgY2hlY2tib3hcbiAgICAvLyB3ZSdsbCBsaXN0ZW4gZm9yIHRoZSBjaGFuZ2UgZXZlbnQgaW5zdGVhZCBvZiB0aGUgXCJpbnB1dFwiIGV2ZW50LlxuICAgIHZhciBldmVudCA9IGVsLnRhZ05hbWUudG9Mb3dlckNhc2UoKSA9PT0gJ3NlbGVjdCcgfHwgWydjaGVja2JveCcsICdyYWRpbyddLmluY2x1ZGVzKGVsLnR5cGUpIHx8IG1vZGlmaWVycy5pbmNsdWRlcygnbGF6eScpID8gJ2NoYW5nZScgOiAnaW5wdXQnO1xuICAgIGNvbnN0IGxpc3RlbmVyRXhwcmVzc2lvbiA9IGAke2V4cHJlc3Npb259ID0gcmlnaHRTaWRlT2ZFeHByZXNzaW9uKCRldmVudCwgJHtleHByZXNzaW9ufSlgO1xuICAgIHJlZ2lzdGVyTGlzdGVuZXIoY29tcG9uZW50LCBlbCwgZXZlbnQsIG1vZGlmaWVycywgbGlzdGVuZXJFeHByZXNzaW9uLCAoKSA9PiB7XG4gICAgICByZXR1cm4gX29iamVjdFNwcmVhZDIoe30sIGV4dHJhVmFycygpLCB7XG4gICAgICAgIHJpZ2h0U2lkZU9mRXhwcmVzc2lvbjogZ2VuZXJhdGVNb2RlbEFzc2lnbm1lbnRGdW5jdGlvbihlbCwgbW9kaWZpZXJzLCBleHByZXNzaW9uKVxuICAgICAgfSk7XG4gICAgfSk7XG4gIH1cblxuICBmdW5jdGlvbiBnZW5lcmF0ZU1vZGVsQXNzaWdubWVudEZ1bmN0aW9uKGVsLCBtb2RpZmllcnMsIGV4cHJlc3Npb24pIHtcbiAgICBpZiAoZWwudHlwZSA9PT0gJ3JhZGlvJykge1xuICAgICAgLy8gUmFkaW8gYnV0dG9ucyBvbmx5IHdvcmsgcHJvcGVybHkgd2hlbiB0aGV5IHNoYXJlIGEgbmFtZSBhdHRyaWJ1dGUuXG4gICAgICAvLyBQZW9wbGUgbWlnaHQgYXNzdW1lIHdlIHRha2UgY2FyZSBvZiB0aGF0IGZvciB0aGVtLCBiZWNhdXNlXG4gICAgICAvLyB0aGV5IGFscmVhZHkgc2V0IGEgc2hhcmVkIFwieC1tb2RlbFwiIGF0dHJpYnV0ZS5cbiAgICAgIGlmICghZWwuaGFzQXR0cmlidXRlKCduYW1lJykpIGVsLnNldEF0dHJpYnV0ZSgnbmFtZScsIGV4cHJlc3Npb24pO1xuICAgIH1cblxuICAgIHJldHVybiAoZXZlbnQsIGN1cnJlbnRWYWx1ZSkgPT4ge1xuICAgICAgLy8gQ2hlY2sgZm9yIGV2ZW50LmRldGFpbCBkdWUgdG8gYW4gaXNzdWUgd2hlcmUgSUUxMSBoYW5kbGVzIG90aGVyIGV2ZW50cyBhcyBhIEN1c3RvbUV2ZW50LlxuICAgICAgaWYgKGV2ZW50IGluc3RhbmNlb2YgQ3VzdG9tRXZlbnQgJiYgZXZlbnQuZGV0YWlsKSB7XG4gICAgICAgIHJldHVybiBldmVudC5kZXRhaWw7XG4gICAgICB9IGVsc2UgaWYgKGVsLnR5cGUgPT09ICdjaGVja2JveCcpIHtcbiAgICAgICAgLy8gSWYgdGhlIGRhdGEgd2UgYXJlIGJpbmRpbmcgdG8gaXMgYW4gYXJyYXksIHRvZ2dsZSBpdCdzIHZhbHVlIGluc2lkZSB0aGUgYXJyYXkuXG4gICAgICAgIGlmIChBcnJheS5pc0FycmF5KGN1cnJlbnRWYWx1ZSkpIHtcbiAgICAgICAgICByZXR1cm4gZXZlbnQudGFyZ2V0LmNoZWNrZWQgPyBjdXJyZW50VmFsdWUuY29uY2F0KFtldmVudC50YXJnZXQudmFsdWVdKSA6IGN1cnJlbnRWYWx1ZS5maWx0ZXIoaSA9PiBpICE9PSBldmVudC50YXJnZXQudmFsdWUpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHJldHVybiBldmVudC50YXJnZXQuY2hlY2tlZDtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmIChlbC50YWdOYW1lLnRvTG93ZXJDYXNlKCkgPT09ICdzZWxlY3QnICYmIGVsLm11bHRpcGxlKSB7XG4gICAgICAgIHJldHVybiBtb2RpZmllcnMuaW5jbHVkZXMoJ251bWJlcicpID8gQXJyYXkuZnJvbShldmVudC50YXJnZXQuc2VsZWN0ZWRPcHRpb25zKS5tYXAob3B0aW9uID0+IHtcbiAgICAgICAgICBjb25zdCByYXdWYWx1ZSA9IG9wdGlvbi52YWx1ZSB8fCBvcHRpb24udGV4dDtcbiAgICAgICAgICBjb25zdCBudW1iZXIgPSByYXdWYWx1ZSA/IHBhcnNlRmxvYXQocmF3VmFsdWUpIDogbnVsbDtcbiAgICAgICAgICByZXR1cm4gaXNOYU4obnVtYmVyKSA/IHJhd1ZhbHVlIDogbnVtYmVyO1xuICAgICAgICB9KSA6IEFycmF5LmZyb20oZXZlbnQudGFyZ2V0LnNlbGVjdGVkT3B0aW9ucykubWFwKG9wdGlvbiA9PiB7XG4gICAgICAgICAgcmV0dXJuIG9wdGlvbi52YWx1ZSB8fCBvcHRpb24udGV4dDtcbiAgICAgICAgfSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBjb25zdCByYXdWYWx1ZSA9IGV2ZW50LnRhcmdldC52YWx1ZTtcbiAgICAgICAgY29uc3QgbnVtYmVyID0gcmF3VmFsdWUgPyBwYXJzZUZsb2F0KHJhd1ZhbHVlKSA6IG51bGw7XG4gICAgICAgIHJldHVybiBtb2RpZmllcnMuaW5jbHVkZXMoJ251bWJlcicpID8gaXNOYU4obnVtYmVyKSA/IHJhd1ZhbHVlIDogbnVtYmVyIDogbW9kaWZpZXJzLmluY2x1ZGVzKCd0cmltJykgPyByYXdWYWx1ZS50cmltKCkgOiByYXdWYWx1ZTtcbiAgICAgIH1cbiAgICB9O1xuICB9XG5cbiAgLyoqXG4gICAqIENvcHlyaWdodCAoQykgMjAxNyBzYWxlc2ZvcmNlLmNvbSwgaW5jLlxuICAgKi9cbiAgY29uc3QgeyBpc0FycmF5IH0gPSBBcnJheTtcbiAgY29uc3QgeyBnZXRQcm90b3R5cGVPZiwgY3JlYXRlOiBPYmplY3RDcmVhdGUsIGRlZmluZVByb3BlcnR5OiBPYmplY3REZWZpbmVQcm9wZXJ0eSwgZGVmaW5lUHJvcGVydGllczogT2JqZWN0RGVmaW5lUHJvcGVydGllcywgaXNFeHRlbnNpYmxlLCBnZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3IsIGdldE93blByb3BlcnR5TmFtZXMsIGdldE93blByb3BlcnR5U3ltYm9scywgcHJldmVudEV4dGVuc2lvbnMsIGhhc093blByb3BlcnR5LCB9ID0gT2JqZWN0O1xuICBjb25zdCB7IHB1c2g6IEFycmF5UHVzaCwgY29uY2F0OiBBcnJheUNvbmNhdCwgbWFwOiBBcnJheU1hcCwgfSA9IEFycmF5LnByb3RvdHlwZTtcbiAgZnVuY3Rpb24gaXNVbmRlZmluZWQob2JqKSB7XG4gICAgICByZXR1cm4gb2JqID09PSB1bmRlZmluZWQ7XG4gIH1cbiAgZnVuY3Rpb24gaXNGdW5jdGlvbihvYmopIHtcbiAgICAgIHJldHVybiB0eXBlb2Ygb2JqID09PSAnZnVuY3Rpb24nO1xuICB9XG4gIGZ1bmN0aW9uIGlzT2JqZWN0KG9iaikge1xuICAgICAgcmV0dXJuIHR5cGVvZiBvYmogPT09ICdvYmplY3QnO1xuICB9XG4gIGNvbnN0IHByb3h5VG9WYWx1ZU1hcCA9IG5ldyBXZWFrTWFwKCk7XG4gIGZ1bmN0aW9uIHJlZ2lzdGVyUHJveHkocHJveHksIHZhbHVlKSB7XG4gICAgICBwcm94eVRvVmFsdWVNYXAuc2V0KHByb3h5LCB2YWx1ZSk7XG4gIH1cbiAgY29uc3QgdW53cmFwID0gKHJlcGxpY2FPckFueSkgPT4gcHJveHlUb1ZhbHVlTWFwLmdldChyZXBsaWNhT3JBbnkpIHx8IHJlcGxpY2FPckFueTtcblxuICBmdW5jdGlvbiB3cmFwVmFsdWUobWVtYnJhbmUsIHZhbHVlKSB7XG4gICAgICByZXR1cm4gbWVtYnJhbmUudmFsdWVJc09ic2VydmFibGUodmFsdWUpID8gbWVtYnJhbmUuZ2V0UHJveHkodmFsdWUpIDogdmFsdWU7XG4gIH1cbiAgLyoqXG4gICAqIFVud3JhcCBwcm9wZXJ0eSBkZXNjcmlwdG9ycyB3aWxsIHNldCB2YWx1ZSBvbiBvcmlnaW5hbCBkZXNjcmlwdG9yXG4gICAqIFdlIG9ubHkgbmVlZCB0byB1bndyYXAgaWYgdmFsdWUgaXMgc3BlY2lmaWVkXG4gICAqIEBwYXJhbSBkZXNjcmlwdG9yIGV4dGVybmFsIGRlc2NycGl0b3IgcHJvdmlkZWQgdG8gZGVmaW5lIG5ldyBwcm9wZXJ0eSBvbiBvcmlnaW5hbCB2YWx1ZVxuICAgKi9cbiAgZnVuY3Rpb24gdW53cmFwRGVzY3JpcHRvcihkZXNjcmlwdG9yKSB7XG4gICAgICBpZiAoaGFzT3duUHJvcGVydHkuY2FsbChkZXNjcmlwdG9yLCAndmFsdWUnKSkge1xuICAgICAgICAgIGRlc2NyaXB0b3IudmFsdWUgPSB1bndyYXAoZGVzY3JpcHRvci52YWx1ZSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gZGVzY3JpcHRvcjtcbiAgfVxuICBmdW5jdGlvbiBsb2NrU2hhZG93VGFyZ2V0KG1lbWJyYW5lLCBzaGFkb3dUYXJnZXQsIG9yaWdpbmFsVGFyZ2V0KSB7XG4gICAgICBjb25zdCB0YXJnZXRLZXlzID0gQXJyYXlDb25jYXQuY2FsbChnZXRPd25Qcm9wZXJ0eU5hbWVzKG9yaWdpbmFsVGFyZ2V0KSwgZ2V0T3duUHJvcGVydHlTeW1ib2xzKG9yaWdpbmFsVGFyZ2V0KSk7XG4gICAgICB0YXJnZXRLZXlzLmZvckVhY2goKGtleSkgPT4ge1xuICAgICAgICAgIGxldCBkZXNjcmlwdG9yID0gZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKG9yaWdpbmFsVGFyZ2V0LCBrZXkpO1xuICAgICAgICAgIC8vIFdlIGRvIG5vdCBuZWVkIHRvIHdyYXAgdGhlIGRlc2NyaXB0b3IgaWYgY29uZmlndXJhYmxlXG4gICAgICAgICAgLy8gQmVjYXVzZSB3ZSBjYW4gZGVhbCB3aXRoIHdyYXBwaW5nIGl0IHdoZW4gdXNlciBnb2VzIHRocm91Z2hcbiAgICAgICAgICAvLyBHZXQgb3duIHByb3BlcnR5IGRlc2NyaXB0b3IuIFRoZXJlIGlzIGFsc28gYSBjaGFuY2UgdGhhdCB0aGlzIGRlc2NyaXB0b3JcbiAgICAgICAgICAvLyBjb3VsZCBjaGFuZ2Ugc29tZXRpbWUgaW4gdGhlIGZ1dHVyZSwgc28gd2UgY2FuIGRlZmVyIHdyYXBwaW5nXG4gICAgICAgICAgLy8gdW50aWwgd2UgbmVlZCB0b1xuICAgICAgICAgIGlmICghZGVzY3JpcHRvci5jb25maWd1cmFibGUpIHtcbiAgICAgICAgICAgICAgZGVzY3JpcHRvciA9IHdyYXBEZXNjcmlwdG9yKG1lbWJyYW5lLCBkZXNjcmlwdG9yLCB3cmFwVmFsdWUpO1xuICAgICAgICAgIH1cbiAgICAgICAgICBPYmplY3REZWZpbmVQcm9wZXJ0eShzaGFkb3dUYXJnZXQsIGtleSwgZGVzY3JpcHRvcik7XG4gICAgICB9KTtcbiAgICAgIHByZXZlbnRFeHRlbnNpb25zKHNoYWRvd1RhcmdldCk7XG4gIH1cbiAgY2xhc3MgUmVhY3RpdmVQcm94eUhhbmRsZXIge1xuICAgICAgY29uc3RydWN0b3IobWVtYnJhbmUsIHZhbHVlKSB7XG4gICAgICAgICAgdGhpcy5vcmlnaW5hbFRhcmdldCA9IHZhbHVlO1xuICAgICAgICAgIHRoaXMubWVtYnJhbmUgPSBtZW1icmFuZTtcbiAgICAgIH1cbiAgICAgIGdldChzaGFkb3dUYXJnZXQsIGtleSkge1xuICAgICAgICAgIGNvbnN0IHsgb3JpZ2luYWxUYXJnZXQsIG1lbWJyYW5lIH0gPSB0aGlzO1xuICAgICAgICAgIGNvbnN0IHZhbHVlID0gb3JpZ2luYWxUYXJnZXRba2V5XTtcbiAgICAgICAgICBjb25zdCB7IHZhbHVlT2JzZXJ2ZWQgfSA9IG1lbWJyYW5lO1xuICAgICAgICAgIHZhbHVlT2JzZXJ2ZWQob3JpZ2luYWxUYXJnZXQsIGtleSk7XG4gICAgICAgICAgcmV0dXJuIG1lbWJyYW5lLmdldFByb3h5KHZhbHVlKTtcbiAgICAgIH1cbiAgICAgIHNldChzaGFkb3dUYXJnZXQsIGtleSwgdmFsdWUpIHtcbiAgICAgICAgICBjb25zdCB7IG9yaWdpbmFsVGFyZ2V0LCBtZW1icmFuZTogeyB2YWx1ZU11dGF0ZWQgfSB9ID0gdGhpcztcbiAgICAgICAgICBjb25zdCBvbGRWYWx1ZSA9IG9yaWdpbmFsVGFyZ2V0W2tleV07XG4gICAgICAgICAgaWYgKG9sZFZhbHVlICE9PSB2YWx1ZSkge1xuICAgICAgICAgICAgICBvcmlnaW5hbFRhcmdldFtrZXldID0gdmFsdWU7XG4gICAgICAgICAgICAgIHZhbHVlTXV0YXRlZChvcmlnaW5hbFRhcmdldCwga2V5KTtcbiAgICAgICAgICB9XG4gICAgICAgICAgZWxzZSBpZiAoa2V5ID09PSAnbGVuZ3RoJyAmJiBpc0FycmF5KG9yaWdpbmFsVGFyZ2V0KSkge1xuICAgICAgICAgICAgICAvLyBmaXggZm9yIGlzc3VlICMyMzY6IHB1c2ggd2lsbCBhZGQgdGhlIG5ldyBpbmRleCwgYW5kIGJ5IHRoZSB0aW1lIGxlbmd0aFxuICAgICAgICAgICAgICAvLyBpcyB1cGRhdGVkLCB0aGUgaW50ZXJuYWwgbGVuZ3RoIGlzIGFscmVhZHkgZXF1YWwgdG8gdGhlIG5ldyBsZW5ndGggdmFsdWVcbiAgICAgICAgICAgICAgLy8gdGhlcmVmb3JlLCB0aGUgb2xkVmFsdWUgaXMgZXF1YWwgdG8gdGhlIHZhbHVlLiBUaGlzIGlzIHRoZSBmb3JraW5nIGxvZ2ljXG4gICAgICAgICAgICAgIC8vIHRvIHN1cHBvcnQgdGhpcyB1c2UgY2FzZS5cbiAgICAgICAgICAgICAgdmFsdWVNdXRhdGVkKG9yaWdpbmFsVGFyZ2V0LCBrZXkpO1xuICAgICAgICAgIH1cbiAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgIH1cbiAgICAgIGRlbGV0ZVByb3BlcnR5KHNoYWRvd1RhcmdldCwga2V5KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCwgbWVtYnJhbmU6IHsgdmFsdWVNdXRhdGVkIH0gfSA9IHRoaXM7XG4gICAgICAgICAgZGVsZXRlIG9yaWdpbmFsVGFyZ2V0W2tleV07XG4gICAgICAgICAgdmFsdWVNdXRhdGVkKG9yaWdpbmFsVGFyZ2V0LCBrZXkpO1xuICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgfVxuICAgICAgYXBwbHkoc2hhZG93VGFyZ2V0LCB0aGlzQXJnLCBhcmdBcnJheSkge1xuICAgICAgICAgIC8qIE5vIG9wICovXG4gICAgICB9XG4gICAgICBjb25zdHJ1Y3QodGFyZ2V0LCBhcmdBcnJheSwgbmV3VGFyZ2V0KSB7XG4gICAgICAgICAgLyogTm8gb3AgKi9cbiAgICAgIH1cbiAgICAgIGhhcyhzaGFkb3dUYXJnZXQsIGtleSkge1xuICAgICAgICAgIGNvbnN0IHsgb3JpZ2luYWxUYXJnZXQsIG1lbWJyYW5lOiB7IHZhbHVlT2JzZXJ2ZWQgfSB9ID0gdGhpcztcbiAgICAgICAgICB2YWx1ZU9ic2VydmVkKG9yaWdpbmFsVGFyZ2V0LCBrZXkpO1xuICAgICAgICAgIHJldHVybiBrZXkgaW4gb3JpZ2luYWxUYXJnZXQ7XG4gICAgICB9XG4gICAgICBvd25LZXlzKHNoYWRvd1RhcmdldCkge1xuICAgICAgICAgIGNvbnN0IHsgb3JpZ2luYWxUYXJnZXQgfSA9IHRoaXM7XG4gICAgICAgICAgcmV0dXJuIEFycmF5Q29uY2F0LmNhbGwoZ2V0T3duUHJvcGVydHlOYW1lcyhvcmlnaW5hbFRhcmdldCksIGdldE93blByb3BlcnR5U3ltYm9scyhvcmlnaW5hbFRhcmdldCkpO1xuICAgICAgfVxuICAgICAgaXNFeHRlbnNpYmxlKHNoYWRvd1RhcmdldCkge1xuICAgICAgICAgIGNvbnN0IHNoYWRvd0lzRXh0ZW5zaWJsZSA9IGlzRXh0ZW5zaWJsZShzaGFkb3dUYXJnZXQpO1xuICAgICAgICAgIGlmICghc2hhZG93SXNFeHRlbnNpYmxlKSB7XG4gICAgICAgICAgICAgIHJldHVybiBzaGFkb3dJc0V4dGVuc2libGU7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNvbnN0IHsgb3JpZ2luYWxUYXJnZXQsIG1lbWJyYW5lIH0gPSB0aGlzO1xuICAgICAgICAgIGNvbnN0IHRhcmdldElzRXh0ZW5zaWJsZSA9IGlzRXh0ZW5zaWJsZShvcmlnaW5hbFRhcmdldCk7XG4gICAgICAgICAgaWYgKCF0YXJnZXRJc0V4dGVuc2libGUpIHtcbiAgICAgICAgICAgICAgbG9ja1NoYWRvd1RhcmdldChtZW1icmFuZSwgc2hhZG93VGFyZ2V0LCBvcmlnaW5hbFRhcmdldCk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiB0YXJnZXRJc0V4dGVuc2libGU7XG4gICAgICB9XG4gICAgICBzZXRQcm90b3R5cGVPZihzaGFkb3dUYXJnZXQsIHByb3RvdHlwZSkge1xuICAgICAgfVxuICAgICAgZ2V0UHJvdG90eXBlT2Yoc2hhZG93VGFyZ2V0KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCB9ID0gdGhpcztcbiAgICAgICAgICByZXR1cm4gZ2V0UHJvdG90eXBlT2Yob3JpZ2luYWxUYXJnZXQpO1xuICAgICAgfVxuICAgICAgZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKHNoYWRvd1RhcmdldCwga2V5KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCwgbWVtYnJhbmUgfSA9IHRoaXM7XG4gICAgICAgICAgY29uc3QgeyB2YWx1ZU9ic2VydmVkIH0gPSB0aGlzLm1lbWJyYW5lO1xuICAgICAgICAgIC8vIGtleXMgbG9va2VkIHVwIHZpYSBoYXNPd25Qcm9wZXJ0eSBuZWVkIHRvIGJlIHJlYWN0aXZlXG4gICAgICAgICAgdmFsdWVPYnNlcnZlZChvcmlnaW5hbFRhcmdldCwga2V5KTtcbiAgICAgICAgICBsZXQgZGVzYyA9IGdldE93blByb3BlcnR5RGVzY3JpcHRvcihvcmlnaW5hbFRhcmdldCwga2V5KTtcbiAgICAgICAgICBpZiAoaXNVbmRlZmluZWQoZGVzYykpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIGRlc2M7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNvbnN0IHNoYWRvd0Rlc2NyaXB0b3IgPSBnZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Ioc2hhZG93VGFyZ2V0LCBrZXkpO1xuICAgICAgICAgIGlmICghaXNVbmRlZmluZWQoc2hhZG93RGVzY3JpcHRvcikpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIHNoYWRvd0Rlc2NyaXB0b3I7XG4gICAgICAgICAgfVxuICAgICAgICAgIC8vIE5vdGU6IGJ5IGFjY2Vzc2luZyB0aGUgZGVzY3JpcHRvciwgdGhlIGtleSBpcyBtYXJrZWQgYXMgb2JzZXJ2ZWRcbiAgICAgICAgICAvLyBidXQgYWNjZXNzIHRvIHRoZSB2YWx1ZSwgc2V0dGVyIG9yIGdldHRlciAoaWYgYXZhaWxhYmxlKSBjYW5ub3Qgb2JzZXJ2ZVxuICAgICAgICAgIC8vIG11dGF0aW9ucywganVzdCBsaWtlIHJlZ3VsYXIgbWV0aG9kcywgaW4gd2hpY2ggY2FzZSB3ZSBqdXN0IGRvIG5vdGhpbmcuXG4gICAgICAgICAgZGVzYyA9IHdyYXBEZXNjcmlwdG9yKG1lbWJyYW5lLCBkZXNjLCB3cmFwVmFsdWUpO1xuICAgICAgICAgIGlmICghZGVzYy5jb25maWd1cmFibGUpIHtcbiAgICAgICAgICAgICAgLy8gSWYgZGVzY3JpcHRvciBmcm9tIG9yaWdpbmFsIHRhcmdldCBpcyBub3QgY29uZmlndXJhYmxlLFxuICAgICAgICAgICAgICAvLyBXZSBtdXN0IGNvcHkgdGhlIHdyYXBwZWQgZGVzY3JpcHRvciBvdmVyIHRvIHRoZSBzaGFkb3cgdGFyZ2V0LlxuICAgICAgICAgICAgICAvLyBPdGhlcndpc2UsIHByb3h5IHdpbGwgdGhyb3cgYW4gaW52YXJpYW50IGVycm9yLlxuICAgICAgICAgICAgICAvLyBUaGlzIGlzIG91ciBsYXN0IGNoYW5jZSB0byBsb2NrIHRoZSB2YWx1ZS5cbiAgICAgICAgICAgICAgLy8gaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvSmF2YVNjcmlwdC9SZWZlcmVuY2UvR2xvYmFsX09iamVjdHMvUHJveHkvaGFuZGxlci9nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3IjSW52YXJpYW50c1xuICAgICAgICAgICAgICBPYmplY3REZWZpbmVQcm9wZXJ0eShzaGFkb3dUYXJnZXQsIGtleSwgZGVzYyk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBkZXNjO1xuICAgICAgfVxuICAgICAgcHJldmVudEV4dGVuc2lvbnMoc2hhZG93VGFyZ2V0KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCwgbWVtYnJhbmUgfSA9IHRoaXM7XG4gICAgICAgICAgbG9ja1NoYWRvd1RhcmdldChtZW1icmFuZSwgc2hhZG93VGFyZ2V0LCBvcmlnaW5hbFRhcmdldCk7XG4gICAgICAgICAgcHJldmVudEV4dGVuc2lvbnMob3JpZ2luYWxUYXJnZXQpO1xuICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgfVxuICAgICAgZGVmaW5lUHJvcGVydHkoc2hhZG93VGFyZ2V0LCBrZXksIGRlc2NyaXB0b3IpIHtcbiAgICAgICAgICBjb25zdCB7IG9yaWdpbmFsVGFyZ2V0LCBtZW1icmFuZSB9ID0gdGhpcztcbiAgICAgICAgICBjb25zdCB7IHZhbHVlTXV0YXRlZCB9ID0gbWVtYnJhbmU7XG4gICAgICAgICAgY29uc3QgeyBjb25maWd1cmFibGUgfSA9IGRlc2NyaXB0b3I7XG4gICAgICAgICAgLy8gV2UgaGF2ZSB0byBjaGVjayBmb3IgdmFsdWUgaW4gZGVzY3JpcHRvclxuICAgICAgICAgIC8vIGJlY2F1c2UgT2JqZWN0LmZyZWV6ZShwcm94eSkgY2FsbHMgdGhpcyBtZXRob2RcbiAgICAgICAgICAvLyB3aXRoIG9ubHkgeyBjb25maWd1cmFibGU6IGZhbHNlLCB3cml0ZWFibGU6IGZhbHNlIH1cbiAgICAgICAgICAvLyBBZGRpdGlvbmFsbHksIG1ldGhvZCB3aWxsIG9ubHkgYmUgY2FsbGVkIHdpdGggd3JpdGVhYmxlOmZhbHNlXG4gICAgICAgICAgLy8gaWYgdGhlIGRlc2NyaXB0b3IgaGFzIGEgdmFsdWUsIGFzIG9wcG9zZWQgdG8gZ2V0dGVyL3NldHRlclxuICAgICAgICAgIC8vIFNvIHdlIGNhbiBqdXN0IGNoZWNrIGlmIHdyaXRhYmxlIGlzIHByZXNlbnQgYW5kIHRoZW4gc2VlIGlmXG4gICAgICAgICAgLy8gdmFsdWUgaXMgcHJlc2VudC4gVGhpcyBlbGltaW5hdGVzIGdldHRlciBhbmQgc2V0dGVyIGRlc2NyaXB0b3JzXG4gICAgICAgICAgaWYgKGhhc093blByb3BlcnR5LmNhbGwoZGVzY3JpcHRvciwgJ3dyaXRhYmxlJykgJiYgIWhhc093blByb3BlcnR5LmNhbGwoZGVzY3JpcHRvciwgJ3ZhbHVlJykpIHtcbiAgICAgICAgICAgICAgY29uc3Qgb3JpZ2luYWxEZXNjcmlwdG9yID0gZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKG9yaWdpbmFsVGFyZ2V0LCBrZXkpO1xuICAgICAgICAgICAgICBkZXNjcmlwdG9yLnZhbHVlID0gb3JpZ2luYWxEZXNjcmlwdG9yLnZhbHVlO1xuICAgICAgICAgIH1cbiAgICAgICAgICBPYmplY3REZWZpbmVQcm9wZXJ0eShvcmlnaW5hbFRhcmdldCwga2V5LCB1bndyYXBEZXNjcmlwdG9yKGRlc2NyaXB0b3IpKTtcbiAgICAgICAgICBpZiAoY29uZmlndXJhYmxlID09PSBmYWxzZSkge1xuICAgICAgICAgICAgICBPYmplY3REZWZpbmVQcm9wZXJ0eShzaGFkb3dUYXJnZXQsIGtleSwgd3JhcERlc2NyaXB0b3IobWVtYnJhbmUsIGRlc2NyaXB0b3IsIHdyYXBWYWx1ZSkpO1xuICAgICAgICAgIH1cbiAgICAgICAgICB2YWx1ZU11dGF0ZWQob3JpZ2luYWxUYXJnZXQsIGtleSk7XG4gICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICB9XG4gIH1cblxuICBmdW5jdGlvbiB3cmFwUmVhZE9ubHlWYWx1ZShtZW1icmFuZSwgdmFsdWUpIHtcbiAgICAgIHJldHVybiBtZW1icmFuZS52YWx1ZUlzT2JzZXJ2YWJsZSh2YWx1ZSkgPyBtZW1icmFuZS5nZXRSZWFkT25seVByb3h5KHZhbHVlKSA6IHZhbHVlO1xuICB9XG4gIGNsYXNzIFJlYWRPbmx5SGFuZGxlciB7XG4gICAgICBjb25zdHJ1Y3RvcihtZW1icmFuZSwgdmFsdWUpIHtcbiAgICAgICAgICB0aGlzLm9yaWdpbmFsVGFyZ2V0ID0gdmFsdWU7XG4gICAgICAgICAgdGhpcy5tZW1icmFuZSA9IG1lbWJyYW5lO1xuICAgICAgfVxuICAgICAgZ2V0KHNoYWRvd1RhcmdldCwga2V5KSB7XG4gICAgICAgICAgY29uc3QgeyBtZW1icmFuZSwgb3JpZ2luYWxUYXJnZXQgfSA9IHRoaXM7XG4gICAgICAgICAgY29uc3QgdmFsdWUgPSBvcmlnaW5hbFRhcmdldFtrZXldO1xuICAgICAgICAgIGNvbnN0IHsgdmFsdWVPYnNlcnZlZCB9ID0gbWVtYnJhbmU7XG4gICAgICAgICAgdmFsdWVPYnNlcnZlZChvcmlnaW5hbFRhcmdldCwga2V5KTtcbiAgICAgICAgICByZXR1cm4gbWVtYnJhbmUuZ2V0UmVhZE9ubHlQcm94eSh2YWx1ZSk7XG4gICAgICB9XG4gICAgICBzZXQoc2hhZG93VGFyZ2V0LCBrZXksIHZhbHVlKSB7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgfVxuICAgICAgZGVsZXRlUHJvcGVydHkoc2hhZG93VGFyZ2V0LCBrZXkpIHtcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9XG4gICAgICBhcHBseShzaGFkb3dUYXJnZXQsIHRoaXNBcmcsIGFyZ0FycmF5KSB7XG4gICAgICAgICAgLyogTm8gb3AgKi9cbiAgICAgIH1cbiAgICAgIGNvbnN0cnVjdCh0YXJnZXQsIGFyZ0FycmF5LCBuZXdUYXJnZXQpIHtcbiAgICAgICAgICAvKiBObyBvcCAqL1xuICAgICAgfVxuICAgICAgaGFzKHNoYWRvd1RhcmdldCwga2V5KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCwgbWVtYnJhbmU6IHsgdmFsdWVPYnNlcnZlZCB9IH0gPSB0aGlzO1xuICAgICAgICAgIHZhbHVlT2JzZXJ2ZWQob3JpZ2luYWxUYXJnZXQsIGtleSk7XG4gICAgICAgICAgcmV0dXJuIGtleSBpbiBvcmlnaW5hbFRhcmdldDtcbiAgICAgIH1cbiAgICAgIG93bktleXMoc2hhZG93VGFyZ2V0KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCB9ID0gdGhpcztcbiAgICAgICAgICByZXR1cm4gQXJyYXlDb25jYXQuY2FsbChnZXRPd25Qcm9wZXJ0eU5hbWVzKG9yaWdpbmFsVGFyZ2V0KSwgZ2V0T3duUHJvcGVydHlTeW1ib2xzKG9yaWdpbmFsVGFyZ2V0KSk7XG4gICAgICB9XG4gICAgICBzZXRQcm90b3R5cGVPZihzaGFkb3dUYXJnZXQsIHByb3RvdHlwZSkge1xuICAgICAgfVxuICAgICAgZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKHNoYWRvd1RhcmdldCwga2V5KSB7XG4gICAgICAgICAgY29uc3QgeyBvcmlnaW5hbFRhcmdldCwgbWVtYnJhbmUgfSA9IHRoaXM7XG4gICAgICAgICAgY29uc3QgeyB2YWx1ZU9ic2VydmVkIH0gPSBtZW1icmFuZTtcbiAgICAgICAgICAvLyBrZXlzIGxvb2tlZCB1cCB2aWEgaGFzT3duUHJvcGVydHkgbmVlZCB0byBiZSByZWFjdGl2ZVxuICAgICAgICAgIHZhbHVlT2JzZXJ2ZWQob3JpZ2luYWxUYXJnZXQsIGtleSk7XG4gICAgICAgICAgbGV0IGRlc2MgPSBnZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Iob3JpZ2luYWxUYXJnZXQsIGtleSk7XG4gICAgICAgICAgaWYgKGlzVW5kZWZpbmVkKGRlc2MpKSB7XG4gICAgICAgICAgICAgIHJldHVybiBkZXNjO1xuICAgICAgICAgIH1cbiAgICAgICAgICBjb25zdCBzaGFkb3dEZXNjcmlwdG9yID0gZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKHNoYWRvd1RhcmdldCwga2V5KTtcbiAgICAgICAgICBpZiAoIWlzVW5kZWZpbmVkKHNoYWRvd0Rlc2NyaXB0b3IpKSB7XG4gICAgICAgICAgICAgIHJldHVybiBzaGFkb3dEZXNjcmlwdG9yO1xuICAgICAgICAgIH1cbiAgICAgICAgICAvLyBOb3RlOiBieSBhY2Nlc3NpbmcgdGhlIGRlc2NyaXB0b3IsIHRoZSBrZXkgaXMgbWFya2VkIGFzIG9ic2VydmVkXG4gICAgICAgICAgLy8gYnV0IGFjY2VzcyB0byB0aGUgdmFsdWUgb3IgZ2V0dGVyIChpZiBhdmFpbGFibGUpIGNhbm5vdCBiZSBvYnNlcnZlZCxcbiAgICAgICAgICAvLyBqdXN0IGxpa2UgcmVndWxhciBtZXRob2RzLCBpbiB3aGljaCBjYXNlIHdlIGp1c3QgZG8gbm90aGluZy5cbiAgICAgICAgICBkZXNjID0gd3JhcERlc2NyaXB0b3IobWVtYnJhbmUsIGRlc2MsIHdyYXBSZWFkT25seVZhbHVlKTtcbiAgICAgICAgICBpZiAoaGFzT3duUHJvcGVydHkuY2FsbChkZXNjLCAnc2V0JykpIHtcbiAgICAgICAgICAgICAgZGVzYy5zZXQgPSB1bmRlZmluZWQ7IC8vIHJlYWRPbmx5IG1lbWJyYW5lIGRvZXMgbm90IGFsbG93IHNldHRlcnNcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYgKCFkZXNjLmNvbmZpZ3VyYWJsZSkge1xuICAgICAgICAgICAgICAvLyBJZiBkZXNjcmlwdG9yIGZyb20gb3JpZ2luYWwgdGFyZ2V0IGlzIG5vdCBjb25maWd1cmFibGUsXG4gICAgICAgICAgICAgIC8vIFdlIG11c3QgY29weSB0aGUgd3JhcHBlZCBkZXNjcmlwdG9yIG92ZXIgdG8gdGhlIHNoYWRvdyB0YXJnZXQuXG4gICAgICAgICAgICAgIC8vIE90aGVyd2lzZSwgcHJveHkgd2lsbCB0aHJvdyBhbiBpbnZhcmlhbnQgZXJyb3IuXG4gICAgICAgICAgICAgIC8vIFRoaXMgaXMgb3VyIGxhc3QgY2hhbmNlIHRvIGxvY2sgdGhlIHZhbHVlLlxuICAgICAgICAgICAgICAvLyBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9KYXZhU2NyaXB0L1JlZmVyZW5jZS9HbG9iYWxfT2JqZWN0cy9Qcm94eS9oYW5kbGVyL2dldE93blByb3BlcnR5RGVzY3JpcHRvciNJbnZhcmlhbnRzXG4gICAgICAgICAgICAgIE9iamVjdERlZmluZVByb3BlcnR5KHNoYWRvd1RhcmdldCwga2V5LCBkZXNjKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIGRlc2M7XG4gICAgICB9XG4gICAgICBwcmV2ZW50RXh0ZW5zaW9ucyhzaGFkb3dUYXJnZXQpIHtcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9XG4gICAgICBkZWZpbmVQcm9wZXJ0eShzaGFkb3dUYXJnZXQsIGtleSwgZGVzY3JpcHRvcikge1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgIH1cbiAgfVxuICBmdW5jdGlvbiBjcmVhdGVTaGFkb3dUYXJnZXQodmFsdWUpIHtcbiAgICAgIGxldCBzaGFkb3dUYXJnZXQgPSB1bmRlZmluZWQ7XG4gICAgICBpZiAoaXNBcnJheSh2YWx1ZSkpIHtcbiAgICAgICAgICBzaGFkb3dUYXJnZXQgPSBbXTtcbiAgICAgIH1cbiAgICAgIGVsc2UgaWYgKGlzT2JqZWN0KHZhbHVlKSkge1xuICAgICAgICAgIHNoYWRvd1RhcmdldCA9IHt9O1xuICAgICAgfVxuICAgICAgcmV0dXJuIHNoYWRvd1RhcmdldDtcbiAgfVxuICBjb25zdCBPYmplY3REb3RQcm90b3R5cGUgPSBPYmplY3QucHJvdG90eXBlO1xuICBmdW5jdGlvbiBkZWZhdWx0VmFsdWVJc09ic2VydmFibGUodmFsdWUpIHtcbiAgICAgIC8vIGludGVudGlvbmFsbHkgY2hlY2tpbmcgZm9yIG51bGxcbiAgICAgIGlmICh2YWx1ZSA9PT0gbnVsbCkge1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgIH1cbiAgICAgIC8vIHRyZWF0IGFsbCBub24tb2JqZWN0IHR5cGVzLCBpbmNsdWRpbmcgdW5kZWZpbmVkLCBhcyBub24tb2JzZXJ2YWJsZSB2YWx1ZXNcbiAgICAgIGlmICh0eXBlb2YgdmFsdWUgIT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgfVxuICAgICAgaWYgKGlzQXJyYXkodmFsdWUpKSB7XG4gICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICB9XG4gICAgICBjb25zdCBwcm90byA9IGdldFByb3RvdHlwZU9mKHZhbHVlKTtcbiAgICAgIHJldHVybiAocHJvdG8gPT09IE9iamVjdERvdFByb3RvdHlwZSB8fCBwcm90byA9PT0gbnVsbCB8fCBnZXRQcm90b3R5cGVPZihwcm90bykgPT09IG51bGwpO1xuICB9XG4gIGNvbnN0IGRlZmF1bHRWYWx1ZU9ic2VydmVkID0gKG9iaiwga2V5KSA9PiB7XG4gICAgICAvKiBkbyBub3RoaW5nICovXG4gIH07XG4gIGNvbnN0IGRlZmF1bHRWYWx1ZU11dGF0ZWQgPSAob2JqLCBrZXkpID0+IHtcbiAgICAgIC8qIGRvIG5vdGhpbmcgKi9cbiAgfTtcbiAgY29uc3QgZGVmYXVsdFZhbHVlRGlzdG9ydGlvbiA9ICh2YWx1ZSkgPT4gdmFsdWU7XG4gIGZ1bmN0aW9uIHdyYXBEZXNjcmlwdG9yKG1lbWJyYW5lLCBkZXNjcmlwdG9yLCBnZXRWYWx1ZSkge1xuICAgICAgY29uc3QgeyBzZXQsIGdldCB9ID0gZGVzY3JpcHRvcjtcbiAgICAgIGlmIChoYXNPd25Qcm9wZXJ0eS5jYWxsKGRlc2NyaXB0b3IsICd2YWx1ZScpKSB7XG4gICAgICAgICAgZGVzY3JpcHRvci52YWx1ZSA9IGdldFZhbHVlKG1lbWJyYW5lLCBkZXNjcmlwdG9yLnZhbHVlKTtcbiAgICAgIH1cbiAgICAgIGVsc2Uge1xuICAgICAgICAgIGlmICghaXNVbmRlZmluZWQoZ2V0KSkge1xuICAgICAgICAgICAgICBkZXNjcmlwdG9yLmdldCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgIC8vIGludm9raW5nIHRoZSBvcmlnaW5hbCBnZXR0ZXIgd2l0aCB0aGUgb3JpZ2luYWwgdGFyZ2V0XG4gICAgICAgICAgICAgICAgICByZXR1cm4gZ2V0VmFsdWUobWVtYnJhbmUsIGdldC5jYWxsKHVud3JhcCh0aGlzKSkpO1xuICAgICAgICAgICAgICB9O1xuICAgICAgICAgIH1cbiAgICAgICAgICBpZiAoIWlzVW5kZWZpbmVkKHNldCkpIHtcbiAgICAgICAgICAgICAgZGVzY3JpcHRvci5zZXQgPSBmdW5jdGlvbiAodmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgIC8vIEF0IHRoaXMgcG9pbnQgd2UgZG9uJ3QgaGF2ZSBhIGNsZWFyIGluZGljYXRpb24gb2Ygd2hldGhlclxuICAgICAgICAgICAgICAgICAgLy8gb3Igbm90IGEgdmFsaWQgbXV0YXRpb24gd2lsbCBvY2N1ciwgd2UgZG9uJ3QgaGF2ZSB0aGUga2V5LFxuICAgICAgICAgICAgICAgICAgLy8gYW5kIHdlIGFyZSBub3Qgc3VyZSB3aHkgYW5kIGhvdyB0aGV5IGFyZSBpbnZva2luZyB0aGlzIHNldHRlci5cbiAgICAgICAgICAgICAgICAgIC8vIE5ldmVydGhlbGVzcyB3ZSBwcmVzZXJ2ZSB0aGUgb3JpZ2luYWwgc2VtYW50aWNzIGJ5IGludm9raW5nIHRoZVxuICAgICAgICAgICAgICAgICAgLy8gb3JpZ2luYWwgc2V0dGVyIHdpdGggdGhlIG9yaWdpbmFsIHRhcmdldCBhbmQgdGhlIHVud3JhcHBlZCB2YWx1ZVxuICAgICAgICAgICAgICAgICAgc2V0LmNhbGwodW53cmFwKHRoaXMpLCBtZW1icmFuZS51bndyYXBQcm94eSh2YWx1ZSkpO1xuICAgICAgICAgICAgICB9O1xuICAgICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBkZXNjcmlwdG9yO1xuICB9XG4gIGNsYXNzIFJlYWN0aXZlTWVtYnJhbmUge1xuICAgICAgY29uc3RydWN0b3Iob3B0aW9ucykge1xuICAgICAgICAgIHRoaXMudmFsdWVEaXN0b3J0aW9uID0gZGVmYXVsdFZhbHVlRGlzdG9ydGlvbjtcbiAgICAgICAgICB0aGlzLnZhbHVlTXV0YXRlZCA9IGRlZmF1bHRWYWx1ZU11dGF0ZWQ7XG4gICAgICAgICAgdGhpcy52YWx1ZU9ic2VydmVkID0gZGVmYXVsdFZhbHVlT2JzZXJ2ZWQ7XG4gICAgICAgICAgdGhpcy52YWx1ZUlzT2JzZXJ2YWJsZSA9IGRlZmF1bHRWYWx1ZUlzT2JzZXJ2YWJsZTtcbiAgICAgICAgICB0aGlzLm9iamVjdEdyYXBoID0gbmV3IFdlYWtNYXAoKTtcbiAgICAgICAgICBpZiAoIWlzVW5kZWZpbmVkKG9wdGlvbnMpKSB7XG4gICAgICAgICAgICAgIGNvbnN0IHsgdmFsdWVEaXN0b3J0aW9uLCB2YWx1ZU11dGF0ZWQsIHZhbHVlT2JzZXJ2ZWQsIHZhbHVlSXNPYnNlcnZhYmxlIH0gPSBvcHRpb25zO1xuICAgICAgICAgICAgICB0aGlzLnZhbHVlRGlzdG9ydGlvbiA9IGlzRnVuY3Rpb24odmFsdWVEaXN0b3J0aW9uKSA/IHZhbHVlRGlzdG9ydGlvbiA6IGRlZmF1bHRWYWx1ZURpc3RvcnRpb247XG4gICAgICAgICAgICAgIHRoaXMudmFsdWVNdXRhdGVkID0gaXNGdW5jdGlvbih2YWx1ZU11dGF0ZWQpID8gdmFsdWVNdXRhdGVkIDogZGVmYXVsdFZhbHVlTXV0YXRlZDtcbiAgICAgICAgICAgICAgdGhpcy52YWx1ZU9ic2VydmVkID0gaXNGdW5jdGlvbih2YWx1ZU9ic2VydmVkKSA/IHZhbHVlT2JzZXJ2ZWQgOiBkZWZhdWx0VmFsdWVPYnNlcnZlZDtcbiAgICAgICAgICAgICAgdGhpcy52YWx1ZUlzT2JzZXJ2YWJsZSA9IGlzRnVuY3Rpb24odmFsdWVJc09ic2VydmFibGUpID8gdmFsdWVJc09ic2VydmFibGUgOiBkZWZhdWx0VmFsdWVJc09ic2VydmFibGU7XG4gICAgICAgICAgfVxuICAgICAgfVxuICAgICAgZ2V0UHJveHkodmFsdWUpIHtcbiAgICAgICAgICBjb25zdCB1bndyYXBwZWRWYWx1ZSA9IHVud3JhcCh2YWx1ZSk7XG4gICAgICAgICAgY29uc3QgZGlzdG9ydGVkID0gdGhpcy52YWx1ZURpc3RvcnRpb24odW53cmFwcGVkVmFsdWUpO1xuICAgICAgICAgIGlmICh0aGlzLnZhbHVlSXNPYnNlcnZhYmxlKGRpc3RvcnRlZCkpIHtcbiAgICAgICAgICAgICAgY29uc3QgbyA9IHRoaXMuZ2V0UmVhY3RpdmVTdGF0ZSh1bndyYXBwZWRWYWx1ZSwgZGlzdG9ydGVkKTtcbiAgICAgICAgICAgICAgLy8gd2hlbiB0cnlpbmcgdG8gZXh0cmFjdCB0aGUgd3JpdGFibGUgdmVyc2lvbiBvZiBhIHJlYWRvbmx5XG4gICAgICAgICAgICAgIC8vIHdlIHJldHVybiB0aGUgcmVhZG9ubHkuXG4gICAgICAgICAgICAgIHJldHVybiBvLnJlYWRPbmx5ID09PSB2YWx1ZSA/IHZhbHVlIDogby5yZWFjdGl2ZTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIGRpc3RvcnRlZDtcbiAgICAgIH1cbiAgICAgIGdldFJlYWRPbmx5UHJveHkodmFsdWUpIHtcbiAgICAgICAgICB2YWx1ZSA9IHVud3JhcCh2YWx1ZSk7XG4gICAgICAgICAgY29uc3QgZGlzdG9ydGVkID0gdGhpcy52YWx1ZURpc3RvcnRpb24odmFsdWUpO1xuICAgICAgICAgIGlmICh0aGlzLnZhbHVlSXNPYnNlcnZhYmxlKGRpc3RvcnRlZCkpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuZ2V0UmVhY3RpdmVTdGF0ZSh2YWx1ZSwgZGlzdG9ydGVkKS5yZWFkT25seTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIGRpc3RvcnRlZDtcbiAgICAgIH1cbiAgICAgIHVud3JhcFByb3h5KHApIHtcbiAgICAgICAgICByZXR1cm4gdW53cmFwKHApO1xuICAgICAgfVxuICAgICAgZ2V0UmVhY3RpdmVTdGF0ZSh2YWx1ZSwgZGlzdG9ydGVkVmFsdWUpIHtcbiAgICAgICAgICBjb25zdCB7IG9iamVjdEdyYXBoLCB9ID0gdGhpcztcbiAgICAgICAgICBsZXQgcmVhY3RpdmVTdGF0ZSA9IG9iamVjdEdyYXBoLmdldChkaXN0b3J0ZWRWYWx1ZSk7XG4gICAgICAgICAgaWYgKHJlYWN0aXZlU3RhdGUpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIHJlYWN0aXZlU3RhdGU7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNvbnN0IG1lbWJyYW5lID0gdGhpcztcbiAgICAgICAgICByZWFjdGl2ZVN0YXRlID0ge1xuICAgICAgICAgICAgICBnZXQgcmVhY3RpdmUoKSB7XG4gICAgICAgICAgICAgICAgICBjb25zdCByZWFjdGl2ZUhhbmRsZXIgPSBuZXcgUmVhY3RpdmVQcm94eUhhbmRsZXIobWVtYnJhbmUsIGRpc3RvcnRlZFZhbHVlKTtcbiAgICAgICAgICAgICAgICAgIC8vIGNhY2hpbmcgdGhlIHJlYWN0aXZlIHByb3h5IGFmdGVyIHRoZSBmaXJzdCB0aW1lIGl0IGlzIGFjY2Vzc2VkXG4gICAgICAgICAgICAgICAgICBjb25zdCBwcm94eSA9IG5ldyBQcm94eShjcmVhdGVTaGFkb3dUYXJnZXQoZGlzdG9ydGVkVmFsdWUpLCByZWFjdGl2ZUhhbmRsZXIpO1xuICAgICAgICAgICAgICAgICAgcmVnaXN0ZXJQcm94eShwcm94eSwgdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgT2JqZWN0RGVmaW5lUHJvcGVydHkodGhpcywgJ3JlYWN0aXZlJywgeyB2YWx1ZTogcHJveHkgfSk7XG4gICAgICAgICAgICAgICAgICByZXR1cm4gcHJveHk7XG4gICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgIGdldCByZWFkT25seSgpIHtcbiAgICAgICAgICAgICAgICAgIGNvbnN0IHJlYWRPbmx5SGFuZGxlciA9IG5ldyBSZWFkT25seUhhbmRsZXIobWVtYnJhbmUsIGRpc3RvcnRlZFZhbHVlKTtcbiAgICAgICAgICAgICAgICAgIC8vIGNhY2hpbmcgdGhlIHJlYWRPbmx5IHByb3h5IGFmdGVyIHRoZSBmaXJzdCB0aW1lIGl0IGlzIGFjY2Vzc2VkXG4gICAgICAgICAgICAgICAgICBjb25zdCBwcm94eSA9IG5ldyBQcm94eShjcmVhdGVTaGFkb3dUYXJnZXQoZGlzdG9ydGVkVmFsdWUpLCByZWFkT25seUhhbmRsZXIpO1xuICAgICAgICAgICAgICAgICAgcmVnaXN0ZXJQcm94eShwcm94eSwgdmFsdWUpO1xuICAgICAgICAgICAgICAgICAgT2JqZWN0RGVmaW5lUHJvcGVydHkodGhpcywgJ3JlYWRPbmx5JywgeyB2YWx1ZTogcHJveHkgfSk7XG4gICAgICAgICAgICAgICAgICByZXR1cm4gcHJveHk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICB9O1xuICAgICAgICAgIG9iamVjdEdyYXBoLnNldChkaXN0b3J0ZWRWYWx1ZSwgcmVhY3RpdmVTdGF0ZSk7XG4gICAgICAgICAgcmV0dXJuIHJlYWN0aXZlU3RhdGU7XG4gICAgICB9XG4gIH1cbiAgLyoqIHZlcnNpb246IDAuMjYuMCAqL1xuXG4gIGZ1bmN0aW9uIHdyYXAoZGF0YSwgbXV0YXRpb25DYWxsYmFjaykge1xuXG4gICAgbGV0IG1lbWJyYW5lID0gbmV3IFJlYWN0aXZlTWVtYnJhbmUoe1xuICAgICAgdmFsdWVNdXRhdGVkKHRhcmdldCwga2V5KSB7XG4gICAgICAgIG11dGF0aW9uQ2FsbGJhY2sodGFyZ2V0LCBrZXkpO1xuICAgICAgfVxuXG4gICAgfSk7XG4gICAgcmV0dXJuIHtcbiAgICAgIGRhdGE6IG1lbWJyYW5lLmdldFByb3h5KGRhdGEpLFxuICAgICAgbWVtYnJhbmU6IG1lbWJyYW5lXG4gICAgfTtcbiAgfVxuICBmdW5jdGlvbiB1bndyYXAkMShtZW1icmFuZSwgb2JzZXJ2YWJsZSkge1xuICAgIGxldCB1bndyYXBwZWREYXRhID0gbWVtYnJhbmUudW53cmFwUHJveHkob2JzZXJ2YWJsZSk7XG4gICAgbGV0IGNvcHkgPSB7fTtcbiAgICBPYmplY3Qua2V5cyh1bndyYXBwZWREYXRhKS5mb3JFYWNoKGtleSA9PiB7XG4gICAgICBpZiAoWyckZWwnLCAnJHJlZnMnLCAnJG5leHRUaWNrJywgJyR3YXRjaCddLmluY2x1ZGVzKGtleSkpIHJldHVybjtcbiAgICAgIGNvcHlba2V5XSA9IHVud3JhcHBlZERhdGFba2V5XTtcbiAgICB9KTtcbiAgICByZXR1cm4gY29weTtcbiAgfVxuXG4gIGNsYXNzIENvbXBvbmVudCB7XG4gICAgY29uc3RydWN0b3IoZWwsIHNlZWREYXRhRm9yQ2xvbmluZyA9IG51bGwpIHtcbiAgICAgIHRoaXMuJGVsID0gZWw7XG4gICAgICBjb25zdCBkYXRhQXR0ciA9IHRoaXMuJGVsLmdldEF0dHJpYnV0ZSgneC1kYXRhJyk7XG4gICAgICBjb25zdCBkYXRhRXhwcmVzc2lvbiA9IGRhdGFBdHRyID09PSAnJyA/ICd7fScgOiBkYXRhQXR0cjtcbiAgICAgIGNvbnN0IGluaXRFeHByZXNzaW9uID0gdGhpcy4kZWwuZ2V0QXR0cmlidXRlKCd4LWluaXQnKTtcbiAgICAgIHRoaXMudW5vYnNlcnZlZERhdGEgPSBzZWVkRGF0YUZvckNsb25pbmcgPyBzZWVkRGF0YUZvckNsb25pbmcgOiBzYWZlckV2YWwoZGF0YUV4cHJlc3Npb24sIHt9KTtcbiAgICAgIC8vIENvbnN0cnVjdCBhIFByb3h5LWJhc2VkIG9ic2VydmFibGUuIFRoaXMgd2lsbCBiZSB1c2VkIHRvIGhhbmRsZSByZWFjdGl2aXR5LlxuXG4gICAgICBsZXQge1xuICAgICAgICBtZW1icmFuZSxcbiAgICAgICAgZGF0YVxuICAgICAgfSA9IHRoaXMud3JhcERhdGFJbk9ic2VydmFibGUodGhpcy51bm9ic2VydmVkRGF0YSk7XG4gICAgICB0aGlzLiRkYXRhID0gZGF0YTtcbiAgICAgIHRoaXMubWVtYnJhbmUgPSBtZW1icmFuZTsgLy8gQWZ0ZXIgbWFraW5nIHVzZXItc3VwcGxpZWQgZGF0YSBtZXRob2RzIHJlYWN0aXZlLCB3ZSBjYW4gbm93IGFkZFxuICAgICAgLy8gb3VyIG1hZ2ljIHByb3BlcnRpZXMgdG8gdGhlIG9yaWdpbmFsIGRhdGEgZm9yIGFjY2Vzcy5cblxuICAgICAgdGhpcy51bm9ic2VydmVkRGF0YS4kZWwgPSB0aGlzLiRlbDtcbiAgICAgIHRoaXMudW5vYnNlcnZlZERhdGEuJHJlZnMgPSB0aGlzLmdldFJlZnNQcm94eSgpO1xuICAgICAgdGhpcy5uZXh0VGlja1N0YWNrID0gW107XG5cbiAgICAgIHRoaXMudW5vYnNlcnZlZERhdGEuJG5leHRUaWNrID0gY2FsbGJhY2sgPT4ge1xuICAgICAgICB0aGlzLm5leHRUaWNrU3RhY2sucHVzaChjYWxsYmFjayk7XG4gICAgICB9O1xuXG4gICAgICB0aGlzLndhdGNoZXJzID0ge307XG5cbiAgICAgIHRoaXMudW5vYnNlcnZlZERhdGEuJHdhdGNoID0gKHByb3BlcnR5LCBjYWxsYmFjaykgPT4ge1xuICAgICAgICBpZiAoIXRoaXMud2F0Y2hlcnNbcHJvcGVydHldKSB0aGlzLndhdGNoZXJzW3Byb3BlcnR5XSA9IFtdO1xuICAgICAgICB0aGlzLndhdGNoZXJzW3Byb3BlcnR5XS5wdXNoKGNhbGxiYWNrKTtcbiAgICAgIH07XG5cbiAgICAgIHRoaXMuc2hvd0RpcmVjdGl2ZVN0YWNrID0gW107XG4gICAgICB0aGlzLnNob3dEaXJlY3RpdmVMYXN0RWxlbWVudDtcbiAgICAgIHZhciBpbml0UmV0dXJuZWRDYWxsYmFjazsgLy8gSWYgeC1pbml0IGlzIHByZXNlbnQgQU5EIHdlIGFyZW4ndCBjbG9uaW5nIChza2lwIHgtaW5pdCBvbiBjbG9uZSlcblxuICAgICAgaWYgKGluaXRFeHByZXNzaW9uICYmICFzZWVkRGF0YUZvckNsb25pbmcpIHtcbiAgICAgICAgLy8gV2Ugd2FudCB0byBhbGxvdyBkYXRhIG1hbmlwdWxhdGlvbiwgYnV0IG5vdCB0cmlnZ2VyIERPTSB1cGRhdGVzIGp1c3QgeWV0LlxuICAgICAgICAvLyBXZSBoYXZlbid0IGV2ZW4gaW5pdGlhbGl6ZWQgdGhlIGVsZW1lbnRzIHdpdGggdGhlaXIgQWxwaW5lIGJpbmRpbmdzLiBJIG1lYW4gYydtb24uXG4gICAgICAgIHRoaXMucGF1c2VSZWFjdGl2aXR5ID0gdHJ1ZTtcbiAgICAgICAgaW5pdFJldHVybmVkQ2FsbGJhY2sgPSB0aGlzLmV2YWx1YXRlUmV0dXJuRXhwcmVzc2lvbih0aGlzLiRlbCwgaW5pdEV4cHJlc3Npb24pO1xuICAgICAgICB0aGlzLnBhdXNlUmVhY3Rpdml0eSA9IGZhbHNlO1xuICAgICAgfSAvLyBSZWdpc3RlciBhbGwgb3VyIGxpc3RlbmVycyBhbmQgc2V0IGFsbCBvdXIgYXR0cmlidXRlIGJpbmRpbmdzLlxuXG5cbiAgICAgIHRoaXMuaW5pdGlhbGl6ZUVsZW1lbnRzKHRoaXMuJGVsKTsgLy8gVXNlIG11dGF0aW9uIG9ic2VydmVyIHRvIGRldGVjdCBuZXcgZWxlbWVudHMgYmVpbmcgYWRkZWQgd2l0aGluIHRoaXMgY29tcG9uZW50IGF0IHJ1bi10aW1lLlxuICAgICAgLy8gQWxwaW5lJ3MganVzdCBzbyBkYXJuIGZsZXhpYmxlIGFtaXJpdGU/XG5cbiAgICAgIHRoaXMubGlzdGVuRm9yTmV3RWxlbWVudHNUb0luaXRpYWxpemUoKTtcblxuICAgICAgaWYgKHR5cGVvZiBpbml0UmV0dXJuZWRDYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAvLyBSdW4gdGhlIGNhbGxiYWNrIHJldHVybmVkIGZyb20gdGhlIFwieC1pbml0XCIgaG9vayB0byBhbGxvdyB0aGUgdXNlciB0byBkbyBzdHVmZiBhZnRlclxuICAgICAgICAvLyBBbHBpbmUncyBnb3QgaXQncyBncnViYnkgbGl0dGxlIHBhd3MgYWxsIG92ZXIgZXZlcnl0aGluZy5cbiAgICAgICAgaW5pdFJldHVybmVkQ2FsbGJhY2suY2FsbCh0aGlzLiRkYXRhKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBnZXRVbm9ic2VydmVkRGF0YSgpIHtcbiAgICAgIHJldHVybiB1bndyYXAkMSh0aGlzLm1lbWJyYW5lLCB0aGlzLiRkYXRhKTtcbiAgICB9XG5cbiAgICB3cmFwRGF0YUluT2JzZXJ2YWJsZShkYXRhKSB7XG4gICAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgICBsZXQgdXBkYXRlRG9tID0gZGVib3VuY2UoZnVuY3Rpb24gKCkge1xuICAgICAgICBzZWxmLnVwZGF0ZUVsZW1lbnRzKHNlbGYuJGVsKTtcbiAgICAgIH0sIDApO1xuICAgICAgcmV0dXJuIHdyYXAoZGF0YSwgKHRhcmdldCwga2V5KSA9PiB7XG4gICAgICAgIGlmIChzZWxmLndhdGNoZXJzW2tleV0pIHtcbiAgICAgICAgICAvLyBJZiB0aGVyZSdzIGEgd2F0Y2hlciBmb3IgdGhpcyBzcGVjaWZpYyBrZXksIHJ1biBpdC5cbiAgICAgICAgICBzZWxmLndhdGNoZXJzW2tleV0uZm9yRWFjaChjYWxsYmFjayA9PiBjYWxsYmFjayh0YXJnZXRba2V5XSkpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIC8vIExldCdzIHdhbGsgdGhyb3VnaCB0aGUgd2F0Y2hlcnMgd2l0aCBcImRvdC1ub3RhdGlvblwiIChmb28uYmFyKSBhbmQgc2VlXG4gICAgICAgICAgLy8gaWYgdGhpcyBtdXRhdGlvbiBmaXRzIGFueSBvZiB0aGVtLlxuICAgICAgICAgIE9iamVjdC5rZXlzKHNlbGYud2F0Y2hlcnMpLmZpbHRlcihpID0+IGkuaW5jbHVkZXMoJy4nKSkuZm9yRWFjaChmdWxsRG90Tm90YXRpb25LZXkgPT4ge1xuICAgICAgICAgICAgbGV0IGRvdE5vdGF0aW9uUGFydHMgPSBmdWxsRG90Tm90YXRpb25LZXkuc3BsaXQoJy4nKTsgLy8gSWYgdGhpcyBkb3Qtbm90YXRpb24gd2F0Y2hlcidzIGxhc3QgXCJwYXJ0XCIgZG9lc24ndCBtYXRjaCB0aGUgY3VycmVudFxuICAgICAgICAgICAgLy8ga2V5LCB0aGVuIHNraXAgaXQgZWFybHkgZm9yIHBlcmZvcm1hbmNlIHJlYXNvbnMuXG5cbiAgICAgICAgICAgIGlmIChrZXkgIT09IGRvdE5vdGF0aW9uUGFydHNbZG90Tm90YXRpb25QYXJ0cy5sZW5ndGggLSAxXSkgcmV0dXJuOyAvLyBOb3csIHdhbGsgdGhyb3VnaCB0aGUgZG90LW5vdGF0aW9uIFwicGFydHNcIiByZWN1cnNpdmVseSB0byBmaW5kXG4gICAgICAgICAgICAvLyBhIG1hdGNoLCBhbmQgY2FsbCB0aGUgd2F0Y2hlciBpZiBvbmUncyBmb3VuZC5cblxuICAgICAgICAgICAgZG90Tm90YXRpb25QYXJ0cy5yZWR1Y2UoKGNvbXBhcmlzb25EYXRhLCBwYXJ0KSA9PiB7XG4gICAgICAgICAgICAgIGlmIChPYmplY3QuaXModGFyZ2V0LCBjb21wYXJpc29uRGF0YSkpIHtcbiAgICAgICAgICAgICAgICAvLyBSdW4gdGhlIHdhdGNoZXJzLlxuICAgICAgICAgICAgICAgIHNlbGYud2F0Y2hlcnNbZnVsbERvdE5vdGF0aW9uS2V5XS5mb3JFYWNoKGNhbGxiYWNrID0+IGNhbGxiYWNrKHRhcmdldFtrZXldKSk7XG4gICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICByZXR1cm4gY29tcGFyaXNvbkRhdGFbcGFydF07XG4gICAgICAgICAgICB9LCBzZWxmLmdldFVub2JzZXJ2ZWREYXRhKCkpO1xuICAgICAgICAgIH0pO1xuICAgICAgICB9IC8vIERvbid0IHJlYWN0IHRvIGRhdGEgY2hhbmdlcyBmb3IgY2FzZXMgbGlrZSB0aGUgYHgtY3JlYXRlZGAgaG9vay5cblxuXG4gICAgICAgIGlmIChzZWxmLnBhdXNlUmVhY3Rpdml0eSkgcmV0dXJuO1xuICAgICAgICB1cGRhdGVEb20oKTtcbiAgICAgIH0pO1xuICAgIH1cblxuICAgIHdhbGtBbmRTa2lwTmVzdGVkQ29tcG9uZW50cyhlbCwgY2FsbGJhY2ssIGluaXRpYWxpemVDb21wb25lbnRDYWxsYmFjayA9ICgpID0+IHt9KSB7XG4gICAgICB3YWxrKGVsLCBlbCA9PiB7XG4gICAgICAgIC8vIFdlJ3ZlIGhpdCBhIGNvbXBvbmVudC5cbiAgICAgICAgaWYgKGVsLmhhc0F0dHJpYnV0ZSgneC1kYXRhJykpIHtcbiAgICAgICAgICAvLyBJZiBpdCdzIG5vdCB0aGUgY3VycmVudCBvbmUuXG4gICAgICAgICAgaWYgKCFlbC5pc1NhbWVOb2RlKHRoaXMuJGVsKSkge1xuICAgICAgICAgICAgLy8gSW5pdGlhbGl6ZSBpdCBpZiBpdCdzIG5vdC5cbiAgICAgICAgICAgIGlmICghZWwuX194KSBpbml0aWFsaXplQ29tcG9uZW50Q2FsbGJhY2soZWwpOyAvLyBOb3cgd2UnbGwgbGV0IHRoYXQgc3ViLWNvbXBvbmVudCBkZWFsIHdpdGggaXRzZWxmLlxuXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIGNhbGxiYWNrKGVsKTtcbiAgICAgIH0pO1xuICAgIH1cblxuICAgIGluaXRpYWxpemVFbGVtZW50cyhyb290RWwsIGV4dHJhVmFycyA9ICgpID0+IHt9KSB7XG4gICAgICB0aGlzLndhbGtBbmRTa2lwTmVzdGVkQ29tcG9uZW50cyhyb290RWwsIGVsID0+IHtcbiAgICAgICAgLy8gRG9uJ3QgdG91Y2ggc3Bhd25zIGZyb20gZm9yIGxvb3BcbiAgICAgICAgaWYgKGVsLl9feF9mb3Jfa2V5ICE9PSB1bmRlZmluZWQpIHJldHVybiBmYWxzZTsgLy8gRG9uJ3QgdG91Y2ggc3Bhd25zIGZyb20gaWYgZGlyZWN0aXZlc1xuXG4gICAgICAgIGlmIChlbC5fX3hfaW5zZXJ0ZWRfbWUgIT09IHVuZGVmaW5lZCkgcmV0dXJuIGZhbHNlO1xuICAgICAgICB0aGlzLmluaXRpYWxpemVFbGVtZW50KGVsLCBleHRyYVZhcnMpO1xuICAgICAgfSwgZWwgPT4ge1xuICAgICAgICBlbC5fX3ggPSBuZXcgQ29tcG9uZW50KGVsKTtcbiAgICAgIH0pO1xuICAgICAgdGhpcy5leGVjdXRlQW5kQ2xlYXJSZW1haW5pbmdTaG93RGlyZWN0aXZlU3RhY2soKTtcbiAgICAgIHRoaXMuZXhlY3V0ZUFuZENsZWFyTmV4dFRpY2tTdGFjayhyb290RWwpO1xuICAgIH1cblxuICAgIGluaXRpYWxpemVFbGVtZW50KGVsLCBleHRyYVZhcnMpIHtcbiAgICAgIC8vIFRvIHN1cHBvcnQgY2xhc3MgYXR0cmlidXRlIG1lcmdpbmcsIHdlIGhhdmUgdG8ga25vdyB3aGF0IHRoZSBlbGVtZW50J3NcbiAgICAgIC8vIG9yaWdpbmFsIGNsYXNzIGF0dHJpYnV0ZSBsb29rZWQgbGlrZSBmb3IgcmVmZXJlbmNlLlxuICAgICAgaWYgKGVsLmhhc0F0dHJpYnV0ZSgnY2xhc3MnKSAmJiBnZXRYQXR0cnMoZWwpLmxlbmd0aCA+IDApIHtcbiAgICAgICAgZWwuX194X29yaWdpbmFsX2NsYXNzZXMgPSBlbC5nZXRBdHRyaWJ1dGUoJ2NsYXNzJykuc3BsaXQoJyAnKTtcbiAgICAgIH1cblxuICAgICAgdGhpcy5yZWdpc3Rlckxpc3RlbmVycyhlbCwgZXh0cmFWYXJzKTtcbiAgICAgIHRoaXMucmVzb2x2ZUJvdW5kQXR0cmlidXRlcyhlbCwgdHJ1ZSwgZXh0cmFWYXJzKTtcbiAgICB9XG5cbiAgICB1cGRhdGVFbGVtZW50cyhyb290RWwsIGV4dHJhVmFycyA9ICgpID0+IHt9KSB7XG4gICAgICB0aGlzLndhbGtBbmRTa2lwTmVzdGVkQ29tcG9uZW50cyhyb290RWwsIGVsID0+IHtcbiAgICAgICAgLy8gRG9uJ3QgdG91Y2ggc3Bhd25zIGZyb20gZm9yIGxvb3AgKGFuZCBjaGVjayBpZiB0aGUgcm9vdCBpcyBhY3R1YWxseSBhIGZvciBsb29wIGluIGEgcGFyZW50LCBkb24ndCBza2lwIGl0LilcbiAgICAgICAgaWYgKGVsLl9feF9mb3Jfa2V5ICE9PSB1bmRlZmluZWQgJiYgIWVsLmlzU2FtZU5vZGUodGhpcy4kZWwpKSByZXR1cm4gZmFsc2U7XG4gICAgICAgIHRoaXMudXBkYXRlRWxlbWVudChlbCwgZXh0cmFWYXJzKTtcbiAgICAgIH0sIGVsID0+IHtcbiAgICAgICAgZWwuX194ID0gbmV3IENvbXBvbmVudChlbCk7XG4gICAgICB9KTtcbiAgICAgIHRoaXMuZXhlY3V0ZUFuZENsZWFyUmVtYWluaW5nU2hvd0RpcmVjdGl2ZVN0YWNrKCk7XG4gICAgICB0aGlzLmV4ZWN1dGVBbmRDbGVhck5leHRUaWNrU3RhY2socm9vdEVsKTtcbiAgICB9XG5cbiAgICBleGVjdXRlQW5kQ2xlYXJOZXh0VGlja1N0YWNrKGVsKSB7XG4gICAgICAvLyBTa2lwIHNwYXducyBmcm9tIGFscGluZSBkaXJlY3RpdmVzXG4gICAgICBpZiAoZWwgPT09IHRoaXMuJGVsKSB7XG4gICAgICAgIC8vIFdhbGsgdGhyb3VnaCB0aGUgJG5leHRUaWNrIHN0YWNrIGFuZCBjbGVhciBpdCBhcyB3ZSBnby5cbiAgICAgICAgd2hpbGUgKHRoaXMubmV4dFRpY2tTdGFjay5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgdGhpcy5uZXh0VGlja1N0YWNrLnNoaWZ0KCkoKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cblxuICAgIGV4ZWN1dGVBbmRDbGVhclJlbWFpbmluZ1Nob3dEaXJlY3RpdmVTdGFjaygpIHtcbiAgICAgIC8vIFRoZSBnb2FsIGhlcmUgaXMgdG8gc3RhcnQgYWxsIHRoZSB4LXNob3cgdHJhbnNpdGlvbnNcbiAgICAgIC8vIGFuZCBidWlsZCBhIG5lc3RlZCBwcm9taXNlIGNoYWluIHNvIHRoYXQgZWxlbWVudHNcbiAgICAgIC8vIG9ubHkgaGlkZSB3aGVuIHRoZSBjaGlsZHJlbiBhcmUgZmluaXNoZWQgaGlkaW5nLlxuICAgICAgdGhpcy5zaG93RGlyZWN0aXZlU3RhY2sucmV2ZXJzZSgpLm1hcCh0aGluZyA9PiB7XG4gICAgICAgIHJldHVybiBuZXcgUHJvbWlzZShyZXNvbHZlID0+IHtcbiAgICAgICAgICB0aGluZyhmaW5pc2ggPT4ge1xuICAgICAgICAgICAgcmVzb2x2ZShmaW5pc2gpO1xuICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICAgIH0pLnJlZHVjZSgobmVzdGVkUHJvbWlzZSwgcHJvbWlzZSkgPT4ge1xuICAgICAgICByZXR1cm4gbmVzdGVkUHJvbWlzZS50aGVuKCgpID0+IHtcbiAgICAgICAgICByZXR1cm4gcHJvbWlzZS50aGVuKGZpbmlzaCA9PiBmaW5pc2goKSk7XG4gICAgICAgIH0pO1xuICAgICAgfSwgUHJvbWlzZS5yZXNvbHZlKCgpID0+IHt9KSk7IC8vIFdlJ3ZlIHByb2Nlc3NlZCB0aGUgaGFuZGxlciBzdGFjay4gbGV0J3MgY2xlYXIgaXQuXG5cbiAgICAgIHRoaXMuc2hvd0RpcmVjdGl2ZVN0YWNrID0gW107XG4gICAgICB0aGlzLnNob3dEaXJlY3RpdmVMYXN0RWxlbWVudCA9IHVuZGVmaW5lZDtcbiAgICB9XG5cbiAgICB1cGRhdGVFbGVtZW50KGVsLCBleHRyYVZhcnMpIHtcbiAgICAgIHRoaXMucmVzb2x2ZUJvdW5kQXR0cmlidXRlcyhlbCwgZmFsc2UsIGV4dHJhVmFycyk7XG4gICAgfVxuXG4gICAgcmVnaXN0ZXJMaXN0ZW5lcnMoZWwsIGV4dHJhVmFycykge1xuICAgICAgZ2V0WEF0dHJzKGVsKS5mb3JFYWNoKCh7XG4gICAgICAgIHR5cGUsXG4gICAgICAgIHZhbHVlLFxuICAgICAgICBtb2RpZmllcnMsXG4gICAgICAgIGV4cHJlc3Npb25cbiAgICAgIH0pID0+IHtcbiAgICAgICAgc3dpdGNoICh0eXBlKSB7XG4gICAgICAgICAgY2FzZSAnb24nOlxuICAgICAgICAgICAgcmVnaXN0ZXJMaXN0ZW5lcih0aGlzLCBlbCwgdmFsdWUsIG1vZGlmaWVycywgZXhwcmVzc2lvbiwgZXh0cmFWYXJzKTtcbiAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgY2FzZSAnbW9kZWwnOlxuICAgICAgICAgICAgcmVnaXN0ZXJNb2RlbExpc3RlbmVyKHRoaXMsIGVsLCBtb2RpZmllcnMsIGV4cHJlc3Npb24sIGV4dHJhVmFycyk7XG4gICAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgfVxuXG4gICAgcmVzb2x2ZUJvdW5kQXR0cmlidXRlcyhlbCwgaW5pdGlhbFVwZGF0ZSA9IGZhbHNlLCBleHRyYVZhcnMpIHtcbiAgICAgIGxldCBhdHRycyA9IGdldFhBdHRycyhlbCk7XG5cbiAgICAgIGlmIChlbC50eXBlICE9PSB1bmRlZmluZWQgJiYgZWwudHlwZSA9PT0gJ3JhZGlvJykge1xuICAgICAgICAvLyBJZiB0aGVyZSdzIGFuIHgtbW9kZWwgb24gYSByYWRpbyBpbnB1dCwgbW92ZSBpdCB0byBlbmQgb2YgYXR0cmlidXRlIGxpc3RcbiAgICAgICAgLy8gdG8gZW5zdXJlIHRoYXQgeC1iaW5kOnZhbHVlIChpZiBwcmVzZW50KSBpcyBwcm9jZXNzZWQgZmlyc3QuXG4gICAgICAgIGNvbnN0IG1vZGVsSWR4ID0gYXR0cnMuZmluZEluZGV4KGF0dHIgPT4gYXR0ci50eXBlID09PSAnbW9kZWwnKTtcblxuICAgICAgICBpZiAobW9kZWxJZHggPiAtMSkge1xuICAgICAgICAgIGF0dHJzLnB1c2goYXR0cnMuc3BsaWNlKG1vZGVsSWR4LCAxKVswXSk7XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgYXR0cnMuZm9yRWFjaCgoe1xuICAgICAgICB0eXBlLFxuICAgICAgICB2YWx1ZSxcbiAgICAgICAgbW9kaWZpZXJzLFxuICAgICAgICBleHByZXNzaW9uXG4gICAgICB9KSA9PiB7XG4gICAgICAgIHN3aXRjaCAodHlwZSkge1xuICAgICAgICAgIGNhc2UgJ21vZGVsJzpcbiAgICAgICAgICAgIGhhbmRsZUF0dHJpYnV0ZUJpbmRpbmdEaXJlY3RpdmUodGhpcywgZWwsICd2YWx1ZScsIGV4cHJlc3Npb24sIGV4dHJhVmFycywgdHlwZSk7XG4gICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgIGNhc2UgJ2JpbmQnOlxuICAgICAgICAgICAgLy8gVGhlIDprZXkgYmluZGluZyBvbiBhbiB4LWZvciBpcyBzcGVjaWFsLCBpZ25vcmUgaXQuXG4gICAgICAgICAgICBpZiAoZWwudGFnTmFtZS50b0xvd2VyQ2FzZSgpID09PSAndGVtcGxhdGUnICYmIHZhbHVlID09PSAna2V5JykgcmV0dXJuO1xuICAgICAgICAgICAgaGFuZGxlQXR0cmlidXRlQmluZGluZ0RpcmVjdGl2ZSh0aGlzLCBlbCwgdmFsdWUsIGV4cHJlc3Npb24sIGV4dHJhVmFycywgdHlwZSk7XG4gICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgIGNhc2UgJ3RleHQnOlxuICAgICAgICAgICAgdmFyIG91dHB1dCA9IHRoaXMuZXZhbHVhdGVSZXR1cm5FeHByZXNzaW9uKGVsLCBleHByZXNzaW9uLCBleHRyYVZhcnMpO1xuICAgICAgICAgICAgaGFuZGxlVGV4dERpcmVjdGl2ZShlbCwgb3V0cHV0LCBleHByZXNzaW9uKTtcbiAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgY2FzZSAnaHRtbCc6XG4gICAgICAgICAgICBoYW5kbGVIdG1sRGlyZWN0aXZlKHRoaXMsIGVsLCBleHByZXNzaW9uLCBleHRyYVZhcnMpO1xuICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlICdzaG93JzpcbiAgICAgICAgICAgIHZhciBvdXRwdXQgPSB0aGlzLmV2YWx1YXRlUmV0dXJuRXhwcmVzc2lvbihlbCwgZXhwcmVzc2lvbiwgZXh0cmFWYXJzKTtcbiAgICAgICAgICAgIGhhbmRsZVNob3dEaXJlY3RpdmUodGhpcywgZWwsIG91dHB1dCwgbW9kaWZpZXJzLCBpbml0aWFsVXBkYXRlKTtcbiAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgY2FzZSAnaWYnOlxuICAgICAgICAgICAgLy8gSWYgdGhpcyBlbGVtZW50IGFsc28gaGFzIHgtZm9yIG9uIGl0LCBkb24ndCBwcm9jZXNzIHgtaWYuXG4gICAgICAgICAgICAvLyBXZSB3aWxsIGxldCB0aGUgXCJ4LWZvclwiIGRpcmVjdGl2ZSBoYW5kbGUgdGhlIFwiaWZcImluZy5cbiAgICAgICAgICAgIGlmIChhdHRycy5maWx0ZXIoaSA9PiBpLnR5cGUgPT09ICdmb3InKS5sZW5ndGggPiAwKSByZXR1cm47XG4gICAgICAgICAgICB2YXIgb3V0cHV0ID0gdGhpcy5ldmFsdWF0ZVJldHVybkV4cHJlc3Npb24oZWwsIGV4cHJlc3Npb24sIGV4dHJhVmFycyk7XG4gICAgICAgICAgICBoYW5kbGVJZkRpcmVjdGl2ZSh0aGlzLCBlbCwgb3V0cHV0LCBpbml0aWFsVXBkYXRlLCBleHRyYVZhcnMpO1xuICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlICdmb3InOlxuICAgICAgICAgICAgaGFuZGxlRm9yRGlyZWN0aXZlKHRoaXMsIGVsLCBleHByZXNzaW9uLCBpbml0aWFsVXBkYXRlLCBleHRyYVZhcnMpO1xuICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICBjYXNlICdjbG9hayc6XG4gICAgICAgICAgICBlbC5yZW1vdmVBdHRyaWJ1dGUoJ3gtY2xvYWsnKTtcbiAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9XG5cbiAgICBldmFsdWF0ZVJldHVybkV4cHJlc3Npb24oZWwsIGV4cHJlc3Npb24sIGV4dHJhVmFycyA9ICgpID0+IHt9KSB7XG4gICAgICByZXR1cm4gc2FmZXJFdmFsKGV4cHJlc3Npb24sIHRoaXMuJGRhdGEsIF9vYmplY3RTcHJlYWQyKHt9LCBleHRyYVZhcnMoKSwge1xuICAgICAgICAkZGlzcGF0Y2g6IHRoaXMuZ2V0RGlzcGF0Y2hGdW5jdGlvbihlbClcbiAgICAgIH0pKTtcbiAgICB9XG5cbiAgICBldmFsdWF0ZUNvbW1hbmRFeHByZXNzaW9uKGVsLCBleHByZXNzaW9uLCBleHRyYVZhcnMgPSAoKSA9PiB7fSkge1xuICAgICAgcmV0dXJuIHNhZmVyRXZhbE5vUmV0dXJuKGV4cHJlc3Npb24sIHRoaXMuJGRhdGEsIF9vYmplY3RTcHJlYWQyKHt9LCBleHRyYVZhcnMoKSwge1xuICAgICAgICAkZGlzcGF0Y2g6IHRoaXMuZ2V0RGlzcGF0Y2hGdW5jdGlvbihlbClcbiAgICAgIH0pKTtcbiAgICB9XG5cbiAgICBnZXREaXNwYXRjaEZ1bmN0aW9uKGVsKSB7XG4gICAgICByZXR1cm4gKGV2ZW50LCBkZXRhaWwgPSB7fSkgPT4ge1xuICAgICAgICBlbC5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudChldmVudCwge1xuICAgICAgICAgIGRldGFpbCxcbiAgICAgICAgICBidWJibGVzOiB0cnVlXG4gICAgICAgIH0pKTtcbiAgICAgIH07XG4gICAgfVxuXG4gICAgbGlzdGVuRm9yTmV3RWxlbWVudHNUb0luaXRpYWxpemUoKSB7XG4gICAgICBjb25zdCB0YXJnZXROb2RlID0gdGhpcy4kZWw7XG4gICAgICBjb25zdCBvYnNlcnZlck9wdGlvbnMgPSB7XG4gICAgICAgIGNoaWxkTGlzdDogdHJ1ZSxcbiAgICAgICAgYXR0cmlidXRlczogdHJ1ZSxcbiAgICAgICAgc3VidHJlZTogdHJ1ZVxuICAgICAgfTtcbiAgICAgIGNvbnN0IG9ic2VydmVyID0gbmV3IE11dGF0aW9uT2JzZXJ2ZXIobXV0YXRpb25zID0+IHtcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBtdXRhdGlvbnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAvLyBGaWx0ZXIgb3V0IG11dGF0aW9ucyB0cmlnZ2VyZWQgZnJvbSBjaGlsZCBjb21wb25lbnRzLlxuICAgICAgICAgIGNvbnN0IGNsb3Nlc3RQYXJlbnRDb21wb25lbnQgPSBtdXRhdGlvbnNbaV0udGFyZ2V0LmNsb3Nlc3QoJ1t4LWRhdGFdJyk7XG4gICAgICAgICAgaWYgKCEoY2xvc2VzdFBhcmVudENvbXBvbmVudCAmJiBjbG9zZXN0UGFyZW50Q29tcG9uZW50LmlzU2FtZU5vZGUodGhpcy4kZWwpKSkgcmV0dXJuO1xuXG4gICAgICAgICAgaWYgKG11dGF0aW9uc1tpXS50eXBlID09PSAnYXR0cmlidXRlcycgJiYgbXV0YXRpb25zW2ldLmF0dHJpYnV0ZU5hbWUgPT09ICd4LWRhdGEnKSB7XG4gICAgICAgICAgICBjb25zdCByYXdEYXRhID0gc2FmZXJFdmFsKG11dGF0aW9uc1tpXS50YXJnZXQuZ2V0QXR0cmlidXRlKCd4LWRhdGEnKSwge30pO1xuICAgICAgICAgICAgT2JqZWN0LmtleXMocmF3RGF0YSkuZm9yRWFjaChrZXkgPT4ge1xuICAgICAgICAgICAgICBpZiAodGhpcy4kZGF0YVtrZXldICE9PSByYXdEYXRhW2tleV0pIHtcbiAgICAgICAgICAgICAgICB0aGlzLiRkYXRhW2tleV0gPSByYXdEYXRhW2tleV07XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIGlmIChtdXRhdGlvbnNbaV0uYWRkZWROb2Rlcy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBtdXRhdGlvbnNbaV0uYWRkZWROb2Rlcy5mb3JFYWNoKG5vZGUgPT4ge1xuICAgICAgICAgICAgICBpZiAobm9kZS5ub2RlVHlwZSAhPT0gMSB8fCBub2RlLl9feF9pbnNlcnRlZF9tZSkgcmV0dXJuO1xuXG4gICAgICAgICAgICAgIGlmIChub2RlLm1hdGNoZXMoJ1t4LWRhdGFdJykpIHtcbiAgICAgICAgICAgICAgICBub2RlLl9feCA9IG5ldyBDb21wb25lbnQobm9kZSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgdGhpcy5pbml0aWFsaXplRWxlbWVudHMobm9kZSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgICAgb2JzZXJ2ZXIub2JzZXJ2ZSh0YXJnZXROb2RlLCBvYnNlcnZlck9wdGlvbnMpO1xuICAgIH1cblxuICAgIGdldFJlZnNQcm94eSgpIHtcbiAgICAgIHZhciBzZWxmID0gdGhpcztcbiAgICAgIHZhciByZWZPYmogPSB7fTtcbiAgICAgIC8vIE9uZSBvZiB0aGUgZ29hbHMgb2YgdGhpcyBpcyB0byBub3QgaG9sZCBlbGVtZW50cyBpbiBtZW1vcnksIGJ1dCByYXRoZXIgcmUtZXZhbHVhdGVcbiAgICAgIC8vIHRoZSBET00gd2hlbiB0aGUgc3lzdGVtIG5lZWRzIHNvbWV0aGluZyBmcm9tIGl0LiBUaGlzIHdheSwgdGhlIGZyYW1ld29yayBpcyBmbGV4aWJsZSBhbmRcbiAgICAgIC8vIGZyaWVuZGx5IHRvIG91dHNpZGUgRE9NIGNoYW5nZXMgZnJvbSBsaWJyYXJpZXMgbGlrZSBWdWUvTGl2ZXdpcmUuXG4gICAgICAvLyBGb3IgdGhpcyByZWFzb24sIEknbSB1c2luZyBhbiBcIm9uLWRlbWFuZFwiIHByb3h5IHRvIGZha2UgYSBcIiRyZWZzXCIgb2JqZWN0LlxuXG4gICAgICByZXR1cm4gbmV3IFByb3h5KHJlZk9iaiwge1xuICAgICAgICBnZXQob2JqZWN0LCBwcm9wZXJ0eSkge1xuICAgICAgICAgIGlmIChwcm9wZXJ0eSA9PT0gJyRpc0FscGluZVByb3h5JykgcmV0dXJuIHRydWU7XG4gICAgICAgICAgdmFyIHJlZjsgLy8gV2UgY2FuJ3QganVzdCBxdWVyeSB0aGUgRE9NIGJlY2F1c2UgaXQncyBoYXJkIHRvIGZpbHRlciBvdXQgcmVmcyBpblxuICAgICAgICAgIC8vIG5lc3RlZCBjb21wb25lbnRzLlxuXG4gICAgICAgICAgc2VsZi53YWxrQW5kU2tpcE5lc3RlZENvbXBvbmVudHMoc2VsZi4kZWwsIGVsID0+IHtcbiAgICAgICAgICAgIGlmIChlbC5oYXNBdHRyaWJ1dGUoJ3gtcmVmJykgJiYgZWwuZ2V0QXR0cmlidXRlKCd4LXJlZicpID09PSBwcm9wZXJ0eSkge1xuICAgICAgICAgICAgICByZWYgPSBlbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9KTtcbiAgICAgICAgICByZXR1cm4gcmVmO1xuICAgICAgICB9XG5cbiAgICAgIH0pO1xuICAgIH1cblxuICB9XG5cbiAgY29uc3QgQWxwaW5lID0ge1xuICAgIHN0YXJ0OiBhc3luYyBmdW5jdGlvbiBzdGFydCgpIHtcbiAgICAgIGlmICghaXNUZXN0aW5nKCkpIHtcbiAgICAgICAgYXdhaXQgZG9tUmVhZHkoKTtcbiAgICAgIH1cblxuICAgICAgdGhpcy5kaXNjb3ZlckNvbXBvbmVudHMoZWwgPT4ge1xuICAgICAgICB0aGlzLmluaXRpYWxpemVDb21wb25lbnQoZWwpO1xuICAgICAgfSk7IC8vIEl0J3MgZWFzaWVyIGFuZCBtb3JlIHBlcmZvcm1hbnQgdG8ganVzdCBzdXBwb3J0IFR1cmJvbGlua3MgdGhhbiBsaXN0ZW5cbiAgICAgIC8vIHRvIE11dGF0aW9uT2JzZXJ2ZXIgbXV0YXRpb25zIGF0IHRoZSBkb2N1bWVudCBsZXZlbC5cblxuICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcInR1cmJvbGlua3M6bG9hZFwiLCAoKSA9PiB7XG4gICAgICAgIHRoaXMuZGlzY292ZXJVbmluaXRpYWxpemVkQ29tcG9uZW50cyhlbCA9PiB7XG4gICAgICAgICAgdGhpcy5pbml0aWFsaXplQ29tcG9uZW50KGVsKTtcbiAgICAgICAgfSk7XG4gICAgICB9KTtcbiAgICAgIHRoaXMubGlzdGVuRm9yTmV3VW5pbml0aWFsaXplZENvbXBvbmVudHNBdFJ1blRpbWUoZWwgPT4ge1xuICAgICAgICB0aGlzLmluaXRpYWxpemVDb21wb25lbnQoZWwpO1xuICAgICAgfSk7XG4gICAgfSxcbiAgICBkaXNjb3ZlckNvbXBvbmVudHM6IGZ1bmN0aW9uIGRpc2NvdmVyQ29tcG9uZW50cyhjYWxsYmFjaykge1xuICAgICAgY29uc3Qgcm9vdEVscyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1t4LWRhdGFdJyk7XG4gICAgICByb290RWxzLmZvckVhY2gocm9vdEVsID0+IHtcbiAgICAgICAgY2FsbGJhY2socm9vdEVsKTtcbiAgICAgIH0pO1xuICAgIH0sXG4gICAgZGlzY292ZXJVbmluaXRpYWxpemVkQ29tcG9uZW50czogZnVuY3Rpb24gZGlzY292ZXJVbmluaXRpYWxpemVkQ29tcG9uZW50cyhjYWxsYmFjaywgZWwgPSBudWxsKSB7XG4gICAgICBjb25zdCByb290RWxzID0gKGVsIHx8IGRvY3VtZW50KS5xdWVyeVNlbGVjdG9yQWxsKCdbeC1kYXRhXScpO1xuICAgICAgQXJyYXkuZnJvbShyb290RWxzKS5maWx0ZXIoZWwgPT4gZWwuX194ID09PSB1bmRlZmluZWQpLmZvckVhY2gocm9vdEVsID0+IHtcbiAgICAgICAgY2FsbGJhY2socm9vdEVsKTtcbiAgICAgIH0pO1xuICAgIH0sXG4gICAgbGlzdGVuRm9yTmV3VW5pbml0aWFsaXplZENvbXBvbmVudHNBdFJ1blRpbWU6IGZ1bmN0aW9uIGxpc3RlbkZvck5ld1VuaW5pdGlhbGl6ZWRDb21wb25lbnRzQXRSdW5UaW1lKGNhbGxiYWNrKSB7XG4gICAgICBjb25zdCB0YXJnZXROb2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYm9keScpO1xuICAgICAgY29uc3Qgb2JzZXJ2ZXJPcHRpb25zID0ge1xuICAgICAgICBjaGlsZExpc3Q6IHRydWUsXG4gICAgICAgIGF0dHJpYnV0ZXM6IHRydWUsXG4gICAgICAgIHN1YnRyZWU6IHRydWVcbiAgICAgIH07XG4gICAgICBjb25zdCBvYnNlcnZlciA9IG5ldyBNdXRhdGlvbk9ic2VydmVyKG11dGF0aW9ucyA9PiB7XG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgbXV0YXRpb25zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgaWYgKG11dGF0aW9uc1tpXS5hZGRlZE5vZGVzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIG11dGF0aW9uc1tpXS5hZGRlZE5vZGVzLmZvckVhY2gobm9kZSA9PiB7XG4gICAgICAgICAgICAgIC8vIERpc2NhcmQgbm9uLWVsZW1lbnQgbm9kZXMgKGxpa2UgbGluZS1icmVha3MpXG4gICAgICAgICAgICAgIGlmIChub2RlLm5vZGVUeXBlICE9PSAxKSByZXR1cm47IC8vIERpc2NhcmQgYW55IGNoYW5nZXMgaGFwcGVuaW5nIHdpdGhpbiBhbiBleGlzdGluZyBjb21wb25lbnQuXG4gICAgICAgICAgICAgIC8vIFRoZXkgd2lsbCB0YWtlIGNhcmUgb2YgdGhlbXNlbHZlcy5cblxuICAgICAgICAgICAgICBpZiAobm9kZS5wYXJlbnRFbGVtZW50ICYmIG5vZGUucGFyZW50RWxlbWVudC5jbG9zZXN0KCdbeC1kYXRhXScpKSByZXR1cm47XG4gICAgICAgICAgICAgIHRoaXMuZGlzY292ZXJVbmluaXRpYWxpemVkQ29tcG9uZW50cyhlbCA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5pbml0aWFsaXplQ29tcG9uZW50KGVsKTtcbiAgICAgICAgICAgICAgfSwgbm9kZS5wYXJlbnRFbGVtZW50KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgICBvYnNlcnZlci5vYnNlcnZlKHRhcmdldE5vZGUsIG9ic2VydmVyT3B0aW9ucyk7XG4gICAgfSxcbiAgICBpbml0aWFsaXplQ29tcG9uZW50OiBmdW5jdGlvbiBpbml0aWFsaXplQ29tcG9uZW50KGVsKSB7XG4gICAgICBpZiAoIWVsLl9feCkge1xuICAgICAgICBlbC5fX3ggPSBuZXcgQ29tcG9uZW50KGVsKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGNsb25lOiBmdW5jdGlvbiBjbG9uZShjb21wb25lbnQsIG5ld0VsKSB7XG4gICAgICBpZiAoIW5ld0VsLl9feCkge1xuICAgICAgICBuZXdFbC5fX3ggPSBuZXcgQ29tcG9uZW50KG5ld0VsLCBjb21wb25lbnQuZ2V0VW5vYnNlcnZlZERhdGEoKSk7XG4gICAgICB9XG4gICAgfVxuICB9O1xuXG4gIGlmICghaXNUZXN0aW5nKCkpIHtcbiAgICB3aW5kb3cuQWxwaW5lID0gQWxwaW5lO1xuXG4gICAgaWYgKHdpbmRvdy5kZWZlckxvYWRpbmdBbHBpbmUpIHtcbiAgICAgIHdpbmRvdy5kZWZlckxvYWRpbmdBbHBpbmUoZnVuY3Rpb24gKCkge1xuICAgICAgICB3aW5kb3cuQWxwaW5lLnN0YXJ0KCk7XG4gICAgICB9KTtcbiAgICB9IGVsc2Uge1xuICAgICAgd2luZG93LkFscGluZS5zdGFydCgpO1xuICAgIH1cbiAgfVxuXG4gIHJldHVybiBBbHBpbmU7XG5cbn0pKSk7XG4iLCIvKipcbiAqIFNob3BwZXIuanNcbiAqXG4gKiBGaXJzdCB3ZSB3aWxsIGxvYWQgYWxsIG9mIHRoaXMgcHJvamVjdCdzIEphdmFTY3JpcHQgZGVwZW5kZW5jaWVzIHdoaWNoXG4gKiBpbmNsdWRlcyBSZWFjdCBhbmQgb3RoZXIgaGVscGVycy4gSXQncyBhIGdyZWF0IHN0YXJ0aW5nIHBvaW50IHdoaWxlXG4gKiBidWlsZGluZyByb2J1c3QsIHBvd2VyZnVsIHdlYiBhcHBsaWNhdGlvbnMgdXNpbmcgUmVhY3QgKyBMYXJhdmVsLlxuICpcbiAqIEBhdXRob3IgQXJ0aHVyIE1vbm5leTxjb250YWN0QGFydGh1cm1vbm5leS5tZT5cbiAqIEB2ZXJzaW9uIDEuMC4wXG4gKiBAc2luY2UgTm92ZW1iZXIgMjAxOVxuICovXG5cbmltcG9ydCBcImFscGluZWpzXCI7IiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW4iXSwic291cmNlUm9vdCI6IiJ9