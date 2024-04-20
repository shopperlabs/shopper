@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <h3 class="font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
        {{ $title }}
    </h3>
    @if ($description)
        <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ $description }}
        </p>
    @endif
</div>
