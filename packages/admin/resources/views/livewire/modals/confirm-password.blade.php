<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        {{ __('shopper::layout.forms.label.confirm_password') }}
    </x-slot>

    <x-slot name="content">
        <p class="text-sm text-secondary-500 dark:text-secondary-400">
            {{ __('shopper::components.modal.content') }}
        </p>
        <div class="mt-4">
            <x-shopper::forms.input
                wire:model.defer="confirmablePassword"
                id="confirmable_password"
                type="password"
                placeholder="{{ __('shopper::layout.forms.placeholder.password') }}"
                aria-label="{{ __('shopper::layout.forms.label.password') }}"
            />

            @error('confirmable_password')
                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::buttons.primary wire:click="confirmPassword" type="button">
                <x-shopper::loader wire:loading wire:target="confirmPassword" class="text-white" />
                {{ __('shopper::layout.forms.label.confirm') }}
            </x-shopper::buttons.primary>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.nevermind') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>
</x-shopper::modal>
