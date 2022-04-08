<x-shopper::layouts.app :title="__('Reviews for :product', ['product' => $review->reviewrateable->name])">

    <livewire:shopper-reviews.show :review="$review" />

</x-shopper::layouts.app>
