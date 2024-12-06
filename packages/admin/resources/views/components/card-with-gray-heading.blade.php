@props([
    'heading',
])

<x-shopper::card {{ $attributes->twMerge(['class' => 'bg-white p-2']) }}>
    <header class="flex items-start justify-between gap-2 px-2 py-2.5">
        {{ $heading }}
    </header>
    <div class="flex-1 overflow-hidden rounded-lg bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
        {{ $slot }}
    </div>
</x-shopper::card>
