@props([
    'withBorder' => false,
])

<div
    {{ $attributes->twMerge([
        'class' => 'relative flex flex-col lg:min-h-screen lg:flex-row',
    ]) }}
>
    @isset($sidebar)
        <aside
            @class([
                'w-full shrink-0 lg:sticky lg:top-0 lg:max-h-screen lg:w-60 lg:overflow-y-auto lg:self-start',
                'border-sh-border border-b lg:border-b-0' => $withBorder,
            ])
        >
            {{ $sidebar }}
        </aside>
    @endisset

    <div class="min-w-0 flex-1">
        {{ $slot }}
    </div>

    @if ($withBorder && isset($sidebar))
        <span
            class="bg-sh-border pointer-events-none absolute top-0 bottom-0 left-60 hidden w-px lg:block"
            aria-hidden="true"
        ></span>
    @endif
</div>
