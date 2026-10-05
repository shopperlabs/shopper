<div>
    <div class="flex items-center justify-between gap-2">
        <h3 class="text-sh-fg text-lg font-semibold">
            {{ __('shopper::pages/products.menu') }}
        </h3>
        <div class="flex items-center space-x-3">
            <span class="text-sh-fg-muted text-sm font-medium whitespace-nowrap">
                {{ __('shopper::words.per_page') }}
            </span>
            <x-filament::input.wrapper aria-label="{{ __('shopper::words.per_page_items') }}">
                <x-filament::input.select wire:model.live="perPage">
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
    </div>

    <ul class="divide-sh-border mt-2 divide-y">
        @foreach ($items as $item)
            <li class="flex items-center justify-between py-3" wire:key="order-item-{{ $item->id }}">
                <div class="flex min-w-0 flex-1 items-center gap-2">
                    <img
                        class="size-6 shrink-0 rounded object-cover"
                        src="{{ $item->product->getThumbnailUrl() }}"
                        alt="{{ $item->name }}"
                    />
                    <p class="text-sh-fg truncate text-sm">
                        {{ $item->name }}
                    </p>
                    <span class="text-sh-fg-muted shrink-0 text-xs">&times; {{ $item->quantity }}</span>
                </div>
                <div class="flex shrink-0 items-center gap-2 pl-3">
                    @if ($item->fulfillment_status)
                        <x-filament::badge
                            size="sm"
                            :color="$item->fulfillment_status->getColor()"
                            :icon="$item->fulfillment_status->getIcon()"
                        >
                            {{ $item->fulfillment_status->getLabel() }}
                        </x-filament::badge>
                    @endif

                    <span class="text-sh-fg-secondary text-sm font-medium">
                        {{ shopper_money_format($item->total, $order->currency_code) }}
                    </span>
                </div>
            </li>
        @endforeach
    </ul>

    @if ($items->hasPages())
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @endif
</div>
