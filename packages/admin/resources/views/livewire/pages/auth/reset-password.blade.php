<x-shopper::auth-card>
    <div class="space-y-4">
        <x-shopper::validation-errors />

        <div>
            <x-shopper::brand class="mx-auto h-12 w-auto" />

            <h2 class="mt-6 text-3xl font-bold font-heading text-center leading-9 text-secondary-900 dark:text-white">
                {{ __('shopper::pages/auth.reset.title') }}
            </h2>
            <p class="mt-3 text-sm leading-5 text-center">
                {{ __('shopper::pages/auth.reset.message') }}
            </p>
        </div>
    </div>
    <form class="mt-6" wire:submit.prevent="resetPassword">
        <input wire:model="token" type="hidden">

        <div class="rounded-md shadow-sm">
            <div>
                <input
                    aria-label="{{ __('shopper::layout.forms.label.email') }}"
                    value="{{ $email ?? old('email') }}"
                    name="email"
                    type="email"
                    wire:model="email"
                    autocomplete="off"
                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                    placeholder="{{ __('shopper::layout.forms.label.email') }}"
                    required
                    autofocus
                />
            </div>
            <div class="-mt-px">
                <input
                    aria-label="{{ __('shopper::layout.forms.label.password') }}"
                    name="password"
                    type="password"
                    wire:model="password"
                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                    placeholder="{{ __('shopper::layout.forms.label.new_password') }}"
                    required
                />
            </div>
        </div>

        <div class="mt-5">
            <x-shopper::buttons.primary type="submit" class="w-full justify-center">
                <x-shopper::loader wire:loading wire:target="resetPassword" class="text-white" />
                {{ __('shopper::pages/auth.reset.action') }}
            </x-shopper::buttons.primary>
        </div>
    </form>
</x-shopper::auth-card>
