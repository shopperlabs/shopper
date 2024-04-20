@php
    $discount = $getRecord();
@endphp

<div>
    @if ($discount->type === \Shopper\Core\Enum\DiscountType::PERCENTAGE->value)
        {{ $discount->value . '%' }}
    @else
        {{ shopper_money_format($discount->value * 100) }}
    @endif
</div>
