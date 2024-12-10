@php
    $product = $getRecord();
@endphp

<div class="flex items-center">
    @if ($product->variants_count > 0)
        <x-shopper::stock-badge :stock="$product->variants_stock" />
        {{ __('in stock for :count variant(s)', ['count' => $product->variants_count]) }}
    @else
        <x-shopper::stock-badge :stock="$product->stock" />
        {{ __('in stock') }}
    @endif
</div>
