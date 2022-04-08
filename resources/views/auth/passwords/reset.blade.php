<x-shopper::layouts.base :title="__('Reset Password')">

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            <div class="space-y-4">
                <x-shopper::validation-errors />

                <div>
                    <x-shopper::brand />

                    <h2 class="mt-10 text-3xl font-extrabold font-heading text-center leading-9 text-secondary-900 dark:text-white">{{ __('Reset your password') }}</h2>
                    <p class="mt-5 text-sm leading-5 text-center">
                        {{ __("Enter your email and the new password you'd like to use to access your account.") }}
                    </p>
                </div>
            </div>
            <form class="mt-5" action="{{ route('shopper.password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="rounded-md shadow-sm">
                    <div>
                        <input aria-label="{{ __('Email address') }}" value="{{ $email ?? old('email') }}" name="email" type="email" autocomplete="off" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm" placeholder="{{ __('Email address') }}">
                    </div>
                    <div class="-mt-px">
                        <input aria-label="{{ __('Password') }}" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 dark:bg-secondary-800 placeholder-secondary-500 text-secondary-900 dark:text-secondary-300 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-secondary-900 focus:z-10 sm:text-sm" placeholder="{{ __('New password') }}">
                    </div>
                </div>

                <div class="mt-5">
                    <x-shopper::button type="submit" class="w-full justify-center">
                        {{ __('Update password') }}
                    </x-shopper::button>
                </div>
            </form>
        </div>
    </div>

</x-shopper::layouts.base>
