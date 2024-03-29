import Sortable from 'sortablejs'

window.Sortable = Sortable

if (typeof window.Livewire === 'undefined') {
  throw 'Livewire Sortable Plugin: window.Livewire is undefined. Make sure @livewireScripts is placed above this script include'
}

const moveEndMorphMarker = (el) => {
  const endMorphMarker = Array.from(el.childNodes).filter((childNode) => {
    return childNode.nodeType === 8 && ['[if ENDBLOCK]><![endif]', '__ENDBLOCK__'].includes(childNode.nodeValue?.trim())
  })[0];

  if (endMorphMarker) {
    el.appendChild(endMorphMarker)
  }
}

Livewire.directive('sortable', ({ el, directive, component }) => {
  if (directive.modifiers.length > 0) {
    return
  }

  let options = {}

  if (el.hasAttribute('wire:sortable.options')) {
    options = (new Function(`return ${el.getAttribute('wire:sortable.options')};`))()
  }

  el.livewire_sortable = window.Sortable.create(el, {
    sort: true,
    ...options,
    draggable: '[wire\\:sortable\\.item]',
    handle: el.querySelector('[wire\\:sortable\\.handle]') ? '[wire\\:sortable\\.handle]' : null,
    dataIdAttr: 'wire:sortable.item',
    group: {
      pull: false,
      put: false,
      ...options.group,
      name: el.getAttribute('wire:sortable'),
    },
    store: {
      ...options.store,
      set: function (sortable) {
        let items = sortable.toArray().map((value, index) => {
          return {
            order: index + 1,
            value: value,
          }
        })

        moveEndMorphMarker(el)

        component.$wire.call(directive.method, items)
      },
    },
  })

  let hasSetHandleCorrectly = el.querySelector('[wire\\:sortable\\.item]') !== null

  // If there are already items, then the 'handle' option has already been correctly set.
  // The option does not have to reevaluated after the next Livewire component update.
  if (hasSetHandleCorrectly) {
    return
  }

  const currentComponent = component

  Livewire.hook('commit', ({ component, succeed }) => {
    if (component.id !== currentComponent.id) {
      return
    }

    if (hasSetHandleCorrectly) {
      return
    }

    succeed(() => {
      queueMicrotask(() => {
        el.livewire_sortable.option('handle', el.querySelector('[wire\\:sortable\\.handle]') ? '[wire\\:sortable\\.handle]' : null)

        hasSetHandleCorrectly = el.querySelector('[wire\\:sortable\\.item]') !== null
      })
    })
  })
})

Livewire.directive('sortable-group', ({ el, directive, component }) => {
  // Only fire this handler on the "root" group directive.
  if (! directive.modifiers.includes('item-group')) {
    return
  }

  let options = {}

  if (el.hasAttribute('wire:sortable-group.options')) {
    options = (new Function(`return ${el.getAttribute('wire:sortable-group.options')};`))();
  }

  el.livewire_sortable = window.Sortable.create(el, {
    sort: true,
    ...options,
    draggable: '[wire\\:sortable-group\\.item]',
    handle: '[wire\\:sortable-group\\.handle]',
    dataIdAttr: 'wire:sortable-group.item',
    group: {
      pull: true,
      put: true,
      ...options.group,
      name: el.closest('[wire\\:sortable-group]').getAttribute('wire:sortable-group'),
    },
    onSort: (evt) => {
      if (evt.to !== evt.from && el === evt.from) {
        return
      }

      let masterEl = el.closest('[wire\\:sortable-group]')

      let groups = Array.from(masterEl.querySelectorAll('[wire\\:sortable-group\\.item-group]')).map((el, index) => {
        moveEndMorphMarker(el)

        return {
          order: index + 1,
          value: el.getAttribute('wire:sortable-group.item-group'),
          items:  el.livewire_sortable.toArray().map((value, index) => {
            return {
              order: index + 1,
              value: value
            }
          }),
        }
      })

      masterEl.closest('[wire\\:id]').__livewire.$wire.call(masterEl.getAttribute('wire:sortable-group'), groups)
    },
  })
})
