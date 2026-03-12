import Sortable from 'sortablejs'

const NestedSortable = (config) => {
  return {
    parentId: config.parentId ?? null,
    sortable: null,
    init() {
      this.sortable = new Sortable(this.$el, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        handle: '[data-sort-handle]',
        draggable: '> [data-sort-item]',
        onSort: () => {
          const orderedIds = this.sortable.toArray()
          this.$wire.reorder(orderedIds, this.parentId)
          this.updateFirstChildClass()
        },
      })
    },
    updateFirstChildClass() {
      if (this.parentId === null) return

      const items = this.$el.querySelectorAll(':scope > [data-sort-item]')
      items.forEach((item, index) => {
        const card = item.querySelector(':scope > div')
        if (!card) return
        index === 0 ? card.classList.add('rounded-tl-none', 'border-t-0') : card.classList.remove('rounded-tl-none', 'border-t-0')
      })
    },
    destroy() {
      if (this.sortable) {
        this.sortable.destroy()
      }
    },
  }
}

export default NestedSortable
