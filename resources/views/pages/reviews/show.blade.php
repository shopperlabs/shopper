<x-shopper::layouts.app :title="__('shopper::pages/products.reviews.view', ['product' => $review->reviewrateable->name])">

    <livewire:shopper-reviews.show :review="$review" />

</x-shopper::layouts.app>
