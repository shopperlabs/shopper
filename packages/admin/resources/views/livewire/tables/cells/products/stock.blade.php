@php
    $product = $getRecord();
@endphp

<div class="flex items-center">
    @if ($product->variants_count > 0)
        <x-shopper::stock-badge :stock="$product->variants_stock" />
        <span class="text-sm/6 text-gray-700 dark:text-gray-300">
            {{ __('in stock for :count variant(s)', ['count' => $product->variants_count]) }}
        </span>
    @else
        <x-shopper::stock-badge :stock="$product->stock" />
        <span class="text-sm/6 text-gray-700 dark:text-gray-300">
            {{ __('in stock') }}
        </span>
    @endif
</div>
