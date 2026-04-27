@blaze

@props([
    'title' => null,
    'description' => null,
    'footer' => null
])

<div
    {{ $attributes->twMerge(['class' => 'sh-card bg-sh-card ring-sh-border overflow-hidden rounded-xl p-0.5 ring-1']) }}
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

    <div class="sh-card-content bg-sh-surface ring-sh-border overflow-hidden rounded-[10px] p-4 ring-1">
        {{ $slot }}
    </div>

    @if ($footer)
        {{ $footer }}
    @endif
</div>
