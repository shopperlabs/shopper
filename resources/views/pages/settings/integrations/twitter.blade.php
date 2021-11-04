@extends('shopper::layouts.default')
@section('title', __('Integrations ~ Twitter'))

@section('content')

    <div
        x-data="{
        deleteModal: false,
        options: ['products', 'setting'],
        words: {'products': '{{ __("Products") }}', 'setting': '{{ __("Settings") }}'},
        currentTab: 'products'
    }"
    >
        <x-shopper-breadcrumb back="shopper.settings.integrations">
            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('shopper.settings.integrations') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Integrations') }}</a>
        </x-shopper-breadcrumb>

        <div class="mt-3 relative pb-5 border-b border-gray-200 space-y-4 sm:pb-0">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                    Twitter
                </h3>
            </div>
            <div>
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <select x-model="currentTab" aria-label="Selected tab" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-primary focus:border-primary-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
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
                        <button @click="currentTab = 'products'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent group inline-flex items-center font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'products' }">
                            <svg class="-ml-0.5 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ __("Products") }}
                        </button>

                        <button @click="currentTab = 'setting'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent group inline-flex items-center font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'setting' }">
                            <svg class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __("Settings") }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div x-show="currentTab === 'issues'">
{{--                <livewire:shopper-settings.integrations.github.issues />--}}
            </div>
            <div x-cloak x-show="currentTab === 'setting'">
{{--                <livewire:shopper-settings.integrations.github.settings />--}}
            </div>
        </div>
    </div>

@endsection
