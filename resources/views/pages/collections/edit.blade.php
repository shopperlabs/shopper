<x-shopper::layouts.app :title="__('shopper::messages.actions_label.edit', ['name' => $collection->name])">

    <livewire:shopper-collections.edit :collection="$collection" />

</x-shopper::layouts.app>
