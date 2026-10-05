<x-shopper::tabs-navigation
    wire:model="activeTab"
    :tabs="collect($livewire->getCachedTabs())->map(fn ($tab, $key): array => [
        'key' => $key,
        'label' => $tab->getLabel(),
        'icon' => $tab->getIcon(),
        'badge' => $tab->getBadge(),
        'badgeColor' => $tab->getBadgeColor(),
    ])->values()->all()"
    :active="$livewire->activeTab"
    toolbar
/>
