@props([
    'product',
])

<label
    for="product_{{ $product->id }}"
    class="flex cursor-pointer items-center p-3 hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-gray-700"
>
    <span class="mr-4">
        <x-shopper::forms.checkbox
            id="product_{{ $product->id }}"
            wire:model.live.debounce.250ms="selectedProducts"
            aria-label="{{ __('shopper::words.product') }}"
            value="{{ $product->id }}"
        />
    </span>
    <span class="flex flex-1 items-center justify-between truncate">
        <span class="block text-sm font-medium text-gray-700 truncate dark:text-gray-300">
            {{ $product->name }}
        </span>
        <span class="flex items-center space-x-2">
            <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/collections.modal.stock', ['stock' => $product->stock]) }}
            </span>
        </span>
    </span>
</label>
