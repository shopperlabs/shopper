@props([
    'link',
    'title',
])

<x-shopper::link
    :href="$link"
    {{ $attributes->twMerge(['class' => 'inline-flex items-center py-1.5 px-2 text-gray-500 rounded-md hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800']) }}
>
    {{ $title }}
</x-shopper::link>
