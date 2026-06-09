@blaze

@props([
    'title' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0']) }}
>
    @if ($title instanceof \Illuminate\View\ComponentSlot)
        {{ $title }}
    @else
        <h2 class="font-heading text-2xl font-bold text-sh-fg sm:truncate sm:text-3xl">
            {{ $title }}
        </h2>
    @endif

    @isset($action)
        {{ $action }}
    @endisset
</div>
