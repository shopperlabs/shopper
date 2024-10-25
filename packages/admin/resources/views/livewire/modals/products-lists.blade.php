<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-200 dark:border-gray-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        {{ __('shopper::pages/collections.modal.title') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-2">
            <x-shopper::forms.search
                :label="__('shopper::pages/collections.modal.search')"
                :placeholder="__('shopper::pages/collections.modal.search_placeholder')"
                wire:model.live.debounce.550ms="search"
            />
        </div>
        <div class="h-80 -mx-2 my-2 divide-y divide-gray-200 overflow-auto dark:divide-gray-700">
            @foreach ($this->products as $product)
                <x-shopper::forms.label-product :product="$product" wire:key="{{ $product->id }}" />
            @endforeach
        </div>
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.primary
            wire:click="addSelectedProducts"
            wire.loading.attr="disabled"
            :disabled="count($selectedProducts) <= 0"
            type="button"
            class="w-full sm:ml-3 sm:w-auto"
        >
            <x-shopper::loader wire:loading wire:target="addSelectedProducts" class="text-white" />
            {{ __('shopper::pages/collections.modal.action') }}
        </x-shopper::buttons.primary>
        <x-shopper::buttons.default
            wire:click="$dispatch('closeModal')"
            type="button"
            class="mt-3 w-full sm:mt-0 sm:w-auto"
        >
            {{ __('shopper::forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
