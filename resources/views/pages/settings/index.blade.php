@extends('shopper::layouts.default')
@section('title', __('Settings'))

@section('content')

    <div class="mt-4 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-secondary-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ __('Settings') }}</h2>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-secondary-800 rounded-lg shadow-lg p-4 sm:p-5">
        <div class="z-20 relative grid gap-4 sm:gap-x-6 sm:gap-y-4 sm:grid-cols-3">
            <a href="{{ route('shopper.settings.shop') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-cog class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('General') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('View and update your store informations.') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.users') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-users class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Staff & permissions') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('View and manage what staff can see or do in your store.') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.mails') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-mail class="w-6 h-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Email') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage email notifications that will be sent to your customers.') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.inventories.index') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-location-marker class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Locations') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage the places you stock inventory and sell products.') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.attributes.index') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-clipboard-list class="w-6 h-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Attributes') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage additional attributes for your products.') }}
                    </p>
                </div>
            </a>
            <a x-on:click.prevent="modalDemo = true" href="#" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <svg class="h-6 w-6" stroke="none" fill="currentColor" viewBox="0 0 24 24">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M17.765 5.453c2.905 0 5.226 2.405 5.235 5.36v6.042c-.06 1.055-1.028.955-1.028.955v.008c0 1.726-1.431 3.182-3.128 3.182-1.743 0-3.127-1.408-3.127-3.182v-.008h-5.54v.008c0 1.726-1.43 3.182-3.126 3.182-1.73 0-3.106-1.39-3.127-3.143H2.895a.905.905 0 01-.895-.91V2.906A.9.9 0 012.895 2h10.672c2.784.122 2.48 2.881 2.454 3.452h1.744zm0 1.818H16.02v3.726h5.227v-.135c0-2-1.564-3.591-3.483-3.591zM7.046 19.227c.758 0 1.384-.637 1.384-1.409 0-.771-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm7.236-3.235V4.542a.712.712 0 00-.715-.728H3.743v12.222h.703a3.099 3.099 0 012.6-1.4c1.071 0 2.01.532 2.57 1.356h4.666zm4.554 3.235c.763 0 1.388-.637 1.384-1.409-.043-.77-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm0-4.59c.977 0 1.842.444 2.412 1.142V12.81H16.02v3.225h.214a3.099 3.099 0 012.6-1.4h.001zm-7.634-7.641c.488 0 .895.41.895.91 0 .501-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69zm0 3.727c.488 0 .89.41.895.91-.047.502-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Shipping and delivery') }}
                        <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                            {{ __('Soon') }}
                        </span>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage how you ship orders to customers.') }}
                    </p>
                </div>
            </a>
            <a x-on:click.prevent="modalDemo = true" href="#" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-cube-transparent class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        <span>{{ __('Integrations') }}</span>
                        <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                            {{ __('Soon') }}
                        </span>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __("Connect with third-party tools that you’re already using.") }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.analytics') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-chart-bar class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        <span>{{ __('Analytics') }}</span>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Get a better understanding of where your traffic is coming from.') }}
                    </p>
                </div>
            </a>
            <a x-on:click.prevent="modalDemo = true" href="#" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-receipt-tax class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Taxes') }}
                        <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                            {{ __('Soon') }}
                        </span>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage how your store charges taxes.') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.payments') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-credit-card class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Payment methods') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Add different payment methods for your customers.') }}
                    </p>
                </div>
            </a>
            <a x-on:click.prevent="modalDemo = true" href="#" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-paper-clip class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Files') }}
                        <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                            {{ __('Soon') }}
                        </span>
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage store assets (images, videos and documents).') }}
                    </p>
                </div>
            </a>
            <a href="{{ route('shopper.settings.legal') }}" class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200">
                <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
                    <x-heroicon-o-lock-closed class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                        {{ __('Legal') }}
                    </p>
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __("Manage your store's legal pages such as privacy, terms.") }}
                    </p>
                </div>
            </a>
        </div>
    </div>

@endsection
