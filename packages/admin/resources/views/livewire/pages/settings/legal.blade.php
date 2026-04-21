<div>
    <x-shopper::container>
        <x-shopper::heading :title="__('shopper::pages/settings/global.legal.title')" />
    </x-shopper::container>

    <div class="mt-10">
        <x-filament::tabs class="sh-tabs-underline">
            @foreach ($tabs as $tab)
                <x-filament::tabs.item
                    :active="$activeTab === $tab['key']"
                    wire:click="$set('activeTab', '{{ $tab['key'] }}')"
                >
                    {{ $tab['label'] }}
                </x-filament::tabs.item>
            @endforeach
        </x-filament::tabs>
    </div>

    <x-shopper::container class="mt-8">
        @foreach ($legals as $key => $legal)
            <div @class(['hidden' => $activeTab !== $key])>
                <livewire:shopper-settings.legal.form :$legal :key="$key" />
            </div>
        @endforeach
    </x-shopper::container>

    <x-shopper::learn-more :name="__('shopper::pages/settings/global.legal.title')" link="legal" />
</div>
