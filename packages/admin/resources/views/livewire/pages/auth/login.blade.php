<x-shopper::auth-card>
    @if (! $challengedUserId)
        <header class="flex flex-col justify-center items-center py-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg ring-1 ring-gray-200 dark:ring-gray-700/80 shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-sign-in class="size-5" aria-hidden="true" />
            </div>
            <h1 class="mt-4 font-heading text-lg font-medium text-gray-950 dark:text-white">
                {{ __('shopper::pages/auth.login.title') }}
            </h1>
            <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
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
    @else
        <header class="flex flex-col justify-center items-center py-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg ring-1 ring-gray-200 dark:ring-gray-700/80 shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-shield-check class="size-5" aria-hidden="true" />
            </div>
            <h1 class="mt-4 font-heading text-lg font-medium text-gray-950 dark:text-white">
                {{ __('shopper::pages/auth.two_factor.subtitle') }}
            </h1>
            <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
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
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                    @if (! $useRecoveryCode)
                        <button
                            class="cursor-pointer text-sm text-gray-500 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            type="button"
                            wire:click="$set('useRecoveryCode', true)"
                        >
                            {{ __('shopper::pages/auth.two_factor.use_recovery_code') }}
                        </button>
                    @else
                        <button
                            class="cursor-pointer text-sm text-gray-500 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
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
