@props(['link', 'title'])

<a href="{{ $link }}" {{ $attributes->twMerge(['class' => 'inline-flex items-center py-1.5 px-2 text-secondary-500 rounded-md hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-200 hover:bg-secondary-50 dark:hover:bg-secondary-800']) }}>
    {{ $title }}
</a>
