<x-shopper::auth-card>
    @if (! $challengedUserId)
        <header class="flex flex-col justify-center items-center py-3">
            <div class="bg-sh-surface rounded-lg ring-1 ring-sh-border shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-sign-in class="size-5" aria-hidden="true" />
            </div>
            <h1 class="mt-4 font-heading text-lg font-medium text-sh-fg">
                {{ __('shopper::pages/auth.login.title') }}
            </h1>
            <p class="mt-1 text-center text-sm text-sh-fg-muted">
                {{ __('shopper::pages/auth.login.subtitle') }}
            </p>
        </header>

        <form wire:submit="authenticate" class="mt-8 space-y-10">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full justify-center" wire:loading.attr="disabled">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3" wire:target="authenticate">
                    <x-untitledui-lock-04
                        class="text-white/10 group-hover:text-white/20 size-5"
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
        </form>

        @if (config('shopper.auth.passkeys_enabled'))
            <div
                x-data="passkeyLogin({
                    optionsUrl: '{{ route('shopper.passkeys.login-options') }}',
                    loginUrl: '{{ route('shopper.passkeys.login') }}',
                })"
                x-cloak
                x-show="supported"
                class="mt-6"
            >
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-sh-border"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-sh-surface px-2 text-sh-fg-muted">
                            {{ __('shopper::pages/auth.login.or') }}
                        </span>
                    </div>
                </div>

                <x-filament::button
                    x-on:click="login"
                    x-bind:disabled="processing"
                    type="button"
                    color="gray"
                    class="mt-6 w-full justify-center"
                >
                    <x-heroicon-o-finger-print class="size-5" aria-hidden="true" />
                    {{ __('shopper::pages/auth.login.passkey_action') }}
                </x-filament::button>

                <p
                    x-cloak
                    x-show="error"
                    x-text="error"
                    class="text-danger-600 dark:text-danger-400 mt-3 text-center text-sm"
                    role="alert"
                ></p>
            </div>
        @endif
    @else
        <header class="flex flex-col justify-center items-center py-3">
            <div class="bg-sh-surface rounded-lg ring-1 ring-sh-border shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-shield-check class="size-5" aria-hidden="true" />
            </div>
            <h1 class="mt-4 font-heading text-lg font-medium text-sh-fg">
                {{ __('shopper::pages/auth.two_factor.subtitle') }}
            </h1>
            <p class="mt-1 text-center text-sm text-sh-fg-muted">
                {{
                    $useRecoveryCode
                        ? __('shopper::pages/auth.two_factor.recovery_code')
                        : __('shopper::pages/auth.two_factor.authentication_code')
                }}
            </p>
        </header>

        <form wire:submit="authenticate" class="mt-8 space-y-5">
            {{ $this->twoFactorForm }}

            <div class="flex items-center justify-between">
                <p class="text-sm leading-5 text-sh-fg-muted">
                    @if (! $useRecoveryCode)
                        <button
                            class="cursor-pointer text-sm text-sh-fg-muted underline hover:text-sh-fg"
                            type="button"
                            wire:click="$set('useRecoveryCode', true)"
                        >
                            {{ __('shopper::pages/auth.two_factor.use_recovery_code') }}
                        </button>
                    @else
                        <button
                            class="cursor-pointer text-sm text-sh-fg-muted underline hover:text-sh-fg"
                            type="button"
                            wire:click="$set('useRecoveryCode', false)"
                        >
                            {{ __('shopper::pages/auth.two_factor.use_authentication_code') }}
                        </button>
                    @endif
                </p>
                <button
                    type="button"
                    wire:click="resetChallenge"
                    class="text-primary-600 hover:text-primary-500 text-sm font-medium"
                >
                    &larr; {{ __('shopper::pages/auth.login.return_login') }}
                </button>
            </div>

            <x-filament::button type="submit" class="w-full justify-center">
                <x-shopper::loader wire:loading wire:target="authenticate" class="text-white" aria-hidden="true" />
                {{ __('shopper::pages/auth.two_factor.action') }}
            </x-filament::button>
        </form>
    @endif
</x-shopper::auth-card>
