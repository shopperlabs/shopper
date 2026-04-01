<x-shopper::container>
    <x-shopper::breadcrumb
        :back="route('shopper.settings.index')"
        :current="__('shopper::pages/settings/taxes.title')"
    >
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.index')"
            :title="__('shopper::pages/settings/global.menu')"
        />
    </x-shopper::breadcrumb>

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <aside class="lg:sticky lg:top-4">
            <x-shopper::card class="max-w-lg [&>div:first-of-type]:p-0">
                <x-slot name="title">
                    <div class="flex items-start justify-between gap-2 px-2">
                        <x-shopper::section-heading
                            :title="__('shopper::pages/settings/taxes.title')"
                            :description="__('shopper::pages/settings/taxes.description')"
                        />
                        <div class="flex h-7 items-center">
                            <button
                                type="button"
                                wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.tax-zone-form' })"
                                title="{{ __('shopper::pages/settings/taxes.add_action') }}"
                                class="relative text-gray-400 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:text-gray-400"
                            >
                                <span class="absolute -inset-2.5"></span>
                                <span class="sr-only">{{ __('shopper::pages/settings/taxes.add_action') }}</span>
                                <x-untitledui-plus class="size-6" aria-hidden="true" />
                            </button>
                        </div>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse ($this->taxZones as $taxZone)
                        <label
                            wire:key="tax-zone-{{ $taxZone->id }}"
                            for="tax-zone-{{ $taxZone->id }}"
                            class="relative flex cursor-pointer bg-white p-4 focus:outline-none dark:bg-gray-900"
                        >
                            <x-filament::input.radio
                                name="taxZone"
                                value="{{ $taxZone->id }}"
                                id="tax-zone-{{ $taxZone->id }}"
                                wire:model.live="currentTaxZoneId"
                                class="mt-0.5"
                                aria-labelledby="tax-zone-{{ $taxZone->id }}-label"
                                aria-describedby="tax-zone-{{ $taxZone->id }}-description"
                            />
                            <span class="ml-3 flex flex-col space-y-1">
                                <span id="tax-zone-{{ $taxZone->id }}-label" class="flex items-center gap-x-2">
                                    <img
                                        src="{{ $taxZone->country->svg_flag }}"
                                        alt="{{ $taxZone->country->translated_name }}"
                                        class="size-5 shrink-0 rounded-full object-cover"
                                    />
                                    <span
                                        @class([
                                            'block text-sm font-medium',
                                            'text-primary-600 dark:text-primary-700' => $currentTaxZoneId === $taxZone->id,
                                            'text-gray-900 dark:text-white' => $currentTaxZoneId !== $taxZone->id,
                                        ])
                                    >
                                        {{ $taxZone->display_name }}
                                    </span>
                                    <x-filament::badge size="sm" :color="$taxZone->is_tax_inclusive ? 'success': 'warning'">
                                        {{ $taxZone->is_tax_inclusive
                                            ? __('shopper::pages/settings/taxes.inclusive')
                                            : __('shopper::pages/settings/taxes.exclusive')
                                        }}
                                    </x-filament::badge>
                                </span>
                                @if ($taxZone->province_code)
                                    <span
                                        id="tax-zone-{{ $taxZone->id }}-description"
                                        class="block text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $taxZone->province_code }}
                                    </span>
                                @endif
                            </span>
                        </label>
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
