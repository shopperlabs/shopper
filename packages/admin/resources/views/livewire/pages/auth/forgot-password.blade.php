<x-shopper::auth-card>
    <div class="space-y-5">
        <header class="flex flex-col items-center justify-center py-3">
            <div class="flex items-center justify-center space-y-2 rounded-lg bg-white p-2 shadow ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700/80">
                <x-phosphor-key class="size-5" aria-hidden="true" />
            </div>
            <h1 class="font-heading mt-4 text-lg font-medium text-gray-950 dark:text-white">
                {{ __('shopper::pages/auth.email.title') }}
            </h1>
            <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/auth.email.message') }}
            </p>
        </header>

        @if (session()->has('success'))
            <div class="flex gap-0.5 ring-1 ring-success-200 bg-success-50 rounded-lg p-2 dark:ring-success-400/20 dark:bg-success-800/30">
                <div class="ps-2 py-2 pe-0">
                    <svg class="shrink-0 text-success-400 size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ps-2 flex-1">
                    <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2">
                        <div class="flex items-center gap-2 text-sm font-medium text-success-700 dark:text-success-500">
                            {{ session()->get('success') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="sendResetPasswordLink">
            {{ $this->form }}

            <div class="mt-5">
                <x-filament::button type="submit" class="w-full justify-center">
                    <x-shopper::loader wire:loading wire:target="sendResetPasswordLink" class="text-white" />
                    {{ __('shopper::pages/auth.email.action') }}
                </x-filament::button>
            </div>
            <p class="mt-6 text-center">
                <x-shopper::link
                    :href="route('shopper.login')"
                    class="text-primary-500 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-600 inline-flex items-center text-sm"
                >
                    <x-untitledui-arrow-narrow-left class="mr-1.5 size-5" aria-hidden="true" />
                    {{ __('shopper::pages/auth.email.return_to_login') }}
                </x-shopper::link>
            </p>
        </form>
    </div>
</x-shopper::auth-card>
