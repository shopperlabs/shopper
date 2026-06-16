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
        $percent = min(100, (int) round($campaign->spent_amount / $campaign->budget_amount * 100));
        $label = $spent . ' / ' . $cap;
    } elseif ($type->hasCountCap() && $campaign->budget_count > 0) {
        $percent = min(100, (int) round($campaign->used_count / $campaign->budget_count * 100));
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
        <span class="text-sm text-sh-fg-muted">{{ __('shopper::pages/campaigns.no_budget') }}</span>
    @else
        <div class="space-y-1">
            <p class="text-sm text-sh-fg">{{ $label }}</p>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-sh-card">
                <div
                    role="progressbar"
                    aria-valuenow="{{ $percent }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-label="{{ $label }}"
                    class="h-full rounded-full w-(--p) {{ $fill }}"
                    style="--p: {{ $percent }}%"
                ></div>
            </div>
        </div>
    @endif
</div>
