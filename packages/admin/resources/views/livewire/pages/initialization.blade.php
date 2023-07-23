<div class="min-h-screen bg-secondary-100 dark:bg-secondary-900">
    <nav id="navbar" x-data="{ open: false }" class="bg-white shadow-sm dark:bg-secondary-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <x-shopper::brand class="w-auto h-9" />
                        <div class="ml-3 truncate">
                            <h4 class="text-lg leading-4 font-semibold text-secondary-900 dark:text-white truncate font-display">
                                {{ config('app.name') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Profile dropdown -->
                    <div @click.away="open = false" class="ml-3 relative">
                        <div>
                            <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-secondary-300 dark:focus:border-secondary-700" id="user-menu" aria-label="User menu" aria-haspopup="true" x-bind:aria-expanded="open">
                                <img class="h-8 w-8 rounded-full" src="{{ $this->getUser()->picture }}" alt="Avatar">
                            </button>
                        </div>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-right absolute z-50 right-0 mt-2 w-48 rounded-md shadow-lg" style="display: none;">
                            <div class="py-1 rounded-md bg-white shadow-xs dark:bg-secondary-800">
                                <button
                                    type="button"
                                    class="w-full inline-flex items-center px-4 py-2 text-sm leading-5 text-secondary-700 hover:bg-secondary-100 focus:outline-none focus:bg-secondary-100 dark:text-secondary-300 dark:hover:bg-secondary-700 dark:focus:bg-secondary-900"
                                    onclick="document.getElementById('logout-form').submit();"
                                >
                                    <x-untitledui-log-out class="w-5 h-5 mr-2" />
                                    {{ __('shopper::layout.account_dropdown.sign_out') }}
                                </button>
                                <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-secondary-400 hover:text-secondary-500 hover:bg-secondary-100 focus:outline-none focus:bg-secondary-100 focus:text-secondary-500 transition duration-150 ease-in-out" x-bind:aria-label="open ? 'Close main menu' : 'Main menu'" x-bind:aria-expanded="open" aria-label="Main menu">
                        <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': open, 'block': !open }" class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': !open, 'block': open }" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden">
            <div class="pt-4 pb-3 border-t border-secondary-200">
                <div class="flex items-center px-4">
                    <div class="shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ $this->getUser()->picture }}" alt="{{ $this->getUser()->email }}">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-6 text-secondary-800">{{ $this->getUser()->full_name }}</div>
                        <div class="text-sm font-medium leading-5 text-secondary-500">{{ $this->getUser()->email }}</div>
                    </div>
                </div>
                <div class="mt-3" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    <button
                        type="button"
                        class="mt-1 inline-flex items-center w-full px-4 py-2 text-base font-medium text-secondary-500 hover:text-secondary-800 hover:bg-secondary-100 focus:outline-none focus:text-secondary-800 focus:bg-secondary-100 transition duration-150 ease-in-out"
                        role="menuitem"
                        onclick="document.getElementById('sm-logout-form').submit();"
                    >
                        <x-untitledui-log-out class="w-5 h-5 mr-2" />
                        {{ __('shopper::layout.account_dropdown.sign_out') }}
                    </button>
                    <form id="sm-logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

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
                                            'border-primary-600' => $shop_email || $shop_name,
                                            'border-secondary-300 dark:border-secondary-700' => !($shop_email || $shop_name),
                                        ])>
                                            <p @class([
                                                'text-primary-600 dark:text-primary-500' => $shop_email || $shop_name,
                                                'text-secondary-500 dark:text-secondary-400' => !($shop_email || $shop_name),
                                            ])>01</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-0.5 min-w-0">
                                    <h3 @class([
                                        'text-xs leading-4 font-semibold uppercase tracking-wide',
                                        'text-primary-600' => ($shop_email && empty($shop_name)) || ($shop_name && empty($shop_email)),
                                        'text-secondary-900 dark:text-white' => $this->stepOneState(),
                                        'text-secondary-500 dark:text-secondary-400' => ! $this->stepOneState(),
                                    ])>
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
                                        <div @class([
                                            'w-10 h-10 flex items-center justify-center border-2 rounded-full',
                                            'border-primary-600' => $shop_street_address || $shop_city,
                                            'border-secondary-300 dark:border-secondary-700' => !($shop_street_address || $shop_city),
                                        ])>
                                            <p @class([
                                                'text-primary-600' => $shop_street_address || $shop_city,
                                                'text-secondary-500 dark:text-secondary-400' => !($shop_street_address || $shop_city),
                                            ])>02</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-0.5 min-w-0">
                                    <h3 @class([
                                        'text-xs leading-4 font-semibold uppercase tracking-wide',
                                        'text-primary-600 dark:text-primary-500' => ($shop_street_address && empty($shop_city)) || ($shop_city && empty($shop_street_address)),
                                        'text-secondary-900 dark:text-white' => $this->stepTwoState(),
                                        'text-secondary-500 dark:text-secondary-400' => ! $this->stepTwoState(),
                                    ])>
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
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_1') }}
                    </span>
                    <h3 class="text-base mt-1.5 font-semibold text-secondary-900 leading-5 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.tell_about') }}
                    </h3>
                    <p class="mt-3 text-sm leading-5 text-secondary-500 lg:max-w-xl dark:text-secondary-400">
                        {{ __('shopper::pages/settings.initialization.step_1_description') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 lg:mt-8 sm:px-6 lg:px-8">
                <div class="p-4 space-y-6 bg-white shadow-md sm:rounded-lg lg:p-6 dark:bg-secondary-800">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                        <x-shopper::label for="name" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.store_name') }}
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-untitledui-shop class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                </div>
                                <x-shopper::forms.input id="name" type="text" wire:model.defer="shop_name" class="pl-10" autocomplete="name" autofocus required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="email" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.email') }}
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-untitledui-mail class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                </div>
                                <x-shopper::forms.input wire:model.defer="shop_email" id="email" type="email" class="pl-10" required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="country" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.country') }}
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

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="about" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.about')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="flex overflow-x-auto rounded-md shadow-sm sm:max-w-lg lg:w-full lg:overflow-visible">
                                <livewire:shopper-forms.trix :value="$shop_about" />
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/settings.about_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="currency" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.currency')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg" wire:ignore>
                                <select
                                    wire:model.defer="shop_currency_id"
                                    id="currency"
                                    x-data
                                    x-init="function() { tomSelect($el, {}) }"
                                    autocomplete="off"
                                >
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/settings.currency_description') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step-two" class="px-4 sm:px-6 lg:px-8">
                <div class="pt-8 mt-8 border-t lg:mt-10 lg:pt-10 border-secondary-200 dark:border-secondary-700">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_2') }}
                    </span>
                    <h3 class="text-base mt-1.5 font-bold text-secondary-900 leading-5 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.address_description') }}
                    </h3>
                    <p class="mt-4 text-sm text-secondary-500 lg:max-w-xl dark:text-secondary-400">
                        {{ __('shopper::pages/settings.initialization.step_2_description') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 lg:mt-8 sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow-md sm:rounded-lg dark:bg-secondary-800">
                    <div
                        @if(config('shopper.core.mapbox_token'))
                            x-data="mapBox($refs.mapbox, '{{ config('shopper.core.mapbox_token') }}')"
                        @else
                            x-data="{ lat: '', lng: '' }"
                        @endif
                        class="grid gap-4 lg:grid-cols-3 lg:gap-6">
                        <div wire:ignore class="space-y-4 sm:col-span-2">
                            @if(config('shopper.core.mapbox_token'))
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
                                <x-shopper::forms.group class="sm:col-span-4" :label="__('shopper::layout.forms.label.street_address')" for="street_address" isRequired>
                                    <x-shopper::forms.input wire:model.defer="shop_street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" :label="__('shopper::layout.forms.label.postal_code')" for="zipcode" isRequired>
                                    <x-shopper::forms.input wire:model.defer="shop_zipcode" id="zipcode" type="text" autocomplete="off" placeholder="00237" required />
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" :label="__('shopper::layout.forms.label.city')" for="city" isRequired>
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

                                <x-shopper::forms.group class="sm:col-span-2" for="longitude" :label="__('shopper::layout.forms.label.longitude')" wire:ignore>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <x-untitledui-mark class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                    </div>
                                    <div x-data>
                                        <x-shopper::forms.input
                                            x-model="lng"
                                            x-init="$watch('lng', value => $wire.set('shop_lng', value))"
                                            id="longitude"
                                            type="text"
                                            class="pl-10"
                                            autocomplete="off"
                                            placeholder="9.795537"
                                            readonly
                                        />
                                    </div>
                                </x-shopper::forms.group>

                                <x-shopper::forms.group class="sm:col-span-2" for="latitude" :label="__('shopper::layout.forms.label.latitude')" wire:ignore>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <x-untitledui-mark class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                    </div>
                                    <div x-data>
                                        <x-shopper::forms.input
                                            x-model="lat"
                                            x-init="$watch('lat', value => $wire.set('shop_lat', value))"
                                            id="latitude"
                                            type="text"
                                            class="pl-10"
                                            autocomplete="off"
                                            placeholder="4.02537"
                                            readonly
                                        />
                                    </div>
                                </x-shopper::forms.group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-6 lg:px-8">
                <div class="pt-8 mt-8 border-t lg:mt-10 lg:pt-10 border-secondary-200 dark:border-secondary-700">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_3') }}
                    </span>
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

                <div class="p-4 mt-6 bg-white rounded-lg shadow-md lg:p-6 dark:bg-secondary-800">
                    <div class="grid grid-cols-6 gap-6">
                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.facebook')" for="facebook">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-shopper::icons.facebook class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                            </div>
                            <x-shopper::forms.input wire:model.defer="shop_facebook_link" id="facebook" type="text" class="pl-10" autocomplete="off" placeholder="https://facebook.com/laravelshopper" />
                        </x-shopper::forms.group>
                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.instagram')" for="instagram">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-shopper::icons.instagram class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                            </div>
                            <x-shopper::forms.input wire:model.defer="shop_instagram_link" id="instagram" type="text" class="pl-10" autocomplete="off" placeholder="https://instagram.com/laravelshopper" />
                        </x-shopper::forms.group>
                        <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.twitter')" for="twitter">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-shopper::icons.twitter class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
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
