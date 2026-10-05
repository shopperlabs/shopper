@php
    $zone = $this->zone;
    $groupedOptions = $zone->shippingOptions->groupBy('carrier_id');
@endphp

<div>
    <x-shopper::card class="[&_.sh-card-content]:p-0">
        <x-slot name="title">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-start gap-3">
                    <x-phosphor-truck-trailer class="text-sh-fg-muted size-6" aria-hidden="true" />
                    <x-shopper::section-heading
                        class="space-y-1"
                        :title="__('shopper::pages/settings/zones.shipping_options.title')"
                        :description="__('shopper::pages/settings/zones.shipping_options.description')"
                    />
                </div>
                <div class="flex items-center gap-3 pl-4">
                    <x-filament::button
                        wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.shipping-option-form', arguments: { zoneId: {{ $zone->id }} } })"
                        size="sm"
                        color="gray"
                        icon="untitledui-plus"
                    >
                        {{ __('shopper::pages/settings/zones.shipping_options.add') }}
                    </x-filament::button>
                </div>
            </div>
        </x-slot>

        @forelse ($groupedOptions as $carrierId => $options)
            @php
                $carrier = $options->first()->carrier;
                $carrierLogoUrl = $carrier?->logo();
            @endphp

            <div class="ring-sh-border mb-4 overflow-hidden rounded-lg ring-1 last:mb-0">
                <div class="bg-sh-muted flex items-center gap-2 px-4 py-2.5">
                    @if ($carrierLogoUrl)
                        <img
                            class="size-5 shrink-0 object-contain"
                            src="{{ $carrierLogoUrl }}"
                            alt="{{ $carrier->name }}"
                        />
                    @else
                        <x-untitledui-truck class="text-sh-fg-muted size-5 shrink-0" aria-hidden="true" />
                    @endif
                    <span class="text-sh-fg text-sm font-semibold">
                        {{ $carrier?->name ?? __('shopper::words.unknown') }}
                    </span>
                </div>

                <div class="divide-sh-border divide-y">
                    @foreach ($options as $shippingOption)
                        <div class="bg-sh-surface flex items-start justify-between gap-4 px-4 py-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sh-fg text-sm font-medium">
                                        {{ $shippingOption->name }}
                                    </span>
                                    @unless ($shippingOption->isEnabled())
                                        <x-filament::badge size="sm" color="gray">
                                            {{ __('shopper::words.is_disabled') }}
                                        </x-filament::badge>
                                    @endunless
                                </div>
                                @if ($shippingOption->description)
                                    <p class="text-sh-fg-muted mt-1 text-sm">
                                        {{ $shippingOption->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex shrink-0 items-center gap-3">
                                <span class="text-sh-fg text-sm font-semibold">
                                    {{ shopper_money_format($shippingOption->price, $zone->currency->code) }}
                                </span>
                                <div class="flex items-center gap-1">
                                    {{ ($this->editAction)(['zone_id' => $zone->id, 'option_id' => $shippingOption->id]) }}
                                    {{ ($this->deleteAction)(['id' => $shippingOption->id]) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <x-shopper::empty-card
                icon="untitledui-truck"
                :heading="__('shopper::pages/settings/zones.shipping_options.empty_heading')"
            />
        @endforelse
    </x-shopper::card>

    <x-filament-actions::modals />
</div>
