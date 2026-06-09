@php
    $zone = $this->zone;
@endphp

<div class="space-y-10">
    @if ($zone)
        <x-shopper::card>
            <x-slot name="title">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <x-untitledui-globe-05 class="size-6 text-sh-fg-muted" aria-hidden="true" />
                        <x-filament::section.heading class="font-heading font-semibold text-sh-fg">
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
                >
                    <div class="flex flex-wrap gap-x-3 gap-y-1.5">
                        @foreach ($zone->paymentMethods as $paymentMethod)
                            <span class="inline-flex ring-1 px-2 py-1 bg-sh-muted shadow-xs ring-sh-border rounded-md items-center gap-1.5">
                                @if ($paymentMethod->logo())
                                    <img
                                        src="{{ $paymentMethod->logo() }}"
                                        alt="{{ $paymentMethod->title }}"
                                        class="size-4 shrink-0 object-contain"
                                    />
                                @else
                                    <x-untitledui-credit-card-02 class="size-4 shrink-0 text-sh-fg-muted" aria-hidden="true" />
                                @endif
                                {{ $paymentMethod->title }}
                            </span>
                        @endforeach
                    </div>
                </x-shopper::description-list.item>

                <x-shopper::description-list.item
                    icon="phosphor-truck-trailer"
                    :heading="__('shopper::pages/settings/carriers.title')"
                >
                    <div class="flex flex-wrap gap-x-3 gap-y-1.5">
                        @foreach ($zone->carriers as $carrier)
                            <span class="inline-flex ring-1 px-2 py-1 bg-sh-muted shadow-xs ring-sh-border rounded-md items-center gap-1.5">
                                @if ($carrier->logo())
                                    <img
                                        src="{{ $carrier->logo() }}"
                                        alt="{{ $carrier->name }}"
                                        class="size-4 shrink-0 object-contain"
                                    />
                                @else
                                    <x-untitledui-truck class="size-4 shrink-0 text-sh-fg-muted" aria-hidden="true" />
                                @endif
                                {{ $carrier->name }}
                            </span>
                        @endforeach
                    </div>
                </x-shopper::description-list.item>
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
