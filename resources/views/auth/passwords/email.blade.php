<x-shopper::layouts.base :title="__('Reset Password')">

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            @if(session()->has('success'))
                <div class="rounded-md bg-green-100 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm leading-5 text-green-700">
                                {{ session()->get('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <x-shopper::brand />

                <h2 class="mt-10 text-3xl font-extrabold text-center leading-9 text-secondary-900 dark:text-white">{{ __('Reset your password') }}</h2>
                <p class="mt-5 text-sm leading-5 text-center">
                    {{ __('Enter the email address you used when creating your account and we will send you instructions to reset your password.') }}
                </p>
            </div>

            <form class="mt-5" action="{{ route('shopper.password.email') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <x-shopper::forms.input
                        aria-label="{{ __('Email address') }}"
                        name="email"
                        type="email"
                        required
                        autofocus
                        placeholder="{{ __('Email address') }}"
                        value="{{ old('email') }}"
                    />
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-5">
                    <x-shopper::buttons.primary type="submit" class="w-full justify-center">
                        {{ __('Send password reset mail') }}
                    </x-shopper::buttons.primary>
                </div>
                <p class="mt-5 text-center text-sm">
                    <a href="{{ route('shopper.login-view') }}" class="inline-flex items-center text-secondary-500 hover:text-secondary-900 dark:text-secondary-500 dark:hover:text-white leading-5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="arrow-narrow-left w-5 h-5 mr-1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                        {{ __('Return to login page') }}
                    </a>
                </p>
            </form>
        </div>
    </div>

</x-shopper::layouts.base>
