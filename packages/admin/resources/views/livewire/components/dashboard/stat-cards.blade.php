<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($this->cards as $card)
        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ $card['label'] }}
                    </span>
                    @svg($card['icon'], 'size-5 text-gray-700 dark:text-gray-300')
                </div>
            </x-slot:title>

            <p class="font-heading text-2xl font-bold text-gray-900 dark:text-white">
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
                        <span class="text-sm font-medium text-gray-400 dark:text-gray-500">0%</span>
                    @endif
                    <span class="text-sm text-gray-400 dark:text-gray-500">
                        {{ __('shopper::pages/dashboard.stats.vs_last_month') }}
                    </span>
                </div>

                <x-shopper::link
                    :href="$card['route']"
                    wire:navigate
                    class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    {{ __('shopper::pages/dashboard.stats.view_more') }}
                    <x-untitledui-arrow-narrow-right class="size-3.5" />
                </x-shopper::link>
            </div>
        </x-shopper::card>
    @endforeach
</div>
