@php
    use Shopper\Core\Enum\ShipmentStatus;

    $steps = [
        ShipmentStatus::Pending,
        ShipmentStatus::PickedUp,
        ShipmentStatus::InTransit,
        ShipmentStatus::OutForDelivery,
        ShipmentStatus::Delivered,
    ];

    $statusToIndex = [
        ShipmentStatus::Pending->value => 0,
        ShipmentStatus::PickedUp->value => 1,
        ShipmentStatus::InTransit->value => 2,
        ShipmentStatus::AtSortingCenter->value => 2,
        ShipmentStatus::OutForDelivery->value => 3,
        ShipmentStatus::Delivered->value => 4,
    ];

    $currentIndex = $statusToIndex[$record->status?->value] ?? -1;
    $isFailed = $record->status === ShipmentStatus::DeliveryFailed;
    $isReturned = $record->status === ShipmentStatus::Returned;
    $isError = $isFailed || $isReturned;
@endphp

<div class="flex items-center gap-0.5">
    @foreach ($steps as $index => $step)
        @php
            $isCompleted = $currentIndex >= 0 && $index <= $currentIndex;
        @endphp

        <div @class([
            'flex size-7 items-center justify-center rounded-full',
            'bg-sh-fg text-sh-body' => $isCompleted && ! $isError,
            'bg-sh-muted text-sh-fg-muted' => ! $isCompleted && ! $isError,
            'bg-danger-100 text-danger-500 dark:bg-danger-500/10 dark:text-danger-400' => $isError,
        ])>
            <x-filament::icon :icon="$step->getIcon()" class="size-3.5" />
        </div>

        @if (! $loop->last)
            <div @class([
                'h-0.5 w-4',
                'bg-sh-fg' => $currentIndex >= 0 && $index < $currentIndex && ! $isError,
                'bg-sh-muted' => $currentIndex < 0 || $index >= $currentIndex || $isError,
            ])></div>
        @endif
    @endforeach
</div>
