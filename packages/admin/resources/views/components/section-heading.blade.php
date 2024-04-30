@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <x-filament::section.heading class="font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
        {{ $title }}
    </x-filament::section.heading>
    @if ($description)
        <x-filament::section.description class="mt-2 max-w-xl text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ $description }}
        </x-filament::section.description>
    @endif
</div>
