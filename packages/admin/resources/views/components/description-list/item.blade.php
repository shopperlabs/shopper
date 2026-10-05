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
        @svg($icon, 'text-sh-fg-muted mt-0.5 size-5', ['aria-hidden' => true])
    @endif

    <div class="flex-1">
        <dt class="text-sh-fg text-sm leading-6 font-medium">
            {{ $heading }}
        </dt>
        <dd class="text-sh-fg-muted mt-1 text-sm">
            @if ($content)
                {{ $content }}
            @else
                {{ $slot }}
            @endif
        </dd>
    </div>
</div>
