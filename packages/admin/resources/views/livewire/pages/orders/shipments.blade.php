<x-shopper::container class="py-5">
    <x-shopper::breadcrumb :back="route('shopper.orders.index')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.orders.index')"
            :title="__('shopper::pages/orders.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5" :title="__('shopper::pages/orders.shipments')" />

    <div class="mt-8 space-y-4">
        @php
            $tabs = $this->getCachedTabs();
        @endphp

        @if (count($tabs))
            <nav class="flex items-center gap-x-1 border-b border-gray-200 dark:border-white/10">
                @foreach ($tabs as $tabKey => $tab)
                    @php
                        $isActive = $this->activeTab === (string) $tabKey;
                        $badge = $tab->getBadge();
                        $badgeColor = $tab->getBadgeColor();
                        $icon = $tab->getIcon();
                    @endphp

                    <button
                        type="button"
                        wire:click="$set('activeTab', '{{ $tabKey }}')"
                        @class([
                            'group relative flex items-center gap-x-2 px-3 pb-3 pt-1 text-sm font-medium outline-none transition',
                            'text-primary-600 dark:text-primary-400' => $isActive,
                            'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => ! $isActive,
                        ])
                    >
                        @if ($icon)
                            <x-filament::icon
                                :icon="$icon"
                                @class([
                                    'size-5',
                                    'text-primary-600 dark:text-primary-400' => $isActive,
                                    'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400' => ! $isActive,
                                ])
                            />
                        @endif

                        <span>{{ $tab->getLabel() }}</span>

                        @if (filled($badge))
                            <x-filament::badge size="sm" :color="$badgeColor">
                                {{ $badge }}
                            </x-filament::badge>
                        @endif

                        @if ($isActive)
                            <span class="absolute inset-x-0 bottom-0 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                        @endif
                    </button>
                @endforeach
            </nav>
        @endif

        {{ $this->table }}
    </div>
</x-shopper::container>
