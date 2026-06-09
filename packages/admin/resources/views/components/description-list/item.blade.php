@blaze

@props([
    'heading',
    'icon' => null,
    'content' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'flex items-start space-x-3']) }}
>
    @if ($icon)
        @svg($icon, 'mt-0.5 size-5 text-sh-fg-muted', ['aria-hidden' => true])
    @endif

    <div class="flex-1">
        <dt class="text-sm leading-6 font-medium text-sh-fg">
            {{ $heading }}
        </dt>
        <dd class="mt-1 text-sm text-sh-fg-muted">
            @if ($content)
                {{ $content }}
            @else
                {{ $slot }}
            @endif
        </dd>
    </div>
</div>
