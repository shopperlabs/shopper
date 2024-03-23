@props(['style', 'value'])

@php
    $style = match($style ?? 'secondary') {
        'primary' => 'bg-primary-100 text-primary-800',
        'orange' => 'bg-orange-100 text-orange-800',
        'danger' => 'bg-danger-100 text-danger-800',
        'success' => 'bg-green-100 text-green-800',
        default => 'bg-secondary-100 text-secondary-800',
    };
@endphp

<span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full {{ $style }}">
    {{ $value }}
</span>
