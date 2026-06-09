<div>
    <x-shopper::container class="space-y-8 py-5">
        <div class="space-y-2">
            <x-shopper::heading :title="__('shopper::pages/reviews.title')" />
            <p class="max-w-2xl text-sm text-sh-fg-muted">
                {{ __('shopper::pages/reviews.description') }}
            </p>
        </div>

        @php
            $stats = $this->stats;
            $hasReviews = $stats['total'] > 0;
        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-shopper::card>
                <x-slot:title>
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary text-sm font-medium">
                            {{ __('shopper::pages/reviews.stats.average') }}
                        </span>
                        <x-phosphor-sparkle-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                    </div>
                </x-slot:title>

                <p class="font-heading text-sh-fg text-3xl font-bold">
                    {{ $hasReviews ? number_format($stats['average'], 1) : '—' }}
                </p>

                <div class="mt-3 flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        @foreach ([1, 2, 3, 4, 5] as $star)
                            <x-heroicon-s-star
                                @class([
                                    'size-4 shrink-0',
                                    'text-yellow-400' => $stats['average'] >= $star,
                                    'text-sh-fg-muted' => $stats['average'] < $star,
                                ])
                                aria-hidden="true"
                            />
                        @endforeach
                    </div>

                    <p class="text-sh-fg-muted text-xs">
                        {{ $hasReviews
                            ? __('shopper::pages/reviews.stats.based_on', ['count' => \Illuminate\Support\Number::abbreviate($stats['total'])])
                            : __('shopper::pages/reviews.stats.no_data') }}
                    </p>
                </div>
            </x-shopper::card>

            <x-shopper::card>
                <x-slot:title>
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary text-sm font-medium">
                            {{ __('shopper::pages/reviews.stats.total') }}
                        </span>
                        <x-phosphor-list-star-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                    </div>
                </x-slot:title>

                <p class="font-heading text-sh-fg text-3xl font-bold">
                    {{ \Illuminate\Support\Number::abbreviate($stats['total']) }}
                </p>

                <p class="text-sh-fg-muted mt-3 text-xs">
                    @if ($stats['this_month'] > 0)
                        <span class="font-medium text-emerald-600 dark:text-emerald-400">
                            +{{ \Illuminate\Support\Number::abbreviate($stats['this_month']) }}
                        </span>
                        {{ __('shopper::pages/reviews.stats.last_30_days') }}
                    @else
                        {{ __('shopper::pages/reviews.stats.no_recent') }}
                    @endif
                </p>
            </x-shopper::card>

            <x-shopper::card>
                <x-slot:title>
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary text-sm font-medium">
                            {{ __('shopper::pages/reviews.stats.five_star') }}
                        </span>
                        <x-phosphor-shooting-star-duotone class="text-sh-fg-secondary size-5" aria-hidden="true" />
                    </div>
                </x-slot:title>

                <p class="font-heading text-sh-fg text-3xl font-bold">
                    {{ $stats['five_star_percent'] }}%
                </p>

                <p class="text-sh-fg-muted mt-3 text-xs">
                    {{ __('shopper::pages/reviews.stats.excellent') }}
                </p>
            </x-shopper::card>

            <x-shopper::card>
                <x-slot:title>
                    <div class="flex items-center justify-between">
                        <span class="text-sh-fg-secondary text-sm font-medium">
                            {{ __('shopper::pages/reviews.stats.pending') }}
                        </span>
                        <x-phosphor-warning-circle-duotone
                            @class([
                                'size-5',
                                'text-amber-500' => $stats['pending'] > 0,
                                'text-sh-fg-secondary' => $stats['pending'] === 0,
                            ])
                            aria-hidden="true"
                        />
                    </div>
                </x-slot:title>

                <p
                    @class([
                        'font-heading text-3xl font-bold',
                        'text-amber-600 dark:text-amber-400' => $stats['pending'] > 0,
                        'text-sh-fg' => $stats['pending'] === 0,
                    ])
                >
                    {{ \Illuminate\Support\Number::abbreviate($stats['pending']) }}
                </p>

                <p class="text-sh-fg-muted mt-3 text-xs">
                    {{ $stats['pending'] > 0
                        ? __('shopper::pages/reviews.stats.pending_description')
                        : __('shopper::pages/reviews.stats.pending_empty') }}
                </p>
            </x-shopper::card>
        </div>

        {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::REVIEWS_TABLE_BEFORE) }}

        <div class="lg:grid lg:grid-cols-4 lg:gap-6">
            <aside class="space-y-4 lg:sticky lg:top-4 lg:col-span-1 lg:self-start">
                <x-shopper::card>
                    <x-slot:title>
                        <span class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/reviews.breakdown.title') }}
                        </span>
                    </x-slot:title>

                    <div class="space-y-3">
                        @foreach ($this->ratingBreakdown as $row)
                            <div class="flex items-center gap-3 text-sm">
                                <span class="text-sh-fg-secondary flex w-7 shrink-0 items-center gap-1 text-xs font-semibold">
                                    {{ $row['rating'] }}
                                    <x-heroicon-s-star class="size-3 text-yellow-400" aria-hidden="true" />
                                </span>
                                <div class="bg-sh-muted h-1.5 flex-1 overflow-hidden rounded-full">
                                    <div
                                        class="h-full rounded-full bg-yellow-400 transition-all"
                                        style="width: {{ $row['percent'] }}%"
                                    ></div>
                                </div>
                                <span class="text-sh-fg-muted w-8 shrink-0 text-right text-xs tabular-nums">
                                    {{ number_format($row['count']) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </x-shopper::card>

                <x-shopper::card>
                    <x-slot:title>
                        <span class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/reviews.recommended.title') }}
                        </span>
                    </x-slot:title>

                    <div class="flex items-baseline gap-2">
                        <p class="font-heading text-sh-fg text-2xl font-bold">
                            @if ($hasReviews)
                                {{ $this->recommendedPercent }}%
                            @else
                                —
                            @endif
                        </p>
                        <x-untitledui-thumbs-up class="size-4 text-emerald-500" aria-hidden="true" />
                    </div>

                    <p class="text-sh-fg-muted mt-1 text-xs">
                        {{ $hasReviews
                            ? __('shopper::pages/reviews.recommended.description', ['percent' => $this->recommendedPercent])
                            : __('shopper::pages/reviews.recommended.empty') }}
                    </p>
                </x-shopper::card>
            </aside>

            <div class="mt-6 space-y-4 lg:col-span-3 lg:mt-0">
                <x-filament::tabs class="sh-tabs-underline">
                    <x-filament::tabs.item
                        :active="$activeTab === 'all'"
                        wire:click="$set('activeTab', 'all')"
                    >
                        {{ __('shopper::pages/reviews.tabs.all') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        :active="$activeTab === 'pending'"
                        wire:click="$set('activeTab', 'pending')"
                        :badge="$stats['pending'] > 0 ? $stats['pending'] : null"
                        badge-color="warning"
                    >
                        {{ __('shopper::pages/reviews.tabs.pending') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        :active="$activeTab === 'approved'"
                        wire:click="$set('activeTab', 'approved')"
                    >
                        {{ __('shopper::pages/reviews.tabs.approved') }}
                    </x-filament::tabs.item>
                </x-filament::tabs>

                <div>
                    {{ $this->table }}
                </div>
            </div>
        </div>

        {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::REVIEWS_TABLE_AFTER) }}

        <x-shopper::learn-more :name="__('shopper::pages/reviews.menu')" link="reviews" />
    </x-shopper::container>
</div>
