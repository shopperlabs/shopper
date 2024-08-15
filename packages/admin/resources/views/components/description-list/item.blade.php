@props([
    'icon' => null,
    'heading',
    'content' => null,
])

<div {{ $attributes->twMerge(['class' => 'flex items-start space-x-3 overflow-hidden']) }}>
    @if ($icon)
        @svg($icon, 'mt-0.5 size-5 text-gray-400 dark:text-gray-500', ['aria-hidden' => true])
    @endif

    <div class="flex-1">
        <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-white">{{ $heading }}</dt>
        <dd class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
            @if ($content)
                {{ $content }}
            @else
                {{ $slot }}
            @endif
        </dd>
    </div>
</div>
