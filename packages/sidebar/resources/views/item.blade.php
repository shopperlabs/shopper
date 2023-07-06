<li @class([$item->getItemClass()])>
    <a
        href="{{ $item->getUrl() }}"
        @class([
            $item->getClass(),
            'current' => $active,
        ])
        @if($item->getNewTab())target="_blank"@endif
    >
        @foreach($appends as $append)
            {!! $append !!}
        @endforeach

        <span class="truncate">{{ $item->getName() }}</span>

        @foreach($badges as $badge)
            {!! $badge !!}
        @endforeach
    </a>
</li>
