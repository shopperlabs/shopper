<x-shopper::layouts.app :title="__('shopper::messages.actions_label.show', ['name' => $customer->name])">

    <livewire:shopper-customers.show :customer="$customer" />

</x-shopper::layouts.app>
