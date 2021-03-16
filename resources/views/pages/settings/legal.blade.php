@extends('shopper::layouts.default')
@section('title', __('Legal'))

@section('content')

    <div
        x-data="{
        deleteModal: false,
        options: ['role', 'users', 'permissions'],
        words: {'refund': '{{ __("Refund policy") }}', 'privacy': '{{ __("Privacy policy") }}', 'terms': '{{ __("Terms of use") }}', 'shipping': '{{ __("Shipping policy") }}'},
            currentTab: 'refund'
        }"
    >
        <x-shopper-breadcrumb back="shopper.settings.index">
            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Setting') }}</a>
        </x-shopper-breadcrumb>

        <div class="mt-3 relative pb-5 border-b border-gray-200 space-y-4 sm:pb-0">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                    {{ __('Legal') }}
                </h3>
            </div>
            <div>
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <select x-model="currentTab" aria-label="Selected tab" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'refund'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'refund' }">
                            {{ __("Refund policy") }}
                        </button>

                        <button @click="currentTab = 'privacy'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'privacy' }">
                            {{ __("Privacy policy") }}
                        </button>

                        <button @click="currentTab = 'terms'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'terms' }">
                            {{ __("Terms of use") }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'shipping' }">
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

        <x-shopper-learn-more name="legal pages" link="#" />
    </div>

@endsection
