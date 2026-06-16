@props([
    'campaign',
])

@php
    use Shopper\Core\Enum\DiscountType;
@endphp

<x-shopper::card>
    <x-slot:title>
        <span class="text-sh-fg-secondary text-xs font-semibold uppercase tracking-wide">
            {{ __('shopper::pages/campaigns.promotions_panel.title') }}
        </span>
    </x-slot:title>

    @if ($campaign->discounts->isEmpty())
        <p class="text-sh-fg-muted text-sm">
            {{ __('shopper::pages/campaigns.promotions_panel.empty') }}
        </p>
    @else
        <ul role="list" class="divide-y divide-sh-border text-sm">
            @foreach ($campaign->discounts as $discount)
                <li wire:key="campaign-discount-{{ $discount->id }}" class="flex items-center justify-between gap-3 py-2 first:pt-0 last:pb-0">
                    <x-filament::badge color="gray">{{ $discount->code ?? __('shopper::pages/discounts.method_automatic') }}</x-filament::badge>
                    <span class="text-sh-fg-secondary">
                        @if ($discount->type === DiscountType::Percentage)
                            {{ $discount->value . '%' }}
                        @else
                            {{ shopper_money_format(amount: $discount->value, currency: $campaign->currency_code) }}
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</x-shopper::card>
