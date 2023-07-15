<li @class([
        $item->getParentItemClass(),
        'current-group' => $active,
    ])
>
    <a
        href="{{ $item->getUrl() }}"
        @class([
            $item->getItemClass(),
            $item->getActiveClass() => $active,
            $item->getInactiveClass() => ! $active,
        ])
        @if($item->getNewTab())target="_blank"@endif
    >
        @if($item->getIcon() !== null)
            @if($item->iconSvg())
                {!! $item->getIcon() !!}
            @else
                @svg($item->getIcon(), $item->getIconClass())
            @endif
        @endif

        <span class="item-name">{{ $item->getName() }}</span>

        @foreach($badges as $badge)
            {!! $badge !!}
        @endforeach

        @if($item->hasItems())
            <span class="toggle">
                @if($item->toggleIconSvg())
                    {!! $active ? $item->getActiveToggleIcon() : $item->getToggleIcon() !!}
                @else
                    @svg(
                        $active ? $item->getActiveToggleIcon() : $item->getToggleIcon(),
                        $active ? $item->getActiveToggleIconClass() : $item->getToggleIconClass()
                   )
                @endif
            </span>
        @endif
    </a>

    @foreach($appends as $append)
        {!! $append !!}
    @endforeach

    @if(count($items) > 0)
        <ul role="list" class="submenu">
            @foreach($items as $item)
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
