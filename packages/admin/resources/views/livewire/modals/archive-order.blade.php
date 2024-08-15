<x-shopper::modal footerClasses="px-4 pb-5 sm:px-6 sm:flex sm:flex-row-reverse">
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
            >
                <x-untitledui-alert-triangle class="size-6 text-danger-600" stroke-width="1.5" aria-hidden="true" />
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-headline">
                    {{ __('shopper::pages/orders.modals.archived_number', ['number' => $order->number]) }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/orders.modals.archived_notice') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.danger wire:click="archived" type="button" class="sm:ml-3 sm:w-auto">
            <x-shopper::loader wire:loading wire:target="delete" class="text-white" />
            {{ __('shopper::forms.actions.confirm') }}
        </x-shopper::buttons.danger>
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button">
            {{ __('shopper::forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
