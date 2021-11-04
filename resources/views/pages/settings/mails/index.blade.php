@extends('shopper::layouts.default')
@section('title', __('Mail Configuration ~ Templates ~ Mailable'))

@section('content')

    <x:shopper-breadcrumb back="shopper.settings.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.index')" title="Settings" />
    </x:shopper-breadcrumb>

    <div
        x-data="{
            options: ['config', 'templates', 'mailables'],
            words: {
                'config': '{{ __('Configuration') }}',
                'templates': '{{ __('Templates') }}',
                'mailables': '{{ __("Mailables") }}'
            },
            currentTab: 'config'
        }"
        class="sm:-mx-8"
    >
        <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200 md:flex md:items-center md:justify-between dark:bg-gray-900 dark:border-gray-700">
            <div class="flex-1 min-w-0 sm:px-8">
                <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">{{ __('Email') }}</h2>
            </div>
        </div>
        <div class="min-w-0 flex-1 lg:flex">
            <aside class="hidden lg:block lg:flex-shrink-0">
                <div class="h-full relative flex flex-col w-80 border-r border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-800">
                    <nav aria-label="{{ __('Email menu') }}" class="min-h-(screen-content) flex-1 overflow-y-auto">
                        <ul class="border-b border-gray-200 divide-y divide-gray-200 dark:divide-gray-700 dark:border-gray-700">
                            <li class="relative py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-gray-800" :class="{ 'bg-gray-50 dark:bg-gray-800': currentTab === 'config' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'config'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ __('Configuration') }}</p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ __('Manage email global configuration, driver, host, port etc.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-gray-800" :class="{ 'bg-gray-50 dark:bg-gray-800': currentTab === 'templates' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                        <x-heroicon-o-template class="w-6 h-6" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'templates'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="inline-flex items-center text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ __('Templates') }}
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ __('work in progress') }}
                                                </span>
                                            </p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ __('Modify the mail templates that are sent to customers and administrators, manage email layouts.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-gray-800" :class="{ 'bg-gray-50 dark:bg-gray-800': currentTab === 'mailables' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                        <x-heroicon-o-mail class="w-6 h-6" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'mailables'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ __('Mailables') }}</p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ __('Create Laravel mailable class to use on your project to send email notifications.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <section aria-labelledby="configuration-heading" class="min-w-0 flex-1 h-full flex flex-col overflow-hidden">
                <div class="min-h-(screen-content) flex-1 overflow-y-auto">
                    <div class="lg:hidden py-6 border-b border-gray-200 sm:px-4 max-w-2xl mx-auto">
                        <x-shopper-input.select x-model="currentTab" aria-label="Selected tab" class="block w-full pl-3 pr-10 py-2">
                            <template x-for="option in options" :key="option">
                                <option
                                    x-bind:value="option"
                                    x-text="words[option]"
                                    x-bind:selected="option === currentTab"
                                ></option>
                            </template>
                        </x-shopper-input.select>
                    </div>

                    <div x-show="currentTab === 'config'">
                        <livewire:shopper-settings.mails.configuration />
                    </div>
                    <div x-cloak x-show="currentTab === 'templates'">
                        <livewire:shopper-settings.mails.templates />
                    </div>
                    <div x-cloak x-show="currentTab === 'mailables'">
                        <livewire:shopper-settings.mails.mailables />
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
