<a title="{{ $append->getName() }}" href="{{ $append->getUrl() }}" @class(['sh-sidebar-item-append', $append->getClass()])>
    @if ($append->iconSvg())
        {!! $append->getIcon() !!}
    @else
        @svg($append->getIcon(), $append->getIconClass())
    @endif
</a>
