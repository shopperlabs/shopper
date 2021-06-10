<x-shopper-modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-gray-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Add Products to collection') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-2">
            <x-shopper-input.search label="Search product" placeholder="Search product by name" />
        </div>
        <div class="my-2 -mx-2 divide-y divide-gray-200 h-80 overflow-auto dark:divide-gray-700">
            @foreach($this->products as $product)
                <label for="product_{{ $product->id }}" class="flex items-center px-2 py-3 cursor-pointer hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                    <span class="mr-4">
                        <x-shopper-input.checkbox id="product_{{ $product->id }}" aria-label="{{ __('Product') }}" wire:model.debounce.250ms="selectedProducts" value="{{ $product->id }}" />
                    </span>
                    <span class="flex flex-1 items-center justify-between">
                        <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                        <span class="flex items-center space-x-2">
                            <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __(':stock available', ['stock' => $product->stock]) }}</span>
                            <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $product->formattedPrice }}</span>
                        </span>
                    </span>
                </label>
            @endforeach
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="addSelectedProducts" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="addSelectedProducts" class="text-white" />
                {{ __('Add Selected Products') }}
            </x-shopper-button>
            </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Cancel') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
