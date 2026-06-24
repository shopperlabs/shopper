@php
    use Shopper\Core\Enum\ShipmentStatus;

    $order = $this->shipment->order;
    $carrier = $this->shipment->carrier;
    $shippingAddress = $order->shippingAddress;
    $carrierLogoUrl = $carrier?->logo();

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

    $currentIndex = $statusToIndex[$this->shipment->status?->value] ?? -1;
    $isFailed = $this->shipment->status === ShipmentStatus::DeliveryFailed;
    $isReturned = $this->shipment->status === ShipmentStatus::Returned;
    $isError = $isFailed || $isReturned;
@endphp

<x-shopper::slideover-card class="divide-y divide-sh-border">
    <div class="h-0 flex-1 overflow-y-auto py-4">
        <div class="px-4">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="font-heading text-xl font-bold text-sh-fg">
                            SHP-{{ $this->shipment->id }}
                        </h2>
                        @if ($this->shipment->status)
                            <x-filament::badge
                                size="md"
                                :color="$this->shipment->status->getColor()"
                                :icon="$this->shipment->status->getIcon()"
                            >
                                {{ $this->shipment->status->getLabel() }}
                            </x-filament::badge>
                        @endif
                    </div>
                    <p class="flex items-center gap-1 text-sm text-sh-fg-muted">
                        @if ($this->shipment->shipped_at)
                            <span>
                                {{ __('shopper::forms.label.shipped_at') }}
                                {{ $this->shipment->shipped_at->translatedFormat('j M Y H:i') }}
                            </span>
                            <span class="text-sh-fg-muted">&middot;</span>
                        @endif
                        <span>
                            {{ __('shopper::pages/orders.single') }}
                            <x-shopper::link
                                :href="route('shopper.orders.detail', $order)"
                                class="text-primary-600 hover:text-primary-500 font-medium underline"
                            >
                                {{ $order->number }}
                            </x-shopper::link>
                        </span>
                    </p>
                </div>
                <x-livewire-slide-over::close-icon />
            </div>

            @if ($shippingAddress || $carrier)
                <div class="mt-6 rounded-lg bg-sh-muted p-4">
                    <div class="flex items-center justify-between">
                        @if ($shippingAddress)
                            <div class="flex items-start gap-2">
                                <div class="mt-1 size-2 shrink-0 rounded-full bg-sh-fg-muted"></div>
                                <p class="text-sm text-sh-fg-secondary">
                                    {{ $shippingAddress->street_address }},
                                    {{ $shippingAddress->city }}
                                    {{ $shippingAddress->postal_code }}
                                </p>
                            </div>
                        @endif

                        @if ($carrier)
                            <div class="flex shrink-0 items-center gap-2 rounded-md border border-sh-border px-2.5 py-1.5">
                                @if ($carrierLogoUrl)
                                    <img
                                        src="{{ $carrierLogoUrl }}"
                                        class="size-5 rounded object-cover"
                                        alt="{{ $carrier->name }}"
                                    />
                                @endif
                                <span class="text-xs font-medium text-sh-fg-secondary">
                                    {{ $carrier->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Stepper -->
            <div class="mt-6 flex items-center justify-center rounded-lg bg-sh-muted px-6 py-5">
                <div class="flex items-center gap-1">
                    @foreach ($steps as $index => $step)
                        @php
                            $isCompleted = $currentIndex >= 0 && $index <= $currentIndex;
                        @endphp

                        <div class="flex flex-col items-center gap-1.5">
                            <div @class([
                                'flex size-9 items-center justify-center rounded-full',
                                'bg-sh-fg text-sh-body' => $isCompleted && ! $isError,
                                'bg-sh-muted-strong text-sh-fg-muted' => ! $isCompleted && ! $isError,
                                'bg-danger-100 text-danger-500 dark:bg-danger-500/10 dark:text-danger-400' => $isError,
                            ])>
                                <x-filament::icon :icon="$step->getIcon()" class="size-4" aria-hidden="true" />
                            </div>
                            <span @class([
                                'text-[10px] font-medium',
                                'text-sh-fg' => $isCompleted && ! $isError,
                                'text-sh-fg-muted' => ! $isCompleted && ! $isError,
                                'text-danger-500 dark:text-danger-400' => $isError,
                            ])>
                                {{ $step->getLabel() }}
                            </span>
                        </div>

                        @if (! $loop->last)
                            <div @class([
                                'mb-5 h-0.5 w-8',
                                'bg-sh-fg' => $currentIndex >= 0 && $index < $currentIndex && ! $isError,
                                'bg-sh-muted-strong' => $currentIndex < 0 || $index >= $currentIndex || $isError,
                            ])></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-4 border-b border-sh-border pb-6">
                <div>
                    <dt class="text-xs font-medium text-sh-fg-muted">
                        {{ __('shopper::forms.label.shipped_at') }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-sh-fg">
                        {{ $this->shipment->shipped_at?->translatedFormat('j M Y H:i') ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-sh-fg-muted">
                        {{ __('shopper::forms.label.received_at') }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-sh-fg">
                        {{ $this->shipment->received_at?->translatedFormat('j M Y H:i') ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-sh-fg-muted">
                        {{ __('shopper::forms.label.tracking_number') }}
                    </dt>
                    <dd class="mt-1 text-sm font-medium text-sh-fg">
                        @if ($this->shipment->tracking_number)
                            @if ($this->shipment->tracking_url)
                                <a
                                    href="{{ $this->shipment->tracking_url }}"
                                    target="_blank"
                                    class="text-primary-600 hover:text-primary-500 underline"
                                >
                                    {{ $this->shipment->tracking_number }}
                                </a>
                            @else
                                {{ $this->shipment->tracking_number }}
                            @endif
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </div>

            @if ($this->shipment->items->isNotEmpty())
                <div class="mt-6 border-b border-sh-border pb-6">
                    <h3 class="text-sm font-medium text-sh-fg">
                        {{ __('shopper::pages/products.menu') }}
                    </h3>
                    <div class="mt-3 space-y-2.5">
                        @foreach ($this->shipment->items as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img
                                        class="size-6 shrink-0 rounded object-cover"
                                        src="{{ $item->product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                        alt="{{ $item->name }}"
                                    />
                                    <span class="text-sm text-sh-fg-secondary">
                                        {{ $item->name }} &times; {{ $item->quantity }}
                                    </span>
                                </div>

                                @if ($item->fulfillment_status)
                                    <x-filament::badge
                                        size="sm"
                                        :color="$item->fulfillment_status->getColor()"
                                        :icon="$item->fulfillment_status->getIcon()"
                                    >
                                        {{ $item->fulfillment_status->getLabel() }}
                                    </x-filament::badge>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-sh-fg">
                        {{ __('shopper::pages/orders.shipment.timeline') }}
                    </h3>
                    @if (count($this->shipment->allowedTransitions()) > 0)
                        <x-filament::button
                            wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.shipment-add-event', arguments: { shipment: {{ $this->shipment->id }} } })"
                            size="sm"
                            color="gray"
                            icon="untitledui-plus"
                        >
                            {{ __('shopper::pages/orders.shipment.add_event') }}
                        </x-filament::button>
                    @endif
                </div>

                <div class="mt-4 pl-2">
                    @if ($this->events->isEmpty())
                        <p class="text-sm text-sh-fg-muted">
                            {{ __('shopper::pages/orders.shipment.no_events') }}
                        </p>
                    @else
                        <ol class="relative border-l border-sh-border">
                            @foreach ($this->events as $event)
                                <li class="mb-6 ml-6 last:mb-0">
                                    <span
                                        @class([
                                            'absolute -left-3 flex size-6 items-center justify-center rounded-full ring-4 ring-sh-surface',
                                            'bg-blue-100 dark:bg-blue-900' => $event->status->getColor() === 'info',
                                            'bg-primary-100 dark:bg-primary-900' => $event->status->getColor() === 'primary',
                                            'bg-indigo-100 dark:bg-indigo-900' => $event->status->getColor() === 'indigo',
                                            'bg-yellow-100 dark:bg-yellow-900' => $event->status->getColor() === 'warning',
                                            'bg-green-100 dark:bg-green-900' => $event->status->getColor() === 'green',
                                            'bg-success-100 dark:bg-success-900' => $event->status->getColor() === 'success',
                                            'bg-red-100 dark:bg-red-900' => $event->status->getColor() === 'danger',
                                            'bg-sh-muted' => $event->status->getColor() === 'gray',
                                        ])
                                    >
                                        <x-filament::icon
                                            :icon="$event->status->getIcon()"
                                            class="size-3.5 text-sh-fg-secondary"
                                            aria-hidden="true"
                                        />
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-medium text-sh-fg">
                                            {{ $event->status->getLabel() }}
                                        </h4>
                                        <time class="text-xs text-sh-fg-muted">
                                            {{ $event->occurred_at->translatedFormat('j M Y H:i') }}
                                        </time>
                                    </div>
                                    @if ($event->location)
                                        <p class="mt-0.5 text-xs text-sh-fg-muted">
                                            {{ $event->location }}
                                        </p>
                                    @endif
                                    @if ($event->description)
                                        <p class="mt-1 text-sm text-sh-fg-secondary">
                                            {{ $event->description }}
                                        </p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-shopper::slideover-card>
