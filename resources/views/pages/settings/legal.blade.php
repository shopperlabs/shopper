@extends('shopper::layouts.default')
@section('title', __('Legal'))

@section('content')

    <div
        x-data="{
            options: ['role', 'users', 'permissions'],
            words: {
                'refund': '{{ __("Refund policy") }}',
                'privacy': '{{ __("Privacy policy") }}',
                'terms': '{{ __("Terms of use") }}',
                'shipping': '{{ __("Shipping policy") }}'
            },
            currentTab: 'refund'
        }"
    >
        <x-shopper-breadcrumb back="shopper.settings.index">
            <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
            <x-shopper-breadcrumb-link :link="route('shopper.settings.index')" title="Settings" />
        </x-shopper-breadcrumb>

        <div class="mt-3 pb-5 relative border-b border-gray-200 space-y-4 sm:pb-0 dark:border-gray-700">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                    {{ __('Legal') }}
                </h3>
            </div>
            <div>
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <x-shopper-input.select x-model="currentTab" aria-label="{{ __('Selected tab') }}">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper-input.select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'refund'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" aria-current="page" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'refund' }">
                            {{ __("Refund policy") }}
                        </button>

                        <button @click="currentTab = 'privacy'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'privacy' }">
                            {{ __("Privacy policy") }}
                        </button>

                        <button @click="currentTab = 'terms'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'terms' }">
                            {{ __("Terms of use") }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'shipping' }">
                            {{ __("Shipping policy") }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div x-show="currentTab === 'refund'">
                <livewire:shopper-settings.legal.refund />
            </div>
            <div x-cloak x-show="currentTab === 'privacy'">
                <livewire:shopper-settings.legal.privacy />
            </div>
            <div x-cloak x-show="currentTab === 'terms'">
                <livewire:shopper-settings.legal.terms />
            </div>
            <div x-cloak x-show="currentTab === 'shipping'">
                <livewire:shopper-settings.legal.shipping />
            </div>
        </div>

        <x-shopper-learn-more name="legal pages" link="https://docs.laravelshopper.io/docs/legal" />
    </div>

@endsection
