<x-shopper::layouts.app :title="__('shopper::words.actions_label.edit', ['name' => $product->name])">

    <livewire:shopper-products.edit :product="$product" />

</x-shopper::layouts.app>
