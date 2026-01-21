<span
    @class([
        'sh-sidebar-item-badge',
        'sh-sidebar-item-badge-' . $badge->getColor() => $badge->getColor(),
        $badge->getClass(),
    ])
>
    {{ $badge->getValue() }}
</span>
