<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/reviews.menu')" />

    <div class="mt-8">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/reviews.menu')" link="reviews" />
</x-shopper::container>
