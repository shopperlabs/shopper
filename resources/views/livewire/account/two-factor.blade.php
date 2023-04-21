<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">
                    {{ __('shopper::pages/auth.account.two_factor_title') }}
                </h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/auth.account.two_factor_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md bg-white overflow-hidden dark:bg-secondary-800">
                <div class="p-4 sm:px-6 border-b border-secondary-200 dark:border-secondary-700">
                    <div class="flex items-center space-x-3">
                        <div @class([
                            'shrink-0 w-2.5 h-2.5 rounded-full',
                            'bg-green-400' => $this->enabled,
                            'bg-secondary-400 dark:bg-secondary-500' => ! $this->enabled,
                        ])></div>
                        <h3 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                            @if ($this->enabled)
                                {{ __('shopper::pages/auth.account.two_factor_enabled') }}
                            @else
                                {{ __('shopper::pages/auth.account.two_factor_disabled') }}
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    @if (! $this->enabled)
                        <div class="rounded-md bg-primary-50 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-heroicon-s-information-circle class="h-5 w-5 text-primary-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 text-primary-700">
                                        {{ __('shopper::pages/auth.account.two_factor_install_message') }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5">
                                        <a href="https://support.google.com/accounts/answer/1066447" target="_blank" class="whitespace-no-wrap font-medium text-primary-700 hover:text-primary-600 transition ease-in-out duration-150">
                                            {{ __('shopper::components.learn_more') }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <span class="block w-12 w-12">
                                <x-heroicon-o-shield-check class="w-full h-full text-primary-600" />
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/auth.account.two_factor_secure') }}
                            </p>
                            @if($this->enabled)
                                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                    {{ __('shopper::pages/auth.account.two_factor_activation_message') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($this->enabled)
                        @if ($showingQrCode)
                            <p class="max-w-xl text-sm text-secondary-500 dark:text-secondary-400 font-medium">
                                {{ __('shopper::pages/auth.account.two_factor_is_enabled') }}
                            </p>

                            <div class="mt-4">
                                {!! $this->user->twoFactorQrCodeSvg() !!}
                            </div>
                        @endif

                        @if ($showingRecoveryCodes)
                            <div class="mt-4 max-w-xl text-sm text-secondary-500 dark:text-secondary-400">
                                <p class="font-bold">
                                    {{ __('shopper::pages/auth.account.two_factor_store_recovery_codes') }}
                                </p>
                            </div>

                            <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 text-sm bg-secondary-100 rounded-lg dark:bg-secondary-700">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <div class="text-secondary-500 leading-5 dark:text-secondary-300">{{ $code }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="px-4 py-3 sm:px-6 text-right">
                    @if(! $this->enabled)
                        <x-shopper::confirms-password wire:then="enableTwoFactorAuthentication">
                            <x-shopper::buttons.primary type="button" wire:loading.attr="disabled">
                                {{ __('shopper::layout.forms.actions.enabled_two_factor') }}
                            </x-shopper::buttons.primary>
                        </x-shopper::confirms-password>
                    @else
                        <div class="sm:flex sm:flex-row-reverse">
                            <x-shopper::confirms-password wire:then="disableTwoFactorAuthentication">
                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                    <x-shopper::buttons.danger wire:loading.attr="disabled" type="button">
                                        {{ __('shopper::layout.forms.actions.disabled') }}
                                    </x-shopper::buttons.danger>
                                </span>
                            </x-shopper::confirms-password>
                            @if ($showingRecoveryCodes)
                                <x-shopper::confirms-password wire:then="regenerateRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <x-shopper::buttons.primary wire:loading.attr="disabled" type="button">
                                            {{ __('shopper::layout.forms.actions.regenerate') }}
                                        </x-shopper::buttons.primary>
                                    </span>
                                </x-shopper::confirms-password>
                            @else
                                <x-shopper::confirms-password wire:then="showRecoveryCodes">
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <x-shopper::buttons.default wire:loading.attr="disabled" type="button" class="justify-center w-full">
                                            {{ __('shopper::layout.forms.actions.show_recovery_code') }}
                                        </x-shopper::buttons.default>
                                    </span>
                                </x-shopper::confirms-password>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
