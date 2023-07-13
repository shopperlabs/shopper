<x-shopper::auth-card>
    <div class="space-y-4">
        <x-shopper::validation-errors />

        <div>
            <x-shopper::brand class="w-auto h-12 mx-auto" />

            <h2 class="mt-6 text-3xl font-bold leading-9 text-center text-secondary-900 dark:text-white font-display">
                {{ __('shopper::pages/auth.login.title') }}
            </h2>
            <p class="max-w-sm mt-3 text-sm text-center text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/auth.login.or') }}
                <a href="{{ url('/') }}" class="font-medium transition duration-150 ease-in-out text-primary-600 hover:text-primary-500 focus:outline-none focus:underline">
                    {{ __('shopper::pages/auth.login.return_landing') }}
                </a>
            </p>
        </div>
    </div>

    <form class="mt-6" wire:submit.prevent="authenticate">
        <div class="rounded-md shadow-sm">
            <div>
                <input
                    aria-label="{{ __('shopper::layout.forms.label.email') }}"
                    name="email"
                    type="email"
                    wire:model.defer="email"
                    autocomplete="off"
                    class="relative block w-full px-3 py-2 border rounded-none appearance-none border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                    placeholder="{{ __('shopper::layout.forms.label.email') }}"
                    required
                />
            </div>
            <div class="-mt-px">
                <input
                    aria-label="{{ __('shopper::layout.forms.label.password') }}"
                    name="password"
                    type="password"
                    wire:model.defer="password"
                    class="relative block w-full px-3 py-2 border rounded-none appearance-none border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                    placeholder="{{ __('shopper::layout.forms.label.password') }}"
                    required
                />
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="flex items-center">
                <input id="remember" name="remember" wire:model.defer="remember" type="checkbox" class="w-4 h-4 rounded text-primary-600 dark:bg-secondary-800 dark:border-secondary-700 focus:ring-primary-500 border-secondary-300 dark:focus:ring-offset-secondary-900">
                <label for="remember" class="block ml-2 text-sm leading-5 cursor-pointer text-secondary-500 hover:text-secondary-900 dark:text-white dark:hover:text-secondary-300">
                    {{ __('shopper::layout.forms.label.remember') }}
                </label>
            </div>

            <div class="text-sm leading-5">
                <a href="{{ route('shopper.password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('shopper::pages/auth.login.forgot_password') }}
                </a>
            </div>
        </div>

        <div class="mt-6">
            <x-shopper::buttons.primary type="submit" class="relative justify-center w-full group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3" wire:loading.delay.remove wire:target="authenticate">
                        <x-heroicon-s-lock-closed class="w-5 h-5 text-primary-500 group-hover:text-primary-400"/>
                    </span>
                <x-shopper::loader wire:loading wire:target="authenticate" class="text-white" />
                {{ __('shopper::pages/auth.login.action') }}
            </x-shopper::buttons.primary>
        </div>
    </form>

    <x-shopper::layouts.footer />
</x-shopper::auth-card>
