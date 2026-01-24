<x-shopper::auth-card>
    <div class="space-y-4">
        <x-shopper::validation-errors />

        <div>
            <x-shopper::brand class="mx-auto size-12" />

            @if (! $challengedUserId)
                <h2 class="font-heading mt-6 text-center text-3xl font-bold text-gray-950 dark:text-white">
                    {{ __('shopper::pages/auth.login.title') }}
                </h2>
                <p class="mt-3 max-w-sm text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/auth.login.or') }}
                    <a href="{{ url('/') }}" class="text-primary-600 hover:text-primary-500 font-medium">
                        {{ __('shopper::pages/auth.login.return_landing') }}
                    </a>
                </p>
            @else
                <h2
                    class="font-heading mt-6 inline-flex w-full items-center justify-center text-center text-xl leading-9 font-medium text-gray-900 dark:text-white"
                >
                    <x-heroicon-o-shield-check class="text-primary-600 mr-2 -ml-1 size-10" aria-hidden="true" />
                    {{ __('shopper::pages/auth.two_factor.subtitle') }}
                </h2>
                <p class="mt-1 text-center text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{
                        $useRecoveryCode
                            ? __('shopper::pages/auth.two_factor.recovery_code')
                            : __('shopper::pages/auth.two_factor.authentication_code')
                    }}
                </p>
            @endif
        </div>
    </div>

    @if (! $challengedUserId)
        <form class="mt-6" wire:submit="authenticate">
            <div class="rounded-md">
                <div>
                    <input
                        aria-label="{{ __('shopper::forms.label.email') }}"
                        name="email"
                        type="email"
                        wire:model="email"
                        autocomplete="email address"
                        class="focus:ring-primary-500 dark:focus:ring-primary-500 relative block w-full rounded-t-lg border-0 px-3 py-1.5 text-gray-900 placeholder-gray-400 ring-1 ring-gray-300 ring-inset focus:z-10 focus:ring-2 focus:outline-none sm:text-sm dark:bg-white/5 dark:text-gray-300 dark:ring-white/10 dark:focus:ring-offset-gray-900"
                        placeholder="{{ __('shopper::forms.label.email') }}"
                        required
                    />
                </div>
                <div class="-mt-px">
                    <input
                        aria-label="{{ __('shopper::forms.label.password') }}"
                        name="password"
                        type="password"
                        wire:model="password"
                        class="focus:ring-primary-500 dark:focus:ring-primary-500 relative block w-full rounded-b-lg border-0 px-3 py-1.5 text-gray-900 placeholder-gray-400 ring-1 ring-gray-300 ring-inset focus:z-10 focus:ring-2 focus:outline-none sm:text-sm dark:bg-white/5 dark:text-gray-300 dark:ring-white/10 dark:focus:ring-offset-gray-900"
                        placeholder="{{ __('shopper::forms.label.password') }}"
                        required
                    />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        name="remember"
                        wire:model="remember"
                        type="checkbox"
                        class="text-primary-600 focus:ring-primary-500 size-4 rounded border-gray-300 dark:border-white/10 dark:bg-gray-800 dark:focus:ring-offset-gray-900"
                    />
                    <x-shopper::label
                        for="remember"
                        class="ml-2 cursor-pointer"
                        :value="__('shopper::forms.label.remember')"
                    />
                </div>

                <div class="text-sm leading-5">
                    <x-shopper::link
                        :href="route('shopper.password.request')"
                        class="text-primary-600 hover:text-primary-500 font-medium"
                    >
                        {{ __('shopper::pages/auth.login.forgot_password') }}
                    </x-shopper::link>
                </div>
            </div>

            <div class="mt-6">
                <x-filament::button type="submit" class="group relative w-full justify-center">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3" wire:target="authenticate">
                        <x-untitledui-lock-04
                            class="text-primary-500 group-hover:text-primary-400 size-5"
                            aria-hidden="true"
                            wire:loading.remove
                        />
                        <x-shopper::loader
                            wire:loading
                            wire:target="authenticate"
                            class="text-white"
                            aria-hidden="true"
                        />
                    </span>
                    {{ __('shopper::pages/auth.login.action') }}
                </x-filament::button>
            </div>
        </form>
    @else
        <form class="mt-5" wire:submit="authenticate">
            @if (! $useRecoveryCode)
                <x-shopper::forms.group :label="__('shopper::forms.label.code')" for="code">
                    <x-shopper::forms.input
                        id="code"
                        type="text"
                        wire:model="code"
                        autofocus
                        autocomplete="one-time-code"
                    />
                </x-shopper::forms.group>
            @else
                <x-shopper::forms.group :label="__('shopper::forms.label.recovery_code')" for="recovery_code">
                    <x-shopper::forms.input
                        id="recovery_code"
                        type="text"
                        wire:model="recoveryCode"
                        autofocus
                        autocomplete="one-time-code"
                    />
                </x-shopper::forms.group>
            @endif

            <div class="mt-5 flex items-center space-x-4">
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/auth.two_factor.remember') }}

                    @if (! $useRecoveryCode)
                        <button
                            class="ml-1 cursor-pointer text-sm text-gray-500 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            type="button"
                            wire:click="$set('useRecoveryCode', true)"
                        >
                            {{ __('shopper::pages/auth.two_factor.use_recovery_code') }}
                        </button>
                    @else
                        <button
                            class="ml-1 cursor-pointer text-sm text-gray-500 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            type="button"
                            wire:click="$set('useRecoveryCode', false)"
                        >
                            {{ __('shopper::pages/auth.two_factor.use_authentication_code') }}
                        </button>
                    @endif
                </p>
                <x-filament::button type="submit">
                    <x-shopper::loader wire:loading wire:target="authenticate" class="text-white" aria-hidden="true" />
                    {{ __('shopper::pages/auth.two_factor.action') }}
                </x-filament::button>
            </div>

            <div class="mt-4">
                <button
                    type="button"
                    wire:click="resetChallenge"
                    class="text-primary-600 hover:text-primary-500 text-sm font-medium"
                >
                    &larr; {{ __('shopper::pages/auth.login.return_login') }}
                </button>
            </div>
        </form>
    @endif

    <x-shopper::layouts.footer />
</x-shopper::auth-card>
