<x-shopper::layouts.app :title="__('shopper::words.actions_label.edit', ['name' => $collection->name])">

    <livewire:shopper-collections.edit :collection="$collection" />

</x-shopper::layouts.app>
