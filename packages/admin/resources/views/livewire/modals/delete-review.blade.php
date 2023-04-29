<x-shopper::modal footerClasses="px-4 pb-5 sm:px-6 sm:flex sm:flex-row-reverse">
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-heroicon-o-exclamation class="h-6 w-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white" id="modal-headline">
                    {{ __('shopper::pages/products.reviews.modal.title') }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/products.reviews.modal.description') }}
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
