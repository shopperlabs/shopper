@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'inline-flex items-center p-2 text-base leading-6 font-medium rounded-md text-white bg-primary-900 focus:outline-none transition ease-in-out duration-150'
                : 'inline-flex items-center p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-primary-700 focus:outline-none focus:text-white focus:bg-primary-900 transition ease-in-out duration-150';
@endphp

<li  class="{{ $classes }}">
    <a {{ $attributes }}>
        {{ $slot }}
    </a>
</li>
