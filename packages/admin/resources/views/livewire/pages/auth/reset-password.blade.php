<x-shopper::auth-card>
    <div class="space-y-5">
        <header class="flex flex-col items-center justify-center py-3">
            <div class="flex items-center justify-center space-y-2 rounded-lg bg-white p-2 shadow ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700/80">
                <x-phosphor-lock-key class="size-5" aria-hidden="true" />
            </div>
            <h1 class="font-heading mt-4 text-lg font-medium text-gray-950 dark:text-white">
                {{ __('shopper::pages/auth.reset.title') }}
            </h1>
            <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/auth.reset.message') }}
            </p>
        </header>

        <form wire:submit="resetPassword">
            {{ $this->form }}

            <div class="mt-5">
                <x-filament::button type="submit" class="w-full justify-center">
                    <x-shopper::loader wire:loading wire:target="resetPassword" class="text-white" />
                    {{ __('shopper::pages/auth.reset.action') }}
                </x-filament::button>
            </div>
        </form>
    </div>
</x-shopper::auth-card>
