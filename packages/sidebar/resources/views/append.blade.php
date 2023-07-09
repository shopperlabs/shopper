<a title="{{ $append->getName() }}" href="{{ $append->getUrl() }}" @class([$append->getClass()])>
    @if($append->iconSvg())
        {!! $append->getIcon() !!}
    @else
        @svg($append->getIcon(), $append->getIconClass())
    @endif
</a>
