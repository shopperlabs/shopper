<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-white/10"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
    form-action="save"
>
    <x-slot name="title">
        <span class="flex flex-col">
            {{ __('shopper::modals.permissions.new') }}
            <span class="text-base font-normal leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::modals.permissions.new_description') }}
            </span>
        </span>
    </x-slot>

    <x-slot name="content">
        {{ $this->form }}
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.primary type="submit" class="sm:ml-3 sm:w-auto">
            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
            {{ __('shopper::forms.actions.save') }}
        </x-shopper::buttons.primary>
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button" class="mt-3 sm:mt-0 sm:w-auto">
            {{ __('shopper::forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
