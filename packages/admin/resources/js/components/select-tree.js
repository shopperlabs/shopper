import Treeselect from 'treeselectjs'

export default function selectTree({
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
  direction = 'auto',
}) {
  return {
    state,

    /** @type Treeselect */
    tree: null,

    init() {
      this.tree = new Treeselect({
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
        rtl,
      })

      this.tree.srcElement.addEventListener('input', (e) => {
        this.state = e.detail
      })
    },
  }
}
