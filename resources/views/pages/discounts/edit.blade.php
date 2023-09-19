<x-shopper::layouts.app :title="__('shopper::pages/discounts.actions.update', ['code' => $discount->code])">

    <livewire:shopper-discounts.edit :discount="$discount" />

</x-shopper::layouts.app>
