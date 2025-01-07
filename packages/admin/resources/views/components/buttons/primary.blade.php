@php
    $classes = 'inline-flex items-center gap-2 px-3 py-2 text-[13px] font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-75'
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
