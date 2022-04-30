<x-shopper::layouts.base :title="__('shopper::pages/auth.login.title')">

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            <div class="space-y-4">
                <x-shopper::validation-errors />

                <div>
                    <x-shopper::brand class="mx-auto h-20 w-auto" />

                    <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/auth.login.title') }}
                    </h2>
                    <p class="mt-2 text-center text-sm max-w">
                        {{ __('shopper::pages/auth.login.or') }}
                        <a href="{{ url('/') }}" class="font-medium text-primary-600 hover:text-primary-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            {{ __('shopper::pages/auth.login.return_landing') }}
                        </a>
                    </p>
                </div>
            </div>

            <form class="mt-8" action="{{ route('shopper.login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <div>
                        <input
                            aria-label="{{ __('shopper::layout.forms.label.email') }}"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="off"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                            placeholder="{{ __('shopper::layout.forms.label.email') }}"
                            required
                        />
                    </div>
                    <div class="-mt-px">
                        <input
                            aria-label="{{ __('shopper::layout.forms.label.password') }}"
                            name="password"
                            type="password"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm"
                            placeholder="{{ __('shopper::layout.forms.label.password') }}"
                            required
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary-600 dark:bg-secondary-800 dark:border-secondary-700 focus:ring-primary-500 border-secondary-300 dark:focus:ring-offset-secondary-900 rounded">
                        <label for="remember_me" class="ml-2 block text-sm leading-5 cursor-pointer text-secondary-500 hover:text-secondary-900 dark:text-white dark:hover:text-secondary-300">
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
                    <x-shopper::buttons.primary type="submit" class="group relative w-full justify-center">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <x-heroicon-s-lock-closed class="h-5 w-5 text-primary-500 group-hover:text-primary-400"/>
                        </span>
                        {{ __('shopper::pages/auth.login.action') }}
                    </x-shopper::buttons.primary>
                </div>
            </form>

            <x-shopper::layouts.footer />
        </div>
    </div>

</x-shopper::layouts.base>
