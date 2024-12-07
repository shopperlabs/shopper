@php
    $discount = $getRecord();
@endphp

<div>
    @if ($discount->type === \Shopper\Core\Enum\DiscountType::Percentage)
        {{ $discount->value . '%' }}
    @else
        {{ shopper_money_format(amount: $discount->value, currency: $discount->zone?->currency_code) }}
    @endif
</div>
