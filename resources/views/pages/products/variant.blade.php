<x-shopper::layouts.app :title=" __('Variants ~ :name', ['name' => $variant->name])">

    <livewire:shopper-products.variant :product="$product" :variant="$variant" :currency="shopper_currency()" />

</x-shopper::layouts.app>
