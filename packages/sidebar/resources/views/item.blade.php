<li
    @class([
        'sh-sidebar-item',
        $item->getParentItemClass(),
        'sh-items-has-child' => $item->hasItems(),
    ])
    x-data="{ url: {{ \Illuminate\Support\Js::from($item->getUrl()) }} }"
    x-bind:class="{ 'sh-sidebar-item-active': $store.sidebar?.isActive(url) }"
>
    <a
        href="{{ $item->getUrl() }}"
        @class([
            'sh-sidebar-item-link',
            $item->getItemClass(),
        ])
        x-bind:class="{ '{{ $item->getActiveClass() }}': $store.sidebar?.isActive(url) }"
        @if($item->withSpa()) wire:navigate @endif
        @if($item->getNewTab()) target="_blank" @endif
        x-on:click="if (window.innerWidth < ($store.sidebar?.breakpoint ?? 1024)) { $store.sidebar?.close() }"
        x-tooltip="{
            content: {{ \Illuminate\Support\Js::from($item->getName()) }},
            placement: 'right',
            theme: $store.theme,
            onShow: () => $store.sidebar?.isCollapsed,
        }"
    >
        @if ($item->getIcon() !== null)
            @if ($item->iconSvg())
                <span class="sh-sidebar-item-icon">{!! $item->getIcon() !!}</span>
            @else
                {{ svg(
                        name: $item->getIcon(),
                        class: 'sh-sidebar-item-icon ' . $item->getIconClass(),
                        attributes: $item->getIconAttributes()
                  ) }}
            @endif
        @else
            <span class="sh-sidebar-item-dot" aria-hidden="true"></span>
        @endif

        <span
            class="sh-sidebar-item-label"
            x-show="!$store.sidebar?.isCollapsed"
            x-transition:enter="transition-opacity duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            {{ $item->getName() }}
        </span>

        <div class="sh-sidebar-item-nav">
            @foreach ($badges as $badge)
                <span x-show="!$store.sidebar?.isCollapsed">
                    {!! $badge !!}
                </span>
            @endforeach

            @if ($item->hasItems())
                <span class="sh-sidebar-item-toggle" x-show="!$store.sidebar?.isCollapsed">
                    @if ($item->toggleIconSvg())
                        {!! $active ? $item->getActiveToggleIcon() : $item->getToggleIcon() !!}
                    @else
                        @svg(
                            $active ? $item->getActiveToggleIcon() : $item->getToggleIcon(),
                            $active ? $item->getActiveToggleIconClass() : $item->getToggleIconClass()
                       )
                    @endif
                </span>
            @endif
        </div>
    </a>

    @foreach ($appends as $append)
        <span x-show="!$store.sidebar?.isCollapsed">
            {!! $append !!}
        </span>
    @endforeach

    @if (count($items) > 0)
        <ul role="list" class="sh-sidebar-list-items sh-submenu">
            @foreach ($items as $item)
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
