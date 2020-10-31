<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                    {{ __("Two Factor Authentication") }}
                </h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("After entering your password, verify your identity with a second authentication method.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md overflow-hidden">
                <div class="bg-white p-4 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $this->enabled ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                        <h3 class="text-base leading-6 font-medium text-gray-900">
                            @if ($this->enabled)
                                {{ __('You have enabled two factor authentication.') }}
                            @else
                                {{ __('You have not enabled two factor authentication.') }}
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6 space-y-6">
                    @if (! $this->enabled)
                        <div class="rounded-md bg-blue-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 md:flex md:justify-between">
                                    <p class="text-sm leading-5 text-blue-700">
                                        {{ __("To utilize two factor authentication, you must install the Google Authenticator application on your smartphone.") }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5 md:mt-0 md:ml-6">
                                        <a href="https://support.google.com/accounts/answer/1066447" target="_blank" class="whitespace-no-wrap font-medium text-blue-700 hover:text-blue-600 transition ease-in-out duration-150">
                                            {{ __("Details") }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="block w-12 w-12">
                                <svg class="w-full h-full text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm">
                                {{ __("With two factor authentication, only you can access your account — even if someone else has your password.") }}
                            </p>
                            @if($this->enabled)
                                <p class="mt-1 text-gray-500 text-sm leading-5">
                                    {{ __("When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.") }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($this->enabled)
                        @if ($showingQrCode)
                            <div class="max-w-xl text-sm text-gray-600">
                                <p class="font-semibold">
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                {!! $this->user->twoFactorQrCodeSvg() !!}
                            </div>
                        @endif

                        @if ($showingRecoveryCodes)
                            <div class="mt-4 max-w-xl text-sm text-gray-600">
                                <p class="font-semibold">
                                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                                </p>
                            </div>

                            <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    @if(! $this->enabled)
                        <x-shopper-confirms-password wire:then="enableTwoFactorAuthentication">
                            <x-shopper-button type="button" wire:loading.attr="disabled">
                                {{ __("Enable Authentication") }}
                            </x-shopper-button>
                        </x-shopper-confirms-password>
                    @else
                        <div class="sm:flex sm:flex-row-reverse">
                            <x-shopper-confirms-password wire:then="disableTwoFactorAuthentication">
                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                    <button wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                        {{ __('Disable') }}
                                    </button>
                                </span>
                            </x-shopper-confirms-password>
                            @if ($showingRecoveryCodes)
                                <x-shopper-confirms-password wire:then="regenerateRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            {{ __('Regenerate Recovery Codes') }}
                                        </button>
                                    </span>
                                </x-shopper-confirms-password>
                            @else
                                <x-shopper-confirms-password wire:then="showRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            {{ __('Show Recovery Codes') }}
                                        </button>
                                    </span>
                                </x-shopper-confirms-password>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
