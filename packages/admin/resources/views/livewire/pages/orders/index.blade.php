<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/orders.menu')" />

    <div class="mt-8 space-y-4">
        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::INDEX_TABLE_BEFORE) }}

        {{ $this->table }}

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::INDEX_TABLE_AFTER) }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/orders.menu')" link="orders" />
</x-shopper::container>
