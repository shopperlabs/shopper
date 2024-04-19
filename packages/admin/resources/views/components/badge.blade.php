@props([
    'style' => null,
    'value',
])

@php
    $style = match ($style ?? 'gray') {
        'primary' => 'bg-primary-100 text-primary-800',
        'warning' => 'bg-warning-100 text-warning-800',
        'danger' => 'bg-danger-100 text-danger-800',
        'success' => 'bg-success-100 text-success-800',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white',
    };
@endphp

<span class="{{ $style }} inline-flex rounded-full px-2 py-0.5 text-xs font-medium leading-5">
    {{ $value }}
</span>
