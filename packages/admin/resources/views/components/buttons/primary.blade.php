@php
    $classes =
        'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 inline-flex items-center gap-2 rounded-lg px-3 py-2 text-[13px] font-medium text-white shadow-sm focus:ring-2 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 dark:focus:ring-offset-gray-900';
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
