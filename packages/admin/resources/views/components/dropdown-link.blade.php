@blaze

<x-shopper::link
    {{ $attributes->twMerge(['class' => 'group flex items-center gap-2 rounded-lg px-4 py-2 text-sm leading-5 text-sh-fg-secondary hover:text-sh-fg hover:bg-sh-muted']) }}
>
    {{ $slot }}
</x-shopper::link>
