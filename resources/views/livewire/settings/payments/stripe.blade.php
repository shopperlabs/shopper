<div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Stripe") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("Accept payments on your store using third-party providers such as Stripe.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md overflow-hidden">
                <div class="bg-white p-4 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $this->enabled ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                            <h3 class="text-base leading-6 font-medium text-gray-900">
                                @if ($this->enabled)
                                    {{ __('Stripe is available for your store.') }}
                                @else
                                    {{ __('Stripe is not enabled.') }}
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95.779 40.164">
                            <g transform="translate(24.946 -325.034)">
                                <g transform="translate(-38.97 315.774) scale(.26458)">
                                    <path d="M414 113.4c0-25.6-12.4-45.8-36.1-45.8-23.8 0-38.2 20.2-38.2 45.6 0 30.1 17 45.3 41.4 45.3 11.9 0 20.9-2.7 27.7-6.5v-20c-6.8 3.4-14.6 5.5-24.5 5.5-9.7 0-18.3-3.4-19.4-15.2h48.9c0-1.3.2-6.5.2-8.9zm-49.4-9.5c0-11.3 6.9-16 13.2-16 6.1 0 12.6 4.7 12.6 16z" class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd"/>
                                    <path d="M301.1 67.6c-9.8 0-16.1 4.6-19.6 7.8l-1.3-6.2h-22v116.6l25-5.3.1-28.3c3.6 2.6 8.9 6.3 17.7 6.3 17.9 0 34.2-14.4 34.2-46.1-.1-29-16.6-44.8-34.1-44.8zm-6 68.9c-5.9 0-9.4-2.1-11.8-4.7l-.1-37.1c2.6-2.9 6.2-4.9 11.9-4.9 9.1 0 15.4 10.2 15.4 23.3 0 13.4-6.2 23.4-15.4 23.4z" class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd"/>
                                    <path clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd" d="M248.9 56.3V36l-25.1 5.3v20.4z"/>
                                    <path class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd" d="M223.8 69.3h25.1v87.5h-25.1z"/>
                                    <path d="M196.9 76.7l-1.6-7.4h-21.6v87.5h25V97.5c5.9-7.7 15.9-6.3 19-5.2v-23c-3.2-1.2-14.9-3.4-20.8 7.4z" class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd"/>
                                    <path d="M146.9 47.6l-24.4 5.2-.1 80.1c0 14.8 11.1 25.7 25.9 25.7 8.2 0 14.2-1.5 17.5-3.3V135c-3.2 1.3-19 5.9-19-8.9V90.6h19V69.3h-19z" class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd"/>
                                    <path d="M79.3 94.7c0-3.9 3.2-5.4 8.5-5.4 7.6 0 17.2 2.3 24.8 6.4V72.2c-8.3-3.3-16.5-4.6-24.8-4.6C67.5 67.6 54 78.2 54 95.9c0 27.6 38 23.2 38 35.1 0 4.6-4 6.1-9.6 6.1-8.3 0-18.9-3.4-27.3-8v23.8c9.3 4 18.7 5.7 27.3 5.7 20.8 0 35.1-10.3 35.1-28.2-.1-29.8-38.2-24.5-38.2-35.7z" class="st0" clip-rule="evenodd" fill="#6772e5" fill-rule="evenodd"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-500 text-sm">
                            {{ __("This provider allows you to integrate Laravel Cashier into your store to allow your customers to make payments, subscriptions using Stripe.") }}
                            <a href="https://laravel.com/docs/billing" target="_blank" class="text-blue-600 hover:text-blue-500 transition-colors duration-150 ease-in-out">{{ __("Learn more about Laravel Cashier") }}</a>
                        </p>
                        @if(! $this->enabled)
                            <span class="mt-4 inline-flex rounded-md shadow-sm">
                                <button wire:click="enabledStripe" wire.loading.attr="disabled" type="button" class="inline-flex items-center py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-light-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="enabledStripe" class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    {{ __("Enabled Stripe Payment") }}
                                </button>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($this->enabled)

        <div class="hidden sm:block">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Environnement") }}</h3>
                        <p class="mt-4 text-sm leading-5 text-gray-600">
                            {{ __("Stripe has two environments Sandbox and Live, make sure to use sandbox for testing before going live.") }}
                            {{ __("API Keys can be grabbed from") }} <a href="https://dashboard.stripe.com/account/apikeys" target="_blank" class="text-blue-700 hover:text-blue-600">https://dashboard.stripe.com/account/apikeys</a>
                            {{ __("To enable Sandbox switch Sandbox mode to True.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow rounded-md overflow-hidden">
                        <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                            <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                                <div class="col-span-6">
                                    <label for="stripe_mode" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Stripe Mode") }}</label>
                                    <div class="mt-1 rounded-md shadow-sm">
                                        <select wire:model="stripe_mode" id="stripe_mode" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                            <option value="sandbox">Sandbox</option>
                                            <option value="live">Live</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <x-shopper-input.group label="Public key" for="public_key">
                                        <input wire:model="stripe_key" id="public_key" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>

                                <div class="sm:col-span-3">
                                    <x-shopper-input.group label="Secret key" for="secret_key">
                                        <input wire:model="stripe_secret" id="secret_key" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>

                                <div class="sm:col-span-6">
                                    <x-shopper-input.group label="Webhook key" for="webhook_key">
                                        <input wire:model="stripe_webhook_secret" id="webhook_key" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                    <p class="mt-2 text-gray-500 text-sm leading-5">
                                        {{ __("Webhooks Key can be grabbed from") }} <a href="https://dashboard.stripe.com/account/webhooks" target="_blank" class="text-blue-600 hover:text-blue-500">https://dashboard.stripe.com/account/webhooks</a>
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
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Cashier currency") }}</h3>
                        <p class="mt-4 text-sm leading-5 text-gray-500">
                            {{ __("The default currency that will be used when generating charges from your store.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="currency" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Default Currency") }}</label>
                                    <div class="mt-1 rounded-md shadow-sm">
                                        <select wire:model="currency" id="currency" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
                <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                    <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __("Update Configuration") }}
                </x-shopper-button>
            </div>
        </div>

    @endif

    <x-shopper-modal wire:model="confirmInstallation" maxWidth="lg">
        <div class="p-4 sm:p-6 space-y-5">
            <div>
                <div class="py-4">
                    <h3 class="text-xs leading-4 font-medium text-gray-900 tracking-wide uppercase">{{ __("What's included") }}</h3>
                    <ul class="mt-6 space-y-2">
                        <li class="flex space-x-2">
                            <svg class="flex-shrink-0 h-5 w-5 text-green-500" aria-hidden="true" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm leading-5 text-gray-500">{{ __("Download Laravel Cashier from composer") }}</span>
                        </li>
                        <li class="flex space-x-2">
                            <svg class="flex-shrink-0 h-5 w-5 text-green-500" aria-hidden="true" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm leading-5 text-gray-500">{{ __("Run package migrations") }}</span>
                        </li>
                        <li class="flex space-x-2">
                            <svg class="flex-shrink-0 h-5 w-5 text-green-500" aria-hidden="true" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm leading-5 text-gray-500">{{ __("Set up environnement variables") }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="relative bg-gray-900 shadow rounded-md h-28 overflow-y-auto overflow-hidden p-3">
                <div class="absolute left-0 top-0 px-2">
                    <span class="text-gray-600 text-xs leading-4">{{ __("output") }}</span>
                </div>
                <div class="mt-2 text-gray-500 text-sm leading-5">
                    {{ $message }}
                </div>
            </div>
            <div class="sm:mt-4 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <x-shopper-button wire:click="install" type="button" wire.loading.attr="disabled">
                        <svg wire:loading wire:target="install" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ __("Proceed to installation") }}
                    </x-shopper-button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button wire:click="closeModal" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        {{ __("Cancel") }}
                    </button>
                </span>
            </div>
        </div>
    </x-shopper-modal>

</div>
