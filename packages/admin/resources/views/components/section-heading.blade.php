@blaze

@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <x-filament::section.heading class="font-heading text-sh-fg font-semibold">
        {{ $title }}
    </x-filament::section.heading>

    @if ($description)
        <x-filament::section.description class="text-sh-fg-muted mt-1 max-w-2xl text-sm">
            {{ $description }}
        </x-filament::section.description>
    @endif
</div>
