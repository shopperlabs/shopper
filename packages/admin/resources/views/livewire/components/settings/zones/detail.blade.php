@php
    $zone = $this->zone;
@endphp

<div class="space-y-10">
    @if ($zone)
        <x-shopper::card>
            <x-slot name="title">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <x-untitledui-globe-05 class="size-6 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                        <x-filament::section.heading class="font-semibold font-heading text-gray-950 dark:text-white">
                            {{ $zone->name }}
                            @if ($zone->code)
                                <span>({{ $zone->code }})</span>
                            @endif
                        </x-filament::section.heading>
                    </div>
                    <div class="flex items-center gap-3">
                        {{ ($this->editAction)(['id' => $zone->id]) }}
                        {{ ($this->deleteAction)(['id' => $zone->id]) }}
                    </div>
                </div>
            </x-slot>

            <div class="grid gap-6 sm:grid-cols-2">
                <x-shopper::description-list.item
                    icon="phosphor-money"
                    :heading="__('shopper::forms.label.currency')"
                    :content="$zone->currency->name .' ('.$zone->currency->code.')'"
                />
                <x-shopper::description-list.item
                    icon="phosphor-globe-hemisphere-west"
                    :heading="__('shopper::forms.label.countries')"
                    :content="$zone->countries_name"
                />
                <x-shopper::description-list.item
                    icon="phosphor-credit-card"
                    :heading="__('shopper::pages/settings/payments.title')"
                    :content="$zone->payments_name"
                />
                <x-shopper::description-list.item
                    icon="phosphor-truck-trailer"
                    :heading="__('shopper::pages/settings/carriers.title')"
                    :content="$zone->carriers_name"
                />
                <div class="lg:col-span-2" wire:ignore>
                    <x-shopper::description-list.item icon="phosphor-brackets-curly" :heading="__('Metadata')">
                        <x-shopper::code-preview
                            :code="$zone->metadata"
                            lang="json"
                            :themes="[
                                    'light' => 'github-light',
                                    'dark' => 'github-dark-default',
                                ]"
                        />
                    </x-shopper::description-list.item>
                </div>
            </div>
        </x-shopper::card>
    @else
        <x-shopper::card>
            <x-shopper::empty-card
                icon="untitledui-globe-05"
                :heading="__('shopper::pages/settings/zones.empty_detail_heading')"
                :description="__('shopper::pages/settings/zones.empty_detail_description')"
            />
        </x-shopper::card>
    @endif

    <x-filament-actions::modals />
</div>
