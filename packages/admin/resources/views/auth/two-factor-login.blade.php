<x-shopper::layouts.base :title="__('shopper::pages/auth.two_factor.title')">
    <x-shopper::auth-card>
        <div x-data="{ recovery: false }" class="space-y-4">
            <x-shopper::validation-errors />

            <div>
                <x-shopper::brand class="mx-auto h-12 w-auto" />

                <div class="mt-6 text-center">
                    <h2 class="inline-flex items-center text-xl font-medium font-heading text-center leading-9 text-secondary-900 dark:text-white">
                        <x-heroicon-o-shield-check class="w-10 h-10 text-primary-600 -ml-1 mr-2" />
                        {{ __('shopper::pages/auth.two_factor.subtitle') }}
                    </h2>
                    <p class="mt-1 text-sm leading-5 text-center text-secondary-500 dark:text-secondary-400" x-show="! recovery">
                        {{ __('shopper::pages/auth.two_factor.authentication_code') }}
                    </p>
                    <p class="mt-1 text-sm leading-5 text-center text-secondary-500 dark:text-secondary-400" x-show="recovery" style="display: none">
                        {{ __('shopper::pages/auth.two_factor.recovery_code') }}
                    </p>
                </div>
                <form class="mt-5" action="{{ route('shopper.two-factor.post-login') }}" method="POST">
                    @csrf
                    <x-shopper::forms.group x-show="! recovery" :label="__('shopper::layout.forms.label.code')" for="code">
                        <x-shopper::forms.input id="code" type="text" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group x-show="recovery" :label="__('shopper::layout.forms.label.recovery_code')" for="recovery_code" style="display: none">
                        <x-shopper::forms.input id="recovery_code" name="recovery_code" type="text" x-ref="recovery_code" autocomplete="one-time-code" />
                    </x-shopper::forms.group>

                    <div class="mt-5 flex items-center space-x-4">
                        <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/auth.two_factor.remember') }}
                            <button
                                class="ml-1 text-sm text-secondary-500 hover:text-secondary-900 underline cursor-pointer dark:text-secondary-400 dark:hover:text-white"
                                type="button"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                "
                            >
                                {{ __('shopper::pages/auth.two_factor.use_recovery_code') }}
                            </button>

                            <button
                                x-show="recovery"
                                type="button"
                                class="ml-1 text-sm text-secondary-500 hover:text-secondary-900 underline cursor-pointer dark:text-secondary-400 dark:hover:text-white"
                                style="display: none"
                                x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                            >
                                {{ __('shopper::pages/auth.two_factor.use_authentication_code') }}
                            </button>
                        </p>
                        <x-shopper::buttons.primary type="submit">
                            {{ __('shopper::pages/auth.two_factor.action') }}
                        </x-shopper::buttons.primary>
                    </div>
                </form>
            </div>
        </div>
    </x-shopper::auth-card>
</x-shopper::layouts.base>
