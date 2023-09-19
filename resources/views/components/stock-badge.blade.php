@props(['stock'])

<span @class([
    'mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full',
    'bg-red-100 text-red-800' => $stock < 10,
    'bg-green-100 text-green-800' => $stock >= 10,
])>
    {{ $stock }}
</span>
