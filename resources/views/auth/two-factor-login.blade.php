<x-shopper::layouts.base :title="__('Login with Two-Factor')">

    <div x-data="{ recovery: false }" class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6 space-y-4">
            <x-shopper::validation-errors />

            <div class="shrink-0">
                <x-shopper::brand />
            </div>
            <div class="p-4 sm:p-6 bg-white rounded-lg shadow-md overflow-hidden dark:bg-secondary-800">
                <div class="text-center">
                    <h2 class="inline-flex items-center text-xl font-medium font-heading text-center leading-9 text-secondary-900 dark:text-white">
                        <x-heroicon-o-shield-check class="w-10 h-10 text-primary-600 -ml-1 mr-2" />
                        {{ __('Authenticate Your Account') }}
                    </h2>
                    <p class="mt-1 text-sm leading-5 text-center text-secondary-500 dark:text-secondary-400" x-show="! recovery">
                        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                    </p>
                    <p class="mt-1 text-sm leading-5 text-center text-secondary-500 dark:text-secondary-400" x-show="recovery" style="display: none">
                        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                    </p>
                </div>
                <form class="mt-5" action="{{ route('shopper.two-factor.post-login') }}" method="POST">
                    @csrf
                    <x-shopper::forms.group x-show="! recovery" label="Code" for="code">
                        <x-shopper::forms.input  id="code" type="text" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group x-show="recovery" label="Recovery Code" for="recovery_code" style="display: none">
                        <x-shopper::forms.input  id="recovery_code" name="recovery_code" type="text" x-ref="recovery_code" autocomplete="one-time-code" />
                    </x-shopper::forms.group>

                    <div class="mt-5 flex items-center space-x-4">
                        <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __("Can't remember this code?") }}
                            <button
                                class="ml-1 text-sm text-secondary-500 hover:text-secondary-900 underline cursor-pointer dark:text-secondary-400 dark:hover:text-white"
                                type="button"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                "
                            >
                                {{ __('Use a recovery code') }}
                            </button>

                            <button
                                x-show="recovery"
                                type="button"
                                class="ml-1 text-sm text-secondary-500 hover:text-secondary-900 underline cursor-pointer dark:text-secondary-400 dark:hover:text-white"
                                style="display: none"
                                x-on:click="
                                    recovery = false;
                                    $nextTick(() => { $refs.code.focus() })
                                "
                            >
                                {{ __('Use an authentication code') }}
                            </button>
                        </p>
                        <x-shopper::buttons.primary type="submit">
                            {{ __('Login') }}
                        </x-shopper::buttons.primary>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-shopper::layouts.base>
