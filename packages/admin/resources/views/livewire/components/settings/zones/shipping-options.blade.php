@php
    $zone = $this->zone;
@endphp

<div>
    <x-shopper::card class="bg-gray-50 p-1">
        <x-slot name="title">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-start gap-3">
                    <x-phosphor-truck-trailer class="size-6 text-gray-400 dark:text-gray-500" aria-hidden="true" />
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

        <div class="lg:grid lg:grid-cols-2 lg:gap-6">
            @forelse ($zone->shippingOptions as $shippingOption)
                @php
                    $carrierLogoUrl = $shippingOption->carrier->logoUrl()
                        ?? \Shopper\Shipping\Facades\Shipping::driver($shippingOption->carrier->driver ?? 'manual')->logo();
                @endphp

                <div
                    class="relative flex items-start justify-between rounded-lg border border-gray-200 bg-white p-3 dark:border-white/10 dark:bg-white/5"
                >
                    <div>
                        <div class="flex items-center gap-2">
                            @if ($carrierLogoUrl)
                                <img
                                    class="size-6 rounded-full object-cover"
                                    src="{{ $carrierLogoUrl }}"
                                    alt="Logo {{ $shippingOption->carrier->name }}"
                                />
                            @endif

                            <p class="flex items-center gap-2 text-sm">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $shippingOption->name }}
                                </span>
                                <x-filament::badge size="sm" :color="$shippingOption->isEnabled() ? 'success': 'gray'">
                                    {{ $shippingOption->isEnabled() ? __('shopper::words.is_enabled') : __('shopper::words.is_disabled') }}
                                </x-filament::badge>
                            </p>
                        </div>
                        <div class="mt-2 space-y-0.5">
                            @if ($shippingOption->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $shippingOption->description }}
                                </p>
                            @endif

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('shopper::forms.label.price') }}:
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ \Illuminate\Support\Number::currency($shippingOption->price, in: $zone->currency->code) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        {{ ($this->editAction)(['zone_id' => $zone->id, 'option_id' => $shippingOption->id]) }}
                        {{ ($this->deleteAction)(['id' => $shippingOption->id]) }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/settings/zones.shipping_options.empty_heading') }}
                </p>
            @endforelse
        </div>
    </x-shopper::card>

    <x-filament-actions::modals />
</div>
