@props([
    'summary',
    'discount' => null,
])

@php
    $hasContent = $summary['code']
        || $summary['type_label']
        || $summary['zone_name']
        || $summary['apply_to']
        || $summary['eligibility']
        || $summary['minimum_label']
        || $summary['start_at'];

    $rows = [];

    if ($summary['type_label']) {
        $rows[] = ['label' => __('shopper::pages/discounts.summary.rows.type'), 'value' => $summary['type_label']];
    }

    if ($summary['code']) {
        $rows[] = [
            'label' => __('shopper::pages/discounts.summary.rows.code'),
            'value' => 'badge:' . $summary['code'],
        ];
    }

    if ($summary['zone_name']) {
        $rows[] = ['label' => __('shopper::pages/discounts.summary.rows.zone'), 'value' => $summary['zone_name']];
    }

    if ($summary['apply_to']) {
        $applyText = $summary['apply_to']->getLabel();

        if (
            $summary['apply_to'] === \Shopper\Core\Enum\DiscountApplyTo::Products
            && $summary['products_count'] > 0
        ) {
            $applyText .= ' (' . $summary['products_count'] . ')';
        }

        $rows[] = ['label' => __('shopper::pages/discounts.summary.rows.applies'), 'value' => $applyText];
    }

    if ($summary['eligibility']) {
        $forText = $summary['eligibility']->getLabel();

        if (
            $summary['eligibility'] === \Shopper\Core\Enum\DiscountEligibility::Customers
            && $summary['customers_count'] > 0
        ) {
            $forText .= ' (' . $summary['customers_count'] . ')';
        }

        $rows[] = ['label' => __('shopper::pages/discounts.summary.rows.for'), 'value' => $forText];
    }

    if ($summary['minimum_label']) {
        $rows[] = ['label' => __('shopper::pages/discounts.summary.rows.minimum'), 'value' => $summary['minimum_label']];
    }

    if ($summary['usage_limit']) {
        $rows[] = [
            'label' => __('shopper::pages/discounts.summary.rows.usage'),
            'value' => trans_choice('shopper::pages/discounts.summary.rows.usage_value', $summary['usage_limit'], ['count' => $summary['usage_limit']]),
        ];
    }

    if ($summary['start_at']) {
        $start = \Illuminate\Support\Carbon::parse($summary['start_at'])->translatedFormat('Y-m-d');
        $end = $summary['end_at']
            ? \Illuminate\Support\Carbon::parse($summary['end_at'])->translatedFormat('Y-m-d')
            : null;
        $rows[] = [
            'label' => __('shopper::pages/discounts.summary.rows.active'),
            'value' => $end ? $start . ' → ' . $end : $start,
        ];
    }
@endphp

<x-shopper::card>
    <x-slot:title>
        <span class="text-sh-fg-secondary text-xs font-semibold uppercase tracking-wide">
            {{ __('shopper::pages/discounts.summary.title') }}
        </span>
    </x-slot:title>

    @if (! $hasContent && ! $discount?->exists)
        <p class="text-sh-fg-muted text-sm">
            {{ __('shopper::pages/discounts.summary.empty') }}
        </p>
    @else
        <dl class="space-y-3 text-sm">
            @foreach ($rows as $row)
                <div class="flex items-center gap-3">
                    <dt class="text-sh-fg-secondary w-24 shrink-0">{{ $row['label'] }}</dt>
                    <dd class="text-sh-fg min-w-0 flex-1 wrap-break-word">
                        @if (str_starts_with($row['value'], 'badge:'))
                            <x-filament::badge color="gray">
                                {{ \Illuminate\Support\Str::after($row['value'], 'badge:') }}
                            </x-filament::badge>
                        @else
                            {{ $row['value'] }}
                        @endif
                    </dd>
                </div>
            @endforeach

            <div class="flex items-center gap-3">
                <dt class="text-sh-fg-secondary w-24 shrink-0">
                    {{ __('shopper::pages/discounts.summary.rows.visibility') }}
                </dt>
                <dd class="min-w-0 flex-1">
                    @if ($discount?->exists)
                        <x-filament::badge :color="$discount->status->getColor()" :icon="$discount->status->getIcon()">
                            {{ $discount->status->getLabel() }}
                        </x-filament::badge>
                    @else
                        <x-filament::badge :color="$summary['is_active'] ? 'success' : 'gray'">
                            {{ $summary['is_active']
                                ? __('shopper::pages/discounts.summary.visibility_public')
                                : __('shopper::pages/discounts.summary.visibility_hidden') }}
                        </x-filament::badge>
                    @endif
                </dd>
            </div>
        </dl>
    @endif
</x-shopper::card>
