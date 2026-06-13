@php
    $product = $getRecord();
@endphp

<div class="flex items-center">
    @if ($product->variants_count > 0)
        <x-shopper::stock-badge :stock="$product->variants_stock" />
        <span class="text-sm/6 text-sh-fg-secondary">
            {{ __('shopper::words.in_stock_variants', ['count' => $product->variants_count]) }}
        </span>
    @else
        <x-shopper::stock-badge :stock="$product->stock" />
        <span class="text-sm/6 text-sh-fg-secondary">
            {{ __('shopper::words.in_stock') }}
        </span>
    @endif
</div>
