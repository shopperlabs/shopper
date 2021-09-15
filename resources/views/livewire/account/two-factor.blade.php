<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">
                    {{ __('Two Factor Authentication') }}
                </h3>
                <p class="mt-4 text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ __('After entering your password, verify your identity with a second authentication method.') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md bg-white overflow-hidden dark:bg-gray-800">
                <div class="p-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $this->enabled ? 'bg-green-400' : 'bg-gray-400 dark:bg-gray-500' }}"></div>
                        <h3 class="text-base leading-6 font-medium text-gray-900 dark:text-white">
                            @if ($this->enabled)
                                {{ __('You have enabled two factor authentication.') }}
                            @else
                                {{ __('You have not enabled two factor authentication.') }}
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    @if (! $this->enabled)
                        <div class="rounded-md bg-blue-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-heroicon-s-information-circle class="h-5 w-5 text-blue-400" />
                                </div>
                                <div class="ml-3 flex-1 md:flex md:justify-between">
                                    <p class="text-sm leading-5 text-blue-700">
                                        {{ __('To utilize two factor authentication, you must install the Google Authenticator application on your smartphone.') }}
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
                                <x-heroicon-o-shield-check class="w-full h-full text-blue-600" />
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('With two factor authentication, only you can access your account — even if someone else has your password.') }}
                            </p>
                            @if($this->enabled)
                                <p class="mt-1 text-sm leading-5text-gray-500 dark:text-gray-400">
                                    {{ __("When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.") }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($this->enabled)
                        @if ($showingQrCode)
                            <div class="max-w-xl text-sm text-gray-500 dark:text-gray-400">
                                <p class="font-bold">
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                {!! $this->user->twoFactorQrCodeSvg() !!}
                            </div>
                        @endif

                        @if ($showingRecoveryCodes)
                            <div class="mt-4 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                                <p class="font-bold">
                                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                                </p>
                            </div>

                            <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 text-sm bg-gray-100 rounded-lg dark:bg-gray-700">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <div class="text-gray-500 leading-5 dark:text-gray-300">{{ $code }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="px-4 py-3 sm:px-6 text-right">
                    @if(! $this->enabled)
                        <x-shopper-confirms-password wire:then="enableTwoFactorAuthentication">
                            <x-shopper-button type="button" wire:loading.attr="disabled">
                                {{ __('Enable Authentication') }}
                            </x-shopper-button>
                        </x-shopper-confirms-password>
                    @else
                        <div class="sm:flex sm:flex-row-reverse">
                            <x-shopper-confirms-password wire:then="disableTwoFactorAuthentication">
                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                    <x-shopper-danger-button wire:loading.attr="disabled" type="button">
                                        {{ __('Disable') }}
                                    </x-shopper-danger-button>
                                </span>
                            </x-shopper-confirms-password>
                            @if ($showingRecoveryCodes)
                                <x-shopper-confirms-password wire:then="regenerateRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <x-shopper-default-button wire:loading.attr="disabled" type="button">
                                            {{ __('Regenerate Recovery Codes') }}
                                        </x-shopper-default-button>
                                    </span>
                                </x-shopper-confirms-password>
                            @else
                                <x-shopper-confirms-password wire:then="showRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <x-shopper-default-button wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            {{ __('Show Recovery Codes') }}
                                        </x-shopper-default-button>
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
