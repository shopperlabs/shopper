<x-shopper::container class="space-y-8">
    <x-shopper::section-heading
        :title="__('shopper::pages/attributes.menu')"
        :description="__('shopper::pages/attributes.description')"
    />

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-shopper::container>
