@props([
    'stock',
])

<span
    @class([
        'mr-2 inline-flex rounded-full px-1.5 text-xs font-semibold leading-5',
        'bg-danger-100 text-danger-800' => $stock < 10,
        'bg-success-100 text-success-800' => $stock >= 10,
    ])
>
    {{ $stock }}
</span>
