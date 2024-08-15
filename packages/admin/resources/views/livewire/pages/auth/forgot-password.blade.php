<x-shopper::auth-card>
    @if (session()->has('success'))
        <div class="rounded-lg bg-green-100 p-4">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="size-5 text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3 flex-1 lg:flex lg:justify-between">
                    <p class="text-sm leading-5 text-green-700">
                        {{ session()->get('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-4">
        <x-shopper::brand class="mx-auto h-12 w-auto" />
        <h2 class="mt-6 text-center font-heading text-3xl font-bold leading-9 text-gray-900 dark:text-white">
            {{ __('shopper::pages/auth.email.title') }}
        </h2>
        <p class="mt-3 text-center text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/auth.email.message') }}
        </p>
    </div>

    <form class="mt-6" wire:submit="sendResetPasswordLink">
        <div class="rounded-md">
            <x-shopper::forms.input
                aria-label="{{ __('shopper::forms.label.email') }}"
                name="email"
                type="email"
                wire:model="email"
                placeholder="{{ __('shopper::forms.label.email') }}"
                required
                autofocus
            />
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-5">
            <x-shopper::buttons.primary type="submit" class="w-full justify-center">
                <x-shopper::loader wire:loading wire:target="sendResetPasswordLink" class="text-white" />
                {{ __('shopper::pages/auth.email.action') }}
            </x-shopper::buttons.primary>
        </div>
        <p class="mt-5 text-center text-sm">
            <x-shopper::link
                href="{{ route('shopper.login') }}"
                class="inline-flex items-center leading-5 text-gray-500 hover:text-gray-900 dark:text-gray-500 dark:hover:text-white"
            >
                <x-untitledui-arrow-narrow-left class="mr-1.5 size-5" />
                {{ __('shopper::pages/auth.email.return_to_login') }}
            </x-shopper::link>
        </p>
    </form>
</x-shopper::auth-card>
