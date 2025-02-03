@php
    $zone = $this->zone;
@endphp

<div class="space-y-10">
    @if ($zone)
        <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10">
            <div class="flex items-center justify-between p-4 lg:p-5">
                <div class="flex items-center space-x-3">
                    <x-untitledui-globe-05 class="size-6 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                    <x-filament::section.heading>
                        {{ $zone->name }}
                        @if ($zone->code)
                            <span>({{ $zone->code }})</span>
                        @endif
                    </x-filament::section.heading>
                </div>
                <div class="flex items-center space-x-3 pl-4">
                    {{ ($this->editAction)(['id' => $zone->id]) }}
                    {{ ($this->deleteAction)(['id' => $zone->id]) }}
                </div>
            </div>
            <div class="p-4 lg:p-5">
                <div class="grid gap-6 sm:grid-cols-2">
                    <x-shopper::description-list.item
                        icon="untitledui-bank-note"
                        :heading="__('shopper::forms.label.currency')"
                        :content="$zone->currency->name .' ('.$zone->currency->code.')'"
                    />
                    <x-shopper::description-list.item
                        icon="untitledui-globe-02"
                        :heading="__('shopper::forms.label.countries')"
                        :content="$zone->countries_name"
                    />
                    <x-shopper::description-list.item
                        icon="untitledui-credit-card-02"
                        :heading="__('shopper::pages/settings/payments.title')"
                        :content="$zone->payments_name"
                    />
                    <x-shopper::description-list.item
                        icon="untitledui-truck-02"
                        :heading="__('shopper::pages/settings/carriers.title')"
                        :content="$zone->carriers_name"
                    />
                    <div class="lg:col-span-2" wire:ignore>
                        <x-shopper::description-list.item icon="untitledui-brackets" heading="Metadata">
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

    <div x-data>
        <template x-teleport="body">
            <x-filament-actions::modals />
        </template>
    </div>
</div>
