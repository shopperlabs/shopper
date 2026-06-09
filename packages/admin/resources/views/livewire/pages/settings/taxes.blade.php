<x-shopper::container>
    <div class="space-y-2">
        <x-shopper::heading :title="__('shopper::pages/settings/taxes.title')">
            <x-slot name="action">
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.tax-zone-form' })"
                    icon="untitledui-plus"
                >
                    {{ __('shopper::pages/settings/taxes.add_action') }}
                </x-filament::button>
            </x-slot>
        </x-shopper::heading>
        <p class="text-sm text-sh-fg-muted">
            {{ __('shopper::pages/settings/taxes.description') }}
        </p>
    </div>

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:gap-y-6">
        <aside class="lg:sticky lg:top-4 lg:self-start">
            <x-shopper::card class="[&_.sh-card-content]:p-0 [&>div:first-of-type]:p-0">
                <div class="divide-y divide-sh-border">
                    @forelse ($this->taxZones as $taxZone)
                        @php
                            $isSelected = (int) $currentTaxZoneId === (int) $taxZone->id;
                        @endphp
                        <button
                            type="button"
                            wire:key="tax-zone-{{ $taxZone->id }}"
                            wire:click="$set('currentTaxZoneId', {{ $taxZone->id }})"
                            @class([
                                'group flex w-full items-start gap-4 border-l-2 p-4 text-left transition',
                                'border-l-gray-900 bg-sh-muted dark:border-l-white' => $isSelected,
                                'border-l-transparent hover:bg-sh-muted' => ! $isSelected,
                            ])
                        >
                            <img
                                src="{{ $taxZone->country->svg_flag }}"
                                alt="{{ $taxZone->country->translated_name }}"
                                class="size-6 shrink-0 rounded-full object-cover"
                            />

                            <div class="min-w-0 flex-1 space-y-1.5">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-sm font-semibold text-sh-fg">
                                        {{ $taxZone->display_name }}
                                    </span>
                                    <x-filament::badge size="sm" :color="$taxZone->is_tax_inclusive ? 'success' : 'warning'">
                                        {{ $taxZone->is_tax_inclusive
                                            ? __('shopper::pages/settings/taxes.inclusive')
                                            : __('shopper::pages/settings/taxes.exclusive')
                                        }}
                                    </x-filament::badge>
                                </div>
                                @if ($taxZone->province_code)
                                    <p class="text-xs text-sh-fg-muted">
                                        {{ $taxZone->province_code }}
                                    </p>
                                @endif
                            </div>
                        </button>
                    @empty
                        <x-shopper::empty-card
                            :heading="__('shopper::pages/settings/taxes.empty_heading')"
                            icon="untitledui-coins-02"
                        />
                    @endforelse
                </div>
            </x-shopper::card>
        </aside>

        <div class="mt-6 space-y-4 lg:col-span-2 lg:mt-0">
            @if ($currentTaxZoneId)
                <livewire:shopper-settings.taxes.detail :$currentTaxZoneId :key="$currentTaxZoneId" />

                <livewire:shopper-settings.taxes.rates
                    :selectedTaxZoneId="$currentTaxZoneId"
                    :key="'rates-' . $currentTaxZoneId"
                />
            @else
                <x-shopper::card>
                    <x-shopper::empty-card
                        icon="untitledui-coins-02"
                        :heading="__('shopper::pages/settings/taxes.empty_detail_heading')"
                        :description="__('shopper::pages/settings/taxes.empty_detail_description')"
                    />
                </x-shopper::card>
            @endif
        </div>
    </div>
</x-shopper::container>
