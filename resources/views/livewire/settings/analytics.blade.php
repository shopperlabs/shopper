<div class="pb-20">
    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate pb-5 border-b border-gray-200">{{ __('Analytics') }}</h2>
        </div>
    </div>

    <form wire:submit.prevent="submit">
    <div class="mt-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <div class="flex-shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.75 7.5h-7.5a.75.75 0 0 0-.75.75v15c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-15a.75.75 0 0 0-.75-.75z" fill="#FFC107"/>
                            <path d="M8.25 15H.75a.75.75 0 0 0-.75.75v7.5c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75v-7.5a.75.75 0 0 0-.75-.75z" fill="#FFC107"/>
                            <path d="M23.25 0h-7.5a.75.75 0 0 0-.75.75v22.5c0 .414.336.75.75.75h7.5a.75.75 0 0 0 .75-.75V.75a.75.75 0 0 0-.75-.75z" fill="#FFA000"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium leading-6 text-gray-900">{{ __("Google Analytics") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Google Analytics allows you to track visitors to your site and generates reports that will help you with your marketing.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3">
                                <label for="google_tracking_id" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __("Google Analytics Tracking ID") }}
                                </label>
                                <div class="mt-1 relative">
                                    <input id="google_tracking_id" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="UA-XXX" wire:model="google_analytics_tracking_id"/>
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="google_view_id" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __("Google Analytics view ID") }}
                                </label>
                                <div class="mt-1 relative">
                                    <input id="google_view_id" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="123456789" wire:model="google_analytics_view_id" />
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="analytic_script" class="block text-sm leading-5 font-medium text-gray-700">
                                    {{ __("Google Analytics additional script") }}
                                </label>
                                <div class="rounded-md shadow-sm">
                                    <textarea id="analytic_script" rows="3" class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" wire:model="google_analytics_add_js"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="inline-flex items-center block text-sm leading-5 font-medium text-gray-700">
                                Live Reporting
                                <button type="button" class="ml-3 text-gray-400 hover:text-gray-500 outline-none focus:outline-none leading-4 transition duration-200 ease-in-out">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </label>
                            <div class="mt-2 flex items-center">
                                <span class="rounded-md shadow-sm">
                                    <button type="button" class="inline-flex items-center py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                        <svg class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.667 10.667v.666a2 2 0 0 0 2 2h6.666a2 2 0 0 0 2-2v-.666M10.667 8L8 10.667m0 0L5.333 8M8 10.667v-8" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Upload
                                    </button>
                                </span>
                                <span class="ml-4 text-sm leading-5 text-gray-400">{{ __("No json file selected.") }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <div class="flex-shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.089 23.09l-4.167-4.172 8.95-9.043 4.245 4.243-9.028 8.972z" fill="#8AB4F8"/>
                            <path d="M14.119 5.122L9.876.878.88 9.875a2.998 2.998 0 0 0-.002 4.24l.002.003 8.997 8.996 4.162-4.181-6.798-6.93 6.879-6.88z" fill="#4285F4"/>
                            <path d="M23.116 9.875L14.119.878a3 3 0 0 0-4.244 4.243l9.002 8.997a3 3 0 1 0 4.242-4.243h-.003z" fill="#8AB4F8"/>
                            <path d="M11.964 24.002a2.974 2.974 0 1 0 0-5.948 2.974 2.974 0 0 0 0 5.948z" fill="#246FDB"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium leading-6 text-gray-900">{{ __("Google Tag Manager") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Google Tag Manager allows marketing managers to easily add tags (Analytics, remarketing, etc.)") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="google_tag_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Your Google Tag Manager account ID") }}</label>
                                <div class="mt-1 relative">
                                    <input id="google_tag_id" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="GTM-XXX" wire:model="google_tag_manager_account_id" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __("Read more about") }} <a href="#" class="text-blue-500 hover:text-blue-400">Google Tag Manager</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <div class="flex-shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 4h3a.5.5 0 0 0 .5-.5v-3A.5.5 0 0 0 18 0h-3a5.506 5.506 0 0 0-5.5 5.5V9H6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3.5v10.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V13H17a.5.5 0 0 0 .474-.342l1-3A.5.5 0 0 0 18 9h-4.5V5.5A1.5 1.5 0 0 1 15 4z" fill="#2196F3"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium leading-6 text-gray-900">{{ __("Facebook Pixel") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Facebook Pixel helps you create ad campaigns to find new customers who are most like your buyers.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="pixel_facebook_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Your Facebook Pixel account ID") }}</label>
                                <div class="mt-1 relative">
                                    <input id="pixel_facebook_id" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="12345678" wire:model="facebook_pixel_account_id" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __("Read more about") }} <a href="#" class="text-blue-500 hover:text-blue-400">Facebook Pixel</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
            <span class="inline-flex rounded-md shadow-sm">
                <button class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 shadow-sm hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:bg-blue-500 active:bg-blue-600 transition duration-150 ease-in-out">
                    <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __("Save") }}
                </button>
            </span>
        </div>
    </div>
    </form>
</div>
