<div>
    <x-shopper::separator />

    <x-shopper::section-heading
        :title="__('shopper::pages/products.menu')"
        :description="$collection->isAutomatic() ? __('shopper::pages/collections.automatic_description') : __('shopper::pages/collections.manual_description')"
    />

    <div class="mt-6">
        {{ $this->table }}
    </div>
</div>
