@php
    $discount = $getRecord();
@endphp

<div>
    @if ($discount->type === \Shopper\Core\Enum\DiscountType::Percentage->value)
        {{ $discount->value . '%' }}
    @else
        {{ shopper_money_format(amount: $discount->value * 100, currency: $discount->zone?->currency_code) }}
    @endif
</div>
