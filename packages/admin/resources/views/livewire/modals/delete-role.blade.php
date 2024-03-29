<x-shopper::modal footerClasses="px-4 pb-5 sm:px-6 sm:flex sm:flex-row-reverse">
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-untitledui-alert-triangle class="h-6 w-6 text-danger-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                    {{ __('shopper::layout.forms.actions.delete') }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500">
                        {{ __('shopper::modals.roles.confirm_delete_msg') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.danger wire:click="delete" type="button" class="sm:ml-3 sm:w-auto">
            <x-shopper::loader wire:loading wire:target="delete" class="text-white" />
            {{ __('shopper::layout.forms.actions.confirm') }}
        </x-shopper::buttons.danger>
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button" class="mt-3 sm:mt-0 sm:w-auto">
            {{ __('shopper::layout.forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
