@blaze

@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <x-filament::section.heading class="font-heading font-semibold text-sh-fg">
        {{ $title }}
    </x-filament::section.heading>

    @if ($description)
        <x-filament::section.description class="mt-1 max-w-2xl text-sm text-sh-fg-muted">
            {{ $description }}
        </x-filament::section.description>
    @endif
</div>
