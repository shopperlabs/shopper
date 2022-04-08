<x-shopper::layouts.app :title="__('Update discount code :code', ['code' => $discount->code])">

    <livewire:shopper-discounts.edit :discount="$discount" />

</x-shopper::layouts.app>
