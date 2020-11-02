<div>
    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate pb-5 border-b border-gray-200">{{ __('Payments methods') }}</h2>
        </div>
    </div>

    <div class="mt-10">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <div class="flex-shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 4h3a.5.5 0 0 0 .5-.5v-3A.5.5 0 0 0 18 0h-3a5.506 5.506 0 0 0-5.5 5.5V9H6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3.5v10.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V13H17a.5.5 0 0 0 .474-.342l1-3A.5.5 0 0 0 18 9h-4.5V5.5A1.5 1.5 0 0 1 15 4z" fill="#2196F3"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-semibold leading-6 text-gray-900">{{ __("Stripe") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Stripe allows your visitors to pay through the well-known and popular Stripe gateway.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="pixel_facebook_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Stripe API key") }}</label>
                                <div class="mt-1 relative">
                                    <input wire:model="facebook_pixel_account_id" id="pixel_facebook_id" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="12345678" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __("Read more about") }} <a href="https://www.facebook.com/business/learn/facebook-ads-pixel" target="_blank" class="text-blue-500 hover:text-blue-400">Stripe SDK</a>.
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
                <button wire:click="store" type="button" class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 shadow-sm hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:bg-blue-500 active:bg-blue-600 transition duration-150 ease-in-out">
                    <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __("Save") }}
                </button>
            </span>
        </div>
    </div>

</div>
