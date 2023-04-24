<x-shopper::layouts.app :title="__('shopper::words.actions_label.show', ['name' => $customer->name])">

    <livewire:shopper-customers.show :customer="$customer" />

</x-shopper::layouts.app>
