<span @class([
        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4',
        'bg-green-100 text-green-800' => $row->isAutomatic(),
        'bg-primary-100 text-primary-800' => ! $row->isAutomatic(),
    ])
>
    <svg @class([
        '-ml-1 mr-1.5 h-2 w-2',
        'text-green-400' => $row->isAutomatic(),
        'text-primary-400' => ! $row->isAutomatic(),
        ]) fill="currentColor" viewBox="0 0 8 8"
    >
        <circle cx="4" cy="4" r="3" />
    </svg>
    {{ $row->isAutomatic() ? __('shopper::pages/collections.automatic') : __('shopper::pages/collections.manual') }}
</span>
