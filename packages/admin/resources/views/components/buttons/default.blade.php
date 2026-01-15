@php
    $classes =
        'inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-[13px] font-medium text-gray-700 shadow-sm ring-1 ring-gray-950/10 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-75 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:hover:bg-white/10 dark:focus:ring-offset-gray-900';
@endphp

@isset($link)
    <x-shopper::link :href="$link" {{ $attributes->twMerge(['class' => $classes]) }}>
        {{ $slot }}
    </x-shopper::link>
@else
    <button {{ $attributes->twMerge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endisset
