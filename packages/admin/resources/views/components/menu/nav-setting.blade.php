@props([
    'menu',
])

@php
    $url = $menu->url();
    $isCurrent = $url && request()->is(trim(parse_url($url, PHP_URL_PATH), '/') . '*');
@endphp

<a
    href="{{ $url ?? '#' }}"
    @class([
        'border-b-[3px] px-1 py-4 text-sm font-medium whitespace-nowrap select-none',
        'current border-primary-500 text-primary-600 dark:text-primary-500' => $isCurrent,
        'text-sh-fg-muted hover:border-sh-border hover:text-sh-fg-secondary border-transparent' => ! $isCurrent,
    ])
    @if ($isCurrent)
        aria-current="page"
    @endif
    wire:navigate
>
    {{ $menu->name() }}

    @if (! $url)
        <span
            class="bg-sh-muted text-sh-fg-secondary ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
        >
            <svg
                class="text-sh-fg-muted mr-1.5 -ml-0.5 size-2"
                fill="currentColor"
                viewBox="0 0 8 8"
                aria-hidden="true"
            >
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ __('shopper::words.soon') }}
        </span>
    @endif
</a>
