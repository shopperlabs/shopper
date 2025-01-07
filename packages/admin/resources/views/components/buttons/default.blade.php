@php
    $classes = 'inline-flex items-center gap-2 px-3 py-2 bg-white ring-1 ring-gray-950/10 shadow-sm text-[13px] font-medium rounded-lg text-gray-700 hover:bg-gray-50 dark:text-white dark:ring-white/20 dark:bg-white/5 dark:hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 disabled:cursor-not-allowed disabled:opacity-75'
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
