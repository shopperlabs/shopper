<x-shopper::container class="py-5">
    <x-shopper::heading class="mt-5" :title="__('shopper::pages/orders.shipments')" />

    <div class="mt-8 space-y-4">
        <x-shopper::tabs-navigation
            wire:model="activeTab"
            :tabs="collect($this->getCachedTabs())->map(fn ($tab, $key): array => [
                'key' => $key,
                'label' => $tab->getLabel(),
                'icon' => $tab->getIcon(),
                'badge' => $tab->getBadge(),
                'badgeColor' => $tab->getBadgeColor(),
            ])->values()->all()"
            :active="$this->activeTab"
        />

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::SHIPMENTS_TABLE_BEFORE) }}

        {{ $this->table }}

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::SHIPMENTS_TABLE_AFTER) }}
    </div>
</x-shopper::container>
