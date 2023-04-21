@props(['product'])

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
        <span class="block font-medium text-sm text-secondary-700 dark:text-secondary-300">
            {{ $product->name }}
        </span>
        <span class="flex items-center space-x-2">
            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/collections.modal.stock', ['stock' => $product->stock]) }}
            </span>
            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                {{ $product->formattedPrice }}
            </span>
        </span>
    </span>
</label>
