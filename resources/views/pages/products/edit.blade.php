<x-shopper::layouts.app :title="__('Update product :name', ['name' => $product->name])">

    <livewire:shopper-products.edit :product="$product" />

</x-shopper::layouts.app>
