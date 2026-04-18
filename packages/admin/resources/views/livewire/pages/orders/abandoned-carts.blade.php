<x-shopper::container class="py-5">
    <x-shopper::heading class="mt-5" :title="__('shopper::pages/orders.abandoned_carts.title')" />

    {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::ABANDONED_CARTS_TABLE_BEFORE) }}

    <div class="mt-8">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::ABANDONED_CARTS_TABLE_AFTER) }}
</x-shopper::container>
