@props([
    'stock',
])

<span
    @class([
        'me-2 inline-flex rounded-full px-1.5 text-xs leading-5 font-semibold',
        'bg-danger-100 text-danger-800' => $stock < 10,
        'bg-success-100 text-success-800' => $stock >= 10,
    ])
>
    {{ $stock }}
</span>
