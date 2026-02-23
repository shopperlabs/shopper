@php
    $carrier = $record->carrier;
    $logoUrl = $carrier
        ? ($carrier->logoUrl() ?? \Shopper\Shipping\Facades\Shipping::driver($carrier->driver ?? 'manual')->logo())
        : null;
@endphp

<div class="flex items-center gap-2">
    @if ($logoUrl)
        <img src="{{ $logoUrl }}" class="size-6 shrink-0 rounded-md object-cover" alt="{{ $carrier?->name }}" />
    @endif
    <span class="text-sm text-gray-700 dark:text-gray-300">
        {{ $carrier?->name ?? '—' }}
    </span>
</div>
