@props([
    'title' => null,
    'description' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'sh-card p-1.5 bg-gray-50 dark:bg-gray-950 rounded-lg ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden']) }}
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

    <div class="sh-card-content bg-white dark:bg-gray-900 ring-1 ring-gray-200 rounded-lg dark:ring-white/10 p-4 overflow-hidden">
        {{ $slot }}
    </div>
</div>
