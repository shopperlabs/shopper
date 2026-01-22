@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <x-filament::section.heading class="font-heading font-semibold text-gray-950 dark:text-white">
        {{ $title }}
    </x-filament::section.heading>

    @if ($description)
        <x-filament::section.description class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $description }}
        </x-filament::section.description>
    @endif
</div>
