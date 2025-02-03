@php
    $zone = $this->zone;
@endphp

<div>
    <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10">
        <div class="flex items-center justify-between p-4 lg:p-5">
            <div class="flex items-start space-x-3">
                <x-heroicon-o-truck class="size-6 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                <div class="space-y-1">
                    <x-filament::section.heading>
                        {{ __('shopper::pages/settings/zones.shipping_options.title') }}
                    </x-filament::section.heading>
                    <x-filament::section.description>
                        {{ __('shopper::pages/settings/zones.shipping_options.description') }}
                    </x-filament::section.description>
                </div>
            </div>
            <div class="flex items-center space-x-3 pl-4">
                <x-filament::button
                    wire:click="$dispatch(
                        'openPanel', {
                            component: 'shopper-slide-overs.shipping-option-form',
                            arguments: {{ json_encode(['zoneId' => $zone->id]) }}
                        })"
                    size="sm"
                    color="gray"
                    icon="untitledui-plus"
                >
                    {{ __('shopper::pages/settings/zones.shipping_options.add') }}
                </x-filament::button>
            </div>
        </div>
        <div class="p-4 lg:grid lg:grid-cols-2 lg:gap-6 lg:p-5">
            @forelse ($zone->shippingOptions as $shippingOption)
                <div
                    class="relative flex items-start justify-between rounded-lg border border-gray-200 bg-white px-6 py-4 shadow-sm dark:border-white/10 dark:bg-white/5"
                >
                    <div class="flex flex-1 flex-col leading-6">
                        <p class="flex items-center gap-2">
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $shippingOption->name }}
                            </span>
                            <x-filament::badge size="sm" :color="$shippingOption->isEnabled() ? 'success': 'gray'">
                                {{ $shippingOption->isEnabled() ? __('shopper::words.is_enabled') : __('shopper::words.is_disabled') }}
                            </x-filament::badge>
                        </p>
                        @if ($shippingOption->description)
                            <p class="mb-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ $shippingOption->description }}
                            </p>
                        @endif

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('shopper::forms.label.price') }}:
                            <span class="font-medium text-gray-600 dark:text-gray-300">
                                {{ \Illuminate\Support\Number::currency($shippingOption->price, in: $zone->currency->code) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                        {{ ($this->editAction)(['zone_id' => $zone->id, 'option_id' => $shippingOption->id]) }}
                        {{ ($this->deleteAction)(['id' => $shippingOption->id]) }}
                    </div>
                </div>
            @empty
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/settings/zones.shipping_options.empty_heading') }}
                </p>
            @endforelse
        </div>
    </x-shopper::card>

    <div x-data>
        <template x-teleport="body">
            <x-filament-actions::modals />
        </template>
    </div>
</div>
