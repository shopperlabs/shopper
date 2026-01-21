<div
    {{ $attributes->merge(['class' => $class]) }}
    x-data
    x-bind:class="{
        'sh-si-open': $store.sidebar.isOpen,
        'sh-si-collapsed': $store.sidebar.isCollapsed,
    }"
    x-bind:style="$store.sidebar.isCollapsed
        ? 'width: var(--sidebar-collapsed-width)'
        : 'width: var(--sidebar-width)'"
    @keydown.escape.window="$store.sidebar.close()"
>
    @if ($collapsible)
        <button
            type="button"
            class="sidebar-toggle-btn"
            x-on:click="$store.sidebar.toggleCollapse()"
            x-show="$store.sidebar.collapsible"
        >
            <span x-show="$store.sidebar.isCollapsed" x-cloak>
                <x-untitledui-chevron-right class="size-5" aria-hidden="true" />
            </span>
            <span x-show="!$store.sidebar.isCollapsed" x-cloak>
                <x-untitledui-chevron-left class="size-5" aria-hidden="true" />
            </span>
        </button>
    @endif

    <nav class="sh-si-content" x-show="$store.sidebar.isOpen || window.innerWidth >= $store.sidebar.breakpoint">
        {!! $renderedSidebar !!}
    </nav>
</div>
