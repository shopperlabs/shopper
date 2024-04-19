<x-shopper::modal footerClasses="px-4 pb-5 sm:px-6 sm:flex sm:flex-row-reverse">
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
            >
                <x-untitledui-alert-triangle class="h-6 w-6 text-danger-600" />
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-headline">
                    {{ __('shopper::words.actions_label.delete', ['name' => $name]) }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::modals.inventories.confirm_delete_msg') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::buttons.danger wire:click="delete" type="button">
                <x-shopper::loader wire:loading wire:target="delete" class="text-white" />
                {{ __('shopper::layout.forms.actions.confirm') }}
            </x-shopper::buttons.danger>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.cancel') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>
</x-shopper::modal>
