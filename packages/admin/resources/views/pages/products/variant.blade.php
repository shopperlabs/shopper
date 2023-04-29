<x-shopper::layouts.app :title=" __('shopper::pages/products.variants.variant_title', ['name' => $variant->name])">

    <livewire:shopper-products.variant :product="$product" :variant="$variant" :currency="shopper_currency()" />

</x-shopper::layouts.app>
