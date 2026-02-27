<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/reviews.menu')" />

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::REVIEWS_TABLE_BEFORE) }}

    <div class="mt-8">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::REVIEWS_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/reviews.menu')" link="reviews" />
</x-shopper::container>
