<x-shopper::auth-card>
    <div class="space-y-4">
        <x-shopper::validation-errors />

        <div>
            <x-shopper::brand class="mx-auto size-12" />

            <h2 class="mt-6 text-center font-heading text-3xl font-bold leading-9 text-gray-900 dark:text-white">
                {{ __('shopper::pages/auth.login.title') }}
            </h2>
            <p class="mt-3 max-w-sm text-center text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/auth.login.or') }}
                <a
                    href="{{ url('/') }}"
                    class="font-medium text-primary-600 transition duration-150 ease-in-out hover:text-primary-500"
                >
                    {{ __('shopper::pages/auth.login.return_landing') }}
                </a>
            </p>
        </div>
    </div>

    <form class="mt-6" wire:submit="authenticate">
        <div class="rounded-md">
            <div>
                <input
                    aria-label="{{ __('shopper::forms.label.email') }}"
                    name="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email address"
                    class="relative block w-full rounded-t-lg border-0 px-3 py-1.5 text-gray-900 placeholder-gray-400 ring-1 ring-inset ring-gray-300 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-gray-300 dark:ring-white/10 dark:focus:ring-primary-500 dark:focus:ring-offset-gray-900 sm:text-sm"
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
                    class="relative block w-full rounded-b-lg border-0 px-3 py-1.5 text-gray-900 placeholder-gray-400 ring-1 ring-inset ring-gray-300 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-gray-300 dark:ring-white/10 dark:focus:ring-primary-500 dark:focus:ring-offset-gray-900 sm:text-sm"
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
                    class="size-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-white/10 dark:bg-gray-800 dark:focus:ring-offset-gray-900"
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
                    class="font-medium text-primary-600 hover:text-primary-500"
                >
                    {{ __('shopper::pages/auth.login.forgot_password') }}
                </x-shopper::link>
            </div>
        </div>

        <div class="mt-6">
            <x-shopper::buttons.primary type="submit" class="group relative w-full justify-center">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3" wire:target="authenticate">
                    <x-untitledui-lock-04
                        class="size-5 text-primary-500 group-hover:text-primary-400"
                        aria-hidden="true"
                        wire:loading.remove
                    />
                    <x-shopper::loader wire:loading wire:target="authenticate" class="text-white" aria-hidden="true" />
                </span>
                {{ __('shopper::pages/auth.login.action') }}
            </x-shopper::buttons.primary>
        </div>
    </form>

    <x-shopper::layouts.footer />
</x-shopper::auth-card>
