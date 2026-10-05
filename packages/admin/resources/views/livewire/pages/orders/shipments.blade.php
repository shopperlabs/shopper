<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/orders.shipments')" />

    <div class="mt-8 space-y-4">
        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::SHIPMENTS_TABLE_BEFORE) }}

        {{ $this->table }}

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::SHIPMENTS_TABLE_AFTER) }}
    </div>
</x-shopper::container>
