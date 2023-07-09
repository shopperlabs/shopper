<li @class([
        $item->getParentItemClass(),
        'current-group' => $active,
    ])
>
    <a
        href="{{ $item->getUrl() }}"
        @class([
            $item->getItemClass(),
            'current' => $active,
        ])
        @if($item->getNewTab())target="_blank"@endif
    >
        @if($item->iconSvg())
            {!! $item->getIcon() !!}
        @else
            @svg($item->getIcon(), $item->getIconClass())
        @endif

        <span class="truncate">{{ $item->getName() }}</span>

        @foreach($badges as $badge)
            {!! $badge !!}
        @endforeach

        @if($item->hasItems())
            {{ svg($item->getToggleIcon()) }}
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
