@blaze

@props([
    'link',
    'title',
])

<x-shopper::link
    :href="$link"
    {{ $attributes->twMerge(['class' => 'inline-flex items-center py-1.5 px-2 text-sh-fg-muted rounded-md hover:text-sh-fg-secondary hover:bg-sh-muted']) }}
>
    {{ $title }}
</x-shopper::link>
