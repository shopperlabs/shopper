<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($this->cards as $card)
        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ $card['label'] }}
                    </span>
                    @svg($card['icon'], 'size-5 text-sh-fg-secondary')
                </div>
            </x-slot:title>

            <p class="font-heading text-sh-fg text-2xl font-bold">
                {{ $card['value'] }}
            </p>

            <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    @if ($card['trend'] === 'up')
                        <x-untitledui-trend-up class="size-4 text-green-500" />
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">
                            {{ $card['change'] }}%
                        </span>
                    @elseif ($card['trend'] === 'down')
                        <x-untitledui-trend-down class="size-4 text-red-500" />
                        <span class="text-sm font-medium text-red-600 dark:text-red-400">
                            {{ $card['change'] }}%
                        </span>
                    @else
                        <span class="text-sh-fg-muted text-sm font-medium">0%</span>
                    @endif
                    <span class="text-sh-fg-muted text-sm">
                        {{ __('shopper::pages/dashboard.stats.vs_last_month') }}
                    </span>
                </div>

                <x-shopper::link
                    :href="$card['route']"
                    wire:navigate
                    class="text-sh-fg-muted hover:text-sh-fg inline-flex items-center gap-1 text-sm font-medium transition-colors"
                >
                    {{ __('shopper::pages/dashboard.stats.view_more') }}
                    <x-untitledui-arrow-narrow-right class="size-3.5" />
                </x-shopper::link>
            </div>
        </x-shopper::card>
    @endforeach
</div>
