@blaze

@props([
    'heading',
    'description' => null,
    'icon' => null,
    'action' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'flex flex-col items-center justify-center px-8 py-10 text-center']) }}
>
    @if ($icon)
        <div class="bg-sh-muted text-sh-fg flex size-12 items-center justify-center rounded-full">
            @svg($icon, 'size-5', ['aria-hidden' => 'true'])
        </div>
    @endif

    <h3 @class(['text-sh-fg font-medium', 'mt-2' => $icon])>
        {{ $heading }}
    </h3>
    @if ($description)
        <p class="text-sh-fg-muted mx-auto mt-1 max-w-lg text-sm">
            {{ $description }}
        </p>
    @endif

    @if ($action)
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>
