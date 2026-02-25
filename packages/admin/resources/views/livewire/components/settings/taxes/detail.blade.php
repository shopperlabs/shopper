@php
    $taxZone = $this->taxZone;
@endphp

<div class="space-y-10">
    @if ($taxZone)
        <x-shopper::card>
            <x-slot name="title">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ $taxZone->country->svg_flag }}"
                            alt="{{ $taxZone->country->name }}"
                            class="size-6 shrink-0 rounded-full object-cover"
                        />
                        <x-filament::section.heading class="font-heading font-semibold text-gray-950 dark:text-white">
                            {{ $taxZone->display_name }}
                        </x-filament::section.heading>
                    </div>
                    <div class="flex items-center gap-3">
                        {{ ($this->editAction)(['id' => $taxZone->id]) }}
                        {{ ($this->deleteAction)(['id' => $taxZone->id]) }}
                    </div>
                </div>
            </x-slot>

            <div class="grid gap-6 sm:grid-cols-2">
                <x-shopper::description-list.item
                    icon="phosphor-globe-hemisphere-west"
                    :heading="__('shopper::forms.label.country')"
                    :content="$taxZone->country->name"
                />
                @if ($taxZone->province_code)
                    <x-shopper::description-list.item
                        icon="phosphor-map-pin"
                        :heading="__('shopper::pages/settings/taxes.province_code')"
                        :content="$taxZone->province_code"
                    />
                @endif
                <x-shopper::description-list.item
                    icon="phosphor-receipt"
                    :heading="__('shopper::pages/settings/taxes.tax_behavior')"
                >
                    <x-filament::badge :color="$taxZone->is_tax_inclusive ? 'success' : 'warning'">
                        {{ $taxZone->is_tax_inclusive
                            ? __('shopper::pages/settings/taxes.inclusive')
                            : __('shopper::pages/settings/taxes.exclusive')
                        }}
                    </x-filament::badge>
                </x-shopper::description-list.item>
                <x-shopper::description-list.item
                    icon="phosphor-plug"
                    :heading="__('shopper::pages/settings/taxes.provider')"
                    :content="$taxZone->provider?->identifier ?? __('shopper::pages/settings/taxes.system_default')"
                />
                @if ($taxZone->metadata)
                    <div class="lg:col-span-2" wire:ignore>
                        <x-shopper::description-list.item icon="phosphor-brackets-curly" :heading="__('Metadata')">
                            <x-shopper::code-preview
                                :code="$taxZone->metadata"
                                lang="json"
                                :themes="[
                                    'light' => 'github-light',
                                    'dark' => 'github-dark-default',
                                ]"
                            />
                        </x-shopper::description-list.item>
                    </div>
                @endif
            </div>
        </x-shopper::card>
    @else
        <x-shopper::card>
            <x-shopper::empty-card
                icon="untitledui-coins-02"
                :heading="__('shopper::pages/settings/taxes.empty_detail_heading')"
                :description="__('shopper::pages/settings/taxes.empty_detail_description')"
            />
        </x-shopper::card>
    @endif

    <x-filament-actions::modals />
</div>
