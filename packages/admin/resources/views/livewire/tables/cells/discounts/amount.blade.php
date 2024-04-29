@php
    $discount = $getRecord();
@endphp

<div>
    @if ($discount->type === \Shopper\Core\Enum\DiscountType::Percentage->value)
        {{ $discount->value . '%' }}
    @else
        {{ shopper_money_format($discount->value * 100) }}
    @endif
</div>
