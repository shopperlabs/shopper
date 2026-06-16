@props([
    'campaign',
])

@php
    $type = $campaign->budget_type;

    $spendPercent = $type->hasSpendCap() && $campaign->budget_amount > 0
        ? min(100, (int) round($campaign->spent_amount / $campaign->budget_amount * 100))
        : null;

    $countPercent = $type->hasCountCap() && $campaign->budget_count > 0
        ? min(100, (int) round($campaign->used_count / $campaign->budget_count * 100))
        : null;

    $fill = fn (int $percent): string => match (true) {
        $percent >= 90 => 'bg-danger-500',
        $percent >= 70 => 'bg-warning-500',
        default => 'bg-success-500',
    };
@endphp

<x-shopper::card>
    <x-slot:title>
        <span class="text-sh-fg-secondary text-xs font-semibold uppercase tracking-wide">
            {{ __('shopper::pages/campaigns.budget_panel.title') }}
        </span>
    </x-slot:title>

    @if ($spendPercent === null && $countPercent === null)
        <p class="text-sh-fg-muted text-sm">
            {{ __('shopper::pages/campaigns.budget_panel.none') }}
        </p>
    @else
        <div class="space-y-4 text-sm">
            @if ($spendPercent !== null)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary">{{ __('shopper::pages/campaigns.budget_panel.spend') }}</span>
                        <span class="text-sh-fg">
                            {{ shopper_money_format(amount: $campaign->spent_amount, currency: $campaign->currency_code) }}
                            /
                            {{ shopper_money_format(amount: $campaign->budget_amount, currency: $campaign->currency_code) }}
                        </span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-sh-card">
                        <div
                            role="progressbar"
                            aria-valuenow="{{ $spendPercent }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            aria-label="{{ __('shopper::pages/campaigns.budget_panel.spend') }}"
                            class="h-full rounded-full w-(--p) {{ $fill($spendPercent) }}"
                            style="--p: {{ $spendPercent }}%"
                        ></div>
                    </div>
                </div>
            @endif

            @if ($countPercent !== null)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary">{{ __('shopper::pages/campaigns.budget_panel.count') }}</span>
                        <span class="text-sh-fg">{{ $campaign->used_count }} / {{ $campaign->budget_count }}</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-sh-card">
                        <div
                            role="progressbar"
                            aria-valuenow="{{ $countPercent }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            aria-label="{{ __('shopper::pages/campaigns.budget_panel.count') }}"
                            class="h-full rounded-full w-(--p) {{ $fill($countPercent) }}"
                            style="--p: {{ $countPercent }}%"
                        ></div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-shopper::card>
