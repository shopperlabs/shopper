<div>
    <div class="flex items-center justify-between">
        <h4 class="text-sh-fg text-sm leading-5 font-medium">
            {{ __('shopper::pages/products.quantity_inventory') }}
        </h4>
        <div class="ml-4 flex items-center">
            {{ $this->stockAction }}
        </div>
    </div>

    <x-filament-actions::modals />

    <div class="bg-sh-surface ring-sh-border mt-5 overflow-hidden rounded-xl ring-1">
        <table class="divide-sh-border min-w-full divide-y">
            <thead class="bg-sh-muted">
                <x-shopper::tables.table-head>
                    {{ __('shopper::pages/products.inventory_name') }}
                </x-shopper::tables.table-head>
                <x-shopper::tables.table-head class="text-right">
                    {{ __('shopper::words.available') }}
                </x-shopper::tables.table-head>
            </thead>
            <tbody class="divide-sh-border divide-y" x-max="1">
                @foreach ($this->inventories as $inventory)
                    <tr>
                        <x-shopper::tables.table-cell class="whitespace-no-wrap">
                            <div class="flex items-center gap-2">
                                {{ $inventory->name }}

                                @if ($inventory->is_default)
                                    <x-filament::badge color="gray">
                                        {{ __('shopper::words.default') }}
                                    </x-filament::badge>
                                @endif
                            </div>
                        </x-shopper::tables.table-cell>
                        <x-shopper::tables.table-cell class="text-right">
                            {{ $variant->stockInventory($inventory->id) }}
                        </x-shopper::tables.table-cell>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
