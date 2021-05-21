<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-200"
    contentClasses="relative"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Stock management for this variant') }}
    </x-slot>

    <x-slot name="content">
        <div class="h-96 overflow-y-scroll">
            <livewire:shopper-products.variant-stock :variant="$variant" />
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Close') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
