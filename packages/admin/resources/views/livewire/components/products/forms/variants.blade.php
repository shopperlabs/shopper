<x-shopper::container>
    <x-shopper::section-heading
        :title="__('shopper::pages/products.variants.title')"
        :description="__('shopper::pages/products.variants.description')"
    />

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-shopper::container>
