<div>
    <x-shopper::container class="space-y-8">
        <x-shopper::heading :title="__('shopper::pages/settings/payments.title')">
            <x-slot name="action">
                {{ $this->createPaymentAction }}
            </x-slot>
        </x-shopper::heading>

        {{ $this->table }}
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
