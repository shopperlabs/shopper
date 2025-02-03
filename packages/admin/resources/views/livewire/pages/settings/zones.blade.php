<x-shopper::container>
    <x-shopper::breadcrumb
        :back="route('shopper.settings.index')"
        :current="__('shopper::pages/settings/zones.title')"
    >
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.index')"
            :title="__('shopper::pages/settings/global.menu')"
        />
    </x-shopper::breadcrumb>

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <aside class="lg:sticky lg:top-4">
            <x-shopper::card-with-gray-heading class="max-w-lg space-y-6">
                <x-slot:heading>
                    <div class="space-y-1">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/settings/zones.title') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('shopper::pages/settings/zones.description') }}
                        </p>
                    </div>
                    <div class="flex h-7 items-center">
                        <button
                            type="button"
                            wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.zone-form' })"
                            title="{{ __('shopper::pages/settings/zones.add_action') }}"
                            class="relative text-gray-400 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:text-gray-400"
                        >
                            <span class="absolute -inset-2.5"></span>
                            <span class="sr-only">Open Panel</span>
                            <x-untitledui-plus class="size-6" aria-hidden="true" />
                        </button>
                    </div>
                </x-slot>
                <div class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse ($zones as $zone)
                        <label wire:key="{{ $zone->slug }}" for="{{ $zone->slug }}" class="relative flex cursor-pointer bg-gray-50 p-4 focus:outline-none dark:bg-gray-950">
                            <x-filament::input.radio
                                name="zone"
                                value="{{ $zone->id }}"
                                id="{{ $zone->slug }}"
                                wire:model.live="currentZoneId"
                                class="mt-0.5"
                                aria-labelledby="zone-{{ $zone->id }}-label"
                                aria-describedby="zone-{{ $zone->id }}-description"
                            />
                            <span class="ml-3 flex flex-col space-y-1">
                                <span id="zone-{{ $zone->id }}-label" class="flex items-center space-x-2">
                                    <span
                                        @class([
                                            'block text-sm font-medium',
                                            'text-primary-600 dark:text-primary-700' => $currentZoneId === $zone->id,
                                            'text-gray-900 dark:text-white' => $currentZoneId !== $zone->id,
                                        ])
                                    >
                                        {{ $zone->name }}
                                        @if ($zone->code)
                                            ({{ $zone->code }})
                                        @endif
                                    </span>
                                    <x-filament::badge size="sm" :color="$zone->isEnabled() ? 'success': 'warning'">
                                        {{ $zone->isEnabled() ? __('shopper::words.is_enabled') : __('shopper::words.is_disabled') }}
                                    </x-filament::badge>
                                </span>
                                <span
                                    id="zone-{{ $zone->id }}-description"
                                    class="block text-sm text-gray-500 dark:text-gray-400"
                                >
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ __('shopper::pages/settings/carriers.title') }}:
                                    </span>
                                    ({{ $zone->carriers_name }}) -
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ __('shopper::forms.label.countries') }}:
                                    </span>
                                    ({{ $zone->countries_name }})
                                </span>
                            </span>
                        </label>
                    @empty
                        <x-shopper::empty-card
                            :heading="__('shopper::pages/settings/zones.empty_heading')"
                            icon="untitledui-globe-05"
                        />
                    @endforelse
                </div>
            </x-shopper::card-with-gray-heading>
        </aside>
        <div class="mt-6 space-y-4 lg:col-span-2 lg:mt-0">
            @if ($currentZoneId)
                <livewire:shopper-settings.zones.detail :$currentZoneId :key="$currentZoneId" />

                <livewire:shopper-settings.zones.shipping-options :selectedZoneId="$currentZoneId" :key="'options-' . $currentZoneId" />
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
