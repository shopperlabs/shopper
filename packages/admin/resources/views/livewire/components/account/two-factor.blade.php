<div class="mt-10 sm:mt-0">
    <div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <x-shopper::section-heading
            class="lg:col-span-1"
            :title="__('shopper::pages/auth.account.two_factor_title')"
            :description="__('shopper::pages/auth.account.two_factor_description')"
        />
        <div class="mt-5 lg:col-span-2 lg:mt-0 lg:max-w-3xl">
            <x-shopper::card>
                <div class="border-b border-gray-200 p-4 dark:border-white/10 sm:px-6">
                    <div class="flex items-center space-x-3">
                        <div
                            @class([
                                'size-2.5 shrink-0 rounded-full',
                                'bg-green-400' => $this->enabled,
                                'bg-gray-400 dark:bg-gray-500' => ! $this->enabled,
                            ])
                        ></div>
                        <h3 class="text-base font-medium leading-6 text-gray-900 dark:text-white">
                            @if ($this->enabled)
                                {{ __('shopper::pages/auth.account.two_factor_enabled') }}
                            @else
                                {{ __('shopper::pages/auth.account.two_factor_disabled') }}
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="space-y-6 px-4 py-5 sm:p-6">
                    @if (! $this->enabled)
                        <div class="rounded-md bg-primary-50 p-4 dark:bg-primary-800/20">
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-heroicon-s-information-circle class="size-5 text-primary-400" aria-hidden="" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 text-primary-500">
                                        {{ __('shopper::pages/auth.account.two_factor_install_message') }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5">
                                        <a
                                            href="https://support.google.com/accounts/answer/1066447"
                                            target="_blank"
                                            class="whitespace-no-wrap font-medium text-primary-500 transition duration-150 ease-in-out hover:text-primary-600"
                                        >
                                            {{ __('shopper::words.learn_more') }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <div class="shrink-0">
                            <span class="block size-12">
                                <x-heroicon-o-shield-check class="h-full w-full text-primary-600" aria-hidden="true" />
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/auth.account.two_factor_secure') }}
                            </p>
                            @if ($this->enabled)
                                <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/auth.account.two_factor_activation_message') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($this->enabled)
                        @if ($showingQrCode)
                            <div class="border-t border-gray-200 pt-5 dark:border-white/10">
                                <p class="max-w-2xl text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('shopper::pages/auth.account.two_factor_is_enabled') }}
                                </p>

                                <div class="mt-4">
                                    {!! $this->user->twoFactorQrCodeSvg() !!}
                                </div>
                            </div>
                        @endif

                        @if ($showingRecoveryCodes)
                            <div class="border-t border-gray-200 pt-5 dark:border-white/10">
                                <p class="max-w-2xl text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('shopper::pages/auth.account.two_factor_store_recovery_codes') }}
                                </p>

                                <div
                                    class="mt-4 grid max-w-xl gap-1 rounded-lg bg-gray-50 p-4 text-sm dark:bg-gray-700"
                                >
                                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                        <span class="leading-5 text-gray-700 dark:text-gray-300">{{ $code }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="flex justify-end px-4 py-4 sm:px-6">
                    @if (! $this->enabled)
                        <x-shopper::buttons.primary
                            wire:click="startConfirmingPassword('enableTwoFactorAuthentication')"
                            type="button"
                            wire:loading.attr="disabled"
                        >
                            <x-shopper::loader wire:loading wire:target="startConfirmingPassword" class="text-white" />
                            {{ __('shopper::forms.actions.enabled_two_factor') }}
                        </x-shopper::buttons.primary>
                    @else
                        <div class="space-x-3 sm:flex sm:items-center">
                            <x-shopper::buttons.danger
                                wire:click="startConfirmingPassword('disableTwoFactorAuthentication')"
                                wire:loading.attr="disabled"
                                type="button"
                            >
                                {{ __('shopper::forms.actions.disable') }}
                            </x-shopper::buttons.danger>
                            @if ($showingRecoveryCodes)
                                <x-shopper::buttons.primary
                                    wire:click="startConfirmingPassword('regenerateRecoveryCodes')"
                                    wire:loading.attr="disabled"
                                    type="button"
                                >
                                    {{ __('shopper::forms.actions.regenerate_code') }}
                                </x-shopper::buttons.primary>
                            @else
                                <x-shopper::buttons.default
                                    wire:click="startConfirmingPassword('showRecoveryCodes')"
                                    wire:loading.attr="disabled"
                                    type="button"
                                >
                                    {{ __('shopper::forms.actions.show_recovery_code') }}
                                </x-shopper::buttons.default>
                            @endif
                        </div>
                    @endif
                </div>
            </x-shopper::card>
        </div>
    </div>
</div>
