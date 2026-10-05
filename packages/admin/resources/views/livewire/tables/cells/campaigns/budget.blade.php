@php
    $campaign = $getRecord();
    $type = $campaign->budget_type;

    $spent = null;
    $cap = null;
    $label = null;
    $percent = 0;

    if ($type->hasSpendCap() && $campaign->budget_amount > 0) {
        $spent = shopper_money_format(amount: $campaign->spent_amount, currency: $campaign->currency_code);
        $cap = shopper_money_format(amount: $campaign->budget_amount, currency: $campaign->currency_code);
        $percent = min(100, (int) round(($campaign->spent_amount / $campaign->budget_amount) * 100));
        $label = $spent . ' / ' . $cap;
    } elseif ($type->hasCountCap() && $campaign->budget_count > 0) {
        $percent = min(100, (int) round(($campaign->used_count / $campaign->budget_count) * 100));
        $label = $campaign->used_count . ' / ' . $campaign->budget_count;
    }

    $fill = match (true) {
        $percent >= 90 => 'bg-danger-500',
        $percent >= 70 => 'bg-warning-500',
        default => 'bg-success-500',
    };
@endphp

<div class="min-w-40">
    @if ($label === null)
        <span class="text-sh-fg-muted text-sm">{{ __('shopper::pages/campaigns.no_budget') }}</span>
    @else
        <div class="space-y-1">
            <p class="text-sh-fg text-sm">{{ $label }}</p>
            <div class="bg-sh-card h-1.5 w-full overflow-hidden rounded-full">
                <div
                    role="progressbar"
                    aria-valuenow="{{ $percent }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-label="{{ $label }}"
                    class="{{ $fill }} h-full w-(--p) rounded-full"
                    style="--p: {{ $percent }}%"
                ></div>
            </div>
        </div>
    @endif
</div>
