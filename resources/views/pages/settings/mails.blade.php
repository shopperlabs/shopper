@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Mail Configuration ~ Templates ~ Mailable'))

@section('content')

    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div
        x-data="{ currentTab: 'config' }"
        class="sm:-mx-8"
    >
        <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0 sm:px-8">
                <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Email') }}</h2>
            </div>
        </div>
        <div class="min-w-0 flex-1 lg:flex">
            <aside class="hidden lg:block lg:flex-shrink-0">
                <div class="h-full relative flex flex-col w-80 border-r border-gray-200 bg-white">
                    <nav aria-label="{{ __('Email menu') }}" class="min-h-(screen-content) flex-1 overflow-y-auto">
                        <ul class="border-b border-gray-200 divide-y divide-gray-200">
                            <li class="relative bg-white py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-600" :class="{ 'bg-gray-50': currentTab === 'config' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'config'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ __('Configuration') }}</p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500">
                                                    {{ __('Manage email global configuration, driver, host, port etc.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative bg-white py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-600" :class="{ 'bg-gray-50': currentTab === 'templates' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'templates'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ __('Templates') }}</p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500">
                                                    {{ __('Modify the mail templates that are sent to customers and administrators, manage email layouts.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative bg-white py-5 px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-600" :class="{ 'bg-gray-50': currentTab === 'mailables' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="flex-shrink-0 text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'mailables'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ __('Mailables') }}</p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-gray-500">
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
