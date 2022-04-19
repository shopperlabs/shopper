<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        {{ __('shopper::pages/collections.modal.title') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-2">
            <x-shopper::forms.search label="shopper::pages/collections.modal.search" placeholder="shopper::pages/collections.modal.search_placeholder" />
        </div>
        <div class="my-2 -mx-2 divide-y divide-secondary-200 h-80 overflow-auto dark:divide-secondary-700">
            @foreach($this->products as $product)
                <label for="product_{{ $product->id }}" class="flex items-center px-2 py-3 cursor-pointer hover:bg-secondary-50 focus:bg-secondary-50 dark:hover:bg-secondary-700 dark:focus:bg-secondary-700">
                    <span class="mr-4">
                        <x-shopper::forms.checkbox
                            id="product_{{ $product->id }}"
                            wire:model.debounce.250ms="selectedProducts"
                            aria-label="{{ __('shopper::messages.product') }}"
                            value="{{ $product->id }}"
                        />
                    </span>
                    <span class="flex flex-1 items-center justify-between">
                        <span class="block font-medium text-sm text-secondary-700 dark:text-secondary-300">{{ $product->name }}</span>
                        <span class="flex items-center space-x-2">
                            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/collections.modal.stock', ['stock' => $product->stock]) }}
                            </span>
                            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $product->formattedPrice }}</span>
                        </span>
                    </span>
                </label>
            @endforeach
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::buttons.primary wire:click="addSelectedProducts" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="addSelectedProducts" class="text-white" />
                {{ __('shopper::pages/collections.modal.action') }}
            </x-shopper::buttons.primary>
            </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.cancel') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>

</x-shopper::modal>
