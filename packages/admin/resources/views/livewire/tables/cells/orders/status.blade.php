@if ($record->status === \Shopper\Core\Enum\OrderStatus::Cancelled || $record->status === \Shopper\Core\Enum\OrderStatus::Archived)
    <x-filament::badge
        size="sm"
        :color="$record->status->getColor()"
        :icon="$record->status->getIcon()"
    >
        {{ $record->status->getLabel() }}
    </x-filament::badge>
@else
    <div class="flex items-center gap-1.5">
        <x-filament::badge
            size="sm"
            :color="$record->payment_status->getColor()"
            :icon="$record->payment_status->getIcon()"
        >
            {{ $record->payment_status->getLabel() }}
        </x-filament::badge>
        <x-filament::badge
            size="sm"
            :color="$record->shipping_status->getColor()"
            :icon="$record->shipping_status->getIcon()"
        >
            {{ $record->shipping_status->getLabel() }}
        </x-filament::badge>
    </div>
@endif
