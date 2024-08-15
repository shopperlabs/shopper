@php
    $classes = 'inline-flex items-center px-4 py-2 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-white bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 disabled:cursor-not-allowed disabled:opacity-75'
@endphp

@isset($link)
    <x-shopper::link href="{{ $link }}" {{ $attributes->twMerge(['class' => $classes]) }}>
        {{ $slot }}
    </x-shopper::link>
@else
    <button {{ $attributes->twMerge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endisset
