<div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Stripe') }}</h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('Accept payments on your store using third-party providers such as Stripe.') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                <div class="p-4 sm:px-6 border-b border-secondary-200 dark:border-secondary-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $this->enabled ? 'bg-green-400' : 'bg-secondary-400 dark:bg-secondary-600' }}"></div>
                            <h3 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                                @if ($this->enabled)
                                    {{ __('Stripe is available for your store.') }}
                                @else
                                    {{ __('Stripe is not enabled.') }}
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="shrink-0">
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
                        <p class="text-secondary-500 text-sm leading-5 dark:text-secondary-400">
                            {{ __('This provider allows you to integrate Laravel Cashier into your store to allow your customers to make payments, subscriptions using Stripe.') }}
                            <a href="https://laravel.com/docs/billing" target="_blank" class="text-primary-600 hover:text-primary-500">{{ __('Learn more about Laravel Cashier') }}</a>
                        </p>
                        @if(! $this->enabled)
                            <span class="mt-4 inline-flex rounded-md shadow-sm">
                                <x-shopper-default-button wire:click="enabledStripe" wire.loading.attr="disabled" type="button">
                                    <x-shopper-loader wire:loading wire:target="enabledStripe" class="text-secondary-600 dark:text-secondary-300" />
                                    {{ __('Enabled Stripe Payment') }}
                                </x-shopper-default-button>
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
                <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Environnement') }}</h3>
                        <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('Stripe has two environments Sandbox and Live, make sure to use sandbox for testing before going live.') }}
                            {{ __('API Keys can be grabbed from') }} <a href="https://dashboard.stripe.com/account/apikeys" target="_blank" class="text-primary-600 dark:text-primary-500/50">https://dashboard.stripe.com/account/apikeys</a>
                            {{ __('To enable Sandbox switch Sandbox mode to True.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow rounded-md overflow-hidden">
                        <div class="px-4 py-5 sm:p-6 space-y-4 bg-white dark:bg-secondary-800">
                            <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                                <x-shopper-forms.group label="Stripe Mode" for="stripe_mode" class="col-span-6">
                                    <x-shopper-forms.select wire:model.lazy="stripe_mode" id="stripe_mode">
                                        <option value="sandbox">Sandbox</option>
                                        <option value="live">Live</option>
                                    </x-shopper-forms.select>
                                </x-shopper-forms.group>

                                <x-shopper-forms.group label="Public key" for="public_key" class="sm:col-span-3">
                                    <x-shopper-forms.input wire:model.lazy="stripe_key" id="public_key" type="text" autocomplete="off" />
                                </x-shopper-forms.group>

                                <x-shopper-forms.group label="Secret key" for="secret_key" class="sm:col-span-3">
                                    <x-shopper-forms.input wire:model.lazy="stripe_secret" id="secret_key" type="text" />
                                </x-shopper-forms.group>

                                <div class="sm:col-span-6">
                                    <x-shopper-forms.group label="Webhook key" for="webhook_key">
                                        <x-shopper-forms.input wire:model.lazy="stripe_webhook_secret" id="webhook_key" type="text" autocomplete="off" />
                                    </x-shopper-forms.group>
                                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                        {{ __('Webhooks Key can be grabbed from') }} <a href="https://dashboard.stripe.com/account/webhooks" target="_blank" class="text-primary-600 dark:text-primary-500/50">https://dashboard.stripe.com/account/webhooks</a>
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
                <div class="border-t border-secondary-200  dark:border-secondary-700"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Cashier currency') }}</h3>
                        <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('The default currency that will be used when generating charges from your store.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6 bg-white dark:bg-secondary-800">
                            <div class="grid grid-cols-6 gap-6">
                                <x-shopper-forms.group label="Default Currency" for="currency" class="col-span-6">
                                    <x-shopper-forms.select wire:model="currency" id="currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                        @endforeach
                                    </x-shopper-forms.select>
                                </x-shopper-forms.group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-5 border-t border-secondary-200 dark:border-secondary-700">
            <div class="flex justify-end">
                <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                    <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                    {{ __('Update Configuration') }}
                </x-shopper-button>
            </div>
        </div>

    @endif

</div>
