<x-shopper::layouts.base :title="__('shopper::pages/auth.email.title')">

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
                <x-shopper::brand class="mx-auto h-20 w-auto" />
                <h2 class="mt-10 text-3xl font-extrabold text-center leading-9 text-secondary-900 dark:text-white">
                    {{ __('shopper::pages/auth.email.title') }}
                </h2>
                <p class="mt-5 text-sm leading-5 text-center">
                    {{ __('shopper::pages/auth.email.message') }}
                </p>
            </div>

            <form class="mt-5" action="{{ route('shopper.password.email') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <x-shopper::forms.input
                        aria-label="{{ __('shopper::layout.forms.label.email') }}"
                        name="email"
                        type="email"
                        required
                        autofocus
                        placeholder="{{ __('shopper::layout.forms.label.email') }}"
                        value="{{ old('email') }}"
                    />
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-5">
                    <x-shopper::buttons.primary type="submit" class="w-full justify-center">
                        {{ __('shopper::pages/auth.email.action') }}
                    </x-shopper::buttons.primary>
                </div>
                <p class="mt-5 text-center text-sm">
                    <a href="{{ route('shopper.login-view') }}" class="inline-flex items-center text-secondary-500 hover:text-secondary-900 dark:text-secondary-500 dark:hover:text-white leading-5">
                        <x-heroicon-o-arrow-narrow-left class="w-5 h-5 mr-1.5"/>
                        {{ __('shopper::pages/auth.email.return_to_login') }}
                    </a>
                </p>
            </form>
        </div>
    </div>

</x-shopper::layouts.base>
