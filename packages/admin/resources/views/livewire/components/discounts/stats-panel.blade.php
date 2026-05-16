@php
    $currency = $discount->zone?->currency_code ?? shopper_currency();
    $hasUsage = $stats['usage'] > 0;
    $usagePercent = $stats['usage_limit'] !== null && $stats['usage_limit'] > 0
        ? min(100, (int) round($stats['usage'] / $stats['usage_limit'] * 100))
        : null;
@endphp

<x-shopper::card class="min-w-0">
    <x-slot:title>
        <div class="flex items-center justify-between">
            <span class="text-sh-fg-secondary text-sm font-medium">
                {{ __('shopper::pages/discounts.stats.title') }}
            </span>
            <x-phosphor-chart-line-up-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
        </div>
    </x-slot:title>

    <dl class="text-sh-fg grid grid-cols-2 gap-4 text-sm">
        <div class="min-w-0">
            <dt class="text-sh-fg-secondary truncate text-xs font-medium uppercase tracking-wide">
                {{ __('shopper::pages/discounts.stats.usage') }}
            </dt>
            <dd class="font-heading text-sh-fg mt-1 truncate text-xl font-bold">
                {{ $stats['usage'] }}
                @if ($stats['usage_limit'] !== null)
                    <span class="text-sh-fg-secondary text-base font-normal">
                        / {{ $stats['usage_limit'] }}
                    </span>
                @endif
            </dd>
            @if ($usagePercent !== null)
                <div class="bg-sh-surface-muted mt-2 h-1.5 w-full overflow-hidden rounded-full">
                    <div
                        class="h-full bg-emerald-500 transition-all"
                        style="width: {{ $usagePercent }}%"
                    ></div>
                </div>
            @endif
        </div>

        <div class="min-w-0">
            <dt class="text-sh-fg-secondary truncate text-xs font-medium uppercase tracking-wide">
                {{ __('shopper::pages/discounts.stats.orders') }}
            </dt>
            <dd class="font-heading text-sh-fg mt-1 truncate text-xl font-bold">
                {{ \Illuminate\Support\Number::abbreviate($stats['orders_count']) }}
            </dd>
        </div>

        <div class="min-w-0">
            <dt class="text-sh-fg-secondary truncate text-xs font-medium uppercase tracking-wide">
                {{ __('shopper::pages/discounts.stats.gross_revenue') }}
            </dt>
            <dd class="font-heading text-sh-fg mt-1 truncate text-xl font-bold">
                {{ $hasUsage ? shopper_money_format($stats['gross_revenue'], $currency) : '—' }}
            </dd>
        </div>

        <div class="min-w-0">
            <dt class="text-sh-fg-secondary truncate text-xs font-medium uppercase tracking-wide">
                {{ __('shopper::pages/discounts.stats.discount_cost') }}
            </dt>
            <dd class="font-heading mt-1 truncate text-xl font-bold text-amber-600 dark:text-amber-400">
                {{ $hasUsage ? '−'.shopper_money_format($stats['discount_cost'], $currency) : '—' }}
            </dd>
        </div>

        <div class="col-span-2 min-w-0">
            <dt class="text-sh-fg-secondary truncate text-xs font-medium uppercase tracking-wide">
                {{ __('shopper::pages/discounts.stats.aov_with') }}
            </dt>
            <dd class="font-heading text-sh-fg mt-1 truncate text-xl font-bold">
                {{ $stats['orders_count'] > 0 ? shopper_money_format($stats['aov_with'], $currency) : '—' }}
            </dd>
        </div>
    </dl>

    <p class="text-sh-fg-muted mt-4 text-xs">
        {{ __('shopper::pages/discounts.stats.disclaimer') }}
    </p>
</x-shopper::card>
