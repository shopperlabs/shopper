<div>
    <x-shopper::container class="space-y-8">
        <x-shopper::heading :title="__('shopper::pages/settings/channels.title')" />

        {{ $this->table }}
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
