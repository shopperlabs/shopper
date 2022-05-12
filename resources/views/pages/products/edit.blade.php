<x-shopper::layouts.app :title="__('shopper::messages.actions_label.edit', ['name' => $product->name])">

    <livewire:shopper-products.edit :product="$product" />

</x-shopper::layouts.app>
