<x-shopper::layouts.app :title="__('Customer :name', ['name' => $customer->name])">

    <livewire:shopper-customers.show :customer="$customer" />

</x-shopper::layouts.app>
