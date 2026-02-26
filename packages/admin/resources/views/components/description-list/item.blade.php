@blaze

@props([
    'heading',
    'icon' => null,
    'content' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'flex items-start space-x-3 overflow-hidden']) }}
>
    @if ($icon)
        @svg($icon, 'mt-0.5 size-5 text-gray-400 dark:text-gray-500', ['aria-hidden' => true])
    @endif

    <div class="flex-1">
        <dt class="text-sm leading-6 font-medium text-gray-900 dark:text-white">
            {{ $heading }}
        </dt>
        <dd class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            @if ($content)
                {{ $content }}
            @else
                {{ $slot }}
            @endif
        </dd>
    </div>
</div>
