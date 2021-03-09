@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Dashboard'))

@section('content')

    <div class="mt-4 pb-5 border-b border-gray-200 flex items-center">
        <svg class="h-6 w-6 mr-1.5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8 3.6a1.2 1.2 0 1 1 2.4 0v6.6a.6.6 0 1 0 1.2 0V4.8a1.2 1.2 0 1 1 2.4 0v5.4a.6.6 0 1 0 1.2 0v-3a1.2 1.2 0 1 1 2.4 0v6a8.401 8.401 0 0 1-16.16 3.215A8.4 8.4 0 0 1 3.6 13.2v-2.4a1.2 1.2 0 0 1 2.4 0v3a.6.6 0 1 0 1.2 0v-9a1.2 1.2 0 0 1 2.4 0v5.4a.6.6 0 1 0 1.2 0V3.6z" />
        </svg>
        <h2 class="text-2xl leading-6 text-gray-600 sm:leading-9 sm:truncate">
            @lang('shopper::messages.dashboard.title')
        </h2>
    </div>

    <div class="my-8 bg-white overflow-hidden shadow-lg sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="text-xl text-gray-700 font-medium">
                {{ __("Start with the basic for your online store") }}
            </div>

            <div class="mt-4 text-gray-500">
                @lang('shopper::messages.dashboard.description')
            </div>
        </div>

        <div class="bg-gray-50 grid grid-cols-1 md:grid-cols-2">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    <h4 class="ml-4 text-base text-gray-900 leading-6 font-medium">Documentation</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-4 text-sm text-gray-500">
                        {{ __('Get to know Laravel Shopper by understanding its capabilities the right way, whether you are new to the framework or have already worked on it. This documentation is made for you.') }}
                    </div>

                    <a href="https://docs.laravelshopper.io" class="group">
                        <div class="mt-3 flex items-center text-sm font-medium text-blue-600">
                            <span>{{ __('Visit the documentation') }}</span>

                            <span class="ml-1 text-blue-500 transform translate-x-0 group-hover:translate-x-1 transition duration-150 ease-in-out">
                                <svg class="arrow-right w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-gray-200 sm:border-t-0">
                <div class="flex items-center">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                    </svg>
                    <h4 class="ml-4 text-bas text-gray-900 leading-6 font-medium">{{ __('Screencasts') }}</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-4 text-sm text-gray-500">
                        {{ __('Learn how to Learn to build a professional online store from start to finish with complete Shopper video lessons and sample codes to quickly set up your store.') }}
                    </div>

                    <a href="https://docs.laravelshopper.io/screencasts" class="group">
                        <div class="mt-3 flex items-center text-sm font-medium text-blue-600">
                            <span>{{ __('Start watching Shopper') }}</span>

                            <span class="ml-1 text-blue-500 transform translate-x-0 group-hover:translate-x-1 transition duration-150 ease-in-out">
                                <svg class="arrow-right w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-gray-200">
                <div class="flex items-center">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                        <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <h4 class="ml-4 text-base text-gray-900 leading-6 font-medium">{{ __('Themes') }}</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-2 text-sm text-gray-500">
                        {{ __('Your store is the website for your products. Get up and running quickly with an available theme, specially created for Shopper. Edit as needed or create your own theme.') }}
                    </div>

                    <a href="#" class="group">
                        <div class="mt-3 flex items-center text-sm font-medium text-blue-600">
                            <span>{{ __('Find a Theme') }}</span>

                            <span class="ml-1 text-blue-500 transform translate-x-0 group-hover:translate-x-1 transition duration-150 ease-in-out">
                                <svg class="arrow-right w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-gray-200">
                <div class="flex items-center">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h4 class="ml-4 text-base text-gray-900 leading-6 font-medium">{{ __('Add product') }}</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-2 text-sm text-gray-500">
                        {{ __("Add products and prices to start selling. Tailor it to your store's needs with an unlimited number of products (depending on the size of your store), brands, collections, and variations.") }}
                    </div>

                    @can('add_products')
                        <a href="{{ route('shopper.products.create') }}" class="group">
                            <div class="mt-3 flex items-center text-sm font-medium text-blue-600">
                                <span>{{ __('Add product to your store') }}</span>

                                <span class="ml-1 text-blue-500 transform translate-x-0 group-hover:translate-x-1 transition duration-150 ease-in-out">
                                    <svg class="arrow-right w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection
