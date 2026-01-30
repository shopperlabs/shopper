@props([
    "menu",
])

@php
    $url = $menu->url();
    $isCurrent = $url && request()->is(trim(parse_url($url, PHP_URL_PATH), '/') . '*');
@endphp

<a
    href="{{ $url ?? "#" }}"
    @class([
        "border-b-[3px] px-1 py-4 text-sm font-medium whitespace-nowrap select-none",
        "current border-primary-500 text-primary-600 dark:text-primary-500" => $isCurrent,
        "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:text-gray-300" => ! $isCurrent,
    ])
    @if ($isCurrent)
        aria-current="page"
    @endif
    wire:navigate
>
    {{ $menu->name() }}

    @if (! $url)
        <span
            class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300"
        >
            <svg
                class="mr-1.5 -ml-0.5 size-2 text-gray-400 dark:text-gray-500"
                fill="currentColor"
                viewBox="0 0 8 8"
                aria-hidden="true"
            >
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ __("shopper::layout.soon") }}
        </span>
    @endif
</a>
