<x-shopper::container>
    <div class="space-y-2">
        <x-shopper::heading :title="__('shopper::pages/settings/zones.title')">
            <x-slot name="action">
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.zone-form' })"
                    icon="untitledui-plus"
                >
                    {{ __('shopper::pages/settings/zones.add_action') }}
                </x-filament::button>
            </x-slot>
        </x-shopper::heading>
        <p class="text-sm text-sh-fg-muted">
            {{ __('shopper::pages/settings/zones.description') }}
        </p>
    </div>

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:gap-y-6">
        <aside class="lg:sticky lg:top-4 lg:self-start">
            <x-shopper::card class="[&_.sh-card-content]:p-0 [&>div:first-of-type]:p-0">
                <x-slot name="title">
                    <x-filament::input.wrapper prefix-icon="untitledui-search-md" inline-prefix>
                        <x-filament::input
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            :placeholder="__('shopper::pages/settings/zones.search_placeholder')"
                        />
                    </x-filament::input.wrapper>
                </x-slot>

                <div class="divide-y divide-sh-border">
                    @forelse ($this->zones as $zone)
                        @php
                            $isSelected = (int) $currentZoneId === (int) $zone->id;
                        @endphp
                        <button
                            type="button"
                            wire:key="zone-{{ $zone->id }}"
                            wire:click="$set('currentZoneId', {{ $zone->id }})"
                            @class([
                                'group flex w-full items-start gap-3 border-l-2 p-4 text-left transition',
                                'border-l-gray-900 bg-sh-muted dark:border-l-white' => $isSelected,
                                'border-l-transparent hover:bg-sh-muted' => ! $isSelected,
                            ])
                        >
                            <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-sh-muted ring-1 ring-sh-border text-xs font-semibold text-sh-fg-secondary">
                                {{ $zone->code }}
                            </div>

                            <div class="min-w-0 flex-1 space-y-1.5">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-sm font-semibold text-sh-fg">
                                        {{ $zone->name }}
                                    </span>
                                    <x-filament::badge size="sm" :color="$zone->isEnabled() ? 'success' : 'gray'">
                                        {{ $zone->isEnabled() ? __('shopper::words.is_enabled') : __('shopper::words.is_disabled') }}
                                    </x-filament::badge>
                                </div>

                                <p class="text-xs text-sh-fg-muted">
                                    {{ trans_choice('shopper::pages/settings/zones.countries_count', $zone->countries->count(), ['count' => $zone->countries->count()]) }}
                                    @if ($zone->currency)
                                        · {{ $zone->currency->code }}
                                    @endif
                                </p>

                                @if ($zone->countries->isNotEmpty())
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @foreach ($zone->countries->take(8) as $country)
                                            <img
                                                src="{{ $country->svg_flag }}"
                                                alt="{{ $country->translated_name }}"
                                                class="size-4 rounded-xs object-cover"
                                            />
                                        @endforeach
                                        @if ($zone->countries->count() > 8)
                                            <span class="ml-0.5 text-xs text-sh-fg-muted">
                                                +{{ $zone->countries->count() - 8 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <x-shopper::empty-card
                            :heading="__('shopper::pages/settings/zones.empty_heading')"
                            icon="untitledui-globe-05"
                        />
                    @endforelse
                </div>
            </x-shopper::card>
        </aside>

        <div class="mt-6 space-y-4 lg:col-span-2 lg:mt-0">
            @if ($currentZoneId)
                <livewire:shopper-settings.zones.detail :$currentZoneId :key="$currentZoneId" />

                <livewire:shopper-settings.zones.shipping-options
                    :selectedZoneId="$currentZoneId"
                    :key="'options-' . $currentZoneId"
                />
            @else
                <x-shopper::card>
                    <x-shopper::empty-card
                        icon="untitledui-globe-05"
                        :heading="__('shopper::pages/settings/zones.empty_detail_heading')"
                        :description="__('shopper::pages/settings/zones.empty_detail_description')"
                    />
                </x-shopper::card>
            @endif
        </div>
    </div>
</x-shopper::container>
