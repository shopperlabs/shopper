@props([
    'summary',
    'campaign' => null,
])

@php
    $hasContent = $summary['name'] || $summary['budget_label'] || $summary['starts_at'];

    $rows = [];

    if ($summary['name']) {
        $rows[] = ['label' => __('shopper::pages/campaigns.summary.rows.name'), 'value' => $summary['name']];
    }

    if ($summary['currency']) {
        $rows[] = [
            'label' => __('shopper::pages/campaigns.summary.rows.currency'),
            'value' => 'badge:' . $summary['currency'],
        ];
    }

    if ($summary['budget_label']) {
        $rows[] = ['label' => __('shopper::pages/campaigns.summary.rows.budget'), 'value' => $summary['budget_label']];
    }

    if ($summary['starts_at']) {
        $start = \Illuminate\Support\Carbon::parse($summary['starts_at'])->translatedFormat('Y-m-d');
        $end = $summary['ends_at']
            ? \Illuminate\Support\Carbon::parse($summary['ends_at'])->translatedFormat('Y-m-d')
            : null;
        $rows[] = [
            'label' => __('shopper::pages/campaigns.summary.rows.schedule'),
            'value' => $end ? $start . ' → ' . $end : $start,
        ];
    }
@endphp

<x-shopper::card>
    <x-slot:title>
        <span class="text-sh-fg-secondary text-xs font-semibold uppercase tracking-wide">
            {{ __('shopper::pages/campaigns.summary.title') }}
        </span>
    </x-slot:title>

    @if (! $hasContent && ! $campaign?->exists)
        <p class="text-sh-fg-muted text-sm">
            {{ __('shopper::pages/campaigns.summary.empty') }}
        </p>
    @else
        <dl class="space-y-3 text-sm">
            @foreach ($rows as $row)
                <div class="flex items-center gap-3">
                    <dt class="text-sh-fg-secondary w-24 shrink-0">{{ $row['label'] }}</dt>
                    <dd class="text-sh-fg min-w-0 flex-1 break-words">
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
                    {{ __('shopper::pages/campaigns.summary.rows.visibility') }}
                </dt>
                <dd class="min-w-0 flex-1">
                    @if ($campaign?->exists)
                        <x-filament::badge :color="$campaign->status->getColor()" :icon="$campaign->status->getIcon()">
                            {{ $campaign->status->getLabel() }}
                        </x-filament::badge>
                    @else
                        <x-filament::badge :color="$summary['is_active'] ? 'success' : 'gray'">
                            {{ $summary['is_active']
                                ? __('shopper::pages/campaigns.summary.visibility_public')
                                : __('shopper::pages/campaigns.summary.visibility_hidden') }}
                        </x-filament::badge>
                    @endif
                </dd>
            </div>
        </dl>
    @endif
</x-shopper::card>
