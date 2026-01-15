@php
    $classes =
        'inline-flex items-center gap-2 rounded-lg bg-primary-600 px-3 py-2 text-[13px] font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-75 dark:focus:ring-offset-gray-900';
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
