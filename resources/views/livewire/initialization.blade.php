<div>
    <header class="sticky top-0 z-30 hidden bg-white shadow-md lg:block lg:border-t lg:border-b lg:border-secondary-200 dark:bg-secondary-800 lg:dark:border-secondary-700">
        <nav class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <ul class="flex overflow-hidden">
                <li class="relative overflow-hidden lg:flex-1">
                    <div class="overflow-hidden border border-b-0 border-secondary-200 rounded-t-md lg:border-0">
                        <button x-data @click="scrollToPosition('#step-one')" type="button" class="text-left group">
                            @if(($shop_email && empty($shop_name)) || ($shop_name && empty($shop_email)))
                                <div class="absolute top-0 left-0 w-1 h-full bg-primary-600 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto"></div>
                            @endif
                            <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-secondary-200 group-focus:bg-secondary-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto dark:group-hover:bg-secondary-700 dark:group-focus:bg-secondary-700"></div>
                            <div class="flex items-start py-5 pr-6 space-x-4 text-sm font-medium leading-5">
                                <div class="shrink-0">
                                    @if($this->stepOneState())
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600">
                                            <svg class="w-6 h-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div @class([
                                            'w-10 h-10 flex items-center justify-center border-2 rounded-full',
                                            'border-primary-600 dark:hover:border-primary-500' => ($shop_email || $shop_name),
                                            'border-secondary-300 dark:border-secondary-700' => !($shop_email || $shop_name),
                                        ])>
                                            <p @class([
                                                'text-primary-600 dark:text-primary-500' => ($shop_email || $shop_name),
                                                'text-secondary-500 dark:text-secondary-400' => !($shop_email || $shop_name),
                                            ])>01</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-0.5 min-w-0">
                                    <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide @if(($shop_email && empty($shop_name)) || ($shop_name && empty($shop_email))) text-primary-600 dark:text-primary-500 @elseif($this->stepOneState()) text-secondary-900 dark:text-white @else text-secondary-500 dark:text-secondary-400 @endif">
                                        {{ __('shopper::pages/settings.initialization.step_one_title') }}
                                    </h3>
                                    <p class="text-sm font-medium leading-5 text-secondary-400 dark:text-secondary-500">
                                        {{ __('shopper::pages/settings.initialization.step_one_description') }}
                                    </p>
                                </div>
                            </div>
                        </button>
                    </div>
                </li>

                <li class="relative overflow-hidden lg:flex-1">
                    <div class="overflow-hidden border border-secondary-200 lg:border-0">
                        <button x-data @click="scrollToPosition('#step-two')" type="button" class="text-left group">
                            @if(($shop_street_address && empty($shop_city)) || ($shop_city && empty($shop_street_address)))
                                <div class="absolute top-0 left-0 w-1 h-full bg-primary-600 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto"></div>
                            @endif
                            <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-secondary-200 group-focus:bg-secondary-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto dark:group-hover:bg-secondary-700 dark:group-focus:bg-secondary-700"></div>
                            <div class="flex items-start px-6 py-5 space-x-4 text-sm font-medium leading-5 lg:pl-9">
                                <div class="shrink-0">
                                    @if($this->stepTwoState())
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600">
                                            <svg class="w-6 h-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 flex items-center justify-center border-2 @if($shop_street_address || $shop_city) border-primary-600 dark:hover:border-primary-500 @else border-secondary-300 dark:border-secondary-700 @endif rounded-full">
                                            <p class="@if($shop_street_address || $shop_city) text-primary-600 dark:text-primary-500 @else text-secondary-500 dark:text-secondary-400 @endif">02</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-0.5 min-w-0">
                                    <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide @if(($shop_street_address && empty($shop_city)) || ($shop_city && empty($shop_street_address))) text-primary-600 dark:text-primary-500 @elseif($this->stepTwoState()) text-secondary-900 dark:text-white @else text-secondary-500 dark:text-secondary-400 @endif">
                                        {{ __('shopper::pages/settings.initialization.step_two_title') }}
                                    </h3>
                                    <p class="text-sm font-medium leading-5 text-secondary-400 dark:text-secondary-500">
                                        {{ __('shopper::pages/settings.initialization.step_two_description') }}
                                    </p>
                                </div>
                            </div>

                            <div class="absolute inset-0 top-0 left-0 hidden w-3 lg:block">
                                <svg class="w-full h-full text-secondary-300 dark:text-secondary-700" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
                                    <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentColor" vector-effect="non-scaling-stroke" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </li>

                <li class="relative overflow-hidden lg:flex-1">
                    <div class="overflow-hidden border border-t-0 border-secondary-200 rounded-b-md lg:border-0">
                        <button x-data @click="scrollToPosition('#step-tree')" type="button" class="text-left group">
                            <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-secondary-200 group-focus:bg-secondary-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto dark:group-hover:bg-secondary-700 dark:group-focus:bg-secondary-700"></div>
                            <div class="flex items-start py-5 space-x-4 text-sm font-medium leading-5 pl-9">
                                <div class="shrink-0">
                                    @if($this->stepTreeState())
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600">
                                            <svg class="w-6 h-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full border-secondary-300 dark:border-secondary-700">
                                            <p class="text-secondary-500 dark:text-secondary-400">03</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-0.5 min-w-0">
                                    <h3 class="text-xs font-semibold leading-4 tracking-wide uppercase text-secondary-900 dark:text-white">
                                        {{ __('shopper::pages/settings.initialization.step_tree_title') }}
                                    </h3>
                                    <p class="text-sm font-medium leading-5 text-secondary-400 dark:text-secondary-500">
                                        {{ __('shopper::pages/settings.initialization.step_tree_description') }}
                                    </p>
                                </div>
                            </div>
                        </button>

                        <div class="absolute inset-0 top-0 left-0 hidden w-3 lg:block">
                            <svg class="w-full h-full text-secondary-300 dark:text-secondary-700" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
                                <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentColor" vector-effect="non-scaling-stroke" />
                            </svg>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="py-5 mx-auto max-w-7xl sm:py-10">
        <form wire:submit.prevent="store">
            <div id="step-one" class="px-4 sm:px-6 lg:px-8">
                <span class="text-sm font-medium uppercase text-primary-600 lg:hidden dark:text-primary-500">
                    {{ __('shopper::pages/settings.initialization.step', ['step' => 1]) }}
                </span>
                <h1 class="mt-2 text-2xl font-bold leading-8 text-secondary-900 lg:text-3xl lg:mt-0 dark:text-white">
                    {{ __('shopper::pages/settings.initialization.shop_configuration') }}
                </h1>

                <x-shopper::validation-errors />

                <div class="mt-10">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">{{ __('shopper::pages/settings.initialization.step_1') }}</span>
                    <h3 class="text-base mt-1.5 font-semibold text-secondary-900 leading-5 dark:text-white">{{ __('shopper::pages/settings.initialization.tell_about') }}</h3>
                    <p class="mt-3 text-sm leading-5 text-secondary-500 lg:max-w-xl dark:text-secondary-400">
                        {{ __('shopper::pages/settings.initialization.step_1_description') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 lg:mt-8 sm:px-6 lg:px-8">
                <div class="p-4 space-y-6 bg-white shadow-md sm:rounded-md lg:p-6 dark:bg-secondary-800">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                        <x-shopper::label for="name" class="sm:mt-px sm:pt-2">
                            {{ __('shopper::layout.forms.label.store_name') }} <span class="text-red-500">*</span>
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-shopper::forms.input id="name" type="text" wire:model.defer="shop_name" class="pl-10" autocomplete="name" autofocus required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="email" class="sm:mt-px sm:pt-2">
                            {{ __('shopper::layout.forms.label.email') }} <span class="text-red-500">*</span>
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-secondary-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <x-shopper::forms.input wire:model.defer="shop_email" id="email" type="email" class="pl-10" required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="country" class="sm:mt-px sm:pt-2">
                            {{ __('shopper::layout.forms.label.country') }} <span class="text-red-500">*</span>
                        </x-shopper::label>
                        <div class="relative mt-1 sm:mt-0 sm:col-span-2">
                            <div class="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg" wire:ignore>
                                <select
                                    wire:model.defer="shop_country_id"
                                        id="shop_country_id"
                                        x-data="{}"
                                        x-init="function() { tomSelect($el, {}) }"
                                        autocomplete="off"
                                    >
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="photo" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.logo')" />
                        <div class="relative mt-1 sm:mt-0 sm:col-span-2">
                            <x-shopper::forms.avatar-upload wire:model.defer="logo" id="photo">
                                <span class="flex items-center justify-center w-12 h-12 overflow-hidden rounded-full bg-secondary-100 dark:bg-secondary-700">
                                    @if($logo)
                                        <img class="object-cover w-full h-full" src="{{ $logo->temporaryUrl() }}" alt="Store logo">
                                    @else
                                        <svg class="w-6 h-6 text-secondary-300 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </span>
                            </x-shopper::forms.avatar-upload>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/settings.logo_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="about" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.about')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="flex overflow-x-auto rounded-md shadow-sm sm:max-w-lg lg:w-full lg:overflow-visible">
                                <livewire:shopper-forms.trix :value="$shop_about" />
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">{{ __('shopper::pages/settings.about_description') }}</p>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="currency" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.currency')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg" wire:ignore>
                                <select
                                    wire:model.defer="shop_currency_id"
                                    id="currency"
                                    x-data="{}"
                                    x-init="function() { tomSelect($el, {}) }"
                                    autocomplete="off"
                                >
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">{{ __('shopper::pages/settings.currency_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step-two" class="px-4 sm:px-6 lg:px-8">
                <div class="pt-8 mt-8 border-t lg:mt-10 lg:pt-10 border-secondary-200 dark:border-secondary-700">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">{{ __('shopper::pages/settings.initialization.step_2') }}</span>
                    <h3 class="text-base mt-1.5 font-bold text-secondary-900 leading-5 dark:text-white">{{ __('shopper::pages/settings.initialization.address_description') }}</h3>
                    <p class="mt-4 text-sm text-secondary-500 lg:max-w-xl dark:text-secondary-400">
                        {{ __('shopper::pages/settings.initialization.step_2_description') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 lg:mt-8 sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow-md sm:rounded-md dark:bg-secondary-800">
                    <div
                        @if(env('MAPBOX_PUBLIC_TOKEN'))
                            x-data="mapBox($refs.mapbox, '{{ env('MAPBOX_PUBLIC_TOKEN') }}')"
                        @else
                            x-data="{ lat: '', lng: '' }"
                        @endif
                        class="grid gap-4 lg:grid-cols-3 lg:gap-6">
                        <div wire:ignore class="space-y-4 sm:col-span-2">
                            @if(env('MAPBOX_PUBLIC_TOKEN'))
                                <div x-ref="mapbox" class="overflow-hidden rounded-md outline-none bg-secondary-100 h-95 dark:bg-secondary-900"></div>
                            @else
                                <div class="flex items-center justify-center overflow-hidden rounded-md outline-none bg-secondary-100 h-95 dark:bg-secondary-900">
                                    <p class="text-base font-medium leading-6 text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/settings.mapbox_disabled') }}
                                    </p>
                                </div>
                            @endif
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                Shopper uses <span class="font-medium text-secondary-700 dark:text-secondary-300">Mapbox</span> to make it easier to locate your store.
                                To learn more about mapbox, consult the <a href="https://docs.mapbox.com/mapbox-gl-js/api" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:text-primary-500 dark:text-primary-500">documentation</a>.
                            </p>
                        </div>
                        <div class="py-2 pr-2">
                            <div class="grid gap-4 lg:grid-cols-4 lg:gap-5">
                                <x-shopper::forms.group class="sm:col-span-4" label="shopper::layout.forms.label.street_address" for="street_address" isRequired>
                                    <x-shopper::forms.input wire:model.defer="shop_street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" label="shopper::layout.forms.label.postal_code" for="zipcode" isRequired>
                                    <x-shopper::forms.input wire:model.defer="shop_zipcode" id="zipcode" type="text" autocomplete="off" placeholder="00237" required />
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" label="shopper::layout.forms.label.city" for="city" isRequired>
                                    <x-shopper::forms.input wire:model.defer="shop_city" id="city" type="text" autocomplete="off" required />
                                </x-shopper::forms.group>

                                <div wire:ignore x-data="internationalNumber('#phone_number')" class="sm:col-span-4">
                                    <x-shopper::label for="phone_number" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.phone_number')" />
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                            <x-shopper::forms.input wire:model.defer="shop_phone_number" id="phone_number" type="tel" class="w-full" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

                                <x-shopper::forms.group class="sm:col-span-2" for="longitude" label="shopper::layout.forms.label.longitude" wire:ignore>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 7.083A2.92 2.92 0 0 0 7.083 10 2.92 2.92 0 0 0 10 12.917 2.92 2.92 0 0 0 12.917 10 2.92 2.92 0 0 0 10 7.083zm0 4.167c-.69 0-1.25-.56-1.25-1.25S9.31 8.75 10 8.75s1.25.56 1.25 1.25-.56 1.25-1.25 1.25z" />
                                            <path d="M19.167 9.167H17.45a7.51 7.51 0 0 0-6.618-6.618V.833a.834.834 0 0 0-1.666 0V2.55a7.51 7.51 0 0 0-6.618 6.618H.833a.834.834 0 0 0 0 1.666H2.55a7.51 7.51 0 0 0 6.618 6.618v1.716a.834.834 0 0 0 1.666 0V17.45a7.51 7.51 0 0 0 6.618-6.618h1.716a.834.834 0 0 0 0-1.666zM10 15.833A5.84 5.84 0 0 1 4.167 10 5.84 5.84 0 0 1 10 4.167 5.84 5.84 0 0 1 15.833 10 5.84 5.84 0 0 1 10 15.833z" />
                                        </svg>
                                    </div>
                                    <x-shopper::forms.input x-model="lng" x-data x-init="$watch('lng', value => $wire.set('shop_lng', value))" id="longitude" type="text" class="pl-10" autocomplete="off" placeholder="9.795537" readonly />
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" for="latitude" label="shopper::layout.forms.label.latitude" wire:ignore>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 7.083A2.92 2.92 0 0 0 7.083 10 2.92 2.92 0 0 0 10 12.917 2.92 2.92 0 0 0 12.917 10 2.92 2.92 0 0 0 10 7.083zm0 4.167c-.69 0-1.25-.56-1.25-1.25S9.31 8.75 10 8.75s1.25.56 1.25 1.25-.56 1.25-1.25 1.25z" />
                                            <path d="M19.167 9.167H17.45a7.51 7.51 0 0 0-6.618-6.618V.833a.834.834 0 0 0-1.666 0V2.55a7.51 7.51 0 0 0-6.618 6.618H.833a.834.834 0 0 0 0 1.666H2.55a7.51 7.51 0 0 0 6.618 6.618v1.716a.834.834 0 0 0 1.666 0V17.45a7.51 7.51 0 0 0 6.618-6.618h1.716a.834.834 0 0 0 0-1.666zM10 15.833A5.84 5.84 0 0 1 4.167 10 5.84 5.84 0 0 1 10 4.167 5.84 5.84 0 0 1 15.833 10 5.84 5.84 0 0 1 10 15.833z" />
                                        </svg>
                                    </div>
                                    <x-shopper::forms.input x-model="lat" x-data x-init="$watch('lat', value => $wire.set('shop_lat', value))" id="latitude" type="text" class="pl-10" autocomplete="off" placeholder="4.02537" readonly />
                                </x-shopper::forms.group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-6 lg:px-8">
                <div class="pt-8 mt-8 border-t lg:mt-10 lg:pt-10 border-secondary-200 dark:border-secondary-700">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">{{ __('shopper::pages/settings.initialization.step_3') }}</span>
                    <h3 class="text-base mt-1.5 font-bold text-secondary-900 leading-5 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.social_description') }}
                        <span class="font-normal text-secondary-500 dark:text-secondary-400">
                            ({{ __('shopper::layout.forms.label.optional') }})
                        </span>
                    </h3>
                    <p class="mt-4 text-sm text-secondary-500 lg:max-w-xl dark:text-secondary-400">
                        {{ __('shopper::pages/settings.initialization.step_3_description') }}
                    </p>
                </div>

                <div class="p-4 mt-6 bg-white rounded-md shadow-md lg:p-6 dark:bg-secondary-800">
                    <div class="grid grid-cols-6 gap-6">
                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" label="Facebook" for="facebook">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <x-shopper::forms.input wire:model.defer="shop_facebook_link" id="facebook" type="text" class="pl-10" autocomplete="off" placeholder="https://facebook.com/laravelshopper" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" label="Instagram" for="instagram">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2 1.2 1.2 0 0 0-1.2-1.2zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43 4.94 4.94 0 0 0-1.16-1.77 4.7 4.7 0 0 0-1.77-1.15 7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47 4.78 4.78 0 0 0-1.77 1.15 4.7 4.7 0 0 0-1.15 1.77 7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43 4.7 4.7 0 0 0 1.15 1.77 4.78 4.78 0 0 0 1.77 1.15 7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47 4.7 4.7 0 0 0 1.77-1.15 4.85 4.85 0 0 0 1.16-1.77 7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12zM20.14 16a5.61 5.61 0 0 1-.34 1.86 3.06 3.06 0 0 1-.75 1.15 3.19 3.19 0 0 1-1.15.75 5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3 3.27 3.27 0 0 1-1.1-.75 3 3 0 0 1-.74-1.15 5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34 3.06 3.06 0 0 1 1.19.8 3.06 3.06 0 0 1 .75 1.1 5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4zM12 6.87A5.13 5.13 0 1 0 17.14 12 5.12 5.12 0 0 0 12 6.87zm0 8.46A3.33 3.33 0 1 1 15.33 12 3.33 3.33 0 0 1 12 15.33z" />
                                </svg>
                            </div>
                            <x-shopper::forms.input wire:model.defer="shop_instagram_link" id="instagram" type="text" class="pl-10" autocomplete="off" placeholder="https://instagram.com/laravelshopper" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" label="Twitter" for="twitter">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </div>
                            <x-shopper::forms.input wire:model.defer="shop_twitter_link" id="twitter" type="text" class="pl-10" autocomplete="off" placeholder="https://twitter.com/laravelshopper" />
                        </x-shopper::forms.group>
                    </div>
                </div>
            </div>

            <div id="step-tree" class="px-4 pt-5 mt-8 sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <span class="inline-flex ml-3 rounded-md shadow-sm">
                        <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::pages/settings.initialization.action') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </div>
        </form>
    </main>
</div>
