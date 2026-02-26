<li
    @class(['sh-sidebar-group', $group->getClass()])
    x-data="{ label: @js($group->getName()) }"
    x-bind:class="{ 'sh-sidebar-group-collapsed': label && $store.sidebar?.isGroupCollapsed(label) }"
>
    @if ($group->shouldShowHeading())
        <div
            @class(['sh-sidebar-group-label', $group->getHeadingClass()])
            @if ($group->isCollapsible())
                role="button"
                x-on:click="if (label) $store.sidebar?.toggleGroup(label)"
            @endif
        >
            <span
                x-show="!$store.sidebar?.isCollapsed"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                {{ $group->getName() }}
            </span>

            @if ($group->isCollapsible())
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
            @endif
        </div>
    @endif

    <ul
        role="list"
        @class(['sh-sidebar-group-items', $group->getGroupItemsClass()])
        x-show="!label || !$store.sidebar?.isGroupCollapsed(label)"
        x-collapse
    >
        @foreach ($items as $item)
            {!! $item !!}
        @endforeach
    </ul>
</li>
