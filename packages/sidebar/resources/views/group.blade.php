<li
    @class(['sh-sidebar-group', $group->getClass()])
    x-data="{ label: @js($group->getName()) }"
    x-bind:class="{ 'sh-sidebar-group-collapsed': label && $store.sidebar?.isGroupCollapsed(label) }"
>
    @if ($group->shouldShowHeading() && $group->isCollapsible())
        <button
            type="button"
            @class(['sh-sidebar-group-label', $group->getHeadingClass()])
            x-on:click="if (label) $store.sidebar?.toggleGroup(label)"
            x-bind:aria-expanded="!$store.sidebar?.isGroupCollapsed(label)"
            x-bind:disabled="$store.sidebar?.isCollapsed"
        >
            <span
                class="truncate"
                x-show="!$store.sidebar?.isCollapsed"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                {{ $group->getName() }}
            </span>

            <span x-show="!$store.sidebar?.isCollapsed" class="sh-sidebar-group-toggle">
                <x-untitledui-minus
                    x-show="!$store.sidebar?.isGroupCollapsed(label)"
                    class="size-3.5"
                    aria-hidden="true"
                />
                <x-untitledui-plus
                    x-show="$store.sidebar?.isGroupCollapsed(label)"
                    class="size-3.5"
                    aria-hidden="true"
                />
            </span>
        </button>
    @elseif ($group->shouldShowHeading())
        <div @class(['sh-sidebar-group-label', $group->getHeadingClass()])>
            <span
                class="truncate"
                x-show="!$store.sidebar?.isCollapsed"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                {{ $group->getName() }}
            </span>
        </div>
    @endif

    <ul
        role="list"
        @class(['sh-sidebar-group-items', $group->getGroupItemsClass()])
        x-show="!label || $store.sidebar?.isCollapsed || !$store.sidebar?.isGroupCollapsed(label)"
        x-collapse
    >
        @foreach ($items as $item)
            {!! $item !!}
        @endforeach
    </ul>
</li>
