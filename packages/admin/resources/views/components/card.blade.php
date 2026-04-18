@blaze

@props([
    'title' => null,
    'description' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'sh-card bg-sh-card ring-sh-border overflow-hidden rounded-xl p-1 ring-1']) }}
>
    @if ($title)
        <header class="sh-card-header px-2 py-3">
            @if ($title instanceof \Illuminate\View\ComponentSlot)
                {{ $title }}
            @else
                <x-shopper::section-heading :$title :$description />
            @endif
        </header>
    @endif

    <div class="sh-card-content bg-sh-surface ring-sh-border overflow-hidden rounded-lg p-4 ring-1">
        {{ $slot }}
    </div>
</div>
