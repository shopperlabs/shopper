@props(['link', 'title'])

<a href="{{ $link }}" {{ $attributes->merge(['class' => 'text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-200']) }}>
    {{ $title }}
</a>
