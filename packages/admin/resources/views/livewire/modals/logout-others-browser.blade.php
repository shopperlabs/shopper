<x-shopper::modal
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="content">
        <div class="px-4 pt-4 sm:flex sm:items-start sm:px-6">
            <div class="text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::words.logout_session') }}
                </h3>
                <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ __('shopper::words.logout_session_confirm') }}
                </p>
            </div>
        </div>
        <div class="p-4 sm:px-6">
            <div>
                <div class="relative">
                    <x-shopper::forms.input
                        wire:model.lazy="password"
                        aria-label="{{ __('shopper::forms.label.password') }}"
                        type="password"
                        placeholder="{{ __('Enter your password') }}"
                    />
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-danger-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::buttons.danger
                wire:click="logoutOtherBrowserSessions"
                wire:loading.attr="disabled"
                type="button"
            >
                <x-shopper::loader wire:loading wire:target="logoutOtherBrowserSessions" class="text-white" />
                {{ __('shopper::forms.actions.logout_session') }}
            </x-shopper::buttons.danger>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" wire:loading.attr="disabled" type="button">
                {{ __('shopper::forms.actions.nevermind') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>
</x-shopper::modal>
